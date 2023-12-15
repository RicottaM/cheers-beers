import random
from selenium.webdriver.chrome.options import Options
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from time import sleep

class PrestaShopTester:
    def __init__(self, url_base):
        chrome_options = Options()
        chrome_options.add_argument("--ignore-certificate-errors")
        self.web_driver = webdriver.Chrome(options=chrome_options)
        self.url_base = url_base

    def open_start_page(self):
        self.web_driver.get(self.url_base)
        sleep(2)
    
    def add_product_to_cart(self):
        try:
            # Losowanie liczby kliknięć w przycisk zwiększania ilości
            clicks = random.randint(0, 2)
            print(f"Zwiększanie ilości produktu o {clicks}.")

            # Znalezienie przycisku do zwiększania ilości i kliknięcie odpowiednią ilość razy
            increase_quantity_button = self.web_driver.find_element(By.CSS_SELECTOR, "button.bootstrap-touchspin-up")
            for _ in range(clicks):
                increase_quantity_button.click()
                sleep(0.5)  # krótkie opóźnienie pomiędzy kliknięciami

            sleep(2)
            # Kliknięcie w przycisk "Dodaj do koszyka"
            add_to_cart_button = self.web_driver.find_element(By.CSS_SELECTOR, "button.add-to-cart")
            add_to_cart_button.click()
            sleep(2)  # Czekanie na odpowiedź strony

        except Exception as e:
            print(f"Wystąpił błąd podczas dodawania produktu do koszyka: ", e)

    def explore_specific_products(self):
        product_xpaths = [
            "//a[@href=\"https://localhost/kobiety/379-okulary-z-filtrem-swiatla-niebieskiego.html\"]",
            "//a[@href=\"https://localhost/kobiety/300-alaska---kurtka-puchowa.html\"]",
            "//a[@href=\"https://localhost/kobiety/211-dol-od-bikini.html\"]",
            "//a[@href=\"https://localhost/kobiety/341-jibbitz-dyed-5-unisex---inne-akcesoria.html\"]",
            "//a[@href=\"https://localhost/kobiety/259-bluzka-z-dlugim-rekawem.html\"]",
            "//a[@href=\"https://localhost/kobiety/395-szal.html\"]",
            "//a[@href=\"https://localhost/kobiety/247-spodnie-materialowe.html\"]",
            "//a[@href=\"https://localhost/kobiety/73-sandaly.html\"]",
            "//a[@href=\"https://localhost/kobiety/306-sukienka-dzianinowa.html\"]",
            "//a[@href=\"https://localhost/kobiety/285-szorty.html\"]"
        ]

        for xpath in product_xpaths:
            print(xpath)
            try:
                # Znalezienie i kliknięcie w link produktu
                product_link = self.web_driver.find_element(By.XPATH, xpath)
                product_link.click()
                sleep(2)  # Czekanie na załadowanie strony produktu

                self.add_product_to_cart()
                self.web_driver.get(self.url_base)
                sleep(2) 
            except Exception as e:
                print(f"Wystąpił błąd podczas odwiedzania produktu: ", e)

    def close_browser(self):
        self.web_driver.close()

# Użycie klasy
tester = PrestaShopTester("http://localhost:8080")
tester.open_start_page()
tester.explore_specific_products()
tester.close_browser()
