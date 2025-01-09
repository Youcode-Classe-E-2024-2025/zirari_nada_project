<?php
require_once 'config.php';

class Task
{
    private $pdo;

    public function __construct()
    {
        // Connexion à la base de données via la classe Database
        $this->pdo = Database::getInstance()->getConnection();
    }

    
    // Ajouter une tâche
    public function addTask($title, $description, $status, $projectId, $assignedTo, $categoryId) {
        $query = "
            INSERT INTO tasks (title, description, status, project_id, assigned_to, category_id)
            VALUES (:title, :description, :status, :project_id, :assigned_to, :category_id)
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
        $stmt->bindParam(':assigned_to', $assignedTo, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT); // Nouvelle colonne
        $stmt->execute();
    }
    


    // Récupérer toutes les tâches d'un projet
    public function getTasksByProject($projectId)
    {
        $sql = "SELECT * FROM tasks WHERE project_id = :project_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les catégories
    public function getCategories()
    {
        $sql = "SELECT * FROM categories";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTasksByUserId($userId) {
        // Se connecter à la base de données
        $pdo = Database::getInstance()->getConnection();

        // Requête pour récupérer les tâches assignées à un utilisateur
        $query = "
            SELECT t.id, t.title, t.status, t.project_id
            FROM tasks t
            WHERE t.assigned_to = :userId
            ORDER BY t.status, t.id;
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les tâches
    public function getAllTasks()
    {
        $sql = "SELECT * FROM tasks";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
