
<?php
include 'conexion.php';

// 1. Seguridad
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') { header("Location: index.php"); exit; }

if (!isset($_GET['id'])) { header("Location: admin.php"); exit; }
$id = $_GET['id'];

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$id]);
$curso = $stmt->fetch();

if (!$curso) { echo "Curso no encontrado."; exit; }

// 3. PROCESAR GUARDADO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $instructor = $_POST['instructor'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    // --- AQUÍ RECIBIMOS EL LINK DEL VIDEO ---
    $video_url = $_POST['video_url'];

    // Imagen
    $imagen_final = $curso['imagen'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $directorio = "uploads/";
        $nombre_archivo = $directorio . time() . "_" . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $nombre_archivo)) {
            $imagen_final = $nombre_archivo;
        }
    } elseif (!empty($_POST['imagen_url'])) {
        $imagen_final = $_POST['imagen_url'];
    }

    // --- ACTUALIZAMOS LA BASE DE DATOS INCLUYENDO EL VIDEO ---
    $sql = "UPDATE cursos SET nombre=?, instructor=?, precio=?, descripcion=?, imagen=?, video_url=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $instructor, $precio, $descripcion, $imagen_final, $video_url, $id]);

    header("Location: admin.php?mensaje=editado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-warning text-dark"><h4>✏️ Editar Curso</h4></div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($curso['nombre']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Instructor</label>
                        <input type="text" name="instructor" class="form-control" value="<?php echo htmlspecialchars($curso['instructor']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-primary">Link del Video (YouTube)</label>
                        <input type="text" name="video_url" class="form-control"
                               value="<?php echo htmlspecialchars($curso['video_url'] ?? ''); ?>"
                               placeholder="Pega aquí el link de YouTube">
                        <small class="text-muted">Acepta links normales (youtu.be) o embed.</small>
                    </div>

                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $curso['precio']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                    </div>

                    <hr>
                    <p><strong>Imagen Actual:</strong></p>
                    <img src="<?php echo $curso['imagen']; ?>" width="100" class="mb-3 rounded">

                    <div class="mb-2">
                        <label class="small">Cambiar imagen (Archivo):</label>
                        <input type="file" name="foto" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="small">O URL de imagen:</label>
                        <input type="text" name="imagen_url" class="form-control form-control-sm">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="admin.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
