


<?php
require 'conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new mysqli($host, $usuario, $password, $basededatos);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password_ingresada = $_POST['password'];

    // CONSULTA CORRECTA
    $sql = "SELECT contraseña, idRol FROM usuarios WHERE correo = '$email'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows === 1) {

        $fila = $resultado->fetch_assoc();
        $password_hash = $fila['contraseña'];
        $rol = (int)$fila['idRol'];

        if (password_verify($password_ingresada, $password_hash)) {

            session_start();
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_rol'] = $rol;

            if ($rol === 1) {
                header("Location: admin_cursos.html");
            } else {
                header("Location: perfil.html");
            }
            exit();

        } else {
            echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='login.html';</script>";
        }

    } else {
        echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='login.html';</script>";
    }

    $conn->close();
}
?>


