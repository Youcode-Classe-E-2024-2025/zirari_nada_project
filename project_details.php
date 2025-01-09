<?php
require_once 'config.php';
require_once 'dashboardchef.php';
// Assurez-vous que l'ID du projet est passé dans l'URL
if (isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
    $projectId = (int)$_GET['project_id'];

    // Récupérer les tâches associées à ce projet
    $tasks = $taskManager->getTasksByProjectId($projectId);

    if (!empty($tasks)) {
        foreach ($tasks as $task) {
            echo '<p>' . htmlspecialchars($task['title']) . ' - ' . htmlspecialchars($task['status']) . '</p>';
        }
    } else {
        echo '<p>Aucune tâche trouvée pour ce projet.</p>';
    }
} else {
    echo '<p>Le projet spécifié est invalide ou manquant.</p>';
}
?>
