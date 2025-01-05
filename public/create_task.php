<?php
session_start();
require_once '../src/config/config.php';
require_once '../src/models/Task.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $taskModel = new Task();
    $taskModel->createTask($projectId, $title, $description, $status);

    header('Location: dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une tâche</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Créer une tâche</h1>
    </header>

    <main>
        <form method="POST">
            <input type="hidden" name="project_id" value="<?php echo $_GET['project_id']; ?>">
            <label for="title">Titre de la tâche:</label>
            <input type="text" name="title" required>
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            <label for="status">Statut:</label>
            <select name="status">
                <option value="en_cours">En cours</option>
                <option value="terminée">Terminée</option>
                <option value="en_attente">En attente</option>
            </select>
            <button type="submit">Créer</button>
        </form>
    </main>
</body>
</html>
