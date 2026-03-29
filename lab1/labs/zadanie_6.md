# Zadanie 6: Algorytm Zachłanny — Harmonogramowanie Zadań i Salek

## Cel Zadania
Rozwiąż problem optymalnego przydziału zadań do sal (Interval Scheduling) algorytmem zachłannym.

## Specyfikacja techniczna

### Algorytm zachłanny (jedna sala — maksymalna liczba zadań)
1. Posortuj zadania rosnąco według czasu **zakończenia** (`usort`).
2. Wybierz pierwsze zadanie.
3. Każde kolejne zadanie dodaj tylko wtedy, gdy jego `start >= koniec_ostatnio_wybranego`.

### Wykrywanie kolizji
Dwa zadania kolidują, gdy: `max(start_A, start_B) < min(koniec_A, koniec_B)`.

### Minimalna liczba sal (algorytm zachłanny — wersja z wieloma zasobami)
1. Posortuj zadania rosnąco według `start`.
2. Dla każdego zadania: przypisz do pierwszej sali, której ostatnie zadanie już się zakończyło (`koniec_ostatniego <= start_nowego`). Jeśli brak wolnej sali — otwórz nową.

## Dane wejściowe — użyj dokładnie tej tablicy

```php
// Czasy w minutach od północy: 8:00 = 480, 8:30 = 510, 9:00 = 540 itd.
$zadania = [
    ["id"=>1,  "nazwa"=>"T01", "start"=>480,  "koniec"=>600],   // 8:00–10:00
    ["id"=>2,  "nazwa"=>"T02", "start"=>510,  "koniec"=>720],   // 8:30–12:00
    ["id"=>3,  "nazwa"=>"T03", "start"=>540,  "koniec"=>660],   // 9:00–11:00
    ["id"=>4,  "nazwa"=>"T04", "start"=>600,  "koniec"=>690],   // 10:00–11:30
    ["id"=>5,  "nazwa"=>"T05", "start"=>660,  "koniec"=>780],   // 11:00–13:00
    ["id"=>6,  "nazwa"=>"T06", "start"=>690,  "koniec"=>840],   // 11:30–14:00
    ["id"=>7,  "nazwa"=>"T07", "start"=>720,  "koniec"=>810],   // 12:00–13:30
    ["id"=>8,  "nazwa"=>"T08", "start"=>780,  "koniec"=>900],   // 13:00–15:00
    ["id"=>9,  "nazwa"=>"T09", "start"=>840,  "koniec"=>960],   // 14:00–16:00
    ["id"=>10, "nazwa"=>"T10", "start"=>480,  "koniec"=>540],   // 8:00–9:00
    ["id"=>11, "nazwa"=>"T11", "start"=>570,  "koniec"=>630],   // 9:30–10:30
    ["id"=>12, "nazwa"=>"T12", "start"=>750,  "koniec"=>870],   // 12:30–14:30
    ["id"=>13, "nazwa"=>"T13", "start"=>900,  "koniec"=>990],   // 15:00–16:30
    ["id"=>14, "nazwa"=>"T14", "start"=>495,  "koniec"=>555],   // 8:15–9:15
    ["id"=>15, "nazwa"=>"T15", "start"=>870,  "koniec"=>930],   // 14:30–15:30
];
```

## Zadanie do wykonania

1. Zastosuj algorytm zachłanny i wypisz maksymalny zestaw zadań dla **jednej** sali.
2. Dla każdego zadania policz, z iloma innymi zadaniami koliduje. Wypisz najbardziej konfliktowe.
3. Wyznacz minimalną liczbę sal potrzebną do realizacji **wszystkich** zadań i wypisz harmonogram każdej sali.

Czasy wyświetlaj w formacie `H:MM` (np. `8:00`, `11:30`).

---

## Oczekiwany wynik

```
Algorytm zachłanny (jedna sala):
  Wybrane zadania (5): T10, T11, T05, T08, T13
  Kolejność decyzji: T10(8:00–9:00) → T11(9:30–10:30) → T05(11:00–13:00) → T08(13:00–15:00) → T13(15:00–16:30)

Konflikty:
  Najbardziej konfliktowe: T02 (8 kolizji z innymi zadaniami)

Minimalna liczba sal: 4
  Sala 1: T01(8:00–10:00), T04(10:00–11:30), T06(11:30–14:00), T09(14:00–16:00)
  Sala 2: T10(8:00–9:00), T03(9:00–11:00), T05(11:00–13:00), T08(13:00–15:00), T13(15:00–16:30)
  Sala 3: T14(8:15–9:15), T11(9:30–10:30), T07(12:00–13:30), T15(14:30–15:30)
  Sala 4: T02(8:30–12:00), T12(12:30–14:30)
```

> **Wskazówka:** Napisz pomocniczą funkcję `minutyNaCzas(int $m): string` która zwraca np. `"8:00"` dla `480`.
