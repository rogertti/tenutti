<?php
    class Database
    {
        // specify your own database credentials

        private $type = 'mysql';
        private $host = 'localhost';
        private $dbname = 'u194007705_tenutti';
        private $user = 'u194007705_tenutti';
        private $pass = 'loremIpsum9';
        public $conn;
     
        // get the database connection

        public function getConnection()
        {
            $this->conn = null;
     
            try {
                $this->conn = new PDO(''.$this->type.':host='.$this->host.';dbname='.$this->dbname.'', $this->user, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec('SET NAMES utf8');
            } catch (PDOException $exception) {
                echo 'Connection error: '.$exception->getMessage();
            }
     
            return $this->conn;
        }
    }
