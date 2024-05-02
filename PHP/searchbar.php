<?php
require_once 'queries.php';
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: ../HTML/index.html");
    exit();
}
function generateSearchResultItem($evento) {
    return '<div class="column-home">
    <a class="a_evento" href="../PHP/pageEvent.php?evento='.$evento["evento_id"].'">
                    <div class="container-home-evidenza">
                        <img src="'.$evento["url_immagine"].'" alt="Scheda dell&#39;evento '.$evento["titolo"].'">
                        <h4 class="descrizioneEventiRic" aria-hidden="true">'.$evento["titolo"].'</h4>
                        <time class="descrizioneEventiRicD" datetime="2023-12-11 21:00" aria-hidden="true">'.$evento["data_inizio"].'</time>
                        <p class="descrizioneEventiRicL" aria-hidden="true">'.$evento["luogo"].'</p>
                        <p class="descrizioneEventiRicG" aria-hidden="true">'.$evento["categoria"].'</p>
                    </div>
                </a>
            </div>';
}
$template = file_get_contents("../HTML/searchPage.html");
$ricercaris="";
$ricerca="";
$results="";
if(isset($_POST["cerca_evento"])){
    $ricerca=clearInput($_POST["cerca_evento"]);
    if(!empty($ricerca)) {
        $results = getEventiByTitolo($conn, $ricerca);
        $resultsC = getEventiByCategoria($conn, $ricerca);
    } else {
        $results = "empty";
    }
} else {
    $results = getProssimiEventi($conn);
}
if($results == "empty") {
    $ricerca = "<h2 tabindex='0'>Inserisci un termine di ricerca</h2>";
} else if($results == false && $resultsC == false) {
    $ricerca = "<h2 tabindex='0'>Nessun risultato trovato per &#34" .$ricerca ."&#34</h2>";
} else {
    $ricerca = "<h2 tabindex='0'>Risultati per: &#34" . $ricerca . "&#34</h2>";
    if($results != false) {
        foreach ($results as $evento) {
            $ricercaris .= generateSearchResultItem($evento);
        }
    }
    if($resultsC != false) {
        foreach ($resultsC as $evento) {
            $ricercaris .= generateSearchResultItem($evento);
        }
    }
}

$template = str_replace('{RICERCA}', $ricerca, $template);
$template = str_replace('{RISULTATI}', $ricercaris, $template);
echo $template;

?>