<?php
function getWeatherDatas($ville){
    global $wpdb;
    $registeredkey= $wpdb->get_row( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'appid'", 'ARRAY_A');
    $registeredkey = $registeredkey['option_value'];
 
    $curl = curl_init("https://api.openweathermap.org/data/2.5/weather?q=$ville&appid=$registeredkey&lang=fr&units=metric");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $weather = curl_exec($curl);  
    $weather = json_decode($weather, true);

    return $weather;

    curl_close($curl);
  }

add_shortcode( 'meteo', 'WeatherSC' );

function WeatherSC( $atts ) {
    $a = shortcode_atts( array(
    'ville' => 'Lons-le-Saunier',
    'id' => 'weather'
    ), $atts );
    $output = '<p id="'.$a['id'].'"> Météo de '.$a['ville'].'</p>';
    return displayWeather($a['ville']);
   }

function displayWeather($ville) {
    $weather = getWeatherDatas($ville);

    $icon = $weather['weather']['0']['icon'];
    $iconW = "http://openweathermap.org/img/wn/$icon@2x.png";

    echo "<img src='$iconW'> <br>";
    echo $weather['weather']['0']['description'].'<br>';
    echo $weather['main']['temp'].'°C<br>';
    echo $weather['main']['temp_min'].'°C<br>';
    echo $weather['main']['temp_max'].'°C<br>';
    echo $weather['main']['feels_like'].'<br>';
    echo $weather['name'];

    echo '<pre>';
        // print_r($weather);
    echo '</pre>';







}




 



?>
