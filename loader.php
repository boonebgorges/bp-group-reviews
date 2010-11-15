<?php
/*
Plugin Name: BP Group Reviews
Author: boonebgorges
Author URL: http://boonebgorges.com
Description: Use group activity streams as a review section. As seen on the buddypress.org/extend/plugins
Version: 1.0
*/

if ( !defined( 'BP_GROUP_REVIEWS_SLUG' ) )
	define( 'BP_GROUP_REVIEWS_SLUG', 'reviews' );

if ( !defined( 'BP_GROUP_REVIEWS_DIR' ) )
	define( 'BP_GROUP_REVIEWS_DIR', WP_PLUGIN_DIR . '/bp-group-reviews/' );

if ( !defined( 'BP_GROUP_REVIEWS_URL' ) )
	define( 'BP_GROUP_REVIEWS_URL', WP_PLUGIN_URL . '/bp-group-reviews/' );


function bpgr_loader() {
	require_once( dirname(__FILE__) . '/bp-group-reviews.php' );
}
add_action( 'bp_loaded', 'bpgr_loader' );


function bpgr_setup_globals() {
	global $bp;

	$image_types = array(
		'star',
		'star_half',
		'star_off'
	);
	
	foreach( $image_types as $image_type ) {
		$bp->group_reviews->images[$image_type] = apply_filters( "bpgr-$image_type", BP_GROUP_REVIEWS_URL . 'images/' . $image_type . '.png' );
	}
}
add_action( 'bp_setup_globals', 'bpgr_setup_globals' );

?>