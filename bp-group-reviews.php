<?php

if ( !class_exists( 'BP_Group_Reviews' ) ) :

class BP_Group_Reviews {
	function bp_group_reviews() {
		$this->construct();
	}
	
	function __construct() {
		if ( !bp_is_active( 'groups' ) )
			return false;
	
		$this->includes();
		
		add_action( 'bp_init', array( $this, 'maybe_update' ) );
		
		add_action( 'bp_setup_globals', array( $this, 'setup_globals' ) );
		add_action( 'groups_setup_nav', array( $this, 'setup_current_group_globals' ) );
		add_action( 'wp_print_scripts', array( $this, 'load_js' ) );
		add_action( 'wp_head', array( $this, 'maybe_previous_data' ), 999 );		
		add_action( 'wp_print_styles', array( $this, 'load_styles' ) );
		add_action( 'wp', array( $this, 'grab_cookie' ) );
		add_filter( 'bp_has_activities', array( $this, 'activities_template_data' ) );
		
	}
	
	function includes() {
		require_once( BP_GROUP_REVIEWS_DIR . 'includes/classes.php' );
		require_once( BP_GROUP_REVIEWS_DIR . 'includes/templatetags.php' );
	}
	
	function maybe_update() {
		if ( get_option( 'bp_group_reviews_version' ) < BP_GROUP_REVIEWS_VER ) {
			require_once( BP_GROUP_REVIEWS_DIR . 'includes/upgrade.php' );
		}
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
	
		$bp->group_reviews->slug = BP_GROUP_REVIEWS_SLUG;
	
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
	
	/**
	 * Fetch review data when the activity stream is called. Done in one fell swoop to minimize
	 * database queries. Todo: remove if an extras parameter is added to bp_has_activities()
	 * in BP core
	 *
	 * @package BP Group Reviews
	 * @uses $activities_template The global activity stream object
	 * @param bool $has_activities Must be returned in order for content to render
	 * @return bool $has_activities
	 */
	function activities_template_data( $has_activities ) {
		global $activities_template, $wpdb, $bp;
		
		$activity_ids = array();
		foreach( $activities_template->activities as $activity ) {
			$activity_ids[] = $activity->id;
		}
		$activity_ids = implode( ',', $activity_ids );
		
		$sql = apply_filters( 'bpgr_activities_data_sql', $wpdb->prepare( "SELECT activity_id, meta_value AS rating FROM {$bp->activity->table_name_meta} WHERE activity_id IN ({$activity_ids}) AND meta_key = 'bpgr_rating'" ) );
		$ratings_raw = $wpdb->get_results( $sql, ARRAY_A );
		
		// Arrange the results in a properly-keyed array
		$ratings = array();
		foreach( $ratings_raw as $rating ) {
			$id = $rating['activity_id'];
			$ratings[$id] = $rating['rating'];
		}
				
		
		foreach( $activities_template->activities as $key => $activity ) {
			if ( $activity->type != 'review' )
				continue;
			
			$id = $activity->id;
			
			$activities_template->activities[$key]->rating = $ratings[$id];			
		}
		
		return $has_activities;
	}
}

endif;

$bp_group_reviews = new BP_Group_Reviews;



?>