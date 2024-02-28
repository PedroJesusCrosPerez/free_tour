$(function() {
    // Configurar la validación del formulario
    $('#create-route').validate({
        rules: {
                // ################################
                // #  div#tabs-1 => Information   #
                // ################################
            name: {
                required: true,
                minlength: 1
            },
            // description: {
            //     required: true,
            //     minlength: 10
            // },
            capacity: {
                required: true,
                min: 1
            },
            period: {
                required: true
            },
                // ################################
                // #     div#tabs-2 => Items      #
                // ################################
                    // ...
                // ################################
                // #  div#tabs-3 => Programation  #
                // ################################
            timepicker: {
                required: true
            }
            // Agregar más reglas de validación si es necesario
        },
        messages: {
                // ################################
                // #  div#tabs-1 => Information   #
                // ################################
            name: {
                required: "Por favor introduce un nombre",
                minlength: "El nombre debe tener al menos 1 caracter"
            },
            // description: {
            //     required: "Por favor introduce una descripción",
            //     minlength: "La descripción debe tener al menos 10 caracteres"
            // },
            capacity: {
                required: "Por favor introduce un valor para el aforo",
                min: "El aforo debe ser al menos 1"
            },
            period: {
                required: "Por favor selecciona un período"
            },
                // ################################
                // #     div#tabs-2 => Items      #
                // ################################
                    // ...
                // ################################
                // #  div#tabs-3 => Programation  #
                // ################################
            timepicker: {
                required: "Por favor selecciona una hora de inicio"
            }
            // Agregar más mensajes de error si es necesario
        },
        errorClass: "input-error",
        errorElement: "label",
        highlight: function(element, errorClass) {
            $(element).addClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        }
    });

    // Manejar el evento de cambio de pestaña
    $('#tabs').tabs({
        beforeActivate: function(event, ui) {
            // Verificar si el usuario está cambiando a una pestaña que contiene los campos name y description
            if (ui.newPanel.find('#name').length > 0) {
                // Verificar si los campos name y description están vacíos
                if (!$('#name').valid()) {
                    // Mostrar un mensaje de error
                    alert("Por favor completa los campos Nombre y Descripción antes de cambiar de pestaña.");
                    // Cancelar el cambio de pestaña
                    return false;
                }
            }
        }
    });

    // Manejar el intento de enviar el formulario
    $('#create-route').submit(function() {
        // Verificar si los campos name y description están vacíos
        if (!$('#name').valid() || !$('#description').valid()) {
            // Mostrar un mensaje de error
            console.log("Por favor completa los campos Nombre y Descripción antes de enviar el formulario.");
            // Detener el envío del formulario
            return false;
        }
    });
});
