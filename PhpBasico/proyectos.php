<?php
session_start();

// Verificar si hay sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'PhpBasico/php_librarys/bd.php';

$proyectos = Selectprojects($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Lista de Proyectos</title>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Lista de Proyectos</h1>
            <div>
                <span class="me-3">Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>

        <!-- Formulario para crear nuevo proyecto -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Crear Nuevo Proyecto</h5>
                <form action="formularios/project_form.php" method="POST" class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label for="project_name" class="form-label">Nombre del Proyecto</label>
                        <input type="text" class="form-control" id="project_name" name="project_name" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Crear Proyecto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de proyectos -->
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Proyecto</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proyecto['id']); ?></td>
                            <td><?php echo htmlspecialchars($proyecto['name']); ?></td>
                            <td class="text-end">
                                <a href="tareas.php?id=<?php echo $proyecto['id']; ?>" class="btn btn-primary btn-sm">Abrir proyecto</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>