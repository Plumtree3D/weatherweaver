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

    $cardinals = wind_cardinals($weather['wind']['deg']);
    setlocale (LC_TIME, 'fra.UTF-8'); 

            if($display == "min")  { ?>
            <div class="WWcol">
                
                
                <div class="WWrow">
                    <h2 class="temp"> <?=round($weather['main']['temp'],0)?>° </h2>
                    <img src="<?=$iconW?>" alt="<?=$weather['weather']['0']['description']?>">  
                </div>
                
                <h4 id="city"> <?= $weather['name'] ?></h4>                   
                
            </div>

            <?php }


         
            if($display == "basic")  { ?>

            <div class="WWrow">
                <img src="<?=$iconW?>" alt="<?=$weather['weather']['0']['description']?>">
                <div class="WWcol">
                    <h4 id="city"> <?= $weather['name'] ?></h4> 
                    <span><?=ucfirst($weather['weather']['0']['description'])?> </span>
                    <span> <?=ucwords(strftime("%A %d %B"))?> </span>
                </div>
                <div class='temps'>
                    <h2 class="temp"> <?=round($weather['main']['temp'],0)?>° </h2>
                    <span> <?= round($weather['main']['temp_min'],0)?> / <?= round($weather['main']['temp_max'], 0) ?> </span>
                </div> 
            </div>
            

<?php
            }

            if($display == "fine") { ?>

            <div class="WWrow">
                <div class="WWrow">
                    <img src="<?=$iconW?>" alt="<?=$weather['weather']['0']['description']?>">
                    <div class='temps'>
                        <h2 class="temp"> <?=round($weather['main']['temp'],0)?>° </h2>
                        <span> <?= round($weather['main']['temp_min'],0)?> / <?= round($weather['main']['temp_max'], 0) ?> </span>
                    </div>
                     
                </div>   
                <div class="WWcol rightalign">
                    <h4 id="city"> <?= $weather['name'] ?></h4> 
                    <span><?=ucfirst($weather['weather']['0']['description'])?> </span>
                    <span> <?=ucwords(strftime("%A %d %B"))?> </span>
                </div>
                <div class="WWcol" style="text-align: center;">
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/sunrise.svg'; ?>">
                    <?=wp_date(("H:i"),$weather['sys']['sunrise']); ?>
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/sunset.svg'; ?>">
                    <?=wp_date(("H:i"),$weather['sys']['sunset']); ?>
                </div>
                

                <div class="WWrow">
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/sleeve.svg'; ?>">
                    <div class="WWcol">
                        Vent à <?=round(($weather['wind']['speed']*3.6),2)?> Km/h <br>
                        Avec des rafales à <?=round(($weather['wind']['gust']*3.6),2)?> Km/h <br>
                        Humidité <?=$weather['main']['humidity']?> % <br>
                        Pression atmosphérique <?=$weather['main']['pressure']?> hPa
                    </div>
                </div>
                <div class="WWcol windDeg" style="text-align: center;">
                    <span class="tiny"> Direction </span>
                    <div style="border-radius: 50%; text-align: center; height: 60px; width: 60px; border-top: solid 3px #E5E5E5; font-size: 46px; transform: rotate(<?=$weather['wind']['deg']?>deg); color: #E5E5E5;">
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/wind.svg'; ?>">
                    </div>    
                    
                    <?=$cardinals ?>
                </div>



            </div>

            <hr>



                

    <?php
            } else if($display == "fine") {

        }
        ?>
        </div>
        <?php
        // echo '<pre>';
        //     print_r($weather);
        // echo '</pre>';

}

function wind_cardinals($deg) {
	$cardinalDirections = array(
		'Nord' => array(348.75, 360),
		'Nord' => array(0, 11.25),
		'NNE' => array(11.25, 33.75),
		'N-E' => array(33.75, 56.25),
		'ENE' => array(56.25, 78.75),
		'Est' => array(78.75, 101.25),
		'ESE' => array(101.25, 123.75),
		'S-E' => array(123.75, 146.25),
		'SSE' => array(146.25, 168.75),
		'Sud' => array(168.75, 191.25),
		'SSW' => array(191.25, 213.75),
		'S-O' => array(213.75, 236.25),
		'OSO' => array(236.25, 258.75),
		'Ouest' => array(258.75, 281.25),
		'ONO' => array(281.25, 303.75),
		'N-O' => array(303.75, 326.25),
		'NNO' => array(326.25, 348.75)
	);
	foreach ($cardinalDirections as $dir => $angles) {
			if ($deg >= $angles[0] && $deg < $angles[1]) {
				$cardinal = $dir;
			}
		}
		return $cardinal;
}



?>

<style>
    .WWrow {
        display: flex;
        flex-direction: row;
        align-items: center;  
        justify-content: space-evenly;
        flex-wrap: wrap;
    }


    .WWcol {
        display: flex;
        flex-direction: column;
        justify-content: start; 
        align-content: start;
        margin-left: 4px;
    }

    .temp {
        
        font-size: 42px;
    }

    #city {
        font-weight: 800;
        font-size: 18px;
        padding-bottom: 3px;
    }

    .tiny {
        font-size: 12px;
    }

    .temps {
        padding-left: 15px;
    }

    .rightalign {
        text-align: right;
        justify-self: end;
    }




</style>