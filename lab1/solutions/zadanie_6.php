<?php
/**
 * Zadanie 6: Algorytm Zachłanny — Harmonogramowanie Zadań i Salek
 *
 * Problem wyboru zadań (interval scheduling) i przydziału do sal.
 */

// ---------------------------------------------------------------------------
// Dane — 15 zadań (czasy w minutach od północy)
// ---------------------------------------------------------------------------
$zadania = [
    ["id"=>1,  "nazwa"=>"T01", "start"=>480,  "koniec"=>600],
    ["id"=>2,  "nazwa"=>"T02", "start"=>510,  "koniec"=>720],
    ["id"=>3,  "nazwa"=>"T03", "start"=>540,  "koniec"=>660],
    ["id"=>4,  "nazwa"=>"T04", "start"=>600,  "koniec"=>690],
    ["id"=>5,  "nazwa"=>"T05", "start"=>660,  "koniec"=>780],
    ["id"=>6,  "nazwa"=>"T06", "start"=>690,  "koniec"=>840],
    ["id"=>7,  "nazwa"=>"T07", "start"=>720,  "koniec"=>810],
    ["id"=>8,  "nazwa"=>"T08", "start"=>780,  "koniec"=>900],
    ["id"=>9,  "nazwa"=>"T09", "start"=>840,  "koniec"=>960],
    ["id"=>10, "nazwa"=>"T10", "start"=>480,  "koniec"=>540],
    ["id"=>11, "nazwa"=>"T11", "start"=>570,  "koniec"=>630],
    ["id"=>12, "nazwa"=>"T12", "start"=>750,  "koniec"=>870],
    ["id"=>13, "nazwa"=>"T13", "start"=>900,  "koniec"=>990],
    ["id"=>14, "nazwa"=>"T14", "start"=>495,  "koniec"=>555],
    ["id"=>15, "nazwa"=>"T15", "start"=>870,  "koniec"=>930],
];

// ---------------------------------------------------------------------------
// Pomocnicza: przelicz minuty na format H:MM
// ---------------------------------------------------------------------------
function minutyNaCzas(int $minuty): string
{
    return sprintf("%d:%02d", intdiv($minuty, 60), $minuty % 60);
}

// ---------------------------------------------------------------------------
// Krok 1: Algorytm zachłanny — maksymalna liczba zadań w JEDNEJ sali
// Strategia: sortuj po czasie zakończenia, wybieraj niesprzeczne
// ---------------------------------------------------------------------------
function zaplanujZachlannie(array $zadania): array
{
    usort($zadania, fn($a, $b) => $a['koniec'] - $b['koniec']);

    $wybrane  = [];
    $ostatni  = -1;

    foreach ($zadania as $z) {
        if ($z['start'] >= $ostatni) {
            $wybrane[] = $z;
            $ostatni   = $z['koniec'];
        }
    }

    return $wybrane;
}

$wybrane = zaplanujZachlannie($zadania);

echo "Algorytm zachłanny (jedna sala):\n";
printf("  Wybrane zadania (%d): %s\n",
       count($wybrane),
       implode(', ', array_column($wybrane, 'nazwa')));

echo "  Kolejność decyzji:\n";
foreach ($wybrane as $z) {
    printf("    %s (%s–%s)\n",
           $z['nazwa'],
           minutyNaCzas($z['start']),
           minutyNaCzas($z['koniec']));
}

// ---------------------------------------------------------------------------
// Krok 2: Wykryj najbardziej konfliktowe zadanie
// Dwa zadania kolidują: max(s1,s2) < min(k1,k2)
// ---------------------------------------------------------------------------
$konflikty = [];

foreach ($zadania as $a) {
    $cnt = 0;
    foreach ($zadania as $b) {
        if ($a['id'] === $b['id']) continue;
        if (max($a['start'], $b['start']) < min($a['koniec'], $b['koniec'])) {
            $cnt++;
        }
    }
    $konflikty[$a['nazwa']] = $cnt;
}

arsort($konflikty);
$topNazwa = array_key_first($konflikty);
$topCnt   = $konflikty[$topNazwa];

echo "\nKonflikty:\n";
printf("  Najbardziej konfliktowe: %s (%d kolizji z innymi zadaniami)\n",
       $topNazwa, $topCnt);

// ---------------------------------------------------------------------------
// Krok 3: Minimalna liczba sal — algorytm zachłanny z wieloma zasobami
// Sortuj wg czasu startu; przypisuj do pierwszej wolnej sali
// ---------------------------------------------------------------------------
function przydzielSale(array $zadania): array
{
    usort($zadania, fn($a, $b) => $a['start'] - $b['start']);

    $sale = [];   // sale[i] = tablica zadań przypisanych do sali i

    foreach ($zadania as $z) {
        $przypisano = false;

        foreach ($sale as &$sala) {
            $ostatnieTsk = end($sala);
            if ($ostatnieTsk['koniec'] <= $z['start']) {
                $sala[]     = $z;
                $przypisano = true;
                break;
            }
        }
        unset($sala);

        if (!$przypisano) {
            $sale[] = [$z];
        }
    }

    return $sale;
}

$sale = przydzielSale($zadania);

echo "\nMinimalna liczba sal: " . count($sale) . "\n";
foreach ($sale as $i => $sala) {
    $sloty = array_map(
        fn($z) => $z['nazwa'] . " (" . minutyNaCzas($z['start']) . "–" . minutyNaCzas($z['koniec']) . ")",
        $sala
    );
    printf("  Sala %d: %s\n", $i + 1, implode(', ', $sloty));
}
