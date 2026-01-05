<?php
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // 1. Verificar si el correo ya existe
    $sql_check = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$correo]);

    if ($stmt_check->rowCount() > 0) {
        $mensaje = "❌ Ese correo ya está registrado.";
    } else {
        // 2. Insertar nuevo usuario (Rol por defecto: usuario)
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, 'usuario')";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$nombre, $correo, $contrasena])) {
            // Redirigir al login con mensaje de éxito
            header("Location: login.php?registrado=1");
            exit;
        } else {
            $mensaje = "❌ Error al registrar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Cursos Don Chuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-registro { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card bg-white card-registro">
        <h3 class="text-center text-primary mb-4">Crear Cuenta</h3>

        <?php if($mensaje): ?>
            <div class="alert alert-danger"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Tu nombre">
            </div>
            <div class="mb-3">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" required placeholder="correo@ejemplo.com">
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required placeholder="Crea una contraseña">
            </div>
            <button type="submit" class="btn btn-success w-100">Registrarme</button>
        </form>
        <div class="mt-3 text-center">
            <small>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></small>
        </div>
    </div>
</body>
</html>
