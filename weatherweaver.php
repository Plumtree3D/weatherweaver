<?php
/*
Plugin Name: Weather Weaver
Plugin URI: 
Description: A simple but efficient weather plugin !
Version: 1.0
Author: Sel
Author URI: https://ilanr.promo-93.codeur.online/portfolio/
*/

include('includes/shortcodes.php');
add_action('admin_menu', 'weather_weaver_launch');

function weather_weaver_launch(){
    add_menu_page( 
        'Weather Weaver', 
        'Weather Weaver', 
        'manage_options',
        'weatherweaver',
        'weatherweaver_start',
        'dashicons-pets'
      );
}



function weatherweaver_start() {
       require_once(__DIR__ . "../admin/weather-weaver-admin.php");
}

function weatherweaver_init(){

    

    if ( ! current_user_can( 'activate_plugins' ) ) return;



    create_table_shortcodes();
    create_table_communes();

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
    $page = get_page_by_path('Weather Weaver');
    wp_delete_post($page->ID, true);
}
register_deactivation_hook( __FILE__, 'deactivate_plugin' );
register_activation_hook(  __FILE__, 'weatherweaver_init');


?> 