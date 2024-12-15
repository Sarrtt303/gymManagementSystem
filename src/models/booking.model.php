<?php

class BookingSchema
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createBookingsTable()
    {
        try {
            $query = "SHOW TABLES LIKE 'booking'";
            $stmt = $this->db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$result) {
                $query = "
                CREATE TABLE IF NOT EXISTS bookings(
                    id int AUTO_INCREMENT NOT NULL,
                    uid int NOT NULL,
                    trainer_id int,
                    class_id int NOT NULL,
                    booking_date DATE NOT NULL,
                    status ENUM('confirmed', 'canceled')
                    PRIMARY KEY (id),
                    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE,
                    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
                )";

                if ($this->db->exec($query)) {
                    echo "Bookings table created successfully";
                }
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }
}
