<?php
class Player{
 
    // database connection and table name
    private $conn;
    private $table_name = "players";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $knownas;
    public $team_id;
    public $position_id;
    public $shirt_no;
    public $stats;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read players
    function read(){
     
        // select all query
        $query = "SELECT
                    p.ID, p.firstname, p.lastname, p.knownas, p.positionID, p.teamID, p.jersey, p.status, p.news,
                    IFNULL(s.mins, 0) AS mins,
                    IFNULL(s.tackles, 0) AS tackles,
                    IFNULL(s.passes, 0) AS passes,
                    IFNULL(s.goals, 0) AS goals,
                    IFNULL(s.assists, 0) AS assists,
                    IFNULL(s.cleansheets, 0) AS cleansheets,
                    IFNULL(s.pts, 0) AS pts
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN 
                        player_stats_totals s
                            ON p.ID = s.player_ID
                ORDER BY
                    p.lastname ASC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
    function read_one(){
     
        // select all query
        $query = "SELECT
                    p.ID, p.firstname, p.lastname, p.knownas, p.positionID, p.teamID, p.jersey, p.status, p.news,
                    IFNULL(s.mins, 0) AS mins,
                    IFNULL(s.tackles, 0) AS tackles,
                    IFNULL(s.passes, 0) AS passes,
                    IFNULL(s.goals, 0) AS goals,
                    IFNULL(s.assists, 0) AS assists,
                    IFNULL(s.cleansheets, 0) AS cleansheets,
                    IFNULL(s.pts, 0) AS pts
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN 
                        player_stats_totals s
                            ON p.ID = s.player_ID
                WHERE
                    p.ID = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind id of player to be selected
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
    function read_stats() {
        $query = "SELECT
                    *
                FROM
                    player_stats_detailed
                WHERE
                    player_ID = ? AND season_ID = '2018'
                ORDER BY
                    date DESC";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of player to be selected
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    function read_history() {
        $query = "SELECT 
                    *
                FROM 
                    `player_history` 
                WHERE 
                    player_ID = ?
                ORDER BY
                    season_ID DESC";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of player to be selected
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}