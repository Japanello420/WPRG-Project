<?php
require_once 'DatabaseInterface.php';

class Database implements DatabaseInterface {
    private $conn;
    private $tableName;

    public function __construct(mysqli $conn, $tableName = 'data') {
        $this->conn = $conn;
        $this->tableName = $tableName;
    }

    public function insertUser(array $userData): bool {
        $sql = "INSERT INTO {$this->tableName} (Username, Name, Second_Name, Surname, Gender, E_mail, Password, Date_of_birth, Country, City) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $hashedPassword = password_hash($userData['Password'], PASSWORD_BCRYPT);
            $stmt->bind_param("ssssssssss", 
                $userData['Username'], 
                $userData['Name'], 
                $userData['Second_name'], 
                $userData['Surname'], 
                $userData['Gender'], 
                $userData['E_mail'], 
                $hashedPassword, 
                $userData['Date_of_birth'], 
                $userData['Country'], 
                $userData['City']
            );
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            die("Błąd przy przygotowywaniu zapytania: " . $this->conn->error);
        }
    }
}
?>
