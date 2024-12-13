<?php

class Database
{
    private $host = "localhost";
    private $db_name = "gymdb";
    private $username = "root";
    private $password = null;
    public $conn;
    public function __construct()
    {
        $this->conn = null;
        try {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, username: $this->username, password: $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
