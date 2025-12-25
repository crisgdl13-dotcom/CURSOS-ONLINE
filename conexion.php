


<?php
$host = "localhost";
$usuario = "root";
$password = "";
$basededatos = "cursos_online";
$conexion = new mysqli($host, $usuario, $password, $basededatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>


