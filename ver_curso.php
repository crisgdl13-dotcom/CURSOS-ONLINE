<?php
include 'conexion.php';

// 1. Seguridad básica
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit; }
if (!isset($_GET['id'])) { header("Location: perfil.php"); exit; }

$curso_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];
$mensaje = "";

// 2. SEGURIDAD: ¿Compró el curso?
$sql = "SELECT * FROM compras WHERE usuario_id = ? AND curso_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id, $curso_id]);
$compra = $stmt->fetch();

if (!$compra) {
    echo "⛔ Acceso Denegado."; exit;
}

// --- 3. LÓGICA DE VOTACIÓN (NUEVO) ---
if (isset($_GET['votar'])) {
    $voto = (int)$_GET['votar'];

    if ($voto >= 1 && $voto <= 5) {
        // A) Guardamos tu voto individual
        $sql_update = "UPDATE compras SET calificacion = ? WHERE usuario_id = ? AND curso_id = ?";
        $pdo->prepare($sql_update)->execute([$voto, $usuario_id, $curso_id]);

        // B) Calculamos el NUEVO promedio de este curso
        // (Promediamos todos los votos de la tabla compras para este curso)
        $sql_promedio = "SELECT AVG(calificacion) as promedio FROM compras WHERE curso_id = ? AND calificacion IS NOT NULL";
        $stmt_prom = $pdo->prepare($sql_promedio);
        $stmt_prom->execute([$curso_id]);
        $nuevo_promedio = $stmt_prom->fetch()['promedio'];

        // C) Actualizamos el curso para que se vea en el Index
        $sql_curso_update = "UPDATE cursos SET calificacion = ? WHERE id = ?";
        $pdo->prepare($sql_curso_update)->execute([$nuevo_promedio, $curso_id]);

        $mensaje = "¡Gracias por tu calificación!";
        // Refrescamos la variable $compra para que se ilumine la estrella ya
        $compra['calificacion'] = $voto;
    }
}

// 4. Traer info del curso
$stmt_curso = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt_curso->execute([$curso_id]);
$curso = $stmt_curso->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($curso['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .estrella-btn { font-size: 2rem; color: #ccc; text-decoration: none; transition: color 0.2s; }
        .estrella-btn:hover, .estrella-activa { color: #ffc107; } /* Amarillo */
    </style>
</head>
<body class="bg-dark text-white">

    <nav class="navbar navbar-dark bg-secondary p-3">
        <div class="container">
            <a class="navbar-brand" href="perfil.php">⬅ Volver a Mis Cursos</a>
            <span><?php echo htmlspecialchars($curso['nombre']); ?></span>
        </div>
    </nav>

    <div class="container mt-4">

        <?php if($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="ratio ratio-16x9 shadow-lg mb-4" style="border: 2px solid #444;">
                    <iframe src="https://www.youtube.com/embed/5qap5aO4i9A" allowfullscreen></iframe>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <h3>Lección 1: Introducción</h3>

                    <div class="bg-secondary p-2 rounded text-center">
                        <p class="mb-0 small">Califica este curso:</p>
                        <div>
                            <?php
                            // Dibujamos las 5 estrellas
                            for($i=1; $i<=5; $i++){
                                // Si la estrella es menor o igual a tu voto, la pintamos amarilla
                                $clase = ($i <= $compra['calificacion']) ? 'estrella-activa' : '';
                                echo "<a href='ver_curso.php?id=$curso_id&votar=$i' class='estrella-btn $clase'>★</a>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <p class="mt-3"><?php echo $curso['descripcion']; ?></p>
            </div>

            <div class="col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-header"><i class="fas fa-list"></i> Contenido</div>
                    <ul class="list-group list-group-flush text-dark">
                        <li class="list-group-item active">1. Introducción <i class="fas fa-play-circle float-end"></i></li>
                        <li class="list-group-item">2. Conceptos básicos <i class="fas fa-lock float-end text-muted"></i></li>
                    </ul>
                </div>

                <div class="mt-4 p-3 bg-white text-dark rounded">
                    <h5>Instructor:</h5>
                    <strong><?php echo htmlspecialchars($curso['instructor']); ?></strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
