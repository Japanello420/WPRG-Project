<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Nieautoryzowany']);
    exit();
}

$userId = $_SESSION['user_id'];

$sql = "SELECT n.notification_type, n.related_id, n.sender_user_id, u.Username as sender_username
        FROM notifications n
        LEFT JOIN data u ON n.sender_user_id = u.ID
        WHERE n.recipient_user_id = ? ORDER BY n.created_at DESC";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notificationText = '';
        switch ($row['notification_type']) {
            case 'friend_request':
                $notificationText = "Zaproszenie do znajomych od " . htmlspecialchars($row['sender_username']);
                break;
            // Dodaj inne przypadki powiadomień tutaj
            default:
                $notificationText = "Nowe powiadomienie";
                break;
        }

        $notifications[] = ['notification_text' => $notificationText];
    }

    if (empty($notifications)) {
        $notifications[] = ['notification_text' => 'Brak nowych powiadomień'];
    }

    echo json_encode($notifications);
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd serwera']);
}

$conn->close();
?>
