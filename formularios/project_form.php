<?php
session_start();
require_once '../php_librarys/bd.php';

// Verificar si hay sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Procesar la creación de proyecto
if (isset($_POST['project_name']) && !empty($_POST['project_name'])) {
    $project_name = $_POST['project_name'];
    InsertProject($project_name, $_SESSION['user_id']);
}

// Redirigir de vuelta a la lista de proyectos
header('Location: ../proyectos.php');
exit; 