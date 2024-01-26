<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar pago</title>
    <link rel="stylesheet" href="../css/carrito.css">
</head>

<body>
    <div class="formulario-pago">
        <h2>Total a pagar: <strong><?php echo $precio * count($carrito) ?><trong> euros</h2>
        <form action="../Controladores/Controlador.php" method="POST">
            <label for="nombre">Nombre en la Tarjeta:</label>
            <input type="text" id="nombre" name="nombreTarjeta" required><br><br>

            <label for="numero">Número de Tarjeta:</label>
            <input type="text" id="numero" name="numeroTarjeta" placeholder="** ** ** **" maxlength="19" required><br><br>

            <label for="vencimiento">Fecha de Vencimiento:</label>
            <input type="text" id="vencimiento" name="vencimiento" placeholder="MM/AA" maxlength="5" required><br><br>

            <label for="cvv">CVV:</label><br>
            <input type="text" id="cvv" name="cvv" placeholder="***" maxlength="3" required><br><br>

            <!-- Agregar campos ocultos para los IDs -->
            <?php foreach ($carrito as $mascotaCarrito) : ?>
                <input type="hidden" name="mascota_id" value="<?php echo $mascotaCarrito->mascota_id; ?>">
            <?php endforeach; ?>

            <button type="submit" name="finalizarPago">Finalizar pago</button>
        </form>
    </div>

</body>

</html>