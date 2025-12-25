
      <?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

require 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrar Cursos</title>
  <link rel="stylesheet" href="styles.css">
  <script src="validar_curso.js"></script>
</head>

<body class="no-background-page">
<div class="container">

<h2>Agregar Nuevo Curso</h2>

<form action="agregar_curso.php" method="POST" onsubmit="return validarCurso()">

  <label>Título del Curso:</label>
  <input type="text" id="titulo" name="nombreCurso" class="form-control" required>

  <label>Descripción:</label>
  <textarea name="descripcion" class="form-control" required></textarea>

  <label>Instructor:</label>
  <select name="idInstructor" class="form-control" required>
    <option value="">Seleccione instructor</option>
    <?php
    $inst = mysqli_query($conexion, "SELECT idUsuario, nombre FROM usuarios WHERE idRol = 2");
    while ($i = mysqli_fetch_assoc($inst)) {
        echo "<option value='{$i['idUsuario']}'>{$i['nombre']}</option>";
    }
    ?>
  </select>

  <label>Precio:</label>
  <input type="number" step="0.01" id="precio_actual" name="precio" class="form-control" required>

  <button type="submit" class="btn primary">Agregar Curso</button>
</form>

<?php
$cursos = mysqli_query($conexion, "SELECT * FROM cursos");
?>

<h2 style="margin-top:40px;">Cursos Registrados</h2>

<table border="1" width="100%" cellpadding="8">
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Acciones</th>
  </tr>

  <?php while ($curso = mysqli_fetch_assoc($cursos)) { ?>
    <tr>
      <td><?= $curso['idCurso'] ?></td>
      <td><?= $curso['nombreCurso'] ?></td>
      <td>$<?= $curso['precio'] ?></td>
      <td>
        <a href="eliminar_curso.php?id=<?= $curso['idCurso'] ?>"
           onclick="return confirm('¿Eliminar curso?');">
           Eliminar
        </a>
      </td>
    </tr>
  <?php } ?>
</table>

<p style="margin-top:20px;">
  <a href="productos.php">Ver cursos</a>
</p>

</div>
</body>
</html>













      


