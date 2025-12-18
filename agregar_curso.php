

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 1. Seguridad: solo admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 1) {
    echo "<script>alert('Acceso denegado. Solo administradores'); window.location='login.html';</script>";
    exit;
}

// 2. Conexión
require 'conexion.php';

// 3. Verificar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_cursos.html");
    exit;
}

// 4. Conectar a BD
$conn = new mysqli($host, $usuario, $password, $basededatos);
if ($conn->connect_error) {
    die("Error BD: " . $conn->connect_error);
}

// 5. Recibir datos



$titulo = $conn->real_escape_string($_POST['titulo']);
$precio = floatval($_POST['precio_actual']);

$sql = "INSERT INTO cursos (nombreCurso, descripcion, precio, idInstructor)
        VALUES ('$titulo', 'Curso agregado desde panel admin', $precio, 1)";










if ($conn->query($sql)) {
    echo "<script>alert('Curso agregado correctamente'); window.location='admin_cursos.html';</script>";
} else {
    die("Error SQL: " . $conn->error);
}

$conn->close();
?>







