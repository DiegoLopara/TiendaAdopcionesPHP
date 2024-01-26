<?php
session_start();
include_once('../Modelos/MascotaModelo.php');
$mascotaModelo = new MascotaModelo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu Usuario</title>
    <link rel="stylesheet" href="../css/menus.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/novedades.js"></script>
</head>

<body>
    <!-- Novedades AJAX. El archivo utilizado es novedades.js y Controladores/ajax-obtener-novedades.php -->
    <div class="tabla-ajax-novedades" id="novedadesContainer">
        <table>
            <tr>
                <th>Últimas novedades de nuestra protectora 📰:</th>
            </tr>
            <tr>
                <td id="novedadesContent"><?php echo $mascotasRecientes; ?></td>
            </tr>
            <tr>
                <td><?php echo "hacer funcion para sacar los recien comprados" ?></td>
            </tr>
        </table>
    </div>
    <!-- Barra de navegación -->
    <nav class="nav">
        <div class="bienvenido">
            <img src="../img/working.png" class="logo-usuario">
            <h4 class="title">Bienvenido <?php echo $_SESSION['identificador']; ?></h4>
            <h5 class="conexion">Ultima conexión: <?php echo $_COOKIE['ultima_conexion']; ?></h5>
        </div>
        <div class="enlaces">
            <ul>
                <a href="../xml/noticiasRSS.php" target="_blank"><img src="../img/rss4.png">RSS Feed</a>
                <a href="./lista_mascotas.php"><img src="../img/cart.png"></a>

                <a href="../Controladores/Controlador.php?accion=cerrar_sesion"><img src="../img/exit6.png">Logout</a>
            </ul>
        </div>
    </nav>
    <!-- Buscador -->
    <div class="busqueda">
        <?php
        $criteriosUnicos = $mascotaModelo->obtenerCriteriosUnicos();
        ?>
        <form method="GET" action="../Controladores/Controlador.php" class="busqueda">
            <div class="campo-busqueda">
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="">Todos</option>
                    <?php foreach ($criteriosUnicos['tipos'] as $tipo) : ?>
                        <option value="<?php echo $tipo; ?>"><?php echo $tipo; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo-busqueda">
                <label for="raza">Raza:</label>
                <select name="raza" id="raza">
                    <option value="">Todos</option>
                    <?php foreach ($criteriosUnicos['razas'] as $raza) : ?>
                        <option value="<?php echo $raza; ?>"><?php echo $raza; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo-busqueda">
                <label for="edad">Edad:</label>
                <select name="edad" id="edad">
                    <option value="">Todos</option>
                    <?php foreach ($criteriosUnicos['edades'] as $edad) : ?>
                        <option value="<?php echo $edad; ?>"><?php echo $edad; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo-busqueda">
                <label for="color">Color:</label>
                <select name="color" id="color">
                    <option value="">Todos</option>
                    <?php foreach ($criteriosUnicos['colores'] as $color) : ?>
                        <option value="<?php echo $color; ?>"><?php echo $color; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="buscar">Buscar 🐹🔎</button>
        </form>
    </div>
    <?php
    // Verificar si hay un mensaje y mostrarlo
    if (isset($_SESSION['mensaje'])) {
        $mensaje =$_SESSION['mensaje'];
        echo "<div class='mensaje'>$mensaje</div>";
    }
    ?>

    <!-- Grid de mascotas disponibles: -->
    <div class="grid-container">
        <?php
        $mascotas = $mascotaModelo->obtenerTodo();
        foreach ($mascotas as $mascota) {
            $mascotaModelo->mostrarMascota($mascota);
        }
        ?>
    </div>
    <footer>
        <p>© Desarrollo Web Entorno Servidor - Diego y Amanda 2ºDAW</p>
    </footer>
</body>

</html>