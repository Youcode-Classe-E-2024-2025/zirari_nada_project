<?php
require_once 'project.php';

$projectManager = new Project();

// Modifier un projet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['description'], $_POST['project_id'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $projectId = (int)$_POST['project_id'];
    $visibility = $_POST['visibility'] ?? 'private';
    $deadline = $_POST['deadline'] ?? null;
    $createdBy = $_POST['created_by'] ?? null;

    $projectManager->updateProject($projectId, $name, $description, $visibility, $deadline, $createdBy);

    // Redirection pour éviter un nouvel envoi du formulaire
    header("Location: dashboardChef.php");
    exit;
}

// Supprimer un projet
if (isset($_GET['delete_id'])) {
    $projectIdToDelete = (int)$_GET['delete_id'];
    $projectManager->deleteProject($projectIdToDelete);

    // Redirection après suppression
    header("Location: dahboardChef.php");
    exit;
}

// Récupérer tous les projets
$projects = $projectManager->getAllProjects();
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

        <!-- Bouton pour ouvrir le modal -->
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
                                <a href="edit.php?id=<?= $project['id'] ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Modifier</a>
                                <a href="?delete_id=<?= $project['id'] ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center">Aucun projet trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Gestion du modal
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
    </script>
</body>
</html>
