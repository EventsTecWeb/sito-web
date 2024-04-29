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
            INNER JOIN eventisalvati ON Utenti.utente_id = eventisalvati.utente_id
            WHERE eventisalvati.evento_id = ?";
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
    header("Location: ../HTML/index.html");
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
    $sql = "SELECT * FROM Eventi";
    $result = $conn->query($sql);
    if (!$result) {
        header('Location: ../php/500.php');
        exit();
    }
    return $result;
}

function getEventiByTitolo($conn, $titolo)
{
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
    $queryEventi = "SELECT Eventi.*
    FROM Eventi
    JOIN eventisalvati ON Eventi.evento_id = eventisalvati.evento_id
    JOIN utenti ON eventisalvati.utente_id = utenti.utente_id
    WHERE utenti.utente_id = ?";
    $stmt = $conn->prepare($queryEventi);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultEventi = $stmt->get_result();
    $stmt->close();

    $eventi = array(); // Inizializza un array per memorizzare i risultati

    if ($resultEventi && $resultEventi->num_rows > 0) {
        while ($row = $resultEventi->fetch_assoc()) {
            $eventi[] = $row; // Aggiunge ogni riga all'array
        }
        return $eventi;
    } else {
        return array(); // Se non ci sono risultati, restituisci un array vuoto
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
    $query = "UPDATE Utenti SET username = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newUsername, $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Lo username è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento dello username o nessuna modifica apportata.";
    }
    $stmt->close();
}

function updateUserGender($conn, $userId, $newGender) {
    $query = "UPDATE Utenti SET genere = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newGender, $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Il genere è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento del genere o nessuna modifica apportata.";
    }
    $stmt->close();
}

function updateUserPhone($conn, $userId, $newPhone) {
    $query = "UPDATE Utenti SET telefono = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newPhone, $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Il numero di telefono è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento del numero di telefono o nessuna modifica apportata.";
    }
    $stmt->close();
}

function getProssimiEventi($conn) {
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
    if ($result->num_rows > 0) {
        $eventi = array();
        while($row = $result->fetch_assoc()) {
            $eventi[] = $row;
        }
        return $eventi;
    } else {
        return null;
    }
}

function updateUserEmail($conn, $userId, $newEmail) {
    $query = "UPDATE Utenti SET email = ? WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newEmail, $userId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "L'indirizzo email è stato aggiornato con successo.";
    } else {
        echo "Errore nell'aggiornamento dell'email o nessuna modifica apportata.";
    }
    $stmt->close();
}

function insertUserDateOfBirth($conn, $userId, $dateOfBirth) {
    $query = "SELECT utente_id FROM Utenti WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE Utenti SET data_nascita = ? WHERE utente_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $dateOfBirth, $userId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

function getEventiByIdQuery($conn, $idEvento)
{
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


function effettuaRegistrazione($conn, $email, $username, $nome, $cognome, $password, $genere, $privacy) {
    $queryCheck = "SELECT utente_id FROM Utenti WHERE email = ? OR username = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("ss", $email, $username);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if ($resultCheck->num_rows > 0) {
        return "Email o username già utilizzati.";
    } else {
        $sql = "INSERT INTO Utenti (nome, cognome, genere, username, permessi, email, password, privacy) VALUES (?, ?, ?, ?, 0, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nome, $cognome, $genere, $username, $email, $password, $privacy);
        $stmt->execute();
        $stmt->close();
        return null;
    }
}

function getUsernameById($conn, $userId) {
    $query = "SELECT username FROM Utenti WHERE utente_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['username'];
    } else {
        return false;
    }
}

function salvaEvento($conn, $userid, $evento_id) {
    $risultati = getEventiSalvati($conn, $userid);
    foreach($risultati as $res){
        if($evento_id==$res["evento_id"]){
            return false;
            exit();
        }
    }
    $query = "INSERT INTO eventisalvati (utente_id, evento_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userid, $evento_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function eliminaEvento($conn, $evento){
    $conn->begin_transaction();
    try {
        $query = "DELETE FROM eventisalvati WHERE evento_id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $evento);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
        }
        $query = "DELETE FROM eventi WHERE evento_id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $evento);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $conn->commit();
            return true;
        } else {
            throw new Exception("No rows affected in eventi");
        }
    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage());
        return false;
    }
}

function interessato($conn, $user, $evento) {
    $query = "SELECT 1 FROM eventisalvati WHERE evento_id = ? AND utente_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("ii", $evento, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function nonInteressato($conn, $evento , $user) {
    $query = "DELETE FROM eventisalvati WHERE evento_id = ? AND utente_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("ii", $evento, $user);
    $stmt->execute();
    if ($stmt->affected_rows === 0) {
        return false;
    } else {
        return true;
    }
}