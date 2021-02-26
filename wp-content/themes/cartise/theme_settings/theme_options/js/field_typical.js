jQuery( function($) {



	var $tFTypical = $('.txtFTypical'),

		$tFChange = $tFTypical.filter('.tFChange'),

		$tFText = $tFTypical.filter('.tFText');



	if ( $tFChange.length > 0 ) {

	    $tFChange.change(function(e) {

	        $(this).next().val( $(this).val() );

	    });



	}



	if ( $tFText.length > 0 ) {



	    $('form').on('submit', function(e) {



	    	//e.preventDefault();



	    	$tFText.each(function() {



	    		var $obj = $(this);



	    		$obj.next('input[type=hidden]')

	    			.val( $obj.val() );



	    		//console.log( $obj.next('input[type=hidden]').val() );



	    	});



	    });



	}

    

});