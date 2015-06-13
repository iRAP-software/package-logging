<?php


/*
 * This class is a logger which sends out emails.
 */


namespace iRAP\Logging;

class EmailLogger extends LoggerAbstract
{
    private $m_emailer;
    private $m_admins;
    private $m_service_name;
    private $m_minimum_level;
    
    
    /**
     * Creates an email logger using the provided mysqli connection and table
     * @param EmailerInterface $emailer
     * @param Array<string> $admins - array of administrator emails to send logs to.
     * @param string $serviceName - the name for whatever project/service this is about.
     * @param int $minimumLevel - (optional) the minimum level the loglevel needs to meet for an 
     *                            email to be sent. By default this is 0 which means everything.
     */
    public function __construct(\iRAP\Emailers\EmailerInterface $emailer, 
                                Array $admins, 
                                $serviceName, 
                                $minimumLevel=0)
    {
        $this->m_emailer = $emailer;
        $this->m_admins = $admins;
        $this->m_service_name = $serviceName;
        $this->m_minimum_level = $minimumLevel;
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
    public function log($level, $log_message, array $context = array()) 
    {
        if ($level >= $this->m_minimum_level)
        {
            $params = array(
                'message'  => $log_message,
                'priority' => $level,
                'context'  => json_encode($context)
            );

            $subject = $this->m_service_name . ' - ' . LogLevel::get_name($level);

            $email_body = 
                'This is an automated log from [ ' . $this->m_service_name . ' ]' . PHP_EOL . 
                 PHP_EOL .
                'ERROR MESSAGE:' . PHP_EOL . $log_message . PHP_EOL . 
                 PHP_EOL .
                'CONTEXT:' . PHP_EOL . print_r($params, true);

            foreach ($this->m_admins as $to_email)
            {
                $to_name = '';
                $this->m_emailer->send($to_name, $to_email, $subject, $email_body, $html_format = false);
            }
        }
    }
}
