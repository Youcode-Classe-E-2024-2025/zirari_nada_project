<?php
require_once 'config.php';
class Project
{
    private $pdo;

    public function __construct()
    {
        // Utilisation de la classe Database pour obtenir la connexion PDO
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Ajouter un projet
    public function addProject($name, $description, $visibility, $deadline, $createdBy)
{
    $sql = "INSERT INTO projects (name, description, visibility, deadline, created_by, created_at) 
            VALUES (:name, :description, :visibility, :deadline, :created_by, NOW())";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'visibility' => $visibility,
        'deadline' => $deadline,
        'created_by' => $createdBy
    ]);
}

    // Modifier un projet
    public function updateProject($id, $name, $description, $visibility, $deadline, $createdBy)
    {
        $sql = "UPDATE projects SET name = :name, description = :description, visibility = :visibility, deadline = :deadline, created_by = :created_by WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'visibility' => $visibility,
            'deadline' => $deadline,
            'created_by' => $createdBy,
            'id' => $id
        ]);
    }
    public function getProjectsByVisibility($visibility) {
        // Se connecter à la base de données
        $pdo = Database::getInstance()->getConnection();

        // Requête pour récupérer les projets en fonction de leur visibilité
        $query = "SELECT * FROM projects WHERE visibility = :visibility";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':visibility', $visibility, PDO::PARAM_STR);
        $stmt->execute();

        // Retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un projet
    public function deleteProject($id)
    {
        $sql = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    public function getAssignedProjects($userId) {
        // Se connecter à la base de données
        $pdo = Database::getInstance()->getConnection();
    
        // Requête pour récupérer les projets assignés à l'utilisateur
        $query = "
            SELECT p.id, p.name, p.description
            FROM projects p
            JOIN tasks t ON p.id = t.project_id
            WHERE t.assigned_to = :userId
            GROUP BY p.id;
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    
        // Retourner les résultats
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les projets
    public function getAllProjects()
    {
        $sql = "SELECT * FROM projects";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un projet par son ID
    public function getProjectById($id)
    {
        $sql = "SELECT * FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getProjectProgress($projectId)
{
    $pdo = Database::getInstance()->getConnection();
    $query = "
        SELECT 
            COUNT(t.id) AS total_tasks,
            SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
        FROM 
            tasks t
        WHERE 
            t.project_id = :project_id;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['project_id' => $projectId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
