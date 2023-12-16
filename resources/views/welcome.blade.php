<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style>
        #map {
        height: 300px;
        }
    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>   
    </head>


    <body >
        
    <div id="map"></div>
    <button id="geocode">Buscar</button>
    <div id="result"></div>

    <input type="text" id="tuInputDireccion" placeholder="Ingresa tu dirección" />
    <script>
   
document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([6.2440, -75.5775], 13);
    var marker;  // Variable para almacenar el marcador

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Añadir un área de texto para mostrar la dirección
    var addressTextArea = document.createElement('textarea');
    addressTextArea.rows = 3;  // Ajusta según tus preferencias
    addressTextArea.style.width = '100%';
    document.body.appendChild(addressTextArea);

    // Obtener el input de dirección
    var inputDireccion = document.getElementById('tuInputDireccion'); // Ajusta según tu HTML

    map.on('click', function (e) {
        var coordinates = e.latlng;
        console.log('Coordenadas seleccionadas:', coordinates);

        // Limpiar marcador existente si lo hay
        if (marker) {
            map.removeLayer(marker);
        }

        // Realizar una solicitud para obtener la dirección en función de las coordenadas
        reverseGeocode(coordinates);
    });

    // Manejo de eventos para el input de dirección
    inputDireccion.addEventListener('change', function () {
        // Obtén el valor del input y realiza la geocodificación
        var direccion = inputDireccion.value;
        geocodeAddress(direccion);
    });

    function reverseGeocode(coordinates) {
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + coordinates.lat + '&lon=' + coordinates.lng)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud de geocodificación');
                }
                return response.json();
            })
            .then(data => {
                var address = data.display_name;
                console.log('Dirección obtenida:', address);

                // Mostrar la dirección en el área de texto
                addressTextArea.value = address;

                // Crear y añadir un marcador al mapa
                marker = L.marker(coordinates).addTo(map);
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para geocodificar una dirección
    function geocodeAddress(direccion) {
        // Realizar la solicitud de geocodificación según la dirección ingresada
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(direccion))
            .then(response => response.json())
            .then(data => {
                // Manejar la respuesta y mostrar la ubicación en el mapa
                var firstResult = data[0];
                if (firstResult) {
                    var coordinates = {
                        lat: parseFloat(firstResult.lat),
                        lng: parseFloat(firstResult.lon)
                    };

                    // Limpiar marcador existente si lo hay
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Mostrar la dirección en el área de texto
                    addressTextArea.value = firstResult.display_name;

                    // Crear y añadir un marcador al mapa
                    marker = L.marker(coordinates).addTo(map);
                } else {
                    console.log('No se encontraron resultados de geocodificación para la dirección:', direccion);
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>


    </body>
</html>
