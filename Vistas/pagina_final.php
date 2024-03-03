<?php
include_once('../Modelos/PagoModelo.php');
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$carritoModelo = new CarritoModelo();
$pagoModelo = new PagoModelo();
$mensaje = $_SESSION['mensaje'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis animales adoptados</title>
    <link rel="stylesheet" href="../css/carrito.css">
</head>

<body>
    <nav class="nav">
        <div class="bienvenido">
            <img src="../img/working.png" class="logo-usuario">
            <h4 class="title">Bienvenido <?php echo $_SESSION['identificador']; ?></h4>
            <h5 class="conexion">Ultima conexión: <?php echo $_SESSION['ultima_conexion_$id']; ?></h5>
        </div>
        <div class="enlaces">
            <ul>
                <?php
                if ($_SESSION['role'] == "administrador") {
                    echo "<a href='../Vistas/menu_admin.php'>Volver atrás🔙</a>";
                } else {
                    echo "<a href='../Vistas/menu_principal.php'>Volver atrás🔙</a>";
                }
                ?>
                <a href='<?php if(isset($_SESSION['correo'])){
                    echo $pagoModelo->enviarCorreo($_SESSION['correo'], "Factura de compra");
                } ?>' target="_blank"> Enviar correo con factura</a>
                <a href="../xml/rss.php" target="_blank"><img src="../img/rss4.png"></a>
                <a href="../Controladores/Controlador.php?accion=cerrar_sesion"><img src="../img/exit6.png">Logout</a>
            </ul>
        </div>
    </nav>

    <h2 class="titulo">Mis animales adoptados</h2>
    <div class="mensaje">
        <?php if (isset($_SESSION['mensaje'])) {
            echo $mensaje;
        } ?>
    </div>

    <!-- Grid de animales adoptados -->
    <div class="grid-container">
        <?php $mascotasEnCarrito = $carritoModelo->mostrarAnimalesEnCarrito($usuario_id); ?>
        
        <?php
        foreach ($mascotasEnCarrito as $mascotaEnCarrito) { ?>
            <div class="grid-item">
                <img src='../<?php echo $mascotaEnCarrito->foto ?>' width='200' height='150' alt='Foto de la mascota'>
                <p>Nombre: <?php echo  $mascotaEnCarrito->nombre ?></p>
                <p>Fecha de adquisición:<?php echo $mascotaEnCarrito->fecha ?></p>
                <p>Estado:<?php echo  $mascotaEnCarrito->estado ?></p>
            </div>
        <?php } ?>

    </div>
</body>

</html>