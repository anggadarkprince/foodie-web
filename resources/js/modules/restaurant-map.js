const inputLat = document.getElementById('lat');
const inputLng = document.getElementById('lng');
const inputLocation = document.getElementById('location');
const restaurantMap = document.getElementById('restaurant-map');
let map, geocoder, infoWindow;
let centerLoc = {lat: Number(restaurantMap?.dataset.lat || -7.250445), lng: Number(restaurantMap?.dataset.lng || 112.768845)};

// Initialize and add the map
function initMap() {
    map = new google.maps.Map(restaurantMap, {
        zoom: 15,
        controlSize: 24,
        center: centerLoc,
        scaleControl: false,
        mapTypeControl: false,
        streetViewControl: false,
    });
    geocoder = new google.maps.Geocoder;
    infoWindow = new google.maps.InfoWindow;

    handleChangeCenterLoc(centerLoc);
    handleGeocoderAddress(centerLoc);

    map.addListener("center_changed", () => {
        const currentCenter = map.getCenter();
        centerLoc = {
            lat: currentCenter.lat(),
            lng: currentCenter.lng()
        }
        handleChangeCenterLoc(centerLoc);
    });

    map.addListener('dragend', function() {
        setTimeout(function () {
            handleGeocoderAddress(centerLoc);
        }, 500);
    });

    // Get device location as init map
    if (restaurantMap.classList.contains('init-from-device')) {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            // Get current position of device (if enable)
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    centerLoc = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    map.setCenter(centerLoc);
                    handleChangeCenterLoc(centerLoc);
                    handleGeocoderAddress(centerLoc);
                },
                () => {
                    //handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }
}

function handleChangeCenterLoc(pos) {
    inputLat.value = pos.lat;
    inputLng.value = pos.lng;
}

function handleGeocoderAddress(pos) {
    geocoder.geocode({'location': pos}, function (results, status) {
        if (status === 'OK') {
            if (results[0]) {
                inputLocation.value = results[0].formatted_address;
            } else {
                inputLocation.value = 'Unknown location';
            }
        } else {
            inputLocation.value = 'Geocoder failed due to: ' + status;
        }
    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(map);
}

window.initMap = initMap;
