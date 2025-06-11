<?php

header('Content-Type: application/json');


require_once 'php_librarys/bd.php';


$proyectos = Selectprojects();


echo json_encode($proyectos);
?>
