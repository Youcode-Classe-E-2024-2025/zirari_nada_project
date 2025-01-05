<?php
// Inclure le fichier de connexion à la base de données
require_once 'config.php';
require_once 'project.php';

// Créer une instance de Project pour récupérer les projets
$projectManager = new Project();

// Récupérer tous les projets publics
$projects = $projectManager->getProjectsByVisibility('public');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Invité</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Dashboard Invité</h1>
            <div>
                <a href="login.php" class="text-white hover:text-gray-200">Login</a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Projets Disponibles</h2>

        <!-- Liste des projets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($project['name']) ?></h3>
                            <p class="text-gray-600 mt-2"><?= htmlspecialchars($project['description']) ?></p>
                            <div class="mt-4">
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm"><?= ucfirst(htmlspecialchars($project['visibility'])) ?></span>
                            </div>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500 col-span-full">Aucun projet disponible.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
