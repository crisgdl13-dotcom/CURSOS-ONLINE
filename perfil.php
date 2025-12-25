



<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

$idUsuario = $_SESSION['idUsuario'];

/* ===============================
   1. CURSOS DISPONIBLES
================================ */
$cursos = mysqli_query($conexion, "
    SELECT idCurso, nombreCurso, descripcion, precio 
    FROM cursos
");

/* ===============================
   2. MIS CURSOS INSCRITOS
================================ */
$misCursos = $conexion->prepare("
    SELECT c.nombreCurso, c.descripcion, c.precio
    FROM inscripciones i
    JOIN cursos c ON i.idCurso = c.idCurso
    WHERE i.idUsuario = ?
");
$misCursos->bind_param("i", $idUsuario);
$misCursos->execute();
$resultadoMisCursos = $misCursos->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="no-background-page">
<div class="container">

<h2>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>

<!-- ===============================
     MIS CURSOS
================================ -->
<h3>Mis Cursos Inscritos</h3>

<?php if ($resultadoMisCursos->num_rows > 0) { ?>
<table border="1" width="100%" cellpadding="8">
  <tr>
    <th>Curso</th>
    <th>Descripción</th>
    <th>Precio</th>
  </tr>

  <?php while ($curso = $resultadoMisCursos->fetch_assoc()) { ?>
    <tr>
      <td><?= htmlspecialchars($curso['nombreCurso']) ?></td>
      <td><?= htmlspecialchars($curso['descripcion']) ?></td>
      <td>$<?= $curso['precio'] ?></td>
    </tr>
  <?php } ?>
</table>
<?php } else { ?>
  <p>No estás inscrito en ningún curso aún.</p>
<?php } ?>

<hr>

<!-- ===============================
     CURSOS DISPONIBLES
================================ -->
<h3>Cursos Disponibles</h3>

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
