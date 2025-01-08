<?php
require_once 'config.php'; // Inclure la classe Database

try {
    // Connexion à la base de données
    $pdo = Database::getInstance()->getConnection();

    // Données du chef de projet
    $username = "ChefProjet2"; // Remplace par le nom du chef de projet
    $email = "chef@gmail.com"; // Remplace par l'email du chef de projet
    $password = "123456"; // Remplace par le mot de passe souhaité
    $role = "chef"; // Rôle spécifique pour le chef de projet

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        die("Un utilisateur avec cet email existe déjà.");
    }

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insérer dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->execute();

    echo "Chef de projet ajouté avec succès.";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
