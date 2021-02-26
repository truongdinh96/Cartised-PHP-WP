jQuery(function($) {

	var typical = {

		ready: function() {

			var $tFTypical = $('.txtFTypical'),

				$tFChange = $tFTypical.filter('.tFChange');			

			if ( $tFChange.length > 0 ) {

			    $tFChange.change(function(e) {

			    	var val = $(this).val();

			    	if ( $(this).is(':checkbox') ) {
			    		val = $(this).prop('checked');
			    	}

			    	if ( $(this).is(':checkbox') ) {

			    		var $input = $(this).closest('.field')
						    	   			.find('.checkbox_field_hidden'),

			    			input_val = $input.val().trim(),

			    			val = $(this).attr('data-checkbox-value').trim();

			    		if ( input_val !== '' ) {

			    			var s = input_val.split(',');

			    			if ( $(this).prop('checked') ) {

			    				val = input_val + ',' + val;

			    			}

			    			else {

			    				var index = s.findIndex(function(e) { return e === val });

			    				if ( index !== -1 ) {

			    					s.splice(index, 1);

			    					if ( s.length === 0 ) {
			    						val = '';
			    					}

			    					else if ( s.length === 1 ) {

			    						val = s[0];

			    					}  

			    					else {
			    						val = s.join(',');
			    					}					

			    				}

			    			}			    			

			    		}			    		

			    		$input.val( val );

			    		
			    	}

			    	else if ( $(this).is(':radio') ) {

			    		var $input = $(this).closest('.field')
						    	   			.find('.rd_field_hidden'),			    		

			    			val = $(this).val().trim();

			    		$input.val( val );

			    	}

			    	else {

			        	$(this).next().val( val );
			        	
			        }

			    });

			}			

		}

	}

	typical.ready();

});