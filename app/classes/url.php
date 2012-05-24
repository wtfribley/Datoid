<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

class URL {
 
    private static $url_raw = '',
                    $url_processed = array();
    
    /*
     *      Process the URL
     * 
     *      1. Gets and stores the Raw URL (useful as referrer info)
     *      2. Converts the URL into an array consisting of:
     *          - the "datoid" i.e. the type of page
     *          - a "locator" i.e. an array giving the search terms for
     *            the specific page.
     *      3. Stores the processed url array
     * 
     *      if $return == true:
     *      4. Returns the processed url array
     */
        
    public static function process($return = true)
    {
        // This will hold the processed URI
        $uri = array();
        
        // Try REQUEST_URI
        if (isset($_SERVER['REQUEST_URI']))
        {
            // Parse the URL or Die!
            if(($uri = parse_url(Input::server('request_uri'))) === false) {
                throw new Exception('Malformed request URI');
            }
            else {
                self::$url_raw = Input::server('request_uri');
            }
        }
        
        // Cannot Process URL
        else
        {
            throw new Exception('Unable to determine the requested URL.');
        }
        
        // Remove the Base URL (necessary if DATOID is installed in a subfolder)
        $base_url = parse_url(Config::get('app.base_url'), PHP_URL_PATH);
        
        if(strlen($base_url)) {
            if(strpos($uri['path'], $base_url) === 0) {
                $uri['path'] = substr($uri['path'], strlen($base_url));
            }
        }
        
        // Trim it up
        $uri['path'] = trim($uri['path'],'/');
         
        // Explode the path
        $path = explode('/',$uri['path']);

        // Get the Datoid - a.k.a. the page type (the "action" in MVC terms)          
        ($path[0] == '') ? $datoid = false : $datoid = $path[0];
        
        // Get the locator - a.k.a. the field and value used to search the
        //                   Datoid for a particular entry.
        if(isset($uri['query']))
        {
            $locator = array();
            parse_str($uri['query'],$locator);
            $locator = Input::clean($locator);
        }
        elseif(isset($path[1]) && is_string($path[1]))
        {
            $locator = array('slug'=>$path[1]);
        }
        else
            $locator = false;        
        
        self::$url_processed = array('datoid'=>$datoid,'locator'=>$locator);
                
        if($return)
            return self::$url_processed;      
    }
    
    public static function raw()
    {
        if(self::$url_raw == '')
            self::process(false);
        
        return self::$url_raw;
    }
    
    public static function get($key = null, $default = false)
    {
        if(empty(self::$url_processed))
            self::process(false);
        
        if(is_null($key))
            return self::$url_processed;
        
        if(array_key_exists($key, self::$url_processed))
            return self::$url_processed[$key];
        else
            return $default;        
    }
    
}