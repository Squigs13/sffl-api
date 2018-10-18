<?php
class Club{
 
    // database connection and table name
    private $conn;
    private $table_name = "clubs";
 
    // object properties
    public $id;
    public $name;
    public $short_name;
    public $abbr;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read clubs
    function read(){
     
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " c
                    ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
}