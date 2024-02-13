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

function submit() {
    // Url to send the form data to API server
    const url = '/api/route/insert';

    // Obtener datos del formulario
    var formData = takeData();
    console.log("ENVIAR ==> ");
    console.log(formData);
    console.log(" a servidor http://localhost:8000/, uri: " + url);
    
    // Here you can send the form data to your server using AJAX
    $.ajax({
        type: 'POST',
        url: url,
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

}

function testing() {
    var formData = new FormData();
    formData.append('name', 'titulo de prueba');
    // formData.append('photo', 'blob:http://localhost:8000/a34e5762-6902-411c-b30c-cc258539f398');
    formData.append('photo', $('.input-images img').attr('src'));
    formData.append('coordinates', JSON.stringify({
        'x': '51.508503734827706',
        'y': '-0.060819089412689216'
    }));
    formData.append('capacity', '6');
    formData.append('datetime_start', '20/02/2024 00:00');
    formData.append('datetime_end', '22/02/2024 23:59');
    formData.append('description', '<div>esto es una prueba de descripción con <b>negrita </b>y <i>cursiva</i>, fin.</div>');
    formData.append('programation', JSON.stringify([
        {
            'time_start': '23:00',
            'pattern': ['1', '3', '5'],
            'patternf': ['Lunes', 'Miércoles', 'Viernes'],
            'date_start': '24/02/2024',
            'date_end': '26/02/2024',
            'guide': {
                'id': 13,
                'name': '<span class="fullname">María Ramírez Castillo</span>'
            }
        }
    ]));
    formData.append('selected_items', JSON.stringify([1, 2]));

    // Ejemplo de JSON de envío
    var formData2  = {
        "name": "titulo de prueba",
        "photo": "blob:http://localhost:8000/a34e5762-6902-411c-b30c-cc258539f398",
        "coordinates": {
            "x": "51.508503734827706",
            "y": "-0.060819089412689216"
        },
        "capacity": "6",
        "datetime_start": "20/02/2024 00:00",
        "datetime_end": "22/02/2024 23:59",
        "description": "<div>esto es una prueba de descripción con <b>negrita </b>y <i>cursiva</i>, fin.</div>",
        "programation": [
            {
                "time_start": "23:00",
                "pattern": [
                    "1",
                    "3",
                    "5"
                ],
                "patternf": [
                    "Lunes",
                    "Miércoles",
                    "Viernes"
                ],
                "date_start": "24/02/2024",
                "date_end": "26/02/2024",
                "guide": {
                    "id": 13,
                    "name": "<span class=\"fullname\">María Ramírez Castillo</span>"
                }
            }
        ],
        "selected_items": [
            1,
            2
        ]
    }

    // Here you can send the form data to your server using AJAX
    $.ajax({
        type: 'POST',
        url: "/api/route/insert",
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
}

$(function () {
    // Añadir la clase 'active' al menú
    $(".fa-route").parent().parent().addClass("active");

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
            format: 'DD/MM/YYYY HH:mm'
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

    // Submit form
    $('#create-route').on('submit', function (event) {
        // Prevent default form submission
        event.preventDefault();

        // console.log(formData);

        // // Debugging: Output form data to console
        // localStorage.setItem('formData', JSON.stringify(formData));
        // console.log(formData);
        // var formData = localStorage.getItem('formData');
    });

    // Create button
    $('#create').on('click', submit);
    $('#printdata').on('click', function () { console.log(takeData()); /*Debugging*/ });


    // Upload src 'items' and 'Guides'
    uploadSrc();

});