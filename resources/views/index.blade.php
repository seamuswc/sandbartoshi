<!DOCTYPE html>
<html lang="en">
<head>
    <title>Real Estate Listings</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo">
            サンドバー投資株式会社
        </div>

        <!-- Map Links -->
        <div class="map-links">
            <a onclick="zoomToLocation(33.5858, 130.4089)">福岡</a> <!-- Fukuoka -->
            <a onclick="zoomToLocation(35.6886, 139.7106)">東京</a> <!-- Tokyo -->
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
            <span class="phone-number">+81 050 1720 6334</span>
        </div>
    </header>

    <!-- Map -->
    <div id="map"></div>

    <script>
        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 16,
                center: { lat: 33.5858, lng: 130.4089 }, // Fukuoka default
                gestureHandling: 'greedy',
                mapTypeControl: false
            });

            const properties = @json($properties);
            const groupedProperties = {};
            properties.forEach((property) => {
                const latLngKey = `${property.lat},${property.lng}`;
                if (!groupedProperties[latLngKey]) {
                    groupedProperties[latLngKey] = [];
                }
                groupedProperties[latLngKey].push(property);
            });

            Object.keys(groupedProperties).forEach((latLngKey) => {
                const propertiesInSameLocation = groupedProperties[latLngKey];
                const firstProperty = propertiesInSameLocation[0];
                const latLng = { lat: parseFloat(firstProperty.lat), lng: parseFloat(firstProperty.lng) };

                const marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: firstProperty.title
                });

                let infoWindowContent = '';
                propertiesInSameLocation.forEach((property, index) => {
                    if (index > 0) infoWindowContent += '<br>';
                    infoWindowContent += `<div style="font-size: 14px; font-weight: bold;">
                                          ${property.title} | ${property.size} sqm
                                          </div>`;
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: infoWindowContent
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            });
        }

        // Function to zoom and center the map at a given latitude and longitude
        function zoomToLocation(lat, lng) {
            map.setCenter({ lat: lat, lng: lng });
            map.setZoom(16);
        }
    </script>

    <!-- Include Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVdAS-3mrNYARIDmqn2dP1tG1Khqv5GoM&callback=initMap"></script>
</body>
</html>
