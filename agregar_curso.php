

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

/* =========================
   Seguridad: solo ADMIN
========================= */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

/* =========================
   Validar método POST
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_cursos.php");
    exit();
}

/* =========================
   Conexión BD
========================= */
require 'conexion.php';

/* =========================
   Recibir datos del formulario
========================= */
$nombreCurso  = trim($_POST['nombreCurso']);
$descripcion  = trim($_POST['descripcion']);
$precio       = floatval($_POST['precio']);
$idInstructor = intval($_POST['idInstructor']);

/* =========================
   Validación backend mínima
========================= */
if (strlen($nombreCurso) < 5 || $precio <= 0) {
    die("Datos inválidos");
}

/* =========================
   Insert seguro (Prepared)
========================= */
$stmt = $conexion->prepare(
    "INSERT INTO cursos (nombreCurso, descripcion, precio, idInstructor)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ssdi", $nombreCurso, $descripcion, $precio, $idInstructor);

if ($stmt->execute()) {
    header("Location: admin_cursos.php?ok=1");
} else {
    die("Error SQL: " . $stmt->error);
}

$stmt->close();
$conexion->close();
?>
