<?php
$projectId = (int)$_GET['project_id'];
$tasks = $taskManager->getTasksByProjectId($projectId);

foreach ($tasks as $task) {
    echo '<p>' . htmlspecialchars($task['title']) . ' - ' . htmlspecialchars($task['status']) . '</p>';
}
?>
