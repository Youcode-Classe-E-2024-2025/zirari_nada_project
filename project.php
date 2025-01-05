<?php
require_once 'config.php';

class Project
{
    private $pdo;

    public function __construct()
    {
        // Connexion à la base de données (assurez-vous de bien configurer la connexion PDO)
        $this->pdo = new PDO('mysql:host=localhost;dbname=your_db', 'username', 'password');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Ajouter un projet
    public function addProject($name, $description)
    {
        $sql = "INSERT INTO projects (name, description, created_at) VALUES (:name, :description, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'description' => $description]);
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

    // Supprimer un projet
    public function deleteProject($id)
    {
        $sql = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
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
}
