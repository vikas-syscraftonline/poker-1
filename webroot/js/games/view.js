//initalize the google maps component
document.addEvent('domready', function() {
	if(gameAddress) { //if address was passed from the view, initialize the map
		//display the map canvas
		$("map_canvas").setStyle('display', 'block');

		//load the google maps script and specify our callback function
		var mapsver = '3.0';
		var callback = 'GMapInitialize';
		var sensor = isMobile() ? 'true' : 'false';
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://maps.google.com/maps/api/js?v="+mapsver+"&sensor="+sensor+"&callback="+callback;
		document.body.appendChild(script);
	}
});

//called when the google maps interface is initialized
function GMapInitialize() {
	//get the coordinates of the address
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({ address: gameAddress	}, function(results, status) {

		//if the address can be geolocted, initlaize the map
		if(status == google.maps.GeocoderStatus.OK) {
			//intialize the map and center on the address location
			var myOptions = {
				zoom: 15,
				center: results[0].geometry.location,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				mapTypeControl: false,
				navigationControlOptions: { position: google.maps.ControlPosition.TOP_RIGHT }
			}
			var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

			//add pointer for the location
			var marker = new google.maps.Marker({
				map: map, 
				position: results[0].geometry.location
			});

		//if the address can't be found, hide the map canvas
		} else {
			$('map_canvas').setStyle('display', 'none');
		}
	});
}
