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
			'review_id'		=> false, // Can take an array or a single id
			'user_id'		=> false, // Review author. Array or single id
			'reviewed_group_id'	=> false, // Array or single id
			'reviewed_user_id'	=> false  // Array or single id
		);
		
		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );
		
		foreach( $r as $key => $value ) {
			if ( $value ) {
				$this->$key = $value;
			}
		}
		
		// If args are provided, set up the reviews
		if ( $review_id || $user_id || $reviewed_group_id || $reviewed_user_id ) {
			$this->_load_reviews();
		}
	}
	
	function _load_reviews() {		
		$wp_query_args = array(
			'post_type'	=> $this->post_type_name,
			'post_status'	=> 'publish'
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
					'field'		=> 'slug',
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
					'field'		=> 'slug',
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
}

//$ok = new BP_Group_Reviews_Review( array( 'user_id' => array( 1,3,5 ), 'reviewed_user_id' => 3, 'reviewed_group_id' => array( 3,5 ) ) );

?>