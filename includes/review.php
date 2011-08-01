<?php

class BP_Group_Reviews_Review {
	var $review_id;
	var $user_id;
	var $reviewed_group_id;
	var $reviewed_user_id;
	
	var $post_type_name;
	var $user_tax_name;
	var $group_tax_name;
	
	var $reviews;
	
	/**
	 * PHP 4 constructor
	 *
	 * @package BP Group Reviews
	 * @since 1.3
	 *
	 * @param mixed $args Args as string or array
	 */
	function bp_group_reviews_schema( $args = false ) {
		$this->__construct( $args );
	}

	/**
	 * PHP 5 constructor
	 *
	 * @package BP Group Reviews
	 * @since 1.3
	 *
	 * @param mixed $args Args as string or array
	 */	
	function __construct( $args = false ) {
		global $bp;
	
		// Get some values out of the $bp global for further use
		foreach( array( 'post_type_name', 'user_tax_name', 'group_tax_name' ) as $name ) {
			if ( isset( $bp->reviews->$name ) ) {
				$this->$name = $bp->reviews->$name;
			}
		}
		
		$defaults = array(
			'review_id'		=> null, // Can take an array or a single id
			'user_id'		=> null, // Review author. Array or single id
			'reviewed_group_id'	=> null, // Array or single id
			'reviewed_user_id'	=> null,  // Array or single id
			'content'		=> '',
			'rating'		=> null,
			'paged'			=> 1,
			'posts_per_page'	=> 10
		);
		
		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );
		
		foreach( $r as $key => $value ) {
			if ( $value ) {
				$this->$key = $value;
			}
		}
	}
	
	/**
	 * Get reviews, based on arguments passed to the constructor
	 *
	 * @package BP Group Reviews
	 * @since 1.3
	 */
	function load_reviews() {		
		$wp_query_args = array(
			'post_type'	   => $this->post_type_name,
			'post_status'	   => 'publish',
			'suppress_filters' => 1,
			'paged'		   => $this->paged,
			'posts_per_page'   => $this->posts_per_page
		);
		
		if ( $this->review_id ) {
			// If review_id is populated, the other fields are ignored
			
			if ( is_array( $this->review_id ) ) {
				// Arrays of review ids must use post__in
				$wp_query_args['post__in'] = $this->review_id;
			} else {
				// Single review
				$wp_query_args['p'] = (int)$this->review_id;
			}
		} else {
			// Use whatever parameters are passed along
			
			// Author
			if ( $this->user_id ) {
				// At the moment, WordPress will only accept multiple author IDs
				// if they are passed as a comma-separated string
				if ( is_array( $this->user_id ) ) {
					$this->user_id = implode( ',', $this->user_id );
				}
				$wp_query_args['author'] = $this->user_id;
			}
			
			// Get ready to tax
			$tax_query = array();
			
			// Reviewed User
			if ( $this->reviewed_user_id ) {
				// Tax queries need an array
				if ( !is_array( $this->reviewed_user_id ) ) {
					$this->reviewed_user_id = array( $this->reviewed_user_id );
				}
				
				$tax_query[] = array(
					'taxonomy'	=> $this->user_tax_name,
					'terms'		=> $this->reviewed_user_id,
					'field'		=> 'name',
					'operator'	=> 'IN'
				);
			}
			
			// Reviewed Group
			if ( $this->reviewed_group_id ) {
				// Tax queries need an array
				if ( !is_array( $this->reviewed_group_id ) ) {
					$this->reviewed_group_id = array( $this->reviewed_group_id );
				}
				
				$tax_query[] = array(
					'taxonomy'	=> $this->group_tax_name,
					'terms'		=> $this->reviewed_group_id,
					'field'		=> 'name',
					'operator'	=> 'IN'
				);
			}
			
			// If we've got taxonomies, add them to the query args
			if ( $tax_query ) {
				$wp_query_args['tax_query'] = $tax_query;
			}
		}
		
		$this->reviews = new WP_Query( $wp_query_args );
	}
	
	function get() {
		$this->load_reviews();
		return $this->reviews;
	}
	
	function have_reviews() {
		return $this->reviews->have_posts();
	}

	function the_review() {
		return $this->reviews->the_post();
	}
	
	function save() {
		if ( !empty( $this->reviewed_group_id ) ) {
			$reviewed_group_names = array();
			foreach ( (array)$this->reviewed_group_id as $gid ) {
				$g = new BP_Groups_Group( $gid );
				$reviewed_group_names[] = $g->name;
			}
			$reviewed_group_names = implode( ', ', $reviewed_group_names );
		}
		
		$reviewed_item_names = isset( $reviewed_group_names ) ? $reviewed_group_names : ''; // todo - other item types
		
		$wp_insert_post_args = array(
			'post_title'	=> sprintf( __( '%1$s reviews %2$s', 'bpgr' ), bp_core_get_user_displayname( $this->user_id ), $reviewed_item_names ),
			'post_content'	=> $this->content,
			'post_author'	=> $this->user_id,
			'post_type'	=> $this->post_type_name,
			'post_status'	=> 'publish'
		);
		
		$post_id = wp_insert_post( $wp_insert_post_args );
		
		if ( $post_id ) {
			// Set the reviewed item taxonomy
			if ( isset( $this->reviewed_group_id ) ) {
				$term_name = $this->reviewed_group_id;
				$tax	   = $this->group_tax_name;
			} else {
				// todo: other types
				return;
			}
			
			wp_set_post_terms( $post_id, $term_name, $tax, true );
			
			// Now, save the rating data
			update_post_meta( $post_id, 'bpgr_rating', $this->rating );
		}
	}
}

function bpgr_test() {
	$ok = new BP_Group_Reviews_Review( array( 'user_id' => array( 1,3,5 ) ) );
	
	if ( $ok->have_reviews() ) :
		while ( $ok->have_reviews() ) : $ok->the_review();
		echo 'yes!';
		endwhile;
	endif;
}
//add_action( 'admin_init', 'bpgr_test' );
?>