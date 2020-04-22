<?php 
require('../../../connect.php');
require('../../../header.php');
require('../../../emailDependents.php');
include('../../../functions/global.php');

$type = $_POST["type"];
$projectID=$_POST['projectID'];

if ($type=="add") {
	$memberUserID=$_POST['memberUserID'];
	
	addProjectMember($memberUserID,$projectID);
}
if ($type=="delete") {
	$memberUserID=$_POST['memberUserID'];
	
	deleteProjectMember($memberUserID,$projectID);
}
if ($type == "getUsernames") {
	$searchTerm = $_POST["typedUsername"];
	
	
	$foundUsernames = getUserTagsNotInProject($searchTerm,$projectID);
	
	////////////
	$results = ["foundUsernames" => $foundUsernames];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
}
?>