<?php 
require('../../../connect.php');
require('../../../header.php');
include('../../../functions/global.php');
require('../../../emailDependents.php');

$type = $_POST['type'];

if ($type == "add") {
	
	$title=$_POST['title'];	
$dueDate=date("Y-m-d H:i:s",strtotime($_POST['dueDate']));	
$categoryID = $_POST['categoryID'];	
$description = $_POST['description'];
$visibility = $_POST['visibility'];
$folderlink = $_POST['folderlink'];
$url = $_POST['url'];
$taskType = $_POST['taskType'];
$template = $_POST['template'];
$copy = "";
$ticketID = "";
	
	if ($template === "Blank") {
		$projectID = addBlankProject($title, $description, $dueDate, $template, $url, $categoryID, $folderlink, $visibility, $taskType, $copy, $ticketID);
	}
	else {
		$projectID = addTemplateProject($title, $description, $dueDate, $template, $url, $folderlink, $copy, $ticketID);
	}
	

//////////////
	
	$result = ["projectID" => $projectID];
	header('Content-Type: application/json'); 
	echo json_encode($result);	
	
}


//addToFavorites
if ($type == "addToFavorites") {
	
	$isFavorite = "SELECT COUNT(`ProjectID`) FROM `Team Projects Favorites` WHERE `userID` = '$userID' AND `ProjectID` = '$printProjectID'";
	$isFavorite_result = mysqli_query($connection, $isFavorite) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	$isFavoriteRow_count= $isFavorite_result->num_rows;
	$row = $isFavorite_result->fetch_assoc();
	$printableFavoriteCount =$row['COUNT(`ProjectID`)'];
	
	if ($printableFavoriteCount == 0) {
	$ProjectID = $_POST['ProjectID'];
	$addFav = "INSERT INTO `Team Projects Favorites`(`ProjectID`, `userID`) VALUES ('$ProjectID','$userID')";
	$addFav_result = mysqli_query($connection, $addFav) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	else {
		return false;
	}
	
	
}
//Remove from favorites
//addToFavorites
if ($type == "RemoveFromFavorites") {
	$ProjectID = $_POST['ProjectID'];
	$addFav = "DELETE FROM `Team Projects Favorites` WHERE `ProjectID`='$ProjectID' AND `userID`='$userID'";
	$addFav_result = mysqli_query($connection, $addFav) or die ("Query to get data from Team Project failed: ".mysql_error());
}
//EDIT LOAD
if ($type == "editLoad") {
	$editingProjectID=$_POST['editingProjectID1'];
	$projectID = $editingProjectID;
	
	//getting all 
	$getAll = "SELECT `ProjectID`, `Status`, `Title`, `Description`, `Team Projects Categories`.`ProjectCategoryID`, DATE_FORMAT(`Due Date`, '%Y-%m-%d\T%H:%i:%s'), `userID`, `Date Created`, `Visible`, `Project Folder Link`, `URL To Use`, `TicketID` FROM `Team Projects` JOIN `Team Projects Categories` ON `Team Projects Categories`.`ProjectCategoryID`=`Team Projects`.`Category` WHERE `ProjectID` = '$projectID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $Title = $row["Title"];
		$Description = $row["Description"];
		$DueDate = $row["DATE_FORMAT(`Due Date`, '%Y-%m-%d\T%H:%i:%s')"];
		$Category = $row["ProjectCategoryID"];
		$Visible = $row["Visible"];
		$projectFolder = $row["Project Folder Link"];
		$projectURL = $row["URL To Use"];
	}
	
////////////
	
	$results = ["projectTitleEdit" => $Title, "projectDescriptionEdit" => $Description, "projectDueDateEdit" => $DueDate, "projectCategoryEdit" => $Category, "projectVisibleEdit" => $Visible, "projectFolderEdit" => $projectFolder, "projectURLEdit" => $projectURL];
	header('Content-Type: application/json'); 
	echo json_encode($results);
}
//SAVE
if ($type == "save") {
	$projectID=$_POST['projectID'];
	$title=addslashes($_POST['title']);	
	$dueDate=date("Y-m-d H:i:s",strtotime($_POST['dueDate']));
	
	$categoryID = $_POST['categoryID'];	
	$description = addslashes($_POST['description']);	
	$visibility = $_POST['visibility'];	
	$folderLink = $_POST['folderLink'];	
	$url = $_POST['url'];	
	
	$updateproject = "UPDATE `Team Projects` SET `Title`='$title',`Description`='$description',`Due Date`='$dueDate',`Category`='$categoryID',`Visible`='$visibility',`Project Folder Link`='$folderLink',`URL To Use`='$url' WHERE `projectID` = '$projectID'";
	$updateproject_result = mysqli_query($connection, $updateproject) or die ("Query to get data from Team Project failed: ".mysql_error());

/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "updated the project&#39;s information.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
}
//ARCHIVE
if ($type == "archive") {
	$projectID=$_POST['projectID'];
	
	archiveProject($projectID);
}
//DELETE
if ($type == "delete") {
	$projectID=$_POST['projectID'];
	
	deleteProject($projectID);	
}

if ($type == "getNewCategories") {
	
	$groupID = $_POST['teamID'];
	
							$getCategories = "SELECT DISTINCT * FROM `Team Projects Categories` WHERE `GroupID` = '$groupID'";
							$getCategories_result = mysqli_query($connection, $getCategories) or die ("Query to get data from Team task failed: ".mysql_error());

							
							// Loop through the query results, outputing the options one by one
							while ($row = mysqli_fetch_array($getCategories_result)) {
								$categoryName = $row['Category'];
								$categoryID = $row['ProjectCategoryID'];
								$newCategories[] = "<option value='$categoryID'>$categoryName</option>";
							}

							////////////
	
	$results = ["newCategories" => $newCategories];
	header('Content-Type: application/json'); 
	echo json_encode($results);
}






?>