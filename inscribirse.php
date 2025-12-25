<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$idCurso = intval($_POST['idCurso']);

/* Verificar si ya está inscrito */
$check = $conexion->prepare(
    "SELECT idInscripcion FROM inscripciones WHERE idUsuario = ? AND idCurso = ?"
);
$check->bind_param("ii", $idUsuario, $idCurso);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>
        alert('Ya estás inscrito en este curso');
        window.location='perfil.php';
    </script>";
    exit();
}

/* Insertar inscripción */
$stmt = $conexion->prepare(
    "INSERT INTO inscripciones (idUsuario, idCurso) VALUES (?, ?)"
);
$stmt->bind_param("ii", $idUsuario, $idCurso);

if ($stmt->execute()) {
    echo "<script>
        alert('Inscripción realizada correctamente');
        window.location='perfil.php';
    </script>";
} else {
    echo "<script>
        alert('Error al inscribirse');
        window.location='perfil.php';
    </script>";
}
