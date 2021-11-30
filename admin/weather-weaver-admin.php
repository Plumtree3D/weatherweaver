 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

 <form action="" method="POST">
     <label for="init"> Initialiser le plugin. 
         (L'opération va prendre un certain temps)</label> <br>
    <input type="submit" name="init" value="C'est parti">
 </form>

 <?php


?>


 <h2> Paramètres </h2>
 <hr />

    

    <form method="POST" action=''>
        <h3> Renseignez une clé d'API OpenWeather [APPID] </h3>

        <label> APPID:</label>
        <input type="text" name="APPID" <?=select_appid()?> > <input type="submit" name=submit_APPID>

    </form> 
<?php if(isset($_POST['APPID'])) {
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


    <h3> Indiquez la commune cible </h3>
        <label> Commune ou code postal </label> <br>
        <select name="" id="">
            <option value=""> Sélectionnez une option</option>
        </select>



<?php 
require_once(__DIR__ . "/../includes/curl.php");