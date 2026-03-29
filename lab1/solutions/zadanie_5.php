<?php
/**
 * Zadanie 5: Agregacja Wielowymiarowa i Pivot Table
 *
 * Grupowanie transakcji wg kategorii i miesięcy, tabela przestawna,
 * odchylenie standardowe — implementacja ręczna bez funkcji statystycznych.
 */

// ---------------------------------------------------------------------------
// Dane — 20 transakcji sprzedażowych
// ---------------------------------------------------------------------------
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

// ---------------------------------------------------------------------------
// Krok 1: Budowa tabeli przestawnej (pivot) Kategoria × Miesiąc
// ---------------------------------------------------------------------------
$pivot    = [];    // $pivot[$kategoria][$miesiac] = suma kwot
$porKat   = [];    // $porKat[$kategoria] = [kwota1, kwota2, ...] — do odch. std.
$miesiace = ["2024-01" => "Styczeń", "2024-02" => "Luty", "2024-03" => "Marzec"];

foreach ($transakcje as $t) {
    $miesiac    = substr($t['data'], 0, 7);   // "2024-01"
    $kategoria  = $t['kategoria'];
    $kwota      = $t['kwota'];

    $pivot[$kategoria][$miesiac]  = ($pivot[$kategoria][$miesiac] ?? 0.0) + $kwota;
    $porKat[$kategoria][]         = $kwota;
}

ksort($pivot);     // kategorie alfabetycznie

// ---------------------------------------------------------------------------
// Krok 2: Wypisz tabelę przestawną
// ---------------------------------------------------------------------------
printf("%-14s | %8s | %8s | %8s\n", "Kategoria", "Styczeń", "Luty", "Marzec");
echo str_repeat("-", 46) . "\n";

foreach ($pivot as $kat => $mDane) {
    printf("%-14s | %8.2f | %8.2f | %8.2f\n",
           $kat,
           $mDane["2024-01"] ?? 0.0,
           $mDane["2024-02"] ?? 0.0,
           $mDane["2024-03"] ?? 0.0);
}

// ---------------------------------------------------------------------------
// Krok 3: Odchylenie standardowe dla każdej kategorii
// Wzór: σ = sqrt( (1/N) * Σ(xi - μ)² )
// ---------------------------------------------------------------------------
function odchylenieStd(array $wartosci): float
{
    $n    = count($wartosci);
    $mean = array_sum($wartosci) / $n;

    $sumKwadratow = array_reduce($wartosci, function (float $carry, float $x) use ($mean): float {
        return $carry + ($x - $mean) ** 2;
    }, 0.0);

    return sqrt($sumKwadratow / $n);
}

echo "\nOdchylenia standardowe (σ):\n";
$maxSd  = 0.0;
$maxKat = '';

foreach ($porKat as $kat => $wartosci) {
    $n    = count($wartosci);
    $mean = array_sum($wartosci) / $n;
    $sd   = odchylenieStd($wartosci);

    printf("  %-14s: σ=%.2f (n=%d, avg=%.2f zł)\n", $kat, $sd, $n, $mean);

    if ($sd > $maxSd) {
        $maxSd  = $sd;
        $maxKat = $kat;
    }
}

printf("\nKategoria o największej zmienności: %s (σ=%.2f)\n", $maxKat, $maxSd);
