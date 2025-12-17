
<!DOCTYPE html> 
<?php
include 'conexion_db.php'; 

// Consulta para obtener todos los cursos
$sql_cursos = "SELECT * FROM cursos";
$resultado_cursos = mysqli_query($conexion, $sql_cursos);
?>
<!DOCTYPE html>
<html lang="es">



<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cursos Disponibles</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    .courses-section {
      background-color: #f0f0f0; /* gris claro */
      padding: 30px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

  <header>
    <nav class="navbar">
      <a href="index.html" class="logo">Cursos Online</a>
      <a href="login.html" class="btn">Ingresar</a>
      <a href="registro.html" class="btn primary">Registrarse</a>
    </nav>
  </header>

  <main class="container">
    
    <h2 class="section-title">Cursos Disponibles</h2>

  <section class="courses-section">
  <div class="course-grid">

    <?php 
    // 3. Empezar el bucle (LOOP) para mostrar cada curso
    while ($curso = mysqli_fetch_assoc($resultado_cursos)) {
    ?>

    <div class="course-card">
      <img src="img/default.jpg" alt="<?php echo $curso['nombreCurso']; ?>"> 
      <div class="card-content">
        <h3><?php echo $curso['nombreCurso']; ?></h3>
        <p class="instructor">Instructor con ID: <?php echo $curso['idInstructor']; ?></p>
        <div class="rating">...</div>
        <div class="price-info">
          <span class="current-price"><?php echo $curso['precio']; ?> USD</span>
          <span class="old-price"></span>
        </div>
        <button class="btn primary">Ver detalles</button>
      </div>
    </div>

    <?php
    // 4. Cierra el bucle
    }
    ?>

  </div>
</section>
  </main>
</body>
</html>

