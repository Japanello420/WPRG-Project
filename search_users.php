<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyszukiwanie użytkowników</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
</head>
<body>
<header>
    <div class="header-container">
        <a class="btn" href="profile.php">Powrót do profilu</a>
        <div class="logo-container">
            <img src="logo.png" alt="Logo FaceHub" id="header_logo">
        </div>
        <a class="btn" href="logout.php">Wyloguj się</a>
    </div>
</header>

<div class="container">
    <h2>Wyszukiwanie użytkowników</h2>
    <form action="search_users.php" method="GET">
        <div class="form-group">
            <label for="search">Wyszukaj:</label>
            <input type="text" id="search" name="search">
        </div>
        <input class="szukaj" type="submit" value="Szukaj">
    </form>
    <?php
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

// Obsługa formularza wyszukiwania
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];

    // Zapytanie SQL
    $sql = "SELECT d.id, d.Username, d.bio, d.profile_picture, f.id AS friendship_id, i.id AS invitation_id, i.sender_user_id AS sender_id, i.status, i.invited_user_id
            FROM data d
            LEFT JOIN friends f ON (f.user1_id = ? AND f.user2_id = d.id) OR (f.user1_id = d.id AND f.user2_id = ?)
            LEFT JOIN invitations i ON (i.sender_user_id = ? AND i.invited_user_id = d.id) OR (i.invited_user_id = ? AND i.sender_user_id = d.id)
            WHERE d.id != ? 
            AND (d.Username LIKE ? OR d.name LIKE ? OR d.surname LIKE ? OR d.city LIKE ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        session_start();
        $sender_user_id = $_SESSION['user_id']; // Załóżmy, że przechowujemy user_id w sesji

        $searchParam = "%" . $search . "%";
        $stmt->bind_param("iiiiissss", $sender_user_id, $sender_user_id, $sender_user_id, $sender_user_id, $sender_user_id, $searchParam, $searchParam, $searchParam, $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h3>Wyniki wyszukiwania:</h3>";
            echo "<ul class='search-results'>";
            while ($row = $result->fetch_assoc()) {
                echo "<li class='search-item'>";
                echo "<div class='flex-container'>";
                echo "<div class='profile-picture'>";
                echo "<img class='list-picture' src='" . htmlspecialchars($row['profile_picture']) . "' alt='Zdjęcie profilowe'>";
                echo "</div>";
                echo "<div class='user-info'>";
                echo "<strong>Nazwa użytkownika:</strong> " . htmlspecialchars($row['Username']) . "<br>";
                echo "<strong>Bio:</strong> " . htmlspecialchars($row['bio']) . "<br>";
                echo "</div>";
                echo "<div class='profile-inspection'>";
                    echo "<form action='user_profile.php' method='GET'>";
                    echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<button class='profile' type='submit'>Zobacz profil</button>";
                    echo "</form>";
                    echo "</div>";
                echo "<div class='invitation'>";
                
                // Sprawdzenie stanu zaproszenia lub znajomości
                if (!empty($row['friendship_id'])) {
                    // Wyświetl informację, że są już znajomymi
                    echo "<button class='friends' disabled>Jesteście znajomymi</button>";
                } elseif ($row['invitation_id']) {
                    // Sprawdzenie czy zalogowany użytkownik jest zaproszonym użytkownikiem
                    if ($row['invited_user_id'] == $sender_user_id && isset($row['status']) && $row['status'] == 'pending') {
                        // Wyświetl formularz do zaakceptowania lub odrzucenia zaproszenia
                        echo "<form class='accept-invite-form' action='accept_invitation.php' method='POST'>";
                        echo "<input type='hidden' name='invitation_id' value='" . htmlspecialchars($row['invitation_id']) . "'>";
                        echo "<button class='invitation' type='submit'>Zaakceptuj zaproszenie</button>";
                        echo "</form>";
                        echo "<br>";
                        echo "<form class='decline-invite-form' action='decline_invitation.php' method='POST'>";
                        echo "<input type='hidden' name='invitation_id' value='" . htmlspecialchars($row['invitation_id']) . "'>";
                        echo "<button class='invitation' type='submit'>Odrzuć zaproszenie</button>";
                        echo "</form>";
                    } elseif ($row['status'] == 'declined') {
                        // Wyświetl informację, że zaproszenie jest odrzucone
                        echo "<button class='invitation' disabled>Zaproszenie odrzucone</button>";
                    } elseif ($row['status'] == 'accepted') {
                        // Wyświetl informację, że zaproszenie jest zaakceptowane
                        echo "<button class='invitation' disabled>Zaproszenie zaakceptowane</button>";
                    }else {
                        // Wyświetl informację, że zaproszenie jest oczekujące
                        echo "<button class='invitation' disabled>Zaproszenie oczekujące</button>";
                    }
                } else {
                    // Wyświetl formularz do wysłania zaproszenia
                    echo "<form class='invite-form' action='send_invitation.php' method='POST'>";
                    echo "<input type='hidden' name='invited_username' value='" . htmlspecialchars($row['Username']) . "'>";
                    echo "<button class='invitation' type='submit'>Wyślij zaproszenie</button>";
                    echo "</form>";
                }
                

                echo "</div>";
                echo "</div>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Brak wyników dla wyszukiwanego kryterium.</p>";
        }
        $stmt->close();
    } else {
        echo "Błąd przygotowania zapytania: " . $conn->error;
    }
}
// Zamknięcie połączenia z bazą danych
$conn->close();
?>
</div>
<footer>
    <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Help</a>
    <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Terms</a>
    <a href="https://www.youtube.com/watch?v=oHg5SJYRHA0">Privacy</a>
</footer>

<script>
    $(document).ready(function() {
        $('.invite-form').on('submit', function(event) {
            event.preventDefault(); // Zatrzymanie standardowego wysyłania formularza
            
            var form = $(this);
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    form.find('.invitation').text('Zaproszenie wysłane').prop('disabled', true);
                },
                error: function(xhr, status, error) {
                    alert('Wystąpił błąd. Spróbuj ponownie później.');
                }
            });
        });

        $('.accept-invite-form').on('submit', function(event) {
    event.preventDefault(); // Zatrzymanie standardowego wysyłania formularza
    
    var form = $(this);
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
        success: function(response) {
            // Zaktualizuj przycisk "Zaakceptuj zaproszenie"
            form.find('.invitation').text('Zaproszenie zaakceptowane').prop('disabled', true);
            // Ukryj formularz "Odrzuć zaproszenie"
            form.closest('.search-item').find('.decline-invite-form').hide();
        },
        error: function(xhr, status, error) {
            alert('Wystąpił błąd. Spróbuj ponownie później.');
        }
    });
});


        $('.decline-invite-form').on('submit', function(event) {
    event.preventDefault(); // Zatrzymanie standardowego wysyłania formularza
    
    var form = $(this);
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
        success: function(response) {
            // Zaktualizuj przycisk "Zaproszenie odrzucone" tylko w bieżącym elemencie formularza
            form.find('.invitation').text('Zaproszenie odrzucone').prop('disabled', true);
            // Ukryj formularz "Zaakceptuj zaproszenie"
            form.closest('.search-item').find('.accept-invite-form').hide();
        },
        error: function(xhr, status, error) {
            alert('Wystąpił błąd. Spróbuj ponownie później.');
        }
    });
});
    });
</script>
</body>
</html>
