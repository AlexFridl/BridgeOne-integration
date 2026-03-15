<?php

    function logMessage($type, $message, $context = []){
        //type => INFO, ERROR, WARNING
        //message => description of the log
        //context => array of additional information
        //script => current script name for which log is created
        $scriptName = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);

        $logDir = __DIR__ . '/../logs/' . $scriptName;

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $date = date('Y-m-d');
        $logFile = $logDir . '/api_' . $date . '.log';

        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => $type,    
            'script' => basename($_SERVER['SCRIPT_NAME']),
            'message' => $message,
            'context' => $context
        ];

        $line = json_encode($logData, JSON_UNESCAPED_UNICODE) . PHP_EOL;

        file_put_contents($logFile, $line, FILE_APPEND);
    }
?>