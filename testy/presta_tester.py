import random
import string
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
    
    def add_product_to_cart(self, clicks_range=2):
        try:
            # Losowanie liczby kliknięć w przycisk zwiększania ilości
            clicks = random.randint(0, clicks_range)
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

    def search_and_add_random_product(self, search_query):
        # Wpisanie wartości do pola wyszukiwania i wciskanie Enter
        search_field = self.web_driver.find_element(By.NAME, "s")
        search_field.send_keys(search_query)
        search_field.send_keys(Keys.ENTER)
        sleep(2)

        # Znalezienie dostępnych produktów i losowe wybranie jednego
        products = self.web_driver.find_elements(By.CSS_SELECTOR, "article.product-miniature")
        if products:
            random_product = random.choice(products)
            random_product.find_element(By.CSS_SELECTOR, "a.thumbnail").click()
            sleep(2)

            # Dodanie wybranego produktu do koszyka
            self.add_product_to_cart(0)

        # Powrót na stronę główną
        self.web_driver.get(self.url_base)
        sleep(2)

    def add_10_products(self):
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
                #sleep(2)  # Czekanie na załadowanie strony produktu

                self.add_product_to_cart()
                self.web_driver.get(self.url_base)
                #sleep(2) 
            except Exception as e:
                print(f"Wystąpił błąd podczas odwiedzania produktu: ", e)

    def remove_random_products_from_cart(self, number_of_products=3):
        # Kliknięcie w koszyk
        cart_icon = self.web_driver.find_element(By.CSS_SELECTOR, "div.blockcart a")
        cart_icon.click()
        sleep(2)  # Czekanie na załadowanie zawartości koszyka

        for _ in range(number_of_products):
            # Pobranie listy produktów w koszyku
            cart_items = self.web_driver.find_elements(By.CSS_SELECTOR, "ul.cart-items li.cart-item")

            if not cart_items:
                print("Brak produktów w koszyku.")
                return

            # Losowe wybranie produktu do usunięcia
            product_to_remove = random.choice(cart_items)

            # Kliknięcie w link "Usuń" dla produktu
            remove_link = product_to_remove.find_element(By.CSS_SELECTOR, "a.remove-from-cart")
            remove_link.click()
            sleep(3)  # Czekanie na usunięcie produktu z koszyka

        # Powrót na stronę główną
        self.web_driver.get(self.url_base)

    def register_new_account(self):
        # Kliknięcie w link "Zaloguj się"
        login_link = self.web_driver.find_element(By.CSS_SELECTOR, "a[title='Zaloguj się do swojego konta klienta']")
        login_link.click()
        sleep(2)

        # Kliknięcie w link "Rejestracja"
        register_link = self.web_driver.find_element(By.CSS_SELECTOR, "a[data-link-action='display-register-form']")
        register_link.click()
        sleep(2)

        # Wypełnienie formularza rejestracji
        # Wybór płci
        gender_option = random.choice(["field-id_gender-1", "field-id_gender-2"])
        self.web_driver.find_element(By.ID, gender_option).click()
        sleep(1)

        # Imię
        first_name = "Kleopatra"
        self.web_driver.find_element(By.ID, "field-firstname").send_keys(first_name)
        sleep(1)

        # Nazwisko
        last_name = "Rumcajs"
        self.web_driver.find_element(By.ID, "field-lastname").send_keys(last_name)
        sleep(1)

        prefix = random.randint(1, 1234)

        # E-mail
        email = f"{first_name}{prefix}.{last_name}@example.com"
        self.web_driver.find_element(By.ID, "field-email").send_keys(email)
        sleep(1)

        # Hasło
        password = "KochamMame123!"
        self.web_driver.find_element(By.ID, "field-password").send_keys(password)
        sleep(1)

        # Zgoda na przetwarzanie danych osobowych
        self.web_driver.find_element(By.NAME, "customer_privacy").click()
        sleep(1)

        # Akceptacja warunków użytkowania
        self.web_driver.find_element(By.NAME, "psgdpr").click()
        sleep(1)

        # Kliknięcie przycisku "Zapisz"
        self.web_driver.find_element(By.CSS_SELECTOR, "button.form-control-submit").click()
        sleep(1)

    def complete_order(self):
        # Kliknięcie w koszyk
        cart_icon = self.web_driver.find_element(By.CSS_SELECTOR, "div.blockcart a")
        cart_icon.click()
        sleep(2)

        # Kliknięcie w "Przejdź do realizacji zamówienia"
        proceed_to_checkout_link = self.web_driver.find_element(By.CSS_SELECTOR, "a.btn.btn-primary")
        proceed_to_checkout_link.click()
        sleep(2)

    def fill_delivery_address(self):
        # Wypełnienie formularza adresowego
        self.web_driver.find_element(By.ID, "field-alias").send_keys("Moje mieszkanie")
        sleep(1)
        self.web_driver.find_element(By.ID, "field-firstname").send_keys("Kleopatra")
        sleep(1)
        self.web_driver.find_element(By.ID, "field-lastname").send_keys("Rumcajs")
        sleep(1)
        self.web_driver.find_element(By.ID, "field-address1").send_keys("Ulica Testowa 1")
        sleep(1)
        self.web_driver.find_element(By.ID, "field-postcode").send_keys("52-572")
        sleep(1)
        self.web_driver.find_element(By.ID, "field-city").send_keys("Testowo")
        sleep(1)
        self.web_driver.find_element(By.ID, "field-phone").send_keys("512432432")

        # Kliknięcie "Dalej"
        self.web_driver.find_element(By.CSS_SELECTOR, "button[name='confirm-addresses']").click()
        sleep(2)

    def choose_delivery_option(self):
        # Wybranie opcji dostawy - "Odbiór w sklepie"
        delivery_option_element = self.web_driver.find_element(By.ID, "delivery_option_1")
        self.web_driver.execute_script("arguments[0].scrollIntoView();", delivery_option_element)
        self.web_driver.execute_script("arguments[0].click();", delivery_option_element)
        sleep(1)

        # Kliknięcie "Dalej"
        continue_button = self.web_driver.find_element(By.CSS_SELECTOR, "button[name='confirmDeliveryOption']")
        self.web_driver.execute_script("arguments[0].scrollIntoView();", continue_button)
        self.web_driver.execute_script("arguments[0].click();", continue_button)
        sleep(2)

    def choose_payment_option(self):
        # Wybranie opcji płatności - "Zapłać gotówką przy odbiorze"
        self.web_driver.find_element(By.ID, "payment-option-2").click()

        # Akceptacja warunków świadczenia usług
        self.web_driver.find_element(By.ID, "conditions_to_approve[terms-and-conditions]").click()

        # Kliknięcie "Złóż zamówienie"
        self.web_driver.find_element(By.CSS_SELECTOR, "button.btn.btn-primary.center-block").click()
        sleep(2)



# Użycie klasy
tester = PrestaShopTester("http://localhost:8080")
tester.open_start_page()
# tester.add_10_products()
tester.search_and_add_random_product("półbuty")
# tester.remove_random_products_from_cart()
tester.register_new_account()
tester.complete_order()
tester.fill_delivery_address()
tester.choose_delivery_option()
tester.choose_payment_option()