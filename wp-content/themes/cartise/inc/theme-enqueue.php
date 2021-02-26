<?php
function enqueue_theme_styles() {

	wp_enqueue_script('jquery', THEME_DIR_URI . '/assets/js/jquery.min.js');
	wp_enqueue_script('jquery-ui', THEME_DIR_URI . '/assets/js/jquery-ui.js');
	wp_enqueue_script('bootstrap-js', THEME_DIR_URI . '/assets/js/bootstrap.min.js');
	wp_enqueue_script('form-tablists-js', THEME_DIR_URI . '/assets/js/form-tablists.js');

	wp_enqueue_style('bootstrap-css', THEME_DIR_URI . '/assets/css/bootstrap.min.css');
	wp_enqueue_style('bootstrap-theme-css', THEME_DIR_URI . '/assets/css/bootstrap-theme.css');
	wp_enqueue_style('jquery-ui-css', THEME_DIR_URI . '/assets/css/jquery-ui.css');
	wp_enqueue_style('theme-layout-css', THEME_DIR_URI . '/assets/css/layout.css');
	wp_enqueue_style('theme-stylesheet', THEME_DIR_URI . '/style.css');
	wp_enqueue_style('theme-google-fonts-css', THEME_DIR_URI . '/assets/css/fonts.css');
	wp_enqueue_style('theme-fontawesome-css', THEME_DIR_URI . '/assets/css/font-awesome.min.css');

}

add_action('wp_enqueue_scripts', 'enqueue_theme_styles');