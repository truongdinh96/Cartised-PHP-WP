<?php 
    /*
    * Creating a function to create our CPT
    */
    
    class CustomPostTypeGenerate {
        
        private $post_types = array();
        
        public function __construct() {
            
            include THEME_DIR . '/options/custom_post_types.php';
            $this->set_custom_post_type_registered();
        }

        public function set_custom_post_type_registered() {

            $GLOBALS['_custom_post_types_registered'] = array();
            $post_types = $this->post_types;

            foreach( $post_types as $post_type ) :

                $GLOBALS['_custom_post_types_registered'][] = $post_type;

            endforeach;

        }
    
        public function generate_custom_post_type() {
            
            $post_types = $this->post_types;
            
            foreach ( $post_types as $post_type ) :

                $supports = array( 'title', 'editor', 'excerpt', 'thumbnail' );

                foreach ( $post_type['disable'] as $disable ) :

                    $index = array_search( $disable, $supports );

                    if ( false !== $index ) :

                        array_splice($supports, $index, 1);

                    endif;

                endforeach;
                
                $args = array(
                    'label'               => __( $post_type['label'], 'pitvietco' ),
                    'description'         => __( $post_type['description'], 'pitvietco' ),
                    // Features this CPT supports in Post Editor
                    'supports'            => $supports,
                    // You can associate this CPT with a taxonomy or custom taxonomy. 
                    /* A hierarchical CPT is like Pages and can have
                    * Parent and child items. A non-hierarchical CPT
                    * is like Posts.
                    */  
                    'hierarchical'        => false,
                    'public'              => true,
                    'show_ui'             => true,
                    'show_in_menu'        => true,
                    'show_in_nav_menus'   => true,
                    'show_in_admin_bar'   => true,
                    'menu_position'       => 5,
                    'can_export'          => true,
                    'has_archive'         => true,
                    'exclude_from_search' => false,
                    'publicly_queryable'  => true,
                    'capability_type'     => 'page',
                );
                
                // Registering your Custom Post Type
                register_post_type( $post_type['slug'], $args );

                // tồn tại tham số taxonomy => tạo taxonomy
                if ( isset( $post_type['taxonomy'] ) && is_array( $post_type['taxonomy'] ) ) :

                    $this->generate_taxonomy( $post_type['taxonomy'], $post_type['slug'] );

                endif;
                
            endforeach;
        
        }
        
        /* Hook into the 'init' action so that the function
        * Containing our post type registration is not 
        * unnecessarily executed. 
        */

        // create taxonomy for custom post type
        public function generate_taxonomy( $tax, $post_type ) {

            // Add new taxonomy, make it hierarchical (like categories)
            $labels = array(
                'name'              => _x( $tax['label'], 'taxonomy general name', 'pitvietco' ),
                'singular_name'     => _x( $tax['label'], 'taxonomy singular name', 'pitvietco' )                
            );
            
            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => $tax['slug'] ),
            );

            register_taxonomy( $tax['slug'], $post_type, $args );
        }

    }
    
    $custom_post_types = new CustomPostTypeGenerate();
    add_action( 'init', array( $custom_post_types, 'generate_custom_post_type' ), 0 );

?>