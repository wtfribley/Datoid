<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Datoid creation class
 */

class Datoid {
    
    private $sql = 'CREATE TABLE IF NOT EXISTS [[name]] ([[fields]]);',
            $name;
    
    public function __construct($options) {
        if(is_array($options) && !empty($options)) {
            
            // name
            $this->name($options['name']);
            
            // add fields
            if(isset($options['fields']))
                $this->add_fields($options['fields']);
            
            // create datoid
            $this->create_datoid();
            
        }
        else if (is_string($options)) {
            // name
            $this->name($options['name']);            
        }
        else {
            Error::user('No name or data passed to the new Datoid.');
        }
    }
    
    private function name($name) {
        if(isset($name) && $name) {
            $this->name = $name;
            $this->sql = str_replace('[[name]]', $name, $this->sql);            
        }
        else {
            Error::user('A new Datoid must have a name.');
        }       
    }
    
    public function add_fields($fields = array()) {
        if(!empty($fields)) {
            
            $table_fields = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ';
            
            foreach($fields as $field => $type) {
                
                $table_fields.= $field . ' ' . strtoupper($type) . ', ';
                
            }
                        
            $this->sql = str_replace('[[fields]]',rtrim($table_fields,', '),$this->sql);
        }
    }
    
    public function create_datoid() {
        DB::query($this->sql, false);
    }
    
}