<?php
class Match {
    
    // database connection and table name
    private $conn;
    private $table_name = "fixture_lookup";
    
    public $id;
    public $fixture_id;
    
    public function __construct($db){
        $this->conn = $db;
    }
    
    function read_one() {
        $query = "SELECT ID
                FROM
                " . $this->table_name . " m
                WHERE
                    m.Fixture_ID = ?";
                    
        $stmt = $this->conn->prepare($query);
        
        // bind id of player to be selected
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
                
    }
}