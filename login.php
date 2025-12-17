<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conexion.php'; 

// 1. DEFINIR LA FUNCIÓN AL PRINCIPIO
function sanitizar($conexion, $dato) {
    if (!$conexion) return trim($dato);
    return mysqli_real_escape_string($conexion, trim($dato));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($host, $usuario, $password, $basededatos);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // 2. USAR LA FUNCIÓN
    $email = sanitizar($conn, $_POST['email']);
    $password_ingresada = $_POST['password'];

    // 3. CONSULTA (Asegúrate que coincida con tu BD de AWS)
    $sql = "SELECT contraseña, idRol FROM usuarios WHERE correo = '$email'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $password_hash = $fila['contraseña'];
        $rol = $fila['idRol'];

        // 4. VERIFICACIÓN FLEXIBLE
        if (password_verify($password_ingresada, $password_hash) || $password_ingresada == $password_hash) {
            session_start();
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_rol'] = $rol;

            // 5. REDIRECCIÓN POR ID (1=Admin, otro=Usuario)
            if ($rol == 1) {
                header("Location: admin_cursos.html");
            } else {
                header("Location: productos.html");
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href='login.html';</script>";
    }
}
?>
