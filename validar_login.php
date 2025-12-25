<?php
session_start();
require 'conexion.php';

/* 1. Verificar que venga del formulario */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

/* 2. Recibir datos */
$correo = trim($_POST['email']);
$password = $_POST['password'];

/* 3. Conectar a la BD */
$conn = new mysqli($host, $usuario, $password_db = $password, $basededatos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

/* 4. Buscar usuario */
$stmt = $conn->prepare("SELECT idUsuario, nombre, contrasena, idRol FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {

    $usuario = $resultado->fetch_assoc();

    /* 5. Verificar contraseña */
    if (password_verify($password, $usuario['contrasena'])) {

        /* 6. Crear sesión */
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['idUsuario'] = $usuario['idUsuario'];
        $_SESSION['rol'] = $usuario['idRol'];

        /* 7. Redirección por rol */
        if ($usuario['idRol'] == 1) {
            header("Location: admin_cursos.php");
        } else {
            header("Location: perfil.php");
        }
        exit();

    } else {
        header("Location: login.php?error=1");
        exit();
    }

} else {
    header("Location: login.php?error=1");
    exit();
}

$conn->close();
