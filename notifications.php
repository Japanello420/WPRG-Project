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
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Zapytanie SQL do pobrania powiadomień użytkownika
$sql = "SELECT id, notification_text, created_at, read_status FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='notifications'>";
    echo "<h3>Twoje powiadomienia:</h3>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statusClass = $row['read_status'] ? 'read' : 'unread';
            echo "<div class='notification $statusClass'>";
            echo "<p>" . htmlspecialchars($row['notification_text']) . "</p>";
            echo "<span class='timestamp'>" . htmlspecialchars($row['created_at']) . "</span>";
            echo "</div>";
        }
    } else {
        echo "<p>Brak powiadomień.</p>";
    }
    echo "</div>";

    $stmt->close();
} else {
    echo "<p>Błąd przygotowania zapytania: " . $conn->error . "</p>";
}

$conn->close();
?>
