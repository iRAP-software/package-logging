<?php

/*
 * Use this script to send UDP logs to the reciever.
 */

require_once(__DIR__ . '/../../vendor/autoload.php');

$udpLogger = new \iRAP\Logging\UdpLogger("10.1.0.3", 1234);


while (true)
{
    print "sending log" . PHP_EOL;
    $context = array('time' => time());
    $udpLogger->alert("This is my alert message", $context);
    sleep(1);
}
