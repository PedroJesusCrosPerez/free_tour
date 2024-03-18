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

function appendTopButtons() {
    $("#btnSave").appendTo("div.page-actions") // Primary btn
    $("#btnSaveAndTours").appendTo("div.page-actions") // Secondary btn
    $('#printdata').appendTo("div.page-actions").html("Depurar") // Debugging btn

    $("#btnSaveAndTours").on("click", function() {
        // Dispara el envío del formulario
        $("#create-route").submit();
    });
}

function inicializeTimepicker_Datepicker() {

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
        },
        // TODO AÑADIDO EN ÚLTIMA HORA
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
    }, 
        function(start, end, label) {
            // console.log("New date range selected: " + start.format('DD/MM/YYYY') + " to " + end.format('DD/MM/YYYY') + " (predefined range: " + label + ")");
            datetime_start = start.format('DD/MM/YYYY HH:mm');
            datetime_end = end.format('DD/MM/YYYY HH:mm');
        }
    );

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
}



$(function () {
    // Iniciarlizar botones de @EasyAdmin
    appendTopButtons();
    changeBtnsToEdit()

    // Añadir la clase 'active' al menú
    $(".fa-route").parent().parent().addClass("active");

    inicializeTimepicker_Datepicker();

    programAddProgramation(); // Botón que añade programaciones a la tabla
    selectDayOfWeek();
    

    // Create button
    // $('#create').on('click', createRoute);
    // $('#create_generate').on('click', createRouteAndGenerateTours);
    $('#btnSave').on('click', createRoute);
    // $('#btnSaveAndTours').on('click', createRouteAndGenerateTours);

    // Upload src 'items' and 'Guides'
    uploadSrc();

});