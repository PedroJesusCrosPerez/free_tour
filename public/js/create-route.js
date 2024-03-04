function takeProgramationData() {
    var time_start = $('#time').val();

    var pattern = [];
    var patternf = [];
    // Versión anterior (con radio buttons)
    // $('input[name="pattern[]"]:checked').each(function() {
    //     pattern.push($(this).val());
    //     patternf.push( format( $(this).val() ) );
    // });

    // Nueva versión con divs seleccionables
    $('div[name="container-daysOfWeek"] > div[class="dayOfWeek selected"]').each(function() {
        pattern.push( $(this).data('id') );
        patternf.push( $(this).html() );
    });

    patternf ? patternf : patternf = ['No hay días seleccionados'];
    patternf = patternf.length == 7 ? 'todos los días' : patternf;

    var period = $('input[name="period"]').val().split(' / ');
    var date_start = period[0];
    var date_end = period[1];
    // var daterange_picker = $('input[name="period"]').data('daterangepicker');
    // var date_start = daterange_picker.startDate._i;
    // var date_end = daterange_picker.endDate._i;
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
            'name': guide.find("p").find("span").html()
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

function takeFormData() {
    var daterange = $('input[name="daterange"]').val().split(' / ');
    // var daterange_picker = $('input[name="daterange"]').data('daterangepicker');
    // var daterange_startDate = daterange_picker.startDate._i;
    // var daterange_endDate = daterange_picker.endDate._i;

    // Crear un nuevo objeto FormData
    var formData = new FormData();

    // Obtener el archivo de la entrada de imagen
    var file = $('div.input-images input[type="file"]').prop('files')[0];

    // Agregar los datos al formData
    formData.append('name', $('#name').val());
    var coordinates = {
        'x': $('input[name="x"]').val(),
        'y': $('input[name="y"]').val()
    };
    formData.append('coordinates', JSON.stringify(coordinates));
    // formData.append('coordinates[x]', $('input[name="x"]').val());
    // formData.append('coordinates[y]', $('input[name="y"]').val());
    formData.append('capacity', $('input[name="capacity"]').val());
    formData.append('photo', file, file.name);
    formData.append('datetime_start', daterange[0]);
    formData.append('datetime_end', daterange[1]);
    // formData.append('datetime_start', daterange_startDate);
    // formData.append('datetime_end', daterange_endDate);
    formData.append('description', $('#description').val());
    formData.append('programation', JSON.stringify(programation));
    // Selected items
    // $('#sortable2 li').each(function() {
    //     formData.append('selected_items[]', $(this).data('id'));
    // });
    var selected_items = [];
    $('#sortable2 li').each(function() {
        selected_items.push($(this).data('id'));
    });
    formData.append('selected_items', JSON.stringify(selected_items));

    return formData;
}

function printTakeFormData() {
    var formData = takeFormData();
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]);
    }
}


function createRoute() {
    // Url to send the form data to API server
    const url = '/api/route/insert';

    // Obtener datos del formulario
    var formData = takeFormData();
    console.log("ENVIAR ==> ");
    console.log(formData);
    console.log(" a servidor http://localhost:8000/, uri: " + url);
    
    // Here you can send the form data to your server using AJAX
    $.ajax({
        type: 'POST',
        url: "/api/route/insert",
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

function createRouteAndGenerateTours() {
    // Url to send the form data to API server
    const url = '/api/route/insertAndGenerateTours';

    // Obtener datos del formulario
    var formData = takeFormData();
    
    // Here you can send the form data to your server using AJAX
    $.ajax({
        type: 'POST',
        url: "/api/route/insertAndGenerateTours",
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


    $.ajax({
        type: 'POST',
        url: "/api/route/insert",
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

    // Ejemplo de JSON de envío
    var formData2  = {
        "name": "titulo de prueba",
        // "photo": "blob:http://localhost:8000/a34e5762-6902-411c-b30c-cc258539f398",
        "photo": $('.input-images img').attr('src'),
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
        data: JSON.stringify(formData2),
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

function testingGenerateTour() {
    // var data = takeProgramationData();
    
    $.ajax({
        type: 'POST',
        url: "/api/tour/generate",
        data: JSON.stringify(programation),
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

function appendTopButtons() {
    $("#btnSave").appendTo("div.page-actions") // Primary btn
    $("#btnSaveAndTours").appendTo("div.page-actions") // Secondary btn
    $('#printdata').appendTo("div.page-actions").html("Depurar") // Debugging btn

    $("#btnSaveAndTours").on("click", function() {
        // Dispara el envío del formulario
        $("#create-route").submit();
    });
}



$(function () {
    // Iniciarlizar botones de @EasyAdmin
    appendTopButtons();

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
        // locale: {
        //     format: 'DD/MM/YYYY HH:mm'
        // },
        locale: {
            direction: 'ltr',
            format: 'DD/MM/YYYY HH:mm', // Formato de fecha para Madrid, España
            separator: ' / ',
            applyLabel: 'Aplicar', // Cambiado a español
            cancelLabel: 'Cancelar', // Cambiado a español
            weekLabel: 'Sem', // Abreviatura de "Semana" en español
            customRangeLabel: 'Rango personalizado', // Cambiado a español
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'], // Abreviaturas de los días de la semana en español
            monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], // Nombres de los meses en español
            firstDay: 1 // Lunes es el primer día de la semana en España
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

    programOnChangeSelectFilter("locality");
    programOnChangeSelectFilter("province");
    programAddProgramation(); // Botón que añade programaciones a la tabla
    // programCreateProgramation();
    selectDayOfWeek();

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
    $('#create').on('click', createRoute);
    $('#create_generate').on('click', createRouteAndGenerateTours);
    $('#btnSave').on('click', createRoute);
    $('#btnSaveAndTours').on('click', createRouteAndGenerateTours);
        // Print to debugg
    $('#printdata').on('click', function () { console.log(takeData()); /*Debugging*/ });
        // Testing
    $('#testtour').on('click', function () { console.log(testingGenerateTour()); /*Debugging*/ });
    $('#testryt').on('click', function () { console.log(testingCreateRouteAndGenerateTours()); /*Debugging*/ });
    

    // Upload src 'items' and 'Guides'
    uploadSrc();

});