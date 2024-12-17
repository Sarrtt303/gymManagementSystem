<?php

class PaymentSchema
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createPaymentsTable()
    {
        try {
            $query = "SHOW TABLES LIKE 'payments'";
            $stmt = $this->db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$result) {
                $query = "
                CREATE TABLE IF NOT EXISTS payments(
                    id int AUTO_INCREMENT NOT NULL,
                    uid int NOT NULL,
                    amount int NOT NULL,
                    payment_date DATE NOT NULL,
                    payment_method ENUM('Credit Card', 'Debit Card' ,'Internet Banking' , 'UPI') NOT NULL,
                    membership_id int NOT NULL,
                    PRIMARY KEY (id),
                    FOREIGN KEY (uid) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (membership_id) REFERENCES memberships(id) ON DELETE CASCADE
                )";

                if ($this->db->exec($query)) {
                    echo "Payments table created successfully";
                }
            }
        } catch (\PDOException $th) {
            echo $th->getMessage();
        }
    }

    //write functions here..
}
