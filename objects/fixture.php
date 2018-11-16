<?php
class Fixture{
 
    // database connection and table name
    private $conn;
    private $table_name = "league_schedules";
 
    // object properties
    public $id;
    public $season_id;
    public $week;
    public $status;
    public $home_score;
    public $away_score;
    public $date;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read players
    function read(){
     
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . " f
                WHERE
                    seasonID LIKE " . $this->season_id . "
                ORDER BY
                    f.game_date ASC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
    function getCurrentWeek() {
        $query = "SELECT
                    week
                FROM
                    " . $this->table_name . " f
                WHERE
                    seasonID LIKE " . $this->season_id . " AND status = 'Scheduled'
                ORDER BY
                    f.game_date ASC
                LIMIT 1";
                
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
}