document.addEventListener('DOMContentLoaded', function() {
    const reponseData = null;

    // Obtener datos del servidor. Los tours que tiene el guía asignados
    const url = "/api/user/getTours";
    $.ajax({
        type: 'GET',
        url: url,
        contentType: 'application/json',
        success: function (response) {
            console.log(response)
            
            let arrEvents = [];

            for (const event of response) {
                arrEvents.push({
                    groupId: event.id,
                    title: event.route.name,
                    start: event.datetime,
                    // end: event.end,
                });
                
                var calendarEl = document.getElementById('calendar');

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
                    editable: true,
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