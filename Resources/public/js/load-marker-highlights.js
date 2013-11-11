if (window.jQuery) {
    (function ($) {

        var mapId = $('.teneleven-geolocator-map-container').data('map-id');
        var map = window[mapId];
        var mapContainer = window[mapId + '_container'];

        function highlightLocation (item) {

            $('.teneleven-geolocator-list .highlighted').removeClass('highlighted');
            item.addClass('highlighted');

            //if scrollTo is defined, use it
            if ($().scrollTo) {
                $('.teneleven-geolocator-list').scrollTo(item, 500);
            }
        }

        //register event listener for all markers
        for (var key in mapContainer.markers) {
            if (mapContainer.markers.hasOwnProperty(key)) {
                var marker = mapContainer.markers[key];
                google.maps.event.addListener(marker, "click", (function (key) {
                    return function() {
                        var item = $('.teneleven-geolocator-item[data-marker-id=' + key + ']');
                        highlightLocation(item);
                    }
                } (key)));
            }
        }

        $('.teneleven-geolocator-item').click(function () {
            var $this = $(this);
            //close all windows
            for (var key in mapContainer.closable_info_windows) {
                mapContainer.closable_info_windows[key].close();
            }

            if ($this.data('marker-id')) {
                var marker = mapContainer.markers[$this.data('marker-id')];
                var window = mapContainer.info_windows[$this.data('window-id')];
                window.open(map, marker);
            }

            highlightLocation($this);
        });
    }) (jQuery);
}
