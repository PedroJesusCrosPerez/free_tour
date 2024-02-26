$(function () {

    $("#modal").dialog({
        autoOpen: false, // No abrir automáticamente
        modal: true, // Hacer modal
        buttons: { // Botones
            "Cerrar": function() {
                $(this).dialog("close"); // Cerrar el diálogo al hacer clic en "Cerrar"
            }
        }
    });

    // // Manejador de clic para abrir el diálogo
    // $(".openModalRating").click(function() {
    //     $("#modal").dialog("open"); // Abrir el diálogo al hacer clic en el botón
    // });

    $("#rate").on("click", function(ev) {
        ev.preventDefault();

        console.log("Rate clicked");
        console.log($("input[name='tour_rating']:checked").val());
        console.log($("input[name='guide_rating']:checked").val());

        let formData = new FormData();
        formData.append('tour_id', $("#tour_id").val());
        formData.append('tour_rating', $("input[name='tour_rating']:checked").val());

        // Here you can send the form data to your server using AJAX
        $.ajax({
            type: 'POST',
            url: "/api/rating/insert",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Server response:', response);
            },
            error: function(error) {
                console.error('Error:', error);
                console.log('Error:', error.responseText);
            }
        });
    });
    
})