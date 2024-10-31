(function( $ ) {
	$(document).ready( function() {
		$('.sgp_widgets_select').hide();
		var value = $('input[type="checkbox"]#sgp_widgets_show');
		if( value.is(':checked')) {
			$('.sgp_widgets_select').show();
		}

		// On click automatic show or hide
		value.change( function() {
			if( value.is(':checked')) {
				$('.sgp_widgets_select').show();
			} else {
				$('.sgp_widgets_select').hide();
			}
		});
	});
})(jQuery)