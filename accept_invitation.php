<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invitation_id'])) {
    $invitationId = $_POST['invitation_id'];
    $invitedUserId = $_SESSION['user_id']; // ID zalogowanego użytkownika, który akceptuje zaproszenie

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Project";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Połączenie nieudane: " . $conn->connect_error);
    }

    // Rozpoczęcie transakcji
    $conn->begin_transaction();

    // Aktualizacja statusu zaproszenia na zaakceptowane
    $sql_update_invitation = "UPDATE invitations SET status = 'accepted' WHERE id = ? AND invited_user_id = ?";
    $stmt_update_invitation = $conn->prepare($sql_update_invitation);
    if ($stmt_update_invitation) {
        $stmt_update_invitation->bind_param("ii", $invitationId, $invitedUserId);
        $stmt_update_invitation->execute();
        $stmt_update_invitation->close();

        // Pobranie sender_user_id na podstawie invitationId
        $sql_get_sender_user_id = "SELECT sender_user_id FROM invitations WHERE id = ?";
        $stmt_get_sender_user_id = $conn->prepare($sql_get_sender_user_id);
        if ($stmt_get_sender_user_id) {
            $stmt_get_sender_user_id->bind_param("i", $invitationId);
            $stmt_get_sender_user_id->execute();
            $stmt_get_sender_user_id->bind_result($senderUserId);
            $stmt_get_sender_user_id->fetch();
            $stmt_get_sender_user_id->close();

            // Dodanie nowego rekordu do tabeli friends
            $sql_insert_friendship = "INSERT INTO friends (user1_id, user2_id) VALUES (?, ?)";
            $stmt_insert_friendship = $conn->prepare($sql_insert_friendship);
            if ($stmt_insert_friendship) {
                $stmt_insert_friendship->bind_param("ii", $senderUserId, $invitedUserId);
                $stmt_insert_friendship->execute();
                $stmt_insert_friendship->close();

                // Zatwierdzenie transakcji
                $conn->commit();

                // Zwrócenie odpowiedzi w formacie JSON
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Zaproszenie zaakceptowane pomyślnie']);
            } else {
                echo "Błąd przygotowania zapytania dodawania znajomości: " . $conn->error;
                $conn->rollback(); // Wycofanie transakcji w przypadku błędu
            }
        } else {
            echo "Błąd przygotowania zapytania pobierania sender_user_id: " . $conn->error;
            $conn->rollback(); // Wycofanie transakcji w przypadku błędu
        }
    } else {
        echo "Błąd przygotowania zapytania aktualizacji zaproszenia: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Nieprawidłowe żądanie";
}
?>
