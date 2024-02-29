function changeEvent (calendar, event_id, newTour) {
    // Realizar la solicitud AJAX
    $.ajax({
        url: '/api/tour/update/'+event_id,
        type: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(newTour),
        success: function(response) {
            console.log('Evento actualizado correctamente:', response);
            console.log(response);
    
            // Encontrar y eliminar el evento existente por su identificador
            var existingEvent = calendar.getEventById(event_id);
            if (existingEvent) {
                existingEvent.remove();
            } else {
                console.error('No se encontró el evento con el ID:', event_id);
            }

            newEvent = {
                groupId: response.id,
                title: response.route + ' - ' + response.id,
                start: response.datetime,
                img: "/uploads/images/"+response.guide.photo,
                id: response.id,
                guide_id: response.guide.id,
                guide_fullname: response.guide.fullname,
            };

            // Añadir el nuevo evento
            calendar.addEvent(newEvent);

        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar el evento:', error);
        }
    });
}