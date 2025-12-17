


<?php
require 'conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function sanitizar($conexion, $dato) {
    return mysqli_real_escape_string($conexion, trim($dato));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new mysqli($host, $usuario, $password, $basededatos);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $email = sanitizar($conn, $_POST['email']);
    $password_ingresada = $_POST['password'];

    // 🔑 CONSULTA CORRECTA CON ROL
    $sql = "
        SELECT u.contraseña, u.idRol, r.nombreRol
        FROM usuarios u
        JOIN roles r ON u.idRol = r.idRol
        WHERE u.correo = '$email'
        LIMIT 1
    ";

    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows === 1) {

        $fila = $resultado->fetch_assoc();
        $password_hash = $fila['contraseña'];
        $rol_id = $fila['idRol'];
        $rol_nombre = $fila['nombreRol'];

        if (password_verify($password_ingresada, $password_hash)) {

            session_start();
            $_SESSION['usuario_email'] = $email;
            $_SESSION['rol_id'] = $rol_id;
            $_SESSION['rol_nombre'] = $rol_nombre;

            // 🔀 REDIRECCIÓN POR ROL
            if ($rol_id == 1) {
                header("Location: admin_cursos.html");
            } else {
                header("Location: perfil.html");
            }
            exit();

        } else {
            echo "<script>alert('Correo o contraseña incorrecta'); window.location.href='login.html';</script>";
        }

    } else {
        echo "<script>alert('Correo o contraseña incorrecta'); window.location.href='login.html';</script>";
    }

    $conn->close();

} else {
    header("Location: login.html");
    exit();
}
?>


