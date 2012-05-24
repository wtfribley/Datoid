<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      Link-creation Utility for DATOID
 */
class Link {
    
    public static function relative($from, $uri = '') {
        $path = pathinfo($from, PATHINFO_DIRNAME);
        $path = str_replace(PATH, '', $path) . '/' . $uri;
        $path = str_replace(array('//','\\'), '/', $path);
        
        return '/' . $path;
    }
    
    public static function absolute($url = '') {
        $path = Input::server('http_host') . '/' . $url;
        $path = 'http://' . ltrim(str_replace(array('//','\\'), '/', $path),'/');
        
        return $path;
    }
    
    public static function back($steps = 1) {
        return Session::history($steps);
    }
    
    public static function home() {
        return Config::get('app.base_url', '/');
    }
}