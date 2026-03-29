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
    <style>:root{--primary-blue:#2c3e50;--accent-blue:#3498db;--bg-color:#f4f7f6;--card-bg:#ffffff;--text-color:#333;--border-color:#ccc;--alert-bg:#f8d7da;--alert-text:#721c24;--required-color:#e74c3c}*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif}body{background-color:var(--bg-color);color:var(--text-color);margin:0;padding:20px}.main-header{text-align:center;max-width:900px;margin:0 auto 20px auto}.main-header h1{color:var(--primary-blue);font-size:2.2rem;margin-bottom:10px}.blue-line{height:2px;background-color:var(--accent-blue);width:100%}.container{max-width:900px;margin:0 auto;background:var(--card-bg);padding:30px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,.1)}h2{margin-top:0;color:var(--primary-blue);font-size:1.5rem;margin-bottom:20px}.alert{display:flex;align-items:center;background-color:var(--alert-bg);color:var(--alert-text);padding:15px;border-left:4px solid var(--required-color);border-radius:4px;margin-bottom:20px}.alert::before{content:'⚠';font-size:1.5rem;margin-right:15px;color:var(--required-color)}.alert ul{margin:0;padding-left:20px}.grid-row{display:grid;gap:20px;margin-bottom:20px}.grid-2-1{grid-template-columns:1fr 1fr}.grid-3{grid-template-columns:1fr 1fr 1fr}.full-width{grid-template-columns:1fr}.field{display:flex;flex-direction:column}label{font-weight:700;margin-bottom:8px;font-size:.95rem;color:var(--primary-blue)}.req{color:var(--required-color);margin-left:3px;font-weight:700}input,select,textarea{padding:10px;border:1px solid var(--border-color);border-radius:4px;font-size:1rem;width:100%;transition:border-color .2s,box-shadow .2s}input:focus,select:focus,textarea:focus{outline:0;border-color:var(--accent-blue);box-shadow:0 0 5px rgba(52,152,219,.3)}textarea{resize:vertical;min-height:100px}.resources-section{margin-top:10px}.resources-title{font-weight:700;margin-bottom:10px;color:var(--primary-blue)}.checkbox-group{display:flex;flex-wrap:wrap;gap:15px}.checkbox-item{display:flex;align-items:center;font-weight:700;font-size:.9rem;cursor:pointer}.checkbox-item input{width:auto;margin-right:8px}@media (max-width:600px){.grid-row{grid-template-columns:1fr!important}}.button-group{display:flex;justify-content:flex-end;gap:15px;margin-top:30px}.btn{padding:12px 24px;font-size:1rem;font-weight:700;border:none;border-radius:4px;cursor:pointer;transition:background-color .2s,transform .1s}.btn-submit{background-color:var(--accent-blue);color:#fff}.btn-submit:hover{background-color:#2980b9}.btn-clear{background-color:#ecf0f1;color:var(--text-color);border:1px solid var(--border-color)}.btn-clear:hover{background-color:#bdc3c7}.btn:active{transform:translateY(1px)}@media (max-width:600px){.button-group{flex-direction:column}.btn{width:100%}}.table-container{margin-top:20px}.empty-state{background-color:#e2e3e5;color:#383d41;border-left-color:#d6d8db}.empty-state::before{color:#856404}.task-table{width:100%;border-collapse:collapse;text-align:left}.task-table td,.task-table th{padding:10px;border:1px solid var(--border-color)}.task-table th{background-color:var(--primary-blue);color:#fff}.btn-delete{background-color:var(--required-color);color:#fff}.btn-delete:hover{background-color:#c0392b}.btn-small{padding:6px 12px;font-size:.85rem}.summary-panel{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:30px;background-color:#f8fcfd;padding:20px;border-radius:8px;border:1px solid var(--accent-blue)}.summary-box{background:var(--card-bg);padding:15px;border-radius:6px;box-shadow:0 1px 3px rgba(0,0,0,.1);text-align:center}.summary-box h3{margin-top:0;color:var(--primary-blue);font-size:1.1rem;margin-bottom:15px;border-bottom:1px solid var(--border-color);padding-bottom:10px}.summary-number{font-size:3rem;font-weight:700;color:var(--accent-blue);margin:10px 0}.summary-box ul{list-style:none;padding:0;margin:0;text-align:left;font-size:.95rem}.summary-box li{margin-bottom:8px;display:flex;justify-content:space-between;border-bottom:1px dashed #eee;padding-bottom:4px}.summary-box li:last-child{border-bottom:none}@media (max-width:768px){.summary-panel{grid-template-columns:1fr}}</style>
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