Logging Package
===============
This package aims to make it easy to log things.


## UDP Logger

Using UDP for logging is great if you want to be able to fire-and-forget  your log messages,
improving performance at the penalty of not being able to guarantee log messages are received.

Here is an example of how to send a log to a server ar 192.168.1.1 on port 1234 using the UdpLogger:

```php
<?php
$udpLogger = new \iRAP\Logging\UdpLogger("192.168.1.1", 1234);
$udpLogger->alert("This is my alert message", ['time' => time()]);
```

For the UDP logger to work, you will have to have a service listening for these logs.
Below is an example of a script you could use to create such a service:

```php
<?php

# Create a callback for handling the captured UDP log
$logHandler = function($from, $message, $logLevel, $context) {
    print "{$from} ({$logLevel}): {$message}" . PHP_EOL;
    print "context: " . print_r($context, true) . PHP_EOL . PHP_EOL;
    // now do something else like log it to the database.
};

# Createthe UDP reciever which will listen for logs
$udpReciever = new iRAP\Logging\UdpLogReciever(
    $maxLength = 1024*1024*32,
    1234,
    $logHandler
);

# Call the listen() to listen for UDP logs.
print "listening for messages..." . PHP_EOL;
while (true)
{
    $udpReciever->listen();
}
```
