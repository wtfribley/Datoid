<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Admin Controller for DATOID
 */

// Handle the FIRST visit

if ($action == 'first' && file_exists(PATH . 'app/install')) {
    rename(PATH . 'app/install',PATH . 'app/installed');
    $action = 'dashboard';
}

// Handle log out.

if ($action == 'logout') {
    Session::regenerate(false);
    // reload the page... may need to change depending on how logged vs. logged
    // out navigation ends up working.
    Navigate::url(URL::raw());
}


// Determine the appropriate page.

if (Security::HasPermissionOrGreater('author') || $action == 'login') {
        
    // Include the proper files.
    $includes = PATH . 'app/admin/includes/';
    if(file_exists($includes . 'admin-' . $action . '.phtml')) {
        require $includes . 'admin-' . $action . '.phtml';
    }
    else {
        $action = 'Not Found';
        require PATH . 'app/admin/errors/error_404.phtml';
    }
    
}
elseif ($action != 'login') {
    Navigate::url('/admin?a=login');
}