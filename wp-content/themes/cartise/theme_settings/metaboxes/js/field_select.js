jQuery(function($) {

   var select = {

        ready: function() {

            var $select = $('.select_field_metabox');

            function set_metabox_fields_simple_rule_callback( $object, s_value, rule_value, rule_compare, postbox ) {

               switch ( rule_compare ) {

                    case '=' :

                        if ( s_value === rule_value ) {
                            
                            if ( $object.hasClass('mb_hidden') ) {
                                $object.removeClass('mb_hidden'); 
                            }

                        }

                        else {

                            if ( ! $object.hasClass('mb_hidden') ) {
                                $object.addClass('mb_hidden');
                            }

                        }

                        break;

                    case '!=' :

                        if ( s_value !== rule_value ) { 

                            if ( $object.hasClass('mb_hidden') ) {
                                $object.removeClass('mb_hidden');
                            }

                        }

                        else {

                            if ( ! $object.hasClass('mb_hidden') ) {
                                $object.addClass('mb_hidden');
                            }

                        }

                        break;

                }    

            }

            function set_metabox_fields_complex_rule_callback( $object, s_value, rule_fids, rule_values, rule_compares, rule_operator ) {

                var rules_length = rule_fids.length,
                    boolLogic = null;

                for ( var i = 0; i < rules_length; i++ ) {

                    //console.log( rule_fids[i] );

                    var fid = rule_fids[i],               
                        value = $('#' + fid).val(),
                        rule_value = rule_values[i],
                        rule_compare = rule_compares[i],
                        result_compare = true;

                    switch ( rule_compare ) {

                        case '=' :

                            if ( value === rule_value  ) {
                                result_compare = true;
                            }

                            else {
                                result_compare = false;
                            }

                            break;

                        case '!=' :

                            if ( value !== rule_value ) { 
                                result_compare = true;
                            }

                            else {
                                result_compare = false;
                            }

                            break;

                    }

                    switch ( rule_operator ) {

                        case 'AND' :

                            boolLogic = null === boolLogic ? result_compare : boolLogic && result_compare;
                            break;

                        case 'OR' :

                            boolLogic = null === boolLogic ? result_compare : boolLogic || result_compare;
                            break;

                    }

                    //console.log('boolLogic = ' + boolLogic );

                }

                if ( boolLogic ) {

                    if ( $object.hasClass('mb_hidden') ) {
                        $object.removeClass('mb_hidden');
                    }

                }

                else {

                    if ( ! $object.hasClass('mb_hidden') ) {
                        $object.addClass('mb_hidden');
                    }

                }

                $object.data('condition-complex-approved', 'approved');
                
            }

            function set_state_metabox_fields( s_id, s_value, method ) {

                // chỉ xét những metabox và trường ràng buộc có thuộc tính
                // data-metabox-condition-field = s_id
                var $metabox = $( '#advanced-sortables' ).find('.metabox_wrap'),
                    set_metabox_fields_callback = function( index, elem ) {

                        var $object = $(elem),
                            postbox = false,

                            rule_complex = $object.attr('data-metabox-condition-complex'),                     
                            rule_name = $object.attr('data-metabox-condition-rule'),

                            rule_fid = $object.attr('data-metabox-condition-field'),
                            rule_value = $object.attr('data-metabox-condition-value'),
                            rule_compare = $object.attr('data-metabox-condition-compare'),
                            rule_operator = $object.attr('data-metabox-condition-operator'),

                            // cờ này cho phép tiếp tục duyệt field này hay không ?
                            // cờ này chỉ có tác dụng trong những rule complex và trong vòng lặp each của select
                            // mỗi một field có rule complex chỉ cho phép duyệt một lần duy nhất
                            // nếu field đã duyệt rồi thì data của field: "condition-complex-approved" = "approved"                    
                            rule_complex_approved = true;

                        if ( $object.hasClass('metabox_wrap') ) {                   
                            $object = $object.closest('.postbox'); 
                            postbox = true;
                        }

                        if ( 'visible' === rule_name ) {

                            if ( 'true' === rule_complex ) {

                                if ( 'each' === method ) {

                                    // field đã duyệt rồi => set cờ 'rule_complex_approved' = false để không duyệt tiếp
                                    if ( 'approved' === $object.data('condition-complex-approved') ) {
                                        rule_complex_approved = false;
                                    } 

                                }

                                // tiến hành duyệt field có complex rule
                                if ( rule_complex_approved ) {

                                    //console.log('approved');

                                    var rule_fids = rule_fid.split(','),
                                        rule_values = rule_value.split(','),
                                        rule_compares = rule_compare.split(',');

                                    set_metabox_fields_complex_rule_callback( $object, s_value, rule_fids, rule_values, rule_compares, rule_operator );

                                }

                            }

                            else {
                                set_metabox_fields_simple_rule_callback( $object, s_value, rule_value, rule_compare, postbox );
                            }                                          

                        }
                    },
                    $mb = $metabox.filter('div[data-metabox-condition-field="' + s_id + '"]');
                
                if ( $mb.length > 0 ) {            
                    $mb.each( set_metabox_fields_callback );
                }

                var $fields = $metabox.find('div[data-metabox-condition-field*="' + s_id + '"]');
                
                if ( $fields.length > 0 ) {
                    $fields.each( set_metabox_fields_callback );
                }

            }

            function set_state_term_metabox( $obj ) {

                var obj_value = $obj.val();

                if ( $obj.hasClass('slFrontTermLayout') || 
                     $obj.hasClass('slTermLayout') ) {

                        $('.form_table_layout').each( function(index, elem) {

                            if ( $(elem).hasClass( obj_value ) ) {

                                if ( $(elem).hasClass('mb_hidden') ) {
                                    $(elem).removeClass('mb_hidden');
                                }

                            }

                            else {

                                if ( ! $(elem).hasClass('mb_hidden') ) {
                                    $(elem).addClass('mb_hidden');
                                }

                            }                  

                        });

                    }

            }
            
            $select.change(function(e) {

                var $obj = $(this),
                    obj_value = $obj.val();

                // có ràng buộc hiển thị trường dữ liệu
                if ( $obj.hasClass('validate') ) {

                    set_state_metabox_fields( $obj.attr('id'),
                                              obj_value,
                                              'change' );
                }

                else {

                    set_state_term_metabox( $obj );

                }

            });

            $select.each( function(index, elem) {

                var $obj = $(elem);

                if ( $obj.hasClass('validate') ) {

                    set_state_metabox_fields( $obj.attr('id'),
                                              $obj.val(),
                                              'each' );

                }

                else {

                    set_state_term_metabox( $obj );

                }

            });    

        }

    }

    select.ready();
    
});