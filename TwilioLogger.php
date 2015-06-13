<?php

/* 
 * Logger that will utilize Twilio to sent out notifications.
 */

namespace iRAP\Logging;


class TwilioLogger extends LoggerAbstract
{
    private $m_twilio_client;
    private $m_contact_number;
    
    
    /**
     * 
     * @param \iRAP\Twilio\Twilio $twilio_client
     * @param string $telephone - a mobile number such as "+447883333333"
     */
    public function __construct(\iRAP\Twilio\Twilio $twilio_client, $telephone)
    {
        $this->m_twilio_client = $twilio_client;
        $this->m_contact_number = $telephone;
    }
    
    
    /**
     * Log a message to SMS.
     * @param type $level - the level of the log, e.g ALERT
     * @param type $message - the message of the og
     * @param array $context - required by the logger interface, but will not be used
     */
    public function log($level, $message, array $context = array())
    {
        $body = LogLevel::get_name($level) . PHP_EOL .
                $message;
        
        $this->m_twilio_client->sendSMS($this->m_contact_number, $body);
    }
}