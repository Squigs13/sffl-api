<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/club.php';
 
// instantiate database and club object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$club = new Club($db);
 
// query club
$stmt = $club->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // club array
    $club_arr=array();
    $club_arr["clubs"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
 
        $club_item=array(
            "id" => (int)$ID,
            "name" => html_entity_decode($Name),
            "short_name" => $Short_Name,
            "abbr" => $Club_Abbr
        );
 
        array_push($club_arr["clubs"], $club_item);
    }
 
    echo json_encode($club_arr);
}
 
else{
    echo json_encode(
        array("message" => "No clubs found.")
    );
}
?>