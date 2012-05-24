<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      Naviagtion Utility for DATOID
 */

class Navigate {
    
    /**
     *      By default, this redirects back one step in history, without
     *      generating any output or logging this url into the history.
     * 
     * @param int $steps
     * @param bool $invisible
     */
    
    public static function back($steps = 1, $invisible = true) {
        $url = Link::back($steps);
        Response::redirect($url, $invisible);
    }
    
    public static function home($invisible = true) {
        $url = Link::home();
        Response::redirect($url, $invisible);
    }
    
    public static function url($url, $invisible = true) {
        $url = Link::absolute($url);
        Response::redirect($url);
    }    
}