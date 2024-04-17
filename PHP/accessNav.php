<?php

require_once 'queries.php';
session_start();

$errore_credenziali="";
$template = file_get_contents('../HTML/accessNav.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $errore_credenziali="inserisci la password e lo username";
        $template = str_replace('{ERRORE}', $errore_credenziali, $template);
        echo $template;
    }

    $utente = clearInput($_POST['username']); 
    $password = clearInput($_POST['password']);

    $user = getUserByMailOrUsername($conn, $utente);
    $conn->close();
    if ($user === false) {
        $errore_credenziali="<p class='signin-error'>L'username che è stato inserito oppure la password sono errate, si prega di ricontrolare i valori inseriti nei campi.</p>";
    } else {
        $mail = $user['email'];

        if ($password == $user['password'] && $mail == $user['email']) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']= $user['utente_id'];
			header('Location: ../php/home.php');
            exit();
        } else { 
            $errore_credenziali="<p class='signin-error'>L'username che è stato inserito oppure la password sono errate, si prega di ricontrolare i valori inseriti nei campi.</p>";
        }
    }
}

$template = str_replace('{ERRORE}', $errore_credenziali, $template);
echo $template;

?>
