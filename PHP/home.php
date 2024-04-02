<?php
require_once 'queries.php';
$template = file_get_contents("../HTML/home.html");

$query = "SELECT titolo, orario_inizio, orario_fine, luogo, costo, categoria, url_immagine FROM Eventi WHERE orario_inizio>NOW";

$errore = "";
$results = getEventiByCategoria($conn, "Musica");
$conn -> close();

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

$evidenza='<div class="column">
				<div class="container">
				<img src="immagini/evento4.jpg" alt="music event">
					<h4 class="descrizioneEventiT">Silent Party Portello</h4>
					<time class="descrizioneEventiD" datetime="2023-12-11 21:00">11-12-2023 - Porta portello Padova (PD)</time>
					<p class="descrizioneEventiD">Music - Disco</p>
				</div>
			</div>'

$template = str_replace('{EVIDENZA}', $evidenza, $template);

echo $content;
?>