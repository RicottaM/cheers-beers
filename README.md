# Instrukcja Obsługi Aplikacji

## Korzystanie z Docker Compose

1. Zainstaluj Docker Desktop na systemie Windows. Aby skorzystać z Docker wraz z WSL, postępuj zgodnie z poradnikiem: [Instrukcja instalacji Docker z WSL](https://www.youtube.com/watch?v=cMyoSkQZ41E). Jest też dużo innych poradników zarówno po angielsku, jak i po polsku ;)

2. Aby korzystać z `docker-compose` należy za pomocą Microsoft Power Shell przejść do katalogu aplikacji, zawierającego plik `docker-compose.yml`, który odpowiedzialny jest za uruchomienie serwera aplikacji oraz bazy danych.

3. Wpisz polecenie `docker-compose up -d`, aby uruchomić kontenery bazy danych oraz aplikacji. Użycie flagi `-d` pozwala na uruchomienie w tle, ignorując logi. 

## Uruchamianie i Modyfikowanie Aplikacji Lokalnie

1. Po pobraniu pliku `docker-compose` z repozytorium, przejdź do katalogu aplikacji za pomocą komendy `cd fashionables/aplikacja`. Aby nawigować za pomocą komend w systemie Windows, użyj konsoli Windows PowerShell.

2. W katalogu aplikacji użyj polecenia `docker-compose up -d` w celu uruchomienia aplikacji w kontenerze Docker. 

3. Aby zobaczyć stronę klienta aplikacji, otwórz przeglądarkę i przejdź pod adres [localhost:8080](http://localhost:8080). Ukaże się strona instalacyjna, której pola należy wypełnić ustawiając język polski, nazwę domeny `fashionables` oraz podając swoje własne dane do logowania jako admin. Następnie należy wprowadzić poprawne dane do łączenia się z bazą danych. Spytaj o nie na grupie. 

4. Aby zacząć pracę nad aplikacją, należy dodatkowo zmienić nazwę folderu `admin` w plikach aplikacji na nazwę `admin_fashionables` oraz usunąć folder `install`, aby uniknąć ponownej instalacji strony przy kolejnym uruchomieniu kontenerów. Możesz to zrobić ręcznie w eksploatarze plików, ponieważ `docker-compose.yml` zaciąga pliki z kontenera do katalogu projektu. W przeglądarce odwiedź stronę administracyjną pod adresem [localhost:8080/admin_fashionables](http://localhost:8080/admin) i zaloguj się.

5. Aby zakończyć pracę nad projektem zatrzymaj wszystkie kontenery komendą `docker-compose down`.
