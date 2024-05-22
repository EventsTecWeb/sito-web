<?php
require_once 'queries.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../HTML/index.html');
    exit();
}

error_reporting(E_ERROR | E_PARSE);

$template = file_get_contents('../HTML/indexProfilo.html');
$messaggio = "";
$target_dir = "../Images/";
$uploadOk = 1;
$fixed_width = 800; // Fixed width for the resized image
$fixed_height = 600; // Fixed height for the resized image

// Verify if user is logged in and get user data
if (isset($_SESSION['username']) || isset($_SESSION['email'])) {
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['email'];
    $userData = getUserByMailOrUsername($conn, $user);

    if ($userData) {
        $userId = $userData['utente_id'];
        $profile_image_path = getProfilePhoto($conn, $userId);
        if (!$profile_image_path) {
            $profile_image_path = '../Images/people_icon_small.png';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventName = clearInput($_POST["eventName"]);
            $eventStartDate = clearInput($_POST["eventStartDate"]);
            $eventStartTime = clearInput($_POST["eventStartTime"]);
            $eventEndDate = clearInput($_POST["eventEndDate"]);
            $eventEndTime = clearInput($_POST["eventEndTime"]);
            $eventLocation = clearInput($_POST["eventLocation"]);
            $eventCost = clearInput($_POST["eventCost"]);
            $eventCategory = clearInput($_POST["eventCategory"]);
            $eventDescription = clearInput($_POST["eventDescription"]);

            if ($eventStartDate > $eventEndDate) {
                $messaggio .= "<p class='impo-error'>Errore: La data di inizio dell'evento non può essere successiva alla data di fine.</p>";
                $uploadOk = 0;
            } elseif ($eventStartDate == $eventEndDate && $eventStartTime >= $eventEndTime) {
                $messaggio .= "<p class='impo-error'>Errore: L'ora di inizio dell'evento non può essere successiva o uguale all'ora di fine.</p>";
                $uploadOk = 0;
            }

            if ($uploadOk && !empty($eventName) && !empty($eventStartDate)) {
                $ris = inserisciEvento($conn, $eventName, $eventDescription, $eventStartDate, $eventEndDate, $eventStartTime, $eventEndTime, $eventLocation, $eventCost, $eventCategory, $userId, null);

                if ($ris !== true) {
                    $messaggio .= $ris;
                } else {
                    $eventId = $conn->insert_id;

                    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
                        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
                        $target_file = $target_dir . 'event_' . $eventId . '.' . $imageFileType;
                        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

                        if ($check === false) {
                            $messaggio .= "Il file non è un'immagine.<br>";
                            $uploadOk = 0;
                        }

                        if ($_FILES["fileToUpload"]["size"] > 500000) {
                            $messaggio .= "Il file è troppo grande.<br>";
                            $uploadOk = 0;
                        }

                        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                            $messaggio .= "Solo i formati JPG, JPEG, PNG e GIF sono permessi.<br>";
                            $uploadOk = 0;
                        }

                        if ($uploadOk) {
                            if (resizeImage($_FILES["fileToUpload"]["tmp_name"], $target_file, $fixed_width, $fixed_height)) {
                                $eventImageURL = $target_file;
                                updateEventImage($conn, $eventId, $eventImageURL);
                            } else {
                                $messaggio .= "Non è stato possibile caricare la foto.<br>";
                            }
                        }
                    }
                }
            } else {
                $messaggio .= "<p class='impo-error'>I campi Nome e Data inizio sono obbligatori.</p>";
            }
        }
    } else {
        header('Location: ../php/505.php');
        exit();
    }
} else {
    header('Location: ../php/accessNav.php');
    exit();
}

$template = str_replace('{FOTOPROFILO}', htmlspecialchars($profile_image_path), $template);
$template = str_replace('{MESSAGGIO}', htmlspecialchars($messaggio), $template);

echo $template;

$conn->close();

function updateEventImage($conn, $eventId, $imageURL) {
    $stmt = $conn->prepare("UPDATE eventi SET url_immagine = ? WHERE evento_id = ?");
    $stmt->bind_param("si", $imageURL, $eventId);
    $stmt->execute();
    $stmt->close();
}

function resizeImage($source, $destination, $fixed_width, $fixed_height) {
    list($width, $height, $type) = getimagesize($source);
    $src_x = 0;
    $src_y = 0;
    $src_w = $width;
    $src_h = $height;

    // Calculate aspect ratio
    $src_ratio = $width / $height;
    $dst_ratio = $fixed_width / $fixed_height;

    // Crop the image to fit the fixed dimensions
    if ($src_ratio > $dst_ratio) {
        // Source image is wider than destination aspect ratio
        $src_w = $height * $dst_ratio;
        $src_x = ($width - $src_w) / 2;
    } elseif ($src_ratio < $dst_ratio) {
        // Source image is taller than destination aspect ratio
        $src_h = $width / $dst_ratio;
        $src_y = ($height - $src_h) / 2;
    }

    $image_p = imagecreatetruecolor($fixed_width, $fixed_height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    imagecopyresampled($image_p, $image, 0, 0, $src_x, $src_y, $fixed_width, $fixed_height, $src_w, $src_h);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($image_p, $destination, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($image_p, $destination);
            break;
        case IMAGETYPE_GIF:
            imagegif($image_p, $destination);
            break;
    }

    imagedestroy($image_p);
    imagedestroy($image);

    return true;
}
?>
