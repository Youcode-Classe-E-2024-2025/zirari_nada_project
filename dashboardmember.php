<?php
// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'project.php';
require_once 'task.php';

// Créer des instances pour récupérer les projets et les tâches
$projectManager = new Project();
$taskManager = new Task();

// L'ID de l'utilisateur connecté (à récupérer via la session ou autre méthode)
$userId = 1; // Exemple, vous devrez récupérer l'ID de l'utilisateur connecté

// Récupérer les projets assignés à l'utilisateur
$projects = $projectManager->getAssignedProjects($userId); // Vous devez créer une méthode pour récupérer les projets assignés

// Récupérer les tâches assignées à l'utilisateur
$tasks = $taskManager->getTasksByUserId($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Membre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Dashboard Membre</h1>
            <div>
                <a href="logout.php" class="text-white hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Projets Assignés</h2>

        <!-- Liste des projets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($project['name']) ?></h3>
                            <p class="text-gray-600 mt-2"><?= htmlspecialchars($project['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500 col-span-full">Aucun projet assigné.</p>
            <?php endif; ?>
        </div>

        <!-- Sections des tâches -->
        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">Mes Tâches</h2>

        <div class="flex space-x-6">
            <!-- To Do -->
            <div class="w-1/3 bg-white p-4 rounded shadow">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">À Faire</h3>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] == 'pending'): ?>
                            <li class="border-b py-2"><?= htmlspecialchars($task['title']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- In Progress -->
            <div class="w-1/3 bg-white p-4 rounded shadow">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">En Cours</h3>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] == 'in_progress'): ?>
                            <li class="border-b py-2"><?= htmlspecialchars($task['title']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Done -->
            <div class="w-1/3 bg-white p-4 rounded shadow">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Terminé</h3>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] == 'completed'): ?>
                            <li class="border-b py-2"><?= htmlspecialchars($task['title']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
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
