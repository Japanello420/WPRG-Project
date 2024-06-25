<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dark_mode = isset($_POST['dark_mode']) && $_POST['dark_mode'] == 1 ? 1 : 0;
    $_SESSION['dark_mode'] = $dark_mode;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Project";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Połączenie nieudane: " . $conn->connect_error);
    }

    $userId = $_SESSION['user_id'];

    $sql = "UPDATE data SET dark_mode = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $dark_mode, $userId);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
}
?>
