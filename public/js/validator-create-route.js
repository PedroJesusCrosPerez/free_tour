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
            description: {
                required: true,
                minlength: 10
            },
            capacity: {
                required: true,
                min: 1
            },
            period: {
                required: true
            },
            fileInput: {
                required: true,
                fileRequired: true // Regla personalizada para validar si se ha seleccionado un archivo
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
            description: {
                required: "Por favor introduce una descripción",
                minlength: "La descripción debe tener al menos 10 caracteres"
            },
            capacity: {
                required: "Por favor introduce un valor para el aforo",
                min: "El aforo debe ser al menos 1"
            },
            period: {
                required: "Por favor selecciona un período"
            },
            fileInput: {
                required: "Por favor seleccione un archivo."
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
    
    inicialize_OnChange_checkers_Information();
    inicialize_OnChange_checkers_Items();
    inicialize_OnChange_checkers_Programation();

    let lastActive = "tabs-1";

    // Manejar el evento de cambio de pestaña
    $("#tabs>ul>li").on("click", function() {
        // console.log($(this))
        // console.log($(this).parent())
        // console.log($(this).parent().find("li[aria-controls='tabs-3']"))
        // console.log($(this).parent().find("li[aria-expanded='true']"))
        // $(this).addClass("inputBig-error");
        // $(this).removeClass("inputBig-error");

        var clicked = $(this).parent().find("li[aria-expanded='true']").attr("aria-controls")
        console.log("ir a " + clicked + " desde " + lastActive);

        switch ($(this).attr("aria-controls")) 
        {
            case "tabs-1":
                $("#tabs").tabs("option", "active", 0);
                break;

            case "tabs-2":
                // debugger
                // Verificar la validez de checkInformation
                if (checkInformation()) {
                    // TRUE
                    $("#tabs").tabs("option", "active", 1);
                    $(this).parent().find("li[aria-controls='tabs-1']").removeClass("inputBig-error");
                    lastActive = clicked;
                } else {
                    $("#tabs").tabs("option", "active", 0);
                    $(this).parent().find("li[aria-controls='tabs-1']").addClass("inputBig-error");
                }
                break;

            case "tabs-3":
                // debugger
                // if ($(this).parent().find("li[aria-expanded='true']").attr("aria-controls") == "tabs-1") {
                //     $("#tabs").tabs("option", "active", 0);
                // } else if ($(this).parent().find("li[aria-expanded='true']").attr("aria-controls") == "tabs-2") {
                //     // Verificar la validez de checkItems
                //     if (checkItems()) {
                //         $("#tabs").tabs("option", "active", 2);
                //         $(this).parent().find("li[aria-controls='tabs-1']").removeClass("inputBig-error");
                //     } else {
                //         $("#tabs").tabs("option", "active", 1);
                //         $(this).parent().find("li[aria-controls='tabs-1']").addClass("inputBig-error");
                //     }
                // }
                
                if (lastActive == "tabs-1") {
                    $("#tabs").tabs("option", "active", 0);
                } else if (lastActive == "tabs-2") {
                    // Verificar la validez de checkItems
                    if (checkItems()) {
                        // TRUE
                        $("#tabs").tabs("option", "active", 2);
                        $(this).parent().find("li[aria-controls='tabs-2']").removeClass("inputBig-error");
                        lastActive = clicked;
                    } else {
                        $("#tabs").tabs("option", "active", 1);
                        $(this).parent().find("li[aria-controls='tabs-2']").addClass("inputBig-error");
                    }
                }
                break;

            default:
                break;
        }

    });


    // // Manejar el evento de cambio de pestaña
    // $('#tabs').tabs({
    //     beforeActivate: function(event, ui) {
    //         // Verificar si el usuario está cambiando a una pestaña que contiene los campos name y description
    //         if (ui.newPanel.find('#name').length > 0) {
    //             // Verificar si los campos name y description están vacíos
    //             if (!$('#name').valid()) {
    //                 // Mostrar un mensaje de error
    //                 alert("Por favor completa los campos Nombre y Descripción antes de cambiar de pestaña.");
    //                 // Cancelar el cambio de pestaña
    //                 return false;
    //             }
    //         }
    //     }
    // });

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
