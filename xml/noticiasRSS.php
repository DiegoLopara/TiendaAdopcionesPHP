<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
</head>

<body>
    <?php

    require_once('../Conexion/Database.php');
    require_once('../Conexion/config.php');

    $query = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC LIMIT 10";
    $db = new Database();
    $result = $db->ejecutarConsulta($query);

    //header("Content-type: text/xml; encoding='utf-8'");

    // Encabezado del feed RSS
    echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
    <channel>
        <title>Tu Protectora de Animales</title>
        <link>www.tiendaadopciones.com</link>
        <description>Noticias sobre adopciones y nuevas mascotas en la protectora</description>
        <language>es-es</language>';

    // Generar elementos del feed RSS
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '
        <item>
            <title>' . htmlentities($row['titulo']) . '</title>
            <description>' . htmlentities($row['contenido']) . '</description>
            <pubDate>' . date("D, d M Y H:i:s O", strtotime($row['fecha_publicacion'])) . '</pubDate>
            <link>http://tiendaadopciones/noticia/' . $row['id'] . '</link>
        </item>';
    }

    // Cerrar etiquetas del feed RSS
    echo '
    </channel>
</rss>';
    ?>

</body>

</html>