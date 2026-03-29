# Zadanie 1: Merge Sort — Algorytm "Dziel i Zwyciężaj"

## Cel Zadania
Zaimplementuj algorytm sortowania przez scalanie (Merge Sort) i zweryfikuj empirycznie jego złożoność obliczeniową.

## Specyfikacja techniczna

### Algorytm rekurencyjny
1. Jeśli tablica ma ≤ 1 elementów — zwróć ją.
2. Podziel tablicę na połowy: `$mid = (int)(count($arr) / 2)`.
3. Wywołaj rekurencyjnie `mergeSort` dla lewej i prawej połowy.
4. Scal wyniki funkcją `merge($left, $right, &$comparisons)`.

### Funkcja scalająca `merge`
- Porównuj pierwsze elementy obu tablic; mniejszy przenoś do wyniku.
- **Zliczaj każde porównanie** w zmiennej `$comparisons` przekazanej przez referencję (`&$comparisons`).
- Po wyczerpaniu jednej tablicy dołącz resztę drugiej.

### Wzór na złożoność
$$K = \frac{\text{comparisons}}{n \cdot \log_2(n)}$$

Dla poprawnej implementacji $K$ powinno być z przedziału $[0.5, 1.0]$.

## Zadanie do wykonania

Napisz program, który posortuje poniższe cztery tablice i dla każdej wypisze: wejście, wyjście posortowane, liczbę porównań oraz współczynnik $K$.

```php
$tablice = [
    [5, 3, 8, 1, 9, 2],
    [38, 27, 43, 3, 9, 82, 10, 15],
    [64, 25, 12, 22, 11, 90, 3, 47, 71, 38, 55, 8],
    [25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
];
```

Na końcu posortuj dowolną z tablic wbudowaną funkcją `sort()` i wyświetl komunikat potwierdzający, że wyniki są identyczne.

---

## Oczekiwany wynik

```
n=6  | Wejście: [5, 3, 8, 1, 9, 2]
     | Wyjście: [1, 2, 3, 5, 8, 9]
     | Porównania: 10 | K: 0.645

n=8  | Wejście: [38, 27, 43, 3, 9, 82, 10, 15]
     | Wyjście: [3, 9, 10, 15, 27, 38, 43, 82]
     | Porównania: 17 | K: 0.708

n=12 | Wejście: [64, 25, 12, 22, 11, 90, 3, 47, 71, 38, 55, 8]
     | Wyjście: [3, 8, 11, 12, 22, 25, 38, 47, 55, 64, 71, 90]
     | Porównania: 32 | K: 0.744

n=25 | (malejący 25..1 — najtrudniejszy przypadek)
     | Wyjście: [1, 2, 3, ..., 25]
     | Porównania: 64 | K: 0.551

Weryfikacja z sort(): ZGODNA
```

> **Wskazówka:** Tablicę na połowy dziel funkcją `array_slice($arr, 0, $mid)` i `array_slice($arr, $mid)`.
