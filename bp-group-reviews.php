<?php

if ( !class_exists( 'BP_Group_Reviews' ) ) :

class BP_Group_Reviews {
	function bp_group_reviews() {
		$this->construct();
	}
	
	function __construct() {
		$this->includes();
		
		add_action( 'bp_setup_globals', array( $this, 'setup_globals' ) );
		add_action( 'groups_setup_nav', array( $this, 'setup_current_group_globals' ) );
		add_action( 'wp_print_scripts', array( $this, 'load_js' ) );
		add_action( 'wp_print_styles', array( $this, 'load_styles' ) );
	}
	
	function includes() {
		require_once( BP_GROUP_REVIEWS_DIR . 'includes/classes.php' );
		require_once( BP_GROUP_REVIEWS_DIR . 'includes/templatetags.php' );
	}
	
	function load_js() {
		wp_register_script( 'bp-group-reviews', BP_GROUP_REVIEWS_URL . 'js/group-reviews.js' );
		wp_enqueue_script( 'bp-group-reviews' );	
		
		$params = array(
			'star' => bpgr_get_star_img(),
			'star_half' => bpgr_get_star_half_img(),
			'star_off' => bpgr_get_star_off_img()
		);
		wp_localize_script( 'bp-group-reviews', 'bpgr', $params );	
	}
	
	function load_styles() {
		wp_register_style( 'bp-group-reviews', BP_GROUP_REVIEWS_URL . 'css/group-reviews.css' );
		wp_enqueue_style( 'bp-group-reviews' );
	}
	
	
	function setup_globals() {
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
	
	function setup_current_group_globals() {
		global $bp;
			
		if ( isset( $bp->groups->current_group->id ) ) {
			$rating = groups_get_groupmeta( $bp->groups->current_group->id, 'bpgr_rating' );
			
			if ( !empty( $rating ) ) {
				$bp->groups->current_group->rating_avg_score = $rating['avg_score'];
				$bp->groups->current_group->rating_number = $rating['number'];
				$bp->groups->current_group->rating_raw_score = $rating['raw_score'];
			}	
		}
	}
}

endif;

$bp_group_reviews = new BP_Group_Reviews;



?>