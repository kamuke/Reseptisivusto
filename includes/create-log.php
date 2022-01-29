<?php

    /**
     * Creates a new log to /logs/php.log file. 
     * 
     * @param   mixed   $message      The message to add to the log.
     */
    function createLog($message) {
        // Create path to php.log
        $logFile = "../../logs/php.log";

        // Timestamp to right timezone
        $timezone = 'Europe/Helsinki';
        $timestamp = time();
        $dateTime = new DateTime("now", new DateTimeZone($timezone));
        $dateTime->setTimestamp($timestamp);
        $date = $dateTime->format("Y-m-d H:i:s");

        // Create message and add timestamp to it
        $logMessage = "\n[$date EEST] $message \n";

        // Put message to log file
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }