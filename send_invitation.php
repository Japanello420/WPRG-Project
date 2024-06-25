<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Przetwarzanie formularza
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invited_username'])) {
    $invitedUsername = $_POST['invited_username'];
    $senderUserId = $_SESSION['user_id']; // ID użytkownika wysyłającego zaproszenie

    // Pobranie invited_user_id na podstawie Username
    $sql_get_invited_user_id = "SELECT id FROM data WHERE Username = ?";
    $stmt_get_invited_user_id = $conn->prepare($sql_get_invited_user_id);
    if ($stmt_get_invited_user_id) {
        $stmt_get_invited_user_id->bind_param("s", $invitedUsername);
        $stmt_get_invited_user_id->execute();
        $stmt_get_invited_user_id->bind_result($invitedUserId);
        $stmt_get_invited_user_id->fetch();
        $stmt_get_invited_user_id->close();
    } else {
        echo "Błąd przygotowania zapytania: " . $conn->error;
    }

    // Dodawanie zaproszenia do bazy danych
    $sql_insert_invitation = "INSERT INTO invitations (sender_user_id, invited_user_id, sent_at, status)
                              VALUES (?, ?, NOW(), 'pending')";
    $stmt_insert_invitation = $conn->prepare($sql_insert_invitation);
    if ($stmt_insert_invitation) {
        $stmt_insert_invitation->bind_param("ii", $senderUserId, $invitedUserId);
        if ($stmt_insert_invitation->execute()) {
            echo "Zaproszenie wysłane pomyślnie!";
        } else {
            echo "Błąd przy dodawaniu zaproszenia: " . $stmt_insert_invitation->error;
        }
        $stmt_insert_invitation->close();
    } else {
        echo "Błąd przygotowania zapytania: " . $conn->error;
    }
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
