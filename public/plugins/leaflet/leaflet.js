$(function() {
    var map = L.map('map-container').setView([51.505, -0.09], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 16,
    }).addTo(map);
    
    var marker = null;
    
    map.on('click', function(e) {
        var latlng = e.latlng;
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(latlng).addTo(map);
        // console.log("Coordenadas del clic: " + latlng.lat + ", " + latlng.lng);
    });

    $('#addMarkerBtn').on('click', function() {
        if (marker) {
            $('#mapModal').dialog('close');
            $('#inputX').val(marker.getLatLng().lat);
            $('#inputY').val(marker.getLatLng().lng);
        } else {
            var snackbar = document.getElementById("snackbar");
            snackbar.className = "show";
            setTimeout(function(){ snackbar.className = snackbar.className.replace("show", ""); }, 3000);
        }
    });

    // FLY TO
    $('#citySelect').on('change', function() {
        var coords = $(this).val().split(',');
        var lat = parseFloat(coords[0]);
        var lng = parseFloat(coords[1]);
        map.flyTo([lat, lng], 13, {duration: 2.5});
    });

    $('#mapModal').dialog({
        autoOpen: false,
        width: 650,
        modal: true,
        close: function() {
            map.invalidateSize();
        }
    });

    $('#openModalBtn').on('click', function(ev) {
        ev.preventDefault();
        $('#mapModal').dialog('open');
        map.invalidateSize();
    });

    // MODAL
    $('#openModalBtn').on('click', function() {
        $('#mapModal').dialog('open');
        map.invalidateSize();
    });

    $( "#mapModal" ).dialog({
        autoOpen: false,
        modal: true,
        width: 650,
        height: 490
    });
});