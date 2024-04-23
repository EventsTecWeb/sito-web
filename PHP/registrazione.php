<?php
require_once 'queries.php';
session_start();

$registration_feedback = ''; // Inizializziamo la variabile vuota

$template = file_get_contents('../HTML/regNav.html');

// Controlla se è stato inviato il modulo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error_count = 1; // Impostare a 1 per controllare solo i campi obbligatori se viene inviato il modulo

    // Controllo dei campi obbligatori
    $required_fields = ['username', 'password', 'email', 'password_confirm', 'nome', 'cognome'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $registration_feedback .= "<p class='signin-error'>Il campo $field è obbligatorio</p>";
            $error_count++;
        }
    }

    if ($error_count === 1) {
        // Controllo se le password coincidono solo se sono stati inseriti entrambi i campi
        if (isset($_POST['password'], $_POST['password_confirm'])) {
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            // Controllo se le password coincidono
            if ($password !== $password_confirm) {
                $registration_feedback .= "<p class='signin-error'>Le password non corrispondono</p>";
                $error_count++;
            }
        } else {
            // Se una delle chiavi manca nell'array $_POST, aggiungi un messaggio di errore
            $registration_feedback .= "<p class='signin-error'>Devi compilare entrambi i campi password</p>";
            $error_count++;
        }
    }

    // Verifica se ci sono errori
    if ($error_count === 1) {
        $username = clearInput($_POST['username']);
        $password = clearInput($_POST['password']);
        $email = clearInput($_POST['email']);
        $nome = clearInput($_POST['nome']);
        $cognome = clearInput($_POST['cognome']);

        // Verifica se esistono già utenti con lo stesso username o email
        $existing_user_by_username = getUserByMailOrUsername($conn, $username);
        $existing_user_by_email = getUserByMailOrUsername($conn, $email);

        if ($existing_user_by_username) {
            $registration_feedback .= "<p class='signin-error'>Questo username è già stato utilizzato all'interno del nostro sito</p>";
            $error_count++;
        } 
        if ($existing_user_by_email) {
            $registration_feedback .= "<p class='signin-error'>Questa email è già stata utilizzata all'interno del nostro sito</p>";
            $error_count++;
        } 
        if ($error_count === 1) {
            // Effettua la registrazione
            $res = effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password,"Femmina");
            if ($res === "") {
                $res = getUserByMailOrUsername($conn, $username);
                if ($res === false) {
                    header('Location: 404.php');
                    exit();
                } else {
                    $_SESSION['user_id'] = $res['utente_id'];
                    header('Location: ../php/accessNav.php');
                    exit();
                }
            } else {
                $registration_feedback .= "<p class='signin-error'>$res</p>"; // Aggiungi un messaggio di errore generico
                $error_count++;
            }
        }
    }
}

// Sostituisci il placeholder nel template solo se c'è un feedback da mostrare
if (!empty($registration_feedback)) {
    $template = str_replace('{ERRORE}', $registration_feedback, $template);
} else {
    // Rimuovi il placeholder dal template se non ci sono errori
    $template = str_replace('{ERRORE}', '', $template);
}

echo $template;

$conn->close();
?>