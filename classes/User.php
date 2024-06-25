<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct(mysqli $conn) {
        $this->db = new Database($conn);
    }

    public function register($username, $name, $secondName, $surname, $gender, $email, $password, $dateOfBirth, $country, $city) {
        // Walidacja hasła
        if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
            return "Hasło musi mieć co najmniej 8 znaków i zawierać przynajmniej jedną cyfrę.";
        }
        $userData = [
            'Username' => $username,
            'Name' => $name,
            'Second_name' => $secondName,
            'Surname' => $surname,
            'Gender' => $gender,
            'E_mail' => $email,
            'Password' => $password,
            'Date_of_birth' => $dateOfBirth,
            'Country' => $country,
            'City' => $city
        ];

        // Wstawienie użytkownika do bazy danych
        if ($this->db->insertUser($userData)) {
            return true;
        } else {
            return "Błąd podczas rejestracji użytkownika.";
        }
    }
}
?>
