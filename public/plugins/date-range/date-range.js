$(function () {
    // Get today's date
    var today = new Date();

    // Initialize timepicker
    $('input[name="daterange"]').daterangepicker({
        "showWeekNumbers": false,
        "showISOWeekNumbers": false,
        "showCustomRangeLabel": false,
        "minDate": today,
        "opens": "left",
        locale: {
            format: 'DD/MM/YYYY'
        }
        
    }, function(start, end, label) {
        // console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
    });

    // Initialize timepicker classes
    $('.daterange').daterangepicker({
            "showWeekNumbers": false,
            "showISOWeekNumbers": false,
            "showCustomRangeLabel": false,
            "minDate": today,
            "opens": "left",
            locale: {
                format: 'DD/MM/YYYY'
            }
            
        }, function(start, end, label) {
            // console.log("New date range selected: ' + start.format('DD/MM/YYYY') + ' to ' + end.format('DD/MM/YYYY') + ' (predefined range: ' + label + ')");
        });

})