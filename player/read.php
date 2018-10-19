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
        
        $stat_stmt = $player->read_stats();
        $stat_num = $stat_stmt->rowCount();
        $stats_arr=array();
        
        if($stat_num>0){
            
            while ($stat_row = $stat_stmt->fetch(PDO::FETCH_ASSOC)){
         
                $stats_item=array(
                    "match_id" => $stat_row['match_ID'],
                    "club_id" => $stat_row['club_ID'],
                    "opponent_id" => $stat_row['opponent_ID'],
                    "score" => $stat_row['score'],
                    "mins" => (int)$stat_row['mins'],
                    "passes" => (int)$stat_row['passes'],
                    "tackles" => (int)$stat_row['tackles'],
                    "goals" => (int)$stat_row['goals'],
                    "assists" => (int)$stat_row['assists'],
                    "cleansheets" => (int)$stat_row['cleansheets'],
                    "pts" => (int)$stat_row['pts'],
                    "date" => $stat_row['date']
                );
         
                array_push($stats_arr, $stats_item);
                
            }
        }
        
        $hist_stmt = $player->read_history();
        $hist_num = $hist_stmt->rowCount();
        $hist_arr = array();
        
        if ($hist_num > 0) {
            
            while ($hist_row = $hist_stmt->fetch(PDO::FETCH_ASSOC)) {
                
                $hist_item = array(
                    "club_id" => $hist_row['club_ID'],
                    "season_id" => $hist_row['season_ID'],
                    "mins" => (int)$hist_row['mins'],
                    "passes" => (int)$hist_row['passes'],
                    "tackles" => (int)$hist_row['tackles'],
                    "goals" => (int)$hist_row['goals'],
                    "assists" => (int)$hist_row['assists'],
                    "cleansheets" => (int)$hist_row['cleansheets'],
                    "pts" => (int)$hist_row['pts']  
                );
                
                array_push($hist_arr, $hist_item);
            }
        }
 
        $player_item=array(
            "id" => $ID,
            "firstname" => html_entity_decode($firstname),
            "lastname" => html_entity_decode($lastname),
            "knownas" => html_entity_decode($knownas),
            "team_id" => $teamID,
            "position" => $positionID,
            "shirt_no" => $jersey,
            "status" => $status,
            "news" => $news,
            "mins" => $mins,
            "passes" => $passes,
            "tackles" => $tackles,
            "goals" => $goals,
            "assists" => $assists,
            "cleansheets" => $cleansheets,
            "pts" => $pts,
            "stats" => $stats_arr,
            "history" => $hist_arr
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