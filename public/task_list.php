<?php
session_start();
require_once '../src/config/config.php';
require_once '../src/models/Task.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$projectId = $_GET['project_id'];
$taskModel = new Task();
$tasks = $taskModel->getTasksForProject($projectId); // Récupère les tâches du projet
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tâches du projet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Tâches du projet</h1>
        <a href="create_task.php?project_id=<?php echo $projectId; ?>">Créer une tâche</a>
        <a href="dashboard.php">Retour au tableau de bord</a>
    </header>

    <main>
        <h2>Tâches pour le projet</h2>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <strong><?php echo htmlspecialchars($task['title']); ?></strong><br>
                    <span><?php echo htmlspecialchars($task['description']); ?></span><br>
                    <span>Statut: <?php echo htmlspecialchars($task['status']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
