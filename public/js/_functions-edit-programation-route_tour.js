function getIdsFromTable() {
    var ids = [];
    $('tbody.table-group-divider tr[data-id]').each(function() {
        var id = $(this).attr('data-id');
        ids.push(id);
    });
    return ids;
}

function takeProgramationDataUpdate() {
    var ids = getIdsFromTable();
    let programationUpdate = [];

    for (const id of ids) {
        programationUpdate.push(programation[id]);
    }

    return programationUpdate;
}

function takeFormDataUpdate() {
    var daterange = $('input[name="daterange"]').val().split(' / ');

    // Crear un nuevo objeto FormData
    var formData = new FormData();

    // Obtener el archivo de la entrada de imagen
    if ( $('div.input-images input[type="file"]').prop('files').length != 0) {
        var file = $('div.input-images input[type="file"]').prop('files')[0];
        formData.append('photo', file, file.name);
    } else {
        formData.append('photo', $("div[name='upload-image'] div.uploaded").find("img").attr("src"));
    }

    // Agregar los datos al formData
    formData.append('id', $('#id').val());
    formData.append('name', $('#name').val());
    var coordinates = {
        'x': $('input[name="x"]').val(),
        'y': $('input[name="y"]').val()
    };
    formData.append('coordinates', JSON.stringify(coordinates));
    formData.append('capacity', $('input[name="capacity"]').val());
    formData.append('datetime_start', daterange[0]);
    formData.append('datetime_end', daterange[1]);
    formData.append('description', $('#description').val());
    formData.append('programation', JSON.stringify(takeProgramationDataUpdate()));
    var selected_items = [];
    $('#sortable2 li').each(function() {
        selected_items.push($(this).data('id'));
    });
    formData.append('selected_items', JSON.stringify(selected_items));

    return formData;
}

function action_editRoute() {
    // Url to send the form data to API server
    const url = '/api/route/update';

    // Obtener datos del formulario
    var formData = takeFormDataUpdate();
    
    // Here you can send the form data to your server using AJAX
    $.ajax({
        type: 'POST',
        url: url,
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
}

function action_editRouteAndGenerateTours() {
    // Url to send the form data to API server
    const url = '/api/route/updateAndGenerateTours';

    // Obtener datos del formulario
    var formData = takeFormDataUpdate();
    
    // Here you can send the form data to your server using AJAX
    $.ajax({
        type: 'POST',
        url: url,
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

}

function changeBtnsToEdit() {
    var btnEdit = $("#btnSave");
    btnEdit.html("Editar");
    btnEdit.attr("id","btnEdit");
    btnEdit.off('click');
    btnEdit.on('click', action_editRoute);
    
    var btnEditAndTours = $("#btnSaveAndTours");
    btnEditAndTours.html("Editar y guardar tours");
    btnEditAndTours.attr("id","btnEditAndTours");
    btnEditAndTours.off('click');
    btnEditAndTours.on('click', action_editRouteAndGenerateTours);
}