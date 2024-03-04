
// Locality
function uploadFilterLocality(items) {
    // Obtener el contenido de la plantilla de filter locality
    var select = $("#filter_locality");
    var existingIds = {}; // Objeto para almacenar IDs existentes

    // Obtener los IDs de las opciones existentes
    select.find('option').each(function() {
        existingIds[$(this).data('locality_id')] = true;
    });

    // Option por defecto
    var optionAll = $("<option>")
    optionAll.attr("data-locality_id", "0");
    optionAll.attr("data-province_id", "0");
    optionAll.html("Todas las localidades");
    select.append(optionAll);

    // Iterar sobre los nuevos items
    for (const item of items) {
        // Verificar si el ID ya existe
        if (!existingIds[item.locality.id]) {
            // Crear y agregar la opción si el ID no existe
            var option = $("<option>")
            option.html(item.locality.name + ", " + item.locality.province.name);
            option.attr("data-locality_id", item.locality.id);
            option.attr("data-province_id", item.locality.province.id);
            select.append(option);

            // Marcar el ID como existente
            existingIds[item.locality.id] = true;
        }
    }
}

function toggleItemsByLocalityId(arrLocality_id) {
    // Filter locality
    var ul = $("ul[name='select-items']");
    if (!arrLocality_id.includes(0)) {
        // Mostrar solo los elementos cuyos IDs coincidan con los recibidos
        ul.find("li").each(function() {
            var locality_id = $(this).data('locality');
            if (arrLocality_id.includes(locality_id)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    } else {
        // Si el ID recibido es 0, mostrar todos los elementos
        ul.find("li").show();
    }
}