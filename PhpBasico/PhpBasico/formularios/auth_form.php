<?php
session_start();
require_once '../PhpBasico/php_librarys/bd.php';

// Procesar login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user_id = check_user($username, $password);
    
    if ($user_id > 0) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header('Location: ../proyectos.php');
        exit;
    } else {
        header('Location: ../index.php?error=1');
        exit;
    }
}

// Procesar registro
if (isset($_POST['register'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];
    
    try {
        if (empty($username) || empty($password)) {
            throw new Exception("campos_vacios");
        }
        
        if (strlen($username) < 3) {
            throw new Exception("usuario_corto");
        }
        
        if (strlen($password) < 4) {
            throw new Exception("password_corta");
        }
        
        $user_id = create_user($username, $password);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header('Location: ../proyectos.php');
        exit;
    } catch (Exception $e) {
        header('Location: ../index.php?register_error=' . $e->getMessage());
        exit;
    }
}

// Si no se procesó nada, volver al inicio
header('Location: ../index.php');
exit; 