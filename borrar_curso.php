<?php
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $sql = "DELETE FROM cursos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        header("Location: admin.php?mensaje=borrado");
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            header("Location: admin.php?error=Este curso ya tiene alumnos inscritos, no se puede borrar.");
        } else {
            echo "Error inesperado: " . $e->getMessage();
        }
    }
} else {
    header("Location: admin.php");
}
exit;
?>
