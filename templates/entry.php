<?php /* This template is used by activity-loop.php and AJAX functions to show each activity */ ?>

<?php do_action( 'bp_before_activity_entry' ) ?>

<li>
	<div class="activity-avatar">
		<a href="<?php echo bp_core_get_user_domain( get_the_author_meta( 'ID' ) ) ?>">
			<?php echo bp_core_fetch_avatar( 'item_id=' . get_the_author_meta( 'ID' ) . 'type=thumb&width=30&height=30' ) ?>
		</a>
	</div>

	<div class="activity-content">

		<div class="activity-header">
			<?php echo bpgr_get_review_rating_html( get_post_meta( get_the_ID(), 'bpgr_rating', true ) ) ?> <?php printf( __( 'By %s', 'bpgr' ), bp_core_get_userlink( get_the_author_meta( 'ID' ) ) ) ?> (<?php echo bp_core_time_since( strtotime( get_the_date() ) ) ?>)
		</div>

		<div class="activity-inner">
			<?php the_content() ?>
		</div>

		<?php do_action( 'bp_activity_entry_content' ) ?>

		<div class="activity-meta">
			<?php /* Todo - comments */ /* ?>
			<?php if ( is_user_logged_in() && bpgr_can_comment() && bpgr_is_group_reviews() ) : ?>
				<a href="?ac='<?php get_the_ID() ?>/#ac-form-' . $activities_template->activity->id() ?>" class="acomment-reply" id="acomment-comment-<?php bp_activity_id() ?>"><?php _e( 'Comment', 'buddypress' ) ?> (<span><?php bp_activity_comment_count() ?></span>)</a>
			<?php endif; ?>
			*/ ?>

			<?php do_action( 'bp_activity_entry_meta' ) ?>
		</div>
	</div>

	<?php do_action( 'bp_before_activity_entry_comments' ) ?>

	<?php /* Todo- comments */ /*
	<?php if ( bp_activity_can_comment() && bpgr_is_group_reviews() ) : ?>
		<div class="activity-comments">
			<?php bp_activity_comments() ?>

			<?php if ( is_user_logged_in() ) : ?>
			<form action="<?php bp_activity_comment_form_action() ?>" method="post" id="ac-form-<?php bp_activity_id() ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display() ?>>
				<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=25&height=25' ) ?></div>
				<div class="ac-reply-content">
					<div class="ac-textarea">
						<textarea id="ac-input-<?php bp_activity_id() ?>" class="ac-input" name="ac_input_<?php bp_activity_id() ?>"></textarea>
					</div>
					<input type="submit" name="ac_form_submit" value="<?php _e( 'Post', 'buddypress' ) ?> &rarr;" /> &nbsp; <?php _e( 'or press esc to cancel.', 'buddypress' ) ?>
					<input type="hidden" name="comment_form_id" value="<?php bp_activity_id() ?>" />
				</div>
				<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ) ?>
			</form>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	*/ ?>

	<?php do_action( 'bp_after_activity_entry_comments' ) ?>
</li>

<?php do_action( 'bp_after_activity_entry' ) ?>

