<?php
include_once('../../header.php');
 require('../../connect.php');
$type = $_POST['type'];

if($type == 'sendAlert')
{
	$groupIDs = explode(",", $_POST['groupIDs']);
	$alertTitle = addslashes($_POST['alertTitle']);
	$alertText = addslashes($_POST['alertText']);
	$alertType = $_POST['alertType'];
	$alertTakeDown = date("Y-m-d H:i:s",strtotime($_POST['alertTakeDown']));
	
	
	if ($groupIDs)
	{
		
		foreach ($groupIDs as $thisGroupID) {
			$insert0 = "INSERT INTO `Homepage Alerts Count`(`GroupID`) VALUES ('$thisGroupID')";
			$insert0_result = mysqli_query($connection, $insert0) or die(mysqli_error($connection));
		
			$alertCountID = mysqli_insert_id($connection);
			
			//GETTING USERS
			$getUsers = "SELECT `userID` FROM `Group Membership` WHERE `GroupID` = '$thisGroupID'";
			$getUsers_result = mysqli_query($connection, $getUsers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUsers_result)) {
				$groupMembers[] =$row["userID"];	
				
			}
			
			if ($groupMembers) {
			foreach ($groupMembers as $name) {
				$insert = "INSERT INTO `Homepage Alerts`(`userID`,`Alert Type`, `Alert Title`, `Alert Text`, `GroupID`, `Take Down`, `AlertCountID`) VALUES ('$name','$alertType','$alertTitle','$alertText','$thisGroupID','$alertTakeDown','$alertCountID')";
				$insert_result = mysqli_query($connection, $insert) or die(mysqli_error($connection));
				
				$groupMembers = array_diff($groupMembers, array($name));
				
			}
		}
			
		}
		
		
	}
	else {
		
	}

}

if($type == 'showAlerts')
{
	$selectedGroup = $_POST['GroupID'];
	$todaysDate= date("Y-m-d h:i:s");
	$getAlerts = "SELECT DISTINCT `Homepage Alerts Count`.`AlertCountID`, `Alert Type`, `Alert Title`, `Alert Text`, `Homepage Alerts Count`.`GroupID`, `Timestamp`, `Take Down`,`Homepage Alerts`.`AlertCountID` FROM `Homepage Alerts Count` JOIN `Homepage Alerts` ON `Homepage Alerts`.`AlertCountID` = `Homepage Alerts Count`.`AlertCountID` WHERE `Homepage Alerts Count`.`GroupID`='$selectedGroup' ORDER BY `Timestamp` DESC";
	$getAlerts_result = mysqli_query($connection, $getAlerts) or die(mysqli_error($connection));

	while($row = $getAlerts_result->fetch_assoc()) {
			$alertCountID = $row["AlertCountID"];
			$alertType = $row["Alert Type"];
			$alertTitle = $row["Alert Title"];
			$alertText = $row["Alert Text"];
			$alertTakeDown = date("Y-m-d h:i:s A",strtotime($row["Take Down"]));

			
			$showAllAlerts[] = '<p>This will be removed on: <em><strong>'.$alertTakeDown.'.</strong></em></p><div class="'.$alertType.'Alert" alertcountid="'.$alertCountID.'"><i class="fa fa-times fa-2x pull-right" style="font-size:20px;color:#ffffff;" aria-hidden="true"></i><h1>'.$alertTitle.'</h1><p>'.$alertText.'</p></div>';
	}
	
	
	////////////
	
	$response = ["showAllAlerts" => $showAllAlerts];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if($type == 'deleteAlert')
{
	$groupID = $_POST['groupID'];
	$alertCountID = $_POST['alertCountID'];
	$remove = "DELETE FROM `Homepage Alerts` WHERE `alertCountID` = '$alertCountID' AND `GroupID` = '$groupID'";
    $remove_result = mysqli_query($connection, $remove) or die(mysqli_error($connection));
	
	$remove2 = "DELETE FROM `Homepage Alerts Count` WHERE `alertCountID` = '$alertCountID' AND `GroupID` = '$groupID'";
    $remove2_result = mysqli_query($connection, $remove2) or die(mysqli_error($connection));
	
	
	////////////
	
	$response = ["successMessage" => $successMessage];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if($type == 'markAlert')
{
	$alertID = $_POST['alertID'];
	
	$query = "UPDATE `Homepage Alerts` SET `seen`='1' WHERE `AlertID`='$alertID'";
	$query_result = mysqli_query($connection, $query) or die(mysqli_error($connection));

}
?>