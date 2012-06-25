function isMobile() {
        if (navigator.userAgent.indexOf('iPhone') != -1 || navigator.userAgent.indexOf('Android') != -1 )
                return true;

        return false;
}

function getUserGeoLocation() {
	var geoLocation = false;

	// Try W3C Geolocation (Preferred)
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
				geoLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			}, 
			function() {
				return false;
			}
		);

	// Try Google Gears Geolocation
	} else if (google.gears) {
		var geo = google.gears.factory.create('beta.geolocation');
		geo.getCurrentPosition(function(position) {
			geoLocation = new google.maps.LatLng(position.latitude,position.longitude);
		}, function() {
			return false;
		});

	// Browser doesn't support Geolocation
	} else {
		return false;
	}

	return geoLocation;
}

