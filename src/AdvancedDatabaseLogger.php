<?php


/*
 * Decorator of the basic DatabaseLogger object. This more advanced object allows us to 
 * automatically reconnect with the check_connection method.
 */

namespace iRAP\Logging;


class AdvancedDatabaseLogger extends LoggerAbstract
{
    protected $m_logTable;
    
    private $m_db_host;
    private $m_db_username;
    private $m_db_password;
    private $m_db_name;
    private $m_db_port;
    
    private $m_databaseLogger; # databaseLogger object that we are decorating/wrapping
    
    
    /**
     * Creates a database logger using the provided mysqli connection and table
     * @param mysqli $connection - a connected mysqli instance that we can log to
     * @param type $table - the name of the table that will store the logs
     * @return void - (constructor)
     */
    public function __construct($table, $host, $username, $password, $db_name, $port=3306)
    {        
        $this->m_db_host     = $host;
        $this->m_db_username = $username;
        $this->m_db_password = $password;
        $this->m_db_name     = $db_name;
        $this->m_db_port     = $port;
        $this->m_logTable    = $table;
        
        $connection = $this->create_connection();
        $this->m_databaseLogger = new DatabaseLogger($connection, $this->m_logTable);
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
        $this->m_databaseLogger->log($level, $message, $context);
    }
    
    
    /**
     * Creates a brand new mysqli connection. Does not replace this classes static connection.
     * @return \mysqli
     */
    private function create_connection()
    {
        $connection = new \mysqli($this->m_db_host, 
                                  $this->m_db_username, 
                                  $this->m_db_password, 
                                  $this->m_db_name, 
                                  $this->m_db_port);
        
        if ($connection->connect_error) 
        {
            $msg = 'Connect Error (' . $connection->connect_errno . ') ' . 
                   $connection->connect_error;
            
            throw new \Exception($msg);
        }

        return $connection;
    }
    
    
    /**
     * Closes the database connection if it is open.
     * @param connectionName - the name of the connection we wish to close off.
     * @return void
     */
    public function close_connection()
    {
        $this->m_databaseLogger->close_connection();
    }
    
    
    /**
     * Closes a connection and reconnects to it. YOu may want to use check_connection instead
     * to automatically reconnect only if a connection is timed out etc.
     * @param string $connectionName - the name of the connection we wish to reconnect
     * @return MySqli $connectionName
     */
    public function reconnect()
    {
        $this->m_databaseLogger->close_connection();
        $this->m_databaseLogger = new DatabaseLogger($this->create_connection(), $this->m_logTable);
    }
    
    
    /**
     * Check that a connection is still connected. This is only necessary when there is expected
     * to be a long period between mysql queries so that the connection may have timed out.
     * This function will auto-reconnect if it has timed out without the dev needing to do anything
     * @param String $connectionName - the name of the connection that you wish to reconnect to
     *                                 if it has timed out.
     */
    public function check_connection()
    {        
        if (!$this->m_databaseLogger->is_connected())
        {
            $this->reconnect();
        }
    }
    
    
    /**
     * Checks whether we have been disconnected from the database. This uses the mysqli_ping method
     * that will automatically reconnect if you are NOT using the mysqlnd package.
     * @return boolean - true if connected, false if not.
     */
    public function is_connected()
    {        
        return $this->m_databaseLogger->is_connected();
    }
    
    
    /**
     * Gets the mysqli connection that the databaselogger is using
     * @return \mysqli
     * @throws Exception
     */
    public function get_connection()
    {
        return $this->m_databaseLogger->get_connection();
    }
}
