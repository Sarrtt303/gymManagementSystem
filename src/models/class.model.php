<?php

class ClassSchema
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createClassesTable()
    {
        try {
            $query = "SHOW TABLES LIKE 'classes'";
            $stmt = $this->db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$result) {
                $query = "
                CREATE TABLE IF NOT EXISTS classes(
                    id int AUTO_INCREMENT NOT NULL,
                    name VARCHAR(20) NOT NULL,
                    description VARCHAR(100),
                    trainer_id int NOT NULL,
                    schedule TIME NOT NULL,
                    PRIMARY KEY (id),
                    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE
                )";

                if ($this->db->exec($query)) {
                    echo "Classes table created successfully";
                }
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
