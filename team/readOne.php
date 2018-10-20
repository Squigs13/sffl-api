<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/team.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare team object
$team = new Team($db);
 
// set ID property of team to be selected
$team->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of team to be selected
$stmt = $team->read_one();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

extract($row);

$lineup_stmt = $team->read_lineup();
$lineup_num = $lineup_stmt->rowCount();
$lineup_arr=array();

if($lineup_num>0){
    
    while ($lineup_row = $lineup_stmt->fetch(PDO::FETCH_ASSOC)){
 
        $lineup_item=array(
            "player_id" => (int)$lineup_row['players_ID'],
            "position" => $lineup_row['positions_ID']
        );
 
        array_push($lineup_arr, $lineup_item);
    }
}
 
// create array
$team_arr = array(
    "id" =>  (int)$ID,
    "name" => html_entity_decode($team_name),
    "pts" => $total_points,
    "priority" => (int)$priority,
    "lineup" => $lineup_arr
);
 
// make it json format
print_r(json_encode($team_arr));
?>