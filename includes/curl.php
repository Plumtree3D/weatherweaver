<?php


if(isset($_POST['init'])) {
    insert_communes();
}




function insert_communes() {

    $curl = curl_init("https://geo.api.gouv.fr/communes");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CAINFO, __DIR__.'/certificat.cer');
    $communes = curl_exec($curl);

    $error= curl_error($curl);

    $communes = json_decode($communes, true);



    // var_dump($communes);
    global $wpdb;
    $table_name = $wpdb->prefix."communes";
    $truncate = "TRUNCATE ".$table_name ;
    
    $wpdb->query($wpdb->prepare($truncate));

    $arrayCommunes = array();
    $place_holders = array();

    $query = "INSERT INTO $table_name (communes_id, communes_code, communes_nom) VALUES ";

    foreach ($communes as $commune ) {
        foreach ($commune['codesPostaux'] as $codePostal) {
            array_push( $arrayCommunes, $commune['code'], $codePostal, $commune['nom']);
            $place_holders[] = "( %s, %s, %s)";
            // echo $commune['nom']." - ".$codePostal." - ".$commune['code']."<br>"  ;
        }
    }

    $query .= implode(', ', $place_holders);
    $wpdb->query($wpdb->prepare("$query ", $arrayCommunes));




    curl_close($curl); 
}



?>
