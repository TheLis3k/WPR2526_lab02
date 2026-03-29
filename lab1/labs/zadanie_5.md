# Zadanie 5: Agregacja Wielowymiarowa i Pivot Table

## Cel Zadania
Przetwórz surowe dane transakcyjne do postaci raportu analitycznego — tabeli przestawnej z obliczonym odchyleniem standardowym.

## Specyfikacja techniczna

### Grupowanie i pivot
- Pogrupuj transakcje według `kategoria` i miesiąca (pierwsze 7 znaków `data` → `"2024-01"`).
- Tabela przestawna: wiersze = kategorie, kolumny = miesiące, wartości = suma kwot.

### Odchylenie standardowe
$$\sigma = \sqrt{\frac{1}{N} \sum_{i=1}^{N} (x_i - \bar{x})^2}$$

Oblicz oddzielnie dla każdej kategorii na podstawie kwot **wszystkich** jej transakcji.

### Formatowanie tabeli
Użyj `printf` z szerokościami kolumn: `%-14s | %8s | %8s | %8s`.

## Dane wejściowe — użyj dokładnie tej tablicy

```php
$transakcje = [
    ["id"=>1,  "data"=>"2024-01-15","kategoria"=>"Elektronika","kwota"=>1200.00],
    ["id"=>2,  "data"=>"2024-01-22","kategoria"=>"Dom",        "kwota"=>350.00],
    ["id"=>3,  "data"=>"2024-02-03","kategoria"=>"Elektronika","kwota"=>800.00],
    ["id"=>4,  "data"=>"2024-02-14","kategoria"=>"Odzież",     "kwota"=>250.00],
    ["id"=>5,  "data"=>"2024-02-28","kategoria"=>"Dom",        "kwota"=>420.00],
    ["id"=>6,  "data"=>"2024-03-05","kategoria"=>"Elektronika","kwota"=>1500.00],
    ["id"=>7,  "data"=>"2024-03-12","kategoria"=>"Odzież",     "kwota"=>180.00],
    ["id"=>8,  "data"=>"2024-03-19","kategoria"=>"Dom",        "kwota"=>290.00],
    ["id"=>9,  "data"=>"2024-01-08","kategoria"=>"Odzież",     "kwota"=>310.00],
    ["id"=>10, "data"=>"2024-01-30","kategoria"=>"Elektronika","kwota"=>950.00],
    ["id"=>11, "data"=>"2024-02-10","kategoria"=>"Dom",        "kwota"=>600.00],
    ["id"=>12, "data"=>"2024-03-25","kategoria"=>"Odzież",     "kwota"=>430.00],
    ["id"=>13, "data"=>"2024-01-18","kategoria"=>"Elektronika","kwota"=>2100.00],
    ["id"=>14, "data"=>"2024-02-22","kategoria"=>"Dom",        "kwota"=>175.00],
    ["id"=>15, "data"=>"2024-03-08","kategoria"=>"Elektronika","kwota"=>670.00],
    ["id"=>16, "data"=>"2024-01-25","kategoria"=>"Odzież",     "kwota"=>520.00],
    ["id"=>17, "data"=>"2024-02-17","kategoria"=>"Elektronika","kwota"=>1350.00],
    ["id"=>18, "data"=>"2024-03-14","kategoria"=>"Dom",        "kwota"=>480.00],
    ["id"=>19, "data"=>"2024-01-12","kategoria"=>"Dom",        "kwota"=>230.00],
    ["id"=>20, "data"=>"2024-02-05","kategoria"=>"Odzież",     "kwota"=>390.00],
];
```

## Zadanie do wykonania

1. Zbuduj tabelę przestawną (pivot) Kategoria × Miesiąc i wypisz ją sformatowaną `printf`.
2. Oblicz i wypisz odchylenie standardowe kwot dla każdej kategorii. **Nie wolno** używać wbudowanych funkcji statystycznych — zaimplementuj wzór ręcznie.
3. Wypisz kategorię o największej zmienności.

---

## Oczekiwany wynik

```
Kategoria      |  Styczeń |     Luty |   Marzec
----------------------------------------------
Dom            |   580.00 |  1195.00 |   770.00
Elektronika    |  4250.00 |  2150.00 |  2170.00
Odzież         |   830.00 |   640.00 |   610.00

Odchylenia standardowe (σ):
  Dom          : σ=137.13 (n=7, avg=363.57 zł)
  Elektronika  : σ=450.68 (n=7, avg=1224.29 zł)
  Odzież       : σ=113.53 (n=6, avg=346.67 zł)

Kategoria o największej zmienności: Elektronika (σ=450.68)
```

> **Wskazówka:** Miesiąc wyciągaj przez `substr($t['data'], 0, 7)` — daje np. `"2024-01"`.
