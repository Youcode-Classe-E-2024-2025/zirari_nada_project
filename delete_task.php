<?php
require_once 'config.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'] ?? null;

    if ($taskId) {
        try {
            $query = "DELETE FROM tasks WHERE id = :task_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Tâche supprimée avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de la tâche.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de tâche manquant.']);
    }
}
