from selenium import webdriver
from selenium.webdriver.common.by import By
from time import sleep

class PrestaShopTester:
    def __init__(self, url_base):
        self.url_base = url_base
        self.web_driver = webdriver.Chrome()

    def open_home_page_and_click_all_products(self):
        self.web_driver.get(self.url_base)
        all_products_button = self.web_driver.find_element(By.XPATH, '//a[contains(@class, "all-product-link")]')
        all_products_button.click()

    def explore_products_in_first_subcategory(self):
        first_subcategory = self.web_driver.find_element(By.XPATH, '//ul[@class="subcategories-list"]/li[1]')
        first_subcategory.click()
        products_number = 2

        for product_id in range(0, products_number):
            try:
                # Znalezienie i kliknięcie w produkt bezpośrednio przez data-id-product
                product_link = self.web_driver.find_element(By.XPATH, f'//article[@data-id-product="{product_id + 1}"]')
                product_link.click()
                sleep(2)  # Czekanie na załadowanie strony produktu

                # Powrót do strony podkategorii
                self.web_driver.back()
                sleep(2)  # Czekanie na załadowanie strony podkategorii po powrocie
            except Exception as e:
                print(f"Produkt o ID {product_id} nie istnieje lub wystąpił błąd:", e)
                break

        self.web_driver.back()

        second_podcategory = self.web_driver.find_element(By.XPATH, '//ul[@class="subcategories-list"]/li[2]')
        second_podcategory.click()

        sleep(2)

        for id in range(0, products_number):
            try:
                # Znalezienie i kliknięcie w produkt bezpośrednio przez data-id-product
                product_link = self.web_driver.find_element(By.XPATH, f'//article[@class="product-miniature js-product-miniature"])[{id + 1}]')
                product_link.click()
                sleep(2)  # Czekanie na załadowanie strony produktu

                # Powrót do strony podkategorii
                self.web_driver.back()
                sleep(2)  # Czekanie na załadowanie strony podkategorii po powrocie
            except Exception as e:
                print(f"Produkt o ID {id} nie istnieje lub wystąpił błąd:", e)
                break

    def close_browser(self):
        self.web_driver.close()

# Użycie klasy
tester = PrestaShopTester("http://localhost:8080")
tester.open_home_page_and_click_all_products()
tester.explore_products_in_first_subcategory()
tester.close_browser()
