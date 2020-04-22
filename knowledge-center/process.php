<?php

include('../functions/global.php');

if(file_exists('../connect.php')) {
   require('../connect.php');
}
else if (file_exists('../../connect.php')) {
	require('../../connect.php');
}

if(file_exists('../header.php')) {
   require('../header.php');
}
else if (file_exists('../../header.php')) {
	require('../../header.php');
}


if (isset($_GET['cat'])) {
	$KCcategory = $_GET['cat'];
}

if (isset($_POST['type'])) {
	$type = $_POST['type'];
}



if (isset($KCcategory)) {
$getKCcategoryID = "SELECT * FROM `Knowledge Center Categories` WHERE `Category` = '$KCcategory'";
$getKCcategoryID_result = mysqli_query($connection, $getKCcategoryID) or die ("Query to get data from Team task failed: ".mysql_error());
while ($row = mysqli_fetch_array($getKCcategoryID_result)) {
$KCcategoryID = $row['KC CategoryID'];
}	
	
}
else {
	$getKCcategoryID = "SELECT * FROM `Knowledge Center Categories`";
$getKCcategoryID_result = mysqli_query($connection, $getKCcategoryID) or die ("Query to get data from Team task failed: ".mysql_error());

}

if ($type == "newPost") {
	$postCategoryID = $_POST['postCategoryID'];
	$postTitle = addslashes($_POST['postTitle']);
	$postTagArray =$_POST['postTags'];
	$postBody = mysqli_real_escape_string($connection, $_POST['postBody']);
	
	$query = "INSERT INTO `Knowledge Center`(`Post Title`,`userID`, `Post Description`, `KC CategoryID`, `Post Image`, `Last Updated By`) VALUES ('$postTitle','$userID','$postBody','$postCategoryID','','$userID')";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$postID = $connection->insert_id;
	
	if (isset($postTagArray)) {
		foreach($postTagArray as $tag) {
			$tag = addslashes($tag);
			$query2 = "INSERT INTO `Knowledge Center Tags`(`KC CategoryID`, `Tag`, `PostID`) VALUES ('$postCategoryID','$tag','$postID')";
			$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
		  
		} 
	}
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "added the <strong>$KCcategory</strong> post: <strong>$postTitle</strong> to the Knowledge Center.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `PostID`) VALUES ('$activity','Knowledge Center','$userID','$postID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	////////////
	$message = "Your post has been successfully added.";
	$response = ["message" => $message,
				"postID" => $postID];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if ($type == "updateTag") {
	$newTag = addslashes($_POST['newTag']);
	$postTagID = $_POST['tagID'];
	$postID = $_POST['postID'];
	$postCategoryID = $_POST['postCategoryID'];
	
	$getAllTags = "SELECT `KC TagID` FROM `Knowledge Center Tags` WHERE `PostID`='$postID'";
	$getAllTags_result = mysqli_query($connection, $getAllTags) or die(mysqli_error($connection));
	$tagCount = mysqli_num_rows($getAllTags_result);
	
	if ($newTag == "Remove Tag") {
		$query = "DELETE FROM `Knowledge Center Tags` WHERE `PostID` = '$postID'";
		$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	}
	
	if ($tagCount != 0) {
		$query = "UPDATE `Knowledge Center Tags` SET `Tag`='$newTag' WHERE `KC TagID` = '$postTagID'";
		$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	}
	else {
		$query2 = "INSERT INTO `Knowledge Center Tags`(`KC CategoryID`, `Tag`, `PostID`) VALUES ('$postCategoryID','$newTag','$postID')";
		$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	}
	
	
}

if ($type == "updatePost") {
	$newTitle = addslashes($_POST['postTitle']);
	$newBody =  mysqli_real_escape_string($connection, $_POST['postBody']);
	$postID = $_POST['postID'];
	$postCategoryID = $_POST['postCategoryID'];
	$postTagArray =$_POST['postTags'];
	
	$query2 = "DELETE FROM `Knowledge Center Tags` WHERE `PostID` = '$postID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	if (isset($postTagArray)) {
		foreach($postTagArray as $tag) {
			
			$tag = addslashes($tag);
			$query2 = "INSERT INTO `Knowledge Center Tags`(`KC CategoryID`, `Tag`, `PostID`) VALUES ('$postCategoryID','$tag','$postID')";
			$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
		  
		} 
	}
	
	$query = "UPDATE `Knowledge Center` SET `Post Title`='$newTitle',`Post Description`='$newBody',`Last Updated By`='$userID',`Last Updated`=now() WHERE `postID` = '$postID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "updated the <strong>$KCcategory</strong> post: <strong>$newTitle</strong> in the Knowledge Center.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `PostID`) VALUES ('$activity','Knowledge Center','$userID','$postID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	$response = ["message" => $message,
				"postID" => $postID];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
}

if ($type == "deletePost") {
	$postID = $_POST['postID'];
	$categoryID = $_POST['categoryID'];
	$query = "SELECT * FROM `Knowledge Center` WHERE `PostID` = '$postID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
	$postTitle = addSlashes($row['Post Title']);
	}
	
	//getting category info
	$query = "SELECT * FROM `Knowledge Center Categories` WHERE `KC CategoryID` = '$categoryID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
	$categoryName = $row['Category'];
	}
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "deleted the <strong>$categoryName</strong> post: <strong>$postTitle</strong> from the Knowledge Center.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `PostID`) VALUES ('$activity','Knowledge Center','$userID','$postID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	$query = "DELETE FROM `Knowledge Center` WHERE `PostID` = '$postID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$query2 = "DELETE FROM `Knowledge Center Tags` WHERE `PostID` = '$postID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$response = ["category" => $categoryName];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
}

if ($type == "getTags") {
	
	$postID = $_POST['postID'];
	$categoryID = $_POST['categoryID'];
	$searchTerm = addslashes($_POST['searchTerm']);

	$foundTags = getKCTagsInCatNotInPost($searchTerm,$postID,$categoryID);
	
	$results = ["foundTags" => $foundTags];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
}

if ($type == "getTagsCreate") {
	
	$categoryID = $_POST['categoryID'];
	$searchTerm = addslashes($_POST['searchTerm']);

	$foundTags = getKCTagsInCat($searchTerm,$categoryID);
	
	$results = ["foundTags" => $foundTags];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
}
?>