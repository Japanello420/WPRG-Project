<?php
class DBManager {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Metoda do pobierania listy znajomych
    public function getFriendsList(int $userId): array {
        $friends = [];

        $sql = "SELECT user2_id FROM friends WHERE user1_id = ? UNION SELECT user1_id FROM friends WHERE user2_id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            die("Błąd przy przygotowywaniu zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $friends[] = $row['user2_id']; // Lub $row['user1_id'], w zależności od struktury tabeli friends
        }

        $stmt->close();

        return $friends;
    }

    // Metoda do pobierania nazwy użytkownika znajomego
    public function getFriendUsername(int $friendId): ?string {
        $sql = "SELECT Username FROM data WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Błąd przy przygotowywaniu zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("i", $friendId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $friendUsername = $row['Username'];
        } else {
            $friendUsername = null; // Jeśli nie ma użytkownika o podanym ID
        }

        $stmt->close();

        return $friendUsername;
    }

    public function getFriendAvatar(int $friendId): ?string {
        $sql = "SELECT profile_picture FROM data WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Błąd przy przygotowywaniu zapytania: " . $this->conn->error);
        }
    
        $stmt->bind_param("i", $friendId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $avatarPath = $row['profile_picture'];
        } else {
            $avatarPath = null; // Jeśli nie ma użytkownika o podanym ID
        }
    
        $stmt->close();
    
        return $avatarPath;
    }

    // Metoda do wysyłania wiadomości
    public function sendMessage(int $senderId, int $receiverId, string $message): bool {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Błąd przy przygotowywaniu zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("iis", $senderId, $receiverId, $message);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Metoda do pobierania wiadomości między dwoma użytkownikami
    public function getMessages(int $user1Id, int $user2Id): array {
        $messages = [];

        $sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at ASC";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Błąd przy przygotowywaniu zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("iiii", $user1Id, $user2Id, $user2Id, $user1Id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        $stmt->close();

        return $messages;
    }
}
?>
