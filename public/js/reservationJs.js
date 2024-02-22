$(document).ready(function () {
    let id = $('#route_id').val();

    $.ajax({
        type: 'GET',
        url: '/api/reservation/getFormData?id='+id,
        contentType: 'application/json',
        success: function (response) {
            // console.log(response)
            var tours = response.tours;

            // Array de fechas permitidas en el formato 'yyyy-mm-dd'
            // var fechasPermitidas = ['2024-02-01', '2024-02-05', '2024-02-10', '2024-02-15'];
            var fechasPermitidas = [];
            for (const tour of tours) {
                fechasPermitidas.push(tour.date);
            }

            // Función para verificar si una fecha está permitida
            function fechaPermitida(date) {
                var formattedDate = $.datepicker.formatDate('yy-mm-dd', date);
                if (fechasPermitidas.indexOf(formattedDate) != -1) {
                    return [true, 'validDate', '']; // el último parámetro es para introducir un title
                }
                return [false, '', ''];
            }

            $(".datepicker").datepicker({
                prevText: '<i class="fa fa-fw fa-angle-left"></i>',
                nextText: '<i class="fa fa-fw fa-angle-right"></i>',
                onSelect: function(dateText, inst) {
                    // `dateText` contiene la fecha seleccionada en formato de texto
                    console.log('Fecha seleccionada:', dateText);
                    console.log( $(this) );
                    console.log( inst );

                    // var td = $('td[data-handler="selectDay"][data-event="click"][data-month="1"][data-year="2024"]');
                    // $(".ui-datepicker-current-day")

                    // Resetear select
                    resetSelect();

                    var date = dateText.slice(0, -4);
                    var dateParts = date.split("/");
                    var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];

                    toggleToursByTime(formattedDate);
                },

                // Configuración para idioma español
                dateFormat: 'dd/mm/yyyy', // Formato de fecha día/mes/año
                closeText: 'Cerrar',
                // prevText: '&#x3C;Ant',
                // nextText: 'Sig&#x3E;',
                prevText: '<--',
                nextText: '-->',
                currentText: 'Hoy',
                monthNames: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                    'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                ],
                monthNamesShort: ['ene', 'feb', 'mar', 'abr', 'may', 'jun',
                    'jul', 'ago', 'sep', 'oct', 'nov', 'dic'
                ],
                dayNames: ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
                dayNamesShort: ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'],
                dayNamesMin: ['D', 'L', 'M', 'X', 'J', 'V', 'S'],
                weekHeader: 'Sm',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: '',
                beforeShowDay: fechaPermitida,
            });
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });

});