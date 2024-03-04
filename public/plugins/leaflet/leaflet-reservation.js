function mostrarMapa(coordenadas) {
    // Crear mapa
    var map = L.map('map').setView([coordenadas.lat, coordenadas.lng], 15);

    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Añadir marcador
    L.marker([coordenadas.lat, coordenadas.lng]).addTo(map);
}

// Ejemplo de uso
$(function() {
    var coordenadas = {
        lat: 39.4976871919448,
        lng: -0.3856221958994866
    };
    mostrarMapa(coordenadas);
});