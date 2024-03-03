<?php
session_start();
include_once('../Modelos/MascotaModelo.php');
$mascotaModelo = new MascotaModelo();

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['identificador'])) {
    header("Location: ../Vistas/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Menu Administrador</title>
    <link rel="stylesheet" href="../css/menus.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/novedades.js"></script>
</head>

<body>
    <!-- Novedades AJAX -->
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
            <h5 class="conexion">⌚ Ultima conexión: <?php echo $_SESSION['ultima_conexion_$id']; ?></h5>
        </div>
        <div class="enlaces">
            <ul>
                <a href="../xml/noticiasRSS.php" target="_blank"><img src="../img/rss4.png"></a>
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
        $mensaje = $_SESSION['mensaje'];
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

    <!-- FORMULARIOS DE ADMIN -->
    <div class="funciones-admin">

        <div class="insertar">
            <!-- Insertar -->
            <!-- Formulario para añadir una mascota utilizando la funcion insertarMascota del modelo-->
            <h4>Insertar nueva mascota➕</h4>
            <form method="POST" action="../Controladores/Controlador.php" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre"><br>
                <label for="edad">Edad:</label>
                <input type="number" name="edad" id="edad"><br>
                <label for="raza">Raza:</label>
                <input type="text" name="raza" id="raza"><br>
                <label for="tipo">Tipo:</label>
                <input type="text" name="tipo" id="tipo"><br>
                <label for="color">Color:</label>
                <input type="text" name="color" id="color"><br>
                <label for="id_dueño">ID del dueño:</label>
                <input type="number" name="id_dueño" id="id_dueño"><br>
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto"><br>
                <label for="tamaño">Tamaño:</label>
                <select name="tamaño" id="tamaño">
                    <option value="pequeño">Pequeño</option>
                    <option value="mediano">Mediano</option>
                    <option value="grande">Grande</option>
                </select><br><br>
                <input type="submit" name="insertar" value="Insertar">
            </form>
        </div>


        <div class="insertar-xml">
            <!-- Insertar desde XML -->
            <!-- Formulario para añadir una mascota utilizando la funcion importarDatosDesdeXML:  -->
            <h4>Insertar con XML📁</h4>
            <form method="POST" action="../Controladores/Controlador.php" enctype="multipart/form-data">
                <label for="archivo">Selecciona un archivo XML:</label>
                <input type="file" name="archivo" accept=".xml" id="archivo"><br><br>
                <input type="submit" name="importar" value="Importar">
            </form>
        </div>

        <div class="actualizar">
            <!-- Actualizar -->
            <!-- Formulario para actualizar mascota -->
            <h4>Actualizar mascota📝</h4>
            <form method="POST" action="../Controladores/Controlador.php" enctype="multipart/form-data">
                <label for="mascota_id">Selecciona una mascota:</label>
                <select name="mascota_id" id="mascota_id">
                    <!-- Aquí debes cargar dinámicamente las opciones del select con los IDs de tus mascotas -->
                    <?php foreach ($mascotas as $mascota) : ?>
                        <option value="<?php echo $mascota->id; ?>"><?php echo $mascota->nombre; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <!-- Campos para actualizar -->
                <label for="nombre">Nuevo nombre:</label>
                <input type="text" name="nombre" id="nombre"><br>
                <label for="tipo">Nuevo tipo:</label>
                <input type="text" name="tipo" id="tipo"><br>
                <label for="raza">Nueva raza:</label>
                <input type="text" name="raza" id="raza"><br>
                <label for="edad">Nueva edad:</label>
                <input type="number" name="edad" id="edad"><br>
                <label for="color">Nuevo color:</label>
                <input type="text" name="color" id="color"><br>
                <label for="id_dueño">Nuevo ID del dueño:</label>
                <input type="number" name="id_dueño" id="id_dueño"><br>
                <label for="foto">Nueva foto:</label>
                <input type="file" name="foto" id="foto"><br>
                <br>
                <input type="submit" name="actualizar" value="Actualizar">
            </form>
        </div>

        <div class="eliminar">
            <!-- Eliminar mascota , cambiar a nombre en vez de ID -->
            <h4>Eliminar mascota🗑️</h4>
            <form method="POST" action="../Controladores/Controlador.php">
                <label for="nombre">Nombre de la mascota a eliminar:</label><br>
                <input type="text" name="nombre" id="nombre"><br><br>
                <input type="submit" name="eliminar" value="Eliminar">
            </form>
        </div>
    </div>




    <!-- Mostrar noticias RSS -->
    <!-- <div id="noticias-container"></div> -->
    <!-- <script>
        // Utiliza AJAX para cargar el contenido de rss.php
        var noticiasContainer = $('#noticias-container');

        $.ajax({
            url: '../xml/rss.php',
            type: 'GET',
            dataType: 'xml',
            success: function(response) {
                var noticiasHtml = '';

                $(response).find('item').each(function() {
                    var titulo = $(this).find('title').text();
                    var link = $(this).find('link').text();
                    var descripcion = $(this).find('description').text();
                    var fecha = $(this).find('pubDate').text();

                    // Construir el HTML para cada noticia
                    noticiasHtml += '<div class="noticia">';
                    noticiasHtml += '<h3><a href="' + link + '">' + titulo + '</a></h3>';
                    noticiasHtml += '<p>' + descripcion + '</p>';
                    noticiasHtml += '<p>Publicado el ' + fecha + '</p>';
                    noticiasHtml += '</div>';
                });

                // Mostrar las noticias en el contenedor
                noticiasContainer.html(noticiasHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar noticias:', error);
            }
        });
    </script> -->





    <!-- Link para RSS -->
    <!-- <link rel="alternate" type="application/rss+xml" title="Feed RSS" href="../xml/rss.php" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // Función para cargar los datos usando AJAX
        function cargarDatos() {
            $.ajax({
                url: '../Vistas/menu_admin.php', // Ruta al archivo PHP que proporciona los datos
                method: 'GET',
                success: function(response) {
                    // Actualizar el contenido con los datos
                    $('#novedades').html(response.novedades);
                    mostrarNombreEncontrado(response.nombreProducto);
                    console.log('Datos actualizados');

                    // Después de la primera carga, iniciar el intervalo
                    if (primeraCarga) {
                        setInterval(cargarDatos, 30000); // Intervalo en milisegundos (30 segundos)
                        primeraCarga = false;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos:', error);
                }
            });
        } -->

    <!-- // Cargar los datos al cargar la página
        $(document).ready(function() {
            cargarDatos();
            console.log('Datos actualizados');
        }); -->
    <!-- </script> -->
    <footer>
        <p>© Desarrollo Web Entorno Servidor - Diego y Amanda 2ºDAW</p>
    </footer>
</body>

</html>