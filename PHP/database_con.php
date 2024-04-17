<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "events";
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Verifico la connessione
        if ($conn->connect_error) {
            throw new Exception("Errore di connessione al database: " . $conn->connect_error);
        }
        // Imposto la codifica dei caratteri
        mysqli_set_charset($conn, 'utf8mb4');
    } catch (Exception $e) {

        // Reindirizzo l'utente alla pagina di errore 500
        header('Location: ../html/500.html');
        exit(); // Termino lo script PHP dopo il reindirizzamento

    }
?>