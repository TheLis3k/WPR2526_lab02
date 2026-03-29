<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newTask = [
        'title'       => $_POST['title'] ?? '',
        'category'    => $_POST['category'] ?? '',
        'description' => $_POST['description'] ?? '',
        'priority'    => $_POST['priority'] ?? '',
        'status'      => $_POST['status'] ?? '',
        'due_date'    => $_POST['due-date'] ?? '',
        'time'        => $_POST['time'] ?? '',
        'location'    => $_POST['location'] ?? '',
        'assignee'    => $_POST['assignee'] ?? '',
        'resources'   => isset($_POST['resources']) ? implode(", ", $_POST['resources']) : 'Brak'
    ];

    $_SESSION['tasks'][] = $newTask;

    header("Location: index.php");
    exit;
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

        <section class="alert">
            <ul>
                <li>Kategoria jest wymagana</li>
            </ul>
        </section>

        <form action="index.php" method="POST">
            <div class="grid-row grid-2-1">
                <div class="field">
                    <label for="title">Tytuł zadania:<span class="req">*</span></label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="field">
                    <label for="category">Kategoria:<span class="req">*</span></label>
                    <select id="category" name="category" required>
                        <option value="" selected>Wybierz kategorię</option>
                        <option value="home">Domowe</option>
                        <option value="work">Praca</option>
                        <option value="study">Nauka</option>
                        <option value="hobby">Hobby</option>
                        <option value="other">Inne</option>
                    </select>
                </div>
            </div>

            <div class="grid-row full-width">
                <div class="field">
                    <label for="description">Opis zadania:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="priority">Priorytet:<span class="req">*</span></label>
                    <select id="priority" name="priority" required>
                        <option value="">Wybierz priorytet</option>
                        <option value="1">Niski</option>
                        <option value="2">Średni</option>
                        <option value="3">Wysoki</option>
                    </select>
                </div>
                <div class="field">
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">Wybierz status</option>
                        <option value="new">Nowe</option>
                        <option value="pending">W trakcie</option>
                        <option value="done">Zakończone</option>
                    </select>
                </div>
                <div class="field">
                    <label for="due-date">Data wykonania:<span class="req">*</span></label>
                    <input type="text" id="due-date" name="due-date" placeholder="14.04.2025" required>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="time">Szacowany czas (minuty):</label>
                    <input type="number" id="time" name="time" placeholder="">
                </div>
                <div class="field">
                    <label for="location">Lokalizacja:</label>
                    <input type="text" id="location" name="location">
                </div>
                <div class="field">
                    <label for="assignee">Osoba przypisana:</label>
                    <input type="text" id="assignee" name="assignee">
                </div>
            </div>

            <div class="resources-section">
                <div class="resources-title">Potrzebne zasoby:</div>
                <div class="checkbox-group">
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Komputer"> Komputer</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Internet"> Internet</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Telefon"> Telefon</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Samochód"> Samochód</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Książka"> Książka</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Narzędzia"> Narzędzia</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Dokumenty"> Dokumenty</label>
                    <label class="checkbox-item"><input type="checkbox" name="resources[]" value="Inne"> Inne</label>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-submit">Dodaj</button>
                <button type="reset" class="btn btn-clear">Wyczyść</button>
            </div>
        </form>
    </div>

    <div class="container table-container">
        <h2>Lista zapisanych zadań</h2>
        
        <?php if (empty($_SESSION['tasks'])): ?>
            <section class="alert empty-state">
                <p>Brak dodanych zadań. Wypełnij formularz powyżej, aby dodać pierwsze zadanie.</p>
            </section>
        <?php else: ?>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['tasks'] as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= htmlspecialchars($task['category']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td><?= htmlspecialchars($task['priority']) ?></td>
                            <td><?= htmlspecialchars($task['status']) ?></td>
                            <td><?= htmlspecialchars($task['due_date']) ?></td>
                            <td><?= htmlspecialchars($task['time']) ?></td>
                            <td><?= htmlspecialchars($task['location']) ?></td>
                            <td><?= htmlspecialchars($task['assignee']) ?></td>
                            <td><?= htmlspecialchars($task['resources']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
