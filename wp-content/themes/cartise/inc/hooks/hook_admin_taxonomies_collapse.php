<?php
/*
Plugin Name: FT Fold Category Checklist Tree
Version: 1.0
Description: Folds the category hierarchy on the post editing screen
Author: Glenn Ansley at FullThrottle
Author URI: http://fullthrottledevelopment.com
Plugin URI: http://fullthrottledevelopment.com/ft-fold-category-checklist-tree
*/

define( 'FT_FCCT_Version', '1.0' );
      
// Enqueue dependent scripts
function ft_fcct_enqueue_scripts( $page_hook ){
	if ( 'post.php' == $page_hook )
		wp_enqueue_script( 'jquery' );
}
add_action( 'admin_enqueue_scripts', 'ft_fcct_enqueue_scripts' );

// Print my js
function ft_fcct_print_scripts(){
	global $pagenow;
	if ( 'post.php' != $pagenow )
		return;
	?>

	<style type="text/css">

		ul.categorychecklist li:not(.popular-category) {
			padding-left: 21px;
		}

		ul.categorychecklist li li {
			padding-left: 0 !important;
		}

		ul.categorychecklist li > .toggle-child-cats {
			margin-right: 5px;
		}

	</style>

	<script type='text/javascript'>

		jQuery(function($) {

			/*$('.categorychecklist > li').children( '.children' )
										  .hide();*/

			$('.categorychecklist > li:not(.popular-category)').each(function() {

				var $li_childrens = $(this).find('> .children li');

				if ( $li_childrens.length > 0 ) {

					$(this).css('padding-left', '0px');
					$li_childrens.css('padding-left', '0px');

				}

			});

			$('.categorychecklist > li > ul').each(function() {				

				var $this = $(this),
					$checklist = $this.closest('.categorychecklist');

				if ( $this.find('input[type="checkbox"][checked="checked"]').length > 0 ) {

					$this.closest('li')
						 .prependTo( $checklist );

				}

				$this.find('li')
					 .each(function() {

					 	var $_this = $(this),
					 		$parent = $_this.parent('ul');

					 	if ( $_this.find('> label > input[type="checkbox"][checked="checked"]')
					 			   .length > 0 ) {

					 		if ( $parent.length > 0 ) {

					 			$_this.prependTo( $parent );

					 		}

					 	}

					 });

			});

			$('.categorychecklist > li').each(function() {

				var $this = $(this),
					$checklist = $this.closest('.categorychecklist');

				if ( $this.find('> .children').length === 0 ) {

					$this.css('padding-left', '21px');

				}

				if ( $this.find('> label > input[type="checkbox"][checked="checked"]').length > 0 ) {

					$this.prependTo( $checklist );

				}

			});
			
			$('.categorychecklist > li').children( 'ul' )										 
										.each( function() {

				var $this = $(this);

				if ( $this.hasClass('children') ) {

					$this.parent().prepend( '<a href="#" class="toggle-child-cats"><img src="data:image/gif;base64,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" height="8" /></span>' );
					$this.parent().children( 'ul' ).css( 'padding-left', '15px' );

				} else {

					$this.parent().prepend( "<span style='width:1px;padding-left:16px;'></span>" );

				}
			});
			
			$( '.categorychecklist > li' ).children( '.toggle-child-cats' ).click( function() {

				$( this ).parent().children( '.children' ).toggle();
				return false;

			});
			
		});

	</script>

	<?php
}
add_action( 'admin_print_footer_scripts', 'ft_fcct_print_scripts' );