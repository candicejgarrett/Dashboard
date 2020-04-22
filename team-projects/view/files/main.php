<?php 
require('../../../connect.php');
require('../../../header.php');
require('../../../emailDependents.php');
include('../../../functions/global.php');

$type = $_POST['type'];
$projectID = $_POST['projectID'];
//add
if ($type == "upload") {
	
	if(isset($_FILES['file'])){
		$file = $_FILES['file'];
		addProjectFile($projectID, $file);	
	}
}
//delete
if ($type == "delete") {
	
	$file = $_POST['file'];
	$path = $_POST['path'];

	deleteProjectFile($projectID, $file, $path);
	
}


?>