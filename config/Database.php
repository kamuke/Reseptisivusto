<?php

    class Database {
        // DB Params
        private $host = 'localhost';
        private $db_name = 'reseptisivustodb';
        private $username = 'root';
        private $password = '';
        private $conn;

        // DB Connect
        public function connect() {
            $this->conn = null;

            try { 
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                // Create path to php.log
                $logFile = "../../logs/php.log";

                // Timestamp to right timezone
                $timezone = 'Europe/Helsinki';
                $timestamp = time();
                $dateTime = new DateTime("now", new DateTimeZone($timezone));
                $dateTime->setTimestamp($timestamp);
                $date = $dateTime->format("Y-m-d H:i:s");

                // Create message and add timestamp to it
                $message = "Connection Error: " . $e->getMessage();
                $logMessage = "\n[$date EEST] $message \n";

                // Put message to log file
                file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
                die();
            }

            return $this->conn;
        }
    }