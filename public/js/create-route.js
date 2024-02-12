function takeProgramationData() {
    var time_start = $('#time').val();
    // var pattern = $('input[name="pattern"]').val();
    var pattern = [];
    var patternf = [];
    $('input[name="pattern[]"]:checked').each(function() {
        pattern.push($(this).val());
        patternf.push( format( $(this).val() ) );
    });
    patternf ? patternf : patternf = ['No hay días seleccionados'];
    patternf = patternf.length == 7 ? 'todos los días' : patternf;

    var period = $('input[name="period"]').val().split(' - ');
    var date_start = period[0];
    var date_end = period[1];
    var guide = $('li[class="guide selected"]');

    // Get data
    var data = {
        'time_start': time_start,
        'pattern': pattern,
        'patternf': patternf,
        'date_start': date_start,
        'date_end': date_end,
        'guide': {
            'id': guide.data('id'),
            'name': guide.find("p").html()
        }
    };

    return data;
}


function takeData() {
    var daterange = $('input[name="daterange"]').val().split(' - ');
    // Get form data
    var formData = {
        'name': $('#name').val(),
        'photo': $('.input-images img').attr('src'),
        'coordinates': {
            'x': $('input[name="x"]').val(),
            'y': $('input[name="y"]').val()
        },
        'capacity': $('input[name="capacity"]').val(),
        // 'datetime_start': datetime_start,
        // 'datetime_end': datetime_end,
        'datetime_start': daterange[0],
        'datetime_end': daterange[1],
        //'description': sceditor.instance(textarea).val(),
        'description': $('#description').val(),
        'programation': programation,
        'selected_items': $('#sortable2 li').map(function () {
            return $(this).data('id');
        }).get()
    };

    return formData;
}

$(function () {
    // Get today's date
    var today = new Date();
    var datetime_start, datetime_end;

    // Initialize date range picker
    $('input[name="daterange"]').daterangepicker({
        "showWeekNumbers": false,
        "showISOWeekNumbers": false,
        "showCustomRangeLabel": false,
        "minDate": today,
        "opens": "left",
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function(start, end, label) {
        // console.log("New date range selected: " + start.format('DD/MM/YYYY') + " to " + end.format('DD/MM/YYYY') + " (predefined range: " + label + ")");
        datetime_start = start.format('DD/MM/YYYY HH:mm');
        datetime_end = end.format('DD/MM/YYYY HH:mm');
    });

    // Initialize timepicker
    $('input[name="timepicker"]').timepicker({
        'timeFormat': 'H:i',
        'scrollDefault': 'now',
        'forceRoundTime': true,
        'step': 15,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });



    programAddProgramation(); // Botón que añade programaciones a la tabla
    // programCreateProgramation();

    // CREATE BUTTON
    $('button[name="create"]').on('click', function() {
        // takeProgramationData();
        var data = takeData();
        console.log(data);
    });

    // SAVE BUTTON
    $('#create-route').on('submit', function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Obtener datos del formulario
        var formData = takeData();
        console.log("ENVIAR ==> ");
        console.log(formData);
        console.log(" a servidor http://localhost:8000/api/route/insert");
        // console.log(formData);

        // // Debugging: Output form data to console
        // localStorage.setItem('formData', JSON.stringify(formData));
        // console.log(formData);
        // var formData = localStorage.getItem('formData');

        // Here you can send the form data to your server using AJAX
        const url = '/api/route/insert';
        $.ajax({
            type: 'POST',
            url: '/api/route/insert',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {
                console.log('Server response:', response);
            },
            error: function(error) {
                console.error('Error:', error);
                console.log('Error:', error.responseText);
            }
        });
    });

});