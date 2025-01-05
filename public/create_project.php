<?php
session_start();
require_once '../src/config/config.php';
require_once '../src/models/Project.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $visibility = $_POST['visibility'];

    $projectModel = new Project();
    $projectModel->createProject($name, $description, $visibility, $_SESSION['user_id']);

    header('Location: dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un projet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Créer un projet</h1>
    </header>

    <main>
        <form method="POST">
            <label for="name">Nom du projet:</label>
            <input type="text" name="name" required>
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            <label for="visibility">Visibilité:</label>
            <select name="visibility">
                <option value="public">Public</option>
                <option value="private">Privé</option>
            </select>
            <button type="submit">Créer</button>
        </form>
    </main>
</body>
</html>
