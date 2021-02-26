jQuery(function($) {

	var datepicker = {

		instances : {},
		disableddates : [],

		ready: function() {

			var self = datepicker;				

			$('.datepicker').each(function( index, elem ) {

				var $datepicker = $(this),

					datepicker_values = $datepicker.closest('.field')
												   .find('.datepicker-input')
												   .val(),

					id = $datepicker.attr('id');

				self.instances[ id ] = index;

				if ( self.disableddates[index] === undefined ) {
					self.disableddates[index] = [];
				}		

				if ( datepicker_values !== '' ) {
					self.disableddates[index] = datepicker_values.split(',');
				}		

				$datepicker.datepicker({

					dateFormat: "dd/mm/yy",
					beforeShowDay: self.disableSpecificDates,		
					onSelect: function (dateText, inst) {

						var $field = $datepicker.closest('.field'),
							$datepicker_input = $field.find('.datepicker-input'),							
							datepicker_val = '',
							datepicker_fval = '';

						$datepicker.val('');

						self.disableddates[index] = self.pushDDates( self.disableddates[index], dateText );	

						//console.log( self.disableddates[index] );

						$field.find('.datepicker-tags')
					   		  .append('<div class="tag">'
							   		+  '	<span class="dateText">' + dateText + '</span>'
							   		+  '    <span class="close">x</span>'
							   		+ '</div>');

					   	datepicker_val = self.getDateTextSelected( id );

					   	if ( self.disableddates[index].length > 0 ) {
					   		datepicker_fval = self.disableddates[index][0];
					   	}

					   	$datepicker_input.val( datepicker_val );

					   	self.updateFirstDDateInput( $field, self.disableddates[index] );					   	
								   
					},
					onClose: function(dateText, inst) {

						$datepicker.datepicker('refresh');

					}

				});

			});

			$('.datepicker-tags').on('click', '.close', self.closeDtTag);


		},	
		getDateTextSelected: function( id ) {

			var datepicker_val = '',
				instances = datepicker.instances,
				disableddates = datepicker.disableddates[ instances[ id ] ],
				length = disableddates.length;

			for ( var i = 0; i < length; i++) {

				datepicker_val += disableddates[i];

				if ( i < length - 1 ) {
					datepicker_val += ',';
				}
				
			}

			return datepicker_val;

		},
		disableSpecificDates: function(date) {			

			var string = $.datepicker.formatDate('dd/mm/yy', date),
			    id = $(this).attr('id'),
				instance = datepicker.instances[ id ],
				disableddates = datepicker.disableddates[ instance];

    		return [disableddates.indexOf(string) == -1];   		   		

		},
		updateFirstDDateInput: function( $field, disableddates ) {

			var $datepicker_finput = $field.find('.datepicker-finput'),
				datepicker_fval = '';

			if ( disableddates.length > 0 ) {

				datepicker_fval = disableddates[0].toString().split('/');
				$datepicker_finput.val( datepicker_fval[2] + '-' + datepicker_fval[1] + '-' + datepicker_fval[0] );

			}

			else {
				$datepicker_finput.val('');
			}

		},
		pushDDates: function(disableddates, dateText) {

			var dateSplices = dateText.split('/');
				dateCurrent = new Date(dateSplices[2] + '-' + dateSplices[1] + '-' + dateSplices[0]),
				date = null,
				k = 0,
				length = disableddates.length;

			if ( length > 0 ) {

				for ( var i = 0; i < length; i++ ) {	

					dateSplices = disableddates[i].split('/');				
					date = new Date(dateSplices[2] + '-' + dateSplices[1] + '-' + dateSplices[0]);					

					if ( date > dateCurrent ) {

						k = i;
						break;

					}

					else {
						k = i + 1;
					}

				}
				
				disableddates.splice(k, 0, dateText);							

			}

			else {

				disableddates.push( dateText );			

			}

			return disableddates;		

		}, 
		closeDtTag: function(e) {

			var $tag = $(this).closest('.tag'),

				$field = $tag.closest('.field'),

				$datepicker_input = $field.find('.datepicker-input'),

				id = $field.find('.datepicker').attr('id'),

				dateText = $tag.find('.dateText')
							   .text().trim(),

				dateTextSelected = '',

				instance = datepicker.instances[ id ],

				disableddates = datepicker.disableddates[ instance ],

				index = disableddates.findIndex(function(e) { return e === dateText  });

			if ( index !== -1 ) {

				disableddates.splice(index, 1);

				dateTextSelected = datepicker.getDateTextSelected( id );

				$datepicker_input.val( dateTextSelected );

				$tag.remove();

			}			

			datepicker.updateFirstDDateInput( $field, disableddates );


		}
		
	}

	datepicker.ready();

})