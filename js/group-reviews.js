jQuery(document).ready( function() {
	var jq = jQuery;

	jq('.star').mouseover( function() {
		var num = jq(this).attr('id').substr( 4, jq(this).attr('id').length );
		for ( var i=1; i<=num; i++ )
			jq('#star' + i ).attr( 'src', bpgr.star );
	});
	
	jq('div#review-rating').mouseout( function() {
		for ( var i=1; i<=5; i++ )
			jq('#star' + i ).attr( 'src', bpgr.star_off );
	});
	
	jq('.star').click( function() {
		var num = jq(this).attr('id').substr( 4, jq(this).attr('id').length );
		for ( var i=1; i<=5; i++ )
			jq('#star' + i ).attr( 'src', bpgr.star_off );
		for ( var i=1; i<=num; i++ )
			jq('#star' + i ).attr( 'src', bpgr.star );
	
		jq('.star').unbind( 'mouseover' );
		jq('div#review-rating').unbind( 'mouseout' );
	
		jq('input#rating').attr( 'value', num );
	});
	
	jq('.already-rated-read-more a').on('click', function(event) {
		var target = jq(event.target);
		
		var link_id = target.parent().attr('id');
		var a_id = link_id.replace('already-rated-read-more-', '');

		var ar_inner = '.already-rated blockquote div#review-content';

		jq(target).addClass('loading');

		jq.post( ajaxurl, {
			action: 'get_single_activity_content',
			'activity_id': a_id
		},
		function(response) {
			jq(ar_inner).slideUp(300).html(response).slideDown(300);
		});
		
		return false;
	});
});
