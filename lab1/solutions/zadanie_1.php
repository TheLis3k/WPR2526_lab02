<?php
/**
 * Zadanie 1: Merge Sort — Algorytm "Dziel i Zwyciężaj"
 *
 * Implementacja sortowania przez scalanie z licznikiem porównań.
 * Weryfikacja złożoności: K = comparisons / (n * log2(n)) ∈ [0.5, 1.0]
 */

// ---------------------------------------------------------------------------
// Funkcja scalająca dwie posortowane tablice
// $comparisons przekazany przez referencję — zlicza każde porównanie elementów
// ---------------------------------------------------------------------------
function merge(array $left, array $right, int &$comparisons): array
{
    $result = [];
    $i = $j = 0;

    while ($i < count($left) && $j < count($right)) {
        $comparisons++;                        // liczymy każde porównanie
        if ($left[$i] <= $right[$j]) {
            $result[] = $left[$i];
            $i++;
        } else {
            $result[] = $right[$j];
            $j++;
        }
    }

    // Dołącz pozostałe elementy (już posortowane, bez porównań)
    while ($i < count($left))  { $result[] = $left[$i];  $i++; }
    while ($j < count($right)) { $result[] = $right[$j]; $j++; }

    return $result;
}

// ---------------------------------------------------------------------------
// Rekurencyjny Merge Sort
// ---------------------------------------------------------------------------
function mergeSort(array $arr, int &$comparisons): array
{
    $n = count($arr);
    if ($n <= 1) {
        return $arr;                           // warunek bazowy rekurencji
    }

    $mid   = (int)($n / 2);
    $left  = mergeSort(array_slice($arr, 0, $mid), $comparisons);
    $right = mergeSort(array_slice($arr, $mid),     $comparisons);

    return merge($left, $right, $comparisons);
}

// ---------------------------------------------------------------------------
// Dane testowe (deterministyczne — te same dla każdego uruchomienia)
// ---------------------------------------------------------------------------
$tablice = [
    [5, 3, 8, 1, 9, 2],
    [38, 27, 43, 3, 9, 82, 10, 15],
    [64, 25, 12, 22, 11, 90, 3, 47, 71, 38, 55, 8],
    [25, 24, 23, 22, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
];

// ---------------------------------------------------------------------------
// Sortowanie i analiza
// ---------------------------------------------------------------------------
foreach ($tablice as $arr) {
    $n           = count($arr);
    $comparisons = 0;
    $sorted      = mergeSort($arr, $comparisons);
    $theoretical = $n * log($n, 2);
    $K           = round($comparisons / $theoretical, 3);

    printf("n=%-2d | Wejście: [%s]\n", $n, implode(', ', $arr));
    printf("     | Wyjście: [%s]\n", implode(', ', $sorted));
    printf("     | Porównania: %d | n*log2(n)=%.1f | K=%.3f\n\n",
           $comparisons, $theoretical, $K);
}

// ---------------------------------------------------------------------------
// Weryfikacja z wbudowaną funkcją sort()
// ---------------------------------------------------------------------------
$test = [38, 27, 43, 3, 9, 82, 10, 15];
$comparisons = 0;
$mySorted = mergeSort($test, $comparisons);

$phpSorted = $test;
sort($phpSorted);

$zgodna = ($mySorted === $phpSorted) ? "ZGODNA" : "NIEZGODNA";
echo "Weryfikacja z sort(): $zgodna\n";
