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
    
    
    /**
     * Create the MinLevelLogger.
     * @param \iRAP\Logging\LogLevel $threshold - the minimum level a log has to be in order to
     *                                            actually be logged.
     * @param \iRAP\Logging\LoggerInterface $logger
     */
    public function __construct(LogLevel $threshold, LoggerInterface $logger)
    {
        $this->m_threshold = $threshold;
        $this->m_sub_logger = $logger;
    }
    
    
    public function log($level, $message, array $context = array())
    {
        if ($level >= $this->m_threshold->get_level())
        {
            $this->m_sub_logger->log($level, $message, $context);
        }
    }
}