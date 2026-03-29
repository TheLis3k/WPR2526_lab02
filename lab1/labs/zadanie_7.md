# Zadanie 7: System Rekomendacji — Korelacja Pearsona

## Cel Zadania
Zaimplementuj silnik rekomendacji oparty na podobieństwie użytkowników (User-based Collaborative Filtering).

## Specyfikacja techniczna

### Korelacja Pearsona
Oblicz $r$ dla dwóch użytkowników $A$ i $B$ **tylko na podstawie produktów, które oboje ocenili** (`null` = brak oceny):

$$r = \frac{\sum (r_{A,i} - \bar{r}_A)(r_{B,i} - \bar{r}_B)}{\sqrt{\sum (r_{A,i} - \bar{r}_A)^2 \cdot \sum (r_{B,i} - \bar{r}_B)^2}}$$

Gdzie $\bar{r}_A$ to średnia ocen $A$ na wspólnych produktach. Jeśli wspólnych ocen jest mniej niż 2 — zwróć 0.

### Przewidywanie oceny
Ważona średnia od $k$ najbliższych sąsiadów (kNN):

$$pred(A, p) = \frac{\sum_{X \in kNN} sim(A,X) \cdot r_{X,p}}{\sum_{X \in kNN} |sim(A,X)|}$$

Przewiduj tylko dla sąsiadów, którzy ocenili produkt $p$.

## Dane wejściowe — użyj dokładnie tej macierzy

```php
// Produkty: Laptop, Monitor, Klawiatura, Mysz, Słuchawki, Kamera, Tablet, Głośnik
$oceny = [
    "Anna"    => [5, 4, null, 2, null, 3, 4, 5],
    "Bartek"  => [4, 5, 3, null, 2, 4, null, 4],
    "Celina"  => [5, 3, null, 3, null, 4, 5, null],
    "Dawid"   => [2, null, 4, 5, 3, null, 2, 3],
    "Ewa"     => [null, 4, 3, null, 5, 3, 4, 2],
    "Filip"   => [3, 5, 4, 2, null, 5, null, 4],
    "Grażyna" => [5, null, 2, 4, 3, 2, 5, null],
];
$produkty = ["Laptop","Monitor","Klawiatura","Mysz","Słuchawki","Kamera","Tablet","Głośnik"];
```

## Zadanie do wykonania

1. Oblicz podobieństwo Pearsona między **Anną** a każdym innym użytkownikiem. Posortuj malejąco.
2. Znajdź $k=3$ sąsiadów Anny.
3. Wygeneruj rekomendacje dla Anny: dla każdego produktu, którego Anna **nie** oceniła, oblicz przewidywaną ocenę na podstawie kNN. Posortuj malejąco i wypisz.
4. Obsłuż przypadek **zimnego startu**: nowy użytkownik "Hania" ocenił tylko Laptop (ocena: 4). Wypisz, dlaczego system nie może wygenerować wiarygodnych rekomendacji i jaką strategię zastosować (np. popularność produktów).

---

## Oczekiwany wynik

```
Podobieństwo Pearsona dla Anny:
  Celina:  0.6578
  Grażyna: 0.5477
  Filip:   0.2647
  Bartek: -0.1741
  Ewa:    -0.4264
  Dawid:  -0.8333

k=3 sąsiedzi Anny: Celina(0.6578), Grażyna(0.5477), Filip(0.2647)

Rekomendacje dla Anny (produkty nieocenione):
  1. Słuchawki    — przewidywana ocena: 3.00
  2. Klawiatura   — przewidywana ocena: 2.65

Zimny start (Hania, 1 ocena):
  Za mało wspólnych ocen z innymi użytkownikami — brak wiarygodnych korelacji.
  Strategia: rekomenduj najpopularniejsze produkty (najwyższa średnia ocen wśród wszystkich).
```

> **Wskazówka:** Do obliczenia Pearsona najpierw wyfiltruj indeksy, gdzie obie tablice mają wartość różną od `null`, używając pętli `for ($i = 0; $i < 8; $i++)`.
