<?php

class TrainerSchema
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createTrainerTable()
    {
        $query = "
        CREATE TABLE IF NOT EXISTS trainer (
            id int AUTO_INCREMENT NOT NULL,
            uid int NOT NULL,
            name VARCHAR(100) NOT NULL,
            specialization VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            availability ENUM('MON-TUE','WED-THU','FRI-SAT','SUN'),
            PRIMARY KEY (id),
            FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE
        )";

        if ($this->db->exec($query)) {
            echo "Trainers table created successfully";
        } else {
            echo "Error creating users table";
        }
    }
}
