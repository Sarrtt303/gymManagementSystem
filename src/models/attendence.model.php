<?php

class AttendenceSchema
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createAttendencesTable()
    {
        try {
            $query = "SHOW TABLES LIKE 'attendences'";
            $stmt = $this->db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$result) {
                $query = "
                CREATE TABLE IF NOT EXISTS attendences(
                    id int AUTO_INCREMENT NOT NULL,
                    uid int NOT NULL,
                    class_id int NOT NULL,
                    date DATE NOT NULL,
                    PRIMARY KEY (id),
                    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
                )";

                if ($this->db->exec($query)) {
                    echo "Attendence table created successfully";
                }
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
