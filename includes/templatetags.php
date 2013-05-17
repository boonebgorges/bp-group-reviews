<?php


function bpgr_render_review() {
	global $bp;

	// Don't show for groups that have reviews turned off
	if ( !BP_Group_Reviews::current_group_is_available() )
		return;

	// Rendering the full span so you can avoid editing your group-header.php template
	// If you don't like it you can call bpgr_review_html() yourself and unhook this function ;)

	?>
	<span class="rating"><?php echo bpgr_review_html() ?></span>
	<?php
}
add_action( 'bp_group_header_meta', 'bpgr_render_review' );

function bpgr_review_html() {
	global $bp;

	$rating_score  = isset( $bp->groups->current_group->rating_avg_score ) ? $bp->groups->current_group->rating_avg_score : '';

	$rating_number = isset( $bp->groups->current_group->rating_number ) ? $bp->groups->current_group->rating_number : '';

	return bpgr_get_plugin_rating_html( $rating_score, $rating_number );
}


function bpgr_get_group_rating( $group_id = false ) {
	global $bp, $groups_template;

	if ( empty( $group_id ) )
		$group_id = $bp->groups->current_group->id;

	if ( empty( $group_id ) )
		$group_id = $groups_template->group->id;

	if ( empty( $group_id ) )
		return false;

}

function bpgr_get_plugin_rating_html( $rating, $num_ratings = 0 ) {
	global $bp;

	$rating_html = false;

	if ( ! empty($rating) ) {
		$rating_html = '
		<div class="star-holder" title="' . sprintf(_n('(based on %s rating)', '(based on %s ratings)', $num_ratings), number_format_i18n($num_ratings)) . '">';

		$star1 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('1 star') . '" />';
		$star2 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('2 stars') . '" />';
		$star3 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('3 stars') . '" />';
		$star4 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('4 stars') . '" />';
		$star5 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __('5 stars') . '" />';

		if ( $rating <= .5 )
			$star1 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('1 star') . '" />';
		else if ( $rating > .5  )
			$star1 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('1 star') . '" />';

		if ( $rating > 1.25 && $rating <= 1.75 )
			$star2 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('2 stars') . '" />';
		else if ( $rating > 1.75 )
			$star2 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('2 stars') . '" />';

		if ( $rating > 2.25 && $rating <= 2.75 )
			$star3 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('3 stars') . '" />';
		else if ( $rating > 2.75 )
			$star3 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('3 stars') . '" />';

		if ( $rating > 3.25 && $rating <= 3.75 )
			$star4 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('4 stars') . '" />';
		else if ( $rating > 3.75 )
			$star4 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('4 stars') . '" />';

		if ( $rating > 4.25 && $rating <= 4.6 )
			$star5 = '<img src="' . bpgr_get_star_half_img() . '" alt="' . __('5 stars') . '" />';
		else if ( $rating > 4.6 )
			$star5 = '<img src="' . bpgr_get_star_img() . '" alt="' . __('5 stars') . '" />';

		$rating_html .= '<div class="star star1">' . $star1 . '</div>';
		$rating_html .= '<div class="star star2">' . $star2 . '</div>';
		$rating_html .= '<div class="star star3">' . $star3 . '</div>';
		$rating_html .= '<div class="star star4">' . $star4 . '</div>';
		$rating_html .= '<div class="star star5">' . $star5 . '</div>';

		$rating_html .= '<span class="rating-num">' . sprintf(_n('(based on %s rating)', '(based on %s ratings)', $num_ratings), number_format_i18n($num_ratings)) . '</span></div>';
	}

	return $rating_html;
}

function bpgr_get_review_rating_html( $rating ) {
	$star1 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __( '1 star', 'bpgr' ) . '" />';
	$star2 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __( '2 stars', 'bpgr' ) . '" />';
	$star3 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __( '3 stars', 'bpgr' ) . '" />';
	$star4 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __( '4 stars', 'bpgr' ) . '" />';
	$star5 = '<img src="' . bpgr_get_star_off_img() . '" alt="' . __( '5 stars', 'bpgr' ) . '" />';

	if ( $rating >= 1 )
		$star1 = '<img src="' .bpgr_get_star_img() . '" alt="' . __( '1 star', 'bpgr' ) . '" />';

	if ( $rating >= 2 )
		$star2 = '<img src="' . bpgr_get_star_img() . '" alt="' . __( '2 stars', 'bpgr' ) . '" />';

	if ( $rating >= 3 )
		$star3 = '<img src="' .bpgr_get_star_img() . '" alt="' . __( '3 stars', 'bpgr' ) . '" />';

	if ( $rating >= 4 )
		$star4 = '<img src="' . bpgr_get_star_img() . '" alt="' . __( '4 stars', 'bpgr' ) . '" />';

	if ( $rating >= 5 )
		$star5 = '<img src="' . bpgr_get_star_img() . '" alt="' . __( '5 stars', 'bpgr' ) . '" />';

	return '<span class="rating">' . $star1 . $star2 . $star3 . $star4 . $star5 . '</span>';
}

function bpgr_is_group_reviews() {
	global $bp;

	return ( !empty( $bp->groups->current_group ) && $bp->current_component == BP_GROUPS_SLUG && $bp->current_action == $bp->group_reviews->slug );
}

/**
 * Determine whether the logged-in user has left a review for this group.
 *
 * @package BP Group Reviews
 *
 * @return bool
 */
function bpgr_has_written_review() {
	global $bp;

	$has_posted = groups_get_groupmeta( $bp->groups->current_group->id, 'posted_review' );

	return apply_filters( 'bpgr_has_written_reviews', in_array( $bp->loggedin_user->id, (array) $has_posted ) );
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

function bpgr_has_previous_data() {
	global $bp;

	if ( !empty( $bp->group_reviews->previous_data ) )
		return true;

	return false;
}

function bpgr_previous_review() {
	echo bpgr_get_previous_review();
}
	function bpgr_get_previous_review() {
		global $bp;

		return apply_filters( 'bpgr_previous_review', $bp->group_reviews->previous_data['review_content'] );
	}


function bpgr_previous_rating() {
	echo bpgr_get_previous_rating();
}
	function bpgr_get_previous_rating() {
		global $bp;

		return apply_filters( 'bpgr_previous_rating', $bp->group_reviews->previous_data['rating'] );
	}

function bpgr_get_review_rating( $review_id = false ) {
	global $activities_template;

	if ( !$review_id ) {
		$rating = isset( $activities_template->activity->rating ) ? $activities_template->activity->rating : '';
	} else {
		$rating = bp_activity_get_meta( $review_id, 'bpgr_rating' );
	}

	return apply_filters( 'bpgr_review_rating', $rating, $review_id );
}

/**
 * Returns true if users are able to post more than one review per group
 *
 * By default, this function returns false. To allow multiple reviews per group, put the
 * following in your bp-custom.php file:
 * add_filter( 'bpgr_allow_multiple_reviews', create_function( false, "return true;" ) );
 * You can make these permissions more fine grained by hooking a custom function to
 * 'bpgr_allow_multiple_reviews'.
 *
 * @package BP Group Reviews
 *
 * @return bool
 */
function bpgr_allow_multiple_reviews() {
	return apply_filters( 'bpgr_allow_multiple_reviews', false );
}

function bpgr_user_previous_review_args() {
	$args = array(
		'user_id' => bp_loggedin_user_id(),
		'type' => 'review',
		'item_id' => bp_get_group_id(),
		'max' => 1
	);

	return apply_filters( 'bpgr_user_previous_review_args', $args );
}

function bpgr_activity_date_recorded() {
	echo bpgr_get_activity_date_recorded();
}
	function bpgr_get_activity_date_recorded() {
		$date = bp_get_activity_date_recorded();

		$format = get_option( 'date_format' );

		return apply_filters( 'bpgr_get_activity_date_recorded', date( $format, strtotime( $date ) ), $date );
	}

function bpgr_directory_rating() {
	global $groups_template;

	// Don't show if the group has ratings turned off
	if ( empty( $groups_template->group->ratings_enabled ) || 'yes' != $groups_template->group->ratings_enabled )
		return;

	// Don't show if there are no ratings
	if ( empty( $groups_template->group->rating ) || empty( $groups_template->group->rating_count ) )
		return;

	echo bpgr_get_plugin_rating_html( $groups_template->group->rating, $groups_template->group->rating_count );
}
add_action( 'bp_directory_groups_actions', 'bpgr_directory_rating' );

/**
 * Avoids the javascript trick and replaces the read more class
 *
 * For some reason, when clicking on Read more from the review
 * It was also opening the activity in the list of reviews
 * using this filter removed this behavior
 *
 * @param string $review the content of the activity
 * @return string the content of the review (with new class for the read more link)
 * @author imath
 */
function bpgr_current_group_filter_review_content( $review = '' ) {
	$review = str_replace( 'activity-read-more', 'already-rated-read-more', $review );
	return $review;
}

/**
 * This function replaces the activity loop that was used to show the review a user posted before the list of reviews
 *
 * Using the loop at this place will break the html and delete link in case of pagination (acpage=2 or 3 and so on)
 * Using bp activity get and simulating $activities_template with its results avoids the pagination trouble.
 *
 * @uses bp_loggedin_user_id() to get the current user id
 * @uses bp_get_group_id() to get the current group id
 * @uses bp_activity_get() to avoids the loop and get the review the user posted for the current group
 * @uses bp_get_group_name() to get the current group name
 * @uses bpgr_get_activity_date_recorded() to get the date of the review
 * @uses bp_get_activity_content_body() to get the content of the review (activity)
 * @uses bp_get_activity_id() to get the activity id
 * @uses bpgr_get_review_rating() to get the rating for this review
 * @uses bpgr_get_review_rating_html() to build some stars
 * @uses bp_activity_delete_link() to get the delete link for the activity
 * @return string the html output
 * @author imath
 */
function bpgr_current_group_user_review() {
	global $activities_template;

	$args = array(
		'max' => 1,
		'filter' => array(
			'user_id' => bp_loggedin_user_id(),
			'action' => 'review',
			'primary_id' => bp_get_group_id()
		),
		'show_hidden' => true,
	);

	$activities = bp_activity_get( $args );

	if( empty( $activities['activities'] ) )
		return false;

	if( empty( $activities_template  ) )
		$activities_template = new stdClass();

	$activities_template->activities = $activities['activities'];
	$activities_template->activity = $activities['activities'][0];

	add_filter( 'bp_get_activity_content_body', 'bpgr_current_group_filter_review_content', 10, 1 );
	?>
	<div class="already-rated">
		<h5><?php printf( __( "You rated %s on %s.", 'bpgr' ), bp_get_group_name(), bpgr_get_activity_date_recorded() ) ?></h5>

		<blockquote>
			<div id="review-content"><?php echo bp_get_activity_content_body() ?></div>

			<div class="rest-stars">
				<?php echo bpgr_get_review_rating_html( bpgr_get_review_rating( bp_get_activity_id() ) ) ?>
			</div>
		</blockquote>

		<p><?php _e( "To leave another review, you must delete your existing review.", 'bpgr' ) ?> <?php bp_activity_delete_link() ?></p>
	</div>
	<?php
	$activities_template = false;
	remove_filter( 'bp_get_activity_content_body', 'bpgr_current_group_filter_review_content', 10, 1 );
}
