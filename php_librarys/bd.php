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


function Selectprojects($user_id = null){
    $conexion = openBd();

    if ($user_id) {
        $sentenciaText = "SELECT DISTINCT projects.* FROM projects 
                         LEFT JOIN project_users ON projects.id = project_users.project_id 
                         WHERE projects.owner_id = :user_id OR project_users.user_id = :user_id";
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

    $sentenciaText = "SELECT tasks.*, users.username as creator_username 
                     FROM tasks 
                     INNER JOIN users ON tasks.created_by = users.id 
                     WHERE tasks.project_id = :project_id";
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

    $sentenciaText = "SELECT users.id, users.username FROM users 
                     INNER JOIN project_users ON users.id = project_users.user_id 
                     WHERE project_users.project_id = :project_id";
    $sentencia = $conexion->prepare($sentenciaText);
    $sentencia->bindParam(':project_id', $project_id);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();

    closeBd();
    return $resultado;
}

function get_available_users($project_id) {
    $conexion = openBd();

    $sentenciaText = "SELECT users.id, users.username FROM users 
                     WHERE users.id NOT IN (
                         SELECT user_id FROM project_users WHERE project_id = :project_id
                     ) AND users.id NOT IN (
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