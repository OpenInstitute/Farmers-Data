
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
    
<script src="assets/scripts/jquery-3.1.1.js" type="text/javascript"></script>    
<link rel="stylesheet" href="assets/scripts/leaflet/leaflet.css" />
<!--[if lte IE 8]>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.6.4/leaflet.ie.css" />
<![endif]-->

<script src="assets/scripts/leaflet/leaflet.js"></script>
<script src="assets/scripts/leaflet/leaflet-src.js"></script>
<script type="text/javascript" src="assets/scripts/leaflet/leaflet.ajax.js"></script>
<script src="assets/scripts/leaflet/spin.js"></script>
<script src="assets/scripts/leaflet/leaflet.spin.js"></script>
   <style>
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0;}
      #map{ /*height: 100%;*/ margin: auto; /*width: 1200px;*/ height: 580px; }
		.info { padding: 6px 8px; font: 14px/16px Arial, Helvetica, sans-serif; background: white; background: rgba(255,255,255,0.8); box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; } .info h4 { margin: 0 0 5px; color: #777; }
    </style>
    
    <title>Open Institute - Nakuru North GGLI 2018</title>
    </head>
    
    
    <body>
    <div id="map"></div>
     
     
     
 <div id="mapsubs"></div>    

<script type="text/javascript" src="assets/scripts/maps/nakurunorthsubs-geojson.json"></script>  
<script type="text/javascript" src="assets/scripts/maps/nakurunorthsubs-color.json"></script>  
<script type="text/javascript">

	function getJProps(array, value) {
		return array.filter(function (object) {
			return object["id"] === value;
		});
	}
	
	/* ========== MERGING OPTIONS ============= */
	
	function getObjects(obj, key, val) {
		var objects = [];
		for (var i in obj) {
			if (!obj.hasOwnProperty(i)) continue;
			if (typeof obj[i] == 'object') {
				objects = objects.concat(getObjects(obj[i], key, val));
			} else if (i == key && obj[key] == val) {
				objects.push(obj);
			}
		}
		return objects;
	}
	
	
	function mergeProperties(geom, props, id) {
		var newgeom = {
			type: "FeatureCollection",
			features: []
		};
		
		if (geom.type == "FeatureCollection") {
			for (var i in geom.features) {
				var key = geom.features[i][id];
				var g = geom.features[i].geometry;
				var prp = geom.features[i].properties;				
				if (key === undefined) continue;				
				var p = getObjects(props, id, key);					
				var newPrp = jQuery.extend(prp, p[0].recprops);
				
				newgeom.features.push({
					"id": key,
					"type": "Feature",
					"properties": newPrp,
					"geometry": g
				});
			}
		//if it's a single feature, just get the single object
		} else if (geom.type == "Feature") {
			var key = geom[id];
			var g = geom.geometry;
			var prp = geom.properties;
			if (key !== undefined) {
				var p = getObjects(props, id, key);
				var newPrp = jQuery.extend(prp, p[0].recprops);
				newgeom.features.push({
					"id": key,
						"type": "Feature",
						"properties": newPrp,
						"geometry": g						
				});
			}
		//if it's a geometry collection (with ids within each geometry), add the geometries as features
		} else if (geom.type == "GeometryCollection") {
			for (var i in geom.geometries) {
				var key = geom.geometries[i][id];
				var g = geom.geometries[i];
				var prp = geom.geometries[i].properties;
				if (key === undefined) continue;
				var p = getObjects(props, id, key);
				var newPrp = jQuery.extend(prp, p[0].recprops);
				newgeom.features.push({
					"id": key,
						"type": "Feature",
						"properties": newPrp,
						"geometry": g
				});
			}
		}
		return newgeom;
	}
	
	/* ========== END: MERGING OPTIONS ============= */
	
	var mapsData = mergeProperties(locsMapData, propTable, "id");
	/*console.log(mapsData);
	console.log(mapsData.features[0].properties);
	console.log(mapsData.features[0].recprops);*/
	
	
	var map= L.map('map').setView([-0.18, 36.25], 11);

    // disable drag and zoom handlers
    // map.dragging.disable();
    map.touchZoom.disable();
    // map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();





	var mopt = {
		url: 'https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoicmFnZW11bmVuZSIsImEiOiJjamtlcmZ0dHIwMGhvM2tvZ3JmOG96ZHN2In0.AwR5ZW21EN9AOJ8NuCXECw',
		options: {maxZoom: 18,attribution:'© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', id: 'mapbox.light'}
	  };
	var mq = L.tileLayer(mopt.url, mopt.options);

	mq.addTo(map);

	var info = L.control();

	info.onAdd = function (map) { 
		this._div = L.DomUtil.create('div', 'info');
		this.update();
		return this._div;
	};

	info.update = function (props) {		
		this._div.innerHTML = '<h4>Location Details</h4>' +  (props ?
			'<b>' + props.SLNAME + '</b><br />' + props.population + ' households'
			: 'Hover over an area');
	};

	info.addTo(map);
	
	
	function getColor(d) {
		return d > 1000 ? '#800026' :
				d > 500  ? '#BD0026' :
				d > 200  ? '#E31A1C' :
				d > 100  ? '#FC4E2A' :
				d > 70   ? '#FD8D3C' :
				d > 30   ? '#FEB24C' :
				d > 10   ? '#FED976' :
							'#FFEDA0';
	}
	
	function style(feature) {
		return {weight: 2,opacity: 1,color: 'white',dashArray: '3',fillOpacity: 0.7, fillColor: getColor(feature.properties.population)};
	}
	
	var locStyle = {"color": "#6BCCF2","weight": 3,"opacity": 0.65};
	var sublocStyle = {"color": "#ff7800","weight": 2,"opacity": 1, dashArray: '3', fillColor: "#FFEDA0", fillOpacity: 0.7};

	function popUp(f,l){
		var out = [];
		if (f.properties){
			for(key in f.properties){ 
				if(key !== 'SLID'){
				var keyalt = (key === 'SLNAME') ? 'Name' : key; out.push(keyalt+": "+f.properties[key]); 
				}
			}
			l.bindPopup(out.join("<br />"));
		}
	}
	
	
	
	function highlightFeature(e) {
		popUp(e.target.feature, e.target);
		var layer = e.target;
		layer.setStyle({weight: 5,color: '#666',dashArray: '',fillOpacity: 0.7});		
		if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) { layer.bringToFront(); }		
		info.update(layer.feature.properties);
	}

	var geojson;

	
	function resetHighlight(e) { 
		geojson.resetStyle(e.target);
		info.update();
	}

	function zoomToFeature(e) {
		/*map.fitBounds(e.target.getBounds());*/
	}

	function onEachFeature(feature, layer) {		
		layer.on({
			mouseover: highlightFeature,
			mouseout: resetHighlight,
			click: zoomToFeature
		});
		
	}

	
	
	//var jsonTest = new L.GeoJSON.AJAX(["maps/nakurunorth-geojson.json"],{style: locStyle},{onEachFeature:popUp}).addTo(map);
	geojson = L.geoJson(mapsData, { style: style, onEachFeature: onEachFeature }).addTo(map);


    // Prevent map zooming
    // var map = new L.Map('map', { zoomControl:false });

	
</script>


    </body>
</html>
