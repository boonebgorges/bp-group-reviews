

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

