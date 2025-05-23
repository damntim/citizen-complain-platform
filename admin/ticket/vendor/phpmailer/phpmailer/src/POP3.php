<?php



namespace PHPMailer\PHPMailer;


class POP3
{
    
    const VERSION = '6.9.3';

    
    const DEFAULT_PORT = 110;

    
    const DEFAULT_TIMEOUT = 30;

    
    public $do_debug = self::DEBUG_OFF;

    
    public $host;

    
    public $port;

    
    public $tval;

    
    public $username;

    
    public $password;

    
    protected $pop_conn;

    
    protected $connected = false;

    
    protected $errors = [];

    
    const LE = "\r\n";

    
    const DEBUG_OFF = 0;

    
    const DEBUG_SERVER = 1;

    
    const DEBUG_CLIENT = 2;

    
    public static function popBeforeSmtp(
        $host,
        $port = false,
        $timeout = false,
        $username = '',
        $password = '',
        $debug_level = 0
    ) {
        $pop = new self();

        return $pop->authorise($host, $port, $timeout, $username, $password, $debug_level);
    }

    
    public function authorise($host, $port = false, $timeout = false, $username = '', $password = '', $debug_level = 0)
    {
        $this->host = $host;
        
        if (false === $port) {
            $this->port = static::DEFAULT_PORT;
        } else {
            $this->port = (int) $port;
        }
        
        if (false === $timeout) {
            $this->tval = static::DEFAULT_TIMEOUT;
        } else {
            $this->tval = (int) $timeout;
        }
        $this->do_debug = $debug_level;
        $this->username = $username;
        $this->password = $password;
        
        $this->errors = [];
        
        $result = $this->connect($this->host, $this->port, $this->tval);
        if ($result) {
            $login_result = $this->login($this->username, $this->password);
            if ($login_result) {
                $this->disconnect();

                return true;
            }
        }
        
        $this->disconnect();

        return false;
    }

    
    public function connect($host, $port = false, $tval = 30)
    {
        
        if ($this->connected) {
            return true;
        }

        
        
        set_error_handler(function () {
            call_user_func_array([$this, 'catchWarning'], func_get_args());
        });

        if (false === $port) {
            $port = static::DEFAULT_PORT;
        }

        
        $errno = 0;
        $errstr = '';
        $this->pop_conn = fsockopen(
            $host, 
            $port, 
            $errno, 
            $errstr, 
            $tval
        ); 
        
        restore_error_handler();

        
        if (false === $this->pop_conn) {
            
            $this->setError(
                "Failed to connect to server $host on port $port. errno: $errno; errstr: $errstr"
            );

            return false;
        }

        
        stream_set_timeout($this->pop_conn, $tval, 0);

        
        $pop3_response = $this->getResponse();
        
        if ($this->checkResponse($pop3_response)) {
            
            $this->connected = true;

            return true;
        }

        return false;
    }

    
    public function login($username = '', $password = '')
    {
        if (!$this->connected) {
            $this->setError('Not connected to POP3 server');
            return false;
        }
        if (empty($username)) {
            $username = $this->username;
        }
        if (empty($password)) {
            $password = $this->password;
        }

        
        $this->sendString("USER $username" . static::LE);
        $pop3_response = $this->getResponse();
        if ($this->checkResponse($pop3_response)) {
            
            $this->sendString("PASS $password" . static::LE);
            $pop3_response = $this->getResponse();
            if ($this->checkResponse($pop3_response)) {
                return true;
            }
        }

        return false;
    }

    
    public function disconnect()
    {
        
        if ($this->pop_conn === false) {
            return;
        }

        $this->sendString('QUIT' . static::LE);

        
        
        try {
            $this->getResponse();
        } catch (Exception $e) {
            
        }

        
        
        try {
            @fclose($this->pop_conn);
        } catch (Exception $e) {
            
        }

        
        $this->connected = false;
        $this->pop_conn  = false;
    }

    
    protected function getResponse($size = 128)
    {
        $response = fgets($this->pop_conn, $size);
        if ($this->do_debug >= self::DEBUG_SERVER) {
            echo 'Server -> Client: ', $response;
        }

        return $response;
    }

    
    protected function sendString($string)
    {
        if ($this->pop_conn) {
            if ($this->do_debug >= self::DEBUG_CLIENT) { 
                echo 'Client -> Server: ', $string;
            }

            return fwrite($this->pop_conn, $string, strlen($string));
        }

        return 0;
    }

    
    protected function checkResponse($string)
    {
        if (strpos($string, '+OK') !== 0) {
            $this->setError("Server reported an error: $string");

            return false;
        }

        return true;
    }

    
    protected function setError($error)
    {
        $this->errors[] = $error;
        if ($this->do_debug >= self::DEBUG_SERVER) {
            echo '<pre>';
            foreach ($this->errors as $e) {
                print_r($e);
            }
            echo '</pre>';
        }
    }

    
    public function getErrors()
    {
        return $this->errors;
    }

    
    protected function catchWarning($errno, $errstr, $errfile, $errline)
    {
        $this->setError(
            'Connecting to the POP3 server raised a PHP warning:' .
            "errno: $errno errstr: $errstr; errfile: $errfile; errline: $errline"
        );
    }
}
