<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

class Log {
    
    public static function write($severity,$message)
    {
        // Check Settings
        if(Config::get('error.log') === false) return;
        
        $line = date('m-d-Y hA:i:s') . ' [ ' . $severity . ' ] --> ' . $message . PHP_EOL;
        
        // Write to the log file.
        if($log = @fopen(PATH . '/app/log/' . Config::get('error.logfile','datoid') . '.log', 'a+'))
        {
            fwrite($log, $line);
            fclose($log);
        }
    }
    
    public static function exception($e)
    {
        self::write('error', self::format($e));
    }

    private static function format($e)
    {
        return $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }

}