<?php

	register_sidebar(

		array(
			'name'          => __( 'Header Sidebar', 'pitvietco' ),
			'id'            => 'sidebar-header',
			'description'   => 'This sidebar displays in header',			
			'class'         => 'sidebar',
			'before_widget' => '<div class="widgetbox %1$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widgettitle">',
			'after_title'   => '</div>'
		)

	);
	register_sidebar( 

		array(
			'name'          => __( 'Home Sidebar', 'pitvietco' ),
			'id'            => 'sidebar-home',
			'description'   => 'This sidebar displays in home screen (under slider)',
			'class'         => 'sidebar',
			'before_widget' => '<div class="widgetBox %1$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="toursHeadingTitle %1$s">',
			'after_title'   => '</h2>'
		) 

	);

	register_sidebar(

		array(

			'name'          => __( 'Footer Top Sidebar', 'pitvietco' ),
			'id'            => 'sidebar-top-footer',
			'description'   => 'This sidebar displays in top footer',
			'class'         => 'sidebar',
			'before_widget' => '<div class="footer-top-sidebar %1$s">',							
			'after_widget'  => '</div>',							
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'

		) 

	);		
	
	register_sidebar(

		array(

			'name'          => __( 'Footer Sidebar', 'pitvietco' ),
			'id'            => 'sidebar-footer',
			'description'   => 'This sidebar displays in footer',
			'class'         => 'sidebar',
			'before_widget' => '<div class="footer-sidebar %1$s">',							
			'after_widget'  => '</div>',							
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'

		) 

	);		