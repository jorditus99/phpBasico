<?php

function openBd(){
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conexion = new PDO("mysql:host=$servername;dbname=trello_basico", $username, $password);
    // set the PDO error mode to exception
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");
 
    return $conexion;
} 

function closeBd()
{
    return null;
}

function secure_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function hash_pass($pass) {
    return password_hash($pass, PASSWORD_DEFAULT);
}

function create_user($username, $password) {
    $username = secure_data($username);
    $password = secure_data($password);
    $password = hash_pass($password);

    $conexion = openBd();

    $sentenciaText = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':username', $username);
    $sentencia->bindParam(':password', $password);
    $sentencia->execute();

    $id = $conexion->lastInsertId();
    closeBd();
    return $id;
}

function check_user($username, $password) {
    $conexion = openBd();
    $username = secure_data($username);
    
    $sentenciaText = "SELECT * FROM users WHERE username = :username";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':username', $username);
    $sentencia->execute();

    $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
    
    closeBd();
    
    if ($resultado && password_verify($password, $resultado['password'])) {
        return $resultado["id"];
    }
    return -1;
}

function select_users() {
    $conexion = openBd();

    $sentenciaText = "SELECT id, username FROM users";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

    closeBd();
    return $resultado;
}

function Selectprojects($user_id = null){
    $conexion = openBd();

    if ($user_id) {
        $sentenciaText = "SELECT DISTINCT p.* FROM projects p 
                         LEFT JOIN project_users pu ON p.id = pu.project_id 
                         WHERE p.owner_id = :user_id OR pu.user_id = :user_id";
        $sentencia = $conexion->prepare($sentenciaText);
        $sentencia->bindParam(':user_id', $user_id);
    } else {
        $sentenciaText = "SELECT * FROM projects";
        $sentencia = $conexion->prepare($sentenciaText);
    }
    
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

    closeBd();
    return $resultado;
}

function Selecttasks($project_id){
    $conexion = openBd();

    $sentenciaText = "SELECT t.*, u.username as creator_username 
                     FROM tasks t 
                     INNER JOIN users u ON t.created_by = u.id 
                     WHERE t.project_id = :project_id";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

    closeBd();
    return $resultado;
}

function InsertProject($name, $owner_id) {
    $conexion = openBd();

    $sentenciaText = "INSERT INTO projects (name, owner_id) VALUES (:name, :owner_id)";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':name', $name);
    $sentencia->bindParam(':owner_id', $owner_id);
    $sentencia->execute();

    closeBd();
    return $conexion->lastInsertId();
}

function get_project_users($project_id) {
    $conexion = openBd();

    $sentenciaText = "SELECT u.id, u.username FROM users u 
                     INNER JOIN project_users pu ON u.id = pu.user_id 
                     WHERE pu.project_id = :project_id";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

    closeBd();
    return $resultado;
}

function get_available_users($project_id) {
    $conexion = openBd();

    $sentenciaText = "SELECT u.id, u.username FROM users u 
                     WHERE u.id NOT IN (
                         SELECT user_id FROM project_users WHERE project_id = :project_id
                     ) AND u.id NOT IN (
                         SELECT owner_id FROM projects WHERE id = :project_id
                     )";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

    closeBd();
    return $resultado;
}

function add_user_to_project($project_id, $user_id) {
    $conexion = openBd();

    $sentenciaText = "INSERT INTO project_users (project_id, user_id) VALUES (:project_id, :user_id)";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->bindParam(':user_id', $user_id);
    $sentencia->execute();

    closeBd();
}

function can_access_project($project_id, $user_id) {
    $conexion = openBd();

    $sentenciaText = "SELECT 1 FROM projects p 
                     LEFT JOIN project_users pu ON p.id = pu.project_id 
                     WHERE p.id = :project_id 
                     AND (p.owner_id = :user_id OR pu.user_id = :user_id)";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->bindParam(':user_id', $user_id);
    $sentencia->execute();
    
    $resultado = $sentencia->fetch();
    
    closeBd();
    return $resultado !== false;
}

function InsertTask($project_id, $title, $description, $created_by) {
    $conexion = openBd();

    $sentenciaText = "INSERT INTO tasks (project_id, title, description, created_by) 
                     VALUES (:project_id, :title, :description, :created_by)";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->bindParam(':title', $title);
    $sentencia->bindParam(':description', $description);
    $sentencia->bindParam(':created_by', $created_by);
    $sentencia->execute();

    closeBd();
    return $conexion->lastInsertId();
}

?>