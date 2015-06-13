<?php

/**
 * Describes log levels with priority levels.
 * These make it much easier for developers to choose the right priority level by giving a human
 * readable represention.
 */


namespace iRAP\Logging;


class LogLevel
{
    const EMERGENCY = 7; # System is unusable. (still triggers alert)
    const ALERT     = 6; # Example: Entire website down, database unavailable, etc. sends email/sms
    const CRITICAL  = 5; # Application component unavailable, unexpected exception.
    const ERROR     = 4; # Runtime errors that do not require immediate action but need looking into
    const WARNING   = 3; # Use of deprecated APIs, poor use, etc. things that are not always "wrong".
    const NOTICE    = 2; # Normal but significant events.
    const INFO      = 1; # User logs in, SQL logs. etc.
    const DEBUG     = 0; # Detailed debug information.
    
    private $m_level;
    
    public function __construct($level)
    {
        if ($level < self::DEBUG || $level > self::EMERGENCY)
        {
            throw new \Exception("Invalid level specified.");
        }
        
        $this->m_level = $level;
    }
    
    /**
     * Converts an integer log level into the human readable string
     * @param int $alertLevel - the alert level we wish to convert
     * @return string - the human readable name.
     */
    public static function get_name($alertLevel)
    {
        switch ($alertLevel)
        {
            case LogLevel::ALERT:
            {
                $name = 'Alert';
            }
            break;
        
            case LogLevel::CRITICAL:
            {
                $name = 'Critical';
            }
            break;
        
            case LogLevel::DEBUG:
            {
                $name = 'Debug';
            }
            break;
        
            case LogLevel::EMERGENCY:
            {
                $name = 'Emergency';
            }
            break;
        
            case LogLevel::ERROR:
            {
                $name = 'Error';
            }
            break;
        
            case LogLevel::INFO:
            {
                $name = 'Info';
            }
            break;
        
            case LogLevel::NOTICE:
            {
                $name = 'Notice';
            }
            break;
        
            case LogLevel::WARNING:
            {
                $name = 'Warning';
            }
            break;
        
            default:
            {
                $name = 'Log level not recognized';
            }
        }
        
        return $name;
    }
    
    
    public function get_level() { return $this->m_level; }
}
