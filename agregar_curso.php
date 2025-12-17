<?php



session_start();

echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
exit();

//esto se modifico lo que me dijiste
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 1) {
//sooo esto
    
    echo "<script>alert('Acceso denegado. Solo administradores.'); window.location.href='login.html';</script>";
    exit();
}





require 'conexion.php'; // Incluye la conexión

// Verifica que el formulario haya sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Conexión a la base de datos
    $conn = new mysqli($host, $usuario, $password, $basededatos);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // 2. Obtener y sanitizar datos (protección contra inyección SQL)
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $instructor = $conn->real_escape_string($_POST['instructor']);
    $imagen_url = $conn->real_escape_string($_POST['imagen_url']);
    $precio_actual = (float)$_POST['precio_actual'];
    $precio_anterior = (float)$_POST['precio_anterior'];
    $etiqueta = $conn->real_escape_string($_POST['etiqueta']);
    
    // Valores predeterminados para nueva inserción
    $valoracion = 0.0;
    $reviews = 0;

    // 3. Crear la consulta SQL para insertar
    $sql = "INSERT INTO cursos (titulo, instructor, imagen_url, precio_actual, precio_anterior, etiqueta, valoracion, reviews) 
            VALUES ('$titulo', '$instructor', '$imagen_url', $precio_actual, $precio_anterior, '$etiqueta', $valoracion, $reviews)";

    // 4. Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Curso agregado exitosamente.'); window.location.href='admin_cursos.html';</script>";
    } else {
        echo "<script>alert('Error al agregar el curso: " . $conn->error . "'); window.location.href='admin_cursos.html';</script>";
    }

    $conn->close();
} else {
    header("Location: admin_cursos.html");
    exit();
}

?>



