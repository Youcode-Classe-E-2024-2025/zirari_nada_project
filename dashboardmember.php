<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';
require_once 'project.php';
require_once 'task.php';

$userId = $_SESSION['user_id'];
$projectManager = new Project();
$taskManager = new Task();
$projects = $projectManager->getAssignedProjects($userId);
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
<body class="bg-gray-50 font-sans leading-normal tracking-normal">
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Dashboard Membre</h1>
            <div>
                <a href="logout.php" class="text-white hover:text-gray-200 transition duration-200">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Projets Assignés</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition duration-300">
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
        <h2 class="text-3xl font-semibold text-gray-800 mt-8 mb-4">Mes Tâches</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Section À Faire -->
            <div class="bg-yellow-100 p-4 rounded shadow hover:shadow-lg transition duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">À Faire</h3>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] == 'pending'): ?>
                            <li class="border-b py-2 text-gray-700"><?= htmlspecialchars($task['title']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Section En Cours -->
            <div class="bg-blue-100 p-4 rounded shadow hover:shadow-lg transition duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">En Cours</h3>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] == 'in_progress'): ?>
                            <li class="border-b py-2 text-gray-700"><?= htmlspecialchars($task['title']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Section Terminées -->
            <div class="bg-green-100 p-4 rounded shadow hover:shadow-lg transition duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Terminées</h3>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] == 'done'): ?>
                            <li class="border-b py-2 text-gray-700"><?= htmlspecialchars($task['title']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
