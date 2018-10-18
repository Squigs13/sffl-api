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

// set ID property of player to be selected
$player->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// query players
$stmt = $player->read_player_stats();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // players array
    $stats_arr=array();
    $stats_arr["stats"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
 
        $stats_item=array(
            "match_id" => $match_ID,
            "club_id" => $club_ID,
            "opponent_id" => $opponent_ID,
            "score" => $score,
            "mins" => (int)$mins,
            "passes" => (int)$passes,
            "tackles" => (int)$tackles,
            "goals" => (int)$goals,
            "assists" => (int)$assists,
            "cleansheets" => (int)$cleansheets,
            "pts" => (int)$pts,
            "date" => $date
        );
 
        array_push($stats_arr["stats"], $stats_item);
    }
 
    echo json_encode($stats_arr);
}
 
else{
    echo json_encode(
        array("message" => "No stats found.")
    );
}
?>