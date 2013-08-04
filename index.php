<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>Group Route Finder</title>
		<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
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

				inputElems = document.getElementsByTagName("input");
				for (var i=0; i<inputElems.length; i++) {
					new google.maps.places.Autocomplete(inputElems[i]);
				}
			}
			
			function findMeetingPoint() {
				var meeting;
				var elem,steps=new Array(nRoutes);
				for(var i=0; i<nRoutes; i++) {
					elem=responseArr[i];
					steps[i] = elem.routes[0].legs[0].steps;
					console.log(steps[i]);
				}
				var foundMeetingPoint=false;
				for(var i=0;i<steps[0].length;i++){
					for(j=0;j<steps[1].length;j++){
						if(steps[0][i].start_point.equals(steps[1][j].start_point)){
							addMarker(steps[0][i-1]);
							addMarker(steps[1][j-1]);
							addMarker(steps[1][j]);
							foundMeetingPoint=true;
							break;
						}
					}
					if (foundMeetingPoint) {
						break;
					}
				}
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
			
			new google.maps.places.Autocomplete(newinput);
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
