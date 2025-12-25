
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

/* Obtener cursos */
$cursos = mysqli_query($conexion, "SELECT * FROM cursos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cursos Disponibles</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="no-background-page">
<div class="container">

<h2>Cursos Disponibles</h2>

<table border="1" width="100%" cellpadding="8">
  <tr>
    <th>Curso</th>
    <th>Descripción</th>
    <th>Precio</th>
    <th>Acción</th>
  </tr>

  <?php while ($curso = mysqli_fetch_assoc($cursos)) { ?>
    <tr>
      <td><?= htmlspecialchars($curso['nombreCurso']) ?></td>
      <td><?= htmlspecialchars($curso['descripcion']) ?></td>
      <td>$<?= $curso['precio'] ?></td>
      <td>
        <form action="inscribirse.php" method="POST" style="margin:0;">
          <input type="hidden" name="idCurso" value="<?= $curso['idCurso'] ?>">
          <button type="submit" class="btn primary">Inscribirse</button>
        </form>
      </td>
    </tr>
  <?php } ?>

</table>

<p style="margin-top:20px;">
  <a href="logout.php">Cerrar sesión</a>
</p>

</div>
</body>
</html>
