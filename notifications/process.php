<?php

require('../connect.php');
require('../header.php');

//initializing variables
$printAllNotifications =null;
$printProjectNotifications =null;
$printTaskNotifications = null;
$printReviewNotifications =null;
$printCalendarNotifications = null;
$printKCNotifications = null;
$printRequestNotifications = null;
$final = null;
$notificationCount = 0;
$projectCount = 0;
$reviewCount = 0;
$eventCount = 0;
$requestCount = 0;

$type = $_POST["type"];

if ($type == "getAll") {

	$query = "SELECT `NotificationID`, `Notification`, `Type`, `Seen`, `Timestamp`, `userID`, `ProjectID`, `EventID` FROM `Notifications` WHERE `userID`='$userID' ORDER BY `Timestamp` DESC LIMIT 100";
$query_result = mysqli_query($connection, $query) or die ("getNotifications to get data from Team Project failed: ".mysql_error());
	
	while($row = $query_result->fetch_assoc()) {
		$NotificationID = $row["NotificationID"];
			$notificationText = $row["Notification"];
			$seen = $row["Seen"];
			$type = $row["Type"];
			$Timestamp = $row["Timestamp"];
	
		if ($type == "Project") {
			$icon = '<i class="fa fa-users" aria-hidden="true"></i>';
		}
		if ($type == "Event") {
			$icon = '<i class="fa fa-calendar" aria-hidden="true"></i>';
		}
		else if ($type == "Task") {
			$icon = '<i class="fa fa-tasks" aria-hidden="true"></i>';
		}
		else if ($type == "Note") {
			$icon = '<i class="fa fa-commenting" aria-hidden="true"></i>';
		}
		else if ($type == "Membership" || $type == "Users") {
			$icon = '<i class="fa fa-user" aria-hidden="true"></i>';
		}
		else if ($type == "Ticket" || $type == "LHN" || $type == "MegaNav") {
			$icon = '<i class="fa fa-ticket" aria-hidden="true"></i>';
		}
		else if (strpos($type, 'Review') !== false) {
			$icon = '<i class="fa fa-comments-o" aria-hidden="true"></i>';
		}
		else if (strpos($type, 'Copy') !== false) {
			$icon = '<i class="fa fa-commenting-o" aria-hidden="true"></i>';
		}
		else if (strpos($type, 'File') !== false) {
			$icon = '<i class="fa fa-file" aria-hidden="true"></i>';
		}
		
		//get days,hours,mins
		
		$getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
		$getSeperatedDate_result = mysqli_query($connection, $getSeperatedDate) or die ("Query to get data from Team Project failed: ".mysql_error());
		while ($row = mysqli_fetch_array($getSeperatedDate_result)) {
				$day = $row["DAY(`Timestamp`)"];
				$hour = $row["HOUR(`Timestamp`)"];
				$minute = $row["MINUTE(`Timestamp`)"];
				$second = $row["SECOND(`Timestamp`)"];
			}
		$currentDay = date("d");
		$currentHour = date("g");
		$currentMinute = date("i");
		$currentSecond = date("s");
		$currentAMPM = date("a");
		
		
		if ($hour > 12) {
			$hour = $hour -12;
		}
		
		$finalDay =$currentDay-$day;
		$finalHour =$currentHour-$hour;
		$finalMinute =$currentMinute-$minute;
		$finalSecond = $currentSecond-$second;
		
		if ($finalDay > 1) {
			
			$final = $finalDay." days ago.";
			
		} 
		else if ($finalDay == 0) {
			
			if ($finalHour ==0) {
				if ($finalMinute == 0 || $finalMinute == 1) {
				$final = "Just now";
				}
				else if ($finalMinute > 1 && $finalMinute < 60) {
				$final = $finalMinute." minutes ago.";
				}
				else{
				$final = "1 hour ago.";
					
				}
			}
			
			else if ($finalHour == 1) {
				$final = $finalHour." hour ago.";
				
			}
			
			else if ($finalHour > 1 && $finalHour < 12) {
				
				$final = $finalHour." hours ago.";
			}
			
			
		}
		
		else if ($finalDay == 1) {
			$final = $finalDay." day ago.";
		}
		
	$printAllNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'>$icon</div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";
		
	}
	
	
		$query2 = "SELECT `NotificationID`, `Notification`, `Type`, `Seen`, `Timestamp`, `userID`, `ProjectID`, `EventID` FROM `Notifications` WHERE `userID`='$userID' AND (`Type` = 'Project' OR `Type` = 'Membership' OR `Type` = 'Note' OR `Type` = 'File' OR `Type` = 'Copy') ORDER BY `Timestamp` DESC LIMIT 100";
$query2_result = mysqli_query($connection, $query2) or die ("getNotifications to get data from Team Project failed: ".mysql_error());
	
	while($row = $query2_result->fetch_assoc()) {
		$NotificationID = $row["NotificationID"];
			$notificationText = $row["Notification"];
			$seen = $row["Seen"];
			$type = $row["Type"];
			$Timestamp = $row["Timestamp"];
	
		if ($type == "Project") {
			$icon = '<i class="fa fa-users" aria-hidden="true"></i>';
		}
		if ($type == "Event") {
			$icon = '<i class="fa fa-calendar" aria-hidden="true"></i>';
		}
		else if ($type == "Task") {
			$icon = '<i class="fa fa-tasks" aria-hidden="true"></i>';
		}
		else if ($type == "Note") {
			$icon = '<i class="fa fa-commenting" aria-hidden="true"></i>';
		}
		else if ($type == "Membership" || $type == "Users") {
			$icon = '<i class="fa fa-user" aria-hidden="true"></i>';
		}
		else if ($type == "Ticket" || $type == "Users") {
			$icon = '<i class="fa fa-ticket" aria-hidden="true"></i>';
		}
		else if (strpos($type, 'Review') !== false) {
			$icon = '<i class="fa fa-comments-o" aria-hidden="true"></i>';
		}
		else if (strpos($type, 'Copy') !== false) {
			$icon = '<i class="fa fa-commenting-o" aria-hidden="true"></i>';
		}
		else if (strpos($type, 'File') !== false) {
			$icon = '<i class="fa fa-file" aria-hidden="true"></i>';
		}
		
		//get days,hours,mins
		
		$getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
		$getSeperatedDate_result = mysqli_query($connection, $getSeperatedDate) or die ("Query to get data from Team Project failed: ".mysql_error());
		while ($row = mysqli_fetch_array($getSeperatedDate_result)) {
				$day = $row["DAY(`Timestamp`)"];
				$hour = $row["HOUR(`Timestamp`)"];
				$minute = $row["MINUTE(`Timestamp`)"];
				$second = $row["SECOND(`Timestamp`)"];
			}
		$currentDay = date("d");
		$currentHour = date("g");
		$currentMinute = date("i");
		$currentSecond = date("s");
		$currentAMPM = date("a");
		
		
		if ($hour > 12) {
			$hour = $hour -12;
		}
		
		$finalDay =$currentDay-$day;
		$finalHour =$currentHour-$hour;
		$finalMinute =$currentMinute-$minute;
		$finalSecond = $currentSecond-$second;
		
		if ($finalDay > 1) {
			
			$final = $finalDay." days ago.";
			
		} 
		else if ($finalDay == 0) {
			
			if ($finalHour ==0) {
				if ($finalMinute == 0 || $finalMinute == 1) {
				$final = "Just now";
				}
				else if ($finalMinute > 1 && $finalMinute < 60) {
				$final = $finalMinute." minutes ago.";
				}
				else{
				$final = "1 hour ago.";
					
				}
			}
			
			else if ($finalHour == 1) {
				$final = $finalHour." hour ago.";
				
			}
			
			else if ($finalHour > 1 && $finalHour < 12) {
				
				$final = $finalHour." hours ago.";
			}
			
			
		}
		
		else if ($finalDay == 1) {
			$final = $finalDay." day ago.";
		}
		
	
	$printProjectNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'>$icon</div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";
		
	}
	
	
	
	$query3 = "SELECT `NotificationID`, `Notification`, `Type`, `Seen`, `Timestamp`, `userID`, `ProjectID`, `EventID` FROM `Notifications` WHERE `userID`='$userID' AND `Type` = 'Task' ORDER BY `Timestamp` DESC LIMIT 100";
$query3_result = mysqli_query($connection, $query3) or die ("getNotifications to get data from Team Project failed: ".mysql_error());
	
	while($row = $query3_result->fetch_assoc()) {
		$NotificationID = $row["NotificationID"];
			$notificationText = $row["Notification"];
			$seen = $row["Seen"];
			$type = $row["Type"];
			$Timestamp = $row["Timestamp"];
	
		//get days,hours,mins
		
		$getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
		$getSeperatedDate_result = mysqli_query($connection, $getSeperatedDate) or die ("Query to get data from Team Project failed: ".mysql_error());
		while ($row = mysqli_fetch_array($getSeperatedDate_result)) {
				$day = $row["DAY(`Timestamp`)"];
				$hour = $row["HOUR(`Timestamp`)"];
				$minute = $row["MINUTE(`Timestamp`)"];
				$second = $row["SECOND(`Timestamp`)"];
			}
		$currentDay = date("d");
		$currentHour = date("g");
		$currentMinute = date("i");
		$currentSecond = date("s");
		$currentAMPM = date("a");
		
		
		if ($hour > 12) {
			$hour = $hour -12;
		}
		
		$finalDay =$currentDay-$day;
		$finalHour =$currentHour-$hour;
		$finalMinute =$currentMinute-$minute;
		$finalSecond = $currentSecond-$second;
		
		if ($finalDay > 1) {
			
			$final = $finalDay." days ago.";
			
		} 
		else if ($finalDay == 0) {
			
			if ($finalHour ==0) {
				if ($finalMinute == 0 || $finalMinute == 1) {
				$final = "Just now";
				}
				else if ($finalMinute > 1 && $finalMinute < 60) {
				$final = $finalMinute." minutes ago.";
				}
				else{
				$final = "1 hour ago.";
					
				}
			}
			
			else if ($finalHour == 1) {
				$final = $finalHour." hour ago.";
				
			}
			
			else if ($finalHour > 1 && $finalHour < 12) {
				
				$final = $finalHour." hours ago.";
			}
			
			
		}
		
		else if ($finalDay == 1) {
			$final = $finalDay." day ago.";
		}
		
	
	$printTaskNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'><i class='fa fa-tasks' aria-hidden='true'></i></div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";
		
	}
	//REVIEW
	$query4 = "SELECT `NotificationID`, `Notification`, `Type`, `Seen`, `Timestamp`, `userID`, `ProjectID`, `EventID` FROM `Notifications` WHERE `userID`='$userID' AND `Type` = 'Review' ORDER BY `Timestamp` DESC LIMIT 100";
$query4_result = mysqli_query($connection, $query4) or die ("getNotifications to get data from Team Project failed: ".mysql_error());
	
	while($row = $query4_result->fetch_assoc()) {
		$NotificationID = $row["NotificationID"];
			$notificationText = $row["Notification"];
			$seen = $row["Seen"];
			$type = $row["Type"];
			$Timestamp = $row["Timestamp"];
	
		//get days,hours,mins
		
		$getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
		$getSeperatedDate_result = mysqli_query($connection, $getSeperatedDate) or die ("Query to get data from Team Project failed: ".mysql_error());
		while ($row = mysqli_fetch_array($getSeperatedDate_result)) {
				$day = $row["DAY(`Timestamp`)"];
				$hour = $row["HOUR(`Timestamp`)"];
				$minute = $row["MINUTE(`Timestamp`)"];
				$second = $row["SECOND(`Timestamp`)"];
			}
		$currentDay = date("d");
		$currentHour = date("g");
		$currentMinute = date("i");
		$currentSecond = date("s");
		$currentAMPM = date("a");
		
		
		if ($hour > 12) {
			$hour = $hour -12;
		}
		
		$finalDay =$currentDay-$day;
		$finalHour =$currentHour-$hour;
		$finalMinute =$currentMinute-$minute;
		$finalSecond = $currentSecond-$second;
		
		if ($finalDay > 1) {
			
			$final = $finalDay." days ago.";
			
		} 
		else if ($finalDay == 0) {
			
			if ($finalHour ==0) {
				if ($finalMinute == 0 || $finalMinute == 1) {
				$final = "Just now";
				}
				else if ($finalMinute > 1 && $finalMinute < 60) {
				$final = $finalMinute." minutes ago.";
				}
				else{
				$final = "1 hour ago.";
					
				}
			}
			
			else if ($finalHour == 1) {
				$final = $finalHour." hour ago.";
				
			}
			
			else if ($finalHour > 1 && $finalHour < 12) {
				
				$final = $finalHour." hours ago.";
			}
			
			
		}
		
		else if ($finalDay == 1) {
			$final = $finalDay." day ago.";
		}
	
	
	$printReviewNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'><i class='fa fa-comments-o' aria-hidden='true'></i></div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";
		
	}
	
	//CALENDAR
	$query4 = "SELECT `NotificationID`, `Notification`, `Type`, `Seen`, `Timestamp`, `userID`, `ProjectID`, `EventID` FROM `Notifications` WHERE `userID`='$userID' AND `Type` = 'Event' ORDER BY `Timestamp` DESC LIMIT 100";
$query4_result = mysqli_query($connection, $query4) or die ("getNotifications to get data from Team Project failed: ".mysql_error());
	
	while($row = $query4_result->fetch_assoc()) {
		$NotificationID = $row["NotificationID"];
			$notificationText = $row["Notification"];
			$seen = $row["Seen"];
			$type = $row["Type"];
			$Timestamp = $row["Timestamp"];
	
		//get days,hours,mins
		
		$getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
		$getSeperatedDate_result = mysqli_query($connection, $getSeperatedDate) or die ("Query to get data from Team Project failed: ".mysql_error());
		while ($row = mysqli_fetch_array($getSeperatedDate_result)) {
				$day = $row["DAY(`Timestamp`)"];
				$hour = $row["HOUR(`Timestamp`)"];
				$minute = $row["MINUTE(`Timestamp`)"];
				$second = $row["SECOND(`Timestamp`)"];
			}
		$currentDay = date("d");
		$currentHour = date("g");
		$currentMinute = date("i");
		$currentSecond = date("s");
		$currentAMPM = date("a");
		
		
		if ($hour > 12) {
			$hour = $hour -12;
		}
		
		$finalDay =$currentDay-$day;
		$finalHour =$currentHour-$hour;
		$finalMinute =$currentMinute-$minute;
		$finalSecond = $currentSecond-$second;
		
		if ($finalDay > 1) {
			
			$final = $finalDay." days ago.";
			
		} 
		else if ($finalDay == 0) {
			
			if ($finalHour ==0) {
				if ($finalMinute == 0 || $finalMinute == 1) {
				$final = "Just now";
				}
				else if ($finalMinute > 1 && $finalMinute < 60) {
				$final = $finalMinute." minutes ago.";
				}
				else{
				$final = "1 hour ago.";
					
				}
			}
			
			else if ($finalHour == 1) {
				$final = $finalHour." hour ago.";
				
			}
			
			else if ($finalHour > 1 && $finalHour < 12) {
				
				$final = $finalHour." hours ago.";
			}
			
			
		}
		
		else if ($finalDay == 1) {
			$final = $finalDay." day ago.";
		}
	
	
	$printCalendarNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'><i class='fa fa-calendar' aria-hidden='true'></i></div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";
		
	}
	
	//REQUEST
	$query4 = "SELECT `NotificationID`, `Notification`, `Type`, `Seen`, `Timestamp`, `userID`, `ProjectID`, `EventID` FROM `Notifications` WHERE `userID`='$userID' AND (`Type` = 'Ticket' OR `Type` = 'LHN' OR `Type` = 'MegaNav') ORDER BY `Timestamp` DESC LIMIT 100";
$query4_result = mysqli_query($connection, $query4) or die ("getNotifications to get data from Team Project failed: ".mysql_error());
	
	while($row = $query4_result->fetch_assoc()) {
		$NotificationID = $row["NotificationID"];
			$notificationText = $row["Notification"];
			$seen = $row["Seen"];
			$type = $row["Type"];
			$Timestamp = $row["Timestamp"];
	
		//get days,hours,mins
		
		$getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
		$getSeperatedDate_result = mysqli_query($connection, $getSeperatedDate) or die ("Query to get data from Team Project failed: ".mysql_error());
		while ($row = mysqli_fetch_array($getSeperatedDate_result)) {
				$day = $row["DAY(`Timestamp`)"];
				$hour = $row["HOUR(`Timestamp`)"];
				$minute = $row["MINUTE(`Timestamp`)"];
				$second = $row["SECOND(`Timestamp`)"];
			}
		$currentDay = date("d");
		$currentHour = date("g");
		$currentMinute = date("i");
		$currentSecond = date("s");
		$currentAMPM = date("a");
		
		
		if ($hour > 12) {
			$hour = $hour -12;
		}
		
		$finalDay =$currentDay-$day;
		$finalHour =$currentHour-$hour;
		$finalMinute =$currentMinute-$minute;
		$finalSecond = $currentSecond-$second;
		
		if ($finalDay > 1) {
			
			$final = $finalDay." days ago.";
			
		} 
		else if ($finalDay == 0) {
			
			if ($finalHour ==0) {
				if ($finalMinute == 0 || $finalMinute == 1) {
				$final = "Just now";
				}
				else if ($finalMinute > 1 && $finalMinute < 60) {
				$final = $finalMinute." minutes ago.";
				}
				else{
				$final = "1 hour ago.";
					
				}
			}
			
			else if ($finalHour == 1) {
				$final = $finalHour." hour ago.";
				
			}
			
			else if ($finalHour > 1 && $finalHour < 12) {
				
				$final = $finalHour." hours ago.";
			}
			
			
		}
		
		else if ($finalDay == 1) {
			$final = $finalDay." day ago.";
		}
	
	
	$printRequestNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'><i class='fa fa-ticket' aria-hidden='true'></i></div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";
		
	}
	
	//NOTIFICATION COUNTS
	
	$getNotificationCount = "SELECT COUNT(`NotificationID`) FROM `Notifications` WHERE `userID` = '$userID' AND `Seen` = 'unseen'";
	$getNotificationCount_result = mysqli_query($connection, $getNotificationCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getNotificationCount_result->fetch_assoc()) {
	$notificationCount =$row['COUNT(`NotificationID`)'];
	}
	
	$getProjectCount = "SELECT COUNT(`NotificationID`) FROM `Notifications` WHERE `userID` = '$userID' AND `Seen` = 'unseen' AND (`Type` = 'Project' OR `Type` = 'Membership' OR `Type` = 'Note' OR `Type` = 'File' OR `Type` = 'Copy')";
	$getProjectCount_result = mysqli_query($connection, $getProjectCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getProjectCount_result->fetch_assoc()) {
	$projectCount =$row['COUNT(`NotificationID`)'];
	}
	
	$getTaskCount = "SELECT COUNT(`NotificationID`) FROM `Notifications` WHERE `userID` = '$userID' AND `Seen` = 'unseen' AND (`Type` = 'Task' OR `Type` = 'Tasks' OR `Type` = 'Comment')";
	$getTaskCount_result = mysqli_query($connection, $getTaskCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getTaskCount_result->fetch_assoc()) {
	$taskCount =$row['COUNT(`NotificationID`)'];
	}
	
	$getReviewCount = "SELECT COUNT(`NotificationID`) FROM `Notifications` WHERE `userID` = '$userID' AND `Seen` = 'unseen' AND (`Type` = 'Review')";
	$getReviewCount_result = mysqli_query($connection, $getReviewCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getReviewCount_result->fetch_assoc()) {
	$reviewCount =$row['COUNT(`NotificationID`)'];
	}
	
	$getEventCount = "SELECT COUNT(`NotificationID`) FROM `Notifications` WHERE `userID` = '$userID' AND `Seen` = 'unseen' AND (`Type` = 'Event')";
	$getEventCount_result = mysqli_query($connection, $getEventCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getEventCount_result->fetch_assoc()) {
	$eventCount =$row['COUNT(`NotificationID`)'];
	}
	
	$getRequestCount = "SELECT COUNT(`NotificationID`) FROM `Notifications` WHERE `userID` = '$userID' AND `Seen` = 'unseen' AND (`Type` = 'Ticket' OR `Type` = 'Tickets')";
	$getRequestCount_result = mysqli_query($connection, $getRequestCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getRequestCount_result->fetch_assoc()) {
	$requestCount =$row['COUNT(`NotificationID`)'];
	}
	
	/////////////
	
	//if empty return blank arrays
	
	
	$results = ["printAllNotifications" => $printAllNotifications,
				"printProjectNotifications" => $printProjectNotifications,
				"printTaskNotifications" => $printTaskNotifications,
				"printReviewNotifications" => $printReviewNotifications,
				"printCalendarNotifications" => $printCalendarNotifications,
				"printKCNotifications" => $printKCNotifications,
				"printRequestNotifications" => $printRequestNotifications,
				"printNotificationCount" => $notificationCount,
			   "printProjectNotificationCount" => $projectCount,
			   "printTaskNotificationCount" => $taskCount,
			   "printReviewNotificationCount" => $reviewCount,
			   "printEventNotificationCount" => $eventCount,
			   "printRequestNotificationCount" => $requestCount,
			   "absentUser" => $session_life,
			   "startTime" => $_SESSION['start']];
	
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
	
	
}

if ($type == "clearAll") {
$whichNotif = $_POST["whichNotif"];
if ($whichNotif == "All")	{
	$thisWhich = "";
}
else if ($whichNotif == "Project") {
		$thisWhich = " AND (`Type` = 'Project' OR `Type` = 'Membership' OR `Type` = 'Note' OR `Type` = 'File' OR `Type` = 'Copy')";
	}
else {
		$thisWhich = " AND `Type` = '$whichNotif'";
	}	
	
$deleteNotifications = "DELETE FROM `Notifications` WHERE `userID` = '$userID'$thisWhich";
	$deleteNotifications_result = mysqli_query($connection, $deleteNotifications) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
}

if ($type == "readAll") {
$whichNotif = $_POST["whichNotif"];
if ($whichNotif == "All")	{
	$thisWhich = "";
}
else if ($whichNotif == "Project") {
		$thisWhich = " AND (`Type` = 'Project' OR `Type` = 'Membership' OR `Type` = 'Note' OR `Type` = 'File' OR `Type` = 'Copy')";
	}
else {
		$thisWhich = " AND `Type` = '$whichNotif'";
	}

$deleteNotifications = "UPDATE `Notifications` SET `Seen`='seen' WHERE `userID` = '$userID'$thisWhich";
	$deleteNotifications_result = mysqli_query($connection, $deleteNotifications) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
}

if ($type == "seen") {
	// getting all user's notifications
	$notificationID=$_POST['notificationID1'];
	$getNotifications = "UPDATE `Notifications` SET `Seen` = 'seen' WHERE `NotificationID` = '$notificationID'";
	$getNotifications_result = mysqli_query($connection, $getNotifications) or die ("getNotifications to get data from Team Project failed: ".mysql_error());

}




			
		 		
				
	

	


?>