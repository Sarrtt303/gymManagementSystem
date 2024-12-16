<?php

class UserSchema
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createUsersTable()
    {
        try {
            $query = "SHOW TABLES LIKE 'users'";    //creates table if it already doesn't exists
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $query = "
                CREATE TABLE IF NOT EXISTS users (
                    id int AUTO_INCREMENT NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password VARCHAR(20) NOT NULL,
                    role ENUM('member', 'trainer', 'admin') NOT NULL,
                    phone VARCHAR(20) NOT NULL,
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
                }
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function create($data)  //creates an entry in db
    {
        ['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role, 'phone' => $phone] = $data;
        try {
            $query = "INSERT INTO users (name, email, password, role, phone)
        VALUES (:name, :email, :password, :role, :phone)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":phone", $phone);

            return $stmt->execute();
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function read($id)  //reads an entry with matching id
    {
        try {
            $query = "SELECT name, email, role, phone FROM users WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function updateDetails($id, $data)  //updates entry with the matching id
    {
        try {
            $query = "UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":phone", $data['phone']);

            return $stmt->execute();
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function delete($id) //deletes an entry with the matching id
    {
        try {
            $query = "DELETE FROM users WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id);

            return $stmt->execute();
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function updatePassword($id, $data) //updates password field in the entry with the matching id
    {
        try {
            $query = "UPDATE users SET password = :password WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":password", $data['password']);

            return $stmt->execute();
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    public function updateRole($id, $data) //updates role in the entry with matching id
    {
        try {
            $query = "UPDATE users SET role = :role WHERE id = :id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":role", $data['role']);

            return $stmt->execute();
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }
}
