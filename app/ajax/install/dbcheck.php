<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly."); 

/*
 *      Test the Database Connection
 */

$response = array('success'=>false,'messages'=>array());

// Clean Input - probably not needed, but just in case.

$data = Input::clean($_POST);

if($data['dbname'] === false)
    $response['messages'][] = 'Please provide a database name.';

if($data['host'] === false)
    $response['messages'][] = 'Please provide a host name.';

if(empty($response['messages']))
{   
    // run test
    try {        
        $dsn = 'mysql:dbname=' . $data['dbname'] . ';host=' . $data['host'];
        new PDO($dsn, $data['dbusername'], $data['dbpassword']);
        $response['success'] = true;
    }
    catch(PDOException $e) {
        $response['success'] = false;
        $response['messages'][] = $e->getMessage();
    }
}

echo json_encode($response);