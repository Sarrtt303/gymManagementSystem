<?php

class AttendanceSchema
{
    private $conn;
    private $table = "attendance";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create attendance table
    public function createAttendanceTable()
    {
        $query = "
            CREATE TABLE IF NOT EXISTS $this->table (
                id INT AUTO_INCREMENT PRIMARY KEY,
                member_id INT NOT NULL,
                trainer_id INT DEFAULT NULL,
                entry_time DATETIME NOT NULL,
                exit_time DATETIME DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (member_id) REFERENCES users(id),
                FOREIGN KEY (trainer_id) REFERENCES users(id)
            );
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    // Add manual attendance
    public function addAttendance($memberId, $trainerId, $entryTime, $exitTime = null)
    {
        $query = "INSERT INTO $this->table (member_id, trainer_id, entry_time, exit_time) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$memberId, $trainerId, $entryTime, $exitTime]);
    }

    // Get attendance logs
    public function getAttendanceLogs($memberId)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$memberId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
