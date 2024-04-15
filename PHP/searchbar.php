<?php
require_once 'queries.php';
session_start();

$template = file_get_contents("../HTML/searchPage.html");
$ricercaris="";
$ricerca="";
$results="";
if(isset($_POST["cerca_evento"])){
	$ricerca=clearInput($_POST["cerca_evento"]);
	$results = getEventiByTitolo($conn, $ricerca);
    $resultsC = getEventiByCategoria($conn, $ricerca);
}else{
	$results = getProssimiEventi($conn);
}

if($results==False){
    $ricerca="<p>nessun risultato trovato</p>";
}else{
    foreach ($results as $evento) {
        $ricercaris.=
        '<div class="column-home">
            <div class="container-home-evidenza">
                <img src="'.$evento["url_immagine"].'" alt="music event">
                <h4 class="descrizioneEventiT-home">'.$evento["titolo"].'</h4>
                <time class="descrizioneEventiD-home" datetime="2023-12-11 21:00">'.$evento["data_inizio"].'</time>
                <p class="descrizioneEventiL-home">'.$evento["luogo"].'</p>
                <p class="descrizioneEventiG-home">'.$evento["categoria"].'</p>
            </div>
        </div>';
    }
}

if($resultsC==True){
    foreach ($resultsC as $evento) {
        $ricercaris.=
        '<div class="column-home">
            <div class="container-home-evidenza">
                <img src="'.$evento["url_immagine"].'" alt="music event">
                <h4 class="descrizioneEventiT-home">'.$evento["titolo"].'</h4>
                <time class="descrizioneEventiD-home" datetime="2023-12-11 21:00">'.$evento["data_inizio"].'</time>
                <p class="descrizioneEventiL-home">'.$evento["luogo"].'</p>
                <p class="descrizioneEventiG-home">'.$evento["categoria"].'</p>
            </div>
        </div>';
    }
}





$template = str_replace('{RICERCA}', $ricerca, $template);

$template = str_replace('{RISULTATI}', $ricercaris, $template);
echo $template;

?>
