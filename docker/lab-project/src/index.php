<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$errors = [];
$successMessage = "";

if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    if (isset($_SESSION['tasks'][$id])) {
        unset($_SESSION['tasks'][$id]);
        $_SESSION['tasks'] = array_values($_SESSION['tasks']); 
        $_SESSION['success'] = "Zadanie zostało pomyślnie usunięte.";
    }
    header("Location: index.php");
    exit;
}

if (isset($_POST['delete_all'])) {
    $_SESSION['tasks'] = [];
    $_SESSION['success'] = "Wszystkie zadania zostały usunięte.";
    header("Location: index.php");
    exit;
}

$formData = [
    'title' => '', 'category' => '', 'description' => '', 'priority' => '',
    'status' => '', 'due-date' => '', 'time' => '', 'location' => '', 
    'assignee' => '', 'resources' => []
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formData = [
        'title'         => trim($_POST['title'] ?? ''),
        'category'      => $_POST['category'] ?? '',
        'description'   => trim($_POST['description'] ?? ''),
        'priority'      => $_POST['priority'] ?? '',
        'status'        => $_POST['status'] ?? '',
        'due-date'      => trim($_POST['due-date'] ?? ''),
        'time'          => $_POST['time'] ?? '',
        'location'      => trim($_POST['location'] ?? ''),
        'assignee'      => trim($_POST['assignee'] ?? ''),
        'resources'     => $_POST['resources'] ?? []
    ];

    if (empty($formData['title'])) {
        $errors[] = "Tytuł zadania jest wymagany.";
    }
    if (empty($formData['category'])) {
        $errors[] = "Kategoria jest wymagana.";
    }
    if (empty($formData['priority'])) {
        $errors[] = "Priorytet jest wymagany.";
    }
    if (empty($formData['due-date'])) {
        $errors[] = "Data wykonania jest wymagana.";
    }

    if (empty($errors)) {
        $newTask = $formData; 
        $newTask['resources'] = !empty($formData['resources']) ? implode(", ", $formData['resources']) : 'Brak';

        $_SESSION['tasks'][] = $newTask;
        $_SESSION['success'] = "Zadanie zostało pomyślnie dodane!";
        
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rozbudowany Menedżer Zadań</title>
    <link rel="stylesheet" href="styles/index.css">
</head> 
<body>

    <header class="main-header">
        <h1>Rozbudowany Menedżer Zadań</h1>
        <div class="blue-line"></div>
    </header>

    <div class="container">
        <h2>Dodaj nowe zadanie</h2>

        <?php if (!empty($errors)): ?>
            <section class="alert">
                <p style="margin: 0 0 10px 0; font-weight: bold;">Proszę poprawić poniższe błędy:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <section class="alert" style="background-color: #d4edda; color: #155724; border-left-color: #28a745;">
                <p style="margin: 0; font-weight: bold;"><?= htmlspecialchars($successMessage) ?></p>
            </section>
        <?php endif; ?>

        <form action="index.php" method="POST" novalidate>
            <div class="grid-row grid-2-1">
                <div class="field">
                    <label for="title">Tytuł zadania:<span class="req">*</span></label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($formData['title']) ?>">
                </div>
                <div class="field">
                    <label for="category">Kategoria:<span class="req">*</span></label>
                    <select id="category" name="category">
                        <option value="">Wybierz kategorię</option>
                        <option value="home"  <?= $formData['category'] === 'home' ? 'selected' : '' ?>>Domowe</option>
                        <option value="work"  <?= $formData['category'] === 'work' ? 'selected' : '' ?>>Praca</option>
                        <option value="study" <?= $formData['category'] === 'study' ? 'selected' : '' ?>>Nauka</option>
                        <option value="hobby" <?= $formData['category'] === 'hobby' ? 'selected' : '' ?>>Hobby</option>
                        <option value="other" <?= $formData['category'] === 'other' ? 'selected' : '' ?>>Inne</option>
                    </select>
                </div>
            </div>

            <div class="grid-row full-width">
                <div class="field">
                    <label for="description">Opis zadania:</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($formData['description']) ?></textarea>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="priority">Priorytet:<span class="req">*</span></label>
                    <select id="priority" name="priority">
                        <option value="">Wybierz priorytet</option>
                        <option value="1" <?= $formData['priority'] === '1' ? 'selected' : '' ?>>Niski</option>
                        <option value="2" <?= $formData['priority'] === '2' ? 'selected' : '' ?>>Średni</option>
                        <option value="3" <?= $formData['priority'] === '3' ? 'selected' : '' ?>>Wysoki</option>
                    </select>
                </div>
                <div class="field">
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">Wybierz status</option>
                        <option value="new" <?= $formData['status'] === 'new' ? 'selected' : '' ?>>Nowe</option>
                        <option value="pending" <?= $formData['status'] === 'pending' ? 'selected' : '' ?>>W trakcie</option>
                        <option value="done" <?= $formData['status'] === 'done' ? 'selected' : '' ?>>Zakończone</option>
                    </select>
                </div>
                <div class="field">
                    <label for="due-date">Data wykonania:<span class="req">*</span></label>
                    <input type="text" id="due-date" name="due-date" placeholder="14.04.2025" value="<?= htmlspecialchars($formData['due-date']) ?>">
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="time">Szacowany czas (minuty):</label>
                    <input type="number" id="time" name="time" placeholder="" value="<?= htmlspecialchars($formData['time']) ?>">
                </div>
                <div class="field">
                    <label for="location">Lokalizacja:</label>
                    <input type="text" id="location" name="location" value="<?= htmlspecialchars($formData['location']) ?>">
                </div>
                <div class="field">
                    <label for="assignee">Osoba przypisana:</label>
                    <input type="text" id="assignee" name="assignee" value="<?= htmlspecialchars($formData['assignee']) ?>">
                </div>
            </div>

            <div class="resources-section">
                <div class="resources-title">Potrzebne zasoby:</div>
                <div class="checkbox-group">
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Komputer" <?= in_array('Komputer', $formData['resources']) ? 'checked' : '' ?>> Komputer</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Internet" <?= in_array('Internet', $formData['resources']) ? 'checked' : '' ?>> Internet</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Telefon" <?= in_array('Telefon', $formData['resources']) ? 'checked' : '' ?>> Telefon</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Samochód" <?= in_array('Samochód', $formData['resources']) ? 'checked' : '' ?>> Samochód</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Książka" <?= in_array('Książka', $formData['resources']) ? 'checked' : '' ?>> Książka</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Narzędzia" <?= in_array('Narzędzia', $formData['resources']) ? 'checked' : '' ?>> Narzędzia</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Dokumenty" <?= in_array('Dokumenty', $formData['resources']) ? 'checked' : '' ?>> Dokumenty</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Inne" <?= in_array('Inne', $formData['resources']) ? 'checked' : '' ?>> Inne</label>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-submit">Dodaj</button>
                <a href="index.php" class="btn btn-clear" style="text-decoration: none; text-align: center; display: inline-block; box-sizing: border-box;">Wyczyść</a>
            </div>
        </form>
    </div>

    <div class="container table-container">
        <h2>Lista zapisanych zadań</h2>

        <?php
        $totalTasks = count($_SESSION['tasks']);
        $p_low = 0; $p_mid = 0; $p_high = 0;
        $s_new = 0; $s_pending = 0; $s_done = 0; $s_none = 0;

        foreach ($_SESSION['tasks'] as $t) {
            
            if ($t['priority'] === '1') $p_low++;
            elseif ($t['priority'] === '2') $p_mid++;
            elseif ($t['priority'] === '3') $p_high++;

            if ($t['status'] === 'new') $s_new++;
            elseif ($t['status'] === 'pending') $s_pending++;
            elseif ($t['status'] === 'done') $s_done++;
            else $s_none++;
        }
        ?>

        <div class="summary-panel">
            <div class="summary-box">
                <h3>Wszystkie zadania</h3>
                <p class="summary-number"><?= $totalTasks ?></p>
            </div>
            <div class="summary-box">
                <h3>Wg priorytetu</h3>
                <ul>
                    <li><span>Wysoki:</span> <strong><?= $p_high ?></strong></li>
                    <li><span>Średni:</span> <strong><?= $p_mid ?></strong></li>
                    <li><span>Niski:</span> <strong><?= $p_low ?></strong></li>
                </ul>
            </div>
            <div class="summary-box">
                <h3>Wg statusu</h3>
                <ul>
                    <li><span>Nowe:</span> <strong><?= $s_new ?></strong></li>
                    <li><span>W trakcie:</span> <strong><?= $s_pending ?></strong></li>
                    <li><span>Zakończone:</span> <strong><?= $s_done ?></strong></li>
                    <li><span>Brak statusu:</span> <strong><?= $s_none ?></strong></li>
                </ul>
            </div>
        </div>
        
        <?php if (empty($_SESSION['tasks'])): ?>
            <section class="alert empty-state">
                <p>Brak dodanych zadań. Wypełnij formularz powyżej, aby dodać pierwsze zadanie.</p>
            </section>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Tytuł</th>
                            <th>Kategoria</th>
                            <th>Opis</th>
                            <th>Priorytet</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Czas (min)</th>
                            <th>Lokalizacja</th>
                            <th>Przypisany</th>
                            <th>Zasoby</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><?= htmlspecialchars($task['category']) ?></td>
                                <td><?= htmlspecialchars($task['description']) ?></td>
                                <td><?= htmlspecialchars($task['priority']) ?></td>
                                <td><?= htmlspecialchars($task['status']) ?></td>
                                <td><?= htmlspecialchars($task['due-date']) ?></td>
                                <td><?= htmlspecialchars($task['time']) ?></td>
                                <td><?= htmlspecialchars($task['location']) ?></td>
                                <td><?= htmlspecialchars($task['assignee']) ?></td>
                                <td><?= htmlspecialchars($task['resources']) ?></td>
                                <td>
                                    <form action="index.php" method="POST" style="margin: 0;">
                                        <input type="hidden" name="delete_id" value="<?= $index ?>">
                                        <button type="submit" class="btn btn-delete btn-small">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; text-align: right;">
                <form action="index.php" method="POST">
                    <input type="hidden" name="delete_all" value="1">
                    <button type="submit" class="btn btn-delete" onclick="return confirm('Czy na pewno chcesz usunąć WSZYSTKIE zadania? Tej operacji nie można cofnąć.');">
                        Usuń wszystkie zadania
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>