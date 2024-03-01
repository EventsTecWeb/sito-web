<?php
use DB\DBAccess;
$template = file_get_contents("home.html");

$query = "SELECT titolo, orario_inizio, orario_fine, luogo, costo, categoria, url_immagine FROM Eventi WHERE orario_inizio>NOW";

$error = "";

$connection = new DBAccess();

if ($connection -> openConnection()) {
    $results = $connection -> executeSelect($query);
    if ($results) {
        $content = '			</div>
									<div class="row">
										<div class="column">
											<div class="container">
											<img src="'$results[0]["url_immagine"]'" alt="music event">
												<h4 class="descrizioneEventiT">'$results[0]["titolo"]'</h4>
												<time class="descrizioneEventiD" datetime="'$results[0]["orario_inizio"]'">'$results[0]["orario_inizio"]' - '$results[0]["luogo"]'</time>
												<p class="descrizioneEventiD">'$results[0]["costo"]'</p>
											</div>
										</div>
										<div class="row">
										<div class="column">
											<div class="container">
											<img src="'$results[1]["url_immagine"]'" alt="music event">
												<h4 class="descrizioneEventiT">'$results[1]["titolo"]'</h4>
												<time class="descrizioneEventiD" datetime="'$results[1]["orario_inizio"]'">'$results[1]["orario_inizio"]' - '$results[1]["luogo"]'</time>
												<p class="descrizioneEventiD">'$results[1]["costo"]'</p>
											</div>
										</div>
										<div class="row">
										<div class="column">
											<div class="container">
											<img src="'$results[2]["url_immagine"]'" alt="music event">
												<h4 class="descrizioneEventiT">'$results[2]["titolo"]'</h4>
												<time class="descrizioneEventiD" datetime="'$results[2]["orario_inizio"]'">'$results[2]["orario_inizio"]' - '$results[2]["luogo"]'</time>
												<p class="descrizioneEventiD">'$results[2]["costo"]'</p>
											</div>
										</div>
									</div>';
        $connection -> closeConnection();
    }
    else {
        // $error = '<p>Errore! Nessun <span lang="en">evento</span> trovato. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
        header('Location:404.html');
    }
}
else {
    $error = '<p>Errore! Non è stato possibile connettersi al <span lang="en">database</span>. Riprova o se il problema persiste contattare l\'amministrazione.</p>';
}

?>