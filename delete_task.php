<?php
require_once 'config.php';
require_once 'task.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['task_id'])) {
        $taskId = (int)$input['task_id'];

        $taskManager = new Task();
        if ($taskManager->deleteTask($taskId)) {
            echo json_encode(['success' => true, 'message' => 'Tâche supprimée avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de la suppression de la tâche.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de la tâche manquant.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
