<?php
require_once 'config.php'; // Connexion à la base de données

// Récupérer l'instance de la base de données
$pdo = Database::getInstance()->getConnection();

// Vérifier si l'ID de la tâche est passé dans l'URL
$taskId = $_GET['task_id'] ?? null;

if ($taskId) {
    try {
        // Préparer la requête pour supprimer la tâche
        $query = "DELETE FROM tasks WHERE id = :task_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            header('Location: dashboardChef.php?message=Tâche supprimée avec succès');
            exit;
        } else {
            header('Location: dashboardChef.php?message=Erreur lors de la suppression de la tâche');
            exit;
        }
    } catch (Exception $e) {
        header('Location: dashboardChef.php?message=Erreur : ' . $e->getMessage());
        exit;
    }
} else {
    header('Location: dashboardChef.php?message=ID de tâche manquant');
    exit;
}

