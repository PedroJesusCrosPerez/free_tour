function checkInformation_Description() {
    var input = $('.richText-editor');
    var lblError = $('#description-error');
    var is_description = (input.html() == "<div><br></div>" || input.html() == "<br>" || input.html() == "<br><div><br></div>" || input.html() == "<div><br></div><div><br></div>") ? false : true;
    if (is_description) {
        input.removeClass("inputBig-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("inputBig-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log(input.html() + " ==> " + is_description); // Debugging

    return is_description;
}

function checkInformation_FileUploader() {
    var input = $('div.upload-text');
    var lblError = $('#upload-image-error');
    var is_file = ($('div.uploaded-image').length == 1) ? true : false;
    if (is_file) {
        input.removeClass("inputBig-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("inputBig-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log(" ==> " + is_file); // Debugging

    return is_file;
}

function checkInformation_Map() {
    var input = $('i#openModalBtn');
    var lblError = $('#btnOpenMap-error');
    var is_map = ($('input[name="x"]').val() != "" || $('input[name="y"]').val() != "") ? true : false;
    if (is_map) {
        input.removeClass("inputBig-error");
        lblError.removeClass("input-error");
        // Green good feedback
        lblError.css("display", "block");
        lblError.addClass("input-true");
        lblError.html("Ubicación seleccionada");
        lblError.append(" <i class='fa-solid fa-circle-check'></i>");
    } else {
        input.addClass("inputBig-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log($('input[name="x"]').val() + " & " + $('input[name="x"]').val() + " ==> " + is_map); // Debugging

    return is_map;
}

function checkInformation_Name() {
    var input = $('input#name');
    var lblError = $('#name-error');
    var is_name = ($('input#name').val() != "") ? true : false;
    if (is_name) {
        input.removeClass("input-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("input-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log($('input[name="x"]').val() + " & " + $('input[name="x"]').val()" ==> " + is_map); // Debugging

    return is_name;
}


function testDes() {
    $('.richText-editor').on('keyup', function() {
        checkInformation_Description();
    });
}

function checkInformation() {
    // console.log("SE EJECUTÓ")
    // var isname = $('#name').valid();
    var isname = checkInformation_Name();
    var ismap = checkInformation_Map();
    var isdescription = checkInformation_Description();
    var isdaterange = $('#daterange').valid();
    var iscapacity = $('#capacity').valid();
    var isfile = checkInformation_FileUploader();
    
    return isname && ismap && isdescription && isdaterange && iscapacity && isfile;
}

function inicialize_OnChange_checkers_Information() {
    // Name
    $('input#name').on('keyup', function() {
        checkInformation_Name();
    });
    // Description
    $('.richText-editor').on('keyup', function() {
        checkInformation_Description();
    });

    //  Drag & Drop Image Uploader
    $('input[name="images[]"]').on('change', function() {
        // Obtiene la lista de archivos seleccionados
        var files = $(this)[0].files;

        if (files.length > 0) {
            // console.log("Se seleccionó un archivo."); // Debugging
            checkInformation_FileUploader();

            $('button.delete-image').on('click', function() {
                // console.log("Se borraron todos los archivos."); // Debugging
                checkInformation_FileUploader();
            });
        } else {
            // console.log("Se borraron todos los archivos."); // Debugging
        }
    });

}


// ITEMS
function check_isItemsSelected() {
    var input = $('#sortable2');
    var lblError = $('#sortable2-error');
    var is_itemsSelected = ($('#sortable2').children().length > 0) ? true : false;
    if (is_itemsSelected) {
        input.removeClass("inputBig-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("inputBig-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    console.log(" ==> " + is_itemsSelected); // Debugging

    return is_itemsSelected;
}

function checkItems() {
    var isitemSelected = check_isItemsSelected();
    
    return isitemSelected;
}

function inicialize_OnChange_checkers_Items() {
    // Items selected
    var sortableList = $('#sortable2');

    // Captura la adición de elementos li
    sortableList.on('DOMNodeInserted', 'li', function(event) {
        // console.log('Se añadió un li:', event.target);
        check_isItemsSelected();
    });

    // Captura la eliminación de elementos li
    sortableList.on('DOMNodeRemoved', 'li', function(event) {
        // console.log('Se eliminó un li:', event.target);
        check_isItemsSelected();
    });

}



// Programation

// Función para validar el rango de fechas del segundo input
function checkProgramation_daterange() {
    var daterange1 = $('#daterange');
    var daterange2 = $('#period');
    var input = $('#period');
    var lblError = $('#period-error');

    // Obtener el rango de fechas seleccionado en el primer input
    const startDate1 = daterange1.data('daterangepicker').startDate;
    const endDate1 = daterange1.data('daterangepicker').endDate;
    const start1 = new Date(startDate1);
    const end1 = new Date(endDate1);

    // Obtener el rango de fechas seleccionado en el segundo input
    const startDate2 = daterange2.data('daterangepicker').startDate;
    const endDate2 = daterange2.data('daterangepicker').endDate;
    const start2 = new Date(startDate2);
    const end2 = new Date(endDate2);

    // Validar si el rango de fechas del segundo input está dentro del rango del primer input
    if (!(start2 >= start1 && end2 <= end1)) {
        console.log('Error: El rango de fechas del segundo input no está dentro del rango del primer input.');
        input.addClass("input-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    } else {
        console.log('Correcto: El rango de fechas del segundo input está dentro del rango del primer input.');
        input.removeClass("input-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    }
}
// Time picker - time start
function checkProgramation_Time() {
    var input = $('input#time');
    var lblError = $('#time-error');
    var is_name = ($('input#name').val() != "") ? true : false;
    if (is_name) {
        input.removeClass("input-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("input-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log($('input[name="x"]').val() + " & " + $('input[name="x"]').val()" ==> " + is_map); // Debugging

    return is_name;
}
// Pattern - Days of week
function checkProgramation_DaysOfWeek() {
    var input = $("div[name='container-daysOfWeek']");
    var lblError = $('#pattern-error');
    var is_daysofweek = $("div[name='container-daysOfWeek']").children().hasClass('selected');
    if (is_daysofweek) {
        input.removeClass("inputBig-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("inputBig-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log(is_daysofweek); // Debugging

    return is_daysofweek;
}
// Select Guide
function checkProgramation_SelectGuide() {
    var input = $("#guide-list");
    var lblError = $('#slctguide-error');
    var is_daysofweek = $("#guide-list").children().hasClass('selected');
    if (is_daysofweek) {
        input.removeClass("inputBig-error");
        lblError.removeClass("input-error");
        lblError.css("display", "none");
    } else {
        input.addClass("inputBig-error");
        lblError.addClass("input-error");
        lblError.css("display", "block");
    }
    // console.log(is_daysofweek); // Debugging

    return is_daysofweek;
}

function checkProgramation() {
    var istimestart = checkProgramation_Time();
    var ispattern = checkProgramation_DaysOfWeek();
    var isdaterange = checkProgramation_daterange();
    var isscltguide = checkProgramation_SelectGuide();
    
    return istimestart && ispattern && isdaterange && isscltguide;
}


function inicialize_OnChange_checkers_Programation() {
    // Time picker - time start

    // Pattern

    // Daterange - period
    // Manejar el evento change del segundo daterange picker
    $('#period').on('change', function() {
        checkProgramation_daterange();
    });

}





// ####################
// ####### TABS #############
// ####################
