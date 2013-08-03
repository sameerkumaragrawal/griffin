<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>Directions service</title>
		<!-- link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet" -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<script>
			var directionsService = new google.maps.DirectionsService();
			var map;

			function initialize() {
				var chicago = new google.maps.LatLng(41.850033, -87.6500523);
				var mapOptions = {
					zoom:7,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					center: chicago
				}
				map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		    getMultipleRoute();
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
			
			
				var length = requestarr.length,
				request = null;
				directionsDisplayArr= new Array();
				var j=0;
				for (var i = 0; i < length; i++) {
					request = requestarr[i];
					directionsDisplayArr.push(new google.maps.DirectionsRenderer());
					directionsDisplayArr[i].setMap(map);
							
					directionsService.route(request, function(response, status) {
						if (status == google.maps.DirectionsStatus.OK) {
								directionsDisplayArr[j].setDirections(response);
								j++;
						}
					});
				}
			}
			 
	 		google.maps.event.addDomListener(window, 'load', initialize);
		</script>
	</head>
	<body>
		<div id="map-canvas" style="width: 900px; height: 400px">kk</div>
	</body>
</html>
