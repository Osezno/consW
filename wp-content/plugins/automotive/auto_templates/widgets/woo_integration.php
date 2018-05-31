<?php

if(isset($post_meta['woocommerce_integration_id']) && !empty($post_meta['woocommerce_integration_id'])){
	echo $before_widget;

	echo (!empty($title) ? $before_title . $title . $after_title : "");

	global $Listing;

	$Listing->woocommerce_integration($post_meta['woocommerce_integration_id']);

	echo $after_widget;
}