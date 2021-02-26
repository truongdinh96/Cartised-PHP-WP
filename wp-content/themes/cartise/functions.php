<?php
	
	if ( session_status() == PHP_SESSION_NONE ) :
        session_start();
	endif; 


	// Libraries Theme	
	require_once dirname( __FILE__ ) . '/inc/constants.php';
	require_once dirname( __FILE__ ) . '/inc/theme-hooks.php';
	require_once dirname( __FILE__ ) . '/inc/theme-sidebars.php';
	require_once dirname( __FILE__ ) . '/inc/theme-menu-walker.php';
	require_once dirname( __FILE__ ) . '/inc/theme-functions.php';
	require_once dirname( __FILE__ ) . '/inc/theme-settings.php';
	
	require_once get_template_directory() . '/theme_settings/custom_post_types/options.php';
	require_once get_template_directory() . '/theme_settings/theme_options/options.php';
	require_once get_template_directory() . '/theme_settings/metaboxes/options.php';

    // Modules Theme    
    //require_once get_template_directory() . '/modules/combine_admin/combine_admin.php';   

    /*require_once get_template_directory() . '/modules/contact_form/contact_form.php';    
    require_once get_template_directory() . '/modules/sitemap/sitemap.php';    
    require_once get_template_directory() . '/modules/qTranslate/qTranslate_core.php';
    require_once get_template_directory() . '/modules/ultimated_cache/ultimated_cache.php'; */

    // Enqueue theme stylesheet and scripts 
	require_once get_template_directory() . '/inc/theme-enqueue.php';

	require_once dirname( __FILE__ ) . '/inc/customize-functions/form.php';	
	require_once dirname( __FILE__ ) . '/inc/customize-functions/datetime.php';