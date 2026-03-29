<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$errors      = [];
$message     = '';
$messageType = '';

// ── Etap 3: usunięcie pojedynczego zadania ────────────────────────────────────
if (isset($_POST['delete'])) {
    $idx = (int) ($_POST['delete_index'] ?? -1);
    if (isset($_SESSION['tasks'][$idx])) {
        array_splice($_SESSION['tasks'], $idx, 1);
        $message     = 'Zadanie zostało usunięte.';
        $messageType = 'info';
    }
}

// ── Etap 3: wyczyszczenie całej listy ────────────────────────────────────────
if (isset($_POST['clear_all'])) {
    $_SESSION['tasks'] = [];
    $message     = 'Lista zadań została wyczyszczona.';
    $messageType = 'info';
}

// ── Etap 1 + 2: dodanie nowego zadania ───────────────────────────────────────
if (isset($_POST['submit'])) {
    $title    = trim($_POST['title']    ?? '');
    $category = $_POST['category']      ?? '';
    $priority = $_POST['priority']      ?? '';
    $dueDate  = $_POST['due_date']      ?? '';
    $desc     = trim($_POST['desc']     ?? '');
    $status   = $_POST['status']        ?? '';
    $time     = $_POST['time']          ?? '';
    $location = trim($_POST['location'] ?? '');
    $person   = trim($_POST['person']   ?? '');
    $resources = isset($_POST['resources']) ? (array) $_POST['resources'] : [];

    // Etap 2: walidacja
    if ($title === '') {
        $errors[] = 'Tytuł zadania jest wymagany.';
    }
    if ($category === '') {
        $errors[] = 'Kategoria jest wymagana.';
    }
    if ($priority === '') {
        $errors[] = 'Priorytet jest wymagany.';
    }
    if ($dueDate === '') {
        $errors[] = 'Data wykonania jest wymagana.';
    }

    if (empty($errors)) {
        $_SESSION['tasks'][] = [
            'title'     => $title,
            'category'  => $category,
            'desc'      => $desc,
            'priority'  => $priority,
            'status'    => $status,
            'due_date'  => $dueDate,
            'time'      => $time,
            'location'  => $location,
            'person'    => $person,
            'resources' => $resources,
        ];
        $message     = "Zadanie '" . htmlspecialchars($title) . "' zostało dodane.";
        $messageType = 'success';
        // Wyczyść POST żeby sticky form nie wyświetlał starych wartości po sukcesie
        $_POST = [];
    } else {
        $messageType = 'error';
    }
}

// ── Etap 4: obliczanie statystyk ─────────────────────────────────────────────
$stats = ['total' => count($_SESSION['tasks']), 'priority' => [], 'status' => []];
foreach ($_SESSION['tasks'] as $t) {
    $p = $t['priority'] ?: 'Brak';
    $s = $t['status']   ?: 'Brak';
    $stats['priority'][$p] = ($stats['priority'][$p] ?? 0) + 1;
    $stats['status'][$s]   = ($stats['status'][$s]   ?? 0) + 1;
}

// Pomocnicza funkcja: wartość z $_POST (dla sticky form)
function old(string $key, string $default = ''): string {
    return htmlspecialchars($_POST[$key] ?? $default);
}
function oldSelected(string $key, string $value): string {
    return (($_POST[$key] ?? '') === $value) ? 'selected' : '';
}
function oldChecked(string $key, string $value): string {
    return in_array($value, (array)($_POST[$key] ?? []), true) ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menedżer Zadań</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
            padding: 24px;
        }

        header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 3px solid #3498db;
        }
        header h1 { font-size: 1.8rem; color: #2c3e50; }

        main { max-width: 960px; margin: 0 auto; }

        .card {
            background: #fff;
            border-radius: 8px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            margin-bottom: 28px;
        }
        .card h2 { font-size: 1.15rem; margin-bottom: 20px; color: #2c3e50; }

        /* Alerty */
        .alert {
            border-left: 4px solid;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: .9rem;
        }
        .alert ul { margin: 6px 0 0 18px; }
        .alert-error   { border-color: #e74c3c; background: #fdecea; color: #c0392b; }
        .alert-success { border-color: #27ae60; background: #eafaf1; color: #1e8449; }
        .alert-info    { border-color: #3498db; background: #ebf5fb; color: #1a6fa8; }

        /* Grid formularza */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 24px;
        }
        .full-width { grid-column: 1 / -1; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }

        label { font-size: .85rem; font-weight: bold; color: #555; }
        label .req { color: #e74c3c; }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            padding: 9px 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: .95rem;
            width: 100%;
            transition: border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,.15);
        }
        textarea { resize: vertical; min-height: 90px; }

        /* Checkboxy */
        .checkboxes { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 4px; }
        .checkboxes label { font-weight: normal; display: flex; align-items: center; gap: 5px; }

        /* Przyciski */
        .btn-row { display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px; }
        button {
            padding: 10px 22px;
            border: none;
            border-radius: 5px;
            font-size: .95rem;
            cursor: pointer;
            transition: opacity .2s;
        }
        button:hover { opacity: .82; }
        .btn-primary { background: #3498db; color: #fff; }
        .btn-reset   { background: #e0e0e0; color: #333; }
        .btn-danger  { background: #e74c3c; color: #fff; padding: 5px 12px; font-size: .82rem; }
        .btn-outline { background: transparent; border: 1px solid #e74c3c; color: #e74c3c;
                       padding: 7px 16px; font-size: .88rem; }

        /* Etap 4: Panel statystyk */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            text-align: center;
        }
        .stat-card .stat-number { font-size: 2rem; font-weight: bold; color: #3498db; }
        .stat-card .stat-label  { font-size: .8rem; color: #888; margin-bottom: 10px; }
        .stat-card .stat-rows   { font-size: .85rem; text-align: left; margin-top: 10px; border-top: 1px solid #eee; padding-top: 8px; }
        .stat-rows span { display: flex; justify-content: space-between; padding: 2px 0; }
        .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
        .dot-red    { background: #e74c3c; }
        .dot-yellow { background: #f39c12; }
        .dot-green  { background: #27ae60; }
        .dot-blue   { background: #3498db; }
        .dot-gray   { background: #95a5a6; }

        /* Tabela zadań */
        .table-actions { display: flex; justify-content: flex-end; margin-bottom: 12px; }

        .task-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
        .task-table th {
            background: #3498db; color: #fff;
            text-align: left; padding: 10px 12px;
        }
        .task-table td { padding: 9px 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .task-table tr:last-child td { border-bottom: none; }
        .task-table tr:nth-child(even) td { background: #f9f9f9; }

        .badge {
            display: inline-block; padding: 2px 9px;
            border-radius: 12px; font-size: .78rem; font-weight: bold;
        }
        .badge-wysoki { background: #fdecea; color: #c0392b; }
        .badge-sredni { background: #fef9e7; color: #b7770d; }
        .badge-niski  { background: #eafaf1; color: #1e8449; }

        .empty-info { text-align: center; color: #aaa; padding: 24px; font-style: italic; }

        footer { text-align: center; margin-top: 32px; font-size: .8rem; color: #aaa; }

        /* RWD */
        @media (max-width: 600px) {
            .form-grid, .stats-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: 1; }
            .btn-row { flex-direction: column; }
            .task-table { font-size: .76rem; }
            .task-table th, .task-table td { padding: 7px 8px; }
        }
    </style>
</head>
<body>

<header>
    <h1>Rozbudowany Menedżer Zadań</h1>
</header>

<main>

    <!-- FORMULARZ ─────────────────────────────────────────────────────────── -->
    <div class="card">
        <h2>Dodaj nowe zadanie</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>⚠ Popraw następujące błędy:</strong>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($message !== '' && $messageType !== 'info'): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php">
            <div class="form-grid">

                <div class="form-group full-width">
                    <label for="title">Tytuł zadania: <span class="req">*</span></label>
                    <input type="text" id="title" name="title"
                           value="<?= old('title') ?>"
                           placeholder="Wpisz tytuł zadania">
                </div>

                <div class="form-group full-width">
                    <label for="desc">Opis zadania:</label>
                    <textarea id="desc" name="desc"
                              placeholder="Opcjonalny opis..."><?= old('desc') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="priority">Priorytet: <span class="req">*</span></label>
                    <select id="priority" name="priority">
                        <option value="">Wybierz priorytet</option>
                        <option value="Wysoki" <?= oldSelected('priority','Wysoki') ?>>Wysoki</option>
                        <option value="Średni" <?= oldSelected('priority','Średni') ?>>Średni</option>
                        <option value="Niski"  <?= oldSelected('priority','Niski')  ?>>Niski</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">Wybierz status</option>
                        <option value="Nowe"       <?= oldSelected('status','Nowe')       ?>>Nowe</option>
                        <option value="W toku"     <?= oldSelected('status','W toku')     ?>>W toku</option>
                        <option value="Zakończone" <?= oldSelected('status','Zakończone') ?>>Zakończone</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">Kategoria: <span class="req">*</span></label>
                    <select id="category" name="category">
                        <option value="">Wybierz kategorię</option>
                        <option value="Domowe"  <?= oldSelected('category','Domowe')  ?>>Domowe</option>
                        <option value="Praca"   <?= oldSelected('category','Praca')   ?>>Praca</option>
                        <option value="Nauka"   <?= oldSelected('category','Nauka')   ?>>Nauka</option>
                        <option value="Hobby"   <?= oldSelected('category','Hobby')   ?>>Hobby</option>
                        <option value="Inne"    <?= oldSelected('category','Inne')    ?>>Inne</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="due_date">Data wykonania: <span class="req">*</span></label>
                    <input type="date" id="due_date" name="due_date"
                           value="<?= old('due_date') ?>">
                </div>

                <div class="form-group">
                    <label for="time">Szacowany czas (minuty):</label>
                    <input type="number" id="time" name="time" min="1"
                           value="<?= old('time') ?>" placeholder="np. 60">
                </div>

                <div class="form-group">
                    <label for="location">Lokalizacja:</label>
                    <input type="text" id="location" name="location"
                           value="<?= old('location') ?>" placeholder="np. Dom, Biuro">
                </div>

                <div class="form-group">
                    <label for="person">Osoba przypisana:</label>
                    <input type="text" id="person" name="person"
                           value="<?= old('person') ?>" placeholder="Imię i nazwisko">
                </div>

                <div class="form-group full-width">
                    <label>Potrzebne zasoby:</label>
                    <div class="checkboxes">
                        <?php foreach (['Komputer','Internet','Telefon','Samochód','Książka','Narzędzia','Dokumenty','Inne'] as $r): ?>
                            <label>
                                <input type="checkbox" name="resources[]"
                                       value="<?= htmlspecialchars($r) ?>"
                                       <?= oldChecked('resources', $r) ?>>
                                <?= htmlspecialchars($r) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div class="btn-row">
                <button type="reset" class="btn-reset">Wyczyść</button>
                <button type="submit" name="submit" class="btn-primary">Dodaj zadanie</button>
            </div>
        </form>
    </div>

    <!-- ETAP 4: PANEL STATYSTYK ───────────────────────────────────────────── -->
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-number"><?= $stats['total'] ?></div>
            <div class="stat-label">Łącznie zadań</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Według priorytetu</div>
            <div class="stat-rows">
                <?php foreach (['Wysoki' => 'dot-red', 'Średni' => 'dot-yellow', 'Niski' => 'dot-green'] as $p => $cls): ?>
                    <span>
                        <span><span class="dot <?= $cls ?>"></span><?= $p ?></span>
                        <strong><?= $stats['priority'][$p] ?? 0 ?></strong>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Według statusu</div>
            <div class="stat-rows">
                <?php foreach (['Nowe' => 'dot-blue', 'W toku' => 'dot-yellow', 'Zakończone' => 'dot-green'] as $s => $cls): ?>
                    <span>
                        <span><span class="dot <?= $cls ?>"></span><?= $s ?></span>
                        <strong><?= $stats['status'][$s] ?? 0 ?></strong>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- LISTA ZADAŃ ──────────────────────────────────────────────────────── -->
    <div class="card">
        <h2>Lista zadań (<?= $stats['total'] ?>)</h2>

        <?php if ($messageType === 'info'): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['tasks'])): ?>
            <div class="table-actions">
                <form method="POST" action="index.php"
                      onsubmit="return confirm('Czy na pewno chcesz usunąć wszystkie zadania?')">
                    <button type="submit" name="clear_all" class="btn-outline">
                        Wyczyść wszystkie
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if (empty($_SESSION['tasks'])): ?>
            <p class="empty-info">Brak zadań. Dodaj pierwsze zadanie powyżej.</p>
        <?php else: ?>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tytuł</th>
                        <th>Kategoria</th>
                        <th>Priorytet</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Czas (min)</th>
                        <th>Osoba</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['tasks'] as $i => $task): ?>
                        <?php
                            $badgeClass = match(strtolower($task['priority'])) {
                                'wysoki' => 'badge-wysoki',
                                'średni' => 'badge-sredni',
                                'niski'  => 'badge-niski',
                                default  => '',
                            };
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= htmlspecialchars($task['title']) ?></strong></td>
                            <td><?= htmlspecialchars($task['category']) ?></td>
                            <td>
                                <span class="badge <?= $badgeClass ?>">
                                    <?= htmlspecialchars($task['priority']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($task['status']) ?></td>
                            <td><?= htmlspecialchars($task['due_date']) ?></td>
                            <td><?= htmlspecialchars($task['time']) ?></td>
                            <td><?= htmlspecialchars($task['person']) ?></td>
                            <td>
                                <form method="POST" action="index.php"
                                      onsubmit="return confirm('Usunąć to zadanie?')">
                                    <input type="hidden" name="delete_index" value="<?= $i ?>">
                                    <button type="submit" name="delete" class="btn-danger">
                                        Usuń
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</main>

<footer>
    <p>Menedżer Zadań &mdash; WPRG Lab02 &copy; <?= date('Y') ?></p>
</footer>

</body>
</html>
