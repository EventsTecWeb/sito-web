<?php
require_once '../../queries/queries.php';
session_start();
include 'user_session.php';
$accedi_stringa = gestisciAccesso($conn);

$risultato_info = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i dati inviati tramite il modulo HTML e li pulisce da eventuali caratteri pericolosi
    $titolo = clearInput($_POST['titolo']);
    $descrizione = clearInput($_POST['descrizione']);
    $data_inizio = clearInput($_POST['data_inizio']);
    $data_fine = clearInput($_POST['data_fine']);
    $orario_inizio = clearInput($_POST['orario_inizio']);
    $orario_fine = clearInput($_POST['orario_fine']);
    $luogo = clearInput($_POST['luogo']);
    $costo = clearInput($_POST['costo']);
    $categoria = clearInput($_POST['categoria']);
    $creatore_id = $_SESSION['user_id']; // Recupera l'ID dell'utente attualmente loggato

    // Controllo sulla data d'inizio non precedente a quella odierna
    if (strtotime($data_inizio) < strtotime(date('Y-m-d'))) {
        $risultato_info = '<p class="error">La data d\'inizio non può essere precedente a quella odierna.</p>';
    }
    // Controllo sulla data di fine non precedente alla data d'inizio
    elseif (strtotime($data_fine) < strtotime($data_inizio)) {
        $risultato_info = '<p class="error">La data di fine deve essere successiva alla data di inizio.</p>';
    }
    // Controllo sull'ora di fine se la data di inizio è la stessa di quella della fine
    elseif ($data_inizio === $data_fine && $orario_fine < $orario_inizio) {
        $risultato_info = '<p class="error">L\'ora di fine non può essere precedente all\'ora di inizio se la data di inizio è la stessa della data di fine.</p>';
    }
    else {
        // Se tutti i controlli sono superati, esegui l'inserimento dell'evento nel database
        $sql = "INSERT INTO Eventi (titolo, descrizione, data_inizio, data_fine, orario_inizio, orario_fine, luogo, costo, categoria, creatore_id, url_immagine) 
                VALUES ('$titolo', '$descrizione', '$data_inizio', '$data_fine', '$orario_inizio', '$orario_fine', '$luogo', '$costo', '$categoria', '$creatore_id', '$url_immagine_path')";

        if ($conn->query($sql) === TRUE) {
            $risultato_info = '<p>Evento inserito con successo!</p>';
        } else {
            $risultato_info = '<p>Errore durante l\'inserimento dell\'evento.</p>';
        }
    }
}

$categorie = getCategorie($conn);
$conn->close();

$categoria_info = '';
while ($row = $categorie->fetch_assoc()) {
    $categoria_info .= '<option value="' . $row['nome_categoria'] . '">';
    $categoria_info .= $row['nome_categoria'];
    $categoria_info .= '</option>';
}

$categorie->free();

$template = file_get_contents('../html/aggiungi_evento.html');

$template = str_replace('{ACCEDI}', $accedi_stringa, $template);
$template = str_replace('{CATEGORIA-OPZIONI}', $categoria_info, $template);
$template = str_replace('{RISULTATO}', $risultato_info, $template);

echo $template;

?>
