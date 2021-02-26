jQuery(function($) {

	var tags_instances = [];

	function ref_object_ajax_reload( obj_name, vk ) {

		var tag_objects = tags_instances.filter(function(e) { return e['ref_obj_name'] == obj_name; });

		if ( tag_objects.length > 0 ) {

			$.each( tag_objects, function(i, tg) {

				var tag_inst = tg['tag_instance'],			
					json_tag_ajax_parameters = tg['ajax_parameters'],

					jt_index = json_tag_ajax_parameters.findIndex(function(e) { return e['key'] == vk });

				if ( jt_index !== -1 ) {

					var ajax_data = json_tag_ajax_parameters[ jt_index ]['ajax'];				

					tag_inst.reloadWithNewAjax({

						method : "POST",
						url : ajaxurl,
						data : ajax_data

					});			

				}

			});		

		}

	}	

	function ref_object_change_event(e) {

		var $obj = $(this),
			name = $obj.attr('name'),
			vk = '';

		if ( $obj.is(':radio') || $obj.prop('type') === 'select-one' ) {

			vk = $obj.val();

			ref_object_ajax_reload( name, vk );

		}
		

	}

	function travsel_tag_dom( $e ) {

		var ajax_parameters = $e.data('ajax-parameters'),
			ref_object_id = $e.data('ref-object-id'),

			ajax_param_index = parseInt( $e.data('ajax-param-index') ),

			ajax_data = {},

			_tags = $e.data('tags-init'),

			tag_inst = {};				
		
		tag_inst['ref_obj_name'] = ref_object_id;

		if ( ref_object_id !== undefined && ref_object_id !== '' ) {

			$(document).on('change', '*[name="' + ref_object_id + '"]', ref_object_change_event );

		}

		if ( _tags === undefined || _tags === '' ) {

			_tags = [];

		}

		else {

			_tags = _tags.split(',');

		}

		if ( ajax_parameters !== undefined ) {

			ajax_parameters = ajax_parameters.toString().trim();

			if ( ajax_parameters !== '' ) {

				ajax_parameters = ajax_parameters.replace(/\'/ig, "\"");
				json_parameters = JSON.parse( ajax_parameters );

				ajax_data = json_parameters[ ajax_param_index ]['ajax'];

				tag_inst['ajax_parameters'] = json_parameters;

			}

		} 

		var options = {

			placeholder : 'Mời nhập ký tự gợi ý rồi chọn mục dữ liệu cần ...',			

			src : {

				ajax : {

					method : "POST",
					url : ajaxurl,
					data : ajax_data

				}
				
			},

			array_tags_init: _tags

		}

		tag_inst['tag_instance'] = $e.jcustomtag( options );

		tags_instances.push( tag_inst );

	}

	$('._taginput').each(function(i, e) {

		travsel_tag_dom( $(e) );

	});

});