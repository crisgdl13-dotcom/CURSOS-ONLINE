<?php
// Incluye la conexión a la base de datos
require 'conexion.php'; 

// Función para sanitizar datos (protección básica)
function sanitizar($conexion, $dato) {
    return mysqli_real_escape_string($conexion, trim($dato));
}

// Verifica que el formulario haya sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Conexión a la base de datos
    $conn = new mysqli($host, $usuario, $password, $basededatos);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // 2. Obtener y sanitizar datos
    $email = sanitizar($conn, $_POST['email']);
    $password_ingresada = $_POST['password'];

    // 3. Consultar usuario por email
    $sql = "SELECT password_hash, rol FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $password_hash = $fila['password_hash'];
        $rol = $fila['rol'];

        // 4. Verificar la contraseña
        if (password_verify($password_ingresada, $password_hash)) {
            // Contraseña correcta: iniciar sesión (usando una sesión simple)
            session_start();
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_rol'] = $rol;

            // 5. Redireccionar según el rol
            if ($rol === 'admin') {
                header("Location: admin_cursos.html"); // Redirige al panel de administración
            } else {
                header("Location: perfil.html"); // Redirige al perfil de usuario normal
            }
            exit();

        } else {
            // Contraseña incorrecta
            echo "<script>alert('Correo o Contraseña incorrecta.'); window.location.href='login.html';</script>";
        }

    } else {
        // Usuario no encontrado
        echo "<script>alert('Correo o Contraseña incorrecta.'); window.location.href='login.html';</script>";
    }

    $conn->close();
} else {
    // Si se accede directamente sin POST, redirigir al login
    header("Location: login.html");
    exit();
}
?>