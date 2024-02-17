function selectGuide() {
    // Select guide
    $('.guide').on('click', function () {
        var someselected = $('li[class="guide selected"]');
        if ( someselected ) {
            someselected.removeClass('selected');
            $(this).toggleClass('selected');
        } else {
            $(this).toggleClass('selected');
        }
    })
}

function uploadItems(items) {
    // Obtener el contenido de la plantilla de item
    $.get("/templates/item.html", function (text) {
        var contenedor = $("<div>").html(text);
        var item = contenedor.find(">:first-child");

        for (const itemData of items) {
            var itemAux = item.clone();
            itemAux.find(".item-name").html(itemData.name)
            itemAux.attr("data-id", itemData.id);
            itemAux.attr("data-locality", itemData.locality.id);
            itemAux.attr("data-province", itemData.locality.province.id);
            itemAux.find("img").attr("src", "/images/item/" + itemData.photo);
            $("#sortable1").append(itemAux);
        }
    });
}

function uploadGuides(guides) {
    // Obtener el contenido de la plantilla de guide
    $.get("/templates/guide.html", function (text) {
        var contenedor = $("<div>").html(text);
        var guide = contenedor.find(">:first-child");
        
        for (const guideData of guides) {
            var guideAux = guide.clone();
            guideAux.attr("data-id", guideData.id);
            // guideAux.data("id", "6"); // TODO preguntar ¿por qué no funciona?
            guideAux.find(".fullname").html(guideData.fullname)
            guideAux.find("img").attr("src", "/uploads/images/" + guideData.photo);
            $("#guide-list").append(guideAux)
        }
        selectGuide()
    });
}

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


// Province
function uploadFilterProvince(items) {
    // Obtener el contenido de la plantilla de filter province
    var select = $("#filter_province");
    var existingIds = {}; // Objeto para almacenar IDs de provincias existentes

    // Obtener los IDs de las opciones existentes
    select.find('option').each(function() {
        existingIds[$(this).data('province_id')] = true;
    });

    // Option por defecto
    var optionAll = $("<option>")
    optionAll.attr("data-province_id", "0");
    optionAll.html("Todas las provincias");
    select.append(optionAll);

    // Iterar sobre los nuevos items
    for (const item of items) {
        // Verificar si el ID de la provincia ya existe
        if (!existingIds[item.locality.province.id]) {
            // Crear y agregar la opción si el ID no existe
            var option = $("<option>")
            option.html(item.locality.province.name);
            option.attr("data-province_id", item.locality.province.id);
            select.append(option);

            // Marcar el ID de la provincia como existente
            existingIds[item.locality.province.id] = true;
        }
    }
}

function toggleItemsByProvinceId(arrProvince_id) {
    var ul = $("ul[name='select-items']");
    if (!arrProvince_id.includes(0)) {
        // Mostrar solo los elementos cuyos IDs coincidan con los recibidos
        ul.find("li").each(function() {
            var province_id = $(this).data('province');
            if (arrProvince_id.includes(province_id)) {
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


function uploadSrc() {
    // Obtener los items del servidor mediante una solicitud POST a la API
    $.ajax({
        type: 'GET',
        url: '/api/upload/create-route',
        contentType: 'application/json',
        success: function (response) {
            console.log(response)
            uploadItems(response.items)
            uploadGuides(response.guides)
            // uploadFilterLocality(response.itemsId)
            uploadFilterLocality(response.items)
            uploadFilterProvince(response.items)
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });
}
