<?php
    
    class MyThemeTermMetaBoxes {

        private $metaboxes = array();

        function __construct() {

            include METABOX_DIRECTORY_OPTIONS . '/terms_metaboxes.php';

        }

        function extra_media_field( $field, $term_meta) { ?>

            <tr>

                <th scope="row" valign="top">

                    <label for="<?php echo $field['id'] ?>">
                        <?php echo $field['title'] ?>
                    </label>

                </th>

                <td>
                    <!-- metabox_field -->
                    <div class="metabox_field">

                        <?php if ( $field['multiple'] ) : ?>
            
                            <div style="margin-bottom: 10px;" class="field_media_navigation mtop10">
                                
                                <img class="field_media_widget_add cpointer vmiddle"
                                
                                        src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_add.png' ?>" 
                                        alt="widget_add" 
                                        
                                        data-image-path="<?php echo METABOX_DIRECTORY_IMAGES_URI ?>" 
                                        data-field-id="<?php printf( 'term_meta[%s]', $field['id'] ); ?>" 
                                        data-thumbnail-enable="<?= isset( $field['thumbnail'] ) && ! $field['thumbnail'] ? 'false' : 'true'  ?>" />

                                <span class="vmiddle">Thêm mới đối tượng</span>

                            </div>
                            
                        <?php endif; ?>                           

                        <p class="description"><?php echo $field['desc'] ?></p>

                        <?php 
                            $media_term_field = $term_meta[ $field['id'] ];

                            if ( is_array( $media_term_field ) && sizeof( $media_term_field ) > 0 ) :
                            
                                $count_field = 1;
                                
                                foreach ( $media_term_field as $media_field_value ): ?>
                            
                                    <div class="field mtop10">
                                       
                                       <?php if ( $field['thumbnail'] || ! isset( $field['thumbnail'] ) ) : ?>
                                           <img width="100" src="<?php echo $media_field_value ? $media_field_value : METABOX_DIRECTORY_IMAGES_URI . '/empty-thumbnail.png' ?>" class="thumbnail_media_field_metabox vmiddle" />
                                       <?php endif; ?>
                                       
                                       <input type="text" name="<?php printf( "term_meta[%s][]", $field['id'] ) ?>" class="media_field_metabox vmiddle" value="<?php echo $media_field_value ?>" />
                                       <input type="button" class="button button-default media_upload vmiddle" value="Chọn ảnh" />
                                       
                                       <?php if ( $count_field > 1 ) :  // not first ?>
                                       
                                           <img src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_remove.png' ?>" class="field_media_widget_remove vmiddle cpointer" />
                                       
                                       <?php endif; ?> 

                                    </div>                                         
                            
                <?php               $count_field++;                    
                                endforeach;   

                            else: ?>
                       
                             <div class="field mtop10">
                                 
                                 <?php if ( $field['thumbnail'] || ! isset( $field['thumbnail'] ) ) : ?>
                                     <img width="100" src="<?php echo $term_meta[ $field['id'] ] ? $term_meta[ $field['id'] ] : METABOX_EMPTY_THUMBNAIL_URI ?>" class="thumbnail_media_field_metabox vmiddle" />
                                 <?php endif; ?>
                                 
                                 <input type="text" 
                                        name="<?php printf( 'term_meta[%s]%s', $field['id'], isset( $field['multiple'] ) && $field['multiple'] ? '[]' : '' ) ?>" 
                                        class="media_field_metabox vmiddle" 
                                        value="<?php echo $media_term_field ?>" />

                                 <input type="button" class="button button-default media_upload vmiddle" value="Chọn ảnh" />

                                                    
                              </div>
                
                <?php   endif; ?>

                    </div>
                    <!-- #metabox_field -->
        
                </td>

            </tr>     

  <?php     
        }

        // hiển thị field này ở form tạo term trên màn hình edit-tags
        function extra_front_media_field( $field, $term_meta) { ?>

            <!-- form-field -->
            <div class="form-field">

                <label for="<?php echo $field['id'] ?>">
                     <?php echo $field['title'] ?>
                </label>    

                <!-- metabox_field -->
                <div class="metabox_field">

                    <?php if ( $field['multiple'] ) : ?>
        
                        <div style="margin-bottom: 10px;" class="field_media_navigation mtop10">
                            
                            <img class="field_media_widget_add cpointer vmiddle"
                            
                                    src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_add.png' ?>" 
                                    alt="widget_add" 
                                    
                                    data-image-path="<?php echo METABOX_DIRECTORY_IMAGES_URI ?>" 
                                    data-field-id="<?php printf( 'term_meta[%s]', $field['id'] ); ?>" 
                                    data-thumbnail-enable="<?= isset( $field['thumbnail'] ) && ! $field['thumbnail'] ? 'false' : 'true'  ?>" />

                            <span class="vmiddle">Thêm mới đối tượng</span>


                        </div>
                        
                    <?php endif; ?>                           

                    <p class="description"><?php echo $field['desc'] ?></p>

                    <?php 
                        $media_term_field = $term_meta[ $field['id'] ];

                        if ( is_array( $media_term_field ) && sizeof( $media_term_field ) > 0 ) :
                        
                            $count_field = 1;
                            
                            foreach ( $media_term_field as $media_field_value ): ?>
                        
                                <div class="field mtop10">
                                   
                                   <?php if ( $field['thumbnail'] || ! isset( $field['thumbnail'] ) ) : ?>
                                       <img width="100" src="<?php echo $media_field_value ?>" class="thumbnail_media_field_metabox vmiddle" />
                                   <?php endif; ?>
                                   
                                   <input type="text" name="<?php printf( "term_meta[%s][]", $field['id'] ) ?>" class="media_field_metabox vmiddle" value="<?php echo $media_field_value ?>" />
                                   <input type="button" class="button button-default media_upload vmiddle" value="Chọn ảnh" />
                                   
                                   <?php if ( $count_field > 1 ) :  // not first ?>
                                   
                                       <img src="<?php echo METABOX_DIRECTORY_IMAGES_URI . '/widget_remove.png' ?>" class="field_media_widget_remove vmiddle cpointer" />
                                   
                                   <?php endif; ?> 

                                </div>                                         
                        
            <?php               $count_field++;   

                            endforeach;   

                        else: ?>
                   
                         <div class="field mtop10">
                             
                             <?php if ( $field['thumbnail'] || ! isset( $field['thumbnail'] ) ) : ?>
                                 <img width="100" src="<?php echo $term_meta[ $field['id'] ] ? $term_meta[ $field['id'] ] : METABOX_EMPTY_THUMBNAIL_URI ?>" class="thumbnail_media_field_metabox vmiddle" />
                             <?php endif; ?>
                             
                             <input type="text" 
                                    name="<?php printf( 'term_meta[%s]%s', $field['id'], isset( $field['multiple'] ) && $field['multiple'] ? '[]' : '' ) ?>" 
                                    class="media_field_metabox vmiddle" 
                                    value="<?php echo $media_term_field ?>" />

                             <input type="button" class="button button-default media_upload vmiddle" value="Chọn ảnh" />

                                                
                          </div>
            
            <?php   endif; ?>

                </div>
                <!-- #metabox_field -->

                <br/>

                <!-- description -->
                <p class="description">
                    <?php echo $field['desc'] ?>
                </p>
                <!-- #description -->
               
            </div>     
            <!-- #form-field -->

  <?php     
        }

        function extra_checkbox_field( $field, $term_meta) { ?>

            <tr>  

                <th>
                  <label>
                      <?php echo $field['title']; ?>
                  </label>
                </th>

                <td>

                  <div class="field">

                    <div class="field-cb-group">

                        <?php while ( $data = current( $field['data'] ) ) : 

                                $key = key( $field['data'] );

                                $checkbox_values = explode( ',', $term_meta[ $field['id'] ] );

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
                           name="<?php printf( "term_meta[%s]", $field['id'] ) ?>" 
                           value="<?php echo $term_meta[ $field['id'] ]; ?>" />
                    
                  </div>
        
                </td>

            </tr>     

  <?php     
        }

        // hiển thị field này ở form tạo term trên màn hình edit-tags
        function extra_front_checkbox_field( $field, $term_meta) { ?>

            <!-- form-field -->
            <div class="form-field"> 

                <div class="field">

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
                           name="<?php printf( "term_meta[%s]", $field['id'] ) ?>" 
                           value="<?php echo $term_meta[ $field['id'] ]; ?>" />
                    
                </div>
               
            </div>     
            <!-- #form-field -->

  <?php     
        }

        function extra_tag_field( $field, $term_meta) { ?>

            <tr>  

                <th>
                  <label>
                      <?php echo $field['title']; ?>
                  </label>
                </th>

                <td>

                  <div class="field">

                    <div class="field-cb-group">

                       <div class="field-cb tagsInputBox">
                        
                          <input id="<?php echo "{$field['id']}-{$key}" ?>"
                                 type="text"
                                 class="_taginput tag_field_metabox"
                                 name="<?php echo $field['id'] ?>" />

                        </div>

                    </div>
                    
                    
                  </div>
        
                </td>

            </tr>     

  <?php     
        }

        // hiển thị field này ở form tạo term trên màn hình edit-tags
        function extra_front_tag_field( $field, $term_meta) { ?>

            <!-- form-field -->
            <div class="form-field"> 

                <div class="field">

                    <div class="field-cb-group">

                       

                    </div>                
                    
                </div>
               
            </div>     
            <!-- #form-field -->

  <?php     
        }

        function extra_select_field( $field, $term_meta) {
          
            $validate_condition = isset( $field['validate'] ) && $field['validate']; ?>

            <tr>

                <th scope="row" valign="top">

                    <label for="<?php echo $field['id'] ?>">
                        <?php echo $field['title'] ?>
                    </label>

                </th>

                <td>

                    <select id="sl-<?php echo $field['id'] ?>" 
                            class="txtFTypical tFChange slTermLayout select_field_metabox <?= $validate_condition ? 'validate' : '' ?>">

                        <?php while ( $option_name = current( $field['options'] ) ) : ?>

                                  <option value="<?php echo key( $field['options'] ) ?>" <?php selected( key( $field['options'] ), $term_meta[ $field['id'] ] ) ?> >

                                    <?php echo $option_name ?>

                                  </option>

                        <?php      next( $field['options'] );

                               endwhile; ?>

                    </select>

                    <input type="hidden" id="<?php echo $field['id'] ?>" name="<?php printf( "term_meta[%s]", $field['id'] ) ?>" value="<?php echo $term_meta[ $field['id'] ]; ?>" />

                    <br/>

                    <p class="description"><?php echo $field['desc'] ?></p>
        
                </td>

            </tr>     

 
<?php   }        

        // hiển thị field này ở form tạo term trên màn hình edit-tags
        function extra_front_select_field( $field, $term_meta) { 

            $validate_condition = isset( $field['validate'] ) && $field['validate']; ?>

            <!-- form-field -->
            <div class="form-field">

                <label for="<?php echo $field['id'] ?>">
                     <?php echo $field['title'] ?>
                </label>    
                
                <!-- metabox-field -->
                <div class="metabox-field">

                    <select id="sl-<?php echo $field['id'] ?>"
                            class="txtFTypical tFChange slFrontTermLayout select_field_metabox <?= $validate_condition ? 'validate' : '' ?>">

                        <?php while ( $option_name = current( $field['options'] ) ) : ?>

                              <option value="<?php echo key( $field['options'] ) ?>" <?php selected( key( $field['options'] ), $term_meta[ $field['id'] ] ) ?> >

                                <?php echo $option_name ?>

                              </option>

                    <?php      next( $field['options'] );

                           endwhile; ?>

                    </select>

                    <input type="hidden" id="<?php echo $field['id'] ?>" name="<?php printf( "term_meta[%s]", $field['id'] ) ?>" value="<?php echo $term_meta[ $field['id'] ]; ?>" />

                    <br/>

                    <!-- description -->
                    <p class="description">
                        <?php echo $field['desc'] ?>
                    </p>
                    <!-- #description -->
        
                </div>
                <!-- #metabox-field -->

            </div>
            <!-- #form-field -->     

  <?php    
        }

        function extra_textbox_field( $field, $term_meta) {  ?>

            <tr class="form-field">

                <th scope="row" valign="top">

                    <label for="<?php echo $field['id'] ?>">
                        <?php echo $field['title'] ?>
                    </label>

                </th>

                <td>
                    <input type="text" id="<?php echo $field['id'] ?>" name="<?php printf( "term_meta[%s]", $field['id'] ) ?>" value="<?php echo $term_meta[ $field['id'] ]; ?>" />

                    <br/>

                    <p class="description"><?php echo $field['desc'] ?></p>
        
                </td>

            </tr>     

 
<?php   }

        function extra_front_textbox_field( $field, $term_meta) {  ?>

            <!-- form-field -->
            <div class="form-field">

                <label for="<?php echo $field['id'] ?>">
                     <?php echo $field['title'] ?>
                </label>    
                
                <!-- metabox-field -->
                <div class="metabox-field">                   

                    <input type="text" id="<?php echo $field['id'] ?>" name="<?php printf( "term_meta[%s]", $field['id'] ) ?>" value="<?php echo $term_meta[ $field['id'] ]; ?>" />

                    <br/>

                    <!-- description -->
                    <p class="description">
                        <?php echo $field['desc'] ?>
                    </p>
                    <!-- #description -->
        
                </div>
                <!-- #metabox-field -->

            </div>
            <!-- #form-field -->      

 
<?php   }

        //add extra fields to term edit form callback function
        function extra_term_fields( $tag ) {    //check for existing featured ID

            $t_id = $tag->term_id;
            $term_meta = get_option( "term_$t_id");

            $metaboxes = $this->metaboxes;

            $taxonomy = $_GET['taxonomy']; 
                
            // duyệt từng metabox
            foreach ( $metaboxes as $metabox ) : ?>
             
                 <table class="form-table <?= isset( $metabox['layout'] ) && 
                                           ! empty( $metabox['layout'] ) ? 'form_table_layout ' . $metabox['layout'] . ' mb_hidden' : '' ?>">
        <?php
                    // hiển thị các field của metabox này 
                    foreach ( $metabox['fields'] as $field ) :

                        switch ( $field['type'] ) :

                            case 'select':

                                $this->extra_select_field( $field, $term_meta );
                                
                                break;

                            case 'media':

                                $this->extra_media_field( $field, $term_meta );

                                break;

                            case 'check':

                                $this->extra_checkbox_field( $field, $term_meta );

                                break;

                            case 'tag':

                                $this->extra_tag_field( $field, $term_meta );

                                break;

                            case 'text':

                                $this->extra_textbox_field( $field, $term_meta );
                             
                             default:
                                 
                                 break;

                        endswitch;

                    endforeach; ?>

                </table>

    <?php   endforeach; ?> 
   
    <?php

        }

        //add extra fields to term form form callback function
        function extra_front_term_fields( $tag ) {    //check for existing featured ID
            
            $t_id = $tag->term_id;
            $term_meta = get_option( "term_$t_id");

            $metaboxes = $this->metaboxes;

            $taxonomy = $_GET['taxonomy'];            

            // duyệt từng metabox
            foreach ( $metaboxes as $metabox ) : ?>

                <div class="form-table <?= isset( $metabox['layout'] ) && 
                                           ! empty( $metabox['layout'] ) ? 'form_table_layout ' . $metabox['layout'] . ' mb_hidden' : '' ?>">

    <?php
                   // hiển thị các field của metabox này 
                    foreach ( $metabox['fields'] as $field ) :

                            switch ( $field['type'] ) :

                                case 'select':

                                    $this->extra_front_select_field( $field, $term_meta );
                                    
                                    break;

                                case 'media':

                                    $this->extra_front_media_field( $field, $term_meta );

                                    break;

                                case 'check':

                                    $this->extra_front_checkbox_field( $field, $term_meta );

                                    break;

                                case 'tag':

                                    $this->extra_front_tag_field( $field, $term_meta );

                                    break;

                                case 'text':

                                    $this->extra_front_textbox_field( $field, $term_meta );

                                    break;
                                 
                                 default:
                                     
                                     break;

                            endswitch;

                    endforeach; ?>               

                </div>

    <?php   endforeach;
   
         }
        
        
        // save extra term extra fields callback function
        function save_extra_term_fields( $term_id, $taxonomy ) {            
            
            if ( isset( $_POST['term_meta'] ) ) :
                
                $t_id = $term_id;
                $term_meta = get_option( "term_$t_id");
                
                $term_keys = array_keys( $_POST['term_meta'] );
                
                foreach ($term_keys as $key) :
                    
                    if ( isset( $_POST['term_meta'][$key] ) ) :
                        $term_meta[$key] = $_POST['term_meta'][$key];                       
                    endif;
                    
                endforeach;
                
                //save the option array
                update_option( "term_$t_id", $term_meta );               
                
            endif;
            
        }
    
        function reset_term_form_field_after_create() { 

            $request_uri = $_SERVER['REQUEST_URI'];             

            if ( false !== strpos($request_uri, 'edit-tags.php') &&
                 false !== strpos($request_uri, 'taxonomy=') ) : ?>

                <script type="text/javascript">

                    jQuery(function($) {

                        $(document).ajaxSuccess( function( event, request, settings ) {

                            var data = settings.data;

                            if ( data.indexOf('action=add-tag') !== -1 ) {

                                $('#addtag')[0].reset();

                                var $form_table = $('.form-table.form_table_layout');

                                $form_table.find('img')
                                           .each(function(index, elem) {
                                               
                                                $(elem).attr('src', '<?php echo METABOX_DIRECTORY_IMAGES_URI . "/empty-thumbnail.png" ?>');                                  

                                           });

                                $('#sl-term-field-layout').val('null')
                                                          .change();

                            }

                        });

                    });
                    
                </script>

<?php       endif;

        }

    }

    $term_meta_boxes = new MyThemeTermMetaBoxes();

    $taxonomy = $_GET['taxonomy'];

    if ( isset( $taxonomy ) ) :

        add_filter("{$taxonomy}_edit_form_fields", array( $term_meta_boxes, 'extra_term_fields' ) );    
        add_action("{$taxonomy}_add_form_fields", array( $term_meta_boxes, 'extra_front_term_fields' ), 10, 2);           

    endif;        

    // save extra term extra fields hook
    add_action ( "edited_terms", array( $term_meta_boxes, 'save_extra_term_fields' ), 10, 2 );
    add_action ( "created_term", array( $term_meta_boxes, 'save_extra_term_fields' ), 10, 2 );

    add_action ( "in_admin_footer", array( $term_meta_boxes, 'reset_term_form_field_after_create' ) );