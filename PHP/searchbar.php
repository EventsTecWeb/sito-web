<?php
require_once 'queries.php';
session_start();

function generateSearchResultItem($evento) {
    return '<div class="column-home">
                <div class="container-home-evidenza">
                    <img src="'.$evento["url_immagine"].'" alt="music event">
                    <h4 class="descrizioneEventiRic">'.$evento["titolo"].'</h4>
                    <time class="descrizioneEventiRicD" datetime="2023-12-11 21:00">'.$evento["data_inizio"].'</time>
                    <p class="descrizioneEventiRicL">'.$evento["luogo"].'</p>
                    <p class="descrizioneEventiRicG">'.$evento["categoria"].'</p>
                </div>
            </div>';
}

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

if($results == false && $resultsC == false) {
    $ricerca = "<p>Nessun risultato trovato per &#34" .$ricerca ."&#34</p>";
} else {
    $ricerca = "<p>Risultati per: &#34" . $ricerca . "&#34</p>"; // Add "Risultati per" before the search term
    
    // Se ci sono risultati dalla ricerca per titolo, aggiungili all'output
    if($results != false) {
        foreach ($results as $evento) {
            $ricercaris .= generateSearchResultItem($evento);
        }
    }
    
    // Se ci sono risultati dalla ricerca per categoria, aggiungili all'output
    if($resultsC != false) {
        foreach ($resultsC as $evento) {
            $ricercaris .= generateSearchResultItem($evento);
        }
    }
}

if($resultsC==True){
    foreach ($resultsC as $evento) {
        $ricercaris .= generateSearchResultItem($evento);
    }
}

$template = str_replace('{RICERCA}', $ricerca, $template);
$template = str_replace('{RISULTATI}', $ricercaris, $template);
echo $template;

?>