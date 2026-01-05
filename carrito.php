<?php
include 'conexion.php';

$carrito_vacio = empty($_SESSION['carrito']);
$cursos_en_carrito = [];
$total = 0;

if (!$carrito_vacio) {
    $ids = implode(",", $_SESSION['carrito']);
    $sql = "SELECT * FROM cursos WHERE id IN ($ids)";
    $stmt = $pdo->query($sql);
    $cursos_en_carrito = $stmt->fetchAll();
}

if (isset($_GET['vaciar'])) {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito - Cursos Don Chuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .modal-content { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .form-control-lg { border-radius: 10px; font-size: 1rem; padding: 0.8rem 1rem; border: 1px solid #eee; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary p-3"><div class="container"><a class="navbar-brand" href="index.php">⬅ Volver</a></div></nav>
    <div class="container mt-5">
        <h2><i class="fas fa-shopping-cart"></i> Tu Carrito</h2>
        <?php if ($carrito_vacio): ?>
            <div class="alert alert-info text-center p-5"><h4>Vacío 😢</h4><a href="index.php" class="btn btn-primary mt-3">Comprar</a></div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm p-4">
                        <table class="table align-middle">
                            <tbody>
                                <?php foreach ($cursos_en_carrito as $curso): ?>
                                <tr>
                                    <td><img src="<?php echo $curso['imagen']; ?>" class="rounded" style="width: 60px; height: 40px; object-fit: cover;"></td>
                                    <td><h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($curso['nombre']); ?></h6></td>
                                    <td class="text-end fw-bold text-primary">$<?php echo number_format($curso['precio'], 2); ?></td>
                                </tr>
                                <?php $total += $curso['precio']; endforeach; ?>
                            </tbody>
                        </table>
                        <a href="carrito.php?vaciar=true" class="btn btn-outline-danger btn-sm">Vaciar Carrito</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-4">
                        <h4>Resumen</h4>
                        <div class="d-flex justify-content-between fs-3 fw-bold text-primary mb-4"><span>Total:</span><span>$<?php echo number_format($total, 2); ?></span></div>
                        <button class="btn btn-success w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#modalPago">Finalizar Compra</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="modal fade" id="modalPago" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content p-4">
        <h4>Agregar tarjeta</h4>
        <form action="pagar.php" method="POST">
            <input type="text" class="form-control form-control-lg mb-3" placeholder="Nombre titular" required>
            <input type="text" class="form-control form-control-lg mb-3" placeholder="Número tarjeta" maxlength="16" required>
            <button type="submit" class="btn btn-primary w-100">Pagar ahora</button>
        </form>
    </div></div></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
