<?php


/*
 * This class is a logger which sends out emails.
 */


namespace iRAP\Logging;

class UdpLogger extends LoggerAbstract
{
    private $m_host;
    private $m_port;
    
    
    /**
     * Create a logger that will send logs over UDP to a receiver. This allows you to 
     * "fire and forget" logs and is useful if you don't wish for logging to slow down your 
     * application and it is not critical that logs are received.
     * @param string $host - the host of the receiver that should listen for the logs;
     * @param int $port - the port the receiver server is listening for logs on.
     */
    public function __construct(string $host, int $port)
    {
        $this->m_host = $host;
        $this->m_port = $port;
    }
    
    
    /**
     * Logs with an arbitrary level.
     *
     * @param int $level - the priority of the message - see LogLevel class
     * @param string $message -  the message of the error, e.g "failed to connect to db"
     * @param array $context - name value pairs providing context to error, e.g. "dbname => "yolo")
     * 
     * @throws \Exception - if fails to create socket to send UDP log on
     */
    public function log($level, $message, array $context = array()) 
    {
        $arrayForm = array(
            'message'   => $message,
            'log_level' => $level,
            'context'   => $context,
        );
        
        $jsonString = json_encode($arrayForm);
        
        if ($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) 
        {
            $bytesSent = socket_sendto(
                $socket, 
                $jsonString, 
                strlen($jsonString), 
                0, 
                $this->m_host, 
                $this->m_port
            );
            
            if ($bytesSent === FALSE)
            {
                throw new Exception("Failed to send UDP log.");
            }
        }
        else 
        {
            throw new \Exception("Can't create socket.");
        }
    }
}