<?php
require_once '../src/config/config.php';

class Project {
    private $db;

    public function __construct() {
        $this->db = (new ConnectionDB())->getConnection();
    }

    public function getPublicProjects() {
        $stmt = $this->db->prepare('SELECT * FROM projects WHERE visibility = "public"');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserProjects($userId) {
        $stmt = $this->db->prepare('SELECT * FROM projects WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProject($name, $description, $visibility, $userId) {
        $stmt = $this->db->prepare('INSERT INTO projects (name, description, visibility, user_id) VALUES (:name, :description, :visibility, :user_id)');
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'visibility' => $visibility,
            'user_id' => $userId
        ]);
    }
}
?>
