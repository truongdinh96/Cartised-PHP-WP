<?php 
	
	class PitViet_NavMenu_Walker extends Walker_Nav_Menu {

		 /**
		 * Phương thức start_lvl()
		 * Được sử dụng để hiển thị các thẻ bắt đầu cấu trúc của một cấp độ mới trong menu. (ví dụ: <ul class="sub-menu">)
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 **/
		 public function start_lvl( &$output, $depth = 0, $args = array() ) {

		 	$indent = str_repeat("\t", $depth);		  
		    $output .= "\n$indent<ul class=\"sub-menu\">\n";
		 
		 }
		 
		 /**
		 * Phương thức end_lvl()
		 * Được sử dụng để hiển thị đoạn kết thúc của một cấp độ mới trong menu. (ví dụ: </ul> )
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 **/
		 public function end_lvl( &$output, $depth = 0, $args = array() ) {	

		 	$indent = str_repeat("\t", $depth);
  			$output .= "$indent</ul>\n";
		 
		 }
		 
		 /**
		 * Phương thức start_el()
		 * Được sử dụng để hiển thị đoạn bắt đầu của một phần tử trong menu. (ví dụ: <li id="menu-item-5"> )
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param string $item | Dữ liệu của các phần tử trong menu
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 * @param interger $id | ID của phần tử hiện tại
		 **/
		 public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		 	$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
	        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
	        $classes[] = 'menu-item-' . $item->ID;
	 
	        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
	        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
	 
	        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
	        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
	 
	        $output .= $indent . '<li' . $id . $class_names .'>';
	 
	        $atts = array();
	        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
	        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
	        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
	        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
	 
	        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
	 
	        $attributes = '';
	        foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                }
	        }
	 
	        $item_output = $args->before;
	        $item_output .= '<a'. $attributes .'>';

	        /** This filter is documented in wp-includes/post-template.php */
	        /*$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . ' ' . $args->link_after;*/

	        $item_output .= "<span class='icon'></span>";

	        $item_output .= "<span class='caption'>" . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . ' ' . $args->link_after;

	        if ( $args->walker->has_children ) :

	        	$item_output .= "<span class='fa fa-angle-down'></span>";

	        endif;

	        $item_output .= "</span>";

	        $item_output .= '</a>';	       
	        $item_output .= $args->after;
	 
	        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		 
		 }
		 
		 /**
		 * Phương thức end_el()
		 * Được sử dụng để hiển thị đoạn kết thúc của một phần tử trong menu. (ví dụ: </li> )
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param string $item | Dữ liệu của các phần tử trong menu
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 * @param interger $id | ID của phần tử hiện tại
		 **/
		 public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		 	$output .= "</li>\n";
		 
		 }
	} // end ThachPham_Nav_Walker

	class PitViet_NavMenu_Mobile_Walker extends Walker_Nav_Menu {

		 /**
		 * Phương thức start_lvl()
		 * Được sử dụng để hiển thị các thẻ bắt đầu cấu trúc của một cấp độ mới trong menu. (ví dụ: <ul class="sub-menu">)
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 **/
		 public function start_lvl( &$output, $depth = 0, $args = array() ) {

		 	$indent = str_repeat("\t", $depth);
		    $output .= "<span class=\"expand\"></span>";
		    $output .= "\n$indent<ul class=\"sub-menu\">\n";
		 
		 }
		 
		 /**
		 * Phương thức end_lvl()
		 * Được sử dụng để hiển thị đoạn kết thúc của một cấp độ mới trong menu. (ví dụ: </ul> )
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 **/
		 public function end_lvl( &$output, $depth = 0, $args = array() ) {	

		 	$indent = str_repeat("\t", $depth);
  			$output .= "$indent</ul>\n";
		 
		 }
		 
		 /**
		 * Phương thức start_el()
		 * Được sử dụng để hiển thị đoạn bắt đầu của một phần tử trong menu. (ví dụ: <li id="menu-item-5"> )
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param string $item | Dữ liệu của các phần tử trong menu
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 * @param interger $id | ID của phần tử hiện tại
		 **/
		 public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {		 	

		 	$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
	        $classes[] = 'menu-item-' . $item->ID;
	 
	        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
	        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
	 
	        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
	        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
	 
	        $output .= $indent . '<li' . $id . $class_names .'>';	        	 	
	 
	        $atts = array();
	        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
	        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
	        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
	        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
	 
	        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
	 
	        $attributes = '';

	        foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                }
	        }
	 
	        $item_output = $args->before;
	        $item_output .= '<a'. $attributes .'>';
	        /** This filter is documented in wp-includes/post-template.php */
	        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
	        $item_output .= '</a>';

	        $item_output .= $args->after;
	 
	        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		 
		 }
		 
		 /**
		 * Phương thức end_el()
		 * Được sử dụng để hiển thị đoạn kết thúc của một phần tử trong menu. (ví dụ: </li> )
		 * @param string $output | Sử dụng để thêm nội dung vào những gì hiển thị ra bên ngoài
		 * @param string $item | Dữ liệu của các phần tử trong menu
		 * @param interger $depth | Cấp độ hiện tại của menu. Cấp độ 0 là lớn nhất.
		 * @param array $args | Các tham số trong hàm wp_nav_menu()
		 * @param interger $id | ID của phần tử hiện tại
		 **/
		 public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		 	$output .= "</li>\n";
		 
		 }
	} // end ThachPham_Nav_Walker

	function new_nav_menu_items($items, $args) {

		if ( isset( $args->mobile ) && $args->mobile ) :

			$languages = qt_get_languages();

        	$output = "<li class='languages'>";

	        $output .= "<div class='languages-wrap'>";

	        	foreach ( $languages as $lang ) :

	        		$output .= "<a class='language-flag {$lang->code}' href='/{$lang->code}'></a>";

	        	endforeach; 

				$output .= "</div>";

			$output .= "</li>";    
	       
	       $items = $output . $items;

	    endif;  

	    return $items;
	}

	add_filter('wp_nav_menu_items', 'new_nav_menu_items', 10, 2);
?>