<?php
// Avvia la sessione
session_start();

// Controlla se l'utente è autenticato
if (!isset($_SESSION['user_id'])) {
    // L'utente non è autenticato, reindirizzalo alla pagina di accesso
    header("Location: ../html/accedi.html");
    exit(); // Assicura che lo script si interrompa dopo il reindirizzamento
}

// Connessione al database MySQL
$servername = "nome_del_server";
$username = "nome_utente";
$password = "password";
$database = "nome_database";
$conn = new mysqli($servername, $username, $password, $database);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Recupera l'ID dell'utente dalla sessione
$utente_id = $_SESSION['user_id'];

// Query per recuperare le informazioni dell'utente
$query = "SELECT * FROM Utenti WHERE utente_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $utente_id);
$stmt->execute();
$result = $stmt->get_result();

// Ottieni i dati dell'utente
$userData = $result->fetch_assoc();

// Chiusura della query
$stmt->close();

// Chiusura della connessione al database
$conn->close();
?>