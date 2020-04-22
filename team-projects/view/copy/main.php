<?php 
require('../../../connect.php');
require('../../../header.php');
require('../../../emailDependents.php');
include('../../../functions/global.php');


$type = $_POST['type'];
$projectID = $_POST['projectID'];

//addToFavorites
if ($type == "saveCopy") {
	
	$copy = addslashes($_POST['copy']);
	
	updateProjectCopy($projectID,$copy);
}


?>