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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
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
<div id="todo" class="bg-yellow-100 p-4 rounded shadow hover:shadow-lg transition duration-300">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">À Faire</h3>
    <ul id="todo-list">
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'pending'): ?>
                <li class="border-b py-2 text-gray-700" data-task-id="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Section En Cours -->
<div id="in-progress" class="bg-blue-100 p-4 rounded shadow hover:shadow-lg transition duration-300">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">En Cours</h3>
    <ul id="in-progress-list">
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'in_progress'): ?>
                <li class="border-b py-2 text-gray-700" data-task-id="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Section Terminées -->
<div id="done" class="bg-green-100 p-4 rounded shadow hover:shadow-lg transition duration-300">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Terminées</h3>
    <ul id="done-list">
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'done'): ?>
                <li class="border-b py-2 text-gray-700" data-task-id="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

        </div>
    </div>
    <script>
    // Initialisation de SortableJS pour chaque section
    const todoList = document.getElementById('todo-list');
    const inProgressList = document.getElementById('in-progress-list');
    const doneList = document.getElementById('done-list');

    // Créer des instances de Sortable pour chaque liste
    new Sortable(todoList, {
        group: 'tasks', // Permet le déplacement entre les sections
        animation: 150, // Animation lors du déplacement
        onEnd: function (evt) {
            updateTaskStatus(evt.item, 'pending'); // Mise à jour de l'état de la tâche
        }
    });

    new Sortable(inProgressList, {
        group: 'tasks',
        animation: 150,
        onEnd: function (evt) {
            updateTaskStatus(evt.item, 'in_progress');
        }
    });

    new Sortable(doneList, {
        group: 'tasks',
        animation: 150,
        onEnd: function (evt) {
            updateTaskStatus(evt.item, 'done');
        }
    });

    // Fonction pour mettre à jour l'état de la tâche dans la base de données
    function updateTaskStatus(item, newStatus) {
        const taskId = item.getAttribute('data-task-id');

        // Envoyer la mise à jour au serveur via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_task_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log('Task status updated');
            }
        };
        xhr.send('task_id=' + taskId + '&status=' + newStatus);
    }
</script>

    

</body>
</html>
