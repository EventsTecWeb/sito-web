<?php
require_once 'queries.php';
$template = file_get_contents("../HTML/home.html");

$errore = "";
$results = getEventiByCategoria($conn, "Teatro");

if ($results!=null) {
	$template = file_get_contents('../HTML/home.html');
	$titolo=$results['titolo'];
	$data_inizio=$results['data_inizio'];
	$luogo = $results['luogo'];
	$costo = $results['costo'];
}
else {
	$error = '<p>Errore! Nessun <span lang="en">evento</span> trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
	header('Location:404.html');
}

$evidenza='<div class="column-home-e">
				<div class="container-home-evidenza-e">
				<img src="'.$results["url_immagine"].'" alt="music event">
				<h4 class="descrizioneEventiT-home">'.$results["titolo"].'</h4>
					<time class="descrizioneEventiD-home" datetime="'.$results["data_inizio"].' - '.$results["luogo"].'</time>
					<p class="descrizioneEventiD-home">'.$results["categoria"].'</p>
				</div>
			</div>';


$pEventi = getProssimiEventi($conn);

$prossimiEventi="";
if (!is_null($pEventi)) {
    // Ciclo foreach per iterare su ogni evento restituito
    foreach ($pEventi as $evento) {
		$prossimiEventi.=
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
} else {
    echo "Non ci sono eventi futuri.";
}
$template = str_replace('{EVIDENZA}', $evidenza, $template);
$template = str_replace('{PROSSIMIEVENTI}', $prossimiEventi, $template);

echo $template;
?>