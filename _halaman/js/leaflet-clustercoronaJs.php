<!-- Make sure you put this AFTER Leaflet's CSS -->
	<link rel="stylesheet" href="assets/js/Leaflet.markercluster-master/dist/MarkerCluster.css" />
	<link rel="stylesheet" href="assets/js/Leaflet.markercluster-master/dist/MarkerCluster.Default.css" />
	
	<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
	 integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
	 crossorigin=""></script>
	<script src="assets/js/Leaflet.markercluster-master/dist/leaflet.markercluster-src.js"></script>
	<script src="assets/js/leaflet-search/dist/leaflet-search.js"></script>

   	<script type="text/javascript">

	var map = new L.Map('mapid', {zoom: 5, center: new L.latLng([-0.9898181822734585, 113.91586500022191]) });

	var mapLayer=L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox.streets',
		accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
	});
	map.addLayer(mapLayer);

	// search
	var markersLayer = new L.LayerGroup();	//layer contain searched elements
	
	map.addLayer(markersLayer);

	var controlSearch = new L.Control.Search({
		position:'topright',		
		layer: markersLayer,
		initial: false,
		zoom: 8,
		marker: false
	});

	map.addControl( controlSearch );

	// marker
	<?php
		// dari https://bnpb-inacovid19.hub.arcgis.com/datasets/data-harian-kasus-per-provinsi-covid-19-indonesia/geoservice
		$url = "https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json";
		$ch=curl_init($url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type : application/json',
				'Access-Control-Allow-Origin : *'
			]);
		$result= curl_exec($ch);
		curl_close($ch);
	?>

	var getCoronaJson=<?=$result?>;
	var coronaData=getCoronaJson.features;

	var markers = L.markerClusterGroup();
	for(i=0;i<coronaData.length;i++){
		var data=coronaData[i].attributes;
		var geoLoc=coronaData[i].geometry;
		if(geoLoc.x!=null && geoLoc.y!=null){
			var title = data.Provinsi;
			var loc = [geoLoc.y, geoLoc.x];
			var marker  = L.marker(new L.latLng(loc), {title: title})
					.bindPopup(
							"Provinsi : "+data.Provinsi+"<br>"+
							"Terinfeksi : "+data.Kasus_Posi+"<br>"+
							"Meninggal : "+data.Kasus_Meni+"<br>"+
							"Sembuh : "+data.Kasus_Semb+"<br>"
						);
			markers.addLayer(marker);
		}
	}
	markersLayer.addLayer(markers);

   </script>
   