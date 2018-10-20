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
        
        
 
        $team_item=array(
            "id" => (int)$ID,
            "name" => html_entity_decode($team_name),
            "pts" => $total_points,
            "priority" => (int)$priority
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