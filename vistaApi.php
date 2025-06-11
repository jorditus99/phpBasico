<?php
// Verificar sesión
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Obtener datos de la API
$proyectos = json_decode(file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/phpBasico/api.php'), true);
?>
<!DOCTYPE html>
<html>
<head>
    <title>API de Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1 class="mb-4">Proyectos</h1>
    <a href="proyectos.php" class="btn btn-secondary mb-4">Volver</a>
    
    <?php if ($proyectos): ?>
        <ul class="list-group">
            <?php foreach ($proyectos as $proyecto): ?>
                <li class="list-group-item">
                    <?php echo ($proyecto['name']); ?>
                    <small class="text-muted">(ID: <?php echo ($proyecto['id']); ?>)</small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning">No hay proyectos disponibles</div>
    <?php endif; ?>
</body>
</html>