<?php

/*
 * Base for most normal loggers in which all the function such as alert, debug, critical create
 * the appropriate loglevel and forward the request through the log function which is up to the
 * child class to define.
 * 
 * Please do NOT abuse this class and extend it before heavily customizing what the log function does
 * based on the log level. In that scenario, it would be better to 'reverse' the logic and the 
 * log function would call all the relevant other functions such as alert, and the customizations
 * would be in there.
 */


namespace iRAP\Logging;

abstract class LoggerAbstract implements LoggerInterface
{            
    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function alert($message, array $context = array()) 
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    
    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function critical($message, array $context = array()) 
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    
    /**
     * Detailed debug information.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function debug($message, array $context = array()) 
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    
    /**
     * System is unusable.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function emergency($message, array $context = array()) 
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    
    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function error($message, array $context = array()) 
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    
    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function info($message, array $context = array()) 
    {
        $this->log(LogLevel::INFO, $message, $context);
    }


    /**
     * Normal but significant events.
     *
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return null
     */
    public function notice($message, array $context = array()) 
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    
    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     * 
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * @return void
     */
    public function warning($message, array $context = array()) 
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    
    
    /**
     * Logs with an arbitrary level.
     *
     * @param int $level - the priority of the message - see LogLevel class
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * 
     * @return void
     */
    abstract function log($level, $message, array $context = array());
}

