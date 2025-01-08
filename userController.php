<?php
require_once 'config.php'; // Inclure la classe Database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer la connexion PDO
    $pdo = Database::getInstance()->getConnection();

    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Vérification des champs
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("Tous les champs sont obligatoires.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email invalide.");
    }

    if ($password !== $confirmPassword) {
        die("Les mots de passe ne correspondent pas.");
    }

    if (strlen($password) < 6) {
        die("Le mot de passe doit contenir au moins 6 caractères.");
    }

    try {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            die("Un compte avec cet email existe déjà.");
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérer l'utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:username, :email, :password, 'membre')");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();

        // Rediriger vers la page de connexion après l'inscription
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
} else {
    die("Méthode non autorisée.");
}
?>
