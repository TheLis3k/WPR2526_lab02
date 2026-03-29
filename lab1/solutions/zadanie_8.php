<?php
/**
 * Zadanie 8: Pipeline ETL — Walidacja, Dedulikacja i Normalizacja
 *
 * Proceduralny potok: Extract → Validate → Transform → Load
 * Każda funkcja jest czysta — nie modyfikuje wejścia, zwraca nową tablicę.
 */

// ---------------------------------------------------------------------------
// Dane wejściowe — 15 rekordów z celowymi błędami
// ---------------------------------------------------------------------------
$rekordy = [
    ["id"=>1,  "imie"=>"anna",    "wiek"=>"25",  "email"=>"anna@test.com",   "wynik"=>92.5],
    ["id"=>2,  "imie"=>"Bartosz", "wiek"=>"abc", "email"=>"bartosz@test.com","wynik"=>78.0],
    ["id"=>3,  "imie"=>"celina",  "wiek"=>"31",  "email"=>"celina@test.com", "wynik"=>105.0],
    ["id"=>4,  "imie"=>"Dawid",   "wiek"=>"45",  "email"=>"",               "wynik"=>66.5],
    ["id"=>5,  "imie"=>"EWA",     "wiek"=>"28",  "email"=>"ewa@test.com",    "wynik"=>88.0],
    ["id"=>6,  "imie"=>"filip",   "wiek"=>"130", "email"=>"filip@test.com",  "wynik"=>74.0],
    ["id"=>7,  "imie"=>"Grażyna", "wiek"=>"52",  "email"=>"anna@test.com",   "wynik"=>91.0],
    ["id"=>8,  "imie"=>"Henryk",  "wiek"=>"19",  "email"=>"henryk@test.com", "wynik"=>-5.0],
    ["id"=>9,  "imie"=>"irena",   "wiek"=>"37",  "email"=>"irena@test.com",  "wynik"=>83.5],
    ["id"=>10, "imie"=>"JANEK",   "wiek"=>"22",  "email"=>"janek@test.com",  "wynik"=>55.0],
    ["id"=>11, "imie"=>"Kasia",   "wiek"=>"29",  "email"=>"kasia@test.com",  "wynik"=>97.0],
    ["id"=>12, "imie"=>"Leon",    "wiek"=>"41",  "email"=>"leon@test.com",   "wynik"=>62.0],
    ["id"=>13, "imie"=>"Marta",   "wiek"=>"0",   "email"=>"marta@test.com",  "wynik"=>79.5],
    ["id"=>14, "imie"=>"norbert", "wiek"=>"33",  "email"=>"norbert@test.com","wynik"=>86.0],
    ["id"=>15, "imie"=>"Ola",     "wiek"=>"26",  "email"=>"ola@test.com",    "wynik"=>91.0],
];

// ===========================================================================
// ETAP E — Walidacja (Extract & Validate)
// Zwraca ['valid' => [...], 'rejected' => [...powody...]]
// ===========================================================================
function waliduj(array $dane): array
{
    $valid    = [];
    $rejected = [];

    foreach ($dane as $r) {
        // Sprawdzenie wieku: musi być liczbą całkowitą w zakresie [1, 120]
        if (!is_numeric($r['wiek']) || (int)$r['wiek'] < 1 || (int)$r['wiek'] > 120) {
            $rejected[] = sprintf("ID %-2d (%-8s): nieprawidłowy wiek '%s'",
                                  $r['id'], $r['imie'], $r['wiek']);
            continue;
        }

        // Sprawdzenie wyniku: liczba z zakresu [0.0, 100.0]
        if (!is_numeric($r['wynik']) || $r['wynik'] < 0 || $r['wynik'] > 100) {
            $rejected[] = sprintf("ID %-2d (%-8s): wynik poza zakresem [0–100]: %s",
                                  $r['id'], $r['imie'], $r['wynik']);
            continue;
        }

        // Sprawdzenie emaila: nie może być pusty
        if (trim($r['email']) === '') {
            $rejected[] = sprintf("ID %-2d (%-8s): pusty email",
                                  $r['id'], $r['imie']);
            continue;
        }

        $valid[] = $r;
    }

    return ['valid' => $valid, 'rejected' => $rejected];
}

// ===========================================================================
// ETAP T — Transformacja (Transform)
// Dedulikacja wg emaila + normalizacja nazw i typów
// Zwraca nową tablicę — nie modyfikuje wejścia
// ===========================================================================
function transformuj(array $dane): array
{
    // Dedulikacja: zachowaj tylko pierwszy wpis dla każdego emaila
    $widzianeEmaile = [];
    $zdeduplikowane = [];

    foreach ($dane as $r) {
        if (isset($widzianeEmaile[$r['email']])) {
            continue;   // pomijamy duplikat
        }
        $widzianeEmaile[$r['email']] = true;
        $zdeduplikowane[] = $r;
    }

    // Normalizacja: ujednolicenie imion, rzutowanie typów
    $znormalizowane = array_map(function (array $r): array {
        $r['imie'] = ucfirst(strtolower($r['imie']));
        $r['wiek'] = (int)$r['wiek'];
        $r['wynik'] = (float)$r['wynik'];
        return $r;
    }, $zdeduplikowane);

    return $znormalizowane;
}

// ===========================================================================
// ETAP L — Ładowanie i analiza (Load)
// ===========================================================================
function analizuj(array $dane): void
{
    // Przypisz oceny literowe
    $oceny = [];
    foreach ($dane as &$r) {
        $r['ocena'] = match (true) {
            $r['wynik'] >= 90 => 'A',
            $r['wynik'] >= 75 => 'B',
            $r['wynik'] >= 60 => 'C',
            default           => 'D',
        };
        $oceny[$r['ocena']][] = $r['wynik'];
    }
    unset($r);

    // Wypisz tabelę
    printf("%-12s | %4s | %-25s | %6s | %s\n",
           "Imię", "Wiek", "Email", "Wynik", "Ocena");
    echo str_repeat("-", 65) . "\n";

    foreach ($dane as $r) {
        printf("%-12s | %4d | %-25s | %5.1f | %s\n",
               $r['imie'], $r['wiek'], $r['email'], $r['wynik'], $r['ocena']);
    }

    // Statystyki
    echo "\nRozkład ocen:\n";
    ksort($oceny);
    foreach ($oceny as $lit => $wartosci) {
        $srednia = array_sum($wartosci) / count($wartosci);
        printf("  %s: %d studentów, średnia: %.1f%%\n",
               $lit, count($wartosci), $srednia);
    }
}

// ===========================================================================
// Uruchomienie pipeline'u
// ===========================================================================
echo "=== Etap E: Walidacja ===\n";
$wynikWalidacji = waliduj($rekordy);

printf("Odrzucone rekordy (%d):\n", count($wynikWalidacji['rejected']));
foreach ($wynikWalidacji['rejected'] as $msg) {
    echo "  - $msg\n";
}

$poprawne      = $wynikWalidacji['valid'];
$przetworzone  = transformuj($poprawne);

// Wykryj duplikaty (rekordy odrzucone przez transformację)
$duplikaty = count($poprawne) - count($przetworzone);
if ($duplikaty > 0) {
    // Znajdź i wypisz zduplikowane emaile
    $emaileCnt = array_count_values(array_column($poprawne, 'email'));
    foreach ($emaileCnt as $email => $cnt) {
        if ($cnt > 1) {
            $dupl = array_filter($poprawne, fn($r) => $r['email'] === $email);
            $dupl = array_values($dupl);
            for ($i = 1; $i < count($dupl); $i++) {
                printf("  - ID %-2d (%-8s): duplikat email '%s'\n",
                       $dupl[$i]['id'], $dupl[$i]['imie'], $email);
            }
        }
    }
}

printf("\n=== Etap L: Finalna baza (%d rekordów) ===\n", count($przetworzone));
analizuj($przetworzone);
