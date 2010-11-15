<?php

if ( !class_exists( 'BP_Group_Reviews' ) ) :

class BP_Group_Reviews {
	function bp_group_reviews() {
		$this->construct();
	}
	
	function __construct() {
		$this->includes();
		add_action( 'wp_print_scripts', array( $this, 'load_js' ) );
	}
	
	function includes() {
		require_once( BP_GROUP_REVIEWS_DIR . 'includes/classes.php' );
		require_once( BP_GROUP_REVIEWS_DIR . 'includes/templatetags.php' );
	}
	
	function load_js() {
		wp_register_script( 'bp-group-reviews', BP_GROUP_REVIEWS_URL . 'js/group-reviews.js' );
		wp_enqueue_script( 'bp-group-reviews' );	
		
		$params = array(
			'star' => $bp->group_reviews->images['star'],
			'star_half' => $bp->group_reviews->images['star_half'],
			'star_off' => $bp->group_reviews->images['star_off']
		);
		wp_localize_script( 'bp-group-reviews', 'bpgr', $params );	
	}	
	
	
}

endif;

$bp_group_reviews = new BP_Group_Reviews;



?>