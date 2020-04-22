<?php

require('../connect.php');
require('../header.php');

$type = $_POST["type"];
	
if ($type="getApprovals") {
	$getApprovalRequests = "SELECT `Tasks`.`TaskID`, `Tasks`.`Title` AS 'Task Title', `Tasks`.`Description` AS 'Task Description', DATE_FORMAT(`Tasks`.`Due Date`,'%a, %b. %e, %Y @ %h:%i %p') AS 'Task Due Date', `Tasks`.`End Date`, `Tasks`.`Status` AS 'Task Status',`Tasks`.`Category` AS 'Task CategoryID', `Requested By`, `Tasks`.`ProjectID`, `Tasks`.`userID`, `allDay`, `Task Date Created`, `Task Date Completed`,`Team Projects`.`Title` AS 'Project Title',DATE_FORMAT(`Team Projects`.`Due Date`,'%W, %b. %e, %Y @ %h:%i %p') AS 'Project Due Date',`user`.`First Name`,`user`.`Last Name` FROM `Tasks` JOIN `Team Projects` ON `Team Projects`.`ProjectID` = `Tasks`.`ProjectID` JOIN `user` ON `user`.`userID` = `Tasks`.`userID` WHERE `Tasks`.`Status` = 'In Review' AND `Requested By` = '$userID'";
					$getApprovalRequests_result = mysqli_query($connection, $getApprovalRequests) or die ("Query to get data from Team task failed: ".mysql_error());
while ($row = mysqli_fetch_array($getApprovalRequests_result)) {
	
	$taskID = $row['TaskID'];
	$taskTitle = $row['Task Title'];
	$taskDescription = $row['Task Description'];
	$taskDueDate = $row['Task Due Date'];
	$taskStatus = $row['Task Status'];
	$taskTitle = $row['Task Title'];
	$taskProjectID = $row['ProjectID'];
	$taskProjectTitle = $row['Project Title'];
	$taskAssignedToUserID = $row['userID'];
	$taskAssignedToName = $row['First Name'].' '.$row['Last Name'];
								
	//getting comments
	$getComments = "SELECT `CommentID`,`Message`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `userID`, `ProjectID`, `TaskID`, `Sent By` FROM `Task Comments` WHERE `TaskID` = '$taskID' ORDER BY `Timestamp` ASC";

	$getComments_result = mysqli_query($connection, $getComments) or die ("getComments to get data from Team Project failed: ".mysql_error());

	while($row5 = $getComments_result->fetch_assoc()) {
			$whoSentCom = $row5["Sent By"];
			$ProjectIDCom = $row5["ProjectID"];
			$TimestampCom = $row5["DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')"];
			$MessageCom = $row5["Message"];
			$MessageIDCom = $row5["CommentID"];
			$getWhoSentCom = "SELECT * FROM `user` WHERE `userID` = '$whoSentCom'";

			$getWhoSentCom_result = mysqli_query($connection, $getWhoSentCom) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

			while($row6 = $getWhoSentCom_result->fetch_assoc()) {	
			$WhoSentFNCom = $row6["username"];
			$ppLink = $row6["PP Link"];
			}
		
		
			if ($whoSentCom != $userID){
				$messageCSS = "incomingCom";
				
			}
			else {
				$messageCSS = "outgoingCom";
				
			}

			$printMessagesCom = "<div class='comments $messageCSS' id='$MessageIDCom'><div class='sender'><img class='commentsImage' src='$ppLink'></div><pre class='message'>$MessageCom</pre><div class='timestamp'>@$WhoSentFNCom<br>$TimestampCom</div></div>";
			$printComments =implode('',$printMessagesCom);

	}
	
	
	$printApprovals[]= "
	<div class='approvalContainer'>
	<div class='approvalRequest' taskid='$taskID'>
		<table style='width:100%'>
			<tr>
				<td style='width: 75%;' class='expandApprovalDetails'>
					<p>$taskTitle</p>
				</td>
				<td style='width: 25%;'>
					<div class='approval pull-right' style='color:#ffffff !important;' taskid='$taskID'>Approve</div>
					<div class='kickback pull-right' style='color:#ffffff !important;'>Kickback</div>
				</td>
			</tr>
		</table>
	</div>
	<div class='approvalRequestDetails'>
		<h3>$taskTitle</h3><br>
		<p><strong>Assigned To:</strong> $taskAssignedToName</p>
		<p><strong>Due Date:</strong> $taskDueDate</p>
		<p><strong>Project Title:</strong> <a href=/dashboard/team-projects/view/?projectID=$taskProjectID>$taskProjectTitle</a></p><br>
		<p><strong>Description:</strong><br>$taskDescription</p>
		<hr>
		<p><strong>Comments:</strong><br><div class='comment-container'>".$printMessagesCom."</div></p>
		<div class='kickbackMessage'>
		<div class='formLabels'>Message:</div>
			<pre><textarea class='kickback_message' placeholder='Enter your reason why here.'></textarea></pre>
			<div class='kickback pull-right' style='color:#ffffff !important;' id='sendKickback' taskid='$taskID'>SEND</div>
			<br><br>
		</div>
	</div>
	
		
	
	</div>
	";
	
	
}
	
	
	///////// RETURN DATA //////////
	
	$results = ["printApprovals" => $printApprovals];
				header('Content-Type: application/json'); 
				echo json_encode($results);
							
}
	






			
		 		
				
	

	


?>