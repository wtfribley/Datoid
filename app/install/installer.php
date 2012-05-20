<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly."); ?>

<!doctype html>
<html lang="en-us">
    
<head>
    <meta charset="utf-8">
    <title>Install Datoid</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="stylesheet" href="<?= URL::absolute('/css/main.css'); ?>">
    <link rel="stylesheet" href="<?= URL::relative(__FILE__,'install.css'); ?>">
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.js"><\/script>');</script>
    <script src="<?= URL::relative(__FILE__,'install.js'); ?>"></script>
</head>
    
<body>   
    <h1><a href="<?= URL::absolute() ?>">Datoid</a></h1>
    
<?php

// Clean $_POST array (probably not needed, but just in case)

$data = Input::clean($_POST);

// Set up array to hold errors

$errors = array();

// WRITE THE CONFIG FILE

$config_template = file_get_contents(PATH . 'config.default.php');

$base_url = trim($data['site-path'],'/');
$path_url = '/' . $base_url;

(empty($base_url)) ? $base_url = '/' : $base_url = '/' . $base_url . '/';

$search = array(
    "'host' => 'localhost'",
    "'username' => 'root'",
    "'password' => 'root'",
    "'name' => 'datoid'",
    
    "'base_url' => '/'",
    "'timezone' => 'UTC'",
    
    "'path' => '/'"
);
        
$replace = array(
    "'host' => '" . $data['host'] . "'",
    "'username' => '" . $data['dbusername'] . "'",
    "'password' => '" . $data['dbpassword'] . "'",
    "'name' => '" . $data['dbname'] . "'",
    
    "'base_url' => '" . $base_url . "'",
    "'timezone' => '" . $data['timezone'] . "'",
    
    "'path' => '" . $path_url . "'"
);

$config = str_replace($search, $replace, $config_template);
$config_path = PATH . 'config.php';

if(file_put_contents($config_path, $config)) {
    chmod($config_path, 0640);
} else
    $errors[] = "Could not write configuration file.";

Config::load($config_path);

// CREATE OUR FIRST TWO DATOIDS (and the only Datoids that are mandatory)

new Datoid(array(
    'name' => 'metadata',
    'fields' => array(
        'site_name' => 'varchar(64)',
        'site_description' => 'text',
        'theme' => 'varchar(64)',
        'site_email' => 'varchar(255)'
    )
));

$metadata = array(
    'site_name' => $data['site-name'],
    'site_description' => $data['site-description'],
    'theme' => $data['theme'],
    'site_email' => $data['email']
);

if (DB::insert('metadata', $metadata) === false)
    $errors[] = "Could not add site metadata.";

new Datoid(array(
    'name' => 'users',
    'fields' => array(
        'username' => 'varchar(30)',
        'password' => 'varchar(64)',
        'realname' => 'varchar(64)',
        'permissions' => 'varchar(10)'
    )
));

$userdata = array(
    'username' => $data['adminusername'],
    'password' => Security::GenerateHash($data['adminpassword']),
    'realname' => $data['realname'],
    'permissions' => 'admin'
);

if (DB::insert('users', $userdata) === false)
    $errors[] = "Could not create user data.";


// Display Responses

if(!empty($errors)) {
    // @todo: show errors
}

else {
?>
<div class="content">
    <h2>Install <small>Complete!</small></h2>
    
    <p>
        Your first two <span class="monospace">Datoids</span> have been created. 
        One is called <code>metadata</code> and stores information about your site, 
        like its name and description. The other is <code>users</code>, which contains
        your login credentials.
    </p>
    <p>
        These are the only two Datoids that are mandatory - and even they can be
        tinkered with, adding new fields to store additional data.
    </p>
    
    <hr />
    
    <h3>What's Next?</h3>
    <p>
        Well, it would be a good idea to rename or delete the install folder - 
        it's found in the <code>app</code> directory.
    </p>
    <p>
        After that, you can navigate to the Admin area to create more 
        <span class="monospace">Datoids</span> or work with some of the default 
        <span class="monospace">Datoids</span> like <code>posts</code> or <code>pages</code>.
    </p>
    <p>
        In fact, to make the whole process easier, the button below will automagically
        rename the install folder <strong>and</strong> take you to the Admin area 
        where you can sign in and get started!
    </p>
    <p class="textcenter">
        <a class="btn btn-success" href="/admin?first=true" title="Finish!">Finish!</a>
    </p>
    
</div>
    
<?php
}
?>

<footer style="margin-top: 60px"></footer>
    
</body>
</html>