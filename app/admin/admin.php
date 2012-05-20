<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Admin Controller for DATOID
 */

// Handle the FIRST visit

if ($action == 'first' && file_exists(PATH . 'app/install')) {
    rename(PATH . 'app/install',PATH . 'app/installed');    
}

// Handle log out.

if ($action == 'logout') {
    Session::end();
    header('Location: /');
}


// Determine the appropriate page.

$page = 'login';
if (Security::HasPermissionOrGreater('author'))
    $page = 'dashboard';

// Include the proper files.

$includes = PATH . '/app/admin/includes/';

require $includes . 'admin-header.phtml'; 

require $includes . 'admin-' . $page . '.phtml';

require $includes . 'admin-footer.phtml';