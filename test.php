<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=project_management;charset=utf8',
        'nada',
        '123456'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
