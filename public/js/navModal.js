$(function () {
    
    $(".form").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Salir": function() {
                $(this).dialog("close");
            }
        }
    });

    // Manejar el clic del botón
    $(".loginmodalbutton").on("click", function() {
        $(".form").dialog("open");
    });
    
})