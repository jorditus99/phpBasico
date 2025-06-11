<?php
session_start();
require_once '../php_librarys/bd.php';

// Verificar si hay sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$project_id = $_POST['project_id'] ?? null;

// Verificar si se proporcionó un ID de proyecto
if (!$project_id) {
    header('Location: ../proyectos.php');
    exit;
}


// Procesar la creación de tarea
if (isset($_POST['create_task'])) {
    $title = $_POST['task_title'];
    $description = $_POST['task_description'] ?? '';
    
    InsertTask($project_id, $title, $description, $_SESSION['user_id']);
    header('Location: ../tareas.php?id=' . $project_id);
    exit;
}

// Procesar la adición de usuario al proyecto
if (isset($_POST['add_user']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    add_user_to_project($project_id, $user_id);
    header('Location: ../tareas.php?id=' . $project_id);
    exit;
}

// Si no se procesó nada, redirigir a la página de tareas
header('Location: ../tareas.php?id=' . $project_id);
exit; 