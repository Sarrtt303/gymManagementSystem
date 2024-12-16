<?php
class AttendanceSchema
{
    private $conn;
    private $table = "attendance";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create attendance table with enhanced constraints
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
                FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (trainer_id) REFERENCES users(id) ON DELETE SET NULL,
                CHECK (exit_time IS NULL OR exit_time > entry_time)
            ) ENGINE=InnoDB;
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    // Add manual attendance with validation
    public function addAttendance($memberId, $trainerId, $entryTime, $exitTime = null)
    {
        // Validate entry time format
        if (!strtotime($entryTime)) {
            throw new InvalidArgumentException("Invalid entry time format");
        }

        // Validate exit time if provided
        if ($exitTime && !strtotime($exitTime)) {
            throw new InvalidArgumentException("Invalid exit time format");
        }

        // Optional: Add a check to ensure the member exists
        $memberCheck = $this->conn->prepare("SELECT id FROM users WHERE id = ?");
        $memberCheck->execute([$memberId]);
        if (!$memberCheck->fetch()) {
            throw new RuntimeException("Member does not exist");
        }

        $query = "INSERT INTO $this->table (member_id, trainer_id, entry_time, exit_time) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$memberId, $trainerId, $entryTime, $exitTime]);
    }

    // Get attendance logs with optional date range
    public function getAttendanceLogs($memberId, $startDate = null, $endDate = null)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = ?";
        $params = [$memberId];

        if ($startDate) {
            $query .= " AND entry_time >= ?";
            $params[] = $startDate;
        }

        if ($endDate) {
            $query .= " AND entry_time <= ?";
            $params[] = $endDate;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}