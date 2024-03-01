// $(function(){
//     // Ocultar los divs con una animación cuando se carga la página
//     $("#overlayer, .loader").fadeOut(600);
// });

// $(function () {
//     $('form').on('submit', function(event) {
//         event.preventDefault(); // Prevent the form from submitting via the browser

//         var locality = $('#combobox').val();
//         var daterange = $('input[name="daterange"]').val();
//         var amount = $('input[name="amount"]').val();
//         var saveSearch = $('input[type="checkbox"]').is(':checked');

//         console.log('Locality: ' + locality);
//         console.log('Date range: ' + daterange);
//         console.log('Amount: ' + amount);
//         console.log('Save search: ' + saveSearch);

//         // Crea un objeto JSON con los datos del formulario
//         var route = {
//             "locality": locality,
//             "daterange": daterange,
//             "amount": amount,
//             "saveSearch": saveSearch
//         };
        
//         // 1RA FORMA DE MANDAR INFORMACIÓN A SERVIDOR
//         // mandar información a servidor
//         $.post('localhost:8000/route/search', route, function(response) {
//             console.log('Respuesta del servidor:', response);
//         })
//         .fail(function(error) {
//             console.error('Error en la solicitud AJAX:', error);
//         });
        

//         // 2DA FORMA DE MANDAR INFORMACIÓN A SERVIDOR
//         // Redirigir a la página de resultados

//         // // Convierte el objeto JSON en una cadena de consulta
//         // var queryString = $.param(route);

//         // // Construye la URL con la cadena de consulta
//         // // var url = 'localhost:8000/route/search?' + queryString;
//         // var url = 'localhost:8000/route/search?locality='+locality+"datarange="+daterange+"amount="+amount+"saveSearch="+saveSearch;

//         // // Redirige a la URL construida
//         // window.location.href = url;
//         // // TODO hacer la redirección a la web => "http://localhost:8000/route/search?locality=2" etc
//     });
// })

function existenRutas(ruta_id) {
    // // Obtener las rutas del servidor mediante una solicitud POST a la API
    // $.ajax({
    //     type: 'GET',
    //     url: '/api/route/existsByLocality/',
    //     contentType: 'application/json',
    //     success: function (response) {
    //         console.log(response)
    //     },
    //     error: function (error) {
    //         console.error('Error:', error);
    //         console.log('Error message:', error.responseText);
    //     }
    // });

    if (ruta_id == 21) {
        return false;
    } else {
        return true;
    }
}

// Función para mostrar el snackbar
function showSnackbar(message) {
    // Selecciona el elemento del snackbar
    var snackbar = $("#snackbar");

    // Establece el mensaje del snackbar
    snackbar.text(message);

    // Agrega la clase show para mostrar el snackbar
    snackbar.addClass("show");

    // Después de 3 segundos, elimina la clase show para ocultar el snackbar
    setTimeout(function(){
        snackbar.removeClass("show");
    }, 3000);
}

$(function () {
    // Inicializar combobox 'placeholder' localidades
    // let inputLocalities = $('input.form-control.custom-select.ui-autocomplete-input');
    let inputLocalities = $('#combobox');
    inputLocalities.attr("placeholder", "Busca una localidad...");

    // Capturamos el evento submit del formulario
    $("#searchForm").submit(function(event) {
        // Prevenimos la acción por defecto del formulario
        event.preventDefault();
        
        // // Si el usuario confirma, volvemos a habilitar el envío del formulario
        // if (inputLocalities.val() != "") {
        //     if (existenRutas( inputLocalities.val() )) {
        //         // Volvemos a habilitar el envío del formulario
        //         $(this).unbind("submit").submit();
        //     } else {
        //         alert("No existen rutas en esta localidad");
        //     }
        // } else {
        //     alert("Debes introducir una localidad");
        // }

        // Si el usuario confirma, volvemos a habilitar el envío del formulario
        if (inputLocalities.val() != "") {
            if (existenRutas(inputLocalities.val())) {
                // Volvemos a habilitar el envío del formulario
                $(this).unbind("submit").submit();
            } else {
                // Muestra el mensaje en el snackbar
                showSnackbar("No existen rutas en esta localidad");
            }
        } else {
            // Muestra el mensaje en el snackbar
            showSnackbar("Debes introducir una localidad");
        }
    });
});