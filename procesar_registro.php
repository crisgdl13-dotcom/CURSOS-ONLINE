<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registro.php");
    exit;
}

$nombre   = trim($_POST['nombre']);
$correo   = trim($_POST['correo']);
$password = $_POST['password'];
$password2 = $_POST['password2'];

/* Validaciones */
if ($password !== $password2) {
    die("Las contraseñas no coinciden");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

/* Rol estudiante = 3 */
$sql = "INSERT INTO usuarios (nombre, correo, contrasena, idRol)
        VALUES (?, ?, ?, 3)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $correo, $hash);

if ($stmt->execute()) {
    header("Location: login.php");
} else {
    die("Error al registrar usuario: " . $conexion->error);
}
