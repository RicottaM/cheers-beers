import json
import os
import re
import requests
from bs4 import BeautifulSoup
from urllib.parse import unquote

def save_to_json(data, path):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=4)

def scrape_subcategories(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    subcategories_list = soup.find('ul', class_='ODGSbs')
    if not subcategories_list:
        return [("Brak podkategorii", url)]
    subcategories = subcategories_list.find_all('li', class_='_4oK5GO')
    return [(subcategory.find('span').text, subcategory.find('a')['href']) for subcategory in subcategories]

def scrape_product_details(url, department, category, subcategory):
    try:
        response = requests.get(url)
        if response.status_code != 200:
            print("Nie udało się pobrać strony:", url)
            return []

        soup = BeautifulSoup(response.content, 'html.parser')
        products = soup.find_all('article', class_='z5x6ht')
        product_details = []

        for product in products:
            product_link = product.find('a', class_='_LM JT3_zV CKDt_l CKDt_l LyRfpJ')['href']
            
            if 'outfits' in product_link:
                continue
        
            product_response = requests.get(product_link)
            if product_response.status_code != 200:
                print("Nie udało się pobrać strony produktu:", product_link)
                continue

            product_soup = BeautifulSoup(product_response.content, 'html.parser')
        
            image_containers = product_soup.find_all('div', class_='JT3_zV mo6ZnF Zhr-fS')
            image_urls = [img['src'] for container in image_containers for img in container.find_all('img')]

            brand_tag = product.find('h3', class_='FtrEr_')
            brand = brand_tag.text.strip() if brand_tag else 'Brak marki'

            name_tag = product.find('h3', class_='sDq_FX')
            name = name_tag.text.strip() if name_tag else 'Brak nazwy'

            price_tag = product.find('p', class_='sDq_FX')
            price = price_tag.text.strip() if price_tag else 'Brak ceny'
            price = price.replace(',', '.')
            price = re.sub(r'[^\d.]', '', price)
            

            sizes_script = product_soup.find('script', text=re.compile('"size":".*?"'))
            sizes = list(set(re.findall('"size":"(.*?)"', sizes_script.string))) if sizes_script else ['Brak rozmiarów']

            product_html_content = product_response.text
            
            product_matches = re.findall(r'{"__typename":"ProductAttributeKeyValue","key":"(.*?)","value":"(.*?)"}', product_html_content)
            additional_info = {}
            # Dodawanie znalezionych danych do additional_info
            for key, value in product_matches:
                additional_info[unquote(key)] = unquote(value)

            product_details.append({
                'department': department,
                'category': category,
                'subcategory': subcategory,
                'images': image_urls,
                'brand': brand,
                'name': name,
                'price': price,
                'sizes': sizes,
                'additional_info': additional_info
            })

        return product_details
    except Exception as e:
        print(f"Wystąpił błąd podczas przetwarzania URL: {url}")
        print(str(e))
        return []

departments = {
    'Kobiety': [
        'https://www.zalando.pl/odziez-damska/',
        'https://www.zalando.pl/obuwie-damskie/',
        'https://www.zalando.pl/sport-kobiety/',
        'https://www.zalando.pl/kobiety-akcesoria/',
        'https://www.zalando.pl/beauty-kobiety/'
    ],
    'Mężczyźni': [
        'https://www.zalando.pl/odziez-meska/',
        'https://www.zalando.pl/obuwie-meskie/',
        'https://www.zalando.pl/sport-mezczyzni/',
        'https://www.zalando.pl/mezczyzni-akcesoria/',
        'https://www.zalando.pl/kosmetyki-mezczyzni/'
    ],
    'Dzieci': [
        'https://www.zalando.pl/odziez-dziecieca/',
        'https://www.zalando.pl/obuwie-dzieciece/',
        'https://www.zalando.pl/sport-dzieci/',
        'https://www.zalando.pl/bielizna-dziecieca/',
        'https://www.zalando.pl/dzieci-akcesoria/'
    ]
}

category_mapping = {
    'odziez-dziecieca': 'Odzież Dziecięca',
    'obuwie-dzieciece': 'Obuwie Dziecięce',
    'sport-dzieci': 'Sport Dzieci',
    'bielizna-dziecieca': 'Bielizna Dziecięca',
    'dzieci-akcesoria': 'Akcesoria Dziecięce',
    'odziez-damska': 'Odzież Damska',
    'obuwie-damskie': 'Obuwie Damskie',
    'sport-kobiety': 'Sport Kobiety',
    'akcesoria-kobiety': 'Akcesoria Kobiety',
    'beauty-kobiety': 'Beauty Kobiety',
    'odziez-meska': 'Odzież Męska',
    'obuwie-meskie': 'Obuwie Męskie',
    'sport-mezczyzni': 'Sport Mężczyźni',
    'akcesoria-mezczyzni': 'Akcesoria Mężczyźni',
    'kosmetyki-mezczyzni': 'Kosmetyki Mężczyźni'
}

for department, categories in departments.items():
    for category_url in categories:
        category_name = category_url.split('/')[-2]
        category_name = category_mapping.get(category_name, category_name)
        subcategories = scrape_subcategories(category_url)
        
        for subcategory_name, subcategory_url in subcategories:
            full_subcategory_url = 'https://www.zalando.pl' + subcategory_url if subcategory_url.startswith('/') else subcategory_url
            products = scrape_product_details(full_subcategory_url, department, category_name, subcategory_name)

            directory_path = f'../scrapowanie-wynik/{department}/{category_name}/{subcategory_name}'
            json_file_path = f'{directory_path}/produkty.json'

            save_to_json(products, json_file_path)
            print(f'Dane zapisane do {json_file_path}')