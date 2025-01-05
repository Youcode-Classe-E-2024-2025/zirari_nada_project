<?php
require_once 'project.php';

$projectManager = new Project();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['description'], $_POST['project_id'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $projectId = (int)$_POST['project_id'];
    $visibility = $_POST['visibility'] ?? 'private';
    $deadline = $_POST['deadline'] ?? null;
    $createdBy = $_POST['created_by'] ?? null;

    $projectManager->updateProject($projectId, $name, $description, $visibility, $deadline, $createdBy);

    // Redirection après modification
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $projectId = (int)$_GET['id'];
    $project = $projectManager->getProjectById($projectId);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Modifier le Projet</h1>

        <form method="POST" action="">
            <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id']) ?>">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nom du Projet :</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($project['name']) ?>" required class="w-full border border-gray-300 rounded p-2">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description :</label>
                <textarea id="description" name="description" rows="4" required class="w-full border border-gray-300 rounded p-2"><?= htmlspecialchars($project['description']) ?></textarea>
            </div>
            <div class="mb-4">
                <label for="visibility" class="block text-gray-700">Visibilité :</label>
                <select id="visibility" name="visibility" class="w-full border border-gray-300 rounded p-2">
                    <option value="private" <?= $project['visibility'] == 'private' ? 'selected' : '' ?>>Privé</option>
                    <option value="public" <?= $project['visibility'] == 'public' ? 'selected' : '' ?>>Public</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="deadline" class="block text-gray-700">Date limite :</label>
                <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($project['deadline']) ?>" class="w-full border border-gray-300 rounded p-2">
            </div>
            <div class="mb-4">
                <label for="created_by" class="block text-gray-700">Chef de Projet (ID) :</label>
                <input type="number" id="created_by" name="created_by" value="<?= htmlspecialchars($project['created_by']) ?>" required class="w-full border border-gray-300 rounded p-2">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="window.location.href='index.php'" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">Annuler</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Sauvegarder</button>
            </div>
        </form>
    </div>
</body>
</html>
