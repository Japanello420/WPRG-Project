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

if (isset($_SESSION['register_success'])) {
    $register_success = $_SESSION['register_success'];
    // Usunięcie komunikatu, aby nie pojawiał się ponownie
    unset($_SESSION['register_success']);
} else {
    $register_success = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sprawdzenie czy użytkownik istnieje w bazie danych
    $sql = "SELECT ID, Password FROM data WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Użytkownik istnieje, pobierz zahaszowane hasło z bazy danych
            $row = $result->fetch_assoc();
            $hashed_password = $row['Password'];

            // Porównaj podane hasło z zahaszowanym hasłem
            if (password_verify($password, $hashed_password)) {
                // Hasło jest poprawne, zaloguj użytkownika
                $_SESSION['user_id'] = $row['ID'];
                // Tutaj możesz przekierować użytkownika na stronę profilu
                header("Location: profile.php");
                exit();
            } else {
                // Niepoprawne hasło
                echo "<p class='error'>Niepoprawne hasło logowania.</p>";
            }
        } else {
            // Użytkownik nie istnieje
            echo "<p class='error'>Niepoprawne dane logowania.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>Błąd w przygotowaniu zapytania: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo FaceHub" id="header_logo">
    </header>
    <div class="container">
        <h2>Zaloguj się</h2>
        <?php if (!empty($register_success)): ?>
            <div class="alert alert-success">
                <?php echo $register_success; ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Nazwa użytkownika:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Zaloguj się</button>
        </form>
        <p>Nie masz jeszcze konta? <a href="register.php">Zarejestruj się</a></p>
    </div>
    <footer>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Help</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Terms</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Privacy</a>
    </footer>
</body>
</html>
