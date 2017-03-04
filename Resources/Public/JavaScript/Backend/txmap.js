/**
 * Handles geocoding and displays google maps
 *
 * @package TYPO3
 * @subpackage tx_map
 * @copyright Copyright belongs to the respective authors
 * @author 2016 J.Kummer <typo3 et enobe dot de>, enobe.de
 * @author 2016 Robert Gonda <robert.gonda@gmail.com> EXT:rtg_ttaddress_extend
 * @author 2011 Christian Brinkert <christian.brinkert@googlemail.com>
 */

/**
 * _TXMAP_FORM
 *
 *  entryDefaultLatitude: Default value if 0, null or empty
 *  entryDefaultLongitude: Default value if 0, null or empty
 *  entryFieldLatitude: TCA or flexform field path. fx: pi_flexform/data/sDEF/lDEF/settings.latitude/vDEF
 *  entryFieldLongitude: TCA or flexform field path. fx: pi_flexform/data/sDEF/lDEF/settings.longitude/vDEF
 *  entryTable: TCA entry table name
 *  entryUid: TCA entry row uid
 *  mapElementId: HTML container id for map canvas. fx:tx_map_map_canvas
 *  mapDefaultZoom: Integer value for map option zoom. Range 1-19
 *  .
 *  entryFieldPath: TCA field path. fx: data[TABLENAME][UID]
 */
var _TXMAP_FORM = [];
var _TXMAP_MAP;

/**
 * Init map control data
 */
var initTxMapForm = function () {
    _TXMAP_FORM = TYPO3.jQuery(document.getElementById('tx_map_map_control')).data();
    // Eval Fix default zoom
    _TXMAP_FORM.mapDefaultZoom = _TXMAP_FORM.mapDefaultZoom > 0 ? _TXMAP_FORM.mapDefaultZoom : 10;
    // Set entryFieldPath
    _TXMAP_FORM.entryFieldPath = "data[" + _TXMAP_FORM.entryTable + "][" + _TXMAP_FORM.entryUid + "]";
    // Convert entry field names
    _TXMAP_FORM.entryFieldLatitude = convertEntryFieldName(_TXMAP_FORM.entryFieldLatitude);
    _TXMAP_FORM.entryFieldLongitude = convertEntryFieldName(_TXMAP_FORM.entryFieldLongitude);
    _TXMAP_FORM.entryFieldZoom = convertEntryFieldName(_TXMAP_FORM.entryFieldZoom);
    // Renew map options zoom
    renewMapOptionZoom();
}

/**
 * Convert entry field names
 * Fieldname can be table- or flexform-field
 * TCA: from fieldName to [fieldName]
 * Flexform: from pi_flexform/data/sDEF/lDEF/settings.field/vDEF to [pi_flexform][data][sDEF][lDEF][settings.field][vDEF]
 */
var convertEntryFieldName = function (field) {
    if (field) {
        if (field.indexOf('[') != 0) {
            return '[' + field.split('/').join('][') + ']';
        } else {
            return field;
        }
    } else {
        return null;
    }
}

/**
 * Renew map options zoom
 * Read entry field zoom and resets default predefined zoom value
 */
var renewMapOptionZoom = function () {
    if (_TXMAP_FORM.entryFieldZoom
        && TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldZoom + "\']").val()) {
        _TXMAP_FORM.mapDefaultZoom = TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldZoom + "\']").val();
        _TXMAP_FORM.mapDefaultZoom = _TXMAP_FORM.mapDefaultZoom > 0 ? _TXMAP_FORM.mapDefaultZoom : 10;
    }
}

/**
 * Try to find coordinates by given address
 * Written for TCA fields
 */
var doGeocoding = function () {
    var street = '';
    var zip = '';
    var city = '';
    var country = '';
    // Fetch current inputs
    if (TYPO3.jQuery
        && TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + "[address]\']")) {
        street = TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + "[address]\']").val();
        zip = TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + "[zip]\']").val();
        city = TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + "[city]\']").val();
        country = TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + "[country]\']").val();
    }
    // Create gmaps object
    var gmap = new gmaps();
    // Associate values to object
    gmap.setAddress(street, zip, city, country);
    // Try to fetch coordinates by address, set callback method
    gmap.fetchCoordinatesByAddress(resultsHandling);
}

/**
 * Result handle to check if gecoding was succesfull and display results in backend form
 *
 * @param latitude
 * @param longitude
 */
var resultsHandling = function (latitude, longitude) {
    // Work with given results
    if (typeof(latitude) == 'number' && typeof(longitude) == 'number') {
        // Write results to backend form
        if (TYPO3.jQuery
            && TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']")) {
            TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']").val(latitude);
            TYPO3.jQuery("input[name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']").val(latitude);
            TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLongitude + "\']").val(longitude);
            TYPO3.jQuery("input[name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLongitude + "\']").val(longitude);
        }
    } else {
        alert("Given address could not been fetched! #1473353090");
    }
    // Reload map
    renderMap(latitude, longitude);
}

/**
 * Display results in a new windows with coordinates and google maps preview
 */
var displayMap = function () {
    // enable options
    enableOptions();
    // Init map form
    initTxMapForm();
    // Get coordinates
    var coordinates = getCoordinates();
    // Render maps
    renderMap(coordinates.latitude, coordinates.longitude);
}

/**
 * Hide google maps preview, resets global values
 */
var closeMap = function () {
    // disable options
    disableOptions();
    TYPO3.jQuery(document.getElementById(_TXMAP_FORM.mapElementId)).hide();
    _TXMAP_FORM = [];
}

/**
 * Fetch zoom
 * Sets map zoom on current entry field zoom value
 */
var fetchZoom = function () {
    renewMapOptionZoom();
    _TXMAP_MAP.setZoom(parseInt(_TXMAP_FORM.mapDefaultZoom));
}

/**
 * Get coordinates
 */
var getCoordinates = function () {
    var coordinates = [];
    coordinates.latitude = 0;
    coordinates.longitude = 0;
    // Write results to backend form
    if (TYPO3.jQuery && TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']").val()) {
        coordinates.latitude = parseFloat(
            TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']").val()
        );
        coordinates.longitude = parseFloat(
            TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLongitude + "\']").val()
        );
    }
    return coordinates;
}

/**
 * Render map
 *
 * @param latitude
 * @param longitude
 */
var renderMap = function (latitude, longitude) {
    // Check if coordinates are given, try to retrieve from default
    if (typeof(latitude) !== 'number' && typeof(longitude) !== 'number') {
        // Use default values
        if (typeof(_TXMAP_FORM.entryDefaultLatitude) == 'number' && typeof(_TXMAP_FORM.entryDefaultLongitude) == 'number') {
            latitude = parseFloat(_TXMAP_FORM.entryDefaultLatitude);
            longitude = parseFloat(_TXMAP_FORM.entryDefaultLongitude);
        }
    }
    if (typeof(latitude) == 'number' && typeof(longitude) == 'number') {
        // Set attributes to the map
        document.getElementById(_TXMAP_FORM.mapElementId).setAttribute("style", "height:300px; border:1px solid #BBBBBB; ", false);
        // Create google maps LatLng object
        var latlng = new google.maps.LatLng(latitude, longitude);
        // Set options object
        var myOptions = {
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            zoom: parseInt(_TXMAP_FORM.mapDefaultZoom)
        };
        // Create map
        _TXMAP_MAP = new google.maps.Map(document.getElementById(_TXMAP_FORM.mapElementId), myOptions);
        // Enable scrolling on click event on the map
        _TXMAP_MAP.addListener('click', enableScrollingWithMouseWheel);
        // Enable scrolling on drag event on the map
        _TXMAP_MAP.addListener('drag', enableScrollingWithMouseWheel);
        // Disable scrolling on mouseout from the map
        _TXMAP_MAP.addListener('mouseout', disableScrollingWithMouseWheel);
        // Create map marker
        var marker = new google.maps.Marker({
            position: latlng,
            draggable: true,
            title: ""
        });
        // Add listener
        google.maps.event.addListener(marker, 'dragend', function () {
            // Set new coordinates to the typo3 backend
            if (TYPO3.jQuery
                && TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']")) {
                TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']").val(marker.getPosition().lat());
                TYPO3.jQuery("input[name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLatitude + "\']").val(marker.getPosition().lat());
                TYPO3.jQuery("input[data-formengine-input-name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLongitude + "\']").val(marker.getPosition().lng());
                TYPO3.jQuery("input[name*=\'" + _TXMAP_FORM.entryFieldPath + _TXMAP_FORM.entryFieldLongitude + "\']").val(marker.getPosition().lng());
            }
        });
        // Assign marker to the map
        marker.setMap(_TXMAP_MAP);
    } else {
        alert('No coordinates found! #1473353093');
    }
}

/*
 * Provides functionality to geocode, localize positions by using
 * google maps api v3 and display given geocoded postions in
 * google maps views.
 *
 * @package class.gmaps
 * @version 1.0: gmaps,  03-2011
 * @copyright (c)2011 Christian Brinkert
 * @author Christian Brinkert <christian.brinkert@googlemail.com>
 */
var gmaps = function () {
    // Set self
    var self = this;
    // Set private properties
    var street = null;
    var zip = null;
    var city = null;
    var country = null;
    // Initialize defauls
    var lat = null;
    var lng = null;
    // Add helper methods
    function trim(givenString) {
        return givenString.replace(/^\s+/, '').replace(/\s+$/, '');
    }
    // Add getter & setter methods
    this.setStreet = function (streetString) {
        if (typeof(streetString) == 'string' && '' != streetString)
            self.street = streetString;
    }
    this.getStreet = function () {
        return self.street;
    }
    this.setZip = function (zipCode) {
        if (typeof(zipCode) == 'string' && '' != zipCode)
            self.zip = zipCode;
    }
    this.getZip = function () {
        return self.zip;
    }
    this.setCity = function (cityString) {
        if (typeof(cityString) == 'string' && '' != cityString)
            self.city = cityString;
    }
    this.getCity = function () {
        return self.city;
    }
    this.setCountry = function (countryString) {
        if (typeof(countryString) == 'string' && '' != countryString)
            self.country = countryString;
    }
    this.getCountry = function () {
        return self.country;
    }
    this.setLatitude = function (latitude) {
        if (typeof(latitude) == 'number')
            self.lat = latitude;
    }
    this.getLatitude = function () {
        return self.lat;
    }
    this.setLongitude = function (longitude) {
        if (typeof(longitude) == 'number')
            self.lng = longitude;
    }
    this.getLongitude = function () {
        return self.lng;
    }
    // Get geocoordinates
    this.fetchCoordinatesByAddress = function (callback) {
        // Create google geocoder object
        var geocoder = new google.maps.Geocoder();
        if (geocoder) {
            // Try to fetch coordinates and save them to local property
            geocoder.geocode({'address': this.getAddressAsString()}, function (results, status) {
                // Check if geocoding was successfull
                if (status == google.maps.GeocoderStatus.OK) {
                    self.setLatitude(results[0].geometry.location.lat());
                    self.setLongitude(results[0].geometry.location.lng());
                    // Return values to callback method
                    callback(self.getLatitude(), self.getLongitude());
                }
            });
        }
    }
    // Set location by one method
    this.setAddress = function (street, zip, city, country) {
        self.setStreet(street);
        self.setZip(zip);
        self.setCity(city);
        self.setCountry(country);
    }
    // Return complete location as comma separated string
    this.getAddressAsString = function () {
        var address = new Array();
        if (null != self.getStreet())
            address.push(self.getStreet());
        if (null != self.getZip() && null != self.getCity()) {
            address.push(trim(self.getZip() + " " + self.getCity()));
        } else if (null != self.getZip()) {
            address.push(self.getZip());
        } else if (null != self.getCity()) {
            address.push(self.getCity());
        }
        if (null != self.getCountry())
            address.push(self.getCountry());
        // Return array as string
        var addressAsString = address.join(", ");
        return addressAsString;
    }
    // Set coordinates by one call
    this.setCoordinates = function (lat, lng) {
        self.lat = latitude;
        self.lng = longitude;
    }
}

/**
 * Enable scrolling for map zoom
 */
var enableScrollingWithMouseWheel = function () {
    _TXMAP_MAP.setOptions({scrollwheel: true});
}

/**
 * Disable scrolling for map zoom
 */
var disableScrollingWithMouseWheel = function () {
    _TXMAP_MAP.setOptions({scrollwheel: false});
}

/**
 * Enable options
 */
var enableOptions = function () {
    document.getElementById('tx_map_map_control_displaymap') ? document.getElementById('tx_map_map_control_displaymap').style.display='none' : '';
    document.getElementById('tx_map_map_control_closemap') ? document.getElementById('tx_map_map_control_closemap').style.display='inline' : '';
    document.getElementById('tx_map_map_control_dogeocoding') ? document.getElementById('tx_map_map_control_dogeocoding').style.display='inline' : '';
    document.getElementById('tx_map_map_control_fetchzoom') ? document.getElementById('tx_map_map_control_fetchzoom').style.display='inline' : '';
}

/**
 * Disable options
 */
var disableOptions = function () {
    document.getElementById('tx_map_map_control_displaymap') ? document.getElementById('tx_map_map_control_displaymap').style.display='inline' : '';
    document.getElementById('tx_map_map_control_closemap') ? document.getElementById('tx_map_map_control_closemap').style.display='none' : '';
    document.getElementById('tx_map_map_control_dogeocoding') ? document.getElementById('tx_map_map_control_dogeocoding').style.display='none' : '';
    document.getElementById('tx_map_map_control_fetchzoom') ? document.getElementById('tx_map_map_control_fetchzoom').style.display='none' : '';
}