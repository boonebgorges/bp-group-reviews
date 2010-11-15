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
		global $bp;
		
		wp_register_script( 'bp-group-reviews', BP_GROUP_REVIEWS_URL . 'js/group-reviews.js' );
		wp_enqueue_script( 'bp-group-reviews' );	
		
		$params = array(
			'star' => bpgr_get_star_img(),
			'star_half' => bpgr_get_star_half_img(),
			'star_off' => bpgr_get_star_off_img()
		);
		wp_localize_script( 'bp-group-reviews', 'bpgr', $params );	
	}	
	
	
}

endif;

$bp_group_reviews = new BP_Group_Reviews;



?>