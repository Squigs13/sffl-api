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
$team->readOne();
 
// create array
$team_arr = array(
    "id" =>  $team->id,
    "name" => $team->name,
    "points" => $team->points,
    "squad" => $team->squad
);
 
// make it json format
print_r(json_encode($team_arr));
?>