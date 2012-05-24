<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      DATOID - Default Configuration
 * 
 *      This file will be overwritten and renamed on install.
 */

return array(
    // Database Details
    'database' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'name' => 'datoid'
    ),
    
    // Application Settings
    'app' => array(
        // web paths
        'base_url' => '/',
        'index_page' => 'index.php',
        
        // number of history steps
        'history_steps' => 10,
        
        // timezone
        'timezone' => 'UTC'       
    ),
    
    // Session Details
    'session' => array(
        'name' => 'datoid',
        'expire' => 86400,
        'path' => '/',
        'domain' => ''
    ),
    
    // Error Settings
    'error' => array(
        'ignore' => array('E_NOTICE', 'E_USER_NOTICE', 'E_DEPRECATED', 'E_USER_DEPRECATED'),
        'detail' => false,
        'log' => true,
        'logfile' => 'datoid'
    )
);