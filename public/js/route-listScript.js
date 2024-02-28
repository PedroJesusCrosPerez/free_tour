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
});