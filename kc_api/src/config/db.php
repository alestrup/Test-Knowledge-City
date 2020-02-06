<?php
// Database connection class

class Connection {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'kc_db';

    public function connect() {
        $mysql_connect_str = "mysql:host=$this->host;dbname=$this->name";
        $dbConnection = new PDO($mysql_connect_str, $this->user, $this->pass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbConnection;
    }
}