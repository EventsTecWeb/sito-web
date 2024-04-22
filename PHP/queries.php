<?php
require_once 'database_con.php';

function clearInput($value) {
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
    return $value;
}

function getPartecipantiEvento($conn, $evento_id) {
    $sql = "SELECT Utenti.nome, Utenti.cognome 
            FROM Utenti
            INNER JOIN Partecipazioni ON Utenti.utente_id = Partecipazioni.utente_id
            WHERE Partecipazioni.evento_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $evento_id);
    
    $stmt->execute();

    $result = $stmt->get_result();
    
    return $result;
}

function logout() {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    header("Location: ../PHP/accessNav.php");
    exit();
}


function getProfilePhoto($conn, $userId) {
    $sql = "SELECT foto_profilo FROM Utenti WHERE utente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['foto_profilo'];
    } else {
        return '../Images/people_icon_small.png';
    }
}

function getEventi($conn) {
    //Mi creo la query per selezionare tutti i campi della tabella eventi
    $sql = "SELECT * FROM Eventi";
    $result = $conn->query($sql);
    // Verifico se la query è stata eseguita correttamente
    if (!$result) {
        header('Location: ../php/500.php');
        exit();
    }
    return $result;
}

function getEventiByTitolo($conn, $titolo)
{
    // Preparo la per selezionare gli eventi con un determinato titolo
    $queryEventi = "SELECT * 
    FROM Eventi 
    WHERE titolo LIKE ?
    AND creatore_id IN (
        SELECT utente_id
        FROM utenti
        WHERE privacy = 'Pubblico' OR privacy IS NULL
        )";
    $stmt = $conn->prepare($queryEventi);

    $titolo_like = "%" . $titolo . "%"; 
    $stmt->bind_param("s", $titolo_like);

    $stmt->execute();

    $resultEventi = $stmt->get_result();

    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        return False;
    }
}

function getEventiByCategoria($conn, $categoria)
{
    // Mi preparo la query per selezionare gli eventi con una determinata categoria
    $queryEventi = "SELECT * FROM Eventi WHERE categoria LIKE ?";
    $categoria = "%" . $categoria . "%"; 

    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("s", $categoria);

    $stmt->execute();

    $resultEventi = $stmt->get_result();


    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        return false;
    }
}

function getEventiByDate($conn, $month)
{
    // Mi preparo la query per selezionare gli eventi con una determinata categoria
    $queryEventi = "SELECT * FROM Eventi WHERE data_inizio > ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("s", $month);

    $stmt->execute();

    $resultEventi = $stmt->get_result();


    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) { 
        return $resultEventi;
    } else {
        return (bool) false;
        exit;
    }
}

function getEventiSalvati($conn, $id)
{
    // Prepare the query to select events saved by a specific user
    $queryEventi = "SELECT Eventi.*
    FROM Eventi
    JOIN eventisalvati ON Eventi.evento_id = eventisalvati.evento_id
    JOIN utenti ON eventisalvati.utente_id = utenti.utente_id
    WHERE utenti.utente_id = ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("i", $id); // Use "i" for integer parameter

    $stmt->execute();

    $resultEventi = $stmt->get_result();

    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        return false; // No need for (bool) conversion
    }
}

function getEventiCreati($conn, $id)
{   
    $queryEventi = "SELECT Eventi.*
    FROM Eventi
    JOIN utenti ON Eventi.creatore_id = utenti.utente_id
    WHERE utenti.utente_id = ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $resultEventi = $stmt->get_result();

    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        return false;
    }
}

function updateUserName($conn, $userId, $nome, $cognome) {
    $sql = "UPDATE utenti SET nome = IF(? IS NOT NULL, ?, nome), cognome = IF(? IS NOT NULL, ?, cognome) WHERE utente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $nome, $cognome, $cognome, $userId);
    $stmt->execute();
    $stmt->close();
}


function updateUserUsername($conn, $userId, $newUsername) {
    // Prepariamo la query SQL per aggiornare lo username dell'utente
    $query = "UPDATE Utenti SET username = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    // Associamo i parametri (i valori delle variabili $newUsername e $userId) ai posti dei segnaposto nella query
    $stmt->bind_param("si", $newUsername, $userId);
    // Eseguiamo la query preparata
    $stmt->execute();
    
    // Controlliamo se l'aggiornamento ha avuto successo
    if ($stmt->affected_rows > 0) {
        echo "Lo username è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento dello username o nessuna modifica apportata.";
    }
    
    // Chiudiamo lo statement
    $stmt->close();
}

function updateUserGender($conn, $userId, $newGender) {
    // Prepariamo la query SQL per aggiornare il genere dell'utente
    $query = "UPDATE Utenti SET genere = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    // Associamo i parametri (i valori delle variabili $newGender e $userId) ai posti dei segnaposto nella query
    $stmt->bind_param("si", $newGender, $userId);
    // Eseguiamo la query preparata
    $stmt->execute();
    
    // Controlliamo se l'aggiornamento ha avuto successo
    if ($stmt->affected_rows > 0) {
        echo "Il genere è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento del genere o nessuna modifica apportata.";
    }
    
    // Chiudiamo lo statement
    $stmt->close();
}

function updateUserPhone($conn, $userId, $newPhone) {
    // Prepariamo la query SQL per aggiornare il numero di telefono dell'utente
    $query = "UPDATE Utenti SET telefono = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    // Associamo i parametri (i valori delle variabili $newPhone e $userId) ai posti dei segnaposto nella query
    $stmt->bind_param("si", $newPhone, $userId);
    // Eseguiamo la query preparata
    $stmt->execute();
    
    // Controlliamo se l'aggiornamento ha avuto successo
    if ($stmt->affected_rows > 0) {
        echo "Il numero di telefono è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento del numero di telefono o nessuna modifica apportata.";
    }
    
    // Chiudiamo lo statement
    $stmt->close();
}

function getProssimiEventi($conn) {
    // Prepariamo la query SQL per selezionare i prossimi 12 eventi
    $query = "SELECT * 
    FROM Eventi 
    WHERE data_inizio > NOW()
        AND creatore_id IN (
            SELECT utente_id
            FROM utenti
            WHERE privacy = 'Pubblico' OR privacy IS NULL
            ) 
    ORDER BY data_inizio ASC LIMIT 12";
    $result = $conn->query($query);
    
    // Verifichiamo se la query ha prodotto risultati
    if ($result->num_rows > 0) {
        // Creiamo un array per contenere gli eventi
        $eventi = array();
        // Iteriamo sui risultati e li aggiungiamo all'array
        while($row = $result->fetch_assoc()) {
            $eventi[] = $row;
        }
        return $eventi;
    } else {
        // Restituiamo null se non ci sono eventi futuri
        return null;
    }
}

function updateUserEmail($conn, $userId, $newEmail) {
    // Prepariamo la query SQL per aggiornare l'email dell'utente
    $query = "UPDATE Utenti SET email = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    // Associamo i parametri (i valori delle variabili $newEmail e $userId) ai posti dei segnaposto nella query
    $stmt->bind_param("si", $newEmail, $userId);
    // Eseguiamo la query preparata
    $stmt->execute();
    // Controlliamo se l'aggiornamento ha avuto successo
    if ($stmt->affected_rows > 0) {
        echo "L'indirizzo email è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento dell'email o nessuna modifica apportata.";
    }
    // Chiudiamo lo statement
    $stmt->close();
}

function insertUserDateOfBirth($conn, $userId, $dateOfBirth) {
    // Check if the user already has a date of birth set and update it if so, or insert a new record otherwise
    $query = "SELECT utente_id FROM Utenti WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, update their date of birth
        $updateQuery = "UPDATE Utenti SET data_nascita = ? WHERE utente_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $dateOfBirth, $userId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // User does not exist, optional: handle this case according to your application's logic
        echo "User not found.";
    }

    $stmt->close();
}

function getEventiByIdQuery($conn, $idEvento)
{
    // Mi perparo la query per selezionare un evento dato il suo ID
    $queryEventi = "SELECT * FROM Eventi WHERE evento_id = ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("i", $idEvento);

    $stmt->execute();

    $resultEvento = $stmt->get_result();

    $stmt->close();

    if ($resultEvento && $resultEvento->num_rows > 0) {
        return $resultEvento->fetch_assoc();
    } else {
        header('Location: ../php/404.php');
        exit;
    }
}

function getUserByMailOrUsername($conn, $user){
    $query = "SELECT * 
                FROM Utenti 
                WHERE username = ? OR email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $user , $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        return (bool) false;
    }else{
        return $result->fetch_assoc();
    }
    $stmt->close();

}

function getCategorie($conn){
    $querySql = "SELECT categoria FROM Eventi";
    $resultCategoria = $conn->prepare($querySql);

    if(!$resultCategoria){
        header('Location: ../php/500.php');
        exit();
    }
    return $result;
}

function getPermessiByUsername($conn, $user){
    $query = "SELECT U.permessi 
            FROM Utenti AS U 
            WHERE U.email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header('Location: ../php/500.php');
        exit();
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    return (bool) $row['permessi'];
}


function effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password,$genere){
    $sql = "INSERT INTO Utenti (nome, cognome, genere, username, permessi, email, password) VALUES (?, ?, ?, ?, 0, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $cognome, $genere, $username,$email, $password);
    $stmt->execute();
	$result=$stmt->get_result();
    $stmt->close();
	return $result;
}

