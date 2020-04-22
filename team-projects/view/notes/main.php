<?php 
require('../../../connect.php');
require('../../../header.php');
require('../../../emailDependents.php');
include('../../../functions/global.php');

$type = $_POST['type'];
$projectID = $_POST['projectID'];
//add
if ($type == "add") {
	
	$message=$_POST['message'];	
	$mentionUserIDs = json_decode($_POST['mentionUserIDs'], true);
	
	addProjectNote($projectID, $message, $mentionUserIDs);	

}
//delete
if ($type == "delete") {
	$noteID=$_POST['noteID'];

	deleteProjectNote($noteID);
	
}

if ($type == "getUsernames") {
	$searchTerm = $_POST["typedUsername"];
	
	
	$foundUsernames = getUserTags($searchTerm);
	
	////////////
	$results = ["foundUsernames" => $foundUsernames];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
}


?>