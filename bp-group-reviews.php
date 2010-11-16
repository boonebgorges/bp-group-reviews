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
		add_action( 'wp_head', array( $this, 'maybe_previous_data' ), 999 );		
		add_action( 'wp_print_styles', array( $this, 'load_styles' ) );
		add_action( 'wp', array( $this, 'grab_cookie' ) );
		
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
	
	function maybe_previous_data() {
		if ( bpgr_has_previous_data() ) {
		?>
			<script type="text/javascript">
				jQuery(document).ready( function() {
					jQuery("#review-post-form").css('display','block');
				});
			</script>
		<?php
		}
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
	
	function grab_cookie() {
		global $bp;
		
		if ( empty( $bp->group_reviews->previous_data ) && isset( $_COOKIE['bpgr-data'] ) )
			$bp->group_reviews->previous_data = maybe_unserialize( imap_base64 ( $_COOKIE['bpgr-data'] ) );
		
		@setcookie( 'bpgr-data', false, time() - 1000, COOKIEPATH );
	}
}

endif;

$bp_group_reviews = new BP_Group_Reviews;



?>