<?php
/**
 * Zadanie 9: Interaktywny Interpreter Poleceń (Mini-REPL)
 *
 * Pętla while → readline → explode → switch/case
 * Stan trzymany w $dane; historia ostatnich 10 komend.
 *
 * Uruchom w terminalu: php zadanie_9.php
 */

// ---------------------------------------------------------------------------
// Pomocnicze: wypisz tablicę w formacie [a, b, c]
// ---------------------------------------------------------------------------
function pokazTablice(array $dane): void
{
    echo "[" . implode(", ", $dane) . "]\n";
}

// ---------------------------------------------------------------------------
// stats — suma, średnia, min, max BEZ wbudowanych min()/max()
// ---------------------------------------------------------------------------
function obliczStatystyki(array $dane): void
{
    if (count($dane) === 0) {
        echo "Tablica jest pusta.\n";
        return;
    }

    $suma = 0.0;
    $min  = $dane[0];
    $max  = $dane[0];

    foreach ($dane as $v) {
        $suma += $v;
        if ($v < $min) $min = $v;
        if ($v > $max) $max = $v;
    }

    $srednia = $suma / count($dane);
    printf("Suma: %g | Średnia: %g | Min: %g | Max: %g\n", $suma, $srednia, $min, $max);
}

// ---------------------------------------------------------------------------
// Pętla główna REPL
// ---------------------------------------------------------------------------
$dane     = [];
$historia = [];

echo "Mini-REPL | wpisz 'help' aby zobaczyć dostępne komendy\n";

while (true) {
    $linia = readline(">> ");

    if ($linia === false || trim($linia) === '') {
        continue;
    }

    // Rozbij na maksymalnie 3 części: polecenie, arg1, reszta
    $czesci    = explode(' ', trim($linia), 3);
    $polecenie = strtolower($czesci[0]);
    $arg1      = $czesci[1] ?? null;
    $arg2      = $czesci[2] ?? null;

    $wykonano = true;   // flaga do historii

    switch ($polecenie) {

        // --- Modyfikacje ---
        case 'push':
            if ($arg1 === null) { echo "Brak argumentu dla: push\n"; $wykonano = false; break; }
            $dane[] = (float)$arg1;
            pokazTablice($dane);
            break;

        case 'pop':
            if (count($dane) === 0) { echo "Tablica jest pusta.\n"; $wykonano = false; break; }
            array_splice($dane, -1, 1);         // odpowiednik array_pop — przez array_splice
            pokazTablice($dane);
            break;

        case 'insert':
            if ($arg1 === null || $arg2 === null) {
                echo "Użycie: insert <idx> <wartość>\n"; $wykonano = false; break;
            }
            $idx = (int)$arg1;
            array_splice($dane, $idx, 0, [(float)$arg2]);
            pokazTablice($dane);
            break;

        case 'delete':
            if ($arg1 === null) { echo "Brak argumentu dla: delete\n"; $wykonano = false; break; }
            $idx = (int)$arg1;
            if ($idx < 0 || $idx >= count($dane)) {
                echo "Indeks poza zakresem.\n"; $wykonano = false; break;
            }
            array_splice($dane, $idx, 1);
            pokazTablice($dane);
            break;

        // --- Sortowanie ---
        case 'sort':
            sort($dane);
            pokazTablice($dane);
            break;

        case 'rsort':
            rsort($dane);
            pokazTablice($dane);
            break;

        // --- Filtrowanie i unikaty ---
        case 'filter':
            // Użycie: filter > 5   lub   filter <= 10
            if ($arg1 === null || $arg2 === null) {
                echo "Użycie: filter <operator> <wartość>  (np. filter > 4)\n";
                $wykonano = false; break;
            }
            $op  = $arg1;
            $val = (float)$arg2;
            $dane = array_values(array_filter($dane, function (float $x) use ($op, $val): bool {
                return match ($op) {
                    '>'  => $x > $val,
                    '<'  => $x < $val,
                    '>=' => $x >= $val,
                    '<=' => $x <= $val,
                    '==' => $x == $val,
                    '!=' => $x != $val,
                    default => true,
                };
            }));
            pokazTablice($dane);
            break;

        case 'unique':
            $dane = array_values(array_unique($dane));
            pokazTablice($dane);
            break;

        case 'reverse':
            $dane = array_reverse($dane);
            pokazTablice($dane);
            break;

        // --- Widoki (nie modyfikują tablicy) ---
        case 'chunk':
            if ($arg1 === null || (int)$arg1 < 1) {
                echo "Użycie: chunk <n>\n"; $wykonano = false; break;
            }
            $n      = (int)$arg1;
            $chunks = array_chunk($dane, $n);
            foreach ($chunks as $i => $chunk) {
                printf("Chunk %d: [%s]\n", $i + 1, implode(', ', $chunk));
            }
            break;

        case 'slice':
            if ($arg1 === null) { echo "Użycie: slice <od> [ile]\n"; $wykonano = false; break; }
            $od  = (int)$arg1;
            $ile = $arg2 !== null ? (int)$arg2 : null;
            $fragment = array_slice($dane, $od, $ile);
            pokazTablice($fragment);
            break;

        case 'stats':
            obliczStatystyki($dane);
            break;

        case 'show':
            pokazTablice($dane);
            break;

        // --- Zarządzanie stanem ---
        case 'reset':
            $dane = [];
            echo "Tablica wyczyszczona.\n";
            break;

        case 'save':
            echo json_encode(['dane' => $dane], JSON_UNESCAPED_UNICODE) . "\n";
            break;

        case 'history':
            if (count($historia) === 0) {
                echo "Historia jest pusta.\n";
            } else {
                foreach ($historia as $i => $cmd) {
                    printf("%d: %s\n", $i + 1, $cmd);
                }
            }
            $wykonano = false;   // historia nie zapisuje sama siebie
            break;

        case 'help':
            echo "Dostępne komendy:\n";
            echo "  push <v>          — dodaj wartość na koniec\n";
            echo "  pop               — usuń ostatni element\n";
            echo "  insert <idx> <v>  — wstaw wartość na pozycję idx\n";
            echo "  delete <idx>      — usuń element na pozycji idx\n";
            echo "  sort / rsort      — sortuj rosnąco / malejąco\n";
            echo "  filter <op> <v>   — filtruj wg warunku (> < >= <= == !=)\n";
            echo "  unique            — usuń duplikaty\n";
            echo "  reverse           — odwróć kolejność\n";
            echo "  chunk <n>         — podziel na grupy po n elementów\n";
            echo "  slice <od> [ile]  — wypisz fragment (nie modyfikuje tablicy)\n";
            echo "  stats             — suma, średnia, min, max\n";
            echo "  show              — wypisz aktualną tablicę\n";
            echo "  reset             — wyczyść tablicę\n";
            echo "  save              — wypisz tablicę jako JSON\n";
            echo "  history           — ostatnie 10 komend\n";
            echo "  help              — ta lista\n";
            echo "  exit              — zakończ\n";
            $wykonano = false;
            break;

        case 'exit':
            echo "Do widzenia!\n";
            exit(0);

        default:
            echo "Nieznane polecenie: $polecenie\n";
            $wykonano = false;
    }

    // Zapisz do historii ostatnich 10 komend
    if ($wykonano) {
        $historia[] = trim($linia);
        $historia   = array_slice($historia, -10);
    }
}
