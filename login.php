<?php
include 'conexion.php';

// Si ya está logueado, que no vea el login
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

// Cuando le dan al botón "Ingresar"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Buscamos al usuario por su correo
    $sql = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['correo' => $correo]);
    $usuario = $stmt->fetch();

    // Verificamos contraseña
    // NOTA: Para este ejercicio usamos texto plano. En producción real usaríamos password_verify()
    if ($usuario && $contrasena === $usuario['contrasena']) {
        // ¡Login correcto! Guardamos datos en la sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redireccionamos según quién sea
        if ($usuario['rol'] == 'admin') {
            // Aún no creamos admin.php, así que por hoy lo mandamos al index también
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Correo o contraseña incorrectos ❌";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Cursos Don Chuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-login { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card bg-white card-login">
        <h3 class="text-center text-primary mb-4">Iniciar Sesión</h3>
        <?php if(isset($_GET['registrado'])): ?>
    <div class="alert alert-success">¡Registro exitoso! Ahora inicia sesión.</div>
<?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" required placeholder="ejemplo@correo.com">
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required placeholder="********">
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        <div class="mt-3 text-center">
<small>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></small>
        </div>
    </div>
</body>
</html>
