<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invitation_id'])) {
    $invitationId = $_POST['invitation_id'];
    $invitedUserId = $_SESSION['user_id']; // ID zalogowanego użytkownika, który odrzuca zaproszenie

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

    // Aktualizacja statusu zaproszenia na odrzucone
    $sql_update_invitation = "UPDATE invitations SET status = 'declined' WHERE id = ? AND invited_user_id = ?";
    $stmt_update_invitation = $conn->prepare($sql_update_invitation);
    if ($stmt_update_invitation) {
        $stmt_update_invitation->bind_param("ii", $invitationId, $invitedUserId);
        $stmt_update_invitation->execute();
        $stmt_update_invitation->close();

        // Usunięcie rekordu z tabeli invitations
        $sql_delete_invitation = "DELETE FROM invitations WHERE id = ?";
        $stmt_delete_invitation = $conn->prepare($sql_delete_invitation);
        if ($stmt_delete_invitation) {
            $stmt_delete_invitation->bind_param("i", $invitationId);
            $stmt_delete_invitation->execute();
            $stmt_delete_invitation->close();

            // Zatwierdzenie transakcji
            $conn->commit();

            // Zwrócenie odpowiedzi AJAX, która zostanie przetworzona po stronie klienta
            echo json_encode(array('message' => 'Zaproszenie odrzucone.'));
        } else {
            echo json_encode(array('error' => 'Błąd przygotowania zapytania usuwania zaproszenia: ' . $conn->error));
            $conn->rollback(); // Wycofanie transakcji w przypadku błędu
        }
    } else {
        echo json_encode(array('error' => 'Błąd przygotowania zapytania aktualizacji zaproszenia: ' . $conn->error));
    }

    $conn->close();
} else {
    echo json_encode(array('error' => 'Nieprawidłowe żądanie'));
}

?>
