<?php require_once(__DIR__ . "/../model.php"); ?>

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



    <h3> Indiquez la commune cible </h3>

<form action="" method="GET" autocomplete="off">
    <label for="communeSearch">Chosissez une commune</label>
    <input list="communes" id="communeSearch" oninput="searching(this.value)" name="communeSearch" placeholder="Code postal ou ville" />
    <input type="submit" value="Let's go">
</form>


<!-- SHORTCODE OUTPUT -->
<input type="text" value="[shortcode - ]" id="shortcode">
<button onclick="CTC()"> Copier </button>



<datalist id="communes"> 
    <div id="options">
        <script>
            function searching(search) {
                var ajax = new XMLHttpRequest();
                console.log(search);
                // ajax.onreadystatechange = function() {
                // ajax.responseType = 'json';
                ajax.open('GET', '../wp-content/plugins/weatherweaver/includes/ajax.php?communeSearch='+search);
                ajax.onloadend = function() {setlist(this.response)};
                ajax.send();
            }
            function setlist(response){
                let communes = JSON.parse(response);
                console.log(communes);
                document.getElementById("options").innerHTML = "";
                for (commune  of communes){
                    console.log(commune);
                    document.getElementById("options").innerHTML += '<option value="'+commune.communes_code+' - '+commune.communes_nom+'">';
                    
            }
        }
        </script>
    </div>       
</datalist>


<script>
    function CTC() {
    /* Get the text field */
    var copyText = document.getElementById("shortcode");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);

}


</script>


<?php 
require_once(__DIR__ . "/../includes/curl.php");