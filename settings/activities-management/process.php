<?php
include_once('../../header.php');
 require('../../connect.php');
$type = $_POST['type'];

function returnResults() {
	
	global $connection;
	global $userID;
	
	$query = "SELECT DISTINCT `ActivityID`, `Type`, `Activity`, `Activity Feed`.`userID`, `ProjectID`, `EventID`, `TaskID`, `PostID`, `TicketID`, `ReviewID`, DATE_FORMAT(`Timestamp`, '%b %d %Y @ %h:%i%p') AS 'Timestamp', `First Name`, `Last Name` FROM `Activity Feed` JOIN `user` ON `Activity Feed`.`userID` = `user`.`userID` ORDER BY `ActivityID` DESC LIMIT 200";
	
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

	while ($row = mysqli_fetch_array($query_result)) {
											$printActivityID = $row["ActivityID"];
											$printType = $row["Type"];
											$printActivity = $row["Activity"];
											$printUserID = $row["userID"];
											$printTimestamp = $row["Timestamp"];
											 $printOwner = $row["First Name"]." ".$row["Last Name"];
											$printBack[]= '<tr activityID="'.$printActivityID.'">
				<td>'.$printActivityID.'</td>
				<td style="text-align:left">'.$printActivity.'</td>
				<td>'.$printTimestamp.'</td>
				<td>'.$printType.'</td>
				<td>'.$printOwner.'</td>
				<td><input type="checkbox" activityID="'.$printActivityID.'" value="'.$printActivityID.'"></td>
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

if($type == 'deleteMultiple')
{
	$activityIDs = explode(",", $_POST['activityIDs']);
	
	if (is_array($activityIDs))
	{
		foreach ($activityIDs as $activityID) {
			$remove = "DELETE FROM `Activity Feed` WHERE `ActivityID` = '$activityID'";
			$remove_result = mysqli_query($connection, $remove) or die(mysqli_error($connection));
		}
		
	}
	else {
		
	}
	
	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

}
?>