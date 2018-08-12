<?php

/*
 * Run this script to recieve logs. If you don't see the logs being printed out something is wrong
 */

require_once(__DIR__ . '/../../vendor/autoload.php');

# Create a callback for handling the captured UDP log
$logHandler = function($from, $message, $logLevel, $context) {
    print "{$from} ({$logLevel}): {$message}" . PHP_EOL;
    print "context: " . print_r($context, true) . PHP_EOL . PHP_EOL;
};

# Createthe UDP reciever which will listen for logs
$udpReciever = new iRAP\Logging\UdpLogReciever(
    $maxLength = 1024*1024*1, 
    1234, 
    $logHandler,
    "0.0.0.0",
    false
);

# Call the listen() to listen for UDP logs.
print "listening for messages..." . PHP_EOL;
while (true)
{
    $udpReciever->listen();
    //usleep(1);
}
