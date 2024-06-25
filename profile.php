<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceHub - Profil użytkownika</title>
    <link rel="stylesheet" href="style_profile.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
<header>
    <div class="header-container">
        <div class="left-side-header">
            <a class="btn" href="search_users.php">Szukaj na FaceHub</a>
            <a class="btn" href="messages.php">Wiadomości</a>
            <!--<div class="notifications-container">
                <div class="notifications-icon">
                    <img src="notification.png" alt="Powiadomienia" id="notifications-icon">
                </div>
                <div class="notifications-dropdown" id="notifications-dropdown">
                    <ul class="notifications-list">
                    </ul>
                </div>
            </div>-->
        </div>
        <div class="logo-container">
            <img src="logo.png" alt="Logo FaceHub" id="header_logo">
        </div>
        <a class="btn" href="logout.php">Wyloguj się</a>
    </div>
</header>
    <div class="container">
        <div class="profile-box">
            <h1>Profil użytkownika</h1>
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

            $sql = "SELECT Username, profile_picture, bio, dark_mode FROM data WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $_SESSION['dark_mode'] = $row['dark_mode']; // Aktualizacja sesji preferencjami użytkownika
                    echo "<h2>Witaj, " . htmlspecialchars($row['Username']) . "!</h2>";
                    if (!empty($row['profile_picture'])) {
                        echo "<img src='" . htmlspecialchars($row['profile_picture']) . "' alt='Zdjęcie profilowe' class='profile-picture'><br>";
                    }
                    echo "<p>Opis: " . nl2br(htmlspecialchars($row['bio'])) . "</p>";
                } else {
                    echo "<p>Błąd w pobieraniu danych użytkownika.</p>";
                }
                $stmt->close();
            } else {
                echo "<p>Błąd w przygotowaniu zapytania: " . $conn->error . "</p>";
            }

            $conn->close();
            ?>
            <p><a class="btn" href="edit_profile.php">Edytuj profil</a></p>
        </div>
    </div>
    <footer>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Help</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Terms</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Privacy</a>
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('notifications-icon').addEventListener('click', function() {
        console.log("Kliknięto w dzwonek powiadomień");
        const notificationContainer = document.getElementById('notifications-dropdown');
        const notificationList = document.querySelector('.notifications-list');
        
        notificationContainer.style.display = notificationContainer.style.display === 'none' ? 'block' : 'none';
        
        if (notificationContainer.style.display === 'block') {
            console.log("Ładowanie powiadomień...");
            fetch('fetch_notifications.php')
                .then(response => response.json())
                .then(data => {
                    console.log("Powiadomienia załadowane:", data);
                    notificationList.innerHTML = '';
                    data.forEach(notification => {
                        const listItem = document.createElement('li');
                        listItem.textContent = notification.notification_text;
                        notificationList.appendChild(listItem);
                    });
                    if (data.length === 0) {
                        notificationList.innerHTML = '<li>Brak nowych powiadomień</li>';
                    }
                })
                .catch(error => {
                    console.error('Błąd:', error);
                    notificationList.innerHTML = '<li>Błąd ładowania powiadomień</li>';
                });
        }
    });
});
</script>
</body>
</html>
