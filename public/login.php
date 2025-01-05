<?php
require_once '../src/config/config.php';
require_once '../src/models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userModel = new User();
    $user = $userModel->login($email, $password);

    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
    } else {
        $error = "Identifiants invalides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>

    <main>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" required>
            <button type="submit">Se connecter</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </main>
</body>
</html>
