<?php
interface UserInterface {
    public function getFriendsList(int $userId): array;
}

trait UserTrait {
    public function getFriendsList(int $userId): array {
        // Implementacja metody w trait
        $friends = []; // Placeholder, w rzeczywistości implementacja zapytania do bazy danych

        // Przykładowe zapytanie do bazy danych
        // Tutaj mogłoby być zapytanie do bazy danych, aby pobrać listę znajomych użytkownika $userId
        // $friends = $this->db->query("SELECT * FROM friends WHERE user1_id = $userId OR user2_id = $userId");

        return $friends;
    }
}
?>
