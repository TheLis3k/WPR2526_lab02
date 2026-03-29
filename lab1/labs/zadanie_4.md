# Zadanie 4: Struktury LIFO i Walidacja Wyrażeń (Stos, ONP, Nawiasy)

## Cel Zadania
Zaimplementuj własną strukturę stosu i użyj jej do walidacji nawiasów oraz obliczania wyrażeń w Odwrotnej Notacji Polskiej (ONP/RPN).

## Specyfikacja techniczna

### Stos (LIFO) — zakaz użycia `array_push` i `array_pop`
```php
function s_push(array &$stos, $val): void {
    array_splice($stos, count($stos), 0, [$val]);
}
function s_pop(array &$stos) {
    $top = $stos[count($stos) - 1];
    array_splice($stos, -1, 1);
    return $top;
}
function s_peek(array $stos) {
    return $stos[count($stos) - 1];
}
```

### Walidacja nawiasów
- `(`, `[`, `{` → wrzuć na stos.
- `)`, `]`, `}` → zdejmij ze stosu i sprawdź czy pasuje (np. `(` pasuje do `)`).
- Na końcu stos musi być pusty.

### Kalkulator ONP
Tokeny to wyrazy po rozbiciu przez `explode(' ', $wyrazenie)`:
- Liczba → `s_push`.
- Operator (`+`, `-`, `*`, `/`) → zdejmij dwie liczby, wykonaj działanie, wrzuć wynik.

### Bufor cykliczny
Tablica o stałym rozmiarze 5. Kolejny wynik zapisuj pod indeksem `$pos % 5`. Zwiększaj `$pos` po każdym zapisie.

## Dane wejściowe — użyj dokładnie tych danych

```php
$wyrazenia_ONP = [
    "5 2 + 3 *",
    "15 7 1 1 + - / 3 * 2 1 1 + + -",
    "4 13 5 / +",
    "2 3 + 4 * 5 -",
    "100 50 25 / -",
];

$napisy_nawiasy = [
    "[({()})]",
    "((())",
    "{[()]}",
    "([)]",
    "",
];
```

## Zadanie do wykonania

Dla każdego wyrażenia ONP: sprawdź poprawność nawiasów w odpowiadającym napisie, oblicz wynik ONP, zapisz do bufora cyklicznego. Na końcu wypisz cały bufor.

---

## Oczekiwany wynik

```
[1] Nawiasy "[({()})]": OK   | ONP "5 2 + 3 *"                    = 21
[2] Nawiasy "((())":   BŁĄD  | ONP "15 7 1 1 + - / 3 * 2 1 1 + + -" = 5
[3] Nawiasy "{[()]}":  OK    | ONP "4 13 5 / +"                   = 6.6
[4] Nawiasy "([)]":    BŁĄD  | ONP "2 3 + 4 * 5 -"                = 15
[5] Nawiasy "":        OK    | ONP "100 50 25 / -"                 = 98

Bufor cykliczny (ostatnie 5 wyników): [21, 5, 6.6, 15, 98]
```

> **Wskazówka:** Przy obliczaniu ONP rzutuj tokeny na `(float)` przed wrzuceniem na stos.
