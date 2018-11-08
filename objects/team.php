<?php
class Team{
 
    // database connection and table name
    private $conn;
    private $table_name = "teams";
 
    // object properties
    public $id;
    public $name;
    public $points;
    public $priority;
    public $lineup_week;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read teams
    function read(){
     
        // select all query
        $query = "SELECT
                    t.ID, t.team_name, t.total_points, t.priority
                FROM
                    " . $this->table_name . " t
                    ";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
    function read_one(){
        
         $query = "SELECT
                    t.ID, t.team_name, t.total_points, t.priority
                FROM
                    " . $this->table_name . " t
                WHERE
                    t.ID = ?";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of team to be selected
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
        
        return $stmt;
                    
    }
    
    function read_lineup() {
        $query = "SELECT
                    players_ID, positions_ID
                FROM
                    starting_lineup_actuals
                WHERE
                    teams_ID = ? AND weeks_ID = ?
                ORDER BY
                    positions_ID ASC";
                    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of team to be selected
        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->lineup_week);
     
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}