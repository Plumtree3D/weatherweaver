<?php 

define( 'SHORTINIT', true );
require( '../../../../wp-load.php' );
search();

function search(){
    global $wpdb;
    $search = sanitize_text_field($_GET['communeSearch']);
    
    $table_name = $wpdb->prefix . 'communes';
    $results =  $wpdb->get_results('SELECT communes_code, communes_nom FROM ' . $table_name . ' WHERE communes_code LIKE "' . $search . '%" OR communes_nom LIKE "%' . $search . '%" LIMIT 15;');
    $resultJSON = json_encode($results, TRUE);
    echo $resultJSON;
    // print_r($results);

}