<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      Output Buffer utility for DATOID
 */

class Response {
     
    public static function start() {
        ob_start('ob_gzhandler');
    }
    
    public static function redirect($url, $invisible = true) {
        if($invisible) {
            if(ob_get_level() > 0)
                ob_end_clean();
            Session::history(-1);
        }
        
        Session::end();
        self::header('Location', $url);
    }
    
    public static function header($name, $value) {
        header($name . ': ' . $value);
    }
    
    public static function send() {
        if(ob_get_level() > 0)
            ob_end_flush();
    }
    
}