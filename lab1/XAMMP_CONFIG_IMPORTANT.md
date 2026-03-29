# Konfiguracja środowiska PHPStorm oraz XAMPP (Ważne)

### Konfiguracja na komputerach uczelnianych PJATK

1. Pod adresem `\\gda-nas\pub` (który trzeba ręcznie dodać jako ścieżkę sieciową) znajduje się plik `xampp-portable-windows-x64-8.1.2-0-VS16` w katalogu `pub\apps`. Można go również pobrać pod adresem: [SourceForge XAMPP](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.1.2/).
2. Rozpakuj plik na partycji `3-4 Users (D:)` lub na `Virtualki (V:)`.
3. Po rozpakowaniu wejdź do katalogu `xampp` i uruchom program `setup_xampp.bat`.
4. Następnie uruchom `xampp-control.exe`.
5. W zakładce **Apache** kliknij przycisk **Config**, a następnie wybierz plik `httpd-ssl.conf`.
   ![Konfiguracja SSL](https://ucarecdn.stepik.net/429fb59e-96a7-49cd-9f9b-dae5c8b6957a/)
6. W pliku `httpd-ssl.conf` odnajdź linię `Listen 443` i zamień ją na `Listen 8080`.
7. Teraz po naciśnięciu przycisku **Start** powinien uruchomić się serwer Apache. Dostęp do niego uzyskasz pod adresem `localhost:8080`.
   ![Apache Start](https://ucarecdn.stepik.net/4422ab97-af49-4401-8b95-13fee097fbcd/)
8. Skonfiguruj plik `php.ini`, klikając w **Config** serwera Apache i wybierając **PHP (php.ini)**.
   ![PHP.ini Config](https://ucarecdn.stepik.net/0ff9e8be-972d-485f-bd53-906688eafde3/)
9. W pliku `php.ini` wprowadź następujące zmiany:
   - Odnajdź linię `extension_dir = "\\xampp\php\ext"` i zamień na `extension_dir = "D:\\xampp\php\ext"`.
   - Odnajdź linię `browscap = "\\xampp\php\extras\browscap.ini"` i zamień na `browscap = "D:\\xampp\php\extras\browscap.ini"`.
10. **PHPStorm:**
    - Aktywuj licencję: Wybierz **Activate License**, następnie **Discover Server** i **Activate**.
    - Utwórz nowy projekt o dowolnej nazwie.
    - Przejdź do ustawień (**ALT+CTRL+S**), wybierz zakładkę **PHP**. Kliknij `...` w linii **CLI Interpreter**.
      ![Ustawienia PHP](https://ucarecdn.stepik.net/19b41d40-d15f-46ec-b650-0710f53243b1/)
    - Kliknij **Add** (znak `+`) i wybierz **Local Path to Interpreter**.
      ![Dodaj Interpreter](https://ucarecdn.stepik.net/bb0384a0-1b8f-401d-ae9b-3c00216ab281/)
      ![Wybór Ścieżki](https://ucarecdn.stepik.net/efbf28a5-75a1-47c6-b818-99560585ccdc/)
    - Wskaż plik `php.exe` znajdujący się w katalogu `xampp\php`.
      ![Wybór Pliku](https://ucarecdn.stepik.net/5db9b564-ddb4-47cb-a3e2-d2da071c9826/)
11. Utwórz plik `index.php` i wpisz w nim:
    ```php
    <?php
      echo "Hello PHPStorm";
    ?>
    ```
12. Uruchom skrypt, klikając prawym przyciskiem myszy i wybierając **Run index.php (PHP Script)**.
    ![Uruchomienie](https://ucarecdn.stepik.net/59f4b34b-6740-41cd-ace5-583c56e95427/)
    Program powinien wyświetlić wynik w konsoli:
    ![Wynik Konsola](https://ucarecdn.stepik.net/253511e2-3212-4974-b322-d8bf1c856153/)
13. Możesz również uruchomić skrypt przez wbudowany serwer/stronę:
    ![Opcje Uruchomienia](https://ucarecdn.stepik.net/6ff5b943-4234-4257-89dc-d8005cb4840b/)
    ![Wynik Przeglądarka](https://ucarecdn.stepik.net/f857a669-b517-452c-815a-32d6495c775f/)

---

### Ważna konfiguracja zmiennej sesyjnej (Session Configuration)

Aby sesje w PHP działały poprawnie na komputerach uczelnianych (szczególnie w środowisku XAMPP), należy upewnić się, że ścieżka zapisu sesji jest poprawnie ustawiona i ma uprawnienia do zapisu.

1. Otwórz plik `php.ini` (zgodnie z instrukcją powyżej).
2. Odnajdź linię zaczynającą się od `;session.save_path`.
3. Usuń średnik na początku (odkomentuj) i ustaw ścieżkę na folder `tmp` w Twoim katalogu XAMPP, np.:
   `session.save_path = "D:\\xampp\\tmp"`
4. Jeśli chcesz ręcznie ustawić lub sprawdzić ID sesji w kodzie PHP, użyj:
   ```php
   <?php
   session_start();
   // Ustawienie własnego ID sesji (musi być wywołane przed session_start)
   // session_id('twoje_unikalne_id'); 
   echo "Aktualne ID sesji: " . session_id();
   ?>
   ```
