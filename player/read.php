<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/player.php';
 
// instantiate database and player object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$player = new Player($db);
 
// query players
$stmt = $player->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // players array
    $players_arr=array();
    $players_arr["players"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
        
        $player->id = $ID;
 
        $player_item=array(
            "id" => (int)$ID,
            "firstname" => html_entity_decode($firstname),
            "lastname" => html_entity_decode($lastname),
            "knownas" => html_entity_decode($knownas),
            "team_id" => $teamID,
            "position" => $positionID,
            "shirt_no" => $jersey,
            "status" => $status,
            "news" => $news,
            "mins" => (int)$mins,
            "passes" => (int)$passes,
            "tackles" => (int)$tackles,
            "goals" => (int)$goals,
            "assists" => (int)$assists,
            "cleansheets" => (int)$cleansheets,
            "pts" => (int)$pts
        );
 
        array_push($players_arr["players"], $player_item);
    }
 
    echo json_encode($players_arr);
}
 
else{
    echo json_encode(
        array("message" => "No players found.")
    );
}
?>