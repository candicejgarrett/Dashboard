<?php

include_once('../../header.php');
 require('../../connect.php');

	$title = addslashes($_POST['title']);
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$inputCategory = $_POST['Category'];
	$Description = addslashes($_POST['Description']);
	$allDay = $_POST['allDay'];
	$addEventRepeatFrequency = $_POST['addEventRepeatFrequency'];
	$addEventRepeatFrequencyWeek = $_POST['addEventRepeatFrequencyWeek'];
	$addEventRepeatTimesNumber = $_POST['addEventRepeatTimesNumber'];
	$Category = $inputCategory;
	
	
	$insert = mysqli_query($connection,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`) VALUES('$title','$startdate','$enddate','$Category','$userID','$Description','$allDay')");
	
	$eventID = mysqli_insert_id($connection);
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path = 'uploads/'.$eventID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	
	/////////// INSERTING NOTIFICATION ///////////
	
	//Getting project members
	$getGroupMembers = "SELECT `SubscriptionID`, `userID`, `Calendar Categories`.`Category`,`Notification Subscription`.`CalendarCategoryID` FROM `Notification Subscription` JOIN `Calendar Categories` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Notification Subscription`.`CalendarCategoryID` = '$Category'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];	
			$categoryName =$row["Category"];	
		}
	
	foreach ($groupMembers as $name2) {
		$notification = "<a href=/dashboard/content-calendar/?eventID=$eventID>The <strong>$categoryName</strong> event: <strong>$title&nbsp;</strong>has been added to the Content Calendar.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name2','$eventID')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "added the <strong>$categoryName</strong> event: <strong>$title</strong> to the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	
	
	
	////////////
	$results = ["eventID" => $eventID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
	




?>