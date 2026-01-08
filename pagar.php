<?php
include 'conexion.php';

// Solo usuarios logueados pueden comprar
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verificar si hay algo que comprar
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {

    $usuario_id = $_SESSION['usuario_id'];

    // Preparamos la orden para insertar en la BD
    $sql = "INSERT INTO compras (usuario_id, curso_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);

    // Recorremos el carrito y guardamos cada curso uno por uno
    foreach ($_SESSION['carrito'] as $curso_id) {
        try {
            $stmt->execute([$usuario_id, $curso_id]);
        } catch (Exception $e) {
            // Si ya lo compró antes, simplemente ignoramos el error y seguimos
            continue;
        }
    }

    // ¡Éxito! Vaciamos el carrito
    unset($_SESSION['carrito']);

    // Mandamos al usuario a ver sus nuevos cursos
    header("Location: perfil.php?compra=exitosa");
    exit;

} else {
    // Si intenta pagar con carrito vacío, lo regresamos
    header("Location: index.php");
    exit;
}
?>
