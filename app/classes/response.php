<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

class Response {
 
    private static $content = '',
                    $headers = array(),
                    $status = 200;
    
    private static $statuses = array(
        200 => 'OK',
        302 => 'Found',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    );
    
    public static function header($name, $value)
    {
        self::$headers[$name] = $value;
    }
    
    public static function content($str)
    {
        self::$content = $str;
    }
    
    public static function append($str)
    {
        self::$content.= $str;
    }
    
    public static function send()
    {
        // Set Content Type
        if(array_key_exists('Content-Type', self::$headers) === false)
            self::$headers['Content-Type'] = 'text/html; charset=UTF-8';
        
        // Send Headers
        if(headers_sent() === false)
        {
            $protocol = Input::server('server_protocol', 'HTTP/1.1');
            
            header($protocol . ' ' . self::$status . ' ' . self::$statuses[self::$status]);
            
            foreach(self::$headers as $name => $value)
            {
                header($name . ': ' . $value, true);
            }
        }
        
        echo self::$content;
    }
    
    public static function redirect($url)
    {
        // Handle Special Cases
        switch($url) {
            case "home":                
                $url = Config::get('app.base_url','/');               
            break;
        
            case "back":              
                $url = Input::get('referrer', Config::get('app.base_url','/'));               
            break;
        }
        
        self::header('Location', $url);
        self::$status = 302;
        self::$content = '';       
    }

}