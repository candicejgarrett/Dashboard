<?php


include_once('../header.php');
 require('../connect.php');
require('../functions/global.php');

$type = $_POST['type'];
$tomorrowsDate = date("Y-m-d H:i:s",strtotime('tomorrow'));
$todaysDate = date("Y-m-d H:i:s",strtotime('today'));
$yesterdaysDate = date("Y-m-d H:i:s",strtotime('yesterday'));

$newsfeedCount = $_POST['newsfeedCount'];





if($type == 'loadAllHomeData')
{
	
	function getFullTodoList($userID,$tomorrowsDate) {
		
	global $connection;
	global $todaysDate;
	global $tomorrowsDate;
	global $yesterdaysDate;
	
	$query = "SELECT 
`TaskID` AS 'ItemID',
`Team Projects`.`Title` AS 'Project Title', 
`Tasks`.`Title` AS 'Item Title', 
DATE_FORMAT(`Tasks`.`Due Date`, '%m/%d/%y') AS 'Item Due Date', 
`Tasks`.`Due Date` AS 'Standard Due Date',
`Tasks`.`Status` AS 'Item Status',
`Tasks`.`ProjectID` AS 'Item ProjectID',
`Task Categories`.`Category` AS 'Item Type',
datediff(`Tasks`.`Due Date`,now()) AS 'Days Left To Complete'
FROM `Tasks` 
JOIN `Team Projects` ON `Tasks`.`ProjectID`=`Team Projects`.`ProjectID`
JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID`
WHERE `Tasks`.`Due Date` <= '$tomorrowsDate' AND
(`Tasks`.`userID`='$userID' 
AND `Tasks`.`Status` != 'Completed'
AND `Tasks`.`Status` != 'In Review')
OR
(`Tasks`.`Requested By`='$userID' 
AND `Tasks`.`Status` = 'In Review'
AND `Tasks`.`Due Date` <= '$tomorrowsDate')
UNION
SELECT 
`Tickets Review`.`ReviewID` AS 'ItemID',
`Team Projects`.`Title` AS 'Project Title', 
`Tickets Review`.`Title` AS 'Item Title', 
DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y') AS 'Item Due Date', 
`Tickets Review`.`Due Date` AS 'Standard Due Date',
`Tickets Review Members`.`Status` AS 'Item Status',
`Tickets Review`.`ProjectID` AS 'Item ProjectID',
`Tickets Review`.`Type` AS 'Item Type',
datediff(`Tickets Review`.`Due Date`,now()) AS 'Days Left To Complete'
FROM `Tickets Review` 
JOIN `Team Projects` ON `Tickets Review`.`ProjectID`=`Team Projects`.`ProjectID`
JOIN `Tickets Review Members` ON `Tickets Review`.`ReviewID` = `Tickets Review Members`.`ReviewID` 
WHERE `Tickets Review Members`.`userID` = '$userID' 
AND `Tickets Review`.`Due Date` <= '$tomorrowsDate'
AND (`Tickets Review Members`.`Status` != 'Approved' OR `Tickets Review Members`.`Status` IS NULL) ORDER BY 'Days Left To Complete' DESC";
			
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

		while ($row = mysqli_fetch_array($query_result)) {
			 $printID = $row["ItemID"];
			$printTaskTitle = $row["Item Title"];
			$printStatus = $row["Item Status"];
			$printDueDate = $row["Item Due Date"];
			$printStandardDueDate = $row["Standard Due Date"];
			 $printProjectID = $row["Item ProjectID"];
			$printProjectTitle = $row["Project Title"];
			$printItemType = $row["Item Type"];
			$daysLeftToComplete = $row["Days Left To Complete"];
			
			if ($printStatus === "New") {
				$statusText = "not started";
			}
			else if ($printStatus === "In Review" || $printStatus === "Not Approved" || $printStatus === null) {
				$statusText = "pending your approval";
				$printStatus = "In";
			}
			else {
				$statusText = "pending completion";
			}
			
			if ($printItemType === "Content" || $printItemType === "Requester") {
				$itemTypeText = "Review";
			}
			else {
				$itemTypeText = "Task";
			}

			//getting timeline status
			if ($itemTypeText == "Review") {
				$timelineStatus = getReviewTimelineStatus($printID);
			}
			else {
				$timelineStatus = getTimelineStatus($printID);
			}
			
			
			$todoItems[]= '<tr itemid="'.$printID.'" projectid="'.$printProjectID.'"type="'.$itemTypeText.'">
			<td>'.$printTaskTitle.' '.$timelineStatus.'</td>
			<td>'.$printProjectTitle.'</h2>
			<td><strong class="taskStatus '.$printStatus.'">'.$statusText.'</strong></td>
			<td>'.$itemTypeText.'</td>
			<td>'.$printDueDate.'</td>
			</tr>';
		}
		
		return $todoItems;
		
	}
	
	$getFullTodoListResult = getFullTodoList($userID,$tomorrowsDate);
	
	function getActiveProjects($userID) {
	global $todaysDate;
	global $groupID;
	global $connection;
	
		
	$getAllProjects = "SELECT DISTINCT `Team Projects`.`ProjectID`,`Team Projects`.`Due Date` AS 'Project Due Date Standard', `Team Projects`.`Status`, `Team Projects`.`Title`, `Team Projects`.`Description`, DATE_FORMAT(`Team Projects`.`Due Date`, '%b %e'), `Team Projects`.`userID`, `Date Created`, `Visible`, `Group Membership`.`GroupID`  
	FROM `Team Projects` 
	LEFT JOIN `Team Projects Member List` ON `Team Projects Member List`.`ProjectID`=`Team Projects`.`ProjectID` 
	JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID`
	WHERE (`Team Projects`.`Status` = 'Incomplete' OR `Team Projects`.`Status` = 'In Progress') 
	AND (`Team Projects`.`Visible`='Public' OR `Team Projects Member List`.`userID`='$userID') 
	AND `Group Membership`.`GroupID` = '$groupID'
	ORDER BY `Team Projects`.`Due Date` ASC";
	$getAllProjects_result = mysqli_query($connection, $getAllProjects) or die ("Query to get data from Team Project failed: ".mysql_error());

	while ($row = mysqli_fetch_array($getAllProjects_result)) {
											$projectID = $row['ProjectID'];
											$projectTitle = $row['Title'];
											$projectDesc = $row['Description'];
											$projectStatus = $row['Status'];
											$projectDueDate = $row["DATE_FORMAT(`Team Projects`.`Due Date`, '%b %e')"];
											$projectDueDateStandard = $row["Project Due Date Standard"];
											

											$getAllTasks = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID'";
											$getAllTasks_result = mysqli_query($connection, $getAllTasks) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getAllTasks_result)) {
												$projectTaskCount = $row2['COUNT(*)'];
											}
		
											 $getComplete = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `Status` = 'Completed'";
											$getComplete_result = mysqli_query($connection, $getComplete) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row3 = mysqli_fetch_array($getComplete_result)) {
												$projectTaskCompleteCount = $row3['COUNT(*)'];
											}
		
											if ($projectTaskCount == 0 && $projectTaskCompleteCount == 0) {
												$percentComplete = 0;
											}
											else {
												$percentComplete = round(($projectTaskCompleteCount/$projectTaskCount) * 100,0);
											}
		
											if ($projectDueDateStandard < $todaysDate && $projectStatus !== "Complete"  ) {
												$overdue = " lateProject";
											}
											else {
												$overdue = "";
											}
		
											
											
											
											$printBackProjects[]= '<div class="projectBox fadeInRight">
								<a href="/dashboard/team-projects/view/?projectID='.$projectID.'">
									<div class="name">'.$projectTitle.'</div>
									<div class="description">'.$projectDesc.'</div>
									
									<div class="percent"><span>'.$percentComplete.'%</span> completed</div>
									<div class="progressBar-container"><div class="progressBar pb-1 growGreen" style="width: '.$percentComplete.'%;"></div></div>
									
									<div class="deadline'.$overdue.'">
										<i class="fa fa-clock-o" aria-hidden="true"></i><h3>Deadline: <span>'.$projectDueDate.'</span></h3>
									</div>
								</a>
							</div>';
		
		
			
										}
		
		
		return $printBackProjects;
		
	}
	
	$getActiveProjectsResult = getActiveProjects($userID);
	///////////
	
	$results = ["newsfeedItems" => $getNewsfeedResult,
			   "fullTodoList" => $getFullTodoListResult,
				"activeProjects" => $getActiveProjectsResult];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
	

}

if($type == 'getMoreNewsfeed')
{
	$newsfeedCount = $_POST['newsfeedCount'];
	$getNewsfeedResult = getNewsfeed($newsfeedCount,0);
	
	
///////////
	
	$results = ["newsfeedItems" => $getNewsfeedResult];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
	

}




?>