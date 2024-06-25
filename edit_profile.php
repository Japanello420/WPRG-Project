<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Zmienne do przechowywania danych użytkownika
$currentUsername = '';
$currentBio = '';
$currentProfilePicture = '';

// Pobranie danych użytkownika
$userId = $_SESSION['user_id'];
$sqlSelect = "SELECT Username, profile_picture, bio FROM data WHERE ID = ?";
$stmtSelect = $conn->prepare($sqlSelect);

if ($stmtSelect) {
    $stmtSelect->bind_param("i", $userId);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    if ($resultSelect->num_rows == 1) {
        $row = $resultSelect->fetch_assoc();
        $currentUsername = $row['Username'];
        $currentBio = $row['bio'];
        $currentProfilePicture = $row['profile_picture'];
    } else {
        echo "<p>Błąd w pobieraniu danych użytkownika.</p>";
    }

    $stmtSelect->close();
} else {
    die("Błąd przygotowania zapytania: " . $conn->error);
}

// Obsługa formularza edycji profilu
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $bio = $_POST['bio'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Obsługa przesyłania zdjęcia profilowego
    if ($_FILES['profile_picture']['size'] > 0) {
        $targetDir = "uploads/";
        $profilePicturePath = $targetDir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profilePicturePath)) {
            $message .= "Zdjęcie profilowe zostało załadowane. ";
        } else {
            $message .= "Błąd podczas ładowania zdjęcia profilowego. ";
            $profilePicturePath = '';
        }
    } else {
        // Jeśli nie przesyłano nowego zdjęcia, użyj obecnego
        $profilePicturePath = $currentProfilePicture;
    }

    // Sprawdzenie, czy można zmienić nazwę użytkownika
    $sqlCheck = "SELECT username_changed_at FROM data WHERE ID = ?";
    $stmtCheck = $conn->prepare($sqlCheck);

    if ($stmtCheck) {
        $stmtCheck->bind_param("i", $userId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows == 1) {
            $row = $resultCheck->fetch_assoc();
            $lastUsernameChange = $row['username_changed_at'];

            // Zmiana nazwy użytkownika co 30 dni
            if (is_null($lastUsernameChange) || strtotime($lastUsernameChange) <= strtotime("-30 days")) {
                $usernameChangeAllowed = true;
            } else {
                $usernameChangeAllowed = false;
            }
        }

        $stmtCheck->close();
    } else {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    // Aktualizacja profilu użytkownika
    if ($usernameChangeAllowed) {
        $sqlUpdate = "UPDATE data SET Username = ?, bio = ?, profile_picture = ?, username_changed_at = NOW() WHERE ID = ?";
    } else {
        $sqlUpdate = "UPDATE data SET bio = ?, profile_picture = ? WHERE ID = ?";
    }

    $stmtUpdate = $conn->prepare($sqlUpdate);

    if ($stmtUpdate) {
        if ($usernameChangeAllowed) {
            $stmtUpdate->bind_param("sssi", $newUsername, $bio, $profilePicturePath, $userId);
        } else {
            $stmtUpdate->bind_param("ssi", $bio, $profilePicturePath, $userId);
        }

        if ($stmtUpdate->execute()) {
            $message .= "Profil zaktualizowany pomyślnie. ";
        } else {
            $message .= "Błąd podczas aktualizacji profilu: " . $stmtUpdate->error;
        }

        $stmtUpdate->close();
    } else {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    // Aktualizacja hasła użytkownika
    if (!empty($password) && ($password === $confirmPassword)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sqlPassword = "UPDATE data SET Password = ? WHERE ID = ?";
        $stmtPassword = $conn->prepare($sqlPassword);

        if ($stmtPassword) {
            $stmtPassword->bind_param("si", $hashedPassword, $userId);

            if ($stmtPassword->execute()) {
                $message .= "Hasło zostało zaktualizowane. ";
            } else {
                $message .= "Błąd podczas aktualizacji hasła: " . $stmtPassword->error;
            }

            $stmtPassword->close();
        } else {
            die("Błąd przygotowania zapytania: " . $conn->error);
        }
    } elseif (!empty($password)) {
        $message .= "Hasła nie pasują do siebie. ";
    }
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj profil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo FaceHub" id="header_logo">
    </header>
    <div class="container">
        <h2>Edytuj profil</h2>

        <form action="edit_profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Nick:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($currentUsername); ?>" required>
            </div>

            <div class="form-group">
                <label for="bio">Opis:</label><br>
                <textarea id="bio" name="bio" rows="5" cols="40"><?php echo htmlspecialchars($currentBio); ?></textarea>
            </div>

            <div class="form-group">
                <label for="profile_picture">Zdjęcie profilowe:</label>
                <input type="file" id="profile_picture" name="profile_picture">
            </div>

            <div class="form-group">
                <label for="password">Nowe hasło:</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Potwierdź nowe hasło:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <div class="form-submit">
            <input type="submit" value="Zaktualizuj profil">
            </div>
        </form>
        <div class="btn-return">
            <a href="profile.php" class="btn">Powrót do profilu</a>
        </div>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

    <footer>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Help</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Terms</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Privacy</a>
    </footer>
</body>
</html>
