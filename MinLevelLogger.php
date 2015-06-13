<?php

/* 
 * A logger that only logs if the minimum threshold is met. 
 * This is useful for if one wants to filter out all debuglogs etc.
 */

namespace iRAP\Logging;

class MinLevelLogger extends LoggerAbstract
{
    private $m_threshold;
    private $m_sub_logger;
    
    
    public function __construct(LogLevel $threshold, LoggerInterface $logger)
    {
        $this->m_threshold = $threshold;
        $this->m_sub_logger = $logger;
    }
    
    
    public function log($level, $message, array $context = array())
    {
        if ($level > $this->m_threshold)
        {
            $this->m_sub_logger->log($level, $message, $context);
        }
    }
}