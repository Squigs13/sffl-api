<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/team.php';
 
// instantiate database and team object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$team = new Team($db);
 
// query team
$stmt = $team->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // team array
    $team_arr=array();
    $team_arr["teams"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

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
 
        $team_item=array(
            "id" => (int)$ID,
            "teamname" => html_entity_decode($team_name),
            "pts" => $total_points,
            "squad" => $temp_arr
        );
 
        array_push($team_arr["teams"], $team_item);
    }
 
    echo json_encode($team_arr);
}
 
else{
    echo json_encode(
        array("message" => "No teams found.")
    );
}
?>