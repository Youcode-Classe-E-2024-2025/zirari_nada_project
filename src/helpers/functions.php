<?php

// Fonction pour échapper les données afin d'éviter les attaques XSS
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Fonction pour valider un email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fonction pour vérifier si un mot de passe est assez fort
function validatePassword($password) {
    return strlen($password) >= 8;
}
