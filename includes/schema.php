<?php

class BP_Group_Reviews_Schema {
	var $post_type_name;
	var $user_tax_name;
	var $group_tax_name;
	
	/**
	 * PHP 4 constructor
	 *
	 * @package BP Group Reviews
	 * @since 1.3
	 */
	function bp_group_reviews_schema() {
		$this->__construct();
	}

	/**
	 * PHP 5 constructor
	 *
	 * @package BP Group Reviews
	 * @since 1.3
	 */	
	function __construct() {
		$this->setup_vars();
		
		add_action( 'init', array( $this, 'register_post_type' ), 1 );
	}
	
	function setup_vars() {
		global $bp;
		
		$this->post_type_name 	= apply_filters( 'bpgr_post_type_name', 'bpgr_review' );
		$this->user_tax_name 	= apply_filters( 'bpgr_user_tax_name', 'bpgr_user' );
		$this->group_tax_name 	= apply_filters( 'bpgr_group_tax_name', 'bpgr_group' );
	
		$bp->reviews->post_type_name 	= $this->post_type_name;
		$bp->reviews->user_tax_name 	= $this->user_tax_name;
		$bp->reviews->group_tax_name 	= $this->group_tax_name;
	}
	
	function register_post_type() {
		// Define the labels to be used by the post type		
		$post_type_labels = array(
			'name' 			=> _x( 'BP Reviews', 'post type general name', 'bpgr' ),
			'singular_name' 	=> _x( 'Review', 'post type singular name', 'bpgr' ),
			'add_new' 		=> _x( 'Add New', 'add new', 'bpgr' ),
			'add_new_item' 		=> __( 'Add New Review', 'bpgr' ),
			'edit_item' 		=> __( 'Edit Review', 'bpgr' ),
			'new_item' 		=> __( 'New Review', 'bpgr' ),
			'view_item' 		=> __( 'View Review', 'bpgr' ),
			'search_items' 		=> __( 'Search Reviews', 'bpgr' ),
			'not_found' 		=>  __( 'No Reviews found', 'bpgr' ),
			'not_found_in_trash' 	=> __( 'No Reviews found in Trash', 'bpgr' ),
			'parent_item_colon' 	=> ''
		);
	
		// Set up the arguments to be used when the post type is registered
		// Only filter this if you are hella smart and/or know what you're doing
		$bpgr_post_type_args = apply_filters( 'bpgr_post_type_args', array(
			'label' 	=> __( 'BP Reviews', 'bpgr' ),
			'labels' 	=> $post_type_labels,
			'public' 	=> false,
			'_builtin' 	=> false,
			'show_ui' 	=> $this->show_cpt_ui(),
			'hierarchical' 	=> false,
			'supports' 	=> array( 'title', 'editor', 'revisions', 'excerpt' ),
			'query_var'	=> true,
			'rewrite' 	=> false // Todo: This bites
		) );
	
		// Register the post type
		register_post_type( $this->post_type_name, $bpgr_post_type_args );
		
		// Now, set up the user and group taxonomies
		// Define the labels
		$user_labels = array(
			'name' 		=> __( 'Reviewed Users', 'bpgr' ),
			'singular_name' => __( 'Reviewed User', 'bpgr' )
		);
		
		// Register the user taxonomy
		register_taxonomy( $this->user_tax_name, array( $this->post_type_name ), array(
			'labels' 	=> $user_labels,
			'hierarchical' 	=> false,
			'show_ui' 	=> true, 
			'query_var' 	=> true,
			'rewrite' 	=> array( 'slug' => 'reviewed_user' ),
		));
		
		// Define the labels
		$group_labels = array(
			'name' 		=> __( 'Reviewed Groups', 'bpgr' ),
			'singular_name' => __( 'Reviewed Group', 'bpgr' )
		);
		
		// Register the user taxonomy
		register_taxonomy( $this->group_tax_name, array( $this->post_type_name ), array(
			'labels' 	=> $group_labels,
			'hierarchical' 	=> false,
			'show_ui' 	=> true, 
			'query_var' 	=> true,
			'rewrite' 	=> array( 'slug' => 'reviewed_user' ),
		));
	}
	
	/**
	 * Show the CPT Dashboard UI to the current user?
	 *
	 * Defaults to is_super_admin(), but is filterable
	 *
	 * @package BP Group Reviews
	 * @since 1.3
	 *
	 * @return bool $show_ui
	 */
	function show_cpt_ui() {
		$show_ui = is_super_admin();
		
		return apply_filters( 'bpgr_show_cpt_ui', $show_ui );
	}
}

$bpgr_schema = new BP_Group_Reviews_Schema;

?>