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