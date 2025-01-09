-- Active: 1733826049669@@127.0.0.1@3306@project_management
CREATE DATABASE project_management;

USE project_management;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('chef', 'membre', 'invite') DEFAULT 'invite',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des projets
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    visibility ENUM('public', 'private') DEFAULT 'private',
    deadline DATE,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des tags
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des tâches
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    assigned_to INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    category_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de liaison pour les tags des tâches
CREATE TABLE task_tags (
    task_id INT,
    tag_id INT,
    PRIMARY KEY (task_id, tag_id),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);
GRANT ALL PRIVILEGES ON project_management.* TO 'nada'@'localhost' IDENTIFIED BY '123456';
FLUSH PRIVILEGES;
INSERT INTO users (name, email, password, role, created_at)
VALUES
('Alice Dupont', 'alice.dupont@example.com', 'hashed_password_1', 'chef', NOW()),
('Bob Martin', 'bob.martin@example.com', 'hashed_password_2', 'membre', NOW()),
('Charlie Durand', 'charlie.durand@example.com', 'hashed_password_3', 'invite', NOW());
INSERT INTO projects (name, description, visibility, deadline, created_by, created_at)
VALUES
('Projet A', 'Description du projet A', 'private', '2025-02-28', 1, NOW()),
('Projet B', 'Description du projet B', 'public', '2025-03-15', 1, NOW()),
('Projet C', 'Description du projet C', 'private', '2025-04-10', 2, NOW());
INSERT INTO categories (name, created_at)
VALUES
('Développement', NOW()),
('Design', NOW()),
('Marketing', NOW());
INSERT INTO tags (name, created_at)
VALUES
('Bug', NOW()),
('Feature', NOW()),
('Tache Simple', NOW());

INSERT INTO tasks (project_id, assigned_to, title, description, status, category_id, created_at)

INSERT INTO users (name, email, password, role, created_at)
VALUES
('Alice Dupont', 'nada@zirari.com', 'hashed_password_1', 'membre', NOW());

SELECT 
    p.id AS project_id,
    p.name AS project_name,
    COUNT(t.id) AS total_tasks,
    SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
FROM 
    projects p
LEFT JOIN 
    tasks t ON t.project_id = p.id
GROUP BY 
    p.id;
SELECT 
    p.id AS project_id,
    p.name AS project_name,
    COUNT(t.id) AS total_tasks,
    SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
FROM 
    projects p
LEFT JOIN 
    tasks t ON t.project_id = p.id
GROUP BY 
    p.id;
