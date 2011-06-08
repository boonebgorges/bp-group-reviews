<?php

class BP_Group_Reviews_Review {
	var $review_id;
	var $user_id;
	var $reviewed_group_id;
	var $reviewed_user_id;
	
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
		$defaults = array(
			'review_id'		=> false,
			'user_id'		=> false, // Review author
			'reviewed_group_id'	=> false,
			'reviewed_user_id'	=> false
		);
		
		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );
		
		foreach( $r as $key => $value ) {
			if ( $value ) {
				$this->$key = $value;
			}
		}
	}
}

?>