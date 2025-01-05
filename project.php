<?php
require_once 'Database.php';

class Project {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllProjects() {
        $stmt = $this->pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProject($name, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO projects (name, description) VALUES (:name, :description)");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
        ]);
    }
}
