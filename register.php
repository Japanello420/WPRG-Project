<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceHub - Rejestracja</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Zarejestruj się</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="Username">Nazwa użytkownika:</label>
                <input type="text" id="Username" name="Username" required>
            </div>
            <div class="form-group">
                <label for="Name">Imie:</label>
                <input type="text" id="Name" name="Name" required>
            </div>
            <div class="form-group">
                <label for="Second_name">Drugie imie:</label>
                <input type="text" id="Second_name" name="Second_name">
            </div>
            <div class="form-group">
                <label for="Surname">Nazwisko:</label>
                <input type="text" id="Surname" name="Surname" required>
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
        // Pobieranie danych z formularza
        $username = $_POST['Username'];
        $name = $_POST['Name'];
        $second_name = $_POST['Second_name'];
        $surname = $_POST['Surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $date_of_birth = $_POST['Date_of_birth'];
        $country = $_POST['Country'];
        $city = $_POST['City'];
    
        // Sprawdzenie wieku
        $age_limit = strtotime("-15 years");
        $dob_timestamp = strtotime($date_of_birth);
    
        if ($dob_timestamp > $age_limit) {
            echo "<p>Musisz mieć co najmniej 15 lat, aby się zarejestrować.</p>";
        } else {
            // Sprawdzenie, czy nazwa użytkownika jest unikalna
            $check_username_sql = "SELECT * FROM data WHERE Username = ?";
            $check_stmt = $conn->prepare($check_username_sql);
            if ($check_stmt === false) {
                die("Błąd przygotowania zapytania: " . $conn->error);
            }
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $result_username = $check_stmt->get_result();
    
            if ($result_username->num_rows > 0) {
                echo "<p>Nazwa użytkownika jest już zajęta. Proszę wybrać inną nazwę użytkownika.</p>";
            } else {
                // Sprawdzenie, czy adres e-mail jest unikalny
                $check_email_sql = "SELECT * FROM data WHERE E_mail = ?";
                $check_stmt->close(); // Zamknięcie poprzedniego zapytania
                $check_stmt = $conn->prepare($check_email_sql);
                if ($check_stmt === false) {
                    die("Błąd przygotowania zapytania: " . $conn->error);
                }
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $result_email = $check_stmt->get_result();
    
                if ($result_email->num_rows > 0) {
                    echo "<p>Adres e-mail jest już używany przez innego użytkownika. Proszę podać inny adres e-mail.</p>";
                } else {
                    // Kontynuowanie procesu rejestracji, jeśli nazwa użytkownika, adres e-mail i wiek są prawidłowe
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $sql = "INSERT INTO data (Username, Name, Second_name, Surname, E_mail, Password, Date_of_birth, Country, City) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("sssssssss", $username, $name, $second_name, $surname, $email, $hashed_password, $date_of_birth, $country, $city);
                        if ($stmt->execute()) {
                            echo "<p>Rejestracja zakończona sukcesem!</p>";
                        } else {
                            echo "<p>Błąd: " . $stmt->error . "</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p>Błąd w przygotowaniu zapytania: " . $conn->error . "</p>";
                    }
                }
            }
    
            $check_stmt->close(); // Zamknięcie ostatniego zapytania
        }
    }

    $conn->close();
    ?>
</body>
</html>
