<?php
session_start();

// Si ya hay sesión iniciada, redirigir a proyectos
if (isset($_SESSION['user_id'])) {
    header('Location: proyectos.php');
    exit;
}

require_once 'php_librarys/bd.php';

// Obtener mensajes de error
$error = '';
if (isset($_GET['error'])) {
    $error = 'Usuario o contraseña incorrectos';
}

$register_error = '';
if (isset($_GET['register_error'])) {
    switch($_GET['register_error']) {
        case 'campos_vacios':
            $register_error = 'El nombre de usuario y la contraseña son obligatorios';
            break;
        case 'usuario_corto':
            $register_error = 'El nombre de usuario debe tener al menos 3 caracteres';
            break;
        case 'password_corta':
            $register_error = 'La contraseña debe tener al menos 4 caracteres';
            break;
        default:
            $register_error = 'Error al registrar el usuario';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Login / Registro</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Login Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Iniciar Sesión</h5>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo ($error); ?></div>
                        <?php endif; ?>
                        <form action="formularios/auth_form.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Register Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registrarse</h5>
                        <?php if ($register_error): ?>
                            <div class="alert alert-danger"><?php echo ($register_error); ?></div>
                        <?php endif; ?>
                        <form action="formularios/auth_form.php" method="POST">
                            <div class="mb-3">
                                <label for="new_username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="new_username" name="new_username" 
                                       required minlength="3" placeholder="Mínimo 3 caracteres">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" 
                                       required minlength="4" placeholder="Mínimo 4 caracteres">
                            </div>
                            <button type="submit" name="register" class="btn btn-success">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>