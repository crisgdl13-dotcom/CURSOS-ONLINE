<?php
include 'conexion.php';

$usuario_logueado = isset($_SESSION['usuario_id']);
$es_admin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo - Cursos Don Chuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('https://www.shutterstock.com/image-photo/university-student-writing-her-notebook-600nw-2345738153.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .card-curso { transition: transform 0.3s; height: 100%; }
        .card-curso:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .precio { font-size: 1.2rem; font-weight: bold; color: #0d6efd; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-graduation-cap"></i> Cursos Don Chuy</a>
            <div class="d-flex align-items-center">
                <?php if($usuario_logueado): ?>
                    <?php if($es_admin): ?>
                        <a href="admin.php" class="btn btn-warning btn-sm me-3 fw-bold"><i class="fas fa-cogs"></i> Panel Admin</a>
                    <?php endif; ?>
                    <a href="carrito.php" class="btn btn-light btn-sm me-3 position-relative">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                    </a>
                    <a href="perfil.php" class="text-white me-3 d-none d-md-block text-decoration-none fw-bold">
                        <i class="fas fa-user"></i> Hola, <?php echo $_SESSION['nombre']; ?>
                    </a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Salir</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-light btn-sm">Ingresar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Aprende algo nuevo hoy</h1>
            <p class="text-muted">Los mejores cursos impartidos por expertos.</p>
        </div>
        <div class="row">
            <?php
            $sql = "SELECT * FROM cursos ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            while ($curso = $stmt->fetch()):
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card card-curso border-0 shadow-sm">
                        <?php $img = $curso['imagen'] ? $curso['imagen'] : 'https://via.placeholder.com/300x200?text=Curso+Don+Chuy'; ?>
                        <img src="<?php echo $img; ?>" class="card-img-top" alt="Imagen curso" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($curso['nombre']); ?></h5>
                            <p class="card-text text-muted small"><i class="fas fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($curso['instructor']); ?></p>
                            <div class="mb-2 text-warning">
                                <?php
                                $estrellas = round($curso['calificacion']);
                                for($i=1; $i<=5; $i++){
                                    if($i <= $estrellas){ echo '<i class="fas fa-star"></i>'; }
                                    else { echo '<i class="far fa-star"></i>'; }
                                }
                                ?>
                                <span class="text-muted small ms-1">(<?php echo $curso['calificacion']; ?>)</span>
                            </div>
                            <p class="card-text flex-grow-1"><?php echo substr($curso['descripcion'], 0, 80); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="precio">$<?php echo $curso['precio']; ?> MXN</span>
                                <form action="agregar_carrito.php" method="POST">
                                    <input type="hidden" name="curso_id" value="<?php echo $curso['id']; ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-cart-plus"></i> Agregar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
