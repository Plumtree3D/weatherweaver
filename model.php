<?php


function create_table_shortcodes() {

    global $wpdb;

    $table_name = $wpdb->prefix."shortcode";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        shortcode_id int(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        shortcode varchar(30) DEFAULT '') 
        $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

function create_table_communes() {

    global $wpdb;

    $table_name = $wpdb->prefix."communes";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        communes_id int(6) NOT NULL,
        communes_code varchar(6) NOT NULL,
        communes_nom varchar(30) NOT NULL) 
        $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

if(isset($_POST['APPID'])) {
    global $wpdb;
    $table_name = $wpdb->prefix."options";
    $wpdb->insert($table_name, array(
        'option_name' => 'appid',
        'option_value' => $_POST['APPID']
        ) );
}

function select_appid() {
    global $wpdb;
    $registeredkey= $wpdb->get_row( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'appid'", 'ARRAY_A');
    $registeredkey = $registeredkey['option_value'];
    if(is_null($registeredkey)) {
        echo "placeholder='Votre clé d&#39API'";
    } else {
        echo "value='$registeredkey'";
    }
}





?>