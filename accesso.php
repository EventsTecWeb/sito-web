<?php

require_once '../../queries/queries.php'; //include il file che contiene le query necessarie per interrogare il database. 
session_start();/*avvia una nuova sessione o riprende una sessione esistente.
 Dopo che l'utente ha inserito le credenziali e ha fatto clic su "Accedi", è necessario mantenere traccia del fatto che l'utente sia autenticato durante la sua sessione sul sito.*/

$errore_credenziali="";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  /*si controolla se la richiesta http è di tipo post*/
    if (!isset($_POST['username']) || !isset($_POST['password'])) {  /*controlla se i campi 'utente' e 'password' sono stati inviati con il modulo POST.
        Se uno dei due manca, reindirizza l'utente alla pagina di errore 404 e termina lo script.*/
        header('Location: 404.php');
        exit();
    }

    $utente = clearInput($_POST['username']); /*ottiene il valore inviato dal campo 'utente' nel modulo POST e
    lo assegna alla variabile $utente dopo averlo pulito per prevenire attacchi di tipo SQL injection o XSS.*/
    $password = clearInput($_POST['password']);

    $user = getUserByMailOrUsername($conn, $utente); /*: Chiama una funzione getUserByMailOrUsername() passando come argomenti la connessione al database ($conn) e
    l'utente (email o username) per ottenere i dettagli dell'utente dal database. */
    $conn->close();
    if ($user === null) {
        $errore_credenziali="<p>L'username che è stato inserito oppure la password sono errate, si prega di ricontrolare i valori inseriti nei campi.</p>";
    } else {

        $mail = $user['mail']; //ottiene l'email dell'utente trovato nel database.

        if ($password == $user['password'] && $mail == $user['mail']) {
            //controlla se la password inviata corrisponde alla password dell'utente nel database e se l'email inviata corrisponde all'email dell'utente nel database.
            $_SESSION['mail'] = $user['mail'];
            $_SESSION['logged_in'] = true;
            if($user['permessi']){
                header('Location: admin.php');
            } else{
                header('Location: profilo.php');
            }
            exit();
        } else { 
            $utente_errato="<p>L'username che è stato inserito oppure la password sono errate, si prega di ricontrolare i valori inseriti nei campi.</p>";
        }
    }
}

$template = file_get_contents('../html/fileAccesso.html'); 

$template = str_replace('{UTENTE-ERRATO}', $utente_errato, $template);

echo $template;

?>
