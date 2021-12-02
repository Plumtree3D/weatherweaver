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
    'disp' => 'basic',
    'id' => 'weather'
    ), $atts );
    $output = '<p id="'.$a['id'].'"> Météo de '.$a['ville'].'</p>';
    return displayWeather($a['ville'],$a['disp']);
   }

function displayWeather($ville, $display) {
    $weather = getWeatherDatas($ville);

    $icon = $weather['weather']['0']['icon'];
    $iconW = "http://openweathermap.org/img/wn/$icon@2x.png";

    $temp = round($weather['main']['temp'],0);



        if($display == "min" || $display == "basic" || $display == "fine") { ?>
        <div class="WWbackground">

            <img src="<?=$iconW?>" alt="<?=$weather['weather']['0']['description']?>">
            <div class="WWrow">
                <h4 id="city"> <?= $weather['name'] ?></h4> 
                <span><?=ucfirst($weather['weather']['0']['description'])?> </span>
                <span id="humidity"> humidité: <?=$weather['main']['humidity']?> % </span>
            </div>
            <div id='temps'>
                <h2 id="temp"> <?=round($weather['main']['temp'],0)?>° </h2>
                <span> <?= round($weather['main']['temp_min'],0)?> / <?= round($weather['main']['temp_max'], 0) ?> </span>
            </div> 
            
 
        </div>
            <!-- echo $weather['name'].'<br>';
            echo '<img src="'.$iconW.'"> <h2>'.round($weather['main']['temp'],0).'°C </h2> <br>';
            echo ucfirst($weather['weather']['0']['description']).'<br>'; -->
            <?php
        }

        if($display == "basic" || $display == "fine") {
            echo 'minimale '.round($weather['main']['temp_min'],0).'°C<br>';
            echo 'maximale '.round($weather['main']['temp_max'],0).'°C<br>';
            echo 'ressenti '.round($weather['main']['feels_like'],0).'°C<br>';
            echo 'Vitesse du vent '.round(($weather['wind']['speed']*3.6),2).' Km/h <br>';
            echo 'Humidité '.$weather['main']['humidity'].'%<br>';
        } else if($display == "fine") {

    }

    echo '<pre>';
        // print_r($weather);
    echo '</pre>';

}



?>

<style>
    .WWbackground {
        display: flex;
        flex-direction: row;
        align-items: center;  
    }


    .WWrow {
        display: flex;
        flex-direction: column;
        justify-content: start; 
        align-content: start;
    }

    #temp {
        
        font-size: 42px;
    }

    #city {
        font-weight: 800;
        font-size: 18px;
        padding-bottom: 3px;
    }

    #humidity {
        font-size: 16px;
    }

    #temps {
        padding-left: 15px;
    }






</style>