<?php
/**
 * Zadanie 4: Struktury LIFO i Walidacja Wyrażeń (Stos, ONP, Nawiasy)
 *
 * Stos oparty wyłącznie na array_splice + count (zakaz array_push/pop).
 * Walidacja nawiasów, kalkulator ONP, bufor cykliczny.
 */

// ---------------------------------------------------------------------------
// Implementacja stosu przez array_splice (ZAKAZ array_push/array_pop!)
// ---------------------------------------------------------------------------
function s_push(array &$stos, $val): void
{
    array_splice($stos, count($stos), 0, [$val]);
}

function s_pop(array &$stos)
{
    if (count($stos) === 0) {
        return null;
    }
    $top = $stos[count($stos) - 1];
    array_splice($stos, -1, 1);
    return $top;
}

function s_peek(array $stos)
{
    return count($stos) > 0 ? $stos[count($stos) - 1] : null;
}

function s_empty(array $stos): bool
{
    return count($stos) === 0;
}

// ---------------------------------------------------------------------------
// Walidacja nawiasów
// Zwraca true jeśli wyrażenie jest poprawnie nawiasowane
// ---------------------------------------------------------------------------
function walidujNawiasy(string $napis): bool
{
    $stos  = [];
    $pary  = [')' => '(', ']' => '[', '}' => '{'];

    for ($i = 0; $i < strlen($napis); $i++) {
        $znak = $napis[$i];

        if (in_array($znak, ['(', '[', '{'])) {
            s_push($stos, $znak);
        } elseif (in_array($znak, [')', ']', '}'])) {
            if (s_empty($stos)) {
                return false;                  // za dużo zamykających
            }
            $top = s_pop($stos);
            if ($top !== $pary[$znak]) {
                return false;                  // niedopasowany typ nawiasu
            }
        }
    }

    return s_empty($stos);                     // stos musi być pusty na końcu
}

// ---------------------------------------------------------------------------
// Kalkulator ONP (Odwrócona Notacja Polska)
// ---------------------------------------------------------------------------
function obliczONP(string $wyrazenie): float
{
    $tokeny  = explode(' ', trim($wyrazenie));
    $stos    = [];
    $operatory = ['+', '-', '*', '/'];

    foreach ($tokeny as $token) {
        if (is_numeric($token)) {
            s_push($stos, (float)$token);
        } elseif (in_array($token, $operatory)) {
            $b = s_pop($stos);
            $a = s_pop($stos);
            switch ($token) {
                case '+': s_push($stos, $a + $b); break;
                case '-': s_push($stos, $a - $b); break;
                case '*': s_push($stos, $a * $b); break;
                case '/': s_push($stos, $a / $b); break;
            }
        }
    }

    return s_pop($stos);
}

// ---------------------------------------------------------------------------
// Bufor cykliczny o rozmiarze 5
// ---------------------------------------------------------------------------
function buforZapis(array &$bufor, int &$pos, float $wartosc, int $rozmiar = 5): void
{
    $bufor[$pos % $rozmiar] = $wartosc;
    $pos++;
}

// ---------------------------------------------------------------------------
// Dane testowe
// ---------------------------------------------------------------------------
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

// ---------------------------------------------------------------------------
// Uruchomienie
// ---------------------------------------------------------------------------
$bufor   = array_fill(0, 5, null);
$buforPos = 0;

for ($i = 0; $i < count($wyrazenia_ONP); $i++) {
    $nawiasy = walidujNawiasy($napisy_nawiasy[$i]) ? "OK  " : "BŁĄD";
    $wynik   = obliczONP($wyrazenia_ONP[$i]);

    // Usuń zbędne miejsca dziesiętne (np. 21.0 → 21, 6.6 zostaje)
    $wynikStr = ($wynik == (int)$wynik) ? (string)(int)$wynik : (string)$wynik;

    printf("[%d] Nawiasy \"%-12s\": %s | ONP \"%-35s\" = %s\n",
           $i + 1,
           $napisy_nawiasy[$i],
           $nawiasy,
           $wyrazenia_ONP[$i],
           $wynikStr);

    buforZapis($bufor, $buforPos, $wynik);
}

// Wypisz bufor (filtruj null — jeśli nie wypełniony)
$zawartoscBufora = array_filter($bufor, fn($v) => $v !== null);
$zawartoscBufora = array_map(fn($v) => ($v == (int)$v) ? (int)$v : $v, $zawartoscBufora);

echo "\nBufor cykliczny (ostatnie 5 wyników): [" . implode(", ", $zawartoscBufora) . "]\n";
