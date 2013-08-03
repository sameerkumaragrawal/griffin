<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>Directions service</title>
		<!-- link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet" -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<script>
		var directionsDisplay;
		var directionsService = new google.maps.DirectionsService();
		var map;

		function initialize() {
			directionsDisplay = new google.maps.DirectionsRenderer();
			var chicago = new google.maps.LatLng(41.850033, -87.6500523);
			var mapOptions = {
			zoom:7,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: chicago
			}
			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			directionsDisplay.setMap(map);
		}

		function getMultipleRoute() {
			var start1 = "joplin, mo";
			var end1 = "chicago, il";
			var start2 = "los angeles, ca";
			var end2 = "chicago, il";
				
			var requestarr = [
				{
					origin:start1,
					destination:end1,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				},
				{
					origin:start2,
					destination:end2,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				}
			];
			
			var sumresponse = null;
			
			//console.log(directionsService);
			var length = requestarr.length,
			request = null;
			for (var i = 0; i < length; i++) {
				request = requestarr[i];
			
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						//console.log(response);
						//console.log(directionsDisplay);
						if (!sumresponse) {
							sumresponse = response;
						} else {
							sumresponse.routes.push.apply(
								sumresponse.routes,
								response.routes
							);
							
							console.log(response);
							console.log(sumresponse);
							
							
							directionsDisplay.setDirections(sumresponse);
						}
					}
				});
			}
		}

		google.maps.event.addDomListener(window, 'load', initialize);

		</script>
	</head>
	<body>
		<div id="panel">
		<b>Start: </b>
		<select id="start" onchange="getMultipleRoute();">
			<option value="chicago, il">Chicago</option>
			<option value="st louis, mo">St Louis</option>
			<option value="joplin, mo">Joplin, MO</option>
			<option value="oklahoma city, ok">Oklahoma City</option>
			<option value="amarillo, tx">Amarillo</option>
			<option value="gallup, nm">Gallup, NM</option>
			<option value="flagstaff, az">Flagstaff, AZ</option>
			<option value="winona, az">Winona</option>
			<option value="kingman, az">Kingman</option>
			<option value="barstow, ca">Barstow</option>
			<option value="san bernardino, ca">San Bernardino</option>
			<option value="los angeles, ca">Los Angeles</option>
		</select>
		<b>End: </b>
		<select id="end" onchange="getMultipleRoute();">
			<option value="chicago, il">Chicago</option>
			<option value="st louis, mo">St Louis</option>
			<option value="joplin, mo">Joplin, MO</option>
			<option value="oklahoma city, ok">Oklahoma City</option>
			<option value="amarillo, tx">Amarillo</option>
			<option value="gallup, nm">Gallup, NM</option>
			<option value="flagstaff, az">Flagstaff, AZ</option>
			<option value="winona, az">Winona</option>
			<option value="kingman, az">Kingman</option>
			<option value="barstow, ca">Barstow</option>
			<option value="san bernardino, ca">San Bernardino</option>
			<option value="los angeles, ca">Los Angeles</option>
		</select>
		</div>
		<div id="map-canvas" style="width: 900px; height: 400px">kk</div>
	</body>
</html>
