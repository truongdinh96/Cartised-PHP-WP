jQuery(function($) {

	function formatMoney(number, decPlaces, thouSeparator, decSeparator) {
	    var n = number,
	        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
	        decSeparator = decSeparator == undefined ? "." : decSeparator,
	        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
	        sign = n < 0 ? "-" : "",
	        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
	        j = (j = i.length) > 3 ? j % 3 : 0;
	    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
	}

	function validateNumberFormat(e) {

		var $input = $(this).attr('data-numformat') !== undefined ? $(this) : e,
			$numformat = $input.prev('.numformat'),
			numformat = $input.attr('data-numformat').toString().trim(),
			value = parseInt( $input.val() ),
			number = '';

		if ( numformat === 'vietnam' ) {
			number = formatMoney( value, 0, '.', ',');
		}

		else {
			number = formatMoney( value, 0, ',', '.');
		}

		if ( number !== '0' ) {

			$numformat.text( number );

		}

		else {

			$numformat.text('');

		}

	}

	var $textbox = $('.field input[type=text][data-numformat]');

	$textbox.keyup( validateNumberFormat );

	$textbox.each( function() {

		validateNumberFormat( $(this) );

	});		

})