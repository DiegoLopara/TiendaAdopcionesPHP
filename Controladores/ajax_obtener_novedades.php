<?php
include_once('../Modelos/MascotaModelo.php');

// Coloca aquí tu lógica para obtener las novedades
$mascotaModelo = new MascotaModelo();
$mascotasRecientes = $mascotaModelo->obtenerMascotasRecienInsertadas(3);

?>
