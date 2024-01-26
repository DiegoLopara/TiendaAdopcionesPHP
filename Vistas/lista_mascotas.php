<?php
session_start();
include_once('../Modelos/CarritoModelo.php');
include_once('../Modelos/MascotaModelo.php');

$carrito=isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$numeroElementosCarrito=isset($_SESSION['elementosCarrito']) ? $_SESSION['elementosCarrito'] : 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi carrito</title>
    <link rel="stylesheet" href="../css/carrito.css">
</head>

<body>
    <nav class="nav">
        <div class="bienvenido">
            <img src="../img/working.png" class="logo-usuario">
            <h4 class="title">Bienvenido <?php echo $_SESSION['identificador']; ?></h4>
            <h5 class="conexion">Ultima conexión: <?php echo $_COOKIE['ultima_conexion']; ?></h5>
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
                <a href="../xml/rss.php" target="_blank"><img src="../img/rss4.png"></a>
                <a href="../Controladores/Controlador.php?accion=cerrar_sesion"><img src="../img/exit6.png">Logout</a>
            </ul>
        </div>
    </nav>

    <div class="titulo">

        <h2>Mi Carrito (<?php echo $numeroElementosCarrito; ?>)🛍️🐻</h2>
        <span>
            <p><?php if (isset($_SESSION['mensaje'])) {
                    echo $_SESSION['mensaje'];
                } ?></p>

        </span>
    </div>
    
    
    
    <div class="grid-container">
        <?php foreach ($carrito as $mascotaCarrito) : ?>
            <?php 
                $mascotaModelo = new MascotaModelo();
                $mascotaAgregada =$mascotaModelo->obtenerMascotaPorID($mascotaCarrito->mascota_id); ?>
            <?php if ($mascotaAgregada) : ?>
            

                <div class='grid-item'>
                    <p> Nombre:<?php echo $mascotaAgregada->nombre;?></p>
                    <p> Tipo:<?php echo  $mascotaAgregada->tipo; ?></p>
                    <p> Raza:<?php echo $mascotaAgregada->raza; ?></p>
                    <img src='../<?php echo $mascotaAgregada->foto; ?>' width='200' height='150' alt='Foto de la mascota'>
                    <br><br>
                    <form method='POST' action='../Controladores/Controlador.php'>
                        <input type='hidden' name='mascota_id' value=<?php echo $mascotaAgregada->id; ?>>
                        <input type='submit' name='eliminarCarrito' value='Eliminar del carrito'>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if(!empty($carrito)): ?>
            <form action='../Controladores/Controlador.php' method='POST'>
                <input type='hidden' name='accion' value='procesarPago'>
                <button type='submit' name='procesarPago'>Adoptame💳</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>