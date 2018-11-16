<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/fixture.php';
 
// instantiate database and fixture object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$fixture = new Fixture($db);

$fixture->season_id = isset($_GET['season']) ? $_GET['season'] : die();
 
// query fixtures
$stmt = $fixture->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
    
    $week_stmt = $fixture->getCurrentWeek();
    $current_week = $week_stmt->fetch(PDO::FETCH_ASSOC);
 
    // fixtures array
    $fixtures_arr=array();
    $fixtures_arr["fixtures"]=array();
    $fixtures_arr["current_week"] = (int)$current_week["week"];
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
        
        $home_team = substr($ID, -3);
        $away_team = substr($ID, -7, 3);
        $date = substr($game_date, 0, 10);
        $time = substr($game_date, 11);
 
        $fixture_item=array(
            "id" => $ID,
            "week" => (int)$week,
            "status" => $status,
            "date" => $date,
            "time" => $time,
            "home_team" => $home_team,
            "away_team" => $away_team,
            "home_score" => (int)$homescore,
            "away_score" => (int)$awayscore,
            "match_id" => $xml
        );
 
        array_push($fixtures_arr["fixtures"], $fixture_item);
    }
 
    echo json_encode($fixtures_arr);
}
 
else{
    echo json_encode(
        array("message" => "No fixtures found.")
    );
}
?>