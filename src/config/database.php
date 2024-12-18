<?php

class Database   //database configuration and connection
{
    private $host = "localhost"; //hostname 
    private $port = '8000';   //port number as per your settings (default is 3306)
    private $db_name = "gymdb";  //database name
    private $username = "root";  //mysql username (change it as per your system)
    private $password = "";    //mysql password (change it as per your system)    
    public $conn;
    public function __construct()
    {
        $this->conn = null;
        try {
            //this is the db connection instance that will be used to talk to the db
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}