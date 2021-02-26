<?php   
   

    class MyThemePostTypeMetaBoxes {
        
        private $metaboxes = array();        
        
        public function __construct() {   

            include METABOX_DIRECTORY_OPTIONS . '/post_types_metaboxes.php';

        }

        /**
         Khai báo meta box
        **/
        function theme_meta_boxes_init() {           
            
            $metaboxes = $this->metaboxes;
            $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
            
            foreach ( $metaboxes as $metabox ) :

                $add_metabox = false;
                
                if ( isset( $metabox['where_show_on'] ) && 
                     ! empty( $metabox['where_show_on'] ) ) :

                    $arr_cat_slug = $metabox['category'];

                    // tham số 'category' tồn tại ?
                    if ( $arr_cat_slug ) :                                              

                        $post_cats = get_the_category( $post_id );
                        
                        foreach( $post_cats as $cat ) :

                            // kiểm tra xem slug của danh mục $cat này có nằm trong $arr_cat_slug ?
                            if ( in_array( $cat->slug, $arr_cat_slug ) ) :

                                $add_metabox = true;    
                                break;                            

                            else: 

                                // kiểm tra xem danh mục $cat này có là con của danh mục nào trong $arr_cat_slug ?                               
                                foreach ( $arr_cat_slug as $cat_slug ) :

                                    $mcat = get_term_by( 'slug', $cat_slug, 'category' );

                                    // là child category ?
                                    if ( cat_is_ancestor_of( $mcat, $cat ) ) :

                                        $add_metabox = true;
                                        break;

                                    endif;
                                    
                                endforeach;

                                // tìm được rồi thì out khỏi vòng lặp
                                if ( $add_metabox ) :

                                    break;
                                
                                endif;

                            endif;

                        endforeach;

                    else: 

                        if ( 'page' === $metabox['where_show_on'] ) :

                            // tham số 'page template' tồn tại ?                        
                            $page_template = $metabox['page_template'];

                            if ( $page_template ) :

                                $page_template_layout = get_post_meta( $post_id, '_wp_page_template', true );

                                // là loại page template này => show metabox này ra
                                if ( false !== strpos( $page_template_layout, $page_template ) ) :

                                    $add_metabox = true;

                                endif;

                            else :

                                $add_metabox = true;

                            endif;

                        else :

                            $add_metabox = true;

                        endif;

                    endif;

                    if ( $add_metabox ) :

                        add_meta_box( $metabox['id'], $metabox['title'], array( &$this, 'theme_metabox_callback' ), $metabox['where_show_on'], 'advanced', 'high', array( 'metabox', $metabox ) );

                    endif;

                endif;                
               
            endforeach;
            
        }

        function print_metabox_field_begin_tag( $field, $obj_id, $obj_class, $args = array() ) {

            $condition = isset( $field['condition'] ) && 
                         is_array( $field['condition'] ) &&
                         sizeof( $field['condition'] ) > 0;

            if ( $condition ) :

                $condition_keys = array_keys( $field['condition'] );
                $rule_key = $condition_keys[0];

                $condition_complex = is_array( $field["condition"]["$rule_key"][0] ) ?
                                     true : 
                                     false;

                $condition_fields = '';
                $condition_values = '';
                $condition_compares = '';
                $condition_operator = '';

            endif; ?>
            
           <div id="<?php echo $obj_id ?>" 
                class="<?php echo $obj_class ?>"

                <?php if ( $condition) : 

                        if ( $condition_complex ) : 

                            $condition_arrays = $field["condition"]["$rule_key"][0]; ?>                

                            data-metabox-condition-complex="true"
                            data-metabox-condition-operator="<?php echo strtoupper( $field["condition"]["$rule_key"][1]['condition_operator'] ); ?>"

                            <?php foreach( $condition_arrays as $my_condition ) : 

                                    $condition_fields .= $my_condition['meta_field_id'];
                                    $condition_values .= $my_condition['meta_field_value'];
                                    $condition_compares .= $my_condition['meta_field_compare'];

                                    if ( $my_condition !== end( $condition_arrays ) ) :

                                        $condition_fields .= ',';
                                        $condition_values .= ',';
                                        $condition_compares .= ',';

                                    endif;

                                  endforeach; 

                        else : 

                            $condition_fields = $field["condition"]["$rule_key"]["meta_field_id"];
                            $condition_values = $field["condition"]["$rule_key"]["meta_field_value"];
                            $condition_compares = $field["condition"]["$rule_key"]["meta_field_compare"];

                        endif; ?>

                            data-metabox-condition-rule="<?php echo $condition_keys[0]; ?>"
                            data-metabox-condition-field="<?php echo $condition_fields; ?>"
                            data-metabox-condition-value="<?php echo $condition_values; ?>"
                            data-metabox-condition-compare="<?php echo $condition_compares; ?>"

             <?php      endif; ?>>

    <?php

        }
        
        function print_text_field_metabox_theme_callback( $post_id, $field, $args = array() ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );

            if ( isset( $args['groupbox_args']['accordion_index'] ) ) :

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );                

            endif;

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
            
                <div class="label">
                    <strong><?php echo $field['title']; ?></strong>
                </div>

                <div class="desc mtop10"><?php echo $field['desc']; ?></div>

                <div class="field mtop10">

                    <div class="numformat"></div>

                    <input id="<?php echo $field['id']; ?>" 
                           type="text" 
                           name="<?php echo $field_name; ?>" 
                           value="<?php echo esc_attr( $field_value ); ?>"
                           <?= $field['numformat'] ? "data-numformat='" . $field['numformat'] . "'" : "" ?> /> 


                </div>

            </div>  
                  
<?php   }
        
        function print_hidden_field_metabox_theme_callback( $post_id, $field, $args = array() ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' ); 
            $field_value = get_post_meta( $post_id, '_' . $field['id'], true ); ?>

           <input id="<?php echo $field['id']; ?>" 
                  type="hidden" 
                  name="<?php echo $field['id']; ?>" 
                  value="<?php echo esc_attr( $field_value ); ?>" />
                  
<?php   }
        
        function print_textarea_field_metabox_theme_callback( $post_id, $field, $args = array() ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );

            if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );                

            endif;

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
            
                <div class="label">
                    <strong><?php echo $field['title']; ?></strong>
                </div>

                <div class="desc mtop10"><?php echo $field['desc']; ?></div>

                <div class="field mtop10">

                    <textarea id="<?php echo $field['id']; ?>"                                                          
                              name="<?php echo $field_name; ?>"
                              rows="5"><?php echo esc_attr( $field_value ); ?></textarea>

                </div>

            </div>  
                  
<?php   }
        
        function print_select_field_metabox_theme_callback( $post_id, $field, $args ) {
            
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );
                      
            if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );

            endif;

            $validate_condition = isset( $field['validate'] ) && $field['validate']; ?>
            
           <div id="metabox_field_<?php echo $field['id'] ?>" 
                class="metabox_field mtop10">
           
                <div class="label"><strong><?php echo $field['title']; ?></strong></div>
                <div class="desc mtop10"><?php echo $field['desc']; ?></div>
                <div class="field mtop10">
                
                    <select id="<?php echo $field['id']; ?>" 
                            class="txtFTypical tFChange select_field_metabox 
                                   <?= $validate_condition ? 'validate' : '' ?>">
                        
                        <?php while ( $option_name = current( $field['options'] ) ) : ?>
                        
                                <option value="<?php echo key( $field['options'] ) ?>" <?php selected( $field_value, key( $field['options'] ) ); ?>><?php echo $option_name; ?></option>
                            
                        <?php
                                next( $field['options'] );

                            endwhile; ?>
                        
                    </select>
                   
                    <input id="<?php echo $field['id'] . '-input-select'; ?>" type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $field_value; ?>" /> 
    
                </div>
                
            </div>
                                  
   <?php 
            
        }
        
        function print_media_field_metabox_theme_callback( $post_id, $field, $args ) {
            
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );

            if ( isset( $args['groupbox_args']['accordion_index'] ) ) :

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $media_field_values = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];                
                $media_field_values = get_post_meta( $post_id, '_' . $field['id'], true );                

            endif;

            $has_field_multiple = isset( $field['multiple'] ) && $field['multiple'];
            $has_name_box = isset( $field['has_name_box'] ) && $field['has_name_box'];

            $field_media_title = '';
            $field_media_thumbnail = '';

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>

           
                <div class="label"><strong><?php echo $field['title']; ?></strong></div>
                <div class="desc pull-left mtop10 mright10"><?php echo $field['desc']; ?></div>
                
                <?php if ( $has_field_multiple ) : ?>
                
                    <div class="field_media_navigation mtop10">

                        <img class="field_media_widget_add cpointer"
                        
                                src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_add.png' ?>" 
                                alt="widget_add" 
                                
                                data-image-path="<?php echo METABOX_DIRECTORY_IMAGES_URI ?>" 
                                data-field-id="<?php echo $field['id']; ?>" 
                                data-thumbnail-enable="<?= isset( $field['thumbnail'] ) && ! $field['thumbnail'] ? 'false' : 'true'  ?>" />
                    </div>
                    
                <?php endif; ?>
                
                <div class="clearfix"></div>
                
               <?php if ( is_array( $media_field_values ) && 
                          count( $media_field_values ) > 0 &&
                          is_array( $media_field_values[0] ) &&
                          count( $media_field_values[0] ) > 0 ) :
                            
                            $count_field = 1;
                            
                            foreach ( $media_field_values as $media_field_value ): 

                                $field_media_title = $media_field_value['title'];
                                $field_media_thumbnail = $media_field_value['thumbnail']; ?>
                        
                                <div class="field mtop10"
                                     data-index="<?php echo $count_field - 1 ?>">

                                    <div class="inlineblock vmiddle">

                                        <?php if ( $has_name_box ) : ?>

                                            <div>

                                                <div>
                                                    <strong>Tiêu đề:</strong>
                                                </div>
                                                
                                                <input type="text" 
                                                      name="<?= ( $has_field_multiple ? $field_name . '[' . ( $count_field - 1 ) . ']' : $field_name ) . '[title]' ?>" 
                                                      class="media_field_metabox title vmiddle" 
                                                      value="<?php echo $field_media_title ?>" />

                                            </div>

                                        <?php endif; ?>

                                            <div class="mtop5">

                                                <div>
                                                    <strong>Tập tin đã chọn:</strong>
                                                </div>
                                       
                                               <?php if ( $field['thumbnail'] || ! isset( $field['thumbnail'] ) ) : ?>

                                                   <img src="<?php echo $media_field_value ?>" 
                                                        class="thumbnail_media_field_metabox vmiddle" />

                                               <?php endif; ?>
                                           
                                               <input type="text" 
                                                      name="<?= ( $has_field_multiple ? $field_name . '[' . ( $count_field - 1 ) . ']' : $field_name ) . '[thumbnail]' ?>" 
                                                      class="media_field_metabox thumbnail vmiddle" 
                                                      value="<?php echo $field_media_thumbnail ?>" />

                                            </div>

                                    </div>

                                    <div class="inlineblock vmiddle">

                                       <input type="button" 
                                              class="button button-default media_upload vmiddle" 
                                              value="Choose an image" />
                                   
                                       <?php if ( $count_field > 1 ) :  // not first ?>
                                       
                                           <img src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_remove.png' ?>" 
                                                class="field_media_widget_remove vmiddle cpointer" />
                                       
                                       <?php endif; ?>

                                    </div>
                    
                                </div>
                            
                <?php           $count_field++;

                            endforeach;
                
                       else: 

                            if ( $has_field_multiple ) :

                                if ( $has_name_box ) :

                                    if ( $media_field_values[0] && 
                                         array_key_exists('title', $media_field_values[0] ) ) :

                                        $field_media_title = $media_field_values[0]['title']; 

                                    endif;

                                endif;

                                if ( $media_field_values[0] &&
                                     array_key_exists('thumbnail', $media_field_values[0] ) ) :

                                    $field_media_thumbnail = $media_field_values[0]['thumbnail'];

                                endif;

                            else :

                                if ( $has_name_box ) :

                                    if ( $media_field_values &&
                                         array_key_exists('title', $media_field_values) ) :

                                        $field_media_title = $media_field_values['title']; 

                                    endif;

                                endif;

                                if ( $media_field_values &&
                                     array_key_exists('thumbnail', $media_field_values) ) :

                                    $field_media_thumbnail = $media_field_values['thumbnail'];

                                endif;

                            endif; ?>
                       
                           <div class="field mtop10" data-index="0">

                                <div class="inlineblock vmiddle">

                                    <?php if ( $has_name_box ) : ?>

                                        <div>

                                            <div>
                                                <strong>Tiêu đề:</strong>
                                            </div>
                                            
                                            <input type="text" 
                                                   name="<?= ( $has_field_multiple ? $field_name . '[0]' : $field_name ) . '[title]' ?>" 
                                                   class="media_field_metabox title vmiddle" 
                                                   value="<?php echo $field_media_title ?>" />

                                        </div>

                                    <?php endif; ?> 

                                        <div class="mtop5">

                                            <div>
                                                <strong>Tập tin đã chọn:</strong>
                                            </div>

                                            <?php if ( $field['thumbnail'] || 
                                                      ! isset( $field['thumbnail'] ) ) : ?>

                                               <img src="<?php echo $media_field_values ?>" 
                                                    class="thumbnail_media_field_metabox vmiddle" />
                                           
                                            <?php endif; ?>     

                                                <input type="text" 
                                                       name="<?= ( $has_field_multiple ? $field_name . '[0]' : $field_name ) . '[thumbnail]' ?>" 
                                                       class="media_field_metabox thumbnail vmiddle" 
                                                       value="<?php echo $field_media_thumbnail  ?>" />

                                        </div>

                                </div>

                                <div class="inlineblock vmiddle">

                                    <input type="button" class="button button-default media_upload vmiddle" value="Choose an image" />

                                </div>
                
                            </div>
                
                <?php endif; ?>
                
            </div>
                                  
   <?php
            
        }
        
        function print_editor_field_metabox_theme_callback( $post_id, $field, $args ) {
            
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );            
            
             if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );

            endif; 

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
                    
                    <div class="label"><strong><?php echo $field['title'] ?></strong></div>
                    <div class="desc mtop10"><?php echo $field['desc'] ?></div>
                    <div class="field mtop10">
                        
                        <?php 
                        
                            $settings = array(
                                'teeny' => false,
                                'textarea_rows' => $field['rows'] ? (int) $field['rows'] : 5,
                                'tabindex' => 1,
                                'textarea_name' => $field_name
                            );
                            
                            wp_editor( $field_value, $field['id'], $settings); ?>
                        
                        
                        <input id="<?php echo $field['id'] . '-editor' ?>" type="hidden" name="<?php echo $field_name ?>" class="editor_field_metabox" />
                        
                    </div>
                </div>

 <?php }

        function print_checkbox_field_metabox_theme_callback( $post_id, $field, $args ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );         
            
             if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );

            endif;

            if ( $field_value ) :

                $checkbox_values = explode(',', $field_value);

            endif;

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
                    
                <div class="label">
                    <strong><?php echo $field['title'] ?></strong>
                </div>         

                <div class="field mtop10">

                    <div class="field-cb-group">

                        <?php while ( $data = current( $field['data'] ) ) : 

                                $key = key( $field['data'] );

                                if ( $checkbox_values ) : 

                                    $checkbox_state = in_array( $key, $checkbox_values );

                                endif; ?>

                                <div class="field-cb">
                        
                                    <input id="<?php echo "{$field['id']}-{$key}" ?>"
                                           type="checkbox"                                  
                                           class="txtFTypical tFChange checkbox_field_metabox"
                                           data-checkbox-value="<?php echo $key ?>" 
                                           <?= isset( $checkbox_state ) && $checkbox_state ? 'checked' : '' ?> />                            

                        <?php 
                                    echo $data; ?>

                                </div>

                        <?php   next( $field['data'] );  ?>  

                        <?php endwhile; ?>

                    </div>

                    <input class="checkbox_field_hidden"
                           type="hidden"
                           name="<?php echo $field_name ?>" 
                           value="<?= isset( $field_value ) ? $field_value : '' ?>" />
                    
                </div>
            </div> 
                   
 <?php }

        function print_radio_field_metabox_theme_callback( $post_id, $field, $args ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );         
            
            if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );

            endif; 

            $radio_value = $field_value;         

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
                    
                <div class="label">

                    <strong>
                        <?php echo $field['title'] ?>
                    </strong>

                </div>         

                <div class="field mtop10">

                    <div class="field-rd-group">

                        <?php
                            $first_data_key = reset( array_keys( $field['data'] ) );
                            $first_data = reset( $field['data'] );

                            while ( $data = current( $field['data'] ) ) : 

                                $key = key( $field['data'] );

                                $radio_state = false;

                                if ( $radio_value ) : 

                                    $radio_state = $radio_value == $key ? true : false;

                                endif; ?>

                                <div class="field-rd mtop5">
                        
                                    <input id="<?php echo "{$field['id']}-{$key}" ?>"
                                           type="radio"                                  
                                           class="txtFTypical tFChange rd_field_metabox"
                                           name="<?php echo $field['id'] ?>"
                                           value="<?php echo $key ?>" 
                                           <?php
                                                if ( isset( $radio_state ) && $radio_state ) :

                                                    echo 'checked';

                                                else :

                                                    if ( $data === $first_data ) :

                                                        echo 'checked';

                                                    endif;

                                                endif;
                                               
                                            ?> />

                        <?php 
                                    echo $data; ?>

                                </div>

                        <?php   next( $field['data'] );  ?>  

                        <?php endwhile; ?>

                    </div>

                    <input class="rd_field_hidden"
                           type="hidden"
                           name="<?php echo $field_name ?>" 
                           value="<?php 
                             
                                    if ( isset( $field_value ) && ! empty( $field_value ) ) :

                                        echo $field_value;
                    
                                    else :

                                        echo $first_data_key;

                                    endif; 

                                  ?>"
                    />
                </div>
            </div> 
                   
 <?php }

        function print_datepicker_field_metabox_theme_callback( $post_id, $field, $args ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );         
            
             if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];

                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );
                $field_fvalue = get_post_meta( $post_id, '_' . $field['id'] . '-first', true );

            endif;

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
                    
                <div class="label">
                    <strong><?php echo $field['title'] ?></strong>
                </div>         

                <div class="field mtop10">

                    <div class="datepicker-tags">

                        <?php if ( $field_value ) : 

                                $dates = explode(',', $field_value); 

                                if ( count( $dates ) > 0 ) : 

                                    foreach ( $dates as $date ) : ?>

                                        <div class="tag">

                                            <span class="dateText">
                                                <?php echo $date; ?>
                                            </span>

                                            <span class="close">x</span>

                                        </div>
                                        
                            <?php   endforeach;

                                endif; 

                            endif; ?>

                    </div>

                    <input type="hidden"
                           name="<?php echo $field_name ?>"
                           id="<?php echo $field['id'] ?>"
                           class="datepicker-input" 
                           value="<?php echo $field_value ?>" /> 

                    <input type="hidden"
                           name="<?php echo $field_name . '-first' ?>"                          
                           class="datepicker-finput" 
                           value="<?php echo $field_fvalue ?>" />                    
                    
                    <input type="text" 
                           id="<?php echo $field['id'] . '-input' ?>"
                           class="datepicker" 
                           placeholder="Mời chọn một ngày" />

                </div>

            </div> 
                   
 <?php }

        function print_tag_field_metabox_theme_callback( $post_id, $field, $args ) {
                       
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );         
            
            if ( isset( $args['groupbox_args']['accordion_index'] ) ) :   

                $accordion_id = $args['groupbox_args']['accordion_id'];
                $accordion_index = $args['groupbox_args']['accordion_index'];

                $accordion = get_post_meta( $post_id, "_{$accordion_id}", true );
                $field_value = $accordion[ $accordion_index ][ "{$field['_id']}" ];

                $field_name = "{$accordion_id}[{$accordion_index}][{$field['_id']}]";

            else :

                $field_name = $field['id'];
                $field_value = get_post_meta( $post_id, '_' . $field['id'], true );                

            endif;

            if ( count( $field['settings']['src']['ajax'] ) > 0 ) :

                $field_ajax_settings = $field['settings']['src']['ajax'];

                if ( array_key_exists('by_ref_object', $field_ajax_settings) ) :

                    $ajax_ref_object = $field_ajax_settings['by_ref_object'];
                    $ajax_ref_values = $ajax_ref_object['object_ref_values'];

                    $ref_object_id = $ajax_ref_object['object_id'];

                    $ajax_parameters = '[';

                    $_object_r_key = get_post_meta( $post_id, '_' . $ref_object_id, true ); 
                    $_object_r_keys = array_keys( $ajax_ref_values );

                    if ( is_null( $_object_r_key ) || empty( $_object_r_key ) ) :
                        
                        $_object_r_key = $_object_r_keys[0];

                    endif;

                    $_object_r_key_index = array_search($_object_r_key, $_object_r_keys);

                    $ref_v_last = end( $ajax_ref_values );

                    reset( $ajax_ref_values );

                    while ( $ref_v = current( $ajax_ref_values )) :                        

                        $object_r_key = key( $ajax_ref_values );

                        $ajax_src_obj = $ajax_ref_values[ $object_r_key ]['get_src'];

                        if ( array_key_exists( 'theme_option', $ajax_src_obj ) ) :

                            $ajax_t_option = $ajax_src_obj['theme_option'];

                            $ajax_t_option_name = $ajax_t_option['option_name'];
                            $ajax_t_option_field = $ajax_t_option['option_field'];

                            $ajax_parameters .= "{'key':'{$object_r_key}','ajax':{'action':'sb_meta_boxes_tag_ajax','cmd':'get_tags_by_theme_option','option_name':'{$ajax_t_option_name}','option_field':'{$ajax_t_option_field}'}}";

                            if ( $ref_v !== $ref_v_last ) :

                                $ajax_parameters .= ",";

                            endif;

                        endif;

                        next( $ajax_ref_values );

                    endwhile;

                    $ajax_parameters .= ']';
 
                endif;

            endif;

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>
                    
                <div class="label">
                    <strong><?php echo $field['title'] ?></strong>
                </div>      

                <div class="desc mtop10">
                    <strong><?php echo $field['desc'] ?></strong>
                </div>         

                <div class="field mtop10">

                    <div class="tags tagBoxField">

                        <input id="<?php echo $field['id'] . '-input' ?>"
                               class="_taginput tag_field_metabox"
                               name="<?php echo $field_name ?>"
                               type="text"
                               data-tags-init="<?php echo $field_value ?>"

                               <?php if ( $ref_object_id ) : ?>

                                    data-ref-object-id="<?php echo $ref_object_id ?>"

                                <?php endif; ?>

                               data-ajax-parameters="<?php echo $ajax_parameters ?>"
                               data-ajax-param-index="<?php echo $_object_r_key_index ?>" />

                    </div>

                    <div class="ajaxLoadTags flex">

                        <span class="dashicons-before dashicons-screenoptions ajax_animate_loading"></span>
                        
                    </div>

                </div>

            </div> 
                   
 <?php }

        function print_accordion_field_metabox_theme_callback( $post_id, $field ) {

            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );
            
            $field_value = get_post_meta( $post_id, '_' . $field['id'], true );            

            if ( is_array( $field_value ) &&
                 ! empty( $field_value ) ) :
            
                $accordion_count = sizeof( $field_value );

                $_SESSION[ basename( __FILE__ ) . "-{$field['id']}-count" ] = $accordion_count; ?>

                <script type="text/javascript">
                   var accordion_index = <?php echo $accordion_count; ?>
                </script>

    <?php   endif;

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field mtop10" ); ?>                

                <div class="label">
                    <strong><?php echo $field['title'] ?></strong>
                </div> 

                <div class="desc mtop10">
                    <?php echo $field['desc'] ?>
                </div> 

                <!-- metabox-field-accordion -->
                <div class="field metabox-field-accordion mtop10">

                    <button type="button" class="metabox-accordion-add-button button button-default">

                        <img class="vmiddle" src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_add.png' ?>" alt="metabox_add_accordion" /> 
                        <span>Add an accordion</span>

                    </button>

                    <!-- metabox-accordion-group -->
                    <div class="metabox-accordion-group metabox-field-sortables metabox-global-fields-sortables metabox-accordion-sortables mtop10">

                        <!-- accordion-template-item -->
                        <div class="metabox-field-postbox-item metabox-accordion-item postbox closed accordion-template-item mtop20">

                            <button type="button" class="button-link metabox-collapse-button metabox-collapse-box">

                                <span class="screen-reader-text">Toggle panel: Metabox accordion</span>
                                <span class="toggle-indicator"></span>

                            </button>

                            <!-- metabox-accordion-title -->
                            <h2 class="metabox-accordion-title metabox-handle metabox-handle-title hndle metabox-collapse-box">

                                <strong>

                                    Accordion

                                    <span class="title" 
                                          data-accordion-title-field="<?php echo $field['collapse_title_field'] ?>">                                    
                                    </span>

                                </strong>

                            </h2>
                            <!-- #metabox-accordion-title -->

                            <!-- metabox-accordion-body -->
                            <div class="metabox-section-body inside mtop20">

                                <!-- accordion-groupbox -->
                                <div class="accordion-groupbox accordion-main-item">
                                   
                                    <?php 
                                        $args = array(
                                                'accordion' => true, 
                                                'accordion_args' => array(                                                                    
                                                    'id' => $field['id']                                                  
                                                )
                                            );
                                        $this->theme_metabox_fields_init_callback( $post_id, $field, $args ); ?>
                                                                
                                </div>
                                <!-- #accordion-groupbox -->

                                <!-- accordion-groupbox -->
                                <div class="accordion-groupbox mtop20">

                                    <button type="button" class="metabox-accordion-remove-button button button-default">

                                        <img class="vmiddle" src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_remove.png' ?>" alt="metabox-remove-accordion" />

                                        <span class="vmiddle">Remove accordion</span>

                                    </button>

                                </div>
                                <!-- #accordion-groupbox -->

                            </div>
                            <!-- #metabox-accordion-body -->                       

                        </div>
                        <!-- #accordion-template-item -->

                        <?php if ( $accordion_count > 0 ) : 

                                $accordion_index = 0;

                                foreach ( $field_value as $accordion ) : ?>

                                    <!-- metabox-accordion-item -->
                                    <div class="metabox-field-postbox-item metabox-accordion-item postbox closed mtop20">

                                        <button type="button" class="button-link metabox-collapse-button metabox-collapse-box">

                                            <span class="screen-reader-text">Toggle panel: Metabox accordion</span>
                                            <span class="toggle-indicator"></span>

                                        </button>

                                        <!-- metabox-accordion-title -->
                                        <h2 class="metabox-accordion-title metabox-handle metabox-handle-title hndle metabox-collapse-box">

                                            <strong>

                                                Accordion

                                                <span class="title" 
                                                      data-accordion-title-field="<?php echo $field['collapse_title_field'] ?>">

                                                      <?php echo ' : ' . $accordion[ "{$field['collapse_title_field']}" ] ?>

                                                </span>

                                            </strong>

                                        </h2>
                                        <!-- #metabox-accordion-title -->

                                        <!-- metabox-accordion-body -->
                                        <div class="metabox-section-body inside mtop20">

                                            <!-- accordion-groupbox -->
                                            <div class="accordion-groupbox accordion-main-item">
                                               
                                                <?php 

                                                    $args = array(
                                                                'accordion' => true, 
                                                                'accordion_args' => array(                                                                    
                                                                    'id' => $field['id'],
                                                                    'index' => $accordion_index,
                                                                ) 
                                                            );

                                                    $this->theme_metabox_fields_init_callback( $post_id, $field, $args ); ?>
                                                                            
                                            </div>
                                            <!-- #accordion-groupbox -->

                                            <!-- accordion-groupbox -->
                                            <div class="accordion-groupbox mtop20">

                                                <button type="button" class="metabox-accordion-remove-button button button-default">

                                                    <img class="vmiddle" src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_remove.png' ?>" alt="metabox-remove-accordion" />

                                                    <span class="vmiddle">Remove accordion</span>

                                                </button>

                                            </div>
                                            <!-- #accordion-groupbox -->

                                        </div>
                                        <!-- #metabox-accordion-body -->                       

                                    </div>
                                    <!-- #metabox-accordion-item -->

                        <?php 
                                    $accordion_index++;

                                endforeach;

                            endif; ?>

                    </div>
                    <!-- #metabox-accordion-group -->

                </div>
                <!-- #metabox-field-accordion -->

            </div>
<?php
        }

        function print_groupbox_field_metabox_theme_callback( $post_id, $field, $args = array() ) {
          
            wp_nonce_field( basename( __FILE__ ), $field['id'] . '-nonce' );           

            $this->print_metabox_field_begin_tag( $field, "metabox_field_{$field['id']}", "metabox_field metabox-field-postbox-item postbox closed mtop20" ); ?>

               
                <button type="button" class="button-link metabox-collapse-button metabox-collapse-box">

                    <span class="screen-reader-text">Toggle panel: Metabox groupbox</span>
                    <span class="toggle-indicator"></span>

                </button>

                <!-- metabox-groupbox-title -->
                <h2 class="metabox-groupbox-title metabox-handle metabox-handle-title hndle metabox-collapse-box">

                    <strong>

                        Groupbox : <?php echo $field['title']; ?>

                    </strong>

                </h2>
                <!-- #metabox-groupbox-title -->

                <!-- metabox-groupbox-body -->
                <div class="metabox-section-body inside mtop20">                       

                    <div class="desc mtop10">
                        <?php echo $field['desc'] ?>
                    </div> 

                    <!-- metabox-field-groupbox -->
                    <div class="metabox-field-groupbox mtop20">

                         <?php

                            $groupbox_args = array();

                            if ( $args['accordion'] ) :

                                $groupbox_args = array(
                                        'accordion_groupbox' => true,
                                        'groupbox_args' => array(
                                                'accordion_id' => $args['accordion_args']['id'],
                                                'accordion_index' => $args['accordion_args']['index']
                                            )
                                    );

                            endif;

                            $this->theme_metabox_fields_init_callback( $post_id, $field, $groupbox_args ); ?>

                    </div>
                    <!-- #metabox-field-groupbox -->

                </div>
                <!-- #metabox-groupbox-body -->

               

            </div>

    <?php
        }

        function theme_metabox_fields_init_callback( $post, $mb_fields, $args = array() ) {

            if ( $args['accordion'] ) :

                $field_arrays = $mb_fields['template'];

            else :

                $field_arrays = $mb_fields['fields'];

            endif;
            
            foreach ( $field_arrays as $field ) :

                if ( $args['accordion'] || 
                     $args['accordion_groupbox'] ) :                   

                    if ( $args['accordion'] ) :

                        if ( 'groupbox' === $field['type'] ) :

                            $field['id'] = "{$field['id']}";

                        endif;    

                    else : // accordion-groupbox  

                        $accordion_id = $args['groupbox_args']['accordion_id'];
                        $accordion_index = $args['groupbox_args']['accordion_index'];

                        if ( isset( $accordion_index ) ) :

                            $field['_id'] = $field['id'];
                            $field['id'] = "{$accordion_id}-{$accordion_index}-{$field['id']}";                            

                        else :

                            $field['id'] = "{$accordion_id}-uo--__index__--uc--uo-{$field['id']}-uc-";

                        endif;                        

                    endif;                                    

                endif;
                
                switch ( $field['type'] ) :
                    
                    case 'text':
                        
                        $this->print_text_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;

                    case 'hidden':
                        
                        $this->print_hidden_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;

                    case 'textarea':
                        
                        $this->print_textarea_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;
                    
                    case 'select':
                        
                        $this->print_select_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;
                        
                    case 'media':
                        
                        $this->print_media_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;
                        
                    case 'editor':
                        
                        $this->print_editor_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;

                    case 'check':
                        
                        $this->print_checkbox_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );
                        
                        break;

                     case 'radio' :

                        $this->print_radio_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );

                        break;

                    case 'tag' :

                        $this->print_tag_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );

                        break;

                    case 'datepicker' :

                        $this->print_datepicker_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args );

                        break;

                    case 'accordion':

                        $this->print_accordion_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field );
                        
                        break;

                    case 'groupbox':

                        $this->print_groupbox_field_metabox_theme_callback( ! is_numeric( $post ) ? $post->ID : $post, $field, $args  );
                        
                        break;
                    
                    default:
                        
                        break;
                        
                endswitch;
                
            endforeach;

        }
         
        /**
         Khai báo callback
         @param $post là đối tượng WP_Post để nhận thông tin của post
        **/
        function theme_metabox_callback( $post, $args_callback ) {

            ob_start();
            
            $metabox = $args_callback['args'][1]; 

            $this->print_metabox_field_begin_tag( $metabox, "metabox_{$metabox['id']}_wrap" , "metabox_wrap" ); ?>

                <div class="metabox_groupbox_fields_sortables metabox-global-fields-sortables">

<?php               $this->theme_metabox_fields_init_callback( $post, $metabox ); ?>
                
                </div>

            </div>

<?php       $contents = ob_get_contents();
            ob_end_clean();           

            echo $contents;
            
        }
         
        /**
         Lưu dữ liệu meta box khi nhập vào
         @param post_id là ID của post hiện tại
        **/       

        function startsWith($haystack, $needle) {

             $length = strlen($needle);             
             return (substr($haystack, 0, $length) === $needle);
        }

        function endsWith($haystack, $needle) {

            $length = strlen($needle);

            if ($length == 0) {
                return true;
            }

            return (substr($haystack, -$length) === $needle);
        }

        function theme_meta_boxes_save( $post_id ) {

            $keys = array_keys( $_POST );
            $size_keys = count( $keys );           

            for ( $i = 0; $i < $size_keys; $i++ ) :

                $key = $keys[ $i ];

                if ( $this->startsWith( $key, 'pt-field-' ) &&
                     ! $this->endsWith( $key, '-nonce') ) :

                    // accordion field                    
                    if ( false !== strpos( $key, '-uo--__index__' ) ) :

                        $s = explode('-uo--__index__', $key);
                        $accordion_key = $s[0];

                        // kiểm tra accordion này ?
                        if ( ! isset( $_POST[ "$accordion_key" ] ) ) :

                            // trước đó đã có dữ liệu => xóa meta accordion của post này
                            if ( $_SESSION[ basename( __FILE__ ) . "-{$field['id']}-count" ] > 0 ) :
                            
                                delete_post_meta( $post_id, "_{$accordion_key}" );

                            endif;

                        endif;

                    endif;                    

                    $_value = $_POST[ $key ];

                    update_post_meta( $post_id, "_{$key}", $_value ); 

                endif;                

            endfor;

          
        }
        
    }
        
    $theme_post_type_meta_boxes = new MyThemePostTypeMetaBoxes();
    
    add_action( 'add_meta_boxes', array( $theme_post_type_meta_boxes, 'theme_meta_boxes_init' ) );
    add_action( 'save_post', array( $theme_post_type_meta_boxes, 'theme_meta_boxes_save') );