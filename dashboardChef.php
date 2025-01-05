<?php
// Inclure le fichier de connexion à la base de données
require_once 'config.php';
require_once 'project.php';
require_once 'task.php';

$projectManager = new Project();
$taskManager = new Task();

// Ajouter un projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['description'], $_POST['visibility'], $_POST['deadline'], $_POST['created_by'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $visibility = htmlspecialchars($_POST['visibility']);
    $deadline = $_POST['deadline'];
    $created_by = (int)$_POST['created_by'];
    $projectManager->addProject($name, $description, $visibility, $deadline, $created_by);

    // Redirection après ajout
    header("Location: dashboardChef.php");
    exit;
}

// Supprimer un projet
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $projectManager->deleteProject($deleteId);

    // Redirection après suppression
    header("Location: dashboardChef.php");
    exit;
}

// Ajouter une tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_title'], $_POST['task_description'], $_POST['task_status'], $_POST['task_project_id'])) {
    $taskTitle = htmlspecialchars($_POST['task_title']);
    $taskDescription = htmlspecialchars($_POST['task_description']);
    $taskStatus = htmlspecialchars($_POST['task_status']);
    $taskProjectId = (int)$_POST['task_project_id'];
    $taskManager->addTask($taskTitle, $taskDescription, $taskStatus, $taskProjectId);

    // Redirection après ajout
    header("Location: dashboardChef.php");
    exit;
}

// Supprimer une tâche
if (isset($_GET['delete_task_id'])) {
    $deleteTaskId = (int)$_GET['delete_task_id'];
    $taskManager->deleteTask($deleteTaskId);

    // Redirection après suppression
    header("Location: dashboardChef.php");
    exit;
}

// Récupérer tous les projets
$projects = $projectManager->getAllProjects();

// Récupérer les tâches pour chaque projet
$pdo = Database::getInstance()->getConnection();
$query = "
    SELECT p.id AS project_id, p.name AS project_name, t.id AS task_id, t.title AS task_title, t.status AS task_status, t.assigned_to
    FROM projects p
    LEFT JOIN tasks t ON t.project_id = p.id
    ORDER BY p.id, t.id;
";
$stmt = $pdo->query($query);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Chef de Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Tableau de Bord - Chef de Projet</h1>

        <!-- Formulaire pour ajouter un projet -->
        <button id="openModalBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Ajouter un Projet
        </button>

        <!-- Modal pour ajouter un projet -->
        <div id="addProjectModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-md w-1/3">
                <h2 class="text-lg font-bold mb-4">Ajouter un Projet</h2>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Nom du Projet :</label>
                        <input type="text" id="name" name="name" required class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description :</label>
                        <textarea id="description" name="description" rows="4" required class="w-full border border-gray-300 rounded p-2"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="visibility" class="block text-gray-700">Visibilité :</label>
                        <select id="visibility" name="visibility" class="w-full border border-gray-300 rounded p-2">
                            <option value="private">Privé</option>
                            <option value="public">Public</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="deadline" class="block text-gray-700">Date limite :</label>
                        <input type="date" id="deadline" name="deadline" class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label for="created_by" class="block text-gray-700">Chef de Projet (ID) :</label>
                        <input type="number" id="created_by" name="created_by" required class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="closeModalBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des projets -->
        <table class="table-auto w-full mt-6 bg-white shadow-md rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nom du Projet</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Date de Création</th>
                    <th class="px-4 py-2">État des Tâches</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $project): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($project['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($project['name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($project['description']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($project['created_at']) ?></td>
                            <td class="px-4 py-2">
                                <?php
                                // Afficher l'état des tâches pour ce projet
                                $taskStatus = '';
                                foreach ($tasks as $task) {
                                    if ($task['project_id'] == $project['id']) {
                                        $statusClass = '';
                                        switch ($task['task_status']) {
                                            case 'pending':
                                                $statusClass = 'text-yellow-500';
                                                break;
                                            case 'in_progress':
                                                $statusClass = 'text-blue-500';
                                                break;
                                            case 'completed':
                                                $statusClass = 'text-green-500';
                                                break;
                                        }
                                        $taskStatus .= '<span class="block ' . $statusClass . '">' . htmlspecialchars($task['task_title']) . ' - ' . ucfirst(htmlspecialchars($task['task_status'])) . '</span>';
                                    }
                                }
                                echo $taskStatus ? $taskStatus : 'Aucune tâche assignée';
                                ?>
                            </td>
                            <td class="px-4 py-2">
                                <a href="edit.php?id=<?= $project['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Modifier</a>
                                <a href="?delete_id=<?= $project['id'] ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</a>
                                
                                <!-- Ajouter une tâche -->
                                <button data-project-id="<?= $project['id'] ?>" class="add-task-btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-2">
                                    Ajouter une Tâche
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center">Aucun projet trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter une tâche -->
    <div id="addTaskModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-md w-1/3">
            <h2 class="text-lg font-bold mb-4">Ajouter une Tâche</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="task_title" class="block text-gray-700">Titre de la Tâche :</label>
                    <input type="text" id="task_title" name="task_title" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div class="mb-4">
                    <label for="task_description" class="block text-gray-700">Description :</label>
                    <textarea id="task_description" name="task_description" rows="4" required class="w-full border border-gray-300 rounded p-2"></textarea>
                </div>
                <div class="mb-4">
                    <label for="task_status" class="block text-gray-700">État de la Tâche :</label>
                    <select id="task_status" name="task_status" class="w-full border border-gray-300 rounded p-2">
                        <option value="pending">En Attente</option>
                        <option value="in_progress">En Cours</option>
                        <option value="completed">Terminée</option>
                    </select>
                </div>
                <input type="hidden" id="task_project_id" name="task_project_id">
                <div class="flex justify-end">
                    <button type="button" id="closeTaskModalBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                        Annuler
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Gestion du modal pour projet
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modal = document.getElementById('addProjectModal');

        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Gestion de l'ajout de tâches
        const addTaskBtns = document.querySelectorAll('.add-task-btn');
        const addTaskModal = document.getElementById('addTaskModal');
        const closeTaskModalBtn = document.getElementById('closeTaskModalBtn');
        const taskProjectIdInput = document.getElementById('task_project_id');

        addTaskBtns.forEach(button => {
            button.addEventListener('click', () => {
                const projectId = button.getAttribute('data-project-id');
                taskProjectIdInput.value = projectId;
                addTaskModal.classList.remove('hidden');
            });
        });

        closeTaskModalBtn.addEventListener('click', () => {
            addTaskModal.classList.add('hidden');
        });

        window.addEventListener('click', (event) => {
            if (event.target === addTaskModal) {
                addTaskModal.classList.add('hidden');
            }
        });
    </script>
</body>
</html>