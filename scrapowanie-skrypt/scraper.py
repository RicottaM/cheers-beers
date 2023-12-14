import json
import os
import requests
from bs4 import BeautifulSoup

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
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    products = soup.find_all('article', class_='z5x6ht')
    product_details = []

    for product in products:
        image_tag = product.find('img', class_='sDq_FX')
        image_url = image_tag['src'] if image_tag else 'Brak zdjęcia'
        brand_tag = product.find('h3', class_='FtrEr_')
        brand = brand_tag.text.strip() if brand_tag else 'Brak marki'
        name_tag = product.find('h3', class_='sDq_FX')
        name = name_tag.text.strip() if name_tag else 'Brak nazwy'
        price_tag = product.find('p', class_='sDq_FX')
        price = price_tag.text.strip() if price_tag else 'Brak ceny'
        sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']

        product_details.append({
            'department': department,
            'category': category,
            'subcategory': subcategory,
            'image': image_url,
            'brand': brand,
            'name': name,
            'price': price,
            'sizes': sizes
        })

    return product_details

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

for department, categories in departments.items():
    for category_url in categories:
        category_name = category_url.split('/')[-2]
        subcategories = scrape_subcategories(category_url)
        
        for subcategory_name, subcategory_url in subcategories:
            full_subcategory_url = 'https://www.zalando.pl' + subcategory_url if subcategory_url.startswith('/') else subcategory_url
            products = scrape_product_details(full_subcategory_url, department, category_name, subcategory_name)

            directory_path = f'./scrapowanie-wynik/{department}/{category_name}/{subcategory_name}'
            json_file_path = f'{directory_path}/produkty.json'

            save_to_json(products, json_file_path)
            print(f'Dane zapisane do {json_file_path}')