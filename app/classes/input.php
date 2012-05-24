<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

class Input {
    
    public static function clean($str) {
        // handle magic quotes for people who cant turn it off
        if(function_exists('get_magic_quotes_gpc')) {
            if(get_magic_quotes_gpc()) {
                return is_array($str) ? array_map(array('Input', 'clean'), $str) : stripslashes($str);
            }
        }
        return $str;
    }
    
    private static function fetch_array($array, $key, $default = false) {
        if(is_array($key)) {
            $data = array();

            foreach($key as $k) {
                $data[$k] = self::fetch_array($array, $k, $default);
            }

            return $data;
        }

        if(array_key_exists($key, $array)) {
            return self::clean($array[$key]);
        }

        return $default;
    }
    
    public static function cookie($key, $default = false) {
        return self::fetch_array($_COOKIE, $key, $default);
    }
    
    public static function get($key, $default = false)
    {
        return self::fetch_array($_GET, $key, $default);
    }
    
    public static function post($key, $default = false)
    {
        return self::fetch_array($_POST, $key, $default);
    }
    
    public static function server($key, $default = false)
    {
        return self::fetch_array($_SERVER, strtoupper($key), $default);
    }
    
    public static function querystring($fields = 1)
    {
        $get = array_filter($_GET);
        
        if (empty($get))
            return false;               
        
        $get = self::clean($get);
        
        if(is_array($fields))
        {
            $query = array();
            foreach ($fields as $field)
            {
                (array_key_exists($field, $_GET)) ? $query[$field] = $_GET[$field] : $query[$field] = false;
            }
            return $query;
        }
        
        if(is_string($fields) && $fields != 'all')
        {
            $query = '';
            (array_key_exists($fields, $_GET)) ? $query = $_GET[$fields] : $query = false;
            return $query;
        }
        
        if(is_int($fields) && $fields > 0)
        {
            if(count($get) > $fields)
                array_splice($get, $fields);
            
            if($fields == 1)
                $get = array(key($get),current($get));
            
            return $get;
        }
        
        return $get;
    }
    
    public static function method($type = null)
    {
        if (!is_null($type))
        {
            return (self::server('request_method') == $type);
        }
        
        return self::server('request_method');
    }
    
    
}