<?php
/**
 * Zadanie 7: System Rekomendacji — Korelacja Pearsona
 *
 * User-based Collaborative Filtering z korelacją Pearsona i prognozowaniem ocen.
 */

// ---------------------------------------------------------------------------
// Dane — macierz ocen 7 użytkowników × 8 produktów (null = brak oceny)
// ---------------------------------------------------------------------------
$oceny = [
    "Anna"    => [5, 4, null, 2, null, 3, 4, 5],
    "Bartek"  => [4, 5, 3, null, 2, 4, null, 4],
    "Celina"  => [5, 3, null, 3, null, 4, 5, null],
    "Dawid"   => [2, null, 4, 5, 3, null, 2, 3],
    "Ewa"     => [null, 4, 3, null, 5, 3, 4, 2],
    "Filip"   => [3, 5, 4, 2, null, 5, null, 4],
    "Grażyna" => [5, null, 2, 4, 3, 2, 5, null],
];

$produkty = ["Laptop", "Monitor", "Klawiatura", "Mysz", "Słuchawki", "Kamera", "Tablet", "Głośnik"];

// ---------------------------------------------------------------------------
// Korelacja Pearsona — tylko na wspólnych ocenach (obie wartości != null)
// ---------------------------------------------------------------------------
function pearsonSimilarity(array $u1, array $u2): float
{
    // Znajdź indeksy, gdzie obaj użytkownicy wystawili ocenę
    $wspolne = [];
    for ($i = 0; $i < count($u1); $i++) {
        if ($u1[$i] !== null && $u2[$i] !== null) {
            $wspolne[] = [$u1[$i], $u2[$i]];
        }
    }

    $n = count($wspolne);
    if ($n < 2) {
        return 0.0;                            // za mało danych — nie liczymy
    }

    // Średnie na wspólnych produktach
    $mean1 = array_sum(array_column($wspolne, 0)) / $n;
    $mean2 = array_sum(array_column($wspolne, 1)) / $n;

    $licznik = 0.0;
    $mian1   = 0.0;
    $mian2   = 0.0;

    foreach ($wspolne as [$r1, $r2]) {
        $licznik += ($r1 - $mean1) * ($r2 - $mean2);
        $mian1   += ($r1 - $mean1) ** 2;
        $mian2   += ($r2 - $mean2) ** 2;
    }

    $mianownik = sqrt($mian1 * $mian2);
    return $mianownik == 0.0 ? 0.0 : round($licznik / $mianownik, 4);
}

// ---------------------------------------------------------------------------
// Krok 1: Podobieństwo Pearsona Anny względem pozostałych użytkowników
// ---------------------------------------------------------------------------
$target       = "Anna";
$podobienstwa = [];

foreach ($oceny as $user => $ratings) {
    if ($user === $target) continue;
    $podobienstwa[$user] = pearsonSimilarity($oceny[$target], $ratings);
}

arsort($podobienstwa);

echo "Podobieństwo Pearsona dla $target:\n";
foreach ($podobienstwa as $user => $sim) {
    printf("  %-10s: %6.4f\n", $user, $sim);
}

// ---------------------------------------------------------------------------
// Krok 2: k=3 najlepsi sąsiedzi (kNN)
// ---------------------------------------------------------------------------
$k   = 3;
$knn = array_slice($podobienstwa, 0, $k, true);

$knnStr = implode(', ', array_map(
    fn($u, $s) => "$u($s)",
    array_keys($knn),
    array_values($knn)
));
echo "\nk=$k sąsiedzi $target: $knnStr\n";

// ---------------------------------------------------------------------------
// Krok 3: Prognoza ocen dla produktów, których Anna nie oceniła
// pred(A, p) = Σ(sim * r) / Σ|sim|
// ---------------------------------------------------------------------------
$annaOceny   = $oceny[$target];
$prognozy    = [];

foreach ($produkty as $idx => $produkt) {
    if ($annaOceny[$idx] !== null) continue;   // pomijamy już ocenione

    $licznik   = 0.0;
    $mianownik = 0.0;

    foreach ($knn as $user => $sim) {
        $r = $oceny[$user][$idx];
        if ($r !== null) {
            $licznik   += $sim * $r;
            $mianownik += abs($sim);
        }
    }

    if ($mianownik > 0) {
        $prognozy[$produkt] = round($licznik / $mianownik, 2);
    }
}

arsort($prognozy);

echo "\nRekomendacje dla $target (produkty nieocenione):\n";
$rank = 1;
foreach ($prognozy as $produkt => $ocena) {
    printf("  %d. %-15s — przewidywana ocena: %.2f\n", $rank++, $produkt, $ocena);
}

// ---------------------------------------------------------------------------
// Krok 4: Obsługa zimnego startu — nowy użytkownik "Hania" z jedną oceną
// ---------------------------------------------------------------------------
$hania = [4, null, null, null, null, null, null, null];   // tylko Laptop: 4

echo "\nZimny start (Hania, 1 ocena):\n";

$maKorelacje = false;
foreach ($oceny as $user => $ratings) {
    $sim = pearsonSimilarity($hania, $ratings);
    if (abs($sim) > 0.0) {
        $maKorelacje = true;
        break;
    }
}

if (!$maKorelacje) {
    echo "  Za mało wspólnych ocen z innymi użytkownikami — brak wiarygodnych korelacji.\n";
    echo "  Strategia: rekomenduj najpopularniejsze produkty (najwyższa średnia ocen).\n";

    // Oblicz popularność każdego produktu
    $srednie = [];
    foreach ($produkty as $idx => $produkt) {
        $ocenyProd = array_filter(
            array_column($oceny, $idx),
            fn($v) => $v !== null
        );
        if (count($ocenyProd) > 0) {
            $srednie[$produkt] = round(array_sum($ocenyProd) / count($ocenyProd), 2);
        }
    }
    arsort($srednie);

    echo "  Top 3 popularne produkty:\n";
    $top3 = array_slice($srednie, 0, 3, true);
    $rank = 1;
    foreach ($top3 as $prod => $avg) {
        printf("    %d. %-15s (średnia ocena: %.2f)\n", $rank++, $prod, $avg);
    }
}
