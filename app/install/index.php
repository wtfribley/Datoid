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

    /*
     *      Check for Compatibility
     */
     $compat_warnings = array();
     
     // PDO
     if(class_exists('PDO') === false) {
         $compat_warnings[] = '<dt>Datoid requires PDO (PHP Data Objects)</dt>
            <dd>You can find more about <a href="//php.net/manual/en/book.pdo.php">installing and setting up PHP Data Objects</a> 
            on the php.net website</dd>';
     } else {
        if(in_array('mysql', PDO::getAvailableDrivers()) === false) {
            $compat_warnings[] = '<dt>Datoid requires the MySQL PDO Driver</dt>
                <dd>You can find more about <a href="//php.net/manual/en/ref.pdo-mysql.php">installing and setting up MySQL PDO Driver</a> 
                on the php.net website</dd>';
        }
    }
    
    // can we write a config file?
    // note: on win the only way to really test is to try and write a new file to disk.
    if(@file_put_contents(PATH . 'test.php', '<?php //test') === false) {
        $compat_warnings[] = '<dt>Unwritable Configuration Directory</dt><dd>It looks like the root directory is not writable, we may not be able to automatically create your config.php file. 
            Please make the root directory writable until the installation is complete.</dd>';
    } else {
        unlink(PATH . 'test.php');
    }
    
    if(count($compat_warnings)): ?>
    
    <div class="content">
        <h2>Oops!</h2>

        <p>Datoid seems to be missing some requirements:</p>

        <dl>
            <?php foreach($compat_warnings as $warning): ?>
            <?= $warning; ?>
            <?php endforeach; ?>
        </dl>

        <p class="textcenter"><a href="./install" class="btn btn-success">Ok, I've fixed these, run the installer.</a></p>
    </div>
    
<?php 

    /*
     *      Is Datoid already installed?
     */
    elseif(file_exists(PATH . 'config.php')): ?>
    	
    <div class="content">
        <h2>Oops!</h2>

        <p>Datoid is already installed. Delete the "install" folder to prevent users from seeing this warning!</p>

        <p><a href="<?= URL::absolute(); ?>" class="button" style="float: none; display: inline-block;">Return to the main site.</a></p>
    </div>
    	
<?php 
    
    /*
     *      Everything Checks Out!
     */
    else: ?>
    
    <div class="content">
        <h2>Datoid <small>Installer</small></h2>
        
        <dl>
            <dt><span class="monospace">Datoid</dt>
                <dd><em>noun</em> -- a <strong>type</strong> of data, having various user-defined properties.<br />
                <span class="tab"> </span>Analogous to (actually, it <em>is</em>) a <strong>table</strong> in a database.</dd>
        </dl>
        
        <p><span class="monospace">Datoid</span> is a Content Management System 
            designed for people who crave flexibility. It provides the tools to 
            store all sorts of data - from <strong>posts</strong> or <strong>pages</strong> 
            to information about <strong>users</strong> or visitor <strong>statistics</strong>. 
        </p>
        <p>You can also define the relationships between Datoids - 
            <strong>posts</strong> have a <em>many-to-many</em> relationship 
            with <strong>categories</strong>, and a <em>one-to-many</em> relationship 
            with <strong>comments</strong>.           
        </p>
        
        <p>What does this mean? You can design the structure of your CMS quickly and intuitively. 
            If you need flexibility, <span class="monospace">Datoid</span> is for you.
            <hr style="margin-bottom: 30px"/>
        </p>
        <p>Just fill out the form below and you'll be on your way to creating 
            beautiful web sites with a simple, fast, and flexible back-end.           
        </p>
        
        <small>If you'd like to do this manually, feel free to edit <code>config.default.php</code> 
        and rename it to <code>config.php</code> - then be sure to delete the install folder.</small>    
        
        <form method="post" action="install/installer.php" novalidate="custom">
            
            <fieldset class="cf" id="database-info">
                <legend>Your Database Information<small class="pull-right"><em>your web host will have this info</em></small></legend>
                <div class="input-group cf">
                    <label class="inline" for="host">your host name:</label>
                    <input name="host" autocorrect="off" autocapitalize="off" value="localhost">                  
                </div>
                <div class="input-group cf">
                    <label class="inline" for="dbusername">your username:</label>
                    <input name="dbusername" autocorrect="off" autocapitalize="off" value="root">                  
                </div>
                <div class="input-group cf">
                    <label class="inline" for="dbpassword">database password:</label>
                    <input name="dbpassword" autocorrect="off" autocapitalize="off" value="root">                  
                </div>
                <div class="input-group cf">
                    <label class="inline" for="dbname">database name:</label>
                    <input name="dbname" autocorrect="off" autocapitalize="off" value="datoid">                  
                </div>
                
                <div class="input-group cf textcenter">
                    <a id="db-check" class="btn btn-warning">Check Database Connection</a>
                </div>
                
                <div id="db-check-response" class="textcenter">
                    <p>Oops!</p>
                </div>
            </fieldset>
            
            <fieldset class="cf" id="site-metadata">
                <legend>Details About Your Site</legend>
                <div class="input-group cf">
                    <label class="inline" for="site-name">your site name:</label>
                    <input name="site-name" placeholder="My Datoid Site">
                </div>
                <div class="input-group cf">
                    <label class="textcenter" for="site-name">site description</label>
                    <textarea name="site-description"></textarea>                 
                <hr />
                </div>
                <!-- themes section -->
                
                <div class="input-group cf">
                    <label class="textcenter">starting theme</label>
                    <ul class="accordion unstyled">
<?php
                foreach(glob(PATH . 'themes/*') as $theme):
                    $about = $theme . '/about.txt';
                    if(file_exists($about)):
                        $theme = preg_replace('/\/(\w+\/)+/','',$theme);
                        $about = Themer::about($about); ?>
                
                        <li class="accordion-group<?php if($about['title'] == "Default"){?> selected<?php }?>">
                            <a data-toggle="collapse" data-parent=".accordion" data-target="#<?= $theme; ?>"><?= $about['title']; ?></a>
                            <div id="<?= $theme; ?>" class="collapse<?php if($about['title'] == "Default"){?> in<?php }?> cf">
                                <p><?= $about['description']; ?></p>
                                <small class="pull-right">by <?= $about['author']; ?></small>
                            </div>
                        </li>
                    
<?php               endif;

                endforeach;
?>                   
                    </ul>
                    <input type="hidden" name="theme" value="default" />
                <hr />
                </div> <!-- end themes section -->
                
                <div class="input-group cf">
                    <label class="inline" for="email">public email address:</label>
                    <input type="email" name="email" placeholder="admin@yoursite.com" />                 
                </div>
                <div class="input-group cf">
                    <label class="inline" for="site-path">site path:</label>
                    <a class="info pull-right" style="margin: 4px 0 0 15px;"></a>
                    <input type="text" name="site-path" value="<?= dirname($_SERVER['SCRIPT_NAME']); ?>" />            
                </div>
                <div class="input-group cf">
                    <label class="inline" for="timezone">your timezone:</label>
                    <select name="timezone">
                        <option data-offset="-11" value="Pacific/Midway">UTC-11:00 (Midway)</option>
                        <option data-offset="-10" value="Pacific/Honolulu">UTC-10:00 (Honolulu)</option>
                        <option data-offset="-9" value="America/Anchorage">UTC-9:00 (Anchorage)</option>
                        <option data-offset="-8" value="America/Los_Angeles">UTC-8:00 (Los Angeles)</option>
                        <option data-offset="-7" value="America/Denver">UTC-7:00 (Denver)</option>
                        <option data-offset="-6" value="America/Chicago">UTC-6:00 (Chicago)</option>
                        <option data-offset="-5" value="America/New_York">UTC-5:00 (New York)</option>
                        <option data-offset="-4.5" value="America/Caracas">UTC-4:30 (Caracas)</option>                       
                        <option data-offset="-4" value="America/Santiago">UTC-4:00 (Santiago de Chile)</option>
                        <option data-offset="-3.5" value="America/St_Johns">UTC-3:30 (Newfoundland)</option>
                        <option data-offset="-3" value="America/Sao_Paulo">UTC-3:00 (Rio de Janeiro)</option>
                        <option data-offset="-2" value="Atlantic/South_Georgia">UTC-2:00 (South Sandwich Islands)</option>
                        <option data-offset="-1" value="Atlantic/Azores">UTC-1:00 (Azores)</option>
                        <option data-offset="0" value="Europe/London">UTC (London)</option>
                        <option data-offset="1" value="Europe/Berlin">UTC+1:00 (Berlin)</option>
                        <option data-offset="2" value="Europe/Istanbul">UTC+2:00 (Istanbul)</option>
                        <option data-offset="3" value="Asia/Baghdad">UTC+3:00 (Baghdad)</option>
                        <option data-offset="4" value="Europe/Moscow">UTC+4:00 (Moscow)</option>
                        <option data-offset="5" value="Asia/Karachi">UTC+5:00 (Karachi)</option>
                        <option data-offset="5.5" value="Asia/Calcutta">UTC+5:30 (Mumbai)</option>
                        <option data-offset="6" value="Asia/Dhaka">UTC+6:00 (Dhaka)</option>
                        <option data-offset="7" value="Asia/Bangkok">UTC+7:00 (Bangkok)</option>
                        <option data-offset="8" value="Asia/Singapore">UTC+8:00 (Beijing)</option>
                        <option data-offset="9" value="Asia/Tokyo">UTC+9:00 (Tokyo)</option>
                        <option data-offset="9.5" value="Australia/Adelaide">UTC+9:30 (Adelaide)</option>
                        <option data-offset="10" value="Australia/Sydney">UTC+10:00 (Sydney)</option>
                        <option data-offset="11" value="Pacific/Guadalcanal">UTC+11:00 (Solomon Islands)</option>
                        <option data-offset="12" value="Pacific/Auckland">UTC+12:00 (Wellington)</option>
                    </select>
                    <script>
                        var now = new Date(),
                            jan1 = new Date(now.getFullYear(),0,1,0,0,0,0),
                            temp = jan1.toGMTString(),
                            jan2 = new Date(temp.substring(0,temp.lastIndexOf(" ")-1)),
                            offset = (jan1 - jan2) / (1000 * 60 * 60);
                            $('option[data-offset="' + offset + '"]').attr('selected','selected');
                    </script>              
                </div>
                
                <hr />
                
                <p><small>Finally, you will use this username and password to log into the admin area of Datoid</small></p>
                <div class="input-group cf">
                    <label class="inline" for="adminusername">admin username:</label>
                    <input name="adminusername" placeholder="admin" />
                </div>
                <div class="input-group cf">
                    <label class="inline" for="adminpassword">admin password:</label>
                    <input type="password" name="adminpassword" />
                </div>
                <div class="input-group cf">
                    <label class="inline" for="realname">real name:</label>
                    <input name="realname" placeholder="only used in the admin area" />
                </div>
                
            </fieldset>
            
            <button class="btn btn-success btn-center" type="submit">Install <span class="monospace">Datoid</span></button>                 
        
        </form>    
    </div>
    
<?php endif; ?>
    
<footer></footer>
    
</body>
</html>