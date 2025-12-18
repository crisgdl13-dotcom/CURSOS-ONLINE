

<?php
session_start();

/* PROTECCIÓN DE RUTA */
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 1) {
    echo "<script>alert('Acceso denegado. Solo administradores.'); window.location.href='login.html';</script>";
    exit();
}

require 'conexion.php';

/* VALIDAR MÉTODO */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_cursos.html");
    exit();
}

/* CONEXIÓN */
$conn = new mysqli($host, $usuario, $password, $basededatos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/* DATOS */
$titulo = $conn->real_escape_string($_POST['titulo']);
$instructor = $conn->real_escape_string($_POST['instructor']);
$imagen_url = $conn->real_escape_string($_POST['imagen_url']);
$precio_actual = (float) $_POST['precio_actual'];
$precio_anterior = (float) $_POST['precio_anterior'];
$etiqueta = $conn->real_escape_string($_POST['etiqueta']);

$valoracion = 0;
$reviews = 0;

/* INSERT */
$sql = "INSERT INTO cursos 
(titulo, instructor, imagen_url, precio_actual, precio_anterior, etiqueta, valoracion, reviews)
VALUES 
('$titulo', '$instructor', '$imagen_url', $precio_actual, $precio_anterior, '$etiqueta', $valoracion, $reviews)";

if ($conn->query($sql)) {
    echo "<script>alert('Curso agregado correctamente'); window.location.href='admin_cursos.html';</script>";
} else {
    echo "<script>alert('Error al insertar: {$conn->error}'); history.back();</script>";
}

$conn->close();




