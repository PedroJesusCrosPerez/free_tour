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

    $(".rate").on("click", function(ev) {
        ev.preventDefault();
        let btnThis = $(this);

        let reservation_id = $(this).data("id");
        let tour_rating = $(`input[name='${reservation_id}tour_rating']:checked`).val();
        let guide_rating = $(`input[name='${reservation_id}guide_rating']:checked`).val();
        let comments = $("textarea[name='comments']").val();

        console.log("Rate clicked");
        console.log(" - Reservation_id: " + reservation_id);
        console.log(" - Route_rating: " +   tour_rating);
        console.log(" - Guide_rating: " +   guide_rating);
        console.log(" - Comments: " +       comments);

        let formData = new FormData();
        formData.append( 'reservation_id',  reservation_id  );
        formData.append( 'tour_rating',     tour_rating     );
        formData.append( 'guide_rating',    guide_rating    );
        formData.append( 'comments',        comments        );

        // Here you can send the form data to your server using AJAX
        $.ajax({
            type: 'POST',
            url: "/api/rating/insert",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Server response:', response);
                if (
                    response.success && 
                    ($.isNumeric(response.id) && parseInt(response.id) == response.id)
                    ) {
                    btnThis.parent().parent().remove();
                    // TODO: mostar mensaje de valoración realizada
                } else {
                    // TODO: Mostrar mensaje de error en valorarción
                    console.log('ERROR EN VALORAR ');
                }
            },
            error: function(error) {
                console.error('Error:', error);
                console.log('Error:', error.responseText);
            }
        });
    });
    
})