# Instalacja chrome-driver

Bilbioteka Selenium potrzebuje zainstalowanej wtyczki do wybranej przeglądarki, aby poprawnie działać. Aby ułatwić proces instalacji napisałem skrypt w bashu o nazwie `driver.sh`, który za pomocą jednej komendy pobierze oraz umieści pobrany sterownik na dysku w folderze `/usr/bin/`. Aby uruchomić skrypt należy będąc w katalogu `/tests` nadać mu uprawnienia, a następnie uruchomić go, podając jako argument swoją werjsę google chrome. Relizują to poniższe komendy, wpisane po kolei.

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
