<?php
include_once('../../header.php');
 require('../../connect.php');
require('../../emailDependents.php');
include('../../functions/global.php');

$type = $_POST['type'];
$taskID = $_POST['taskID'];

function returnResults() {
	
	global $connection;
	global $userID;
	global $todaysDate;
	
	$query = "SELECT `TaskID`,`First Name`,`Last Name`, `Team Projects`.`Title` AS 'Project Title', `Tasks`.`Title`, `Tasks`.`Description`, DATE_FORMAT(`Tasks`.`Due Date`, '%m/%d/%y'), `Tasks`.`Status`, `Task Categories`.`Category`, `Requested By`, `Tasks`.`ProjectID`, `Tasks`.`userID`, `allDay`, DATE_FORMAT(`Task Date Created`, '%m/%d/%y'), `Task Date Completed`,`Tasks`.`Due Date` AS 'Standard Due Date', datediff(`Tasks`.`Due Date`,now()) AS 'Days Left To Complete' FROM `Tasks` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` JOIN `Team Projects` ON `Tasks`.`ProjectID`=`Team Projects`.`ProjectID` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` WHERE `Tasks`.`Requested By`='$userID' AND `Team Projects`.`Status` != 'Archived' AND `Tasks`.`Status` = 'In Review' ORDER BY `Task Date Created` DESC";
	
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

	while ($row = mysqli_fetch_array($query_result)) {
				 $printID = $row["TaskID"];
				$printCreatedDate = $row["DATE_FORMAT(`Task Date Created`, '%m/%d/%y')"];
				$printTitle = $row["Title"];
				$printStatus = $row["Status"];
				$printDueDate = $row["DATE_FORMAT(`Tasks`.`Due Date`, '%m/%d/%y')"];
				$printStandardDueDate = $row["Standard Due Date"];
				 $printDescription = $row["Description"];
				 $printCategory = $row["Category"];
				 $printProjectID = $row["ProjectID"];
				$printProjectTitle = $row["Project Title"];
				 $printCreatedByUserID = $row["userID"];
				$printTicketID = $row["TicketID"];
				$printAssignedTo = $row["First Name"]." ".$row["Last Name"];
				$statusText = "pending your approval";
					$printStatus = "In";
				$daysLeftToComplete = $row["Days Left To Complete"];
		
				//getting timeline status
				$timelineStatus = getTimelineStatus($printID);
		
				$printBack[]= '<tr taskid="'.$printID.'" projectid="'.$printProjectID.'">
				<td>'.$printTitle.' '.$timelineStatus.'</td>
				<td>'.$printProjectTitle.'</h2>
				<td>'.$printCategory.'</td>
				
				<td>'.$printDueDate.'</td>
				<td>'.$printAssignedTo.'</td>
				<td><div class="taskStatus '.$printStatus.'">'.$statusText.'</div></td>
				<td><input type="checkbox" taskID="'.$printID.'" value="'.$printID.'"></td>
				
			  </tr>';
											
											
										}
	
	return $printBack;
}

if($type == 'load')
{
	$printBack = returnResults();
	
	////////////
	
	$result = ["printBack" => $printBack];

	header('Content-Type: application/json'); 
	echo json_encode($result);
}

if($type == 'approve')
{
	approveTask($taskID);
	
	/////////
	$printBack = returnResults();
			
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);
}

if($type == 'approveMultiple')
{
	$taskIDs= explode(",", $_POST['taskIDs']);
	
	if (is_array($taskIDs))
	{
		foreach ($taskIDs as $taskID) {
			approveTask($taskID);	
		}
		
	}
	
	
	/////////
	$printBack = returnResults();
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);
}

if($type == 'kickback')
{
if (isset($_POST['newVal'])) {
	$taskMessage = $_POST['newVal'];
}
else {
	$taskMessage = "";
}
	
	kickbackTask($taskID,$taskMessage);
	
	/////////
	$printBack = returnResults();
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);
}

if($type == 'kickbackMultiple')
{
	$taskIDs= explode(",", $_POST['taskIDs']);

	if (is_array($taskIDs))
	{
		foreach ($taskIDs as $taskID) {
			
			$taskMessage = "N/A";
			kickbackTask($taskID,$taskMessage);
		
		}
		
	}
	
	
	/////////
	$printBack = returnResults();
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);
}

if($type == 'delete')
{
	

	deleteTask($taskID);
	
	
	/////////
$printBack = returnResults();
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);
}

if($type == 'deleteMultiple')
{
	
$taskIDs= explode(",", $_POST['taskIDs']);
	
	if (is_array($taskIDs))
	{
		foreach ($taskIDs as $taskID) {
			deleteTask($taskID);
		}
		
	}	

	///////////////////////////
	$printBack = returnResults();
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);
}

?>