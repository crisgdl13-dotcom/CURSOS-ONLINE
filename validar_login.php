<?php
session_start();

// 1. INCLUYE TU ARCHIVO DE CONEXIÓN
// Asegúrate que el nombre del archivo sea EXACTO (Ej: 'conexion_db.php', 'db.php', etc.)
include 'conexion_db.php'; 

// 2. Recibir los datos del formulario
$correo = $_POST['correo'];
$password_ingresado = $_POST['password'];

// 3. Consultar a la base de datos
$sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    // El usuario existe, obtenemos sus datos
    $fila = mysqli_fetch_assoc($resultado);
    
    // 4. VERIFICACIÓN DE CONTRASEÑA 
    // Usamos 'contraseña' que es el nombre de la columna en tu BD
    if ($password_ingresado == $fila['contraseña']) { 
        
        // LOGIN EXITOSO - Guardamos datos de sesión
        $_SESSION['usuario_nombre'] = $fila['nombre'];
        $_SESSION['rol_id'] = $fila['idRol']; 
        
        // 5. REDIRECCIÓN
        // Redirigimos a perfil.php (lo crearemos en el siguiente paso)
        header("Location: perfil.php"); 
        exit();
    } else {
        // Contraseña incorrecta
        echo "<script>alert('Contraseña incorrecta'); window.location.href='login.html';</script>";
    }
} else {
    // Correo no encontrado
    echo "<script>alert('El correo no existe'); window.location.href='login.html';</script>";
}
?>

