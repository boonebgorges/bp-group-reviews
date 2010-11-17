<?php
/*
Plugin Name: BP Group Reviews
Author: boonebgorges
Author URL: http://boonebgorges.com
Description: Adds a review/rating section to BuddyPress groups. As seen on the buddypress.org/extend/plugins
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
add_action( 'bp_include', 'bpgr_loader' );



?>