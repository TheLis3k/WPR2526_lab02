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

        <form action="#">
            <div class="grid-row grid-2-1">
                <div class="field">
                    <label for="title">Tytuł zadania:<span class="req">*</span></label>
                    <input type="text" id="title" required>
                </div>
                <div class="field">
                    <label for="category">Kategoria:<span class="req">*</span></label>
                    <select id="category" required>
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
                    <textarea id="description"></textarea>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="priority">Priorytet:<span class="req">*</span></label>
                    <select id="priority" required>
                        <option value="">Wybierz priorytet</option>
                        <option value="1">Niski</option>
                        <option value="2">Średni</option>
                        <option value="3">Wysoki</option>
                    </select>
                </div>
                <div class="field">
                    <label for="status">Status:</label>
                    <select id="status">
                        <option value="">Wybierz status</option>
                        <option value="new">Nowe</option>
                        <option value="pending">W trakcie</option>
                        <option value="done">Zakończone</option>
                    </select>
                </div>
                <div class="field">
                    <label for="due-date">Data wykonania:<span class="req">*</span></label>
                    <input type="text" id="due-date" placeholder="14.04.2025" required>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="time">Szacowany czas (minuty):</label>
                    <input type="number" id="time" placeholder="">
                </div>
                <div class="field">
                    <label for="location">Lokalizacja:</label>
                    <input type="text" id="location">
                </div>
                <div class="field">
                    <label for="assignee">Osoba przypisana:</label>
                    <input type="text" id="assignee">
                </div>
            </div>

            <div class="resources-section">
                <div class="resources-title">Potrzebne zasoby:</div>
                <div class="checkbox-group">
                    <label class="checkbox-item"><input type="checkbox"> Komputer</label>
                    <label class="checkbox-item"><input type="checkbox"> Internet</label>
                    <label class="checkbox-item"><input type="checkbox"> Telefon</label>
                    <label class="checkbox-item"><input type="checkbox"> Samochód</label>
                    <label class="checkbox-item"><input type="checkbox"> Książka</label>
                    <label class="checkbox-item"><input type="checkbox"> Narzędzia</label>
                    <label class="checkbox-item"><input type="checkbox"> Dokumenty</label>
                    <label class="checkbox-item"><input type="checkbox"> Inne</label>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
