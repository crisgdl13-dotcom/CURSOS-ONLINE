<?php
session_start();

// Verificar si nos enviaron un ID de curso
if (isset($_POST['curso_id'])) {
    $curso_id = $_POST['curso_id'];

    // 1. Si el carrito no existe, lo creamos como un array vacío
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // 2. Verificamos si el curso YA está en el carrito para no repetirlo
    if (!in_array($curso_id, $_SESSION['carrito'])) {
        // Lo agregamos a la lista
        $_SESSION['carrito'][] = $curso_id;
    }
}

// 3. Nos regresamos a la tienda automáticamente
header("Location: index.php");
exit;
?>
