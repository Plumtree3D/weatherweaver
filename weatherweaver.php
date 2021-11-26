<?php
/*
Plugin Name: Weather Weaver
Plugin URI: 
Description: A simple but efficient weather plugin !
Version: 1.0
Author: Sel
Author URI: https://ilanr.promo-93.codeur.online/portfolio/
*/

add_action('admin_menu', 'weather_weaver_launch');

function weather_weaver_launch(){
    add_menu_page( 
        'Weather Weaver', 
        'Weather Weaver', 
        'manage_options',
        'weatherweaver',
        'weatherweaver_init',
        'dashicons-pets' );
}
 
function weatherweaver_init(){
    

    if ( ! current_user_can( 'activate_plugins' ) ) return;

    require_once("weather-weaver-admin.php");

    global $wpdb;
    
    if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'weather-weaver'", 'ARRAY_A' ) ) {
       
      $current_user = wp_get_current_user();
      
      // create post object
      $page = array(
        'post_title'  => __( 'Weather Weaver' ),
        'post_status' => 'publish',
        'post_author' => $current_user->ID,
        'post_type'   => 'page',
      );
      
      // insert the post into the database
      wp_insert_post( $page );
    }
}

function deactivate_plugin() {
    $page = get_page_by_path('weather-weaver');
    wp_delete_post($page->ID);
}
register_deactivation_hook( __FILE__, 'deactivate_plugin' );

?> 