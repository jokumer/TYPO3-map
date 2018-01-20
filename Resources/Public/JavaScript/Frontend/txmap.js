/**
 * Initialize Google Maps
 */
window.initializeGoogleMap = function () {
    txmap.googleMap.init();
};

/**
 * Initialize Geolocate
 */
$(document).ready(function() {
    txmap.geolocation.init();
});

/**
 * tx_map
 * 
 * Contains functions for GoogleMap, Geolocation and LoadOverlay
 */
var txmap  = (function() {
    return {
        /**
         * Google Map
         */
        googleMap : (function() {
            var identifier = {
                canvas : document.getElementById('tx_map_map_canvas'),
                setup : document.getElementById('tx_map_map_setup'),
                menu : document.getElementById('tx_map_map_menu')
            };
            return {
                /**
                 * Init
                 */
                init : function () {
                    if (identifier.canvas) {
                        txmap.googleMap.map = null;
                        txmap.googleMap.overlays_markers = [];
                        txmap.googleMap.overlays_registeredkeys = ['markers'];
                        txmap.googleMap.options = {
                            //center: '55.0, 11.0', // must at least be google.maps.LatLng() object
                            scroll: false,
                            types: 'roadmap',
                            zoom: 5
                        };
                        txmap.googleMap.getOptions(identifier.canvas);
                        txmap.googleMap.getMap();
                        txmap.googleMap.addListeners();
                        txmap.googleMap.getOverlays();
                    } else {
                        // No map element in HTML
                    }
                },

                /**
                 * Get options from element data attributes
                 *
                 * @param element
                 */
                getOptions : function (element) {
                    if (element) {
                        txmap.googleMap.options.center = txmap.googleMap.getOptionCenter($(element).data('optionsCenter'));
                        txmap.googleMap.options.scroll = txmap.googleMap.getOptionScroll($(element).data('optionsScroll'));
                        txmap.googleMap.options.typeId = txmap.googleMap.getOptionTypeId($(element).data('optionsTypes'));
                        txmap.googleMap.options.zoom = txmap.googleMap.getOptionZoom($(element).data('optionsZoom'));
                    }
                },

                /**
                 * Get map
                 */
                getMap : function () {
                    var options = {
                        center: txmap.googleMap.options.center,
                        scrollwheel: txmap.googleMap.options.scroll,
                        mapTypeId: txmap.googleMap.options.typeId,
                        zoom: txmap.googleMap.options.zoom
                    };
                    // Create the map and reference the div#map-canvas container
                    txmap.googleMap.map = new google.maps.Map(identifier.canvas, options);
                },

                /**
                 * Add listeners
                 */
                addListeners : function () {
                    // Enable scrolling on click event on the map
                    txmap.googleMap.map.addListener('click', txmap.googleMap.enableScrollingWithMouseWheel);
                    // Enable scrolling on drag event on the map
                    txmap.googleMap.map.addListener('drag', txmap.googleMap.enableScrollingWithMouseWheel);
                    // Disable scrolling on mouseout from the map
                    txmap.googleMap.map.addListener('mouseout', txmap.googleMap.disableScrollingWithMouseWheel);
                },

                /**
                 * Get overlays
                 */
                getOverlays : function () {
                    // Fetch default overlay data from setup form and add overlays to map
                    txmap.googleMap.fetchDefaultOverlayDataFromForm(identifier.setup, function (overlayData) {
                        txmap.googleMap.addOverlays(overlayData);
                    })
                },

                /**
                 * Get option center
                 * Expects commasepareted lat,lng values
                 * Returns google.maps.LatLng object
                 *
                 * @param data
                 */
                getOptionCenter : function (data) {
                    if (typeof data !== 'undefined') {
                        var arr = data.split(',');
                        if (arr.length > 0) {
                            var lat = arr.splice(0, 1).join('');
                            var lng = arr.splice(0, 2).join('');
                            return new google.maps.LatLng(lat, lng);
                        }
                    }
                },

                /**
                 * Get option scroll
                 * Enable or disable scrollWheel zoom function
                 *
                 * @param data
                 */
                getOptionScroll : function (data) {
                    return data;
                },

                /**
                 * Get option type id
                 * Expects commasepareted type values
                 * Returns first entry from commaseparated list of given types, as used for type controls
                 *
                 * @param data
                 */
                getOptionTypeId : function (data) {
                    if (typeof data !== 'undefined') {
                        var arr = data.split(',');
                        if (arr.length > 0) {
                            return arr.splice(0, 1).join('');
                        }
                    }
                },

                /**
                 * Get option zoom
                 *
                 * @param data
                 */
                getOptionZoom : function (data) {
                    return parseInt(data);
                },

                /**
                 * Fetch default overlay data from form
                 *
                 * @param formIdentifier string
                 * @param callback function
                 */
                fetchDefaultOverlayDataFromForm : function (formIdentifier, callback) {
                    $.post(
                        $(formIdentifier).attr('action'),
                        $(formIdentifier).serialize(),
                        function(overlayData, status) {
                            // Check status: success, error, etc
                            callback(overlayData);
                        },
                        'json'
                    );
                },

                /**
                 * Fetch requested overlay data for infowindow by overlay identifier
                 *
                 * @param callback function
                 * @param overlayIdentifier string
                 */
                fetchRequestedOverlayDataForInfowindowByOverlayIdentifier : function (callback, overlayIdentifier) {
                    $.post(
                        $(identifier.setup).attr('action'),
                        {'tx_map_map' : {
                            'overlay' : 'infowindow',
                            'identifier' : overlayIdentifier
                        }
                        },
                        function(overlayData, status) {
                            // Check status: success, error, etc
                            callback(overlayData);
                        },
                        'html'
                    );
                },

                /**
                 * Refresh map overlays
                 */
                refreshMapOverlays : function () {
                    // Clear map oberlays
                    txmap.googleMap.clearMapOverlays();
                    // Fetch default overlay data from menu form and add overlays to map
                    txmap.googleMap.fetchDefaultOverlayDataFromForm(identifier.menu, function (overlayData) {
                        txmap.googleMap.addOverlays(overlayData);
                    })
                },

                /**
                 * Add Overlays
                 *
                 * @param overlayData object
                 */
                addOverlays : function (overlayData) {
                    $.each(overlayData, function (overlayDataKey, overlayDataValues) {
                        if (txmap.googleMap.overlays_registeredkeys.indexOf(overlayDataKey) != -1) {
                            switch (overlayDataKey) {
                                case 'markers':
                                    txmap.googleMap.addOverlayMarkers(overlayDataValues);
                                    break;
                            }
                        }
                    });
                },

                /**
                 * Add overlay markers
                 *
                 * @param markerData object
                 */
                addOverlayMarkers : function (markerData) {
                    // Initialize empty info window
                    var infowindow = new google.maps.InfoWindow({content: ''});
                    // Add marker
                    $.each(markerData, function (index, markerDataValues) {
                        markerLatLng = new google.maps.LatLng(markerDataValues.latitude, markerDataValues.longitude);
                        var icon = txmap.googleMap.getIcon(markerDataValues.icon);
                        var marker = txmap.googleMap.getMarker(markerLatLng, icon, markerDataValues.title);
                        google.maps.event.addListener(marker, 'click', function()
                        {
                            // Fetch requested overlay data for infowindow by identifier and add single infowindow to map
                            txmap.googleMap.fetchRequestedOverlayDataForInfowindowByOverlayIdentifier(function (overlayData) {
                                infowindow.setContent(overlayData);
                                infowindow.open(txmap.googleMap.map, marker);
                            }, markerDataValues.identifier);
                        });
                        // Push markers, not currently used but good to keep track of markers
                        txmap.googleMap.overlays_markers.push(marker);
                    });
                },

                /**
                 * Get icon
                 *
                 * @param iconPath string
                 */
                getIcon : function (iconPath) {
                    var icon = {
                        url: window.location.origin + '/' + iconPath,
                        anchor: new google.maps.Point(16,16) // center if icon size 30x30
                    };
                    return icon;
                },

                /**
                 * Get marker
                 *
                 * @param position google.maps.LatLng()
                 * @param icon google.maps.getIcon()
                 * @param title string
                 */
                getMarker : function (position, icon, title) {
                    var marker = new google.maps.Marker({
                        map: txmap.googleMap.map,
                        position: markerLatLng,
                        icon: icon,
                        title: title
                    });
                    return marker;
                },

                /**
                 * Position marker
                 *
                 * @param position google.maps.LatLng()
                 * @param marker google.maps.Marker()
                 */
                positionMarker : function (position, marker) {
                    if (position !== null && typeof position !== 'undefined' && marker !== null && typeof marker !== 'undefined') {
                        marker.setPosition(position);
                    }
                },

                /**
                 * Binds a map marker and infoWindow together on click
                 *
                 * @param marker google.maps.Marker()
                 * @param infowindow google.maps.InfoWindow()
                 * @param content string
                 */
                bindInfoWindow : function (marker, infowindow, content) {
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.setContent(html);
                        infowindow.open(txmap.googleMap.map, marker);
                    });
                },

                /**
                 * Clear map overlays
                 */
                clearMapOverlays : function () {
                    // Clear markers
                    while(txmap.googleMap.overlays_markers.length) {
                        txmap.googleMap.overlays_markers.pop().setMap(null);
                    }
                    txmap.googleMap.overlays_markers.length = 0;
                    //show('load');
                },

                /**
                 * Enable scrolling for map zoom
                 */
                enableScrollingWithMouseWheel : function () {
                    txmap.googleMap.map.setOptions({scrollwheel: true});
                },

                /**
                 * Disable scrolling for map zoom
                 */
                disableScrollingWithMouseWheel : function () {
                    txmap.googleMap.map.setOptions({scrollwheel: false});
                },

                /**
                 * Open info window
                 *
                 * @param content string
                 * @param position google.maps.LatLng()
                 */
                openInfoWindow : function (content, position) {
                    if (txmap.googleMap.map !== null && typeof txmap.googleMap.map !== 'undefined') {
                        var infoWindow = new google.maps.InfoWindow({content: content, position: position});
                        infoWindow.open(txmap.googleMap.map);
                    }
                }
            }
        })(),

        /**
         * Geolocation
         */
        geolocation : (function() {
            // HTML identifiers
            var identifier = {
                //dataGeolocateItem : 'data-geolocate-item',
                dataGeolocateSetupName : 'data-geolocate-setup-name',
                //dataGeolocateSetupItemLatitude : 'data-geolocate-setup-item-latitude',
                //dataGeolocateSetupItemLongitude : 'data-geolocate-setup-item-longitude',
                //dataGeolocateSetupPositionMarkerIconPath : 'data-geolocate-setup-position-marker-icon-path',
                //dataGeolocateSetupPositionTrack : 'data-geolocate-setup-position-track',
                //dataGeolocateSetupTextErrorBrowser : 'data-geolocate-setup-text-error-browser',
                //dataGeolocateSetupTextErrorGeolocation : 'data-geolocate-setup-text-error-geolocation',
                //dataGeolocateSetupTextErrorGeolocationWatch : 'data-geolocate-setup-text-error-geolocation-watch',
            };
            // Setups from form with data attributes
            var geolocateSetups = {};
            // Google Maps marker for geolocation position
            var geolocationMapMarker = null;
            return {
                /**
                 * Init
                 */
                init : function() {
                    txmap.geolocation.getGeolocateSetups();
                    if (geolocateSetups.length > 0) {
                        txmap.geolocation.geolocate();
                    }
                },

                /**
                 * Get geolocate setups
                 */
                getGeolocateSetups : function() {
                    geolocateSetups = $('[' + identifier.dataGeolocateSetupName + ']');
                },

                /**
                 * Geolocate
                 */
                geolocate : function() {
                    if (geolocateSetups.length > 0) {
                        var geolocateSetupTextErrorBrowser = $(geolocateSetups[0]).data('geolocateSetupTextErrorBrowser');
                        var geolocateSetupTextErrorGeolocation = $(geolocateSetups[0]).data('geolocateSetupTextErrorGeolocation');
                        var geolocateSetupTextErrorGeolocationWatch = $(geolocateSetups[0]).data('geolocateSetupTextErrorGeolocationWatch');
                        if (navigator.geolocation) {
                            // Get current position
                            navigator.geolocation.getCurrentPosition(
                                // Succcess
                                function(position) {
                                    for (index = 0; index < geolocateSetups.length; ++index) {
                                        var geolocateItemIdentifier = $(geolocateSetups[index]).data('geolocateItem');
                                        var geolocateItem = document.getElementById(geolocateItemIdentifier);
                                        var geolocateSetup = geolocateSetups[index];
                                        var geolocateSetupName = $(geolocateSetups[index]).data('geolocateSetupName');
                                        var geolocateSetupPositionTrack = $(geolocateSetup).data('geolocateSetupPositionTrack');
                                        switch(geolocateSetupName) {
                                            case 'map':
                                                // Geolocate map, add marker
                                                txmap.geolocation.geolocateMap(position, self, geolocateItem, geolocateSetup);
                                                // Watch position
                                                if (geolocateSetupPositionTrack) {
                                                    navigator.geolocation.watchPosition(
                                                        // Success
                                                        function(positionWatched) {
                                                            txmap.geolocation.geolocateMapTrack(positionWatched, self, geolocateItem, geolocateSetup);
                                                        },
                                                        // Error
                                                        function() {
                                                            txmap.geolocation.geolocationError(geolocateSetupTextErrorGeolocationWatch);
                                                        }
                                                    );
                                                }
                                                break;
                                            case 'location_list':
                                                // Geolocate location list
                                                txmap.geolocation.geolocateLocationList(position, self, geolocateItem, geolocateSetup);
                                                // Watch position
                                                if (geolocateSetupPositionTrack) {
                                                    navigator.geolocation.watchPosition(
                                                        // Success
                                                        function(positionWatched) {
                                                            txmap.geolocation.geolocateLocationListTrack(positionWatched, self, geolocateItem, geolocateSetup);
                                                        },
                                                        // Error
                                                        function() {
                                                            txmap.geolocation.geolocationError(geolocateSetupTextErrorGeolocationWatch);
                                                        }
                                                    );
                                                }
                                                break;
                                        }
                                    }
                                },
                                // Error
                                function() {
                                    txmap.geolocation.geolocationError(geolocateSetupTextErrorGeolocation);
                                },
                                // Options (optional PositionOptions object)
                                function() {
                                    txmap.geolocation.geolocationOptions();
                                }
                            );
                        } else {
                            txmap.geolocation.geolocationError(geolocateSetupTextErrorBrowser);
                        }
                    }
                },

                /**
                 * Geolocate map, add marker
                 *
                 * @param position
                 * @param self
                 * @param geolocateItem
                 * @param geolocateSetup
                 */
                geolocateMap : function(position, self, geolocateItem, geolocateSetup) {
                    if (typeof(position) !== 'undefined' && (txmap.googleMap.map !== null && typeof txmap.googleMap.map !== 'undefined')) {
                        var coordinates = position.coords;
                        var geolocateSetupLatitudeIdentifier = $(geolocateSetup).data('geolocateSetupItemLatitude');
                        var geolocateSetupLatitudeItem = document.getElementById(geolocateSetupLatitudeIdentifier);
                        var geolocateSetupLongitudeIdentifier = $(geolocateSetup).data('geolocateSetupItemLongitude');
                        var geolocateSetupLongitudeItem = document.getElementById(geolocateSetupLongitudeIdentifier);
                        var geolocateSetupPositionMarkerIconPath = $(geolocateSetup).data('geolocateSetupPositionMarkerIconPath');
                        var geolocateSetupTextMarkerTitle = $(geolocateSetup).data('geolocateSetupTextMarkerTitle');
                        // Rewrite setup form values
                        if (coordinates.latitude !== 0 && coordinates.longitude !== 0) {
                            $(geolocateSetupLatitudeItem).val(coordinates.latitude);
                            $(geolocateSetupLongitudeItem).val(coordinates.longitude);
                        }
                        // Loader
                        $(document)
                            .ajaxStart(function () {
                                $(geolocateItem).loadOverlayStart();
                            })
                            .ajaxStop(function () {
                                $(geolocateItem).loadOverlayStop();
                            });
                        // Ajax request
                        $.post(
                            $(geolocateSetup).attr('action'),
                            $(geolocateSetup).serialize(),
                            function(data, status) { },
                            'json'
                        )
                            .done(function(data) {
                                $(geolocateItem).loadOverlayStop();
                                if (data.geoLocation && data.nearestLocation) {
                                    var geoLocation = new google.maps.LatLng (data.geoLocation.latitude, data.geoLocation.longitude);
                                    var geoLocationMapMarkerIcon = txmap.googleMap.getIcon(geolocateSetupPositionMarkerIconPath);
                                    var geolocationMapMarker = new google.maps.Marker({
                                        map: txmap.googleMap.map,
                                        position: geoLocation,
                                        icon: geoLocationMapMarkerIcon,
                                        title: geolocateSetupTextMarkerTitle
                                    });
                                    var nearestLocation = new google.maps.LatLng (data.nearestLocation.latitude, data.nearestLocation.longitude);
                                    var LatLngList = new Array (geoLocation, nearestLocation);
                                    var bounds = new google.maps.LatLngBounds ();
                                    for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
                                        bounds.extend (LatLngList[i]);
                                    }
                                    // Fit bounds / pan map ..
                                    txmap.googleMap.map.fitBounds (bounds);
                                    // Keep marker in global scopes
                                    txmap.googleMap.overlays_markers.push(geolocationMapMarker);
                                }
                            })
                            .fail(function(data) {
                                console.warn('ERROR#1486553585(txmap.geolocation.geolocateMap>post)');
                            })
                    }
                },

                /**
                 * Geolocate location list
                 *
                 * @param position
                 * @param self
                 * @param geolocateItem
                 * @param geolocateSetup
                 */
                geolocateLocationList : function(position, self, geolocateItem, geolocateSetup) {
                    if (typeof(position) !== 'undefined') {
                        var coordinates = position.coords;
                        var geolocateSetupLatitudeIdentifier = $(geolocateSetup).data('geolocateSetupLatitude');
                        var geolocateSetupLatitudeItem = document.getElementById(geolocateSetupLatitudeIdentifier);
                        var geolocateSetupLongitudeIdentifier = $(geolocateSetup).data('geolocateSetupLongitude');
                        var geolocateSetupLongitudeItem = document.getElementById(geolocateSetupLongitudeIdentifier);
                        // Rewrite setup form values
                        if (coordinates.latitude !== 0 && coordinates.longitude !== 0) {
                            $(geolocateSetupLatitudeItem).val(coordinates.latitude);
                            $(geolocateSetupLongitudeItem).val(coordinates.longitude);
                        }
                        // Loader
                        $(document)
                            .ajaxStart(function () {
                                $(geolocateItem).loadOverlayStart();
                            })
                            .ajaxStop(function () {
                                $(geolocateItem).loadOverlayStop();
                            });
                        // Ajax request
                        $.post(
                            $(geolocateSetup).attr('action'),
                            $(geolocateSetup).serialize(),
                            function(data, status) { },
                            'html'
                        )
                            .done(function(data) {
                                $(geolocateItem).loadOverlayStop();
                                $(geolocateItem).replaceWith(data);
                            })
                            .fail(function(data) {
                                console.warn('ERROR#1486553584(txmap.geolocation.geolocateLocationList>post)');
                            })
                    }
                },

                /**
                 * Geolocate map tracking
                 *
                 * @param position
                 * @param self
                 * @param geolocateItem
                 * @param geolocateSetup
                 */
                geolocateMapTrack : function (position, self, geolocateItem, geolocateSetup) {
                    if (txmap.googleMap.map !== null && typeof txmap.googleMap.map !== 'undefined'
                        && geolocationMapMarker !== null && typeof geolocationMapMarker !== 'undefined'
                        && position.coords.latitude !== 0 && position.coords.latitude !== 'undefined'
                        && position.coords.longitude !== 0 && position.coords.longitude !== 'undefined'
                    ) {
                        var newMarkerPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        txmap.googleMap.positionMarker(newMarkerPosition, geolocationMapMarker);
                    } else {
                        console.warn('WARNING#1486553583(txmap.geolocation.geolocateLocationListTrack) No support for tracking location list yet.');
                    }
                },

                /**
                 * Geolocate location list tracking
                 *
                 * @param position
                 * @param self
                 * @param geolocateItem
                 * @param geolocateSetup
                 * @todo: adjust location list on position change?
                 */
                geolocateLocationListTrack : function (position, self, geolocateItem, geolocateSetup) {
                    console.warn('WARNING#1486553582(txmap.geolocation.geolocateLocationListTrack) No support for tracking location list yet.');
                },

                /**
                 * Geolocate clear watch / stop tracking
                 *
                 * @param watchId
                 * @todo: add listener
                 */
                geolocateClearWatch : function (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                },

                /**
                 * Geolocation error
                 *
                 * @param error
                 */
                geolocationError : function (errorMessage) {
                    console.warn('ERROR#1486553581(txmap.geolocation.geolocationError) ' + errorMessage);
                    if (txmap.googleMap.map !== null && typeof txmap.googleMap.map !== 'undefined') {
                        txmap.googleMap.openInfoWindow(errorMessage, txmap.googleMap.map.getCenter());
                    }
                },

                /**
                 * Geolocation options
                 *
                 * @param error
                 */
                geolocationOptions : function () {
                    return {}; // optional PositionOptions object
                }
            };
        })(),

        /**
         * Load overlay (jQuery)
         * 
         * @author Mike A. Leonetti (loadover)
         */
        loadOverlay : (function( $ ){
            /**
             * Load overlay start
             *
             * @returns {$}
             */
            $.fn.loadOverlayStart = function() {
                if( !this.length )
                    return( this );
                if( this.data('loadOver') )
                    return( this );
                var loaddiv, loadtimer, loadframe = 0, overlaydiv, obj = this,
                    _startload = function()
                    {
                        var append;
                        var properties = {};
                        if( obj.height() )
                        {
                            properties['position'] = 'absolute';
                            properties['width'] = obj.outerWidth();
                            append = $('body');
                            properties['top'] = obj.offset().top;
                            properties['left'] = obj.offset().left;
                            properties['height'] = obj.outerHeight();
                        } else {
                            properties['height']=100;
                            properties['position']='relative';
                            properties['width'] = '100%';
                            append = obj;
                            properties['top'] = 0;
                            properties['left'] = 0;
                        }
                        loaddiv = $('<div class="tx-map-loadOverOverlay"><div class="tx-map-loadOver"><div class="tx-map-loadOverDiv"></div></div></div>');
                        loaddiv.css(properties);
                        loaddiv.appendTo( append );
                        clearInterval( loadtimer );
                        loaddiv.show();
                        loadtimer = setInterval( _animate_load, 66 );
                    },
                    _animate_load = function()
                    {
                        if( !loaddiv.length || !loaddiv.is(':visible') )
                        {
                            _stopload();
                            return;
                        }
                        loadframe = loadframe+1;
                        if( loadframe>11 )
                            loadframe = 0;
                        //loaddiv.find('.loadOverDiv').css('top', (loadframe * -40) + 'px');
                    },
                    _stopload = function()
                    {
                        clearInterval( loadtimer );
                        loaddiv.remove();
                        obj.data('loadOver', false);
                        obj.data('stopFunc', undefined);
                    };
                _startload();
                this
                    .data('stopFunc',_stopload)
                    .data('loadOver', true);
                return( this );
            };

            /**
             * Load overlay stop
             *
             * @returns {$}
             */
            $.fn.loadOverlayStop = function() {
                if( !this.length )
                    return( this );
                var stopfunc = this.data('stopFunc');
                if( !stopfunc )
                    return( this );
                stopfunc();
                return( this );
            };
        })( jQuery )
    }
})();
