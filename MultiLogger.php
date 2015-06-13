<?php

/*
 * This is a compound logger that just combines other loggers.
 * It itself does not do anything, it just forwards the requests to the other loggers that are
 * registered with it.
 */


namespace iRAP\Logging;


class MultiLogger extends LoggerAbstract
{
    protected $m_loggers;
    
    
    
    /**
     * Creates a logger that joins two other loggers. When a log is executed, 
     * the log action on both loggers is invoked.
     * @param LoggerInterface $logger
     * @param LoggerInterface $logger2
     */
    public function __construct(LoggerInterface $logger, LoggerInterface $logger2)
    {
        $this->m_loggers[] = $logger;
        $this->m_loggers[] = $logger2;
    }
    
    
    /**
     * Add another logger to the list of loggers to be forwarded requests.
     * @param LoggerInterface $logger - the logger to be added.
     */
    public function register(LoggerInterface $logger)
    {
        $this->m_loggers[] = $logger;
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
    public function log($level, $message, array $context = array()) 
    {
        foreach ($this->m_loggers as $logger)
        {
            /* @var $logger LoggerInterface */
            $logger->log($level, $message, $context);
        }
    }
}
