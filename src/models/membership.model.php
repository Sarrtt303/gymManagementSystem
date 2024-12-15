<?php

class MembershipSchema
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createMembershipsTable()
    {
        try {
            $query = "SHOW TABLES LIKE 'memberships'";
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $query = "
                CREATE TABLE IF NOT EXISTS memberships(
                    id int AUTO_INCREMENT NOT NULL,
                    name VARCHAR(50) NOT NULL UNIQUE,
                    price DECIMAL(20,2) NOT NULL,
                    duration ENUM('Monthly','Quarterly','Yearly') NOT NULL,
                    description VARCHAR(100),
                    PRIMARY KEY (id)
                )";

                if ($this->db->exec($query)) {
                    echo "Membership table created successfully";
                }
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }
}
