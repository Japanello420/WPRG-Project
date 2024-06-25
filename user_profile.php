<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceHub - Profil Użytkownika</title>
    <link rel="stylesheet" href="style_profile.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body class="<?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'dark-mode' : ''; ?>">
<header>
    <div class="header-container">
    <div class="left-side-header">
        <a class="btn" href="search_users.php">Szukaj na FaceHub</a>
        <a class="btn" href="profile.php">Powrót do profilu</a>
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

        if (!isset($_GET['user_id'])) {
            echo "<p>Błąd: Nie podano użytkownika.</p>";
            exit();
        }

        $userId = $_GET['user_id'];

        $sql = "SELECT Username, profile_picture, bio, dark_mode FROM data WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                echo "<h2>Profil użytkownika " . htmlspecialchars($row['Username']) . "</h2>";
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
        <a class="btn" href="messages.php">Wyślij wiadomość</a>
    </div>
</div>
<footer>
    <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Help</a>
    <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Terms</a>
    <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Privacy</a>
</footer>
</body>
</html>
