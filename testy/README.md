**Instalacja chrome-driver**
Aby uruchomić skrypt należy będąc w katalogu `/tests` nadać mu uprawnienia, a następnie odpalić skrypt instalujący `chrome-driver`, podając jako argument swoją werjsę google chrome. 

1. Uzyskanie wersji google-chrome:
> google-chrome --version

Wynikiem będzie nazwa przeglądarki oraz jej wersja. Jako argument podajemy samą wersję np. 119.0.6045.105.

2. Nadanie uprawnień skryptowi:
> chmod +x driver.sh
3. Uruchomienie skryotu
> ./driver.sh wersja-google 
4. Jeżeli nie mamy pobranego selenium to wykonujemy instalację:
> pip3 install -U selenium
5. Uruchamiamy testy:
> python3 selenium_tests.py