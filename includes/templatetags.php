<?php

function bpgr_get_plugin_rating_html( $rating, $num_ratings = 0 ) {
	global $bp;

	$rating_html = false;

	if ( ! empty($rating) ) {
		$rating_html = '
		<div class="star-holder" title="' . sprintf(_n('(based on %s rating)', '(based on %s ratings)', $num_ratings), number_format_i18n($num_ratings)) . '">';

		$star1 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('1 stars') . '" />';
		$star2 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('2 stars') . '" />';
		$star3 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('3 stars') . '" />';
		$star4 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('4 stars') . '" />';
		$star5 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('5 stars') . '" />';

		if ( $rating <= 7 )
			$star1 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('1 stars') . '" />';
		else if ( $rating >= 20 )
			$star1 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('1 stars') . '" />';

		if ( $rating > 20 && $rating <= 27 )
			$star2 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('2 stars') . '" />';
		else if ( $rating >= 40 )
			$star2 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('2 stars') . '" />';

		if ( $rating > 40 && $rating <= 47 )
			$star3 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('3 stars') . '" />';
		else if ( $rating >= 60 )
			$star3 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('3 stars') . '" />';

		if ( $rating > 60 && $rating <= 67 )
			$star4 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('4 stars') . '" />';
		else if ( $rating >= 80 )
			$star4 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('4 stars') . '" />';

		if ( $rating > 80 && $rating <= 87 )
			$star5 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('5 stars') . '" />';
		else if ( $rating >= 93 )
			$star5 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('5 stars') . '" />';

		$rating_html .= '<div class="star star5">' . $star5 . '</div>';
		$rating_html .= '<div class="star star4">' . $star4 . '</div>';
		$rating_html .= '<div class="star star3">' . $star3 . '</div>';
		$rating_html .= '<div class="star star2">' . $star2 . '</div>';
		$rating_html .= '<div class="star star1">' . $star1 . '</div>';

		$rating_html .= '<span class="rating-num">' . sprintf(_n('(based on %s rating)', '(based on %s ratings)', $num_ratings), number_format_i18n($num_ratings)) . '</span></div>';
	}

	return $rating_html;
}

function bpgr_get_review_rating_html( $rating ) {
	global $bp;

	$star1 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('1 stars') . '" />';
	$star2 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('2 stars') . '" />';
	$star3 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('3 stars') . '" />';
	$star4 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('4 stars') . '" />';
	$star5 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('5 stars') . '" />';

	if ( $rating >= 1 )
		$star1 = '<img src="' .bpgr_get_star_img() . '" alt="' . __('1 stars') . '" />';

	if ( $rating >= 2 )
		$star2 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('2 stars') . '" />';

	if ( $rating >= 3 )
		$star3 = '<img src="' .bpgr_get_star_img() . '" alt="' . __('3 stars') . '" />';

	if ( $rating >= 4 )
		$star4 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('4 stars') . '" />';

	if ( $rating >= 5 )
		$star5 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('5 stars') . '" />';

	return '<span class="rating">' . $star1 . $star2 . $star3 . $star4 . $star5 . '</span>';
}

function bpgr_is_group_reviews() {
	global $bp;

	return ( !empty( $bp->groups->current_group ) && $bp->current_component == BP_GROUPS_SLUG && $bp->current_action == BP_GROUP_REVIEWS_SLUG );
}

function bpgr_has_written_review() {
	global $bp;

	return false;

	$has_posted = groups_get_groupmeta( $bp->groups->current_group->id, 'posted_review' );

	return in_array( $bp->loggedin_user->id, (array) $has_posted );
}

function bpgr_star_img() {
	echo bpgr_get_star_img();
}
	function bpgr_get_star_img() {
		global $bp;
		
		return apply_filters( 'bpgr_star_img', $bp->group_reviews->images['star'] );
	}


function bpgr_star_half_img() {
	echo bpgr_get_star_half_img();
}
	function bpgr_get_star_half_img() {
		global $bp;
		
		return apply_filters( 'bpgr_star_half_img', $bp->group_reviews->images['star_half'] );
	}
	
function bpgr_star_off_img() {
	echo bpgr_get_star_off_img();
}
	function bpgr_get_star_off_img() {
		global $bp;
		
		return apply_filters( 'bpgr_star_off_img', $bp->group_reviews->images['star_off'] );
	}

?>