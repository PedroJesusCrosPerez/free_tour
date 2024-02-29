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

function selectGuide() {
    // Select guide
    $('.guide').on('click', function () {
        var someselected = $('li[class="guide selected"]');
        if ( someselected ) {
            someselected.removeClass('selected');
            $(this).toggleClass('selected');
        } else {
            $(this).toggleClass('selected');
        }
    })
}

function uploadGuides(events) {
    // Obtener el contenido de la plantilla de guide
    $.get("/templates/guide.html", function (text) {
        var contenedor = $("<div>").html(text);
        var guide = contenedor.find(">:first-child");
        
        $.each(events, function(index, eventData) {
            var guideAux = guide.clone();

            guideAux.attr("data-id", index);
            guideAux.find(".fullname").html(eventData.fullname);
            guideAux.find("img").attr("src", "/uploads/images/" + eventData.photo);
            $("#guide-list").append(guideAux);
        });
        
        selectGuide();
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
            var listGuides = $('#list-guides');
            uploadGuides(response);
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
                end: '2024-02-10'
            });

            for (const tour of response) {
                arrEvents.push({
                    groupId: tour.id,
                    title: tour.route + ' - ' + tour.id,
                    start: tour.datetime,
                    // end: event.end,
                    img: "/uploads/images/"+tour.guide.photo,
                    id: tour.id,
                    guide_id: tour.guide.id,
                    guide_fullname: tour.guide.fullname,
                    guide_name: tour.guide.name,
                });
                console.log(tour.id +" => "+ tour.guide.fullname);
                
                var calendarEl = document.getElementById('calendar');
                // 
                var modal = $('#modal');
                var eventTitle = $('#eventTitle');
                var eventDescription = $('#eventDescription');
                var closeBtn = $('.close');
                // 
                var body = $('body');
                // var dGuides = $('div[name="list-guides"]');
                // var listGuides = $('#list-guides');

                // $('#eventDescription').data('guide_id', tour.guide.id);
                // $('#eventDescription').data('tour_id', tour.id);
                $('#eventDescription').attr('data-guide_id', tour.guide.id);
                $('#eventDescription').attr('data-tour_id', tour.id);


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
                        // events
                    events: arrEvents,
                    locale: 'es',

                    eventContent: function(arg) {
                        // console.log(arg.event.extendedProps);
                        var imgSrc = arg.event.extendedProps.img; // Obtener la URL de la imagen desde las propiedades extendidas del evento
                        var content = document.createElement('div');
                        content.classList.add('fc-event-content');
                
                        if (imgSrc) {
                            var img = document.createElement('img');
                            img.src = imgSrc;
                            img.classList.add('event-img');
                            content.appendChild(img);
                        }

                        var guideContainer = document.createElement('div');
                        guideContainer.classList.add('guide-event');
                        guideContainer.classList.add(arg.event.extendedProps.guide_id);

                        var guideFullname = document.createElement('p');
                        guideFullname.classList.add('guide-fullname');
                        guideFullname.innerHTML = arg.event.extendedProps.guide_fullname;
                        
                        guideContainer.appendChild(guideFullname);
                        content.appendChild(guideContainer);
                        $("div.guide-event"+arg.event.extendedProps.guide_id).attr("data-guide_id", arg.event.extendedProps.guide_id);
                
                        var title = document.createElement('div');
                        title.classList.add('fc-event-title');
                        title.innerText = arg.event.title;
                        content.appendChild(title);
                
                        return { domNodes: [content] };
                    },
                        // modal
                    eventClick: function (info) {
                        // console.log(info.event.extendedProps);
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
                            width: '50%', // Ancho automático
                            height: 'auto', // Altura automática
                            show: { effect: "fade", duration: 300 }, // Efecto de aparición
                            hide: { effect: "fade", duration: 300 }, // Efecto de desaparición
                            close: function () {
                                // modal.css({
                                //     'display': 'none',
                                //     'width': 'auto',
                                // });
                                body.css('overflow-y', 'auto');
                            },
                            buttons: {
                                Aceptar: function () {
                                    // modal.css({
                                    //     'display': 'none',
                                    //     'width': 'auto',
                                    // });
                                    body.css('overflow-y', 'auto');
                                    var currentGuide_id = info.event.extendedProps.guide_id;
                                    var futureGuide_id = $("li.guide.selected").data('id');
                                    console.log("Cambiar ID: " + currentGuide_id + ", por el guia con ID: " + futureGuide_id);
                                    
                                    info.event.extendedProps.guide_id = futureGuide_id;
                                    console.log($(this))
                                    console.log($(this).parent())

                                    var newTour = { guide_id: futureGuide_id };
                                    changeEvent(calendar, info.event.id, newTour);

                                    $(this).dialog("close");

                                    // Mostrar el snackbar
                                    var snackbar = $("<div class='snackbar'>¡¡Se ha actualizado el nuevo guia con éxito!!</div>");
                                    $("body").append(snackbar);
                                    setTimeout(function(){
                                        snackbar.remove();
                                    }, 3000); // Elimina el snackbar después de 3 segundos
                                },
                            },
                        });
                        body.css('overflow-y', 'hidden');
                        // $('button.ui-dialog-titlebar-close').append('<i class="fa-solid fa-rectangle-xmark"></i>');
                        
                        // Reset guide selected
                        $("li.guide").removeClass("selected");

                        // var currentGuide_id = $("#eventDescription").data('guide_id');
                        var currentGuide_id = info.event.extendedProps.guide_id;
                        $("li.guide[data-id='"+currentGuide_id+"']").addClass("selected");
                    },
                });

                // closeBtn.click(function () {
                //     modal.css({
                //         'display': 'none',
                //         'width': 'auto',
                //     });
                //     body.css('overflow-y', 'auto');
                // });
    
                $(window).click(function (event) {
                    if (event.target == modal[0]) {
                        modal.css('display', 'none');
                        body.css('overflow-y', 'auto');
                    }
                });
            
                calendar.render();
            }

            var img = $("<img>").attr("src", "/uploads/images/32eaf4db-ca83-4709-a956-7de37b72778a.jpg");
            $('div.fc-daygrid-day-events > div.fc-daygrid-event-harness > a.fc-event.fc-event-start').append(img);

            console.log(arrEvents);
        },
        error: function (error) {
            console.error('Error:', error);
            console.log('Error:', error.responseText);
        }
    });



});