

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - Cursos Online</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="no-background-page">
<div class="container">

<h2>Crear cuenta</h2>

<form action="procesar_registro.php" method="POST">

  <label>Nombre completo</label>
  <input type="text" name="nombre" class="form-control" required>

  <label>Correo electrónico</label>
  <input type="email" name="correo" class="form-control" required>

  <label>Contraseña</label>
  <input type="password" name="password" class="form-control" required>

  <label>Confirmar contraseña</label>
  <input type="password" name="password2" class="form-control" required>

  <button type="submit" class="btn primary">Registrarse</button>

</form>

<p style="margin-top:15px;">
  ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
</p>

</div>
</body>
</html>
