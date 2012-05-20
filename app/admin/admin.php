<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Admin Controller for DATOID
 */

// Handle the FIRST visit

if ($first && file_exists(PATH . 'app/install')) {
    rename(PATH . 'app/install',PATH . 'app/installed');    
}


// Determine the appropriate action.

$action = 'login';
if (Security::HasPermissionOrGreater(1))
    $action = 'dashboard';

// Include the proper files.

$includes = PATH . '/app/admin/includes/';

require $includes . 'admin-header.phtml'; 

require $includes . 'admin-' . $action . '.phtml';

require $includes . 'admin-footer.phtml';