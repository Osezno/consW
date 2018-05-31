<?php
//********************************************
//	Automotive Google Map Widget
//***********************************************************

echo $before_widget;
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title; ?>
	<script>
        jQuery(document).ready( function($) {
            var map;
            var latlng = new google.maps.LatLng(<?php echo (isset($latitude) && !empty($latitude) ? $latitude : "-34.397"); ?>, <?php echo (isset($longitude) && !empty($longitude) ? $longitude : "150.644"); ?>);

            function initialize() {
                var mapOptions = {
                    zoom: <?php echo $zoom; ?>,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.<?php echo (isset($type) && !empty($type) ? strtoupper($type) : "ROADMAP"); ?>
                };
                map = new google.maps.Map(document.getElementById('<?php echo $rand_id; ?>'),
                    mapOptions);

                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
            }

            google.maps.event.addDomListener(window, 'load', initialize);
            $("#<?php echo $rand_id; ?>").height($("#<?php echo $rand_id; ?>").width());
        });
	</script>
	<div id="<?php echo $rand_id; ?>" class='map-canvas'></div>
<?php
echo $after_widget;