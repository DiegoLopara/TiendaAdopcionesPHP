// Espera a que el DOM esté listo
$(document).ready(function () {
    // Función para cargar las novedades mediante AJAX
    function cargarNovedades() {
        $.ajax({
            url: '../Controladores/ajax_obtener_novedades.php',
            type: 'GET',
            dataType: 'html',
            success: function (data) {
                $('#novedadesContent').html(data);
                // Desaparecer las novedades después de 10 segundos
                setTimeout(function () {
                    $('#novedadesContainer').fadeOut();
                }, 10000);
            },
            error: function (error) {
                console.error('Error al cargar las novedades: ' + error);
            }
        });
    }

    // Cargar las novedades al cargar la página
    cargarNovedades();
});