<?php
//********************************************
//	Automotive Categories Widget
//***********************************************************

echo $before_widget;
if ( $title )
	echo $before_title . $title . $after_title;

$cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h, 'hide_empty' => 0);

if ( $d ) {
	$cat_args['show_option_none'] = __('Select Category', 'listings');

	wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) );
	?>

	<script type='text/javascript'>
		/* <![CDATA[ */
        var dropdown = document.getElementById("cat");
        function onCatChange() {
            if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
                location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
            }
        }
        dropdown.onchange = onCatChange;
		/* ]]> */
	</script>

	<?php
} else {
	?>
	<ul>
		<?php
		$cat_args['title_li'] = '';

		wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) ); ?>
	</ul>
	<?php
}

echo $after_widget;