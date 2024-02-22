
function toggleToursByTime(fecha) {
    var fechaFormateada = moment(fecha).format("YYYY-MM-DD");

    // Iterar sobre las opciones del select
    $("#tour option").each(function() {
        // Ignorar la opción con el texto "Horas disponibles"
        if ($(this).text() === "Horas disponibles") {
            return true; // Continuar con la siguiente iteración
        }

        var fechaOpcion = $(this).data("date");

        // Mostrar u ocultar la opción según la comparación de fechas
        if (fechaOpcion === fechaFormateada) {
            $(this).show(); // Mostrar la opción
        } else {
            $(this).hide(); // Ocultar la opción
        }
    });
}

function resetSelect() {
    // Seleccionar la primera opción del select
    $("#tour").val($("#tour option:first").val());
}