$(function () {
    // Inicializar el diálogo
    $('.not-logged').click(function(e) {
        $("body").css("overflow-y", "hidden");
        e.preventDefault();  // Evita que el enlace realice su acción por defecto

        // Crea el modal
        $('<div>').dialog({
            modal: true,
            title: "Notificación",
            open: function() {
                var markup = 'Para acceder a esta funcionalidad debes estar logeado.';
                $(this).html(markup);
            },
            buttons: {
                "Iniciar sesión": function() {
                    // Redirigir al usuario a la página de inicio de sesión
                    window.location.href = window.location.origin + '/login';
                },
                "Cerrar": function() {
                    $("body").css("overflow-y", "auto");
                    $(this).dialog("close");
                }
            }
        });  // Fin del diálogo
    });  // Fin del evento click

    // // Obtener los items del servidor mediante una solicitud POST a la API
    // $.ajax({
    //     type: 'GET',
    //     url: '/api/upload/create-route',
    //     contentType: 'application/json',
    //     success: function (response) {
    //         console.log(response)
    //         uploadFilterLocality(response.items)
    //     },
    //     error: function (error) {
    //         console.error('Error:', error);
    //         console.log('Error:', error.responseText);
    //     }
    // });
    // $('#filter_locality').on('change', function() {
    //     var locality_id = $(this).find('option:selected').data('locality_id');
    //     toggleItemsByLocalityId([locality_id]);
    // });
});