<?php

class BookingSchema
{
    private $conn;
    private $table = "bookings";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create booking table
    public function createBookingTable()
    {
        $query = "
            CREATE TABLE IF NOT EXISTS $this->table (
                id INT AUTO_INCREMENT PRIMARY KEY,
                session_id INT NOT NULL,
                member_id INT NOT NULL,
                status ENUM('booked', 'cancelled') DEFAULT 'booked',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (session_id) REFERENCES sessions(id),
                FOREIGN KEY (member_id) REFERENCES users(id)
            );
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    // Book a session
    public function bookSession($sessionId, $memberId)
    {
        $query = "INSERT INTO $this->table (session_id, member_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$sessionId, $memberId]);
    }

    // Get all bookings for a session
    public function getBookingsBySession($sessionId)
    {
        $query = "SELECT * FROM $this->table WHERE session_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$sessionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cancel a booking
    public function cancelBooking($bookingId)
    {
        $query = "UPDATE $this->table SET status = 'cancelled' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$bookingId]);
    }
}
