# Zadanie 9: Interaktywny Interpreter Poleceń (Mini-REPL)

## Cel Zadania
Stwórz interaktywne środowisko konsolowe do manipulacji tablicą danych w czasie rzeczywistym.

## Specyfikacja techniczna

### Pętla główna
```php
$dane    = [];
$historia = [];

while (true) {
    $linia = readline(">> ");
    if ($linia === false || trim($linia) === '') continue;
    $czesci = explode(' ', trim($linia), 3);
    $polecenie = strtolower($czesci[0]);
    // ... switch/case ...
}
```

### Wymagane polecenia (minimum 15)
| Polecenie | Opis |
|-----------|------|
| `push <v>` | Dodaj wartość na koniec |
| `pop` | Usuń i wypisz ostatni element |
| `insert <idx> <v>` | Wstaw wartość na pozycję `idx` (`array_splice`) |
| `delete <idx>` | Usuń element na pozycji `idx` |
| `sort` | Posortuj rosnąco |
| `rsort` | Posortuj malejąco |
| `filter <op> <v>` | Zostaw elementy spełniające warunek, np. `filter > 5` |
| `unique` | Usuń duplikaty (`array_unique`) |
| `reverse` | Odwróć kolejność |
| `chunk <n>` | Podziel na podzbiory po `n` elementów i wypisz (`array_chunk`) |
| `slice <od> <ile>` | Wypisz fragment (`array_slice`), nie modyfikuj tablicy |
| `stats` | Suma, średnia, min, max — **bez** `min()`/`max()`, własna pętla |
| `show` | Wypisz aktualną tablicę |
| `reset` | Wyczyść tablicę |
| `save` | Wypisz tablicę w formacie JSON (`json_encode`) |
| `history` | Wypisz ostatnie 10 komend |
| `help` | Wypisz listę dostępnych poleceń |
| `exit` | Zakończ program |

### Historia
Po każdej poprawnie wykonanej komendzie dołącz ją do `$historia`. Przechowuj **tylko ostatnie 10** wpisów — użyj `array_slice($historia, -10)`.

### Obsługa błędów
Nieznane polecenie → wypisz `"Nieznane polecenie: <nazwa>"`. Brak wymaganego argumentu → wypisz `"Brak argumentu dla: <polecenie>"`.

## Zadanie do wykonania

Zaimplementuj pełny interpreter z co najmniej 15 poleceniami. Przetestuj go sesją poniżej.

---

## Przykładowa sesja (uruchom i sprawdź ręcznie)

```
>> push 10
[10]
>> push 5
[10, 5]
>> push 10
[10, 5, 10]
>> push 3
[10, 5, 10, 3]
>> push 8
[10, 5, 10, 3, 8]
>> unique
[10, 5, 3, 8]
>> sort
[3, 5, 8, 10]
>> stats
Suma: 26 | Średnia: 6.5 | Min: 3 | Max: 10
>> filter > 4
[5, 8, 10]
>> insert 1 7
[5, 7, 8, 10]
>> reverse
[10, 8, 7, 5]
>> chunk 2
Chunk 1: [10, 8]
Chunk 2: [7, 5]
>> save
{"dane":[10,8,7,5]}
>> history
1: push 10
2: push 5
3: push 10
4: push 3
5: push 8
6: unique
7: sort
8: stats
9: filter > 4
10: insert 1 7
>> exit
Do widzenia!
```

> **Wskazówka:** Komendę `filter > 4` rozbij jako: `$czesci[1]` = operator (`>`, `<`, `>=`, `<=`, `==`), `$czesci[2]` = wartość. Użyj `array_filter` z anonimową funkcją porównującą.
