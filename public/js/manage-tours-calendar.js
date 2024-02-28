function inicializeDialog() {
    var modal = $('#modal');
    var eventTitle = $('#eventTitle');
    var eventDescription = $('#eventDescription');
    var closeBtn = $('.close');

    closeBtn.click(function () {
        modal.css({
            'display': 'none',
            'width': 'auto',
        });
    });

    $(window).click(function (event) {
        if (event.target == modal[0]) {
            modal.css('display', 'none');
        }
    });
}

$(function() {
    const reponseData = null;

    let urlGuides = "/api/user/findAll/ASSOCIATIVE?role=ROLE_GUIDE";
    $.ajax({
        type: 'GET',
        url: urlGuides,
        contentType: 'application/json',
        success: function (response) {
            console.log(response)
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });


    // Obtener datos del servidor. Los tours que tiene el guía asignados
    const url = "/api/tour/findAll";
    $.ajax({
        type: 'GET',
        url: url,
        contentType: 'application/json',
        success: function (response) {
            console.log(response)
            
            let arrEvents = [];
            arrEvents.push({
                groupId: 15,
                title: "TITULO DE PRUEBA SI",
                start: '2024-02-07',
            });

            for (const tour of response) {
                arrEvents.push({
                    groupId: tour.id,
                    title: tour.route + ' - ' + tour.id,
                    start: tour.datetime,
                    // end: event.end,
                });
                
                var calendarEl = document.getElementById('calendar');
                // 
                var modal = $('#modal');
                var eventTitle = $('#eventTitle');
                var eventDescription = $('#eventDescription');
                var closeBtn = $('.close');
                // 
                var body = $('body');

                var today = new Date();
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    // initialDate: '2023-01-12',
                    initialDate: today,
                    initialView: 'timeGridWeek',
                    nowIndicator: true,
                    headerToolbar: {
                    left: 'prev,next today',
            
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    navLinks: true, // can click day/week names to navigate views
                    editable: false, // no draggable events
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true, // allow "more" link when too many events
                    // events: [
                    // {
                    //     groupId: 1,
                    //     title: 'default title',
                    //     start: '2024-03-05 17:00',
                    // },
                    // // {
                    // //     groupId: 1,
                    // //     title: 'Long Event',
                    // //     start: '2023-01-07',
                    // //     end: '2023-01-10'
                    // // },
                    // // {
                    // //     groupId: 2,
                    // //     title: 'Repeating Event',
                    // //     start: '2023-01-09T16:00:00'
                    // // },
                    // // {
                    // //     title: 'Conference',
                    // //     start: '2023-01-11',
                    // //     end: '2023-01-13'
                    // // },
                    // ],
                    events: arrEvents,
                    locale: 'es',

                        // modal
                    eventClick: function (info) {
                        eventTitle.text(info.event.title);
                        eventDescription.text('Información del evento ' + info.event.title);
                        modal.dialog({
                            autoOpen: true, // Abre el diálogo automáticamente al inicializar
                            modal: true, // Hace que el diálogo sea modal
                            zIndex: 1000, // Establece el índice z
                            position: { my: "center", at: "center", of: window }, // Centra el diálogo
                            draggable: false, // No permite arrastrar el diálogo
                            resizable: false, // No permite cambiar el tamaño del diálogo
                            dialogClass: 'custom-dialog', // Clase personalizada para aplicar estilos adicionales
                            width: 'auto', // Ancho automático
                            height: 'auto', // Altura automática
                            show: { effect: "fade", duration: 300 }, // Efecto de aparición
                            hide: { effect: "fade", duration: 300 } // Efecto de desaparición
                        });
                        body.css('overflow', 'hidden');
                        // $('button.ui-dialog-titlebar-close').append('<i class="fa-solid fa-rectangle-xmark"></i>');
                    },
                });

                // closeBtn.click(function () {
                //     modal.css({
                //         'display': 'none',
                //         'width': 'auto',
                //     });
                //     body.css('overflow', 'none');
                // });
    
                $(window).click(function (event) {
                    if (event.target == modal[0]) {
                        modal.css('display', 'none');
                        body.css('overflow', 'none');
                    }
                });
            
                calendar.render();
            }
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });



});