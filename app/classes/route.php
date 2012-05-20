<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Front-End Controller for DATOID
 */

class Route {
    
    private $selection = array();
    
    public function __construct($url)
    {
        // Assign the datoid
        $this->selection['datoid'] = $url['datoid'];
                
        // Separate extra options from the main locator
        $options = array();
        if(count($url['locator']) > 1)
            $options = array_splice($url['locator'],1);
        // parse the order if it's there
        if(array_key_exists('order', $options))
            $options['order'] = preg_replace('/[,-:]/', ' ', $options['order']);
        
        // Get our Items per Page value
        //$sel = new Selector('metadata');        
        $this->selection['limit'] = 10; //$sel->metadata('perpage');
        
        /*
         *      Assign the main locator
         */
        if(is_array($url['locator']) && !empty($url['locator']))
        {
            $wherefield = key($url['locator']);
            $wherevalue = current($url['locator']);

            // Dealing with pagination? Set the offset.
            if($wherefield == 'page')
                $this->selection['offset'] = $this->selection['limit'] * ($wherevalue - 1);
            
            // Otherwise, assign the "where" parameters
            else
            {
                $this->selection['wherefield'] = $wherefield;
                $this->selection['wherevalue'] = $wherevalue;
            }
                
            // Change the limit to one if we expect only one result.
            if($wherefield == 'slug' || $wherefield == 'id')
                $this->selection['limit'] = 1;
        }              
                
        // Merge the extra options back into the selection
        $this->selection = array_merge($this->selection,$options);      
        
/************************************
* 
*       Handle some Special Cases, otherwise run normally
* 
*/
        
        switch($this->selection['datoid']) {
            
            // Admin Page
            case "admin":
                (isset($this->selection['wherefield']) && $this->selection['wherefield'] == 'first' && $this->selection['wherevalue'] == 'true') ?
                    $first = true : $first = false;
                if(file_exists(PATH . 'app/admin/admin.php')) {
                    require PATH . 'app/admin/admin.php';
                    return;
                }
                else
                    Error::user('The admin directory is missing.');
            break;
        
            // Install Page
            case "install":
                (isset($this->selection['wherevalue'])) ? $path = $this->selection['wherevalue'] : $path = 'install.php';
                if(file_exists(PATH . 'app/install/' . $path)) {
                    require PATH . 'app/install/' . $path;
                    exit();
                }
                else
                    Response::redirect('home');
            break;
            
            // AJAX
            case 'ajax':
                if(file_exists(PATH . 'app/ajax/' . $this->selection['wherefield'] . '/' . $this->selection['wherevalue'])) {
                    $json = json_decode(Input::post('json'));
                    require PATH . 'app/ajax/' . $this->selection['wherefield'] . '/' . $this->selection['wherevalue'];
                    return;
                }
                else
                    Error::user('The ajax file you requested is missing.');       
            break;
            
            // Normal operation
            default:
                $this->go();
            break;   
        }        
    }
    
    public function go($selection = null)
    {
        if(!is_null($selection))
            $this->selection = array_merge($this->selection, $selection);
        
        // THESE ARE THE DATOIDS YOU'RE LOOKING FOR
        //$results = new Selector($this->selection);
        
        var_dump($this->selection);
    }

}