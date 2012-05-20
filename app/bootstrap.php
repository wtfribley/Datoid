<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      Get and Set the Autoloader
 */

require PATH . '/app/classes/autoloader.php';

// we can map known classes for faster loading
Autoloader::map(array(
    'Config' => PATH . '/app/classes/config.php',
    'Datoid' => PATH . '/app/classes/datoid.php',
    'DB' => PATH . '/app/classes/db.php',
    'Error' => PATH . '/app/classes/error.php',
    'Input' => PATH . '/app/classes/input.php',
    'Log' => PATH . '/app/classes/log.php',
    'Response' => PATH . '/app/classes/response.php',
    'Route' => PATH . '/app/classes/route.php',
    'Session' => PATH . '/app/classes/session.php',
    'Themer' => PATH . '/app/classes/themer.php',
    'URL' => PATH . '/app/classes/url.php',
    'PasswordHash' => PATH . '/app/lib/PasswordHash.php'
));

// Set the directory in which we'll keep our classes
Autoloader::directory(array(
    PATH . 'app/classes/',
    PATH . 'app/lib/'
));

// Register the Autoloader
Autoloader::register();

/*
 *      Error Reporting
 */

// Report all errors - we'll handle the rest.
error_reporting(-1);

// Hide the errors, we'll display them ourselves.
ini_safe_set('display_errors', false);

// Register the PHP exception handler.
set_exception_handler(array('Error', 'exception'));

// Register the PHP error handler.
set_error_handler(array('Error', 'native'));

// Register the shutdown handler.
register_shutdown_function(array('Error', 'shutdown'));

/*
 *      Remove Magic Quotes (magic quotes is deprecated in PHP 5.3)
 */

if(function_exists('get_magic_quotes_gpc')) {
    if(get_magic_quotes_gpc()) {
        ini_safe_set('magic_quotes_gpc', false);
        ini_safe_set('magic_quotes_runtime', false);
        ini_safe_set('magic_quotes_sybase', false);
    }
}

/*
 *      Check for Installation
 */
if (Config::load(PATH . 'config.php') === false)
{
    if(Config::load(PATH . 'config.default.php') === false)
    {
        require PATH . '/app/admin/errors/error_config.phtml';
        exit(1);
    }    
}

// Set Default Timezone
date_default_timezone_set(Config::get('app.timezone'));

/*
 *      Start the Session
 */
Session::start();

/*
 *      Process the Requested URL
 */

$url = URL::process();

/*
 *      Route the URL
 */

$route = new Route($url);

/*
 *      Close out the Session
 */
//Session::end();

/*
 *      Send it all to the Browser
 */
Response::send();