<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      From Anchor CMS by @visualidiot, https://github.com/anchorcms/anchor-cms
 */

class Error {
    
    public static function native($code, $error, $file, $line) {
        // no error reporting nothing to do
        if(error_reporting() === 0) {
            return;
        }

        $exception = new ErrorException($error, $code, 0, $file, $line);

        if(in_array($code, Config::get('error.ignore', array()))) {
            return Log::exception($exception);
        }

        self::exception($exception);
    }

    public static function shutdown() {
        if(!is_null($error = error_get_last())) {          
            // Create variables to be used in the new ErrorException
            extract($error, EXTR_SKIP);
            self::exception(new ErrorException($message, $type, 0, $file, $line));
        }
    }

    public static function exception($e, $user = false) {
        // Clean the output buffer.
        if(ob_get_level() > 0) {
            ob_clean();
        }
        
        if ($user === false)
        {
            // log exception
            Log::exception($e);

            // Display error
            if(Config::get('error.detail', true)) {
                // Get the error file.
                $file = $e->getFile();

                // Trim the period off of the error message.
                $message = rtrim($e->getMessage(), '.');

                $line = $e->getLine();
                $trace = $e->getTraceAsString();
                $contexts = self::context($file, $e->getLine());

                require PATH . 'app/admin/error_php.phtml';
            } else {
                require PATH . 'app/admin/error_500.phtml';
            }
        }
        else
        {
            Log::write('warning', $e);
            
            require PATH . 'app/admin/error_user.phtml';
        }

        exit(1);
    }
    
    public static function user($message)
    {
        self::exception($message, true);
    }

    private static function context($path, $line, $padding = 5) {
        if(file_exists($path)) {
            $file = file($path, FILE_IGNORE_NEW_LINES);

            array_unshift($file, '');

            // Calculate the starting position.
            $start = $line - $padding;

            if($start < 0) {
                    $start = 0;
            }

            // Calculate the context length.
            $length = ($line - $start) + $padding + 1;

            if(($start + $length) > count($file) - 1) {
                    $length = null;
            }

            return array_slice($file, $start, $length, true);
        }

        return array();
    }
}