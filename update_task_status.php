<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['task_id'];
    $newStatus = $_POST['status'];

    // Assurez-vous que l'utilisateur est connecté
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo 'Unauthorized';
        exit;
    }

    // Mise à jour de l'état de la tâche dans la base de données
    $pdo = Database::getInstance()->getConnection();
    $query = "UPDATE tasks SET status = :status WHERE id = :task_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->execute();

    echo 'Task status updated';
}
?>
