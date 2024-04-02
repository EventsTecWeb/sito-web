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

    header("Location: accesso.php");
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
    $queryEventi = "SELECT * FROM Eventi WHERE titolo LIKE ?";
    $stmt = $conn->prepare($queryEventi);

    $titolo_like = "%" . $titolo . "%"; 
    $stmt->bind_param("s", $titolo_like);

    $stmt->execute();

    $resultEventi = $stmt->get_result();

    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi;
    } else {
        header('Location: ../php/404.php');
        exit;
    }
}

function getEventiByCategoria($conn, $categoria)
{
    // Mi preparo la query per selezionare gli eventi con una determinata categoria
    $queryEventi = "SELECT * FROM Eventi WHERE categoria = ?";
    $stmt = $conn->prepare($queryEventi);

    $stmt->bind_param("s", $categoria);

    $stmt->execute();

    $resultEventi = $stmt->get_result();


    $stmt->close();

    if ($resultEventi && $resultEventi->num_rows > 0) {
        return $resultEventi->fetch_assoc();
    } else {
        header('Location: ../php/404.php');
        exit;
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


