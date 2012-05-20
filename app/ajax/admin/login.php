<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Log In action for Datoid's Admin Area
 */

$response = array('success'=>false,'error'=>'');

// Clean Input - probably not needed, but just in case.

$data = Input::clean($_POST);

// Search for User, get stored password hash.

$options = array(
    'datoid' => 'users',
    'wherefield' => 'username',
    'wherevalue' => $data['username'],
    'limit' => 1
);

$user = new Selector($options);
$stored_hash = $user->data('password');
$permissions = $user->data('permissions');

// Check for a recognized username, correct password, and appropriate permissions.

if (!$stored_hash)
    $response['error'] = 'nouser';
elseif (!Security::CheckPassword($data['password'], $stored_hash))
    $response['error'] = 'badpass';
elseif (!Security::HasPermissionOrGreater('author', $permissions))
    $response['error'] = 'unauth';
else {
   
    Session::regenerate();
    Session::set('permissions', $permissions);
    Session::set('realname', $user->data('realname'));
    
    $response['success'] = true;
}

echo json_encode($response);