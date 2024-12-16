<?php

class Database
{
    private $host;
    private $port;
    private $user;
    private $password;
    private $database;
    public $conn;

    public function __construct($configFile, $dbKey)
    {
        $config = parse_ini_file($configFile, true);

        $mysql = $config['mysql'];
        $databases = $config['databases'];

        $this->host = $mysql['host'];
        $this->port = $mysql['port'];
        $this->user = $mysql['user'];
        $this->password = $mysql['password'];

        if (!isset($databases[$dbKey])) {
            die("Database key '$dbKey' not found in configuration.");
        }

        $this->database = $databases[$dbKey];
        $this->connect();
    }

    public function connect()
    {
        try {
            $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->database";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Database connected successfully to {$this->database}.\n";
            return $this->conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
            exit; // Stop execution on a fatal error
        }
    }
}
