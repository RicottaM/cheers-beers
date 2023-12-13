import os
import csv
import re
import json
import random

def replace_polish_chars(text):
    text = re.sub(r'[ą]', 'a', text)
    text = re.sub(r'[ć]', 'c', text)
    text = re.sub(r'[ę]', 'e', text)
    text = re.sub(r'[ł]', 'l', text)
    text = re.sub(r'[ń]', 'n', text)
    text = re.sub(r'[ó]', 'o', text)
    text = re.sub(r'[ś]', 's', text)
    text = re.sub(r'[źż]', 'z', text)
    return text

def generate_csv_files(root_path):
    departments = []
    categories = []
    subcategories = []
    products = []

    department_id = 1000
    category_id = 2000
    subcategory_id = 3000
    product_id = 4000

    for dirpath, dirnames, filenames in os.walk(root_path):
        for filename in filenames:
            if filename.endswith('.json'):
                json_file = os.path.join(dirpath, filename)
                with open(json_file, 'r') as file:
                    data = json.load(file)

                for item in data:
                    department = item['department']
                    category = item['category']
                    subcategory = item['subcategory']
                    product = item['name']
                    price = float(item['price']) if item['price'] else 0.0
                    brand = item['brand']
                    images = ','.join(item['images'])
                    sizes = ','.join(item['sizes'])
                    additional_info = item['additional_info']

                    if not all([department, category, subcategory, product, brand, images, sizes]):
                        continue

                    if department not in [d['Name *'] for d in departments]:
                        departments.append({
                            "Category ID": str(department_id),
                            "Active (0/1)": "1",
                            "Name *": department,
                            "URL rewritten": replace_polish_chars(department.replace(' ', '-').lower())
                        })
                        department_id += 1

                    if category not in [c['Name *'] for c in categories]:
                        categories.append({
                            "Category ID": str(category_id),
                            "Active (0/1)": "1",
                            "Name *": category,
                            "Parent category": department,
                            "URL rewritten": replace_polish_chars(category.replace(' ', '-').lower())
                        })
                        category_id += 1

                    if subcategory not in [s['Name *'] for s in subcategories]:
                        subcategories.append({
                            "Category ID": str(subcategory_id),
                            "Active (0/1)": "1",
                            "Name *": subcategory,
                            "Parent category": category,
                            "URL rewritten": replace_polish_chars(subcategory.replace(' ', '-').lower())
                        })
                        subcategory_id += 1

                    if subcategory_id % 2 == 0:
                        products.append({
                            "Product ID": str(product_id),
                            "Active (0/1)": "1",
                            "Name *": product,
                            "Categories (x,y,z...)": f"{department},{category},{subcategory}",
                            "Price tax excluded": str(round(price * 0.77, 2)),
                            "Tax rules ID": "1",
                            "On sale (0/1)": "1",
                            "Manufacturer": brand,
                            "Quantity": str(random.randint(1, 10)),
                            "Description": "<br>".join([f"<b>{key}:</b> {value}" for key, value in additional_info.items()]),
                            "URL rewritten": replace_polish_chars(product.replace(' ', '-').lower()),
                            "Image URLs (x,y,z...)": images,
                            "Rozmiary (cechy)": sizes
                        })
                        product_id += 1

    csv_files = [('../scrapowanie-wynik/CSV/departments.csv', departments), ('../scrapowanie-wynik/CSV/categories.csv', categories), 
                 ('../scrapowanie-wynik/CSV/subcategories.csv', subcategories), ('../scrapowanie-wynik/CSV/products_resized.csv', products)]
    csv_headers = [["Category ID", "Active (0/1)", "Name *", "URL rewritten"],
                   ["Category ID", "Active (0/1)", "Name *", "Parent category", "URL rewritten"],
                   ["Category ID", "Active (0/1)", "Name *", "Parent category", "URL rewritten"],
                   ["Product ID", "Active (0/1)", "Name *", "Categories (x,y,z...)", "Price tax excluded", 
                    "Tax rules ID", "On sale (0/1)", "Manufacturer", "Quantity", "Description", 
                    "URL rewritten", "Image URLs (x,y,z...)", "Rozmiary (cechy)"]]

    for i in range(len(csv_files)):
        with open(csv_files[i][0], mode='w', newline='', encoding='utf-8') as file:
            writer = csv.DictWriter(file, fieldnames=csv_headers[i], delimiter=';')
            writer.writeheader()
            for row in csv_files[i][1]:
                writer.writerow(row)

generate_csv_files('../scrapowanie-wynik')
