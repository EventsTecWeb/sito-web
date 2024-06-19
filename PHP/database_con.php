<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "events";
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
            throw new Exception("Errore di connessione al database: " . $conn->connect_error);
        }
        mysqli_set_charset($conn, 'utf8mb4');
    } catch (Exception $e) {
        header('Location: ../html/500.html');
        exit();
    }
?>