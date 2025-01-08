<?php
require_once 'config.php'; // Inclure le fichier de configuration
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        try {
            // Obtenir la connexion PDO
            $pdo = Database::getInstance()->getConnection();

            // Préparer la requête
            $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirection basée sur le rôle
                if ($user['role'] === 'membre') {
//                     var_dump($user['role']);
// exit;

                    header('Location: dashboardmember.php');
                } elseif ($user['role'] === 'chef') {
                    header('Location: dashboardChef.php');
                } else {
                    header('Location: dashboardinvit.php');
                }
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur de connexion : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
