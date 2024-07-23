<?php
class DatabaseConnection2 {
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    private $connectionArr;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO ("sqlsrv:Server=$this->host;Database=$this->database",$this->username,$this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connectionArr = array(
                    "title" => 'CONNECTED',
                    "message" => 'connected successfully',
                    "connection" => $this->connection,
                    "connected" => 1
                );
            } catch (PDOException $e) {
                $this->connectionArr = array(
                    "title" => 'NO CONNECTION',
                    "message" => $e->getMessage(),
                    "connection" => null,
                    "connected" => 0
                );
                $this->connection = null;
            }
        }
        return $this->connectionArr;
    }
}