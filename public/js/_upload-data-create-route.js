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

function uploadFilterLocality(items) {
    // Obtener el contenido de la plantilla de filter locality
    var select = $("#filter_locality");
    for (const item of items) {
        var option = $("<option>")
        option.html(item.locality.name + ", " + item.locality.province.name);
        option.attr("data-locality_id", item.locality.id);
        option.attr("data-province_id", item.locality.province.id);
        select.append(option);
    }
    // TODO AQUÍ HE ME QUEDADO HOY 14/02/2024 a las 19:52
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
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });
}
