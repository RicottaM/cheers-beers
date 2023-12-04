# Instrukcja Obsługi Aplikacji

## Korzystanie z Docker Compose

1. Zainstaluj Docker Desktop na systemie Windows. Aby skorzystać z Docker wraz z WSL, postępuj zgodnie z poradnikiem: [Instrukcja instalacji Docker z WSL](https://www.youtube.com/watch?v=cMyoSkQZ41E). Jest też dużo innych poradników zarówno po angielsku, jak i po polsku ;)

2. Aby korzystać z `docker-compoe` należy za pomocą Microsoft Power Shell przejść do katalogu aplikacji, zawierającego plik `docker-compose.yml`, który odpowiedzialny jest za uruchomienie serwera aplikacji oraz bazy danych.

3. Wpisz polecenie `docker-compose up -d`, aby uruchomić kontenery bazy danych oraz aplikacji. Użycie flagi `-d` pozwala na uruchomienie w tle, ignorując logi. 

## Uruchamianie i Modyfikowanie Aplikacji Lokalnie

1. Po pobraniu kodu z repozytorium, przejdź do katalogu aplikacji.

> cd fashionables/aplikacja 

2. W katalogu aplikacji użyj polecenia `docker-compose up -d` w celu uruchomienia aplikacji w kontenerze Docker.

> docker-compose up -d

3. Aby zobaczyć stronę klienta aplikacji, otwórz przeglądarkę i przejdź pod adres [localhost:8080](http://localhost:8080).

4. W celu modyfikowania aplikacji, odwiedź stronę administracyjną pod adresem [localhost:8080/admin](http://localhost:8080/admin) i zaloguj się.

