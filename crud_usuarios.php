
<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

/* Obtener usuarios */
$usuarios = mysqli_query($conexion, "
    SELECT u.idUsuario, u.nombre, u.correo, r.nombreRol
    FROM usuarios u
    JOIN roles r ON u.idRol = r.idRol
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>CRUD Usuarios</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="no-background-page">
<div class="container">

<h2>Administrar Usuarios</h2>

<!-- Formulario agregar usuario -->
<form action="agregar_usuario.php" method="POST">
  <input type="text" name="nombre" placeholder="Nombre" class="form-control" required>
  <input type="email" name="correo" placeholder="Correo" class="form-control" required>
  <input type="password" name="contrasena" placeholder="Contraseña" class="form-control" required>

  <select name="idRol" class="form-control" required>
    <option value="">Seleccione rol</option>
    <?php
    $roles = mysqli_query($conexion, "SELECT * FROM roles");
    while ($r = mysqli_fetch_assoc($roles)) {
        echo "<option value='{$r['idRol']}'>{$r['nombreRol']}</option>";
    }
    ?>
  </select>

  <button type="submit" class="btn primary">Agregar Usuario</button>
</form>

<!-- Tabla usuarios -->
<table border="1" width="100%" cellpadding="8" style="margin-top:30px;">
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Correo</th>
    <th>Rol</th>
    <th>Acciones</th>
  </tr>

  <?php while ($u = mysqli_fetch_assoc($usuarios)) { ?>
    <tr>
      <td><?= $u['idUsuario'] ?></td>
      <td><?= $u['nombre'] ?></td>
      <td><?= $u['correo'] ?></td>
      <td><?= $u['nombreRol'] ?></td>
      <td>
        <a href="eliminar_usuario.php?id=<?= $u['idUsuario'] ?>"
           onclick="return confirm('¿Eliminar usuario?');">
           Eliminar
        </a>
      </td>
    </tr>
  <?php } ?>
</table>

</div>
</body>
</html>

