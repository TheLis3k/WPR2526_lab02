<?php
/**
 * Zadanie 2: Sito Eratostenesa i Hipoteza Goldbacha
 *
 * Implementacja sita z analizą gęstości i weryfikacją hipotezy Goldbacha.
 */

// ---------------------------------------------------------------------------
// Sito Eratostenesa — zwraca tablicę liczb pierwszych do $n włącznie
// ---------------------------------------------------------------------------
function sito(int $n): array
{
    $A = array_fill(0, $n + 1, true);
    $A[0] = $A[1] = false;

    for ($i = 2; $i <= (int)sqrt($n); $i++) {
        if ($A[$i]) {
            for ($j = $i * $i; $j <= $n; $j += $i) {
                $A[$j] = false;
            }
        }
    }

    // Zbierz indeksy z wartością true
    $primes = [];
    for ($i = 2; $i <= $n; $i++) {
        if ($A[$i]) {
            $primes[] = $i;
        }
    }
    return $primes;
}

// ---------------------------------------------------------------------------
// Krok 1: Wypisz liczby pierwsze do 100 w blokach po 10
// ---------------------------------------------------------------------------
$primes500 = sito(500);
$primes100 = array_filter($primes500, fn($p) => $p <= 100);
$primes100 = array_values($primes100);

echo "Liczby pierwsze [1–100] (bloki po 10):\n";
foreach (array_chunk($primes100, 10) as $chunk) {
    echo "[" . implode(", ", $chunk) . "]\n";
}

// ---------------------------------------------------------------------------
// Krok 2: Gęstość liczb pierwszych w przedziałach po 100
// ---------------------------------------------------------------------------
echo "\nGęstość liczb pierwszych:\n";
for ($start = 1; $start <= 500; $start += 100) {
    $end   = $start + 99;
    $mid   = ($start + $end) / 2;
    $count = count(array_filter($primes500, fn($p) => $p >= $start && $p <= $end));
    $theoretical = round(100 / log($mid), 2);
    printf("Przedział [%3d–%3d]: %2d (teoretycznie: ~%.1f)\n",
           $start, $end, $count, $theoretical);
}

// ---------------------------------------------------------------------------
// Krok 3: Hipoteza Goldbacha — znajdź liczbę z największą liczbą par w [4,200]
// ---------------------------------------------------------------------------
// Szybsze wyszukiwanie: indeks asocjacyjny
$primesIndex = array_flip($primes500);

$maxPary  = 0;
$maxLiczba = 0;

for ($n = 4; $n <= 200; $n += 2) {
    $paryCnt = 0;
    foreach ($primes500 as $p) {
        if ($p > $n / 2) break;
        $q = $n - $p;
        if (isset($primesIndex[$q])) {
            $paryCnt++;
        }
    }
    if ($paryCnt > $maxPary) {
        $maxPary   = $paryCnt;
        $maxLiczba = $n;
    }
}

echo "\nGoldbach — najwięcej par w [4, 200]: Liczba $maxLiczba ($maxPary par)\n";

// ---------------------------------------------------------------------------
// Pary Goldbacha dla liczby 30
// ---------------------------------------------------------------------------
$pary30 = [];
foreach ($primes500 as $p) {
    if ($p > 15) break;
    $q = 30 - $p;
    if (isset($primesIndex[$q])) {
        $pary30[] = "[$p+$q]";
    }
}
echo "Pary Goldbacha dla 30: " . implode(", ", $pary30) . "\n";
