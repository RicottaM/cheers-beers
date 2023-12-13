# Instalacja chrome-driver

Aby uruchomić skrypt należy będąc w katalogu `/tests` nadać mu uprawnienia, a następnie odpalić skrypt instalujący `chrome-driver`, podając jako argument swoją werjsę google chrome. Relizują to poniższe komendy, wpisane po kolei.

### Uzyskanie wersji google-chrome
> google-chrome --version

Wynikiem będzie nazwa przeglądarki oraz jej wersja. Jako argument do skryptu podajemy samą wersję np. 119.0.6045.105.

### Nadanie uprawnień skryptowi
> chmod +x driver.sh

###  Uruchomienie skryptu
> ./driver.sh wersja-google 

### Jeżeli nie mamy pobranego selenium to wykonujemy instalację
> pip3 install -U selenium

### Uruchamiamy testy
> python3 selenium_tests.py
