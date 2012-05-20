<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      Here are Datoid's security utilities.
 * 
 *      Thanks to www.openwall.com/phpass for their public-domain Password Hasher.
 */

class Security {
    
    /**
     *  List of available permissions levels, ordered by level of access
     *  (from 'none' to 'admin')
     * @var array 
     */
    private static $permits = array('none','author','admin');
    
    public static function HasPermissionOrGreater($level, $usr = null) {
        
        (Session::get('permissions')) ? $permit = array_search(Session::get('permissions'), self::$permits) :
            $permit = false;
        
        if(is_string($level))
            $level = array_search($level, self::$permits);
        
        if (is_null($usr)) {
            
            
            if ($permit === false || !in_array($level, self::$permits))
                return false;
            
            if ($level > $permit)
                return false;
        }
        
        return true;
    }
    
    public static function HasPermission($level, $usr = null) {
        
        $permit = Session::get('permissions');
        
        if (is_null($usr)) {
            
            if ($permit === false || !in_array($level, self::$permits) || $permit != $level)
                return false;        
        }
        
        return true;
    }
       
    public static function GenerateHash($str) {           
        $hasher = self::hasher();
        
        return $hasher->HashPassword($str);        
    }
    
    public static function CheckPassword($str, $hash) {
        $hasher = self::hasher();
        
        return $hasher->CheckPassword($str, $hash);
    }
    
    private static function hasher()
    {
        // Hashing config variables.
        $hash_cost_log2 = 12;
        $hash_portable = false;      
        
        return new PasswordHash($hash_cost_log2, $hash_portable);
    }
}