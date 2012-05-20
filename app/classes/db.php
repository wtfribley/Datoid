<?php defined("PRIVATE") or die("Permission Denied. Cannot Access Directly.");

/*
 *      This is Datoid's basic DB interaction class
 */

class DB {
    
    private static $dbh = null;
    
    public static function insert($table,$data = array()) {
        
        $sql = 'INSERT INTO ' . $table . ' (';
        
        $fields = array_keys($data);        
        foreach($fields as $field) {
            $sql.= $field . ', ';
        }
        $sql = rtrim($sql, ', ');
        
        $sql.= ') VALUES (';
        
        for($i=0;$i<count($data);$i++) {
            $sql.= "?,";
        }
        $sql = rtrim($sql, ',');
        
        $sql.= ');';
        
        $stmt = self::prep($sql);
        
        return $stmt->execute(array_values($data));       
    }
    
    public static function query($sql, $result = true) {
        if(is_null(self::$dbh))
            self::connect();
        
        if($result) {
            $results = array();
        
            foreach(self::$dbh->query($sql) as $row) {
                $results[] = $row;
            }

            return $results;
        }
        else {
            return self::$dbh->exec($sql);
        }
    }
    
    public static function prep($sql) {
        if(is_null(self::$dbh))
            self::connect();
        
        try {
            $stmt = self::$dbh->prepare($sql);
        }
        catch (PDOException $e) {
            Error::exception($e);
        }
        
        return $stmt;
    }
    
    private static function connect() {
        
        $dbname = Config::get('database.name');
        $host = Config::get('database.host');
        $user = Config::get('database.username');
        $pass = Config::get('database.password');
        
        $dsn = 'mysql:dbname='.$dbname.';host='.$host;
        
        try {
            self::$dbh = new PDO($dsn, $user, $pass);
        }
        catch (PDOException $e) {
            Error::exception($e);
        }
    }
    
}