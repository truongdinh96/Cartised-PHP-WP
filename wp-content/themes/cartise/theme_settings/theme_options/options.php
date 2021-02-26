<?php

include 'includes/MyHelperOptionPage.php';

class MyThemeOptionPage {
    
    /**
     * Holds the values to be used in the fields callbacks
     */
    
    private $options;
    
    private $currentsection = array();
    private $currentfield = array();
    
    private $sections;
    private $helper;
    
    /**
     * Start up
     */       
     public function __construct() {
        
        include THEME_DIR . '/options/theme_options.php';
        
        $this->helper = new MyHelperOptionPage();
        
        add_action('admin_menu', array(
            $this,
            'add_plugin_page'
        ));
        
        add_action('admin_init', array(
            $this,
            'page_init'
        ));
    }
    
    /**
     * Add options page
     */
    
    public function add_plugin_page() {

        global $theme_ui_language;
        
        $sections = $this->sections;  
     
        add_menu_page(__( 'Theme Option', 'pitvietco' ), // Thay title của trang Theme Option
            'Theme Option', // Thay tên hiển thị trên Menu
            'manage_options', 'theme-option', null, THEME_DIR_URI . '/theme_settings/theme_options/theme_option.png' // Thay icon của bạn
            );           
            
         foreach( $sections as $section ) :

            if ( $section === reset( $sections ) ) :

              $section_url = 'theme-option';

            else :

              $section_url = $section['id'] . '-theme-options';

            endif;

            $section_title = is_array( $section["title"] ) ? $section["title"]["$theme_ui_language"] : "";

            $section_hook = add_submenu_page('theme-option', 'Theme Option - ' .  $section_title, $section_title , 'manage_options', $section_url, array(
                &$this,
                'create_section_page'
              )
            );
            
             $this->currentsection[ $section_hook ] = $section;
            
        endforeach;
        
    }
    
    /**
     * Register and add settings
     */
    public function page_init() {

        global $theme_ui_language;
        
        $sections = $this->sections;
        $current_field_section_id = ''; // lưu id của element có type = section
        
        foreach ( $sections as $section ) :
            
            register_setting( $section['id'] . '_option_group', // Option group
                $section['id'] . '_option_name' // Option name               
            );
            
            if ( sizeof( $section['fields'] ) > 0 ) :
                
                foreach ( $section['fields'] as $field ) :
                    
                    if ( $this->helper->is_field_type ( $field['type'], 'section' ) ) : // field có type là section
                        
                        if ( $field['indent'] ) : // bắt đầu một section có property indent = true
                            
                            if ( '' !== $current_field_section_id ) : // lỗi không đóng section trước đó
                                
                                header('Content-Type: text/plain; charset=utf-8');
                                echo 'Phát sinh lỗi: Chưa có mảng kết thúc section có id ["' . $current_field_section_id . '"]';
                                die();
                                
                            endif;
                            
                            $current_field_section_id = $field['id'];

                            $field_title = is_array( $field["title"] ) ? $field["title"]["$theme_ui_language"] : "";
                            $field_desc = is_array( $field["desc"] ) ? $field["desc"]["$theme_ui_language"] : "";                         
                            
                            add_settings_section(
                                $current_field_section_id . '-id', // ID
                                $field_title .
                                ' <div class="desc normal mtop5">' . $field_desc . '</div>', // Title
                                array( &$this, 'section_print_callback' ), // Callback
                                $section['id'] . '-setting-admin' // Page
                            );
                            
                        else : // kết thúc một section có property indent = false
                            
                            $current_field_section_id = '';
                        endif;
                        
                    else : // không phải là field có type là section

                        $field_title = is_array( $field["title"] ) ? $field["title"]["$theme_ui_language"] : "";
                        $field_desc = is_array( $field["desc"] ) ? $field["desc"]["$theme_ui_language"] : "";                         
            
                       add_settings_field(
                            
                            $field['id'] . '-id', // ID
                             $field_title .
                            ' <div class="desc normal mtop5">' .  $field_desc . '</div>',
                            array( &$this, 'id_print_field_callback' ), // Callback
                            $section['id'] . '-setting-admin', // Page
                            $current_field_section_id . '-id', // Section Parent ID
                            array(
                                'field' => $field,
                                'section' => $section
                            )
                            
                        );      
                        
                        
                        
                    endif;
                    
                endforeach;
                
            endif;
            
        endforeach;
        
    }
    
    public function create_section_page() {

        global $theme_ui_language;
        
        $section = $this->currentsection[ current_filter() ];

        $field_title = is_array( $section["title"] ) ? $section["title"]["$theme_ui_language"] : "";
        $field_desc = is_array( $section["desc"] ) ? $section["desc"]["$theme_ui_language"] : "";
        
         // Set class property
        $this->options = get_option( $section['id'] . '_option_name' ); ?>
        
        <div class="wrap">
            <h1><?php echo  $field_title ?></h1>
            <div class="desc"><?php echo  $field_desc ?></div>
            <form id="frmThemeOption" method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( $section['id'] . '_option_group' );
                do_settings_sections($section['id'] . '-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>       
       
         
    <?php }  
     
     
    
    /**
     * Options page callback
    */
    
    public function section_print_callback() {
        print '';
    }
    
    /* media field */
    
    public function print_field_media_type_callback($field, $section) {

            global $theme_ui_language;
        
            ob_start(); ?>
                
                <div class="media_thumbnail">
                    <img id="<?php echo $field['id'] . '-id' ?>"  src="<?= isset( $this->options[ $field['id'] . '-id' ] ) ? esc_attr( $this->options[ $field['id'] . '-id' ] ) : '' ?>" alt="" />
                    <input id="<?php echo $field['id'] . '-input-id' ?>" type="hidden" name="<?php echo $section['id'] . '_option_name[' . $field['id'] . '-id]' ?>" value="<?= isset( $this->options[ $field['id'] . '-id' ] ) ? esc_attr( $this->options[ $field['id'] . '-id' ] ) : '' ?>" />
                </div>
                
                <div class="media_select">

                    <a class="button button-default media_upload" 
                       data-upload-startwiths-id="<?php echo $field['id'] ?>" 
                       href="#">
                       <?= 'vi' === $theme_ui_language ? 'Chọn ảnh' : 'Choose an image' ?>
                   </a>

                </div>
            
    <?php   $contents = ob_get_contents();
            ob_end_clean();
            
            printf( $contents );
        }
        
    /* #media field */
    
    /* editor field */
    
    public function print_field_editor_type_callback($field, $section) {
            
        $settings = array(
            'teeny' => true,
            'textarea_rows' => 15,
            'tabindex' => 1
        );
        
        wp_editor( isset( $this->options[ $field['id'] . '-id' ] ) ? $this->options[ $field['id'] . '-id' ] : '', $field['id'] . '-id', $settings); ?>
        
        <input id="<?php echo $field['id'] . '-id-editor' ?>" type="hidden" name="<?php echo $section['id'] . '_option_name[' . $field['id'] . '-id]' ?>" value="" />
        
<?php }
    
    /* #editor field */
    
    /* range field */
    
    public function print_field_range_type_callback($field, $section) {
          
            ob_start(); ?>
                
                <div class="theme_field_box">

                    <input id="<?php echo $field['id'] . '-id' ?>" 
                           class="txtFTypical tFChange txtRangeObj" 
                           type="range" 
                           min="<?php echo $field['min'] ?>" 
                           max="<?php echo $field['max'] ?>" 
                           step="<?php echo $field['step'] ?>" 
                           value="<?= isset( $this->options[ $field['id'] . '-id' ] ) ? esc_attr( $this->options[ $field['id'] . '-id' ] ) : $field['value'] ?>" />
                    
                    <input id="<?php echo $field['id'] . '-input-id' ?>" class="txtRangeNum" type="text" name="<?php echo $section['id'] . '_option_name[' . $field['id'] . '-id]' ?>" value="<?= isset( $this->options[ $field['id'] . '-id' ] ) ? esc_attr( $this->options[ $field['id'] . '-id' ] ) : $field['value'] ?>" readonly />
                
                </div>
            
    <?php   $contents = ob_get_contents();
            ob_end_clean();
            
            printf( $contents );
        }
        
    /* #range field */
        
    /* select field */

    public function print_defined_select_options($field, $section) {
        
        $sl_options = '';    

        global $theme_ui_language;

        while ( $option = current( $field['data'] ) ) :

            $value = key( $field['data'] );
           
            $sl_options .= sprintf('<option value="%1$s" %2$s>%3$s</option>', 
                                    $value,
                                    $value === $this->options[ $field['id'] . '-id' ] ? 'selected="selected"' : '',
                                    $option["$theme_ui_language"]
                                );

            next( $field['data'] );
           
        endwhile;
       
        return $sl_options;
        
    }
        
    public function print_category_field_select_options($field, $section) {
        
        $sl_options = '';
        
        $args = array(
                'hide_empty' => '0',
                'order' => 'asc',
                'orderby' => 'id'
            );
            
        $categories = get_categories( $args );
        
        $sl_options .= sprintf('<option value="" %1$s></option>', ! isset( $this->options[ $field['id'] . '-id' ] ) ? 'selected="selected"' : '' );
        
       foreach ( $categories as $category ) :
           
           $sl_options .= sprintf('<option value="%1$s" %2$s>%3$s</option>', 
                                    $category->ID,
                                    $category->ID === $this->options[ $field['id'] . '-id' ] ? 'selected="selected"' : '',
                                    $category->name
                                );
           
       endforeach;
       
       return $sl_options;
        
    }
    
    public function print_taxonomy_field_select_options($field, $section) {
        
        $sl_options = '';
        
        $args = array(
          'public'   => true,
          '_builtin' => false
          
        ); 
        
        $output = 'names'; // or objects
        $operator = 'and'; // 'and' or 'or'
        
        $taxonomies = get_taxonomies( $args, $output, $operator );
        
        $sl_options .= sprintf('<option value="" %1$s></option>', ! isset( $this->options[ $field['id'] . '-id' ] ) ? 'selected="selected"' : '' );
        
        if ( $taxonomies ) :
            
          foreach ( $taxonomies as $taxonomy ) :
              
            $sl_options .= sprintf('<option value="%1$s" %2$s>%3$s</option>', 
                                     $taxonomy,
                                     $taxonomy === $this->options[ $field['id'] . '-id' ] ? 'selected="selected"' : '',
                                     $taxonomy
                            );
            
          endforeach;
          
        endif;
       
       return $sl_options;
        
    }
    
    public function print_term_field_select_options($field, $section) {
        
        $sl_options = '';
        
        $args = array(
            'hide_empty' => '0',
            'order' => 'asc',
            'orderby' => 'id'
        );
            
        $terms = get_terms( $args );
        
        $sl_options .= sprintf('<option value="" %1$s></option>', ! isset( $this->options[ $field['id'] . '-id' ] ) ? 'selected="selected"' : '' );
        
       foreach ( $terms as $term ) :
           
           $sl_options .= sprintf('<option value="%value" %selected>%text</option>', 
                                    $term->ID,
                                    $term->ID == $this->options[ $field['id'] . '-id' ] ? 'selected="selected"' : '',
                                    $term->name
                            );
           
       endforeach;
       
       return $sl_options;
        
    }
    
    public function print_page_field_select_options($field, $section) {
        
        $sl_options = '';
        
        $args = array(
            'hide_empty' => '0',
            'order' => 'asc',
            'orderby' => 'id'
        );
            
        $pages = get_pages( $args );
        
        $sl_options .= sprintf('<option value="" %1$s></option>', ! isset( $this->options[ $field['id'] . '-id' ] ) ? 'selected="selected"' : '' );
        
       foreach ( $pages as $page ) :
           
           $sl_options .= sprintf('<option value="%1$s" %2$s>%3$s</option>', 
                                    $page->ID,
                                    $page->ID == $this->options[ $field['id'] . '-id' ] ? 'selected="selected"' : '',
                                    $page->post_title 
                            );
           
       endforeach;
       
       return $sl_options;
        
    }
    
    public function print_post_type_field_select_options($field, $section) {
        
        $sl_options = '';
        
       $args = array(
           'public'   => true,
           '_builtin' => false
        );
        
        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        
        $post_types = get_post_types( $args, $output, $operator ); 
        
        $sl_options .= sprintf('<option value="" %1$s></option>', ! isset( $this->options[ $field['id'] . '-id' ] ) ? 'selected="selected"' : '' );
        
        foreach ( $post_types as $post_type ) :
            
           $sl_options .= sprintf('<option value="%1$s" %2$s>%3$s</option>', 
                               $post_type,
                               $post_type === $this->options[ $field['id'] . '-id' ] ? 'selected="selected"' : '',
                               $post_type 
                            );
           
        endforeach;
       
       return $sl_options;
        
    }
    
    public function print_field_select_options($field, $section) {
        
        $sl_options = '';
        
        switch ( $field['data'] ) :
            
            case 'category':
            case 'categories':
                
                $sl_options = $this->print_category_field_select_options($field, $section);
                
                break;
                
            case 'term':
            case 'terms':
                
                $sl_options = $this->print_term_field_select_options($field, $section);
                
                break;
                
            case 'taxonomy':
            case 'taxonomies':
                
                $sl_options = $this->print_taxonomy_field_select_options($field, $section);
                
                break;
                
            case 'page':
            case 'pages':
                
                $sl_options = $this->print_page_field_select_options($field, $section);
                
                break;
                
            case 'post_type':
                
                $sl_options = $this->print_post_type_field_select_options($field, $section);
                
                break;
            
            default:

                $sl_options = $this->print_defined_select_options($field, $section);

                break;
                
        endswitch;
        
        return $sl_options;
        
    }
    
    public function print_field_select_type_callback($field, $section) {
        
        ob_start(); ?>
            
            <div class="theme_field_box">
                
                <select id="<?php echo $field['id'] . '-id-selectbox' ?>" 
                        class="txtFTypical tFChange selectFieldBox">
                    
                    <?php echo $this->print_field_select_options( $field, $section );  ?>
                    
                </select>
                
                <input id="<?php echo $field['id'] . '-input-id' ?>" type="hidden" name="<?php echo $section['id'] . '_option_name[' . $field['id'] . '-id]' ?>" value="<?= isset( $this->options[ $field['id'] . '-id' ] ) ? esc_attr( $this->options[ $field['id'] . '-id' ] ) : '' ?>" />
                
            </div>
        
<?php   $contents = ob_get_contents();
        ob_end_clean();
        
        printf( $contents );
    }
    
     /* #select field */
     
    /* text field */
    public function print_field_text_type_callback($field, $section) {
        
        ob_start(); ?>
                
                <div class="theme_field_box">
                    <input id="<?php echo $field['id'] . '-id' ?>" 
                           name="<?php echo $section['id'] . '_option_name[' . $field['id'] . '-id]' ?>" 
                           type="text" 
                           value="<?= isset( $this->options[ $field['id'] . '-id' ] ) ? esc_attr( $this->options[ $field['id'] . '-id' ] ) : '' ?>" 

                           <?php while ( $attribute_value = current( $field['attributes'] ) ) : 

                                    $attribute_name = key( $field['attributes']  ); 
                                    echo " {$attribute_name}={$attribute_value} ";

                                    next( $field['attributes'] );

                                endwhile; ?> />
                </div>
            
    <?php   $contents = ob_get_contents();
            ob_end_clean();
            
            echo $contents;
        
    }
    /* #text field */

    /* textarea field */
    public function print_field_textarea_type_callback($field, $section) {
        
        ob_start(); ?>
                
                <div class="theme_field_box">

                    <textarea id="<?php echo $field['id'] . '-id' ?>" 
                              name="<?php echo $section['id'] . '_option_name[' . $field['id'] . '-id]' ?>"
                              <?php while ( $attribute_value = current( $field['attributes'] ) ) : 

                                    $attribute_name = key( $field['attributes']  ); 
                                    echo " {$attribute_name}={$attribute_value} ";

                                    next( $field['attributes'] );

                                endwhile; ?>><?= isset( $this->options[ $field['id'] . '-id' ] ) ? $this->options[ $field['id'] . '-id' ] : '' ?></textarea>
                    
                </div>
            
    <?php   $contents = ob_get_contents();
            ob_end_clean();
            
            echo $contents;
        
    }
    /* #textarea field */
    
    public function id_print_field_callback($args) {
       
        $field =  $args['field'];
        $section = $args['section'];
        
        switch ( $field['type'] ) :
            
            case 'media':
                
                $this->print_field_media_type_callback($field, $section);
                
                break;
                
            case 'select':
                
                $this->print_field_select_type_callback($field, $section);
                
                break;
                
            case 'range':
                
                $this->print_field_range_type_callback($field, $section);
                
                break;
                
            case 'editor':
                
                $this->print_field_editor_type_callback($field, $section);
                break;
                
            case 'text':
                
                $this->print_field_text_type_callback($field, $section);
                break;

             case 'textarea':
                
                $this->print_field_textarea_type_callback($field, $section);
                break;
            
            default:
                break;
                
        endswitch;
        
    }
  
}
    
function theme_option_load_style() {

    global $combine_admin_enqueue;

    $request_uri = $_SERVER['REQUEST_URI'];
    
    if ( false !== strpos( $request_uri, 'theme-option' ) ) :
        
        wp_enqueue_media();

        wp_enqueue_style( 'dealsdot-themeoption-style' , THEME_DIR_URI . '/theme_settings/theme_options/css/style.min.css' );
        wp_enqueue_script( 'dealsdot-themeoption-field-media-js' , THEME_DIR_URI . '/theme_settings/theme_options/js/field_media.min.js' );
        wp_enqueue_script( 'dealsdot-themeoption-field-editor-js' , THEME_DIR_URI . '/theme_settings/theme_options/js/field_editor.min.js' );
        wp_enqueue_script( 'dealsdot-themeoption-typical-js' , THEME_DIR_URI . '/theme_settings/theme_options/js/field_typical.min.js' );

        //$combine_admin_enqueue['stylesheet'][] = THEME_DIR_URI . '/theme_settings/theme_options/css/style.min.css';
        
        //$combine_admin_enqueue['scripts'][] = THEME_DIR_URI . '/theme_settings/theme_options/js/field_media.min.js';
        //$combine_admin_enqueue['scripts'][] = THEME_DIR_URI . '/theme_settings/theme_options/js/field_editor.min.js';                
        //$combine_admin_enqueue['scripts'][] = THEME_DIR_URI . '/theme_settings/theme_options/js/field_typical.min.js';                       
        
        
    endif;
    
}

add_action( 'admin_init', 'theme_option_load_style' );

if ( is_admin() ) :

    $my_theme_option_page = new MyThemeOptionPage();

endif;