<?php
require_once 'queries.php';
session_start();

$registration_feedback = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Controllo dei campi obbligatori
    $required_fields = ['username', 'password', 'email', 'password_confirm', 'nome', 'cognome'];
    $error_count = 0;
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $registration_feedback = "<p class='signin-error'>Il campo $field è obbligatorio</p>";
            $error_count++;
            break;
        }
    }

    // Controllo se le password coincidono
    if ($_POST['password'] !== $_POST['password_confirm']) {
        $registration_feedback = "<p class='signin-error'>Le password non corrispondono</p>";
        $error_count++;
    }

    if ($error_count === 0) {
        $username = clearInput($_POST['username']);
        $password = clearInput($_POST['password']);
        $email = clearInput($_POST['email']);
        $nome = clearInput($_POST['nome']);
        $cognome = clearInput($_POST['cognome']);

        // Verifica se esistono già utenti con lo stesso username o email
        $existing_user_by_username = getUserByMailOrUsername($conn, $username);
        $existing_user_by_email = getUserByMailOrUsername($conn, $email);

        if ($existing_user_by_username !== null) {
            $registration_feedback = "<p class='signin-error'>Questo username è gia stato utilizzato all'interno del nostro sito</p>";
        } elseif ($existing_user_by_email !== null) {
            $registration_feedback = "<p class='signin-error'>Questa password è già stata utilizzata all'interno del nostro sito</p>";
        } else {
            // Effettua la registrazione
            effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password);
            $registration_feedback = "<p class='signin-success'>Utente registrato con SUCCESSO!</p>";
        }
    }

    $conn->close();
}

$template = file_get_contents('../html/registrazione.html');
$template = str_replace('{FEEDBACK}', $registration_feedback, $template);
echo $template;
?>
