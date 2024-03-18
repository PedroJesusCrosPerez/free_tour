$(function () {
    // // Obtener los datos del servidor (en este caso, utilizando AJAX)
    // fetch('datos.php')
    // .then(response => response.json())
    // .then(data => {
    //     // Crear el gráfico con los datos recibidos
    //     var ctx = document.getElementById('myChart').getContext('2d');
    //     var myChart = new Chart(ctx, {
    //         type: 'line',
    //         data: {
    //             labels: data.labels,
    //             datasets: [{
    //                 label: 'Reservas por día',
    //                 data: data.reservas,
    //                 backgroundColor: 'rgba(54, 162, 235, 0.2)',
    //                 borderColor: 'rgba(54, 162, 235, 1)',
    //                 borderWidth: 1
    //             }]
    //         },
    //         options: {
    //             scales: {
    //                 yAxes: [{
    //                     ticks: {
    //                         beginAtZero: true
    //                     }
    //                 }]
    //             }
    //         }
    //     });
    // });

    $(function(){
        // Datos de las reservas por día
        var dataPoints = [
            { label: "2024-02-22", y: 3 },
            { label: "2024-03-03", y: 2 },
            { label: "2024-04-12", y: 2 },
            { label: "2024-03-05", y: 2 },
            { label: "2024-03-07", y: 3 },
            { label: "2024-03-09", y: 1 }
        ];

        // Configuración del gráfico
        var options = {
            title: {
                text: "Reservas por día"
            },
            data: [{
                type: "column",
                dataPoints: dataPoints
            }]
        };

        // Creando el gráfico
        $("#chartContainer").CanvasJSChart(options);
    });
})