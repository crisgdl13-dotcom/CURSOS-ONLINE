

<?php
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Cursos Don Chuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary p-3">
        <div class="container">
            <a class="navbar-brand" href="index.php">⬅ Volver a la Tienda</a>
            <span class="text-white">Perfil de Usuario</span>
        </div>
    </nav>
    <div class="container mt-5">
        <?php if(isset($_GET['compra']) && $_GET['compra'] == 'exitosa'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <strong>¡Felicidades!</strong> Tu compra se realizó correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm text-center p-4">
                    <div class="mb-3"><i class="fas fa-user-circle fa-5x text-secondary"></i></div>
                    <h3><?php echo htmlspecialchars($nombre); ?></h3>
                    <p class="text-muted">Estudiante</p>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-outline-primary">Ir al Catálogo</a>
                        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white"><h4 class="mb-0"><i class="fas fa-book-open text-primary"></i> Mis Cursos</h4></div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT c.id,c.nombre, c.instructor, c.descripcion, co.fecha_compra
                                FROM compras co
                                INNER JOIN cursos c ON co.curso_id = c.id
                                WHERE co.usuario_id = ?
                                ORDER BY co.fecha_compra DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$usuario_id]);
                        $mis_cursos = $stmt->fetchAll();

                        if (count($mis_cursos) > 0):
                        ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($mis_cursos as $curso): ?>
                                    <div class="list-group-item p-3">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1 text-primary"><?php echo htmlspecialchars($curso['nombre']); ?></h5>
                                            <small class="text-muted"><?php echo date('d/m/Y', strtotime($curso['fecha_compra'])); ?></small>
                                        </div>
                                        <p class="mb-1">Instructor: <?php echo htmlspecialchars($curso['instructor']); ?></p>
                                        <a href="ver_curso.php?id=<?php echo $curso['id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-play"></i> Ver Clases</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p>Aún no has comprado ningún curso.</p>
                                <a href="index.php" class="btn btn-primary">Ver Cursos Disponibles</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
