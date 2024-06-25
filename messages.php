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

require_once 'DBManager.php';

$dbManager = new DBManager($conn);

// Pobierz listę znajomych dla aktualnie zalogowanego użytkownika
$userId = $_SESSION['user_id'];
$friendsList = $dbManager->getFriendsList($userId);

// Obsługa wysyłania wiadomości
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['receiverId'], $_POST['message'])) {
        $receiverId = $_POST['receiverId'];
        $message = $_POST['message'];

        // Zabezpiecz przed atakami SQL Injection
        $receiverId = intval($receiverId);
        $message = htmlspecialchars($message);

        // Wysłanie wiadomości przez użytkownika
        $senderId = $userId;
        $success = $dbManager->sendMessage($senderId, $receiverId, $message);

        if ($success) {
            // Przekierowanie z powrotem na stronę messages.php z zachowaniem friendId
            header("Location: messages.php?friendId=$receiverId");
            exit();
        } else {
            echo "<p>Wystąpił problem podczas wysyłania wiadomości.</p>";
        }
    }
}

// Obsługa otwierania czatu
if (isset($_GET['friendId'])) {
    $friendId = $_GET['friendId'];
    $_SESSION['last_opened_chat'] = $friendId; // Zapisz ID czatu do sesji
} elseif (isset($_SESSION['last_opened_chat'])) {
    $friendId = $_SESSION['last_opened_chat'];
} else {
    $friendId = null; // Domyślnie brak otwartego czatu
}

// Sprawdź, czy friendId należy do znajomych użytkownika
if ($friendId !== null && !in_array($friendId, $friendsList)) {
    $friendId = null; // Jeśli nie należy, ustaw na null
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiadomości</title>
    <link rel="stylesheet" href="style_messages.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo-container">
            <img src="logo.png" alt="Logo FaceHub" id="header_logo">
        </div>
        <div class="left-side-header">
            <a class="btn" href="search_users.php">Szukaj na FaceHub</a>
            <a class="btn" href="profile.php">Powrót do profilu</a>
        </div>
        <a class="btn" href="logout.php">Wyloguj się</a>
    </div>
</header>
<div class="container">
<div class="friends-list">
    <h2>Znajomi</h2>
    <ul>
        <?php foreach ($friendsList as $friendIdItem) : ?>
            <?php
            $friendUsername = $dbManager->getFriendUsername($friendIdItem);
            $friendAvatar = $dbManager->getFriendAvatar($friendIdItem);
            ?>
            <?php if ($friendUsername !== null) : ?>
                <li>
                    <a href="messages.php?friendId=<?= $friendIdItem ?>">
                        <img src="<?= htmlspecialchars($friendAvatar) ?>" alt="Avatar" class="avatar">
                        <?= htmlspecialchars($friendUsername) ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

    <div class="chat-box">
        <?php
        // Obsługa czatu z danym użytkownikiem
        if ($friendId !== null) {
            $friendUsername = $dbManager->getFriendUsername($friendId);
            if ($friendUsername !== null) {
                echo "<h2>Rozmowa z użytkownikiem: " . htmlspecialchars($friendUsername) . "</h2>";
                // Wyświetlenie wiadomości lub formularza do wysyłania wiadomości
                echo "<div class='messages'>";
                // Pobierz i wyświetl wiadomości
                $messages = $dbManager->getMessages($userId, $friendId);
                foreach ($messages as $message) {
                    $sender = ($message['sender_id'] == $userId) ? 'Ja' : htmlspecialchars($friendUsername);
                    echo "<p><strong>$sender:</strong> " . htmlspecialchars($message['message']) . "</p>";
                }
                echo "</div>";

                // Formularz do wysyłania wiadomości
                echo "<form method='post' action='messages.php'>";
                echo "<input type='hidden' name='receiverId' value='$friendId'>";
                echo "<textarea name='message' placeholder='Wpisz wiadomość...' required></textarea><br>";
                echo "<button type='submit'>Wyślij</button>";
                echo "</form>";
            } else {
                echo "<p>Nie można znaleźć użytkownika o podanym ID.</p>";
            }
        } else {
            echo "<p>Wybierz znajomego z listy, aby rozpocząć czat.</p>";
        }
        ?>
    </div>
</div>
<footer>
    <div class="footer-container">
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Pomoc</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Warunki</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Prywatność</a>
    </div>
</footer>
</body>
</html>

<?php
$conn->close();
?>

