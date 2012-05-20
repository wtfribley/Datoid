<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      Here are Datoid's security utilities.
 * 
 *      Thanks to www.openwall.com/phpass for their public-domain Password Hasher.
 */

class Security {
       
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