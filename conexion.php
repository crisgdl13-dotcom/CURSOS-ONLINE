

<?php
$host = "localhost";
$usuario = "root";
$password = "Abcdef12345#";
$basededatos = "cursos_online_db";

$conexion = new mysqli($host, $usuario, $password, $basededatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
