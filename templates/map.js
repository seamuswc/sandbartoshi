function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

export function renderMapPage(properties, mapsKey) {
  const key = mapsKey || '';
  const propertiesJson = JSON.stringify(properties);

  return `<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>サンドバー</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header>
    <div class="logo">サンドバー投資株式会社</div>
    <div class="map-links">
      <a onclick="zoomToLocation(33.5858, 130.3489)">福岡</a>
      <a onclick="zoomToLocation(33.8864, 130.8826)">北九州</a>
      <a onclick="zoomToLocation(35.6886, 139.7106)">東京</a>
    </div>
  </header>

  <div id="map"></div>

  <footer>
    <div class="footer-content">
      <div>〒810-0004 福岡市中央区渡辺通２丁目3-8 #504</div>
      <div><a class="footer-phone" href="tel:+815017206334">050 1720 6334</a></div>
      <div>代表取締役: シーマス・コノリー</div>
    </div>
  </footer>

  <script>
    let map;

    function isMobile() {
      return window.innerWidth <= 768;
    }

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: isMobile() ? 15 : 16,
        center: { lat: 33.5858, lng: 130.4089 },
        gestureHandling: 'greedy',
        mapTypeControl: false
      });

      const properties = ${propertiesJson};
      const grouped = {};

      properties.forEach((property) => {
        const key = property.lat + ',' + property.lng;
        if (!grouped[key]) grouped[key] = [];
        grouped[key].push(property);
      });

      Object.keys(grouped).forEach((key) => {
        const atPin = grouped[key];
        const first = atPin[0];
        const latLng = { lat: parseFloat(first.lat), lng: parseFloat(first.lng) };

        const marker = new google.maps.Marker({
          position: latLng,
          map,
          title: first.title
        });

        let content = '';
        atPin.forEach((property, index) => {
          if (index > 0) content += '<br>';
          content += '<div style="font-size:14px;font-weight:bold;">'
            + property.title + ' | ' + property.size + ' sqm'
            + '</div>';
        });

        const infoWindow = new google.maps.InfoWindow({ content });
        marker.addListener('click', () => infoWindow.open({ anchor: marker, map }));
      });
    }

    function zoomToLocation(lat, lng) {
      map.setCenter({ lat, lng });
      map.setZoom(isMobile() ? 15 : 16);
    }
  </script>
  <script async src="https://maps.googleapis.com/maps/api/js?key=${escapeHtml(key)}&loading=async&callback=initMap"></script>
</body>
</html>`;
}
