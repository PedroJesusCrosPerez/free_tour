function uploadItems() {
    
}

function uploadGuides() {
    
}

function uploadSrc() {
    // Obtener los items del servidor mediante una solicitud POST a la API
    $.ajax({
        type: 'GET',
        url: '/api/upload/create-route',
        contentType: 'application/json',
        success: function (response) {
            // Obtener el contenido de la plantilla de item
            $.get("/templates/item.html", function (text) {
                var contenedor = $("<div>").html(text);
                var item = contenedor.find(":first-child");

                console.log(item)
                console.log(item.find(""))

                for (const itemData of response.items) {
                    var itemAux = item.clone();
                    itemAux.find(".item-name").html(itemData.name)
                    // itemAux[0].data("id", itemData.id) // TODO data en li data-id="6"
                    // itemAux[0].data("hola","holavalor")
                    // console.log(itemAux.find("li"))
                    $("#sortable1").append(itemAux)
                }
            });
            $("#sortable1").find(">span").remove() // TODO es un pequeño parche ya que no se por qué se añaden automáticamente un <span> dentro del <ul>

            
            // Obtener el contenido de la plantilla de guide
            $.get("/templates/guide.html", function (text) {
                var contenedor = $("<div>").html(text);
                var guide = contenedor.find(":first-child");
                
                for (const guideData of response.guides) {
                    var guideAux = guide.clone();
                    guideAux.find(".fullname").html(guideData.fullname)
                    guideAux.find("img").attr("src", "/uploads/images/" + guideData.photo);
                    $("#guide-list").append(guideAux)
                }
            });
            // TODO es un pequeño parche ya que no se por qué se añaden automáticamente un <span> dentro del <ul>
            // $("#guide-list").find(">img").remove()
            // $("#guide-list").find(">span").remove()
            // console.log($("#guide-list").find("img"))
            // console.log($("#guide-list").find("span"))
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });
}

$(function () {
    uploadSrc();
    $("#guide-list").find(">img").remove()
    $("#guide-list").find(">span").remove()

})