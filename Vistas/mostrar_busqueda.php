<?php
require_once("../Modelos/MascotaModelo.php");
$mascotaModelo = new MascotaModelo();
$ultimaConexion = isset($_COOKIE['ultima_conexion']) ? $_COOKIE['ultima_conexion'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type='text/css' rel='stylesheet' href='../css/carrito.css'>
    </style>
    <title>Búsqueda por criterios</title>
</head>

<body>
    <nav class="nav">
        <div class="bienvenido">
            <img src="../img/working.png" class="logo-usuario">
            <h4 class="title">Bienvenido <?php echo isset($_SESSION['identificador']) ? $_SESSION['identificador'] : '';?></h4>
            <h5 class="conexion">Ultima conexión: <?php echo isset($_COOKIE['ultima_conexion']) ? $_COOKIE['ultima_conexion'] : '' ?></h5>
        </div>
        <div class="enlaces">
            <ul>
                <?php
                if ($_SESSION['role']== "administrador") {
                    echo "<a href='../Vistas/menu_admin.php'>Volver atrás🔙</a>";
                } else {
                    echo "<a href='../Vistas/menu_principal.php'>Volver atrás🔙</a>";
                }
                ?>
                <a href="../xml/rss.php" target="_blank"><img src="../img/rss4.png"></a>
                <!-- <a href="./lista_mascotas.php"><img src="../img/cart.png"></a> -->
                <a href="../Vistas/Cerrar_Sesion.php"><img src="../img/exit6.png">Logout</a>
            </ul>
        </div>
    </nav>
    <h3>Resultados de la búsqueda🕵️ </h3>

    <div class="grid-container">

        <?php
          if (isset($mascotasFiltradas) && !empty($mascotasFiltradas)) {
            $mascotaModelo->mostrarMascotasFiltradas($mascotasFiltradas);
        } else {
            echo "<div style='text-align:center';>No hay resultados de búsqueda disponibles.</div>";
        }
        ?>
    </div>
</body>
</html>