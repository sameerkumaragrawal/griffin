<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>Group Route Finder</title>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<script>
			var directionsDisplayArr=new Array();
			var directionsService = new google.maps.DirectionsService();
			var map;
			var stepDisplay;
			var markerArr=new Array();
			var starts;
			var end;
			var index = 0;
			var nRoutes=0;
			var responseArr=new Array();
			var requestArr=new Array();

			function initialize() {
				var mumbai = new google.maps.LatLng(19, 73);
				var mapOptions = {
					zoom:7,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					center: mumbai
				}
				map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
				stepDisplay=new google.maps.InfoWindow();
			}
			
			function findMeetingPoint() {
				var meeting;
				var elem,steps=new Array(nRoutes);
				for(var i=0; i<nRoutes; i++) {
					elem=responseArr[i];
					steps[i] = elem.routes[0].legs[0].steps;
				}
				var foundMeetingPoint=false;
				for(var i=0;i<steps[0].length;i++){
					for(j=0;j<steps[1].length;j++){
						if(steps[0][i].start_point.equals(steps[1][j].start_point)){
							foundMeetingPoint=true;
							addMarker(computeMeetingPoint(steps[0][i-1], steps[1][j-1], steps[1][j]));
							break;
						}
					}
					if (foundMeetingPoint) {
						break;
					}
				}
			}

			function 	computeMeetingPoint(a, b, ab) {
				var distanceAtoAB = a.distance.value;
				var distanceBtoAB = b.distance.value;
				var distanceAtoB = getDistance(a.start_point, b.start_point);
				var difference = 0;
				if (distanceAtoAB > distanceBtoAB) {
					difference = Math.abs(distanceAtoB + distanceBtoAB - distanceAtoAB);
					if (difference  < 2000) return b;
					else return ab;
				}
				else {
					difference = Math.abs(distanceAtoB + distanceAtoAB - distanceBtoAB);
					if (difference  < 2000) return a;
					else return ab;
				}
			}
			
			function getDistance(point1, point2) {
				var lat1 = point1.jb * 3.14 / 180;
				var lon1 = point1.kb * 3.14 / 180;
				var lat2 = point2.jb * 3.14 / 180;
				var lon2 = point2.kb * 3.14 / 180;
				
				var x = (lon2-lon1) * Math.cos((lat1+lat2)/2);
				var y = (lat2-lat1);
				var d = Math.sqrt(x*x + y*y) * 6371000;
				return d;
			} 
			
			function getMultipleRoute() {
				var requestarr=new Array();
				
				for (i=0;i<starts.length;i++) {
					requestarr.push({
						origin:starts[i],
						destination:end,
						travelMode: google.maps.DirectionsTravelMode.DRIVING
					});
				}
				
				request = null;
				directionsDisplayArr= new Array();
				var j=0;
				for (var i = 0; i < nRoutes; i++) {
					request = requestarr[i];
					directionsDisplayArr.push(new google.maps.DirectionsRenderer());
					directionsDisplayArr[i].setMap(map);
							
					directionsService.route(request, function(response, status) {
						if (status == google.maps.DirectionsStatus.OK) {
								responseArr.push(response);
								directionsDisplayArr[j].setDirections(response);		
								//showSteps(response);
								j++;
						}
						if(j==nRoutes) findMeetingPoint();
					});
				}
			}
			
			function addMarker(meetStep){
				var marker = new google.maps.Marker({
						position: meetStep.start_point,
						map: map
				});
				attachInstructionText(marker, meetStep.instructions);
				markerArr.push(marker);
			}
			
			function showSteps(directionResult) {
				var myRoute = directionResult.routes[0].legs[0];

				for (var i = 0; i < myRoute.steps.length; i++) {
					var marker = new google.maps.Marker({
						position: myRoute.steps[i].start_point,
						map: map
					});
					attachInstructionText(marker, myRoute.steps[i].instructions);
					markerArr.push(marker);
					}
			}

		function attachInstructionText(marker, text) {
			google.maps.event.addListener(marker, "click", function() {
				stepDisplay.setContent(text);
				stepDisplay.open(map, marker);
			});
		} 
		
		function deleteOverlays() {
			for(var i=0;i<nRoutes;i++){
				directionsDisplayArr[i].setMap(null);
				
			}
			
			for (var i=0;i<markerArr.length;i++){
				markerArr[i].setMap(null);
			}
			
			markerArr.length = 0;
			directionsDisplayArr.length = 0;
		}
		
		function submitform() {
			deleteOverlays();
				
			responseArr=new Array();
			requestArr=new Array();
			markerArr=new Array();
			starts=new Array();
			directionsDisplayArr= new Array();
			
			var inputdivs = document.getElementById("source-panel").children;
			nRoutes=inputdivs.length;
			for (i=0;i<nRoutes;i++) {
				starts.push(inputdivs[i].children[0].value);
			}
			end = document.getElementById("end").value;
			console.log(starts);
			getMultipleRoute();
		}
		
		function addInput() {
			var newdiv = document.createElement("DIV");
			var newinput = document.createElement("INPUT");
			var inputcount = document.getElementById("source-panel").childElementCount;
			
			newinput.setAttribute("type", "text");
			newdiv.innerHTML = "Source"+(inputcount+1) + ": ";
			newdiv.appendChild(newinput);
			document.getElementById("source-panel").appendChild(newdiv);
		}
		
		function removeInput() {
			sp = document.getElementById("source-panel");
			if (sp.children.length>1)
				sp.removeChild(sp.lastElementChild);
		}
			
		google.maps.event.addDomListener(window, "load", initialize);
		</script>
	</head>
	<body>
		<div class="container">
			<div align="center">
				<h3>Group Route Finder</h3>
				<div id="source-panel">
					<div> Source1: <input type="text"> </div>
					<div> Source2: <input type="text"> </div>
				</div>
				<div id="destination-panel">
					Destination: <input type="text" name="end" id="end">
				</div>
				<p>
					<button type="button" class="btn btn-primary" onclick="addInput();">Add Source</button>
					<button type="button" class="btn btn-primary" onclick="removeInput();">Remove Source</button>
					<button type="button" class="btn btn-success" onclick="submitform();">Get routes</button>
				</p>
			</div>
			<div id="map-canvas" style="min-width: 500px; min-height: 400px;">Loading Google Map Api..</div>
		  </div>
	</body>
</html>
