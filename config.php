<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'project_management');
define('DB_USER', 'nada');
define('DB_PASS', '123456'); // Mettez votre mot de passe ici si nécessaire

class ConnectionDB {
    private $connection;

    public function getConnection() {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                    DB_USER, DB_PASS
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}
?>
