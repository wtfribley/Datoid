<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly."); 

/*
 *      Test the Database Connection
 */

$response = array('success'=>false,'messages'=>array());

if($json->dbname === false)
    $response['messages'][] = 'Please provide a database name.';

if($json->host === false)
    $response['messages'][] = 'Please provide a host name.';

if(empty($response['messages']))
{   
    // run test
    try {        
        $dsn = 'mysql:dbname=' . $json->dbname . ';host=' . $json->host;
        new PDO($dsn, $json->dbusername, $json->dbpassword);
        $response['success'] = true;
    }
    catch(PDOException $e) {
        $response['success'] = false;
        $response['messages'][] = $e->getMessage();
    }
}

echo json_encode($response);