<?php
session_start();
require_once 'classes/User.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $name = $_POST['Name'];
    $secondName = $_POST['Second_name'];
    $surname = $_POST['Surname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dateOfBirth = $_POST['Date_of_birth'];
    $country = $_POST['Country'];
    $city = $_POST['City'];

    $register_result = $user->register($username, $name, $secondName, $surname, $gender, $email, $password, $dateOfBirth, $country, $city);

    if ($register_result === true) {
        $_SESSION['register_success'] = "<center><p style='font-size:18px; color:green;'>Konto zostało utworzone pomyślnie!</p></center>";
        header("Location: login.php");
        exit();
    } else {
        echo "<p>Błąd podczas rejestracji użytkownika: " . $register_result . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceHub - Rejestracja</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo FaceHub" id="header_logo">
    </header>
    <div class="container">
        <div class="register-box">
            <h2>Zarejestruj się</h2>
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="Username">Nazwa użytkownika:</label>
                    <input type="text" id="Username" name="Username" required>
                </div>
                <div class="form-group">
                    <label for="Name">Imię:</label>
                    <input type="text" id="Name" name="Name" required>
                </div>
                <div class="form-group">
                    <label for="Second_name">Drugie imię:</label>
                    <input type="text" id="Second_name" name="Second_name">
                </div>
                <div class="form-group">
                    <label for="Surname">Nazwisko:</label>
                    <input type="text" id="Surname" name="Surname" required>
                </div>
                <div class="form-group">
                    <label for="gender">Płeć:</label>
                    <select id="gender" name="gender">
                        <option value="male">Mężczyzna</option>
                        <option value="female">Kobieta</option>
                        <option value="other">Inna</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="Date_of_birth">Data narodzin:</label>
                    <input type="date" id="Date_of_birth" name="Date_of_birth" required>
                </div>
                <div class="form-group">
                    <label for="Country">Państwo:</label>
                    <input type="text" id="Country" name="Country" required>
                </div>
                <div class="form-group">
                    <label for="City">Miejscowość:</label>
                    <input type="text" id="City" name="City" required>
                </div>
                <button type="submit">Zarejestruj się</button>
            </form>
            <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
        </div>
    </div>
    <footer>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Help</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Terms</a>
        <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Privacy</a>
    </footer>
</body>
</html>
