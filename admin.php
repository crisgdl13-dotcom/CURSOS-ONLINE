<?php
include 'conexion.php';

// Verificación de seguridad (Admin)
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_curso'])) {
    $nombre = $_POST['nombre'];
    $instructor = $_POST['instructor'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $video_url = $_POST['video_url'];

    // Imagen por defecto
    $imagen_final = "https://via.placeholder.com/300";

    // OPCIÓN A: ¿Subió un archivo?
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $directorio = "uploads/";
        if (!is_dir($directorio)) { mkdir($directorio, 0777, true); } // Crear carpeta si no existe

        $nombre_archivo = $directorio . time() . "_" . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $nombre_archivo)) {
            $imagen_final = $nombre_archivo;
        }
    }
    // OPCIÓN B: ¿Pegó una URL? (Solo si no subió archivo)
    elseif (!empty($_POST['imagen_url'])) {
        $imagen_final = $_POST['imagen_url'];
    }

$sql = "INSERT INTO cursos (nombre, instructor, precio, descripcion, imagen, video_url) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

    if ($stmt->execute([$nombre, $instructor, $precio, $descripcion, $imagen_final, $video_url])) {
        $mensaje = "✅ Curso agregado correctamente.";
    } else {
        $mensaje = "❌ Error al agregar el curso.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Don Chuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark p-3">
        <div class="container">
            <a class="navbar-brand" href="index.php">⬅ Volver a la Tienda</a>
            <span class="navbar-text">Modo Administrador</span>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Panel de Control</h2>

        <?php if($mensaje): ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>
<?php if(isset($_GET['mensaje'])): ?>
    <div class="alert alert-success">
        <?php
        if($_GET['mensaje'] == 'borrado') echo "🗑️ Curso eliminado correctamente.";
        if($_GET['mensaje'] == 'editado') echo "✅ Cambios guardados.";
        ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        ⛔ <?php echo $_GET['error']; ?>
    </div>
<?php endif; ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4">
                    <h4>➕ Nuevo Curso</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Nombre del Curso</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Instructor</label>
                            <input type="text" name="instructor" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Precio ($)</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2"></textarea>
                        </div>

                        <hr>
                        <h6 class="text-primary">Imagen del Curso</h6>
                        <p class="small text-muted mb-2">Elige UNA opción:</p>

                        <div class="mb-2">
                            <label class="small">Opción 1: Subir Archivo</label>
                            <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                        </div>

                        <div class="text-center text-muted small my-1">- O -</div>

<div class="mb-3">
    <label>Link del Video (Embed)</label>
    <input type="text" name="video_url" class="form-control" placeholder="Ej: https://www.youtube.com/embed/CODIGO" required>
    <small class="text-muted">Ve a YouTube -> Compartir -> Insertar -> Copia solo lo que está dentro de src="..."</small>
</div>

                        <button type="submit" name="crear_curso" class="btn btn-success w-100">Publicar Curso</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4">
                    <h4>📚 Cursos Actuales</h4>
<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Img</th>
            <th>Curso</th>
            <th>Precio</th>
            <th>Acciones</th> </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM cursos ORDER BY id DESC");
        while ($curso = $stmt->fetch()):
        ?>
        <tr>
            <td>
                <img src="<?php echo $curso['imagen']; ?>" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
            </td>
            <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
            <td>$<?php echo $curso['precio']; ?></td>
            <td>
                <a href="editar_curso.php?id=<?php echo $curso['id']; ?>" class="btn btn-sm btn-warning">
                    ✏️
                </a>

                <a href="borrar_curso.php?id=<?php echo $curso['id']; ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('¿Estás seguro de que quieres eliminar este curso?');">
                    🗑️
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
