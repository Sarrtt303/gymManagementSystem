<?php

class UserSchema
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createUserTable()
    {
        $query = "
        CREATE TABLE IF NOT EXISTS users (
            id int AUTO_INCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(20) NOT NULL,
            role ENUM('member', 'trainer', 'admin') NOT NULL,
            phone VARCHAR(20),
            membership_id int, 
            membership_start_date DATE,
            membership_end_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (membership_id) REFERENCES memberships(id) ON DELETE CASCADE
        )";

        if ($this->db->exec($query)) {
            echo "Users table created successfully";
        } else {
            echo "Error creating users table";
        }
    }
}
