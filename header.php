<?php

require('connect.php');
$now = time();

$session_life = $now - $_SESSION['start'];
date_default_timezone_set('America/New_York');
if (strpos($_SERVER['REQUEST_URI'], 'process.php') !== false || strpos($_SERVER['REQUEST_URI'], 'users.php') !== false || $_SERVER['REQUEST_URI'] == "/dashboard/dependents/topNavData.php" || $_SERVER['REQUEST_URI'] == "/dashboard/dependents/searchEverything.php" || $_SERVER['REQUEST_URI'] == "/dashboard/team-projects/view/load.php" || $_SERVER['REQUEST_URI'] == "/dashboard/team-projects/filter.php" || $_SERVER['REQUEST_URI'] == "/dashboard/team-projects/view/tasks/main.php" || $_SERVER['REQUEST_URI'] == "/dashboard/todo/calendar/reviewsFetch.php" || $_SERVER['REQUEST_URI'] == "/dashboard/todo/calendar/tasksFetch.php") {
	
}
else 
{
	$_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; 
}

if(!$_SESSION['username']) {
	session_destroy();
	
  header("location:/dashboard/logout.php?returnURL=".$_SESSION['redirect_url']); 
  die(); 
}
else if (isset($_SESSION['start']) && $session_life > 7200 && $session_life < 10800) {
            
            header("location:/dashboard/verify/"); 
}//7200
else if (isset($_SESSION['start']) && $session_life > 10800) {
          session_destroy();
            header("location:/dashboard/?returnURL=".$_SESSION['redirect_url']); 
       }

//initializing variables
$showStyleCode= '';

$username =$_SESSION['username'];
//getting all users info
	$getAllDetails = "SELECT `user`.`userID`, `username`, `email`, `password`, `First Name`, `Last Name`, `Role`, `Title`, `PP Link`, `Member Status`, `Requested Group`, `Last Active`, `Groups`.`Group Name`, `Group Color`,`Group Membership`.`GroupID` FROM `user` JOIN `Group Membership` ON `user`.`userID`=`Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID`=`Group Membership`.`GroupID` WHERE `username` = '$username'";
	$getAllDetails_result = mysqli_query($connection, $getAllDetails) or die ("1 failed: ".mysql_error());
	
	 while($row = $getAllDetails_result->fetch_assoc()) {
        $userID = $row["userID"];
		$myRole = addslashes($row["Role"]);
		$FN = $row["First Name"];
		$LN = $row["Last Name"];
		$Title = addslashes($row["Title"]);
		$Email = $row["email"];
		$ProfilePic = $row["PP Link"];
		$groupName = $row["Group Name"];
		$groupID = $row["GroupID"];
		$groupColor = $row["Group Color"];
		$username2 = $row["username"];
		$lastActive = $row["Last Active"];
	 }
	//end getting all

//updating last active 
$currentTimestamp = date("Y-m-d H:i:s");
$updateActive = "UPDATE `user` SET `Last Active`='$currentTimestamp' WHERE `userID` = '$userID'";
$updateActive_result = mysqli_query($connection, $updateActive) or die ("1 failed: ".mysql_error());

	 $d5=strtotime("tomorrow");
	$day = date("Y-m-d", $d5);

// INSERTING MISC NOTIFICATIONS

$todaysDate= date("Y-m-d H:i:s");

$deleteOverdue = "DELETE FROM `Homepage Alerts` WHERE '$todaysDate' > `Take Down`";
$deleteOverdue_result = mysqli_query($connection, $deleteOverdue) or die(mysqli_error($connection));



//scripts and bandaids
//CODE BANDAIDS
//style
$query = "SELECT DISTINCT * FROM `Code Bandaids` WHERE `Type` = 'style'";
$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

		while ($row = mysqli_fetch_array($query_result)) {
			if (isset($row['Code'])) {
				$showStyleCode= $row['Code'];
			}

		}
$query = "SELECT DISTINCT * FROM `Code Bandaids` WHERE `Type` = 'script'";
$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

		while ($row = mysqli_fetch_array($query_result)) {
			if (isset($row['Code'])) {
				$showScriptCode= $row['Code'];
			} 
			else {
				$showScriptCode= '';
			}

		}

//hiding bandaids on settings pages
if (strpos($_SERVER['REQUEST_URI'],'settings') !== false) {
	$scripts ='<script type="text/javascript" src="/dashboard/js/main.js"></script>';
}
else {
	$scripts ='<script type="text/javascript" src="/dashboard/js/main.js"></script>'.$showStyleCode.$showScriptCode;
}

//css 
$css ='';

//email headers 
$headers = "From: no-reply@dashboard.coat.com/dashboard\r\n";
$headers .= "Reply-To: candice.garrett@burlingtonstores.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8";



?>