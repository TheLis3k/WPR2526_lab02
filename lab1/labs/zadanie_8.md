# Zadanie 8: Pipeline ETL — Walidacja, Dedulikacja i Normalizacja

## Cel Zadania
Zbuduj proceduralny potok przetwarzania danych (Extract → Transform → Load), który przetworzy "brudny" zestaw rekordów i wygeneruje czysty raport.

## Specyfikacja techniczna

### Etap E — Walidacja (funkcja `waliduj(array $dane): array`)
Odrzuć rekord i zapisz powód, jeśli:
- `wiek` nie jest liczbą całkowitą lub jest poza zakresem `[1, 120]`
- `wynik` jest poza zakresem `[0.0, 100.0]`
- `email` jest pusty po `trim()`

Funkcja **nie modyfikuje** wejścia — zwraca nową tablicę `['valid' => [...], 'rejected' => [...]]`.

### Etap T — Transformacja (funkcja `transformuj(array $dane): array`)
- Usuń duplikaty według pola `email` (zachowaj pierwszy wpis).
- Ujednolicaj imiona: `ucfirst(strtolower($imie))`.
- Rzutuj: `wiek` → `(int)`, `wynik` → `(float)`.

### Etap L — Analiza i wypisywanie
- Przypisz oceny literowe: A ≥ 90, B ≥ 75, C ≥ 60, D < 60.
- Wypisz finalną bazę w sformatowanej tabeli (`printf`).
- Wypisz statystyki rozkładu ocen (liczba studentów i średnia w każdej grupie).

## Dane wejściowe — użyj dokładnie tej tablicy (zawiera celowe błędy!)

```php
$rekordy = [
    ["id"=>1,  "imie"=>"anna",    "wiek"=>"25",  "email"=>"anna@test.com",   "wynik"=>92.5],
    ["id"=>2,  "imie"=>"Bartosz", "wiek"=>"abc", "email"=>"bartosz@test.com","wynik"=>78.0],  // błąd: wiek
    ["id"=>3,  "imie"=>"celina",  "wiek"=>"31",  "email"=>"celina@test.com", "wynik"=>105.0], // błąd: wynik
    ["id"=>4,  "imie"=>"Dawid",   "wiek"=>"45",  "email"=>"",               "wynik"=>66.5],  // błąd: email
    ["id"=>5,  "imie"=>"EWA",     "wiek"=>"28",  "email"=>"ewa@test.com",    "wynik"=>88.0],
    ["id"=>6,  "imie"=>"filip",   "wiek"=>"130", "email"=>"filip@test.com",  "wynik"=>74.0],  // błąd: wiek
    ["id"=>7,  "imie"=>"Grażyna", "wiek"=>"52",  "email"=>"anna@test.com",   "wynik"=>91.0],  // duplikat email
    ["id"=>8,  "imie"=>"Henryk",  "wiek"=>"19",  "email"=>"henryk@test.com", "wynik"=>-5.0],  // błąd: wynik
    ["id"=>9,  "imie"=>"irena",   "wiek"=>"37",  "email"=>"irena@test.com",  "wynik"=>83.5],
    ["id"=>10, "imie"=>"JANEK",   "wiek"=>"22",  "email"=>"janek@test.com",  "wynik"=>55.0],
    ["id"=>11, "imie"=>"Kasia",   "wiek"=>"29",  "email"=>"kasia@test.com",  "wynik"=>97.0],
    ["id"=>12, "imie"=>"Leon",    "wiek"=>"41",  "email"=>"leon@test.com",   "wynik"=>62.0],
    ["id"=>13, "imie"=>"Marta",   "wiek"=>"0",   "email"=>"marta@test.com",  "wynik"=>79.5],  // błąd: wiek
    ["id"=>14, "imie"=>"norbert", "wiek"=>"33",  "email"=>"norbert@test.com","wynik"=>86.0],
    ["id"=>15, "imie"=>"Ola",     "wiek"=>"26",  "email"=>"ola@test.com",    "wynik"=>91.0],
];
```

## Zadanie do wykonania

Przeprowadź dane przez 3 etapy pipeline'u. Wypisz listę odrzuconych rekordów z powodami, a następnie finalną bazę i statystyki.

---

## Oczekiwany wynik

```
=== Etap E: Walidacja ===
Odrzucone rekordy (7):
  - ID 2  (Bartosz): nieprawidłowy wiek 'abc'
  - ID 3  (celina):  wynik poza zakresem [0–100]: 105.0
  - ID 4  (Dawid):   pusty email
  - ID 6  (filip):   nieprawidłowy wiek '130'
  - ID 7  (Grażyna): duplikat email 'anna@test.com'
  - ID 8  (Henryk):  wynik poza zakresem [0–100]: -5.0
  - ID 13 (Marta):   nieprawidłowy wiek '0'

=== Etap L: Finalna baza (8 rekordów) ===
Imię         | Wiek | Email                     | Wynik | Ocena
-----------------------------------------------------------------
Anna         |   25 | anna@test.com             |  92.5 | A
Ewa          |   28 | ewa@test.com              |  88.0 | B
Irena        |   37 | irena@test.com            |  83.5 | B
Janek        |   22 | janek@test.com            |  55.0 | D
Kasia        |   29 | kasia@test.com            |  97.0 | A
Leon         |   41 | leon@test.com             |  62.0 | C
Norbert      |   33 | norbert@test.com          |  86.0 | B
Ola          |   26 | ola@test.com              |  91.0 | A

Rozkład ocen:
  A: 3 studentów, średnia: 93.5%
  B: 3 studentów, średnia: 85.8%
  C: 1 studentów, średnia: 62.0%
  D: 1 studentów, średnia: 55.0%
```

> **Wskazówka:** Walidacja i transformacja muszą być oddzielnymi funkcjami — każda zwraca nową tablicę, nie modyfikuje argumentu.
