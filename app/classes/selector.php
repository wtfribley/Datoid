<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is the Datoid selection class
 */

class Selector {
    
    public $data;
    
    private $sql = "SELECT [[returnfields]] FROM [[datoid]] [[where]] [[order]] [[limit]]",
            $datoid,
            $wherevalue,
            $returnsingle = false;
    
    public function __construct($options) {
        if(is_array($options) && !empty($options)) {
            
            // table
            if(isset($options['datoid'])) {
                $this->datoid($options['datoid']);
            }
            
            // where
            if(isset($options['wherefield']) && isset($options['wherevalue'])) {
                $this->where($options['wherefield'],$options['wherevalue']);
            }
            
            // order
            if(isset($options['order'])) {
                $order = explode(' ', $options['order']);
                $this->order($order[0], $order[1]);
            }
            
            // limit
            if(isset($options['limit'])) {
                $this->limit($options['limit']);
            }
            
            // return fields
            if(isset($options['returnfields'])) {
                $this->returnfields($options['returnfields']);
            }
            
            // run selection
            $this->select();
            
        }
        else if (is_string($options)) {
            // table
            $this->datoid($options);            
        }
        else {
            Error::user('No name or data passed to the new Datoid.');
        }
    }
    
    private function datoid($datoid) {
        if(isset($datoid) && $datoid) {
            $this->datoid = $datoid;
            $this->sql = str_replace('[[datoid]]', $datoid, $this->sql);            
        }
        else {
            Error::user('A Datoid must be given to the Selector');
        }
        
    }
    
    public function where($field,$value) {
        $this->wherevalue = $value;
        $sql = "WHERE " . $field . " = ?";
        $this->sql = str_replace('[[where]]', $sql, $this->sql);        
    }
    
    public function order($by,$sort = 'DESC') {
        $sort = strtoupper($sort);
        
        $sql = "ORDER BY " . $by . " " . $sort;
        $this->sql = str_replace('[[order]]', $sql, $this->sql);
    }
    
    public function limit($num) {
        $sql = "LIMIT " . $num;
        $this->sql = str_replace('[[limit]]', $sql, $this->sql);
    }
    
    public function returnfields($fields) {
        
        $sql = "";
        
        if(is_array($fields)) {          
            foreach($fields as $field) {
                $sql.= $this->datoid . "." . $field . ",";
            }
            $sql = rtrim($sql,',');
        }
        else {
            $this->returnsingle = true;
            $sql = $this->datoid . "." . $fields;
        }
        
        $this->sql = str_replace('[[returnfields]]', $sql, $this->sql);
    }
    
    public function select() {
        
        $this->sql = str_replace('[[returnfields]]', $this->datoid.'.*', $this->sql);
        $this->sql = str_replace(array('[[where]]','[[order]]','[[limit]]'), '', $this->sql);
        
        $stmt = DB::prep($this->sql);
        $stmt->execute(array($this->wherevalue));
        
        if($this->returnsingle) {
            $results = $stmt->fetchAll(PDO::FETCH_NUM);
            
            foreach($results as $key => $value) {
                if(is_array($value) && count($value) === 1)
                    $results[$key] = $value[0];
            }
        }
        else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        if (empty($results)) {
            $results = false;
        }       
        elseif (count($results) === 1) {
            $results = $results[0];
        }
                      
        $this->data = $results;
    }
    
    public function data($field = null) {
        if(is_null($field))       
            return $this->data;
        elseif(array_key_exists($field, $this->data)) {
            
            // unserialize fails on empty arrays - help it out a bit
            if($this->data[$field] == 'a:0:{}')
                $this->data[$field] = array();
            
            // unserialize data if need be
            return (is_string($this->data[$field]) && @unserialize($this->data[$field])) ? 
                unserialize($this->data[$field]) : $this->data[$field];
        }
        else
            return false;
    }
    
}