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
    public function addTask($name, $description, $assignedTo, $projectId, $categoryId, $tags)
    {
        $sql = "INSERT INTO tasks (name, description, assigned_to, project_id, category_id, tags, status) 
                VALUES (:name, :description, :assigned_to, :project_id, :category_id, :tags, 'pending')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'assigned_to' => $assignedTo,
            'project_id' => $projectId,
            'category_id' => $categoryId,
            'tags' => $tags
        ]);
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

    // Récupérer toutes les tâches
    public function getAllTasks()
    {
        $sql = "SELECT * FROM tasks";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
