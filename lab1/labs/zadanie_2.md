# Zadanie 2: Sito Eratostenesa i Hipoteza Goldbacha

## Cel Zadania
Zaimplementuj Sito Eratostenesa i użyj go do analizy gęstości liczb pierwszych oraz weryfikacji Hipotezy Goldbacha.

## Specyfikacja techniczna

### Sito Eratostenesa
1. Utwórz tablicę boolowską `$A` o rozmiarze `$n + 1`, wypełnioną `true`.
2. Ustaw `$A[0] = $A[1] = false`.
3. Dla `$i` od 2 do `sqrt($n)`: jeśli `$A[$i]`, to dla `$j = $i*$i; $j <= $n; $j += $i` ustaw `$A[$j] = false`.
4. Zwróć indeksy, dla których `$A[$i] === true`.

### Hipoteza Goldbacha
Każda parzysta liczba `$n > 2` daje się zapisać jako sumę dwóch liczb pierwszych.  
Para `($p, $n-$p)` jest parą Goldbacha, gdy oba są pierwsze i `$p <= $n/2`.

### Gęstość teoretyczna
Liczba liczb pierwszych w przedziale $[a, b]$ wynosi w przybliżeniu $\frac{b-a}{\ln(\text{środek})}$.

## Zadanie do wykonania

1. Napisz funkcję `sito(int $n): array` i wypisz wszystkie liczby pierwsze do 100 w rzędach po 10 (`array_chunk`).
2. Wypisz gęstość liczb pierwszych dla pięciu przedziałów: `[1–100]`, `[101–200]`, `[201–300]`, `[301–400]`, `[401–500]`. Porównaj z wartością teoretyczną.
3. Dla liczb parzystych od 4 do 200 znajdź pary Goldbacha. Wypisz, która liczba ma ich **najwięcej** oraz wypisz wszystkie pary dla liczby **30**.

---

## Oczekiwany wynik

```
Liczby pierwsze [1–100] (bloki po 10):
[2, 3, 5, 7, 11, 13, 17, 19, 23, 29]
[31, 37, 41, 43, 47, 53, 59, 61, 67, 71]
[73, 79, 83, 89, 97]

Gęstość liczb pierwszych:
Przedział [1–100]:   25 (teoretycznie: ~25.5)
Przedział [101–200]: 21 (teoretycznie: ~19.9)
Przedział [201–300]: 16 (teoretycznie: ~18.1)
Przedział [301–400]: 16 (teoretycznie: ~17.1)
Przedział [401–500]: 17 (teoretycznie: ~16.4)

Goldbach — najwięcej par w [4, 200]: Liczba 180 (14 par)
Pary Goldbacha dla 30: [7+23], [11+19], [13+17]
```

> **Wskazówka:** Do sprawdzenia czy liczba jest pierwsza użyj `in_array($q, $primes)` lub konwertuj wynik sita do tablicy asocjacyjnej `array_flip($primes)` dla szybszego wyszukiwania.
