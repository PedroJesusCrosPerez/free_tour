// #######################################
// INFORMATION
// #######################################
function uploadDateRange(response) {
    var today = new Date();
    // Initialize date range picker
    $('input[name="daterange"]').daterangepicker({
        "showWeekNumbers": false,
        "showISOWeekNumbers": false,
        "showCustomRangeLabel": false,
        "minDate": today,
        "opens": "left",
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
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        startDate: response.datetime_start,
        endDate: response.datetime_end
    }, 
        function(start, end, label) {
            // console.log("New date range selected: " + start.format('DD/MM/YYYY') + " to " + end.format('DD/MM/YYYY') + " (predefined range: " + label + ")");
            datetime_start = start.format('DD/MM/YYYY HH:mm');
            datetime_end = end.format('DD/MM/YYYY HH:mm');
        }
    );
}

function uploadImage(img) {
    $("div[name='upload-image'] div.upload-text").css("display", "none")//.css("visibility", "hidden");
    $("div[name='upload-image'] div.uploaded").html(`<div class="uploaded-image" data-index="0"><img src="${img}"><button class="delete-image"><i class="iui-close"></i></button></div>`);
}

function edit_uploadInformation(response) {
    $("#name").val(response.name);
    uploadMap(JSON.parse(response.coordinates));
    uploadDateRange(response);
    $("#capacity").val(response.capacity);
    $("div.richText-editor").html(response.description);
    uploadImage(response.photo);
}

// #######################################
// ITEMS
// #######################################
function uploadRoute_items(items) {
    for (const item of items) {
        $(`#sortable1 li[data-id='${item}']`).appendTo("#sortable2");
    }
}
function edit_uploadItems(response) {
    uploadRoute_items(response.items);
}


// #######################################
// PROGRAMATION
// #######################################
// function uploadPeriod(response) {
//     var today = new Date();
//     // Initialize date range picker
//     $('#period').daterangepicker({
//         "showWeekNumbers": false,
//         "showISOWeekNumbers": false,
//         "showCustomRangeLabel": false,
//         "minDate": today,
//         "opens": "left",
//         locale: {
//             direction: 'ltr',
//             format: 'DD/MM/YYYY HH:mm', // Formato de fecha para Madrid, España
//             separator: ' / ',
//             applyLabel: 'Aplicar', // Cambiado a español
//             cancelLabel: 'Cancelar', // Cambiado a español
//             weekLabel: 'Sem', // Abreviatura de "Semana" en español
//             customRangeLabel: 'Rango personalizado', // Cambiado a español
//             daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'], // Abreviaturas de los días de la semana en español
//             monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], // Nombres de los meses en español
//             firstDay: 1 // Lunes es el primer día de la semana en España
//         },
//         startDate: response.datetime_start,
//         endDate: response.datetime_end
//     });
// }
// function uploadProgramation(response) {
//     uploadPeriod(response.items);
// }



// $(function () {
function uploadRouteData() {
    let id = $('input[name="id"]').val()
    // Obtener la ruta del servidor mediante una solicitud POST a la API
    $.ajax({
        type: 'GET',
        url: '/api/route/'+id,
        contentType: 'application/json',
        success: function (response) {
            console.log(response);

            edit_uploadInformation(response);
            edit_uploadItems(response);
            // uploadProgramation(response);

            changeBtnsToEdit();
            
            // Verificamos si la función existe después de un retraso de 5 segundos
            setTimeout(function() {
                if (typeof ayuda === "function") {
                    ayuda();
                    console.log("ayuda() ¡¡Se ha ejecutado con éxito!!");
                } else {
                    console.log("¡¡Recueda que estás creando ruta!!");
                }
            }, 4000); // 5000 milisegundos = 5 segundos
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });
}
// })


function ayuda() {
    let id = $('input[name="id"]').val()
    // Obtener la ruta del servidor mediante una solicitud POST a la API
    $.ajax({
        type: 'GET',
        url: '/api/route/'+id,
        contentType: 'application/json',
        success: function (response) {
            console.log(response);

            for (const item of response.items) {
                $(`#sortable1 li[data-id='${item}']`).appendTo("#sortable2");
            }            
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });
}




// function updateRouteById() {
    
// }