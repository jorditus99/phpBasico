<?php
session_start();

// Verificar si hay sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'PhpBasico/php_librarys/bd.php';

// Verificar si se proporcionó un ID de proyecto
if (!isset($_GET['id'])) {
    header('Location: proyectos.php');
    exit;
}

$project_id = $_GET['id'];

// Verificar si el usuario tiene acceso al proyecto
if (!can_access_project($project_id, $_SESSION['user_id'])) {
    header('Location: proyectos.php');
    exit;
}

$tareas = Selecttasks($project_id);
$usuarios_disponibles = get_available_users($project_id);
$usuarios_proyecto = get_project_users($project_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Tareas del Proyecto</title>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Tareas del Proyecto</h1>
            <div class="d-flex align-items-center">
                <?php if (count($usuarios_disponibles) > 0): ?>
                    <form action="formularios/task_form.php" method="POST" class="d-flex me-3">
                        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                        <select name="user_id" class="form-select me-2" required>
                            <option value="">Seleccionar usuario...</option>
                            <?php foreach ($usuarios_disponibles as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>">
                                    <?php echo htmlspecialchars($usuario['username']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="add_user" class="btn btn-success">Agregar Usuario</button>
                    </form>
                <?php endif; ?>
                <a href="proyectos.php" class="btn btn-secondary">Volver a Proyectos</a>
            </div>
        </div>

        <?php if (count($usuarios_proyecto) > 0): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Usuarios con acceso al proyecto:</h5>
                    <ul class="list-inline mb-0">
                        <?php foreach ($usuarios_proyecto as $usuario): ?>
                            <li class="list-inline-item">
                                <span class="badge bg-info"><?php echo htmlspecialchars($usuario['username']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear nueva tarea -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Crear Nueva Tarea</h5>
                <form action="formularios/task_form.php" method="POST" class="row g-3">
                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                    <div class="col-md-6">
                        <label for="task_title" class="form-label">Título de la Tarea</label>
                        <input type="text" class="form-control" id="task_title" name="task_title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="task_description" class="form-label">Descripción (opcional)</label>
                        <input type="text" class="form-control" id="task_description" name="task_description">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="create_task" class="btn btn-primary">Crear Tarea</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="list-group">
            <?php foreach ($tareas as $tarea): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($tarea['title']); ?></h5>
                            <?php if (isset($tarea['description']) && !empty($tarea['description'])): ?>
                                <p class="mb-1"><?php echo htmlspecialchars($tarea['description']); ?></p>
                            <?php endif; ?>
                            <small class="text-muted">Creada por: <?php echo htmlspecialchars($tarea['creator_username']); ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
