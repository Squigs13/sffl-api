<?php
class Team{
 
    // database connection and table name
    private $conn;
    private $table_name = "teams";
 
    // object properties
    public $id;
    public $name;
    public $points;
    public $squad;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read teams
    function read(){
     
        // select all query
        $query = "SELECT
                    t.ID, t.team_name, t.total_points,
                    GROUP_CONCAT(s.players_ID SEPARATOR ':') AS player_ID,
                    GROUP_CONCAT(p.firstname SEPARATOR ':') AS player_first,
                    GROUP_CONCAT(p.lastname SEPARATOR ':') AS player_last,
                    GROUP_CONCAT(p.knownas SEPARATOR ':') AS player_known,
                    GROUP_CONCAT(p.teamID SEPARATOR ':') AS player_club,
                    GROUP_CONCAT(p.positionID SEPARATOR ':') AS player_pos
                FROM
                    " . $this->table_name . " t
                    INNER JOIN
                        rosters s
                            ON s.current_teams_ID = t.ID
                    INNER JOIN
                        players p
                            ON s.players_ID = p.ID
                GROUP BY
                    ID";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
    function readOne(){
        
         $query = "SELECT
                    t.ID, t.team_name, t.total_points,
                    GROUP_CONCAT(s.players_ID SEPARATOR ':') AS player_ID,
                    GROUP_CONCAT(p.firstname SEPARATOR ':') AS player_first,
                    GROUP_CONCAT(p.lastname SEPARATOR ':') AS player_last,
                    GROUP_CONCAT(p.knownas SEPARATOR ':') AS player_known,
                    GROUP_CONCAT(p.teamID SEPARATOR ':') AS player_club,
                    GROUP_CONCAT(p.positionID SEPARATOR ':') AS player_pos
                FROM
                    " . $this->table_name . " t
                    INNER JOIN
                        rosters s
                            ON s.current_teams_ID = t.ID
                    INNER JOIN
                        players p
                            ON s.players_ID = p.ID
                WHERE
                    t.ID = ?
                LIMIT
                    0,1";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of team to be selected
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
        
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
        
        $temp_squad = explode(":",$player_ID);
        $temp_first = explode(":",$player_first);
        $temp_last = explode(":",$player_last);
        $temp_known = explode(":",$player_known);
        $temp_club = explode(":",$player_club);
        $temp_pos = explode(":",$player_pos);
        $temp_arr = array();
        foreach($temp_squad as $key => $val) {
            $temp_item = array(
                "id" => (int)$val,
                "first" => html_entity_decode($temp_first[$key]),
                "last" => html_entity_decode($temp_last[$key]),
                "known" => html_entity_decode($temp_known[$key]),
                "club" => $temp_club[$key],
                "position" => $temp_pos[$key]
            );
            array_push($temp_arr, $temp_item);
        }
        
        $this->name = $team_name;
        $this->points = $total_points;
        $this->squad = $temp_arr;
                    
    }
}