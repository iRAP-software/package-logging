<?php


namespace iRAP\Logging;


class UdpLogReciever
{
    protected $m_maxSizeBytes;
    protected $m_port;
    protected $m_callback;
    protected $m_socket;
    
    
    /**
     * Create a UDP log receiver.
     * @param int $maxSizeBytes - the max size in bytes of a message.
     * @param int $listenPort - the port to listen on
     * @param \iRAP\Logging\callable $callback - a callback to handle received logs. This will be
     *                                          provided the message, and then the from address.
     * @param type $listenAddress - the IP address to listen on. Defaults to 0.0.0.0 to listen
     *                              on all interfaces.
     * @param type $nonBlockingListen - optionally set to true to have the listen() method not block
     *                                  until a message comes in. By default we wait once you call
     *                                  listen().
     * @throws \Exception
     */
    public function __construct(
        int $maxSizeBytes, 
        int $listenPort, 
        callable $callback, 
        $listenAddress="0.0.0.0",
        $nonBlockingListen = false
    )
    {
        $this->m_maxSizeBytes = $maxSizeBytes;
        $this->m_port = $listenPort;
        $this->m_callback = $callback;
        $this->m_socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        
        if ($this->m_socket === FALSE)
        {
            throw new \Exception("Can't create socket.");
        }
        
        if ($nonBlockingListen)
        {
            socket_set_nonblock($this->m_socket);
        }
        
        socket_set_option($this->m_socket, SOL_SOCKET, SO_REUSEADDR, 1);
        
        if (socket_bind($this->m_socket, $listenAddress, $this->m_port) === FALSE)
        {
            throw new \Exception("Socket_bind failed.");
        }
    }
    
    
    /**
     * Listen for incoming UDP logs. When a log is received, this will pass the details to
     * the callback method that was provided to this object's constructor.
     * @throws \Exception
     */
    public function listen()
    {
        $from = ''; // this will get overwritten;
        $buffer = ''; // this will get overwritten

        // refer to http://php.net/manual/en/function.socket-recvfrom.php
        $bytesRecieved = socket_recvfrom( 
            $this->m_socket, 
            $buffer, 
            $this->m_maxSizeBytes, 
            $flags = 0, 
            $from,
            $this->m_port
        );
        
        if ($bytesRecieved > 0)
        {
            $callback = $this->m_callback;
            $messageObject = json_decode($buffer, true);
            
            if ($messageObject !== null)
            {
                $expectedParams = array('message', 'log_level', 'context');
                
                foreach ($expectedParams as $param)
                {
                    if (!isset($messageObject[$param]))
                    {
                        throw new \Exception("Missinge expected param {$param}: " . $buffer);
                    }
                }
                
                $message = $messageObject['message'];
                $logLevel = $messageObject['log_level'];
                $context = $messageObject['context'];
                $callback($from, $message, $logLevel, $context);
            }
            else
            {
                throw new \Exception("Invalid message recieved: " . $buffer);
            }
        };
    }
}
