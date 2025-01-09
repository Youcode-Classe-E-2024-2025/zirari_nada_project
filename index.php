<?php
// index.php

// Récupérer la route depuis l'URL
$route = isset($_GET['route']) ? $_GET['route'] : '/';

// Définir les routes disponibles et leurs actions
switch ($route) {
    case '/':
        include 'dashboardinvit.php';
        break;

    case 'about':
        include 'dashboardinvit.php';
        break;

    case 'contact':
        include 'dashboardinvit.php';
        break;

    default:
        http_response_code(404);
        include 'dashboardinvit.php';
        break;
}
