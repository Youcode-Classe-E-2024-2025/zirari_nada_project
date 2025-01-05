<?php
require_once '../src/config/config.php';

class Task {
    private $db;

    public function __construct() {
        $this->db = (new ConnectionDB())->getConnection();
    }

    public function getTasksForProject($projectId) {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE project_id = :project_id');
        $stmt->execute(['project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($projectId, $title, $description, $status) {
        $stmt = $this->db->prepare('INSERT INTO tasks (project_id, title, description, status) VALUES (:project_id, :title, :description, :status)');
        $stmt->execute([
            'project_id' => $projectId,
            'title' => $title,
            'description' => $description,
            'status' => $status
        ]);
    }

    public function updateTaskStatus($taskId, $status) {
        $stmt = $this->db->prepare('UPDATE tasks SET status = :status WHERE id = :task_id');
        $stmt->execute([
            'status' => $status,
            'task_id' => $taskId
        ]);
    }
}
?>
