<?php
session_start();
require_once '../src/config/config.php';
require_once '../src/models/Project.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$projectModel = new Project();
$projects = $projectModel->getUserProjects($_SESSION['user_id']); // Récupère les projets de l'utilisateur connecté
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Tableau de bord</h1>
        <a href="create_project.php">Créer un projet</a>
        <a href="logout.php">Se déconnecter</a>
    </header>

    <main>
        <h2>Projets Assignés</h2>
        <ul>
            <?php foreach ($projects as $project): ?>
                <li><?php echo htmlspecialchars($project['name']); ?></li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
