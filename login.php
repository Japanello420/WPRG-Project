<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Zaloguj się</h2>
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
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sprawdzenie czy użytkownik istnieje w bazie danych
    $sql = "SELECT * FROM data WHERE Username = ?";
    $stmt = $conn->prepare($sql);
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
            echo "Zalogowano pomyślnie!";
            // Tutaj możesz przekierować użytkownika na inną stronę, np. stronę główną
        } else {
            // Niepoprawne hasło
            echo "Niepoprawne hasło logowania.";
        }
    } else {
        // Użytkownik nie istnieje
        echo "Niepoprawne dane logowania.";
    }

    $stmt->close();
}

$conn->close();
?>



</body>
</html>