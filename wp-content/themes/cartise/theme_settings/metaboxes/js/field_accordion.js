jQuery( function($) {

	var accordion = {

		accordion_index: 0,
		ready: function() {			

			var $metabox_groupbox_fields_sortables = $( ".metabox_groupbox_fields_sortables" );			

			$metabox_groupbox_fields_sortables.sortable({
			      connectWith: ".metabox-field-postbox-item",
			      handle: ".metabox-handle-title",		   
			      placeholder: "metabox-placeholder ui-corner-all"
		    });		   

		    var $accordion_sortables = $( ".metabox-accordion-sortables" );

		    $accordion_sortables.sortable({
			      connectWith: ".metabox-field-postbox-item",
			      handle: ".metabox-handle-title",		   
			      placeholder: "metabox-placeholder ui-corner-all"
		    });

		    try {
		    	this.accordion_index = accordion_index;
		    } catch(e) {}

			$accordion_sortables.on( "sortupdate", this.on_accordion_sortupdate );
			$accordion_sortables.each( function(index, elem) {

				var accordion_title_field = $(elem).find('.accordion-template-item')
							    				   .find('.metabox-accordion-title')
							    				   .find('.title')
							    				   .attr('data-accordion-title-field');

				$(document).on( 'keyup', 
								'input[type=text][id*="' + accordion_title_field + '"]', 
								accordion.on_accordion_field_title_keyup );

			});

			$(document).on( 'click', '.metabox-collapse-button', this.on_collapse_field_box )			   
					   .on( 'click', '.metabox-accordion-remove-button', this.on_accordion_remove_field )
					   .on( 'click', '.metabox-handle-title, .metabox-collapse-button', this.on_accordion_handle_click );

			$('.metabox-accordion-add-button').click( this.on_accordion_add_field );		

			$firstMetabox = $('#advanced-sortables .postbox:eq(0)').find('.metabox_groupbox_fields_sortables:eq(0)')
												   				   .find('.metabox-field-postbox-item:eq(0)');
			
			$firstMetabox.find('h2:eq(0)').click();
			$firstMetabox.removeClass('closed');

		},
		on_accordion_sortupdate: function( event, ui ) { 

			var index = 0,
				id = '',
				name = '',
				$fields_has_name = null;

			// cập nhật lại id && name của các field trong accordion
			$('.metabox-accordion-item:not(.accordion-template-item)').each(function() {

				$fields_has_name = $(this).find('*[name]');
				$fields_has_name.each(function() {

					id = $(this).attr('id'),
					name = $(this).attr('name');

					var s = name.split(/\[\d\]/),
						ac_container = '',
						ac_field = '';

					if ( 2 === s.length ) {

						ac_container = s[0];
						ac_field = s[1];

						ac_field = ac_field.replace('[', '\\[');
						ac_field = ac_field.replace(']', '\\]');

						name = name.replace( new RegExp( ac_container + '\\[\\d\\]' + ac_field, 'ig' ), ac_container + '[' + index + ']' + ac_field );
						name = name.replace(/\\/ig, '');

						$(this).attr('name', name);

						s = id.split(/-\d-/);
						ac_container = s[0];
						ac_field = s[1];

						id = id.replace( new RegExp( ac_container + '-\\d-' + ac_field, 'ig' ), ac_container + '-' + index + '-' + ac_field );

						$(this).attr('id', id);

					}

				});				

				index++;

			});

		},
		on_collapse_field_box: function(e) {

			var $postbox_item = $(this).closest('.metabox-field-postbox-item');

			$postbox_item.siblings().filter(function(index) { 

				return ! $(this).hasClass('closed');	

			}).addClass('closed');			

			$postbox_item.toggleClass('closed');

		},
		on_accordion_add_field: function(e) {

			var $accordion_group_fields = $(this).next('.metabox-accordion-group'),
				$accordion_field = $accordion_group_fields.find('.accordion-template-item').clone(),
				html = '';

			$accordion_field.removeClass('accordion-template-item');
			$(this).next( '.metabox-accordion-sortables' ).sortable('refresh');

			$accordion_field.find('*[id*="-uo-"], *[name*="-uo-"]')
							.each( function(index, elem) {

							 	var $obj = $(elem),
							 		id = $obj.attr('id'),
							 		name = $obj.attr('name');

							 	if ( undefined !== id ) {

							 		if ( $obj.is('textarea') && 
							 			 $obj.hasClass('wp-editor-area') ) {

							 			$obj.data('editor-old-id', id);
							 		}						 			

					 				id = id.replace( new RegExp('-uo-', 'ig'), '' );											 	
								 	id = id.replace( new RegExp('-uc-', 'ig'), '' );								 									 	
								 	id = id.replace( new RegExp('__index__', 'ig'), accordion.accordion_index  );

								 	$obj.attr('id', id);						 									 		

								}

								if ( undefined !== name ) {

								 	name = name.replace( new RegExp('-uo-', 'ig'), '[' );
								 	name = name.replace( new RegExp('-__index__-', 'ig'), accordion.accordion_index );
								 	name = name.replace( new RegExp('-uc-', 'ig'), ']' );

								 	$(elem).attr('name', name);

								}										 

							});		

			$accordion_field.removeClass('closed')
							.appendTo( $accordion_group_fields );

			var $textarea = $accordion_field.find('.wp-editor-wrap')
									 		.find('textarea');

			$textarea.each( function( index, elem ) {

				var $obj = $(elem),					
					$editor_container = $obj.closest('.wp-editor-container'),

					$editor_toolbar = $editor_container.prev('.wp-editor-tools'),					
					$add_media_button = $editor_toolbar.find('button.add_media'),
					$editor_tabs_button = $editor_toolbar.find('button.wp-switch-editor'),

					textarea_id = $obj.attr('id'),
					old_textarea_id = $obj.data('editor-old-id'),
					textarea_name = $obj.attr('name');				

				$obj.prev('.mce-container').remove();
				$obj.prev('.quicktags-toolbar').remove();
				$obj.remove();

				$add_media_button.attr('data-editor', textarea_id);
				$editor_tabs_button.attr('data-wp-editor-id', textarea_id);

				$editor_container.append("<textarea class='wp-editor-area' rows='5' tabindex='1' autocomplete='off' cols='40' id='" + textarea_id + "' name='" + textarea_name + "'></textarea>");

				var mceInit = tinyMCEPreInit.mceInit[old_textarea_id];				

				mceInit.selector = '#' + textarea_id;				

				tinymce.init( mceInit ); //init tinymce							

				var qt_instance = quicktags({ id : textarea_id }),

					t = setInterval(function() {

						if ( typeof qt_instance === 'object' ) {

							QTags._buttonsInit();

							$editor_toolbar.find('.wp-switch-editor.switch-html')
										   .click();

							clearInterval( t );

						}

					}, 200);

			});

			accordion.accordion_index++;			

		},
		on_accordion_remove_field: function(e) {

		 var $accordion = $(this).closest('.metabox-accordion-item');

		 // remove instance tinymce
		 $accordion.find("textarea[class='wp-editor-area']")
		 		   .each( function(index, elem) {

		 		   		tinymce.get( $(elem).attr('id') ).remove();

		 		   });

		 $accordion.remove();

		 accordion.accordion_index--;

		},
		on_accordion_field_title_keyup: function(e) {

			var $accordion_item = $(this).closest('.metabox-accordion-item'),
				$accordion_title = $accordion_item.find('.metabox-accordion-title')
												  .find('.title'),
				text = $(this).val();

			if ( '' !== text ) {
				$accordion_title.html( ' : ' + text );
			}

			else {
				$accordion_title.html( '' );
			}

		},
		on_accordion_handle_click: function(e) {

			var $obj = $(this);			

			if ( $obj.hasClass('metabox-collapse-button') ) {
				$obj = $(this).next('h2.metabox-handle-title');
			}

			var $parent = $obj.closest('.metabox-field-postbox-item');

			$parent.closest('.metabox-field-postbox-item')
				   .siblings()
				   .find('h2.metabox-handle-title')
				   .removeClass('markup');

			if ( ! $obj.hasClass('markup') ) {
				$obj.addClass('markup');
			}

			if ( $parent.hasClass('closed') ) {

				var $c_parent = $parent.find('.metabox-field-postbox-item');

				if ( $c_parent.length > 0 ) {

				    $c_parent.find('h2.metabox-handle-title')
				   			 .removeClass('markup');

				   	$c_parent.addClass('closed');

				}

			}

		}

	}	

	accordion.ready();

});