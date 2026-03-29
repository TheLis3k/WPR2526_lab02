<?php
/**
 * Zadanie 3: Wyszukiwarka — Odwrócony Indeks i Ranking TF
 *
 * Silnik wyszukiwania z indeksem odwróconym, wyszukiwaniem AND/OR i rankingiem TF.
 */

// ---------------------------------------------------------------------------
// Dane — 10 dokumentów o tematyce PHP
// ---------------------------------------------------------------------------
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

$stopWords = ['i', 'w', 'na', 'do', 'z', 'są', 'lub', 'być', 'może', 'jest', 'się'];

// ---------------------------------------------------------------------------
// Budowa indeksu odwróconego
// index[$slowo][$doc_id] = liczba_wystapien
// ---------------------------------------------------------------------------
function zbudujIndeks(array $dokumenty, array $stopWords): array
{
    $index = [];

    foreach ($dokumenty as $docId => $text) {
        // Zamień na małe litery i usuń znaki niebędące literami/spacją
        $clean = preg_replace('/[^a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ ]/u', '', strtolower($text));
        $words = explode(' ', $clean);

        foreach ($words as $word) {
            if (strlen($word) < 3 || in_array($word, $stopWords)) {
                continue;
            }
            // Zwiększ licznik wystąpień słowa w tym dokumencie
            $index[$word][$docId] = ($index[$word][$docId] ?? 0) + 1;
        }
    }

    return $index;
}

// ---------------------------------------------------------------------------
// Wyszukiwanie AND: dokument musi zawierać WSZYSTKIE słowa zapytania
// Wynik posortowany malejąco wg sumarycznego TF
// ---------------------------------------------------------------------------
function szukajAND(array $index, array $query): array
{
    $wyniki = null;

    foreach ($query as $word) {
        $word   = strtolower($word);
        $docIds = array_keys($index[$word] ?? []);

        if ($wyniki === null) {
            $wyniki = $docIds;
        } else {
            $wyniki = array_values(array_intersect($wyniki, $docIds));
        }
    }

    return ocenDocumenty($index, $wyniki ?? [], $query);
}

// ---------------------------------------------------------------------------
// Wyszukiwanie OR: dokument musi zawierać PRZYNAJMNIEJ JEDNO słowo zapytania
// ---------------------------------------------------------------------------
function szukajOR(array $index, array $query): array
{
    $wyniki = [];

    foreach ($query as $word) {
        $word   = strtolower($word);
        $docIds = array_keys($index[$word] ?? []);
        $wyniki = array_unique(array_merge($wyniki, $docIds));
    }

    return ocenDocumenty($index, $wyniki, $query);
}

// ---------------------------------------------------------------------------
// Oblicz wynik TF i posortuj malejąco
// ---------------------------------------------------------------------------
function ocenDocumenty(array $index, array $docIds, array $query): array
{
    $scored = [];

    foreach ($docIds as $docId) {
        $score  = 0;
        $detail = [];
        foreach ($query as $word) {
            $word   = strtolower($word);
            $count  = $index[$word][$docId] ?? 0;
            $score += $count;
            $detail[] = "$word:$count";
        }
        $scored[] = ['id' => $docId, 'score' => $score, 'detail' => implode(', ', $detail)];
    }

    usort($scored, fn($a, $b) => $b['score'] - $a['score']);
    return $scored;
}

// ---------------------------------------------------------------------------
// Uruchomienie
// ---------------------------------------------------------------------------
$index = zbudujIndeks($dokumenty, $stopWords);

// Krok 1: Top 5 najczęstszych słów
$czestotliwosc = [];
foreach ($index as $word => $docs) {
    $czestotliwosc[$word] = array_sum($docs);
}
arsort($czestotliwosc);

echo "Top 5 najczęstszych słów:\n";
$top5 = array_slice($czestotliwosc, 0, 5, true);
foreach ($top5 as $word => $count) {
    printf("  '%s': %dx\n", $word, $count);
}

// Krok 2: Wyszukiwanie AND
$queryAND = ["php", "tablice"];
$wynikAND = szukajAND($index, $queryAND);
echo "\nWyniki dla (" . implode(" AND ", $queryAND) . "):\n";
foreach ($wynikAND as $i => $r) {
    printf("  %d. Dokument ID:%d | Score:%d (%s)\n",
           $i + 1, $r['id'], $r['score'], $r['detail']);
}

// Krok 3: Wyszukiwanie OR
$queryOR = ["mysql", "javascript"];
$wynikOR = szukajOR($index, $queryOR);
echo "\nWyniki dla (" . implode(" OR ", $queryOR) . "):\n";
foreach ($wynikOR as $i => $r) {
    printf("  %d. Dokument ID:%d | Score:%d (%s)\n",
           $i + 1, $r['id'], $r['score'], $r['detail']);
}
