<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

class Config {
    
    private static $settings = array();
    
    public static function load($file)
    {
        if(file_exists($file) === false)
            return false;
                
        self::$settings = require $file;              
                
        return true;
    }
    
    public static function get($key = null, $default = false)
    {
        if (is_null($key)) return self::$settings;
        
        // copy array for search
        $settings = self::$settings;
        
        // search the array, using the form "settingtype.settingname"
        foreach(explode('.',$key) as $locator)
        {
            if (!is_array($settings) || array_key_exists($locator, $settings) === false)
                return $default;
            
            $settings = $settings[$locator];
        }
        
        return $settings;
    }
    
}