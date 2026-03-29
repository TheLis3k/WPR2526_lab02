# Zadanie 1: Menedżer Zadań — obsługa formularza w PHP

## Cel

Rozbudowa formularza menedżera zadań z poprzednich zajęć o obsługę po stronie serwera. Plik ten staje się punktem wyjścia — należy go przekształcić w działającą aplikację PHP, która przyjmuje dane z formularza, przechowuje je między odświeżeniami strony i pozwala nimi zarządzać.

---

## Etap 1 — Konwersja i lista zadań

Plik HTML należy zapisać jako `index.php`. Formularz powinien wysyłać dane do samego siebie metodą POST.

Zadania wprowadzone przez użytkownika mają być przechowywane po stronie serwera tak, żeby przeżywały odświeżenie strony. Pod formularzem powinna pojawić się tabela ze wszystkimi dotychczas dodanymi zadaniami. Gdy lista jest pusta, zamiast tabeli wyświetla się stosowna informacja.

---

## Etap 2 — Walidacja i zachowanie wpisanych wartości

Nie każde zgłoszenie formularza powinno skutkować dodaniem zadania. Wymagane jest sprawdzenie, czy wypełniono pola: tytuł, kategoria, priorytet oraz data wykonania. Gdy któreś z nich jest puste, zadanie nie zostaje zapisane, a użytkownik widzi listę problemów do poprawy.

Ważne jest, żeby po nieudanej próbie zapisu formularz nie kasował tego, co użytkownik już wpisał — pola powinny zachować swoje wartości.

Po poprawnym zapisaniu zadania użytkownik powinien otrzymać potwierdzenie.

---

## Etap 3 — Usuwanie zadań

Przy każdym zadaniu na liście należy umieścić możliwość jego usunięcia. Oprócz tego powinna być dostępna opcja jednorazowego usunięcia wszystkich zadań.

---

## Etap 4 — Statystyki

Nad tabelą zadań należy wyświetlić panel podsumowujący stan listy: łączną liczbę zadań oraz podział według priorytetu i statusu. Panel widoczny jest zawsze, nawet gdy lista jest pusta.

---

## Plik do oddania

`index.php` — kompletna aplikacja zawierająca wszystkie cztery etapy.

---

## Kryteria oceny

- Zadania są zapisywane i widoczne po odświeżeniu strony.
- Walidacja blokuje zapis przy brakujących polach i informuje o tym użytkownika.
- Formularz po nieudanej walidacji zachowuje wpisane wartości.
- Usuwanie pojedynczego zadania oraz czyszczenie całej listy działa poprawnie.
- Statystyki poprawnie odzwierciedlają aktualny stan listy.
- Dane z formularza wyświetlane na stronie są zabezpieczone przed XSS.
