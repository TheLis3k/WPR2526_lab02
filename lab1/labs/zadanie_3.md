# Zadanie 3: Wyszukiwarka — Odwrócony Indeks i Ranking TF

## Cel Zadania
Zbuduj silnik wyszukiwania dla kolekcji dokumentów tekstowych z wyszukiwaniem logicznym AND/OR i rankingiem TF.

## Specyfikacja techniczna

### Budowa indeksu
- Przetwórz każdy dokument: zamień na małe litery (`strtolower`), usuń znaki niebędące literami/spacją, podziel na słowa (`explode`).
- Pomiń stop-words: `['i', 'w', 'na', 'do', 'z', 'są', 'lub', 'być', 'może', 'jest', 'się']` oraz słowa krótsze niż 3 znaki.
- Buduj indeks: `$index[$slowo][$doc_id] = liczba_wystapien`.

### Wyszukiwanie AND
Zwróć tylko dokumenty, w których **wszystkie** słowa z zapytania są obecne w indeksie.

### Wyszukiwanie OR
Zwróć dokumenty, w których **co najmniej jedno** słowo z zapytania jest obecne.

### Ranking TF
Wyniki sortuj malejąco według sumy częstości wystąpień szukanych słów w dokumencie.

## Dane wejściowe — użyj dokładnie tej tablicy

```php
$dokumenty = [
    0 => "PHP jest językiem skryptowym używanym do tworzenia stron internetowych",
    1 => "Tablice w PHP mogą być indeksowane lub asocjacyjne i bardzo przydatne",
    2 => "Funkcje array_map i array_filter ułatwiają przetwarzanie tablic w PHP",
    3 => "PHP obsługuje tablice wielowymiarowe i zagnieżdżone struktury danych",
    4 => "Serwer Apache współpracuje z PHP do obsługi żądań HTTP i połączeń",
    5 => "Bazy danych MySQL są często używane razem z PHP do przechowywania",
    6 => "Funkcja usort sortuje tablice w PHP według różnych kryteriów i warunków",
    7 => "JavaScript i PHP razem tworzą dynamiczne aplikacje internetowe i serwisy",
    8 => "PHP posiada wbudowane funkcje do pracy z plikami tablicami i bazami",
    9 => "Bezpieczeństwo aplikacji PHP wymaga walidacji danych wejściowych i filtrów",
];
```

## Zadanie do wykonania

1. Zbuduj indeks i wypisz 5 najczęstszych słów w całym zbiorze (po odfiltrowaniu stop-words).
2. Wykonaj wyszukiwanie AND dla zapytania `["php", "tablice"]`.
3. Wykonaj wyszukiwanie OR dla zapytania `["mysql", "javascript"]`.

---

## Oczekiwany wynik

```
Top 5 najczęstszych słów:
  'php': 10x
  'tablice': 3x (lub 'danych': 3x)
  'funkcje': 2x
  'razem': 2x
  ...

Wyniki dla (php AND tablice):
  1. Dokument ID:1 | Score:2 (php:1, tablice:1)
  2. Dokument ID:3 | Score:2 (php:1, tablice:1)
  3. Dokument ID:6 | Score:2 (php:1, tablice:1)

Wyniki dla (mysql OR javascript):
  1. Dokument ID:5 | Score:1 (mysql:1)
  2. Dokument ID:7 | Score:1 (javascript:1)
```

> **Wskazówka:** Do wyszukiwania AND użyj `array_intersect` na listach ID dokumentów z każdego słowa zapytania. Do OR użyj `array_unique(array_merge(...))`.
