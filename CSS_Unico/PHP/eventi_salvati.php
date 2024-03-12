<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Recupera l'ID dell'utente dalla sessione
    $utente_id = $_SESSION['user_id'];

    // Query per recuperare gli eventi salvati dall'utente corrente
    $query = "SELECT * FROM EventiSalvati WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $utente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Chiusura della query
    $stmt->close();

    // Creazione del contenuto HTML con gli eventi salvati
    while ($row = $result->fetch_assoc()) {
        // Elabora ciascun evento salvato
        $evento_id = $row['evento_id'];
        $query_evento = "SELECT * FROM Eventi WHERE evento_id = $evento_id";
        $result_evento = $conn->query($query_evento);
        $evento = $result_evento->fetch_assoc();

        // Stampare il contenuto HTML per ciascun evento salvato
        echo "<div class='cardSalvato'>";
        echo "<h3>" . $evento['titolo'] . "</h3>";
        // Altri dettagli dell'evento possono essere stampati qui
        echo "</div>";
    }

    // Chiusura della connessione al database
    $conn->close();
} else {
    // L'utente non è autenticato, reindirizzalo alla pagina di accesso
    header("Location: ../html/accedi.html");
    exit(); // Assicura che lo script si interrompa dopo il reindirizzamento
}
?>
