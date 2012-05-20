<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

class Themer {
    
    public static function about($about_file)
    {
        // This will store the raw lines of about.txt
        $about_raw = array();
        
        // Read about.txt into $about_raw
        if($file = @fopen($about_file, 'r'))
        {
            while(($line = fgets($file)) !== false)
                $about_raw[] = $line;
            
            fclose($file);
        }
        else
        {
            throw new Exception('Could not read theme\'s about.txt file.');
        }
        
        // Parse $about_raw into $about
        $about = array();
        
        foreach($about_raw as $line)
        {
            $line = explode(':', $line);
            $line[0] = trim($line[0], '@');
            $line[1] = trim($line[1]);
            
            $about[$line[0]] = $line[1];
        }
        unset($about_raw);
        
        return $about;
    }

}