


<?php
session_start();
require 'conexion.php';

/* Obtener cursos con nombre del instructor */
$sql = "
SELECT c.idCurso, c.nombreCurso, c.descripcion, c.precio, u.nombre AS instructor
FROM cursos c
JOIN usuarios u ON c.idInstructor = u.idUsuario
";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cursos Disponibles</title>
  <link rel="stylesheet" href="styles.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

<header>
  <nav class="navbar">
    <a href="index.php" class="logo">Cursos Online</a>

    <?php if (!isset($_SESSION['usuario'])) { ?>
      <a href="login.php" class="btn">Ingresar</a>
      <a href="registro.html" class="btn primary">Registrarse</a>
    <?php } else { ?>
      <a href="perfil.php" class="btn">Mi perfil</a>
      <a href="logout.php" class="btn">Salir</a>
    <?php } ?>
  </nav>
</header>

<main class="container">

<h2 class="section-title">Cursos Disponibles</h2>

<section class="courses-section">
  <div class="course-grid">

  <?php while ($curso = mysqli_fetch_assoc($resultado)) { ?>
    <div class="course-card">
      <img src="img/default.jpg" alt="<?= htmlspecialchars($curso['nombreCurso']) ?>">

      <div class="card-content">
        <h3><?= htmlspecialchars($curso['nombreCurso']) ?></h3>

        <p class="instructor">
          Instructor: <?= htmlspecialchars($curso['instructor']) ?>
        </p>

        <p><?= htmlspecialchars($curso['descripcion']) ?></p>

        <div class="price-info">
          <span class="current-price">$<?= $curso['precio'] ?> USD</span>
        </div>

        <?php if (isset($_SESSION['usuario'])) { ?>
          <a href="perfil.php" class="btn primary">Inscribirme</a>
        <?php } else { ?>
          <a href="login.php" class="btn primary">Inicia sesión</a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>

  </div>
</section>

</main>
</body>
</html>
