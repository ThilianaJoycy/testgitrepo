<?php
require get_stylesheet_directory() . '/custom.php';
require get_stylesheet_directory() . '/custom_cf7.php';
require get_stylesheet_directory() . '/custom_wc.php';

/*************************************************************************************************
* 	Pretty print, joli var_dump()
*************************************************************************************************/
function pp($arr) {
	echo "<pre style='background:#b9e0f5;padding:1em;margin:1em 0;border-radius:4px;overflow-x:scroll;'>";
	print_r($arr);
	echo "</pre>";
}

function mosaikatwentyfifteen_setup() {
	load_theme_textdomain('msk', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'mosaikatwentyfifteen_setup');

/*************************************************************************************************
* 	On enregistre le style de twentyfifteen
*	On enregistre notre JS pour custom_wc.php
*************************************************************************************************/
function msk_enqueue_twentyfifteen_style() {
    wp_enqueue_style('twentyfifteen', get_template_directory_uri() . '/style.css' );

    wp_enqueue_script('msk_wc', get_stylesheet_directory_uri() . '/js/wc.js', array('jquery', 'wp-ajax-response'));
    wp_localize_script('msk_wc', 'wpajax', array('ajaxurl' => admin_url('admin-ajax.php')));

}
add_action('wp_enqueue_scripts', 'msk_enqueue_twentyfifteen_style');


/*************************************************************************************************
* 	On officialise le support de WC
*************************************************************************************************/
function msk_add_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'msk_add_woocommerce_support');