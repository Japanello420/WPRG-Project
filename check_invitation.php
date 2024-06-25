<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "error";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "error";
    exit();
}

// Przetwarzanie formularza
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invited_username'])) {
    $invitedUsername = $_POST['invited_username'];
    $senderUserId = $_SESSION['user_id']; // ID użytkownika wysyłającego zaproszenie

    // Sprawdzenie, czy użytkownik może wysłać zaproszenie
    $sql_check_invitation = "SELECT id FROM invitations 
                             WHERE sender_user_id = ? AND invited_user_id = (SELECT id FROM data WHERE Username = ?)";
    $stmt_check_invitation = $conn->prepare($sql_check_invitation);
    if ($stmt_check_invitation) {
        $stmt_check_invitation->bind_param("is", $senderUserId, $invitedUsername);
        $stmt_check_invitation->execute();
        $stmt_check_invitation->store_result();
        
        if ($stmt_check_invitation->num_rows > 0) {
            // Użytkownik ma już oczekujące zaproszenie do tego użytkownika
            echo "pending";
        } else {
            // Może wysłać zaproszenie
            echo "ok";
        }
        
        $stmt_check_invitation->close();
    } else {
        echo "error";
    }
}

$conn->close();
?>
