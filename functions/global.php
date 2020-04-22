<?php

//********* FUNCTIONS FOR GETTING INFORMATION *********
function getUserInfo($userID) {
	global $connection;
	
	$getAll = "SELECT 
	`user`.`userID`, 
	`username`, 
	`email`, 
	`First Name`, 
	`Last Name`, 
	`Role`, 
	`Title`, 
	`PP Link`, 
	`Member Status`, 
	`Requested Group`, 
	`Last Active`, 
	`Groups`.`Group Name`, 
	`Group Color`,
	`Group Membership`.`GroupID`  
	FROM `user` 
	JOIN `Group Membership` ON `user`.`userID`=`Group Membership`.`userID` 
	JOIN `Groups` ON `Groups`.`GroupID`=`Group Membership`.`GroupID` 
	WHERE `user`.`userID` = '$userID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $out["userUsername"] = $row["username"];
		$out["userEmail"]= $row["email"];
		$out["userFirstName"] = $row["First Name"];
		$out["userLastName"] = $row["Last Name"];
		$out["userRole"] = $row["Role"];
		$out["userTitle"] = $row["Title"];
		$out["userPPLink"] = $row["PP Link"];
		$out["userMemberStatus"] = $row["Member Status"];
		$out["userRequestedGroup"] = $row["Requested Group"];
		$out["userLastActive"] = $row["Last Active"];
		$out["userGroupName"] = $row["Group Name"];
		$out["userGroupColor"] = $row["Group Color"];
		$out["userGroupID"] = $row["GroupID"];
	}
	
	return $out;
}

function getTaskInfo($taskID) {
	global $connection;
	
	$getAll = "SELECT 
	`id`,
	`calendar`.`title`,
	DATE_FORMAT(`enddate`, '%Y-%m-%dT%H:%i') AS 'Event End Date',
	`calendar`.`Category` AS 'CalendarCategoryID',
	`Calendar Categories`.`Category` AS 'Calendar Category',
	`Tasks`.`TaskID`,
	`Tasks`.`Title`,
	`Tasks`.`Description`,
	DATE_FORMAT(`Tasks`.`Due Date`, '%Y-%m-%dT%H:%i') AS 'Task Due Date',
	DATE_FORMAT(`Tasks`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Task Due Date Display',
	DATE_FORMAT(`Tasks`.`End Date`, '%Y-%m-%dT%H:%i') AS 'Task End Date',
	`Tasks`.`Status`, 
	`Tasks`.`Category` AS 'Task CategoryID',
	`Task Categories`.`Category` AS 'Task Category Title',
	`Tasks`.`Requested By`, 
	`Tasks`.`ProjectID`, 
	`Tasks`.`userID`, 
	`Tasks`.`allDay`, 
	`First Name`, 
	`Last Name`, 
	`PP Link`,
	`Team Projects`.`Title` AS 'Task Project Title',
	`Team Projects`.`Task Type`,
	datediff(`Tasks`.`Due Date`,now()) AS 'Days Left To Complete'
	FROM `Tasks` 
	LEFT JOIN `calendar` ON `calendar`.`TaskID` = `Tasks`.`TaskID` 
	LEFT JOIN `Calendar Categories` ON `calendar`.`Category` = `Calendar Categories`.`CalendarCategoryID` 
	LEFT JOIN `Task Categories` ON `Tasks`.`Category` = `Task Categories`.`CategoryID` 
	JOIN `user` ON `Tasks`.`userID` = `user`.`userID` 
	JOIN `Team Projects` on `Tasks`.`ProjectID`=`Team Projects`.`ProjectID`
	WHERE 
	`Tasks`.`TaskID` = '$taskID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $out["title"] = $row["Title"];
		$out["description"]= $row["Description"];
		$out["dueDate"] = $row["Task Due Date"];
		$out["dueDateDisplay"] = $row["Task Due Date Display"];
		$out["endDate"] = $row["Task End Date"];
		$out["Status"] = $row["Status"];
		$out["categoryID"] = $row["Task CategoryID"];
		$out["categoryTitle"] = $row["Task Category Title"];
		$out["projectID"] = $row["ProjectID"];
		$out["projectTitle"] = $row["Task Project Title"];
		$out["projectTaskType"] = $row["Task Type"];
		$out["projectCreatedByGroupID"] =$row["GroupID"];
		$out["requestedByUserID"] = $row["Requested By"];
		$out["assignedToUserID"] = $row["userID"];
		$out["assignedToFullName"] = $row["First Name"].' '.$row["Last Name"];
		$out["assignedToPP"] = $row["PP Link"];
		$out["eventID"] = $row["id"];
		$out["eventTitle"] = $row["title"];
		$out["eventEndDate"] = $row["Event End Date"];
		$out["eventCategoryID"] = $row["CalendarCategoryID"];
		$out["eventCategory"] = $row["Calendar Category"];
		$out["daysLeftToComplete"] = $row["Days Left To Complete"];
		
		
		if ($row["Status"] == "New") {
					$statusNumber=0;
				}
				else if ($row["Status"]  == "In Review") {
					$statusNumber=100;
				}
				else if ($row["Status"]  == "Approved") {
					$statusNumber=200;
				}
				else {
					$statusNumber=300;
				}
		$out["statusNumber"] = $statusNumber;
	
	}
	
	return $out;
}

function getProjectInfo($projectID) {
	global $connection;
	
	$getAll = "SELECT 
	`Team Projects`.`ProjectID` AS 'projectID', 
	`Team Projects`.`Status` AS 'Project Status', 
	`Team Projects`.`Title` AS 'Project Title', 
	`Team Projects`.`Description` AS 'Project Description', 
	`Team Projects`.`Category` AS 'Project CategoryID', 
	`Team Projects Categories`.`Category` AS 'Project Category Title',
	`Team Projects`.`Due Date` AS 'Project Due Date',
	 DATE_FORMAT(`Team Projects`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Project Due Date Display',
	`Team Projects`.`Task Type` AS 'Project Task Type', 
	`Team Projects`.`userID` AS 'Project Owner userID', 
	`Team Projects`.`Date Created` AS 'Project Date Created', 
	 DATE_FORMAT(`Team Projects`.`Date Created`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Project Date Created Display',
	`Team Projects`.`Date Completed` AS 'Project Date Completed', 
	 DATE_FORMAT(`Team Projects`.`Date Completed`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Project Date Completed Display',
	`Team Projects`.`Visible` AS 'Project Visibility', 
	`Team Projects`.`Project Folder Link` AS 'Project Folder Link', 
	`Team Projects`.`URL To Use` AS 'Project URL to Use', 
	`Team Projects`.`TicketID` AS 'Project ticketID', 
	`Team Projects`.`Copy` AS 'Project Copy',
	`Group Membership`.`GroupID` AS 'GroupID',
	`First Name`, 
	`Last Name`, 
	`PP Link`
	FROM `Team Projects` 
	LEFT JOIN `Team Projects Categories` ON `Team Projects`.`Category` = `Team Projects Categories`.`ProjectCategoryID` 
	JOIN `user` ON `Team Projects`.`userID` = `user`.`userID`
	JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID`
	WHERE 
	`Team Projects`.`ProjectID` = '$projectID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $out["title"] = $row["Project Title"];
		$out["description"]= $row["Project Description"];
		$out["dueDate"] = $row["Project Due Date"];
		$out["dueDateDisplay"] = $row["Project Due Date Display"];
		$out["dateCreated"] = $row["Project Date Created"];
		$out["dateCreatedDisplay"] = $row["Project Date Created Display"];
		$out["dateCompleted"] = $row["Project Date Completed"];
		$out["dateCompletedDisplay"] = $row["Project Date Completed Display"];
		$out["Status"] = $row["Project Status"];
		$out["categoryID"] = $row["Project CategoryID"];
		$out["categoryTitle"] = $row["Project Category Title"];
		$out["taskType"] = $row["Task Type"];
		$out["ownerUserID"] = $row["Project Owner userID"];
		$out["ownerFullName"] = $row["First Name"].' '.$row["Last Name"];
		$out["ownerPP"] = $row["PP Link"];
		$out["ownerGroupID"] =$row["GroupID"];

	}
	
	return $out;
}

function getReviewInfo($reviewID) {
	global $connection;
	
	$getAll = "SELECT 
	`Tickets Review`.`userID` AS 'ReviewOwnerUserID', 
	`Tickets Review`.`Title` AS 'Review Title',
	`Type`,
	`Tickets Review`.`Status`,
	`Tickets Review`.`Due Date` AS 'Review Due Date',
	 DATE_FORMAT(`Tickets Review`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Review Due Date Display',
	`Tickets Review`.`Date Created` AS 'Review Date Created',
	 DATE_FORMAT(`Tickets Review`.`Date Created`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Review Date Created Display',
	`Tickets Review`.`ProjectID` AS 'Review ProjectID',
	`Team Projects`.`Title` AS 'Review Project Title',
	`Team Projects`.`Due Date` AS 'Review Project Due Date',
	 DATE_FORMAT(`Team Projects`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Review Project Due Date Display',
	`Desktop Preview Image Link`, 
	`Mobile Preview Image Link`, 
	`First Name`, 
	`Last Name`, 
	`PP Link`,
	datediff(`Tickets Review`.`Due Date`, now()) AS 'Days Left To Complete'
	FROM `Tickets Review` 
	JOIN `Team Projects` ON `Tickets Review`.`ProjectID` = `Team Projects`.`ProjectID` 
	JOIN `user` ON `Tickets Review`.`userID` = `user`.`userID` 
	WHERE 
	`ReviewID` ='$reviewID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $out["title"] = $row["Review Title"];
		$out["Type"] = $row["Type"];
		$out["dueDate"] = $row["Review Due Date"];
		$out["dueDateDisplay"] = $row["Review Due Date Display"];
		$out["dateCreated"] = $row["Review Date Created"];
		$out["dateCreatedDisplay"] = $row["Review Date Created Display"];
		$out["Status"] = $row["Status"];
		$out["projectID"] = $row["Review ProjectID"];
		$out["desktopImage"] = $row["Desktop Preview Image Link"];
		$out["mobileImage"] = $row["Mobile Preview Image Link"];
		$out["ownerUserID"] = $row["ReviewOwnerUserID"];
		$out["ownerFullName"] = $row["First Name"].' '.$row["Last Name"];
		$out["ownerPP"] = $row["PP Link"];
		$out["daysLeftToComplete"] = $row["Days Left To Complete"];
		if ($row["Review Project Title"] !== ''){
			 $out["projectTitle"] = $row["Review Project Title"];
		 }
		 else {
			 $out["projectTitle"] = "Deleted Project";
		 }
		$out["projectDueDate"] = $row["Review Project Due Date"];
		$out["projectDueDateDisplay"] = $row["Review Project Due Date Display"];
		 if (isset($row["Desktop Preview Image Link"])) {
			 $desktopImage = $row["Desktop Preview Image Link"];
			  //get image id of desktop
			 $getDesktopImage = "SELECT * FROM `Tickets Review Preview Images` WHERE `Preview Image Link`='$desktopImage' AND `ReviewID`='$reviewID' AND `Type`='Desktop' ORDER BY `ImageID` LIMIT 1";
			 $getDesktopImage_result = mysqli_query($connection, $getDesktopImage) or die ("Query to get data from Team Project failed: ".mysql_error());
			 
			 while($row = $getDesktopImage_result->fetch_assoc()) {
				  $desktopImageID = $row["ImageID"];
				  $out["desktopImageID"] = $row["ImageID"];
			 }
			 
			 $getDesktopMockupCount = "SELECT COUNT(`MarkUpID`) FROM `Tickets Review MarkUps` WHERE `ImageID` ='$desktopImageID'";
				$getDesktopMockupCount_result = mysqli_query($connection, $getDesktopMockupCount) or die ("Query to get data from Team task failed: ".mysql_error());
				while($row = $getDesktopMockupCount_result->fetch_assoc()) {
					
					$out["desktopMarkupCount"] = $row["COUNT(`MarkUpID`)"];
				}
			 
		
		 
		 }
		 else {
			 $out["desktopImageID"] ="";
			 $out["desktopMarkupCount"] =0;
		 }
		 if (isset($row["Mobile Preview Image Link"])) {
			 $mobileImage = $row["Mobile Preview Image Link"];
			  //get image id of mobile
			 $getMobileImage = "SELECT * FROM `Tickets Review Preview Images` WHERE `Preview Image Link`='$mobileImage' AND `ReviewID`='$reviewID' AND `Type`='Mobile' ORDER BY `ImageID` LIMIT 1";
			 $getMobileImage_result = mysqli_query($connection, $getMobileImage) or die ("Query to get data from Team Project failed: ".mysql_error());
			 
			 while($row = $getMobileImage_result->fetch_assoc()) {
				  $mobileImageID = $row["ImageID"];
				  $out["mobileImageID"] = $row["ImageID"];
			 }
			 
			 $getMobileMockupCount = "SELECT COUNT(`MarkUpID`) FROM `Tickets Review MarkUps` WHERE `ImageID` ='$mobileImageID'";
				$getMobileMockupCount_result = mysqli_query($connection, $getMobileMockupCount) or die ("Query to get data from Team task failed: ".mysql_error());
				while($row = $getMobileMockupCount_result->fetch_assoc()) {
					
					$out["mobileMarkupCount"] = $row["COUNT(`MarkUpID`)"];
				}
			 
		
		 
		 }
		 else {
			 $out["mobileImageID"] ="";
			 $out["mobileMarkupCount"] =0;
		 }
		
		
	}
	
	return $out;
}

function getEventInfo($eventID) {
	global $connection;
	
	$getAll = "SELECT DISTINCT 
	`calendar`.`ProjectID`,
	`Tasks`.`Category` AS 'Task CategoryID',
	`calendar`.`TaskID`,
	`calendar`.`id`,
	`calendar`.`title`,
	`First Name`,
	`Last Name`,
	`PP Link`,
	`calendar`.`allDay`,
	`Preview Image Link`,
	`Preview Image Link Mobile`, 
	DATE_FORMAT(`startdate`, '%b %d %Y @ %h:%i%p') AS 'Formatted Start Date',
	`startdate` AS 'Standard Start Date',
	DATE_FORMAT(`enddate`, '%b %d %Y @ %h:%i%p') AS 'Formatted End Date',
	`enddate`AS 'Standard End Date',
	`Calendar Categories`.`Category` AS 'Category Name',
	`calendar`.`Category`,
	`calendar`.`userID`,
	`calendar`.`Description` 
	FROM `calendar` 
	JOIN `user` on `calendar`.`userID` 
	JOIN `Calendar Categories` on `Calendar Categories`.`CalendarCategoryID`=`calendar`.`category` 
	LEFT JOIN `Tasks` on `Tasks`.`TaskID`=`calendar`.`TaskID` 
	WHERE `id`='$eventID' 
	AND `calendar`.`userID`=`user`.`userID`";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $out["title"] = $row["title"];
		$out["startDate"] = $row["Standard Start Date"];
		$out["startDateDisplay"] = $row["Formatted Start Date"];
		$out["endDate"] = $row["End Start Date"];
		$out["endDateDisplay"] = $row["Formatted End Date"];
		$out["categoryID"] = $row["Category"];
		$out["categoryTitle"] = $row["Category Name"];
		$out["description"] = $row["Description"];
		$out["Status"] = $row["Status"];
		$out["projectID"] = $row["ProjectID"];
		$out["taskID"] = $row["TaskID"];
		$out["allDay"] = $row["allDay"];
		$out["desktopImagePath"] = $row["Preview Image Link"];
		$out["mobileImagePath"] = $row["Preview Image Link Mobile"];
		$out["ownerUserID"] = $row["userID"];
		$out["ownerFullName"] = $row["First Name"].' '.$row["Last Name"];
		$out["ownerPP"] = $row["PP Link"];

	}
	
	return $out;
}

function getKCPostInfo($postID) {
	global $connection;
	
	$getAll = "SELECT 
	`Knowledge Center`.`PostID`, 
	`Knowledge Center`.`userID`, 
	DATE_FORMAT(`Date Created`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Date Created', 
	`Post Title`, 
	`Post Description`, 
	`Category`, 
	`Post Image`, 
	DATE_FORMAT(`Last Updated`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Last Updated', 
	`Last Updated By`, 
	`First Name`, 
	`Last Name`, 
	`username`, 
	`PP Link` 
	FROM `Knowledge Center` 
	JOIN `Knowledge Center Categories` ON `Knowledge Center Categories`.`KC CategoryID` = `Knowledge Center`.`KC CategoryID` 
	JOIN `user` ON `Knowledge Center`.`userID` = `user`.`userID` 
	WHERE `PostID` = '$postID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        
		$out["postTitle"] = $row["Post Title"];
		$out["postCreatedDate"] = $row["Date Created"]; 
		$out["postStatus"] = $row["Status"];
		$out["postLastUpdated"] = $row["Last Updated"];
		$out["postDescription"] = $row["Post Description"];
		$out["postCategory"] = $row["Category"];
		$out["postCreatedByUserID"] = $row["userID"];
		$out["projectCreatedByPP"] = $row["PP Link"];
		$out["postCreatedBy"] = $row["First Name"]." ".$row["Last Name"];
		$out["printUsername"]=$row["username"];

	}
	
	return $out;
}

function getNewsfeed($newsfeedCount,$thisUserID) {
	global $userID;
	global $connection;
		
	if ($thisUserID != 0) {
		
		if ($thisUserID != $userID) {
			$getLatestActivity = "SELECT DISTINCT `ActivityID`,`Activity`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `Activity Feed`.`ProjectID`,`EventID`,`PostID`,`TaskID`,`ReviewID`,`Activity Feed`.`TicketID`,`Type`,`Activity Feed`.`userID`,`Timestamp` FROM `Activity Feed` WHERE `Activity Feed`.`ActivityID` NOT IN(SELECT DISTINCT `ActivityID` FROM `Activity Feed` JOIN `Team Projects` on `Team Projects`.`ProjectID` = `Activity Feed`.`ProjectID` WHERE `Activity Feed`.`ProjectID`!= '' AND `Team Projects`.`Visible` = 'Private' ) AND `Activity Feed`.`userID` = '$thisUserID' ORDER BY `ActivityID` DESC LIMIT $newsfeedCount";
		}
		else {
			
		$getLatestActivity = "SELECT DISTINCT `ActivityID`,`Activity`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `Activity Feed`.`ProjectID`,`EventID`,`PostID`,`TaskID`,`ReviewID`,`Activity Feed`.`TicketID`,`Type`,`Activity Feed`.`userID`,`Timestamp` FROM `Activity Feed` WHERE `Activity Feed`.`userID` = '$userID' ORDER BY `ActivityID` DESC LIMIT $newsfeedCount";
		}
		
	}
	else {
		$getLatestActivity = "SELECT DISTINCT `ActivityID`,`Activity`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `Activity Feed`.`ProjectID`,`EventID`,`PostID`,`TaskID`,`ReviewID`,`Activity Feed`.`TicketID`,`Type`,`Activity Feed`.`userID`,`Timestamp` FROM `Activity Feed` WHERE `Activity Feed`.`ActivityID` NOT IN(SELECT DISTINCT `ActivityID` FROM `Activity Feed` JOIN `Team Projects` on `Team Projects`.`ProjectID` = `Activity Feed`.`ProjectID` WHERE `Activity Feed`.`ProjectID`!= '' AND `Team Projects`.`Visible` = 'Private' ) ORDER BY `ActivityID` DESC LIMIT $newsfeedCount";
	}
	
	
	
	$getLatestActivity_result = mysqli_query($connection, $getLatestActivity) or die ("query1 to get data from Team Project failed: ".mysql_error());
					
					while($row = mysqli_fetch_assoc($getLatestActivity_result))
					{
						$who = $row["userID"];
						$ProjectID = $row["ProjectID"];
						$TaskID = $row["TaskID"];
						$Type = $row["Type"];
						$postID = $row["PostID"];
						$eventID = $row["EventID"];
						$ReviewID = $row["ReviewID"];
						$ticketID = $row["TicketID"];
						$Timestamp = $row["DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')"];
						$Activity = $row["Activity"];
						
						
						$userInfo = getUserInfo($who);
						$WhoFN = $userInfo["userFirstName"]." ".$userInfo["userLastName"];
						$WhoPP = $row["userPPLink"];
						$WhoUsername = $row["userUsername"];
						
						//$getUserInfo = "SELECT * FROM `user` WHERE `userID` = '$who'";
						//$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("getUserInfo to get data from Team Project failed: ".mysql_error());
						//while($row = $getUserInfo_result->fetch_assoc()) {	
						//	$WhoFN = $row["First Name"]." ".$row["Last Name"];
						//	$WhoPP = $row["PP Link"];
						//	$WhoUsername = $row["username"];
						//}
						
						
						$links;
						$moreInformation;
						$removeThis = '';
						
				$projectInfo = getProjectInfo($ProjectID);
	
							$projectTitle = addslashes($projectInfo["title"]);
							$projectTitleNoInsert = $projectInfo["title"];
							$projectDesc = $projectInfo["description"];
							$projectDueDate = $projectInfo["dueDate"];
							$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
							$projectDateCreated = $projectInfo["dateCreated"];
							$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
							$projectDateCompleted = $projectInfo["dateCompleted"];
							$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
							$projectStatus = $projectInfo["Status"];
							$projectCategoryID = $projectInfo["categoryID"];
							$projectCategoryTitle = $projectInfo["categoryTitle"];
							$projectTaskType = $projectInfo["taskType"];
							$projectOwnerUserID = $projectInfo["ownerUserID"];
							$projectOwnerFullName = $projectInfo["ownerFullName"];
							$projectOwnerPP = $projectInfo["ownerPP"];
								
								
							$moreInformation = '
								
								<h3>Project Details</h3>
								
							<div class="row">
									<div class="col-sm-6">
									<div class="formLabels">Title:</div>
										<p>'.$projectTitleNoInsert.'</p>
										<div class="formLabels">Category:</div>
										<p>'.$projectCategoryTitle.'</p>
										
									</div>
									<div class="col-sm-6">
										<div class="formLabels">Due Date:</div>
										<p>'.$projectDueDateDisplay.'</p>
										<div class="formLabels">Status:</div>
										<p>'.$projectStatus.'</p>
									</div>
									<div class="col-sm-12">
										<div class="formLabels">Description:</div>
										<p>'.$projectDesc.'</p>
									</div>
								</div>
							';
						
						$links ='
							<li><a href="/dashboard/team-projects/view/?projectID='.$ProjectID.'">View Project</a></li>';
						
						
						$doesExist = "SELECT COUNT(*) FROM `Team Projects` WHERE `ProjectID` = '$ProjectID'";
							$doesExist_result = mysqli_query($connection, $doesExist) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
							while($row = $doesExist_result->fetch_assoc()) {
								$doesExist = $row["COUNT(*)"];
							}
						
						
						if ($doesExist == 0) {
								$moreInformation = '<p class="doesntExist">This project has been deleted.</p>';
								$removeThis = 'style="display:none"';
							}
							else {
						
							$getCompletedCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$ProjectID'";
							$getCompletedCount_result = mysqli_query($connection, $getCompletedCount) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
							while($row = $getCompletedCount_result->fetch_assoc()) {
								$completedTaskCount = $row["COUNT(*)"];
							}
							$getTotalCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$ProjectID'";
							$getTotalCount_result = mysqli_query($connection, $getTotalCount) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
							while($row = $getTotalCount_result->fetch_assoc()) {
								$totalTaskCount = $row["COUNT(*)"];
							}
							
							if ($totalTaskCount == 0 && $completedTaskCount == 0) {
								$finalPercentage = 0;
							}
							else {
									$finalPercentage = round(($completedTaskCount/$totalTaskCount)*100);
							}
								
							
								
							}
						
					if ($Type == "Project") {
					
							
						}
						
					else if ($Type == "Tasks" || $Type == "Task") {
							
							
						}
						
					else if ($Type == "Membership") {
							
							
						}
						
					else if ($Type == "Copy") {
							
							
						}
						
					else if ($Type == "Note") {
							
							
							
						}
						
					else if ($Type == "File") {
							
							
						}
						
					else if ($Type == "Ticket") {
						
							$doesExist = "SELECT COUNT(*) FROM `Tickets` WHERE `TicketID` = '$ticketID'";
							$doesExist_result = mysqli_query($connection, $doesExist) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
							while($row = $doesExist_result->fetch_assoc()) {
								$doesExist = $row["COUNT(*)"];
							}
						
							if ($doesExist == 0) {
								$moreInformation = '<p class="doesntExist">This ticket has been deleted.</p>';
								$removeThis = 'style="display:none"';
							}
							else {
								$links ='
							<li><a href="/dashboard/requests/view/?ticketID='.$ticketID.'">View Ticket</a></li>
							';
								
								$ticketInfo = getTicketInfo($ticketID);
	
							$projectTitle = addslashes($ticketInfo["title"]);
							$projectTitleNoInsert = $ticketInfo["title"];
							$projectDesc = $ticketInfo["description"];
							$projectDueDate = $ticketInfo["dueDate"];
							$projectDueDateDisplay = $ticketInfo["dueDateDisplay"];
							$projectStatus = $ticketInfo["Status"];
							$projectCategoryTitle = $ticketInfo["categoryTitle"];
								
								
							$moreInformation = '
								
								<h3>Ticket Details</h3>
								
							<div class="row">
									<div class="col-sm-6">
									<div class="formLabels">Title:</div>
										<p>'.$projectTitleNoInsert.'</p>
										<div class="formLabels">Category:</div>
										<p>'.$projectCategoryTitle.'</p>
										
									</div>
									<div class="col-sm-6">
										<div class="formLabels">Due Date:</div>
										<p>'.$projectDueDateDisplay.'</p>
										<div class="formLabels">Status:</div>
										<p>'.$projectStatus.'</p>
									</div>
									<div class="col-sm-12">
										<div class="formLabels">Description:</div>
										<p>'.$projectDesc.'</p>
									</div>
								</div>
							';
							
							}
						}
					
					else if ($Type == "Event") {
						
						
						$doesExist = "SELECT COUNT(*) FROM `calendar` WHERE `id` = '$eventID'";
							$doesExist_result = mysqli_query($connection, $doesExist) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
							while($row = $doesExist_result->fetch_assoc()) {
								$doesExist = $row["COUNT(*)"];
							}
						
							if ($doesExist == 0) {
								$moreInformation = '<p class="doesntExist">This event has been deleted.</p>';
								$removeThis = 'style="display:none"';
							}
							else {
								$removeThis = 'style="display:block"';
						$eventInfo = getEventInfo($eventID);
	
	$eventTitle = addslashes($eventInfo["title"]);
	$eventTitleNoInsert = $eventInfo["title"];
	$eventDesc = addslashes($eventInfo["description"]);
	$eventDescNoInsert = $eventInfo["description"];
	$eventStartDate = $eventInfo["startDate"];
	$eventStartDateDisplay = $eventInfo["startDateDisplay"];
	$eventEndDateDisplay = $eventInfo["endDateDisplay"];
	$eventCategoryID = $eventInfo["categoryID"];
	$eventCategoryTitle = $eventInfo["categoryTitle"];
	$eventProjectID = $eventInfo["projectID"];
	$eventTaskID = $eventInfo["taskID"];
	$eventOwnerUserID = $eventInfo["ownerUserID"];
	$eventOwnerFullName = $eventInfo["ownerFullName"];
	$eventOwnerPP = $eventInfo["ownerPP"];
	$eventDesktopImagePath = $eventInfo["desktopImagePath"];
	$eventMobileImagePath = $eventInfo["mobileImagePath"];		
								
	if (isset($eventDesktopImagePath) && $eventDesktopImagePath !== '') {
		$whichImage = '<img src="/dashboard/content-calendar/'.$eventDesktopImagePath.'">';
	}	
	else if (isset($eventMobileImagePath) && $eventMobileImagePath !== '') {
		$whichImage = '<img src="/dashboard/content-calendar/'.$eventMobileImagePath.'">';
	}
	else {
			$whichImage = '';
	}
								
								$links ='
							<li><a href="/dashboard/content-calendar/?eventID='.$eventID.'">View Event</a></li>';
								
							$moreInformation = '
								
								<div class="newsfeedImage">'.$whichImage.'</div>
								
								
								<h3>Event Details</h3>
								
							<div class="row">
									<div class="col-sm-6">
									<div class="formLabels">Title:</div>
										<p>'.$eventTitleNoInsert.'</p>
										<div class="formLabels">Category:</div>
										<p>'.$eventCategoryTitle.'</p>
										
									</div>
									<div class="col-sm-6">
										<div class="formLabels">Start Date:</div>
										<p>'.$eventStartDateDisplay.'</p>
										
										<div class="formLabels">End Date:</div>
										<p>'.$eventEndDateDisplay.'</p>
									</div>
									<div class="col-sm-12">
										<div class="formLabels">Description:</div>
										<p>'.$eventDescNoInsert.'</p>
									</div>
								</div>
							';	
								
													
								
					

							}
					}
					
					else if ($Type == "Knowledge Center") {
						
						$doesExist = "SELECT COUNT(*) FROM `Knowledge Center` WHERE `PostID` = '$postID'";
							$doesExist_result = mysqli_query($connection, $doesExist) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
							while($row = $doesExist_result->fetch_assoc()) {
								$doesExist = $row["COUNT(*)"];
							}
						
							if ($doesExist == 0) {
								$moreInformation = '<p class="doesntExist">This post has been deleted.</p>';
								$removeThis = 'style="display:none"';
							}
							else {
$removeThis = '';
						$links ='
						<li><a href="/dashboard/knowledge-center/post/?ID='.$postID.'">View Post</a></li>
						';
								
								
								
								
								
								
						$postInfo = getKCPostInfo($postID);
	
							$projectTitle = $postInfo["postTitle"];
							$projectDesc = $postInfo["postDescription"];
							$projectDueDateDisplay = $postInfo["postCreatedDate"];
							$projectCategoryTitle = $postInfo["postCategory"];
								
								
							$moreInformation = '
								
								<h3>Post Details</h3>
								
							<div class="row">
									<div class="col-sm-6">
									<div class="formLabels">Title:</div>
										<p>'.$projectTitle.'</p>
										<div class="formLabels">Category:</div>
										<p>'.$projectCategoryTitle.'</p>
										
									</div>
									<div class="col-sm-6">
										<div class="formLabels">Created Date:</div>
										<p>'.$projectDueDateDisplay.'</p>
										
									</div>
								</div>
							';
					}
					}
					
					else if ($Type == "Review") {
						
						$doesExist = "SELECT COUNT(*) FROM `Tickets Review` WHERE `ReviewID` = '$ReviewID'";
						$doesExist_result = mysqli_query($connection, $doesExist) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
						while($row = $doesExist_result->fetch_assoc()) {
							$doesExist = $row["COUNT(*)"];
						}

						if ($doesExist == 0) {
							$moreInformation = '<p class="doesntExist">This review has been deleted.</p>';
							$removeThis = 'style="display:none"';
						}
						else {
							$removeThis = '';
							
							$reviewInfo = getReviewInfo($ReviewID);
	
							$reviewTitle = $reviewInfo["title"];
							$reviewDesktopImageID = $reviewInfo["desktopImageID"];
							$reviewDesktopImage = $reviewInfo["desktopImage"];
							$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
							$reviewMobileImageID = $reviewInfo["mobileImageID"];
							$reviewMobileImage = $reviewInfo["mobileImage"];
							$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
							$reviewDueDate = $reviewInfo["dueDate"];
							$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
							$reviewDateCreated = $reviewInfo["dateCreated"];
							$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
							$reviewStatus = $reviewInfo["Status"];
							$reviewTypeTitle = $reviewInfo["Type"];
							$reviewProjectID = $reviewInfo["projectID"];
							$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
							$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
							$reviewOwnerUserID = $reviewInfo["ownerUserID"];
							$reviewOwnerFullName = $reviewInfo["ownerFullName"];
							$reviewOwnerPP = $reviewInfo["ownerPP"];
							
							
							if (isset($reviewDesktopImage) && $reviewDesktopImage !== '') {
								$whichImage = '<img src="/dashboard/team-projects/view/review/'.$reviewDesktopImage.'">';
							}	
							else if (isset($reviewMobileImage) && $reviewMobileImage !== '') {
								$whichImage = '<img src="/dashboard/team-projects/view/review/'.$reviewMobileImage.'">';
							}
							else {
									$whichImage = '';
							}
								
								
								
							$moreInformation = '
								
								<div class="newsfeedImage">'.$whichImage.'</div>
								
								
								<h3>Review Details</h3>
								
							<div class="row">
									<div class="col-sm-6">
									<div class="formLabels">Title:</div>
										<p>'.$reviewTitle.'</p>
										<div class="formLabels">Type:</div>
										<p>'.$reviewTypeTitle.'</p>
										
									</div>
									<div class="col-sm-6">
										<div class="formLabels">Due Date:</div>
										<p>'.$reviewDueDateDisplay.'</p>
										
									</div>
									
								</div>
							';	
								
						
							
							
							
							
							
							$links ='
							<li><a href="/dashboard/team-projects/view/review/?reviewID='.$ReviewID.'">View Review</a></li>
							<li><a href="/dashboard/team-projects/view/?projectID='.$reviewProjectID.'">View Project</a></li>
							';
						}


					}
						
					else  {
							
							
							$moreInformation = $Type;
						$removeThis = 'style="display:none"';
						}
						
					$userInfo = getUserInfo($who);
						$WhoFN = $userInfo["userFirstName"]." ".$userInfo["userLastName"];
						$WhoPP = $userInfo["userPPLink"];
						$WhoUsername = $userInfo["userUsername"];
						
						if (!isset($WhoPP)) {
							$WhoPP = '/dashboard/images/ticket.png';	
						}
						
						
						if (!isset($who)) {
							$remove = 'style="display:none;"';
							$removeMargin = 'style="margin-left:15px;"';
						}
						else {
							$remove = '';
							$removeMargin = '';
						}
						
						if ($who == 0 && $Type !== "Ticket") {
							$WhoPP = '/dashboard/images/comment.png';	
							$remove = 'style="display:none;"';
							$removeMargin = 'style="margin-left:15px;"';
						}
						else if ($who == 0 && $Type == "Ticket") {
							$WhoPP = '/dashboard/images/ticket.png';	
							$remove = 'style="display:none;"';
							$removeMargin = 'style="margin-left:15px;"';
						}
						else {
							$remove = '';
							$removeMargin = '';
						}
						
						
						
						
						
						
						$newsfeedItems[] = '
						<div class="newsfeed-item">
							<div class="header">
								<div class="picture">
									<a href="/dashboard/users/profile/?userID='.$who.'"><img src="'.$WhoPP.'" class="newsfeed-item-pp"></a>
								</div>
								<div class="info">
									<div class="ownerUsername">@'.$WhoUsername.'</div>
									<div class="date" '.$removeMargin.'>'.$Timestamp.'</div>
								</div>
								<div class="cta">
									<div class="actions pull-right" '.$removeThis.'>
										<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
									</div>
									<div class="actionMenu">
										<ul>
											'.$links.'
										</ul>
									</div>
								</div>
								
							</div>
							<div class="content">
								<div class="news"><div class="owner">'.$WhoFN.'</div> '.$Activity.'</div>
								<hr>
								<div class="moreInfo">
									'.$moreInformation.'
								</div>
							
							</div>
							
						</div>
						';
						
					}
	
		return $newsfeedItems;
	}


function getTaskAssignedToCountInProjectForMember($memberUserID,$projectID) {
	
	global $connection;
	
	$query = "SELECT `TaskID` FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `userID`='$memberUserID'";
	$query_result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	while($row = $query_result->fetch_assoc()) {
		$taskAssignedToArray[] = $row["TaskID"];
	}
	$out["taskIDs"] = $taskAssignedToArray;
	
	$query2 = "SELECT COUNT(`TaskID`) FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `userID`='$memberUserID'";
	$query2_result = mysqli_query($connection, $query2) or die(mysqli_error($connection));
	while($row = $query2_result->fetch_assoc()) {
		$out["taskCount"] = $row["COUNT(`TaskID`)"];
	}
		
	return $out;
}

function getTaskRequestedByCountInProjectForMember($memberUserID,$projectID) {
	
	global $connection;
	
	$query = "SELECT `TaskID` FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `Requested By`='$memberUserID'";
	$query_result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	while($row = $query_result->fetch_assoc()) {
		$taskRequestedByArray[] = $row["TaskID"];
		
	}
	
	$out["taskIDs"] = $taskRequestedByArray;
	
	$query2 = "SELECT COUNT(`TaskID`) FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `Requested By`='$memberUserID'";
	$query2_result = mysqli_query($connection, $query2) or die(mysqli_error($connection));
	while($row = $query2_result->fetch_assoc()) {
		$out["taskCount"] = $row["COUNT(`TaskID`)"];
	}
	
	return $out;
}

function getUserTags($searchTerm){
	global $connection;
	global $userID;
	
	$query ="SELECT DISTINCT `username`,`userID` FROM `user` WHERE `username` LIKE '%$searchTerm%' AND `userID` != '$userID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	while ($row = mysqli_fetch_array($query_result)) {
	$foundUsernames[] = "<div class='userTags' userID=".$row['userID'].">".$row['username']."</div>";

	}
	return $foundUsernames;
}

function getUserTagsNotInProject($searchTerm,$projectID){
	global $connection;
	
	$query ="SELECT DISTINCT `username`,`userID` FROM `user` WHERE `username` LIKE '%$searchTerm%' AND `userID` NOT IN (SELECT `userID` FROM `Team Projects Member List` WHERE `ProjectID` ='$projectID')";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	while ($row = mysqli_fetch_array($query_result)) {
	$foundUsernames[] = "<div class='userTags' userID=".$row['userID'].">".$row['username']."</div>";

	}
	return $foundUsernames;
}

function getUserTagsNotInReview($searchTerm,$reviewID){
	global $connection;
	global $userID;
	
	$query ="SELECT DISTINCT `username`,`userID` FROM `user` WHERE `username` LIKE '%$searchTerm%' AND `userID` NOT IN (SELECT `userID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID') AND `userID` != '$userID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	while ($row = mysqli_fetch_array($query_result)) {
	$foundUsernames[] = "<div class='userTags' userID=".$row['userID'].">".$row['username']."</div>";

	}
	return $foundUsernames;
}

function getKCTagsInCatNotInPost($searchTerm,$postID,$postCategoryID){
	global $connection;
	
	$query ="SELECT DISTINCT `Tag` FROM `Knowledge Center Tags` WHERE `Tag` LIKE '%$searchTerm%' AND `KC CategoryID`='$postCategoryID' AND `PostID`!='$postID' ORDER BY `Tag` DESC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	while ($row = mysqli_fetch_array($query_result)) {
	$foundTags[] = "<div class='KCTags' tagID=".$row['KC TagID'].">".$row['Tag']."</div>";

	}
	
	return $foundTags;
}

function getKCTagsInCat($searchTerm,$postCategoryID){
	global $connection;
	
	$query ="SELECT DISTINCT `Tag` FROM `Knowledge Center Tags` WHERE `Tag` LIKE '%$searchTerm%' AND `KC CategoryID`='$postCategoryID' ORDER BY `Tag` DESC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	while ($row = mysqli_fetch_array($query_result)) {
	$foundTags[] = "<div class='KCTags' tagID=".$row['KC TagID'].">".$row['Tag']."</div>";

	}
	
	return $foundTags;
}

function getTicketInfoFromProject($projectID) {
	global $connection;
	
	$getAll = "SELECT 
	`Tickets`.`TicketID`, 
	`Tickets`.`Title`, 
	`Description`, 
	`Copy`, 
	`URL`, 
	`Tickets`.
	`Timestamp`, 
	DATE_FORMAT(`Tickets`.`Due Date`,'%Y-%m-%dT%H:%i:%s') AS 'Due Date', 
	`Requested By`, 
	`Owner`, 
	`Tickets`.`Status`, 
	`ProjectID`, 
	`Team Projects Categories`.`Category`,
	`Team Projects Categories`.`ProjectCategoryID`, 
	`First Name`, 
	`Last Name`, 
	`PP Link` 
	FROM `Tickets` 
	JOIN `Team Projects Categories` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` 
	JOIN `user` ON `Tickets`.`Requested By` = `user`.`userID` WHERE `Tickets`.`ProjectID` = '$projectID' AND `Team Projects Categories`.`GroupID` = '$groupID'";
	
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getAll_result->fetch_assoc()) {
        $out["ticketID"] = $row["TicketID"];
		$out["title"] = $row["Title"];
		$out["description"]= $row["Description"];
		$out["dueDate"] = $row["Due Date"];
		$out["dueDateDisplay"] = date("m/d/Y", $row["Due Date"]);
		$out["Status"] = $row["Status"];
		$out["categoryID"] = $row["ProjectCategoryID"];
		$out["categoryTitle"] = $row["Category"];
		$out["URL"] = $row["URL"];
		$out["requestedByUserID"] = $row["RequestedBy"];
		$out["requestedByFullName"] = $row["First Name"].' '.$row["Last Name"];
		$out["requestedByPP"] = $row["PP Link"];
		$out["Timestamp"] = $row["Timestamp"];
		$out["Copy"] = $row["Copy"];
	}
	
	return $out;
}

function getTicketInfo($ticketID) {
	global $connection;
	global $groupID;
	
	$getAll = "SELECT 
	`TicketID`, 
	`Tickets`.`Title`, 
	`Description`, 
	`Copy`, 
	`URL`, 
	`Timestamp`, 
	`Due Date`,
	 DATE_FORMAT(`Tickets`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Ticket Due Date Display',
	`Requested By`, 
	`Owner`, 
	`Status`, 
	`Tickets`.`ProjectID`, 
	`Team Projects Categories`.`Category`,
	`Team Projects Categories`.`ProjectCategoryID`, 
	`First Name`, 
	`Last Name`, 
	`PP Link` 
	FROM `Tickets` 
	JOIN `Team Projects Categories` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` 
	JOIN `user` ON `Tickets`.`Requested By` = `user`.`userID` WHERE `Tickets`.`TicketID` = '$ticketID'";
	
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getAll_result->fetch_assoc()) {
        $out["projectID"] = $row["ProjectID"];
		$out["title"] = $row["Title"];
		$out["description"]= $row["Description"];
		$out["dueDate"] = $row["Due Date"];
		$out["dueDateDisplay"] = $row["Ticket Due Date Display"];
		$out["Status"] = $row["Status"];
		$out["categoryID"] = $row["ProjectCategoryID"];
		$out["categoryTitle"] = $row["Category"];
		$out["URL"] = $row["URL"];
		$out["requestedByUserID"] = $row["Requested By"];
		$out["requestedByFullName"] = $row["First Name"].' '.$row["Last Name"];
		$out["requestedByPP"] = $row["PP Link"];
		$out["ownerUserID"] = $row["Owner"];
		$out["Timestamp"] = $row["Timestamp"];
		$out["Copy"] = $row["Copy"];
	}
	
	return $out;
}

function getTaskComments($taskID) {
	global $connection;
	global $userID;
	global $groupID;
	global $myRole;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
		
	$projectInfo = getProjectInfo($taskProjectID);
	$projectCreatedByGroupID = $projectInfo["ownerGroupID"];
	
	$getAll = "SELECT `CommentID`,`Message`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `Task Comments`.`userID`, `ProjectID`, `TaskID`, `Sent By`, `username`, `PP Link` FROM `Task Comments` JOIN `user` ON `Task Comments`.`Sent By` = `user`.`userID` WHERE `TaskID` = '$taskID' ORDER BY `Timestamp` DESC";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        	$whoSentCom = $row["Sent By"];
			$ProjectIDCom = $row["ProjectID"];
			$TimestampCom = $row["DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')"];
			$MessageCom = $row["Message"];
			$MessageIDCom = $row["CommentID"];
			$WhoSentFNCom = $row["username"];
			$ppLink = $row["PP Link"];
		
		
			if ($whoSentCom != $userID){
				$messageCSS = "incomingCom";
				$commentArray[] = "<table class='comments $messageCSS' id='$MessageIDCom'><tr><td style='border:0px !important;' class='sender'><img class='commentsImage' src='$ppLink'></td><td style='border:0px !important;width: 100% !important;'><span>@$WhoSentFNCom</span><div class='timestamp'>$TimestampCom</div></td></tr><tr><td colspan='2'><pre class='message'>$MessageCom</pre><div class='removeNoteContainer'><div class='removeNote' commentid='$MessageIDCom' taskid='$taskID'><br><i class='fa fa-trash' aria-hidden='true'></i></div></div></td></tr></table>";
			}
			else {
				$messageCSS = "outgoingCom";
				$commentArray[] = "<table class='comments $messageCSS' id='$MessageIDCom'><tr><td style='border:0px !important;width: 100% !important;'><span>@$WhoSentFNCom</span><div class='timestamp'>$TimestampCom</div></td><td style='border:0px !important;' class='sender'><img class='commentsImage' src='$ppLink'></td></tr><tr><td colspan='2'><pre class='message'>$MessageCom</pre><div class='removeNoteContainer'><div class='removeNote' commentid='$MessageIDCom' taskid='$taskID'><br><i class='fa fa-trash' aria-hidden='true'></i></div></div></td></tr></table>";
			}
		
			$out["printComments"] = $commentArray;
	
	}
	
	if ($userID == $taskRequestedByUserID || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $projectCreatedByGroupID) || $userID == $taskAssignedToUserID) {
		$out["canComment"] = "<div id='newCommentContainer'><hr><table style='width: 100%;'><tr><td><pre style='width: 100%;'><textarea id='newComment' rows='1' placeholder='Message...'></textarea></pre></td><td><button id='addNewComment' class='smallSend' style='margin-top: -10px;' taskid='$taskID'><i class='fa fa-paper-plane' aria-hidden='true'></i></button></td></tr></table></div>";
	}
	else {
		$out["canComment"] = "";
	}
	
	return $out;

}

function getTimelineStatus($taskID) {
	
	global $connection;
	global $userID;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	$taskDaysLeftToComplete = $taskInfo["daysLeftToComplete"];
	
	
	//if task type is standard
	if ($taskProjectTaskType == "Standard") {
		if ($taskStatus !== "Completed") {

		//if user has 1+ days to complete - on track
			if ($taskDaysLeftToComplete >= 1 ) {
			$timelineStatus	='<div class="timelineStatus green"><span class="dotWack green_bg"></span>On Track</div>';
			}

			//if user has 1 day or no days to complete - at risk
			else if ($taskDaysLeftToComplete < 1 && $taskDaysLeftToComplete >= 0) {
				$timelineStatus	='<div class="timelineStatus orange"><span class="dotWack orange_bg"></span>At Risk</div>';
			}
			//else user is overdue, - off track
			else {
				$timelineStatus	='<div class="timelineStatus red"><span class="dotWack red_bg"></span>Off Track</div>';
			}
		
		}
		else {
			$timelineStatus	='<div class="timelineStatus"></div>';
		}
	}
	// else type is cadence
	else {
		if ($taskStatus !== "Completed") {
		
		//get previous task
		$query2 = "SELECT `TaskID`, `Status` FROM `Tasks` WHERE `Due Date` < '$taskDueDate' AND `ProjectID`='$taskProjectID' AND `TaskID`!='$taskID' ORDER BY `TaskID` LIMIT 1";
				$query2_result = mysqli_query($connection, $query2) or die ("NEw Query to get data from Team Project failed: ".mysql_error());
				while($row = $query2_result->fetch_assoc()) {
					$prevTaskID = $row["TaskID"];	
					$prevTaskStatus = $row["Status"];
		}
		
		//if prev task exists
			if (isset($prevTaskID)) {
				
				//getting prev days to complete
			$query3 = "SELECT datediff(`Due Date`, now()) AS 'Days Left To Complete' FROM `Tasks` WHERE `TaskID` =  '$prevTaskID'";
			$query3_result = mysqli_query($connection, $query3) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

				while($row = $query3_result->fetch_assoc()) {
				$prevTaskDaysLeftToComplete = $row["Days Left To Complete"];
				}

				//if prev task status is incomplete, get prev task days to complete
				if ($prevTaskStatus !== "Completed") {

					//if user has 1+ days and the previous task has 1+ days to complete - on track
					if (
						($taskDaysLeftToComplete >= 1 && $prevTaskDaysLeftToComplete >= 1) || 
						(!isset($prevTaskDaysLeftToComplete) && $taskDaysLeftToComplete >= 1) || 
						$taskDaysLeftToComplete >= 1
					) {
					$timelineStatus	='<div class="timelineStatus green"><span class="dotWack green_bg"></span>On Track</div>';
					}

					//if user task is due today and the previous task is due today - at risk
					else if (
						($taskDaysLeftToComplete == 0 && $prevTaskDaysLeftToComplete == 0 ) || 
						(!isset($prevTaskDaysLeftToComplete) || ($taskDaysLeftToComplete == 0)) || 
						($taskDaysLeftToComplete >= 1 && $prevTaskDaysLeftToComplete <= 0)
					) {
						$timelineStatus	='<div class="timelineStatus orange"><span class="dotWack orange_bg"></span>At Risk</div>';
					}
					
					//else user is overdue, - off track
					else {
						$timelineStatus	='<div class="timelineStatus red"><span class="dotWack red_bg"></span>Off Track</div>';
					}
				}
				else {
					if ($taskDaysLeftToComplete >= 1 ) {
					$timelineStatus	='<div class="timelineStatus green"><span class="dotWack green_bg"></span>On Track</div>';
					}

					//if user has 1 day or no days to complete - at risk
					else if ($taskDaysLeftToComplete < 1 && $taskDaysLeftToComplete >= 0) {
						$timelineStatus	='<div class="timelineStatus orange"><span class="dotWack orange_bg"></span>At Risk</div>';
					}
					//else user is overdue, - off track
					else {
						$timelineStatus	='<div class="timelineStatus red"><span class="dotWack red_bg"></span>Off Track</div>';
					}
				}
			}
			else {
				
			//if user has 1+ days to complete - on track
				if ($taskDaysLeftToComplete >= 1 ) {
					$timelineStatus	='<div class="timelineStatus green"><span class="dotWack green_bg"></span>On Track</div>';
					}

					//if user has 1 day or no days to complete - at risk
					else if ($taskDaysLeftToComplete < 1 && $taskDaysLeftToComplete >= 0) {
						$timelineStatus	='<div class="timelineStatus orange"><span class="dotWack orange_bg"></span>At Risk</div>';
					}
					//else user is overdue, - off track
					else {
						$timelineStatus	='<div class="timelineStatus red"><span class="dotWack red_bg"></span>Off Track</div>';
					}
		
			}

		
	}
	else {
		$timelineStatus	='<div class="timelineStatus"></div>';
	}
	}
	
	
	return $timelineStatus;
}

function getReviewTimelineStatus($reviewID) {
	
	global $connection;
	global $userID;
	
	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewStatus = $reviewInfo["Status"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	$reviewDaysLeftToComplete = $reviewInfo["daysLeftToComplete"];
	
	
	//if review is not approved
	if ($reviewStatus !== "Approved") {

		//if user has 1+ days to complete - on track
			if ($reviewDaysLeftToComplete >= 1 ) {
			$timelineStatus	='<div class="timelineStatus green"><span class="dotWack green_bg"></span>On Track</div>';
			}

			//if user has 1 day or no days to complete - at risk
			else if ($reviewDaysLeftToComplete == 0) {
				$timelineStatus	='<div class="timelineStatus orange"><span class="dotWack orange_bg"></span>At Risk</div>';
			}
			//else user is overdue, - off track
			else {
				$timelineStatus	='<div class="timelineStatus red"><span class="dotWack red_bg"></span>Off Track</div>';
			}
		
		}
		else {
			$timelineStatus	='<div class="timelineStatus"></div>';
		}
	
	
	
	
	
	return $timelineStatus;
}



//********* FUNCTIONS FOR PROJECTS *********

//**PROJECT

function addBlankProject($title, $desc, $dueDate, $template, $url, $categoryID, $folderlink, $visibility, $taskType, $copy, $ticketID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	$title = addslashes($title);
	$description = addslashes($desc);
	$copy = addslashes($copy);
	$folderlink = addslashes($folderlink);
	$url = addslashes($url);
	
	//inserting record
	$addProject = "INSERT INTO `Team Projects`(`Status`,`Title`, `Description`, `Category`, `Due Date`, `userID`, `Visible`,`Project Folder Link`,`URL To Use`,`Task Type`,`Copy`) VALUES ('Incomplete','$title','$description','$categoryID','$dueDate','$userID','$visibility','$folderLink','$url','$taskType','$copy')";
	$addProject_result = mysqli_query($connection, $addProject) or die(mysqli_error($connection));
	//end project title
	
	$projectID = mysqli_insert_id($connection);
		
		
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "created a new project.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());			
		
	//initally insert Project Creator Into Member List
	$InsertCreatortoMemberList = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$userID')";
    $InsertCreatortoMemberList_result = mysqli_query($connection, $InsertCreatortoMemberList) or die(mysqli_error($connection));	
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/uploads/'.$projectID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	
	//updating with ticket if it exists
	if ($ticketID !== "") {
		
		//update ticket 
		$updateTicket = "UPDATE `Tickets` SET `ProjectID`='$projectID',`Status`='In Progress' WHERE `TicketID` ='$ticketID'";
		$updateTicket_result = mysqli_query($connection, $updateTicket) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	
	
	return $projectID;
}

function addTemplateProject($title, $desc, $dueDate, $template, $url, $folderlink, $copy, $ticketID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;

	$titleNoInsert = $title;
	$title = addslashes($title);
	$descriptionNoInsert = $desc;
	$description = addslashes($desc);
	$copyNoInsert = $copy;
	$copy = addslashes($copy);

	$query2 = "SELECT * FROM `Team Projects Templates` WHERE `TemplateID` ='$template'";
		$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query2_result->fetch_assoc()) {
			$templateName=$row["Name"];
			$templateCategory=$row["Category"];
			$templateVisible=$row["Visible"];
			$templateTaskType=$row["Task Type"];
	}
	if ($templateDaysToComplete > 1 || $templateDaysToComplete == 0) {
		$s = 's';	
	}
	else {
		$s = '';	
	}
	
	//inserting record
	$addProject = "INSERT INTO `Team Projects`(`Status`,`Title`, `Description`, `Category`, `Due Date`, `userID`, `Visible`,`Project Folder Link`,`URL To Use`, `Task Type`,`Copy`) VALUES ('Incomplete','$title','$description','$templateCategory','$dueDate','$userID','$templateVisible','$folderLink','$url','$templateTaskType','$copy')";
	$addProject_result = mysqli_query($connection, $addProject) or die(mysqli_error($connection));
	
	$projectID = mysqli_insert_id($connection);
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "created a new project.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());		
	
	
	
	//getting membership statements
	$query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$template' AND `Type` = 'Membership'";
		$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$statements[]=$row["StatementID"];
			$memberIDs[]=$row["Value"];
	}
	
	foreach ($memberIDs as $individual) {
	if ($individual == $userID) {
		
	}
	else {
		
	$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>You have been added to the project: <strong>$title</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Membership','$individual','$projectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	//Getting ADDEE'S name
	$getAddeeUsername = "SELECT `First Name`,`Last Name` FROM `user` WHERE `userID` = '$individual'";
	$getAddeeUsername_result = mysqli_query($connection, $getAddeeUsername) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getAddeeUsername_result->fetch_assoc()) {
			$AddeeFullName = $row["First Name"]." ".$row["Last Name"];
	}
	$activity = "added <strong>$AddeeFullName</strong> to the project.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Membership','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	
	
	}
		
	
		
		}
	
	foreach($statements as $statement){
		
		$query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `StatementID` ='$statement'";
		$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$memberUserID=$row["Value"];
		}
		if ($memberUserID == $userID) {
			//inserting self into membership
			$insertMe = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$userID')";
		mysqli_query($connection, $insertMe) or die ("Query to get data from Team task failed: ".mysql_error());
		}
		else {
			$newCall = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$memberUserID')";
		mysqli_query($connection, $newCall) or die ("Query to get data from Team task failed: ".mysql_error());
		}
		
		

	}
	
	//getting task statements
	$query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$template' AND `Type` = 'Task' ORDER BY `StatementID` ASC";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$statements2[]=$row["StatementID"];
	}
	
	$startDate = time();
	foreach($statements2 as $statement2){
		
		$query3 = "SELECT `StatementID`, `TemplateID`, `Type`, `Value`, `Task Type`, `Category`, `Task Duration`, `CalendarCategoryID` FROM `Team Projects Templates Statements` LEFT JOIN `Task Categories` ON `Task Categories`.`CategoryID` =`Team Projects Templates Statements`.`Task Type` WHERE `StatementID` ='$statement2'";
		$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$memberUserID2=$row["Value"];
			$taskCategoryID=$row["Task Type"];
			$taskCategory=$row["Category"];
			$taskDuration=$row["Task Duration"];
			$taskType=$row["Task Type"];
			$taskCalendarCategoryID=$row["CalendarCategoryID"];
		}
		
		if ($taskDuration > 1 || $taskDuration == 0) {
		$s = 's';	
		}
		else {
			$s = '';	
		}
		
		$taskDueDate = date('Y-m-d H:i:s', strtotime('+'.$taskDuration.' day'.$s.'', $startDate));
		
		//if a task due date lands on a weekend...
			if (date('N', strtotime($taskDueDate)) >= 6){
				
					//setting date to the following monday
				$taskDueDate = date('Y-m-d H:i:s', strtotime('next Monday', $startDate));
				
					//if task due date is after the project due date, set the task due date to the project due date
					if ($taskDueDate > $dueDate) {
						$finalTaskDueDate = $dueDate;

					}
					//else the task due date stands... 
					else {
						$finalTaskDueDate = $taskDueDate;
					
					}
			}
			//else not a weekend - task due date stands
			else {
				
				//if task due date is after the project due date, set the task due date to the project due date
					if ($taskDueDate > $dueDate) {
						$finalTaskDueDate = $dueDate;

					}
					//else the task due date stands
					else {
						$finalTaskDueDate = $taskDueDate;
					}
			}	
		
		
		
		
		
		if ($taskCategoryID == "7") {
			$finalTaskDueDate = $dueDate;
			$launchTitle = $title." ";
		}
		else {
			$launchTitle = "";
		}
		if ($templateTaskType == "Standard") {
			$finalTaskDueDate = $dueDate;
		}

		if ($taskCalendarCategoryID == null) {
			$newCall = "INSERT INTO `Tasks`(`Title`, `Due Date`, `Category`, `Requested By`, `ProjectID`, `userID`) VALUES ('$launchTitle$taskCategory','$finalTaskDueDate','$taskCategoryID','$userID','$projectID','$memberUserID2')";
		mysqli_query($connection, $newCall) or die ("Query to get data from Team task failed: ".mysql_error());
			
			if ($taskCategoryID == "7") {
				$taskID = mysqli_insert_id($connection);
			}
			else {
				$previousTaskEntryID = mysqli_insert_id($connection);
			}
			
			/////////// INSERTING NOTIFICATION ///////////
	if ($memberUserID2 !== $userID) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>A new task: <strong>$taskCategory</strong> has been assigned to you in: <strong>$title</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$memberUserID2','$projectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());	
	}
	
	
	//////// SENDING EMAIL ///////	
		
	if (isset($memberUserID2) && $memberUserID2 !== $userID) {
	
	//getting info
	$userInfo = getUserInfo($memberUserID2);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	$subject = "You have been assigned a new task in the project: ".$titleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Welcome to the project <span style="text-decoration: underline">'.$titleNoInsert.'</span>! A new task has been assigned to you. 
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategory.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($finalTaskDueDate)).'</p>
				 
             <br><h2>'.$taskCategory.'</h2>
            
             </p></div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$projectID.'" class="button">View Project</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		
		if ($memberUserID2 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}			
			
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	//Getting ADDEE'S name
	$getAddeeUsername = "SELECT `First Name`,`Last Name` FROM `user` WHERE `userID` = '$memberUserID2'";
	$getAddeeUsername_result = mysqli_query($connection, $getAddeeUsername) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getAddeeUsername_result->fetch_assoc()) {
			$AddeeFullName = $row["First Name"]." ".$row["Last Name"];
	}
	
	$activity = "created a new task: <em>$taskCategory</em> assigned to <strong>@$AddeeFullName</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Tasks','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
			
		
		
		}
		else {
		
			$newCall2 = "INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`,`TaskID`,`ProjectID`) VALUES('$title','$dueDate','$dueDate','$taskCalendarCategoryID','$userID','$description','false','$taskID','$projectID')";
		mysqli_query($connection, $newCall2) or die ("Query to get data from Team task failed: ".mysql_error());
		
			$eventID = mysqli_insert_id($connection);
			
			////////// CREATING FILE UPLOAD FOLDER //////////
			$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID;

			mkdir($path, 0777, true);
			chmod($path, 0777);
			
			////////// NOTIFICATIONS //////////
			//Getting project members
			$getGroupMembers = "SELECT `userID`, `Calendar Categories`.`Category` FROM `Calendar Categories` LEFT JOIN `Notification Subscription` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Calendar Categories`.`CalendarCategoryID` = '$taskCalendarCategoryID' AND `userID` != '$userID'";
			$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
			
			
				while($row = mysqli_fetch_array($getGroupMembers_result)) {
					if (isset($row["userID"])) {
						$groupMembers[] =$row["userID"];	
					}
					else {
						unset($groupMembers);	
					}
					
					$categoryName =$row["Category"];	
				}
			
			/////////// INSERTING ACTIVITY /////////////
			$activity = "added the <strong>$categoryName</strong> event: <strong>$title</strong> to the Content Calendar.";
			$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
			$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());

			if (!empty($groupMembers)) {
				foreach ($groupMembers as $name2) {
				$notification = "<a href=/dashboard/content-calendar/?eventID=$eventID>The <strong>$categoryName</strong> event: <strong>$title&nbsp;</strong>has been added to the Content Calendar.</a>";
				$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name2','$eventID')";
				$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
				
				
				//////// SENDING EMAIL ///////	
		
	if (isset($name2) && $name2 != $userID) {
	
	//getting info
	$userInfo = getUserInfo($name2);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	$subject = "New Content Calendar ".$categoryName." Event: ".$titleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new '.$categoryName.' event has been added to the Content Calendar.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              <br>'.$FN.' '.$LN.' added the following '.$categoryName.' event to the Content Calendar.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$categoryName.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($finalTaskDueDate)).'</p>
				 
             <br><h2>'.$titleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/content-calendar/?eventID='.$eventID.'" class="button">View Event</a>
              <br><br>
			  <br>
		 <a href="https://dashboard.coat.com/dashboard/content-calendar/unsubscribe/?categoryID='.$taskCalendarCategoryID.'" style="text-decoration:underline !important;font-size:12px !important;">Unsubscribe from <strong>'.$categoryName.'</strong> Calendar Event alerts.</a>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
				
				
				
				
				}
			}
			else {
				
			}
			
			

		
		}
		
	$startDate = strtotime($finalTaskDueDate);
			
	}
	
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/uploads/'.$projectID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	

	///////// EMAILS //////////
	$query33 = "SELECT COUNT(*) FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$template' AND `Type` = 'Task'";
	$query33_result = mysqli_query($connection, $query33) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query33_result->fetch_assoc()) {
			$row_count=$row["COUNT(*)"];
	}
	
	//updating with ticket if it exists
	if ($ticketID !== "") {
		
		//update ticket 
		$updateTicket = "UPDATE `Tickets` SET `ProjectID`='$projectID',`Status`='In Progress' WHERE `TicketID` ='$ticketID'";
		$updateTicket_result = mysqli_query($connection, $updateTicket) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	



	
	return $projectID;
 }

function archiveProject($projectID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	
	if ($projectStatus !== 'Archived') {
		$updateproject = "UPDATE `Team Projects` SET `Status`='Archived' WHERE `ProjectID` = '$projectID'";
	$updateproject_result = mysqli_query($connection, $updateproject) or die ("Query to get data from Team Project failed: ".mysql_error());

/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "archived this project.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	}
	else {
		
	// GETS NUMBER OF TASKS IN A SPECIFIC PROJECT
		$getTaskTotalCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID'";
		$getTaskTotalCount_result = mysqli_query($connection, $getTaskTotalCount) or die ("Query to get data from Team Project failed: ".mysql_error());
			while($row = $getTaskTotalCount_result->fetch_assoc()) {
				$TaskTotalCount = $row["COUNT(*)"];
			 }

		// GETS NUMBER OF COMPLETED TASKS IN A SPECIFIC PROJECT
		$getTaskCompletedCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `Status` = 'Completed'";
		$getTaskCompletedCount_result = mysqli_query($connection, $getTaskCompletedCount) or die ("Query to get data from Team Project failed: ".mysql_error());
		while($row = $getTaskCompletedCount_result->fetch_assoc()) {
				$TaskCompletedCount = $row["COUNT(*)"];
			 }
		if ($TaskTotalCount === $TaskCompletedCount) {
			$setStatus = "Complete";
		}	
		else {
			$setStatus = "Incomplete";
		}

	$updateproject = "UPDATE `Team Projects` SET `Status`='$setStatus' WHERE `ProjectID` = '$projectID'";
	$updateproject_result = mysqli_query($connection, $updateproject) or die ("Query to get data from Team Project failed: ".mysql_error());

/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "reactivated this project.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	

	}
	
	
	
	
}

function reactivateProject($projectID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	// GETS NUMBER OF TASKS IN A SPECIFIC PROJECT
		$getTaskTotalCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID'";
		$getTaskTotalCount_result = mysqli_query($connection, $getTaskTotalCount) or die ("Query to get data from Team Project failed: ".mysql_error());
			while($row = $getTaskTotalCount_result->fetch_assoc()) {
				$TaskTotalCount = $row["COUNT(*)"];
			 }

		// GETS NUMBER OF COMPLETED TASKS IN A SPECIFIC PROJECT
		$getTaskCompletedCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `Status` = 'Completed'";
		$getTaskCompletedCount_result = mysqli_query($connection, $getTaskCompletedCount) or die ("Query to get data from Team Project failed: ".mysql_error());
		while($row = $getTaskCompletedCount_result->fetch_assoc()) {
				$TaskCompletedCount = $row["COUNT(*)"];
			 }
		if ($TaskTotalCount === $TaskCompletedCount) {
			$setStatus = "Complete";
		}	
		else {
			$setStatus = "Incomplete";
		}

	$updateproject = "UPDATE `Team Projects` SET `Status`='$setStatus' WHERE `ProjectID` = '$projectID'";
	$updateproject_result = mysqli_query($connection, $updateproject) or die ("Query to get data from Team Project failed: ".mysql_error());

/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "reactivated this project.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	


}

function deleteProject($projectID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	
	
	//deleting ticket reviews
	$query5 = "SELECT * FROM `Tickets Review` WHERE `ProjectID`='$projectID'";
	$query5_result = mysqli_query($connection, $query5) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query5_result)) {
		$reviewIDs[] = $row["ReviewID"];
	}
	
	foreach ($reviewIDs as $reviewID) {
		//Deleting review comments
	$query2 = "DELETE FROM `Tickets Review Comments` WHERE `ReviewID` = '$reviewID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//Deleting review members
	$query3 = "DELETE FROM `Tickets Review Members` WHERE `ReviewID` = '$reviewID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//Deleting review images
	$query4 = "DELETE FROM `Tickets Review Preview Images` WHERE `ReviewID` = '$reviewID'";
	$query4_result = mysqli_query($connection, $query4) or die ("Query to get data from Team task failed: ".mysql_error());

	//Deleting review
	$query = "DELETE FROM `Tickets Review` WHERE `ReviewID` = '$reviewID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//deleted review files also
	$path2 = $_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/review/uploads/'.$reviewID;
	
	chmod($path2, 0777);
	$files = glob($path2.'/*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
		unlink($file); // delete file
	}
	rmdir($path2);	
		
	}
	
	$addFav = "DELETE FROM `Team Projects Favorites` WHERE `ProjectID`='$projectID'";
	$addFav_result = mysqli_query($connection, $addFav) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	//Getting project members
	$getProjectMembers = "SELECT * FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID' AND `userID` != '$userID'";
	$getProjectMembers_result = mysqli_query($connection, $getProjectMembers) or die ("getProjectMembers_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMembers_result)) {
		$projectMembers[] =$row["userID"];	
	}
	//deleting memberships
	$DeleteMembers = "DELETE FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID'";
	$DeleteMembers_result = mysqli_query($connection, $DeleteMembers) or die(mysqli_error($connection));
	
	//deleted project files also
	$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/uploads/'.$projectID;
	
	chmod($path, 0777);
	$files = glob($path.'/*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
		unlink($file); // delete file
	}
	rmdir($path);
	
	//deleting project id from tickets
	$deleteTicketProjectID = "UPDATE `Tickets` SET `ProjectID`=NULL,`Status`='Incomplete' WHERE `ProjectID` = '$projectID'";
	$deleteTicketProjectID_result = mysqli_query($connection, $deleteTicketProjectID) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	//deleting from favorites
	$DeleteProjectFavorites = "DELETE FROM `Team Projects Favorites` WHERE `ProjectID` = '$projectID'";
    $DeleteProjectFavorites_result = mysqli_query($connection, $DeleteProjectFavorites) or die(mysqli_error($connection));
	
	
	
	//Getting event information
	$getEventInfo = "SELECT * FROM `calendar` WHERE `ProjectID` = '$projectID'";
	$getEventInfo_result = mysqli_query($connection, $getEventInfo) or die ("getEventInfo to get data from Team Project failed: ".mysql_error());
	while($row = $getEventInfo_result->fetch_assoc()) {
			$eventIDs[] = $row["id"];
	}
	
	foreach ($eventIDs as $eventID) {
	$calendarPath=$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID;
		chmod($calendarPath, 0777);
	$files = glob($calendarPath.'/*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
		unlink($file); // delete file
	}
	rmdir($calendarPath);
	
	}
	
	//deleting associated calendar events
	$DeleteProjectEvents = "DELETE FROM `calendar` WHERE `ProjectID` = '$projectID'";
    $DeleteProjectEvents_result = mysqli_query($connection, $DeleteProjectEvents) or die(mysqli_error($connection));
	
	foreach ($projectMembers as $name) {
		$notification = "<a href=#>The project: <strong>$projectTitle</strong> has been deleted by <strong>$FN $LN</strong>.</a>";
	$notification = addslashes($notification);
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Project','$name','$projectID')";

		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "deleted the project: <em>$projectTitle</em>.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
	
	//deleting tasks
	$DeleteProjectTasks = "DELETE FROM `Tasks` WHERE `ProjectID` = '$projectID'";
    $DeleteProjectTasks_result = mysqli_query($connection, $DeleteProjectTasks) or die(mysqli_error($connection));
	
	
	//deleting projects
	$DeleteProject = "DELETE FROM `Team Projects` WHERE `ProjectID` = '$projectID'";
    $DeleteProject_result = mysqli_query($connection, $DeleteProject) or die(mysqli_error($connection));

}

function projectIsCompleted($projectID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	
	$setToComplete = "UPDATE `Team Projects` SET `Status`='Complete',`Date Completed`=NOW() WHERE `ProjectID` = '$projectID'";
	$setToComplete_result = mysqli_query($connection, $setToComplete) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	$setToTicketComplete = "UPDATE `Tickets` SET `Status`='Complete' WHERE `ProjectID` = '$projectID'";
	$setToTicketComplete_result = mysqli_query($connection, $setToTicketComplete) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	/////////// INSERTING NOTIFICATION ///////////
	//Getting project members
	$getProjectMembers = "SELECT * FROM `Team Projects Member List` JOIN `user` ON `Team Projects Member List`.`userID` = `user`.`userID` WHERE `ProjectID` = '$projectID' AND `user`.`userID` !='$userID'";
	$getProjectMembers_result = mysqli_query($connection, $getProjectMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());

	while($row = mysqli_fetch_array($getProjectMembers_result)) {
		$projectMembers[] =$row["userID"];	
	}

		foreach ($projectMembers as $name) {
			$notification2 = "<a href=/dashboard/team-projects/view/?projectID=$projectID>The <strong>$projectTitle</strong> project has been completed!</a>";
			$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification2','Task','$name','$projectID')";
			$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			
			//////// SENDING EMAIL ///////	
		if(isset($name)) {
	
	//getting members
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	$subject = "The project: $projectTitleNoInsert has been completed!";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              The project <span style="text-decoration: underline">'.$projectTitleNoInsert.'</span> has been completed!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$projectID.'" class="button">View Project</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);
		}
	
		}
		
		
		/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
		$activity = "completed the last open task. The project has been completed!";
		$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
		$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
		
		//////// SENDING EMAIL TO TICKET HOLDER ///////
		
	$ticketInfo = getTicketInfoFromProject($projectID);
	
	$ticketID = $ticketInfo["ticketID"];
	$ticketTitle = $ticketInfo["title"];
	$ticketTitleNoInsert = addslashes($ticketInfo["title"]);
	$ticketDesc = addslashes($ticketInfo["description"]);
	$ticketDescNoInsert = $ticketInfo["description"];
	$ticketDueDate = $ticketInfo["dueDate"];
	$ticketStatus = $ticketInfo["Status"];
	$ticketCategoryID = $ticketInfo["categoryID"];
	$ticketCategoryTitle = $ticketInfo["categoryTitle"];
	$ticketURL = $ticketInfo["URL"];
	$ticketRequestedByUserID = $ticketInfo["requestedByUserID"];
	$ticketRequestedByFullName = $ticketInfo["requestedByFullName"];
	$ticketRequestedByPP = $ticketInfo["requestedByPP"];
	$ticketTimestamp = $ticketInfo["Timestamp"];
	$ticketCopy = addslashes($ticketInfo["Copy"]);
	$ticketCopyNoInsert = $ticketInfo["Copy"];
	
		if(isset($ticketRequestedByUserID)) {
			
			$notification3 = "<a href=/dashboard/requests/my-requests/?ticketID=$ticketID><strong>TICKET #$ticketID: $ticketTitle</strong> has been completed!</a>";
			$addNotification3 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification2','Ticket','$ticketRequestedByUserID','$projectID')";
			$addNotification3_result = mysqli_query($connection, $addNotification3) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	//getting members
	$userInfo = getUserInfo($ticketRequestedByUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	$subject = "TICKET #$ticketID: $ticketTitleNoInsert has been completed!";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              TICKET #'.$ticketID.': <span style="text-decoration: underline">'.$ticketTitleNoInsert.'</span> has been completed!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              
             <a href="https://dashboard.coat.com/dashboard/requests/my-requests/?ticketID='.$ticketID.'" class="button">View Ticket</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);
		}
	
}

function projectIsNotCompleted($projectID) {
	global $connection;
	global $userID;
	
	$setToIncomplete = "UPDATE `Team Projects` SET `Status`='Incomplete' WHERE `ProjectID` = '$projectID'";
		$setToIncomplete_result = mysqli_query($connection, $setToIncomplete) or die ("Query to get data from Team Project failed: ".mysql_error());
		
		$setProjectDateCompleted = "UPDATE `Team Projects` SET `Date Completed`=NULL WHERE `ProjectID` = '$projectID'";
		$setProjectDateCompleted_result = mysqli_query($connection, $setProjectDateCompleted) or die ("Query to get data from Team Project failed: ".mysql_error());	
	
	$setToTicketInProgress = "UPDATE `Tickets` SET `Status`='In Progress' WHERE `ProjectID` = '$projectID'";
	$setToTicketInProgress_result = mysqli_query($connection, $setToTicketInProgress) or die ("Query to get data from Team Project failed: ".mysql_error());
}


//**NOTES 
function addProjectNote($projectID,$message,$mentionUserIDs) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	$addNoteMessage=addslashes($message);	
	$addNoteMessageNoInsert=$message;	
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	
/////////// INSERTING NOTIFICATION ///////////

	//Getting project members
	$getProjectMembers = "SELECT * FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID' AND `userID` != '$userID'";
	$getProjectMembers_result = mysqli_query($connection, $getProjectMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMembers_result)) {
		$projectMembers[] =$row["userID"];	
	}
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$userID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
	
	if ($mentionUserIDs) {
		
		foreach($mentionUserIDs as $mentionID){
			$getMentionUsername = "SELECT * FROM `user` WHERE `userID` = '$mentionID'";
			$getMentionUsername_result = mysqli_query($connection, $getMentionUsername) or die ("Query to get data from Team Project failed: ".mysql_error());
			while($row = $getMentionUsername_result->fetch_assoc()) {
				$mentionUsernames[] = $row["username"];
			}
		}
		
		
		$arrayString = implode(" @",$mentionUsernames);
		
		$newMentionMessage = "<strong>@".$arrayString."</strong>:<br>".$addNoteMessage;
		$newMentionMessageNoInsert = "<strong>@".$arrayString."</strong>:<br>".$addNoteMessageNoInsert;
		
		$InsertMessage = "INSERT INTO `Project Notes`(`Message`, `userID`, `ProjectID`) VALUES ('$newMentionMessage','$userID','$projectID')";
	$InsertMessage_result = mysqli_query($connection, $InsertMessage) or die(mysqli_error($connection));
		
		
		foreach($mentionUserIDs as $name){
			
			
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID><strong>$FN $LN</strong> mentioned you in the project: <strong>$projectTitle</strong>.<br><br><em>&#34;$newMentionMessage&#34;</em></a>";
	$notification = addslashes($notification);
	
	
			$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Note','$name','$projectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			/////////// SENDING EMAIL ///////////	
		
	if (isset($name)) {
							
	//getting info
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
		
	$subject = "$FN $LN mentioned you in the project: ".$projectTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              <strong>'.$FN.' '.$LN.'</strong> mentioned you in the project: <span style="text-decoration: underline">'.$projectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 <h3>Comment By: '.$commentBy.'</h3>
			 
             <div class="noteNew">'.$newMentionMessageNoInsert.'</div>
             <br>
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$projectID.'" class="button">View Project</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
			
		
		}
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new note:<br><br><em><p>$newMentionMessage</p></em>";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Note','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	}
	else {
		
		$InsertMessage = "INSERT INTO `Project Notes`(`Message`, `userID`, `ProjectID`) VALUES ('$addNoteMessage','$userID','$projectID')";
	$InsertMessage_result = mysqli_query($connection, $InsertMessage) or die(mysqli_error($connection));
		
		foreach ($projectMembers as $name2) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>A new note has been added to the project: <strong>$projectTitle</strong>.<br><br><strong>$FN $LN</strong>:<br><em>&#34;$addNoteMessage&#34;</em></a>";
	$notification = addslashes($notification);
	
	
			$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Note','$name2','$projectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			/////////// SENDING EMAIL ///////////	
		
	if (isset($name2)) {
							
	//getting info
	$userInfo2 = getUserInfo($name2);
	$emailToEmail2 =$userInfo2["userEmail"];
	$emailToFN2 = $userInfo2["userFirstName"];
	
		
	$subject = "A new note has been added to the project: ".$projectTitleNoInsert.".";
	
		$to = $emailToEmail2;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new note has been added to the project: <span style="text-decoration: underline">'.$projectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 <h3>Comment By: '.$commentBy.'</h3>
			 
             <div class="noteNew">'.$addNoteMessageNoInsert.'</div>
             <br>
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$projectID.'" class="button">View Project</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
			
	}
		
		/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new note:<br><br><em><p>$addNoteMessage</p></em>";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Note','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	}
	
	
	
	
	
	


}

function deleteProjectNote($noteID) {
	global $connection;
	
	$RemoveNote = "DELETE FROM `Project Notes` WHERE `NoteID` = '$noteID'";
    $RemoveNote_result = mysqli_query($connection, $RemoveNote) or die(mysqli_error($connection));
}


//**FILES 
function addProjectFile($projectID,$file) {
global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];

	   
	   $no_files = count($file['name']);
	   for ($i = 0; $i < $no_files; $i++) {
		   $errors= array();
      $file_name = $file['name'][$i];
      $file_size =$file['size'][$i];
      $file_tmp =$file['tmp_name'][$i];
      $file_type=$file['type'][$i];
      $file_ext=strtolower(end(explode('.',$file['name'][$i])));
      
      $expensions= array("jpeg","jpg","png","doc","docx","psd","pdf","html","css","js","xlsx","xls","csv","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[][$i]="extension not allowed.";
      }
      
      if($file_size > 20971520){
         $errors[][$i]='File size must be less than 20MB';
      }
      
      if(empty($errors)==true){
		  $pathEmail ='/uploads/'.$projectID.'/'.$file_name;
		  $path =$_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/uploads/'.$projectID.'/'.$file_name;
         move_uploaded_file($file_tmp,$path);
		 chmod($path, 0777);
		  
		 /////////// INSERTING NOTIFICATION ///////////
	
		
//Getting project members
	$getProjectMembers = "SELECT * FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID' AND `userID` != '$userID'";
	$getProjectMembers_result = mysqli_query($connection, $getProjectMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMembers_result)) {
		$projectMembers[] =$row["userID"];	
	}	  
		  
	
	foreach ($projectMembers as $name) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>A new file: <strong>$file_name</strong> has been added to: <strong>$projectTitle</strong>.</a>";
	$notification = addslashes($notification);
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Note','$name','$projectID')";

		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////	
		
	if (isset($name)) {
	
	//getting info
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	$subject = "NEW FILE UPLOAD - Project: $projectTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new file has been uploaded to the project '.$projectTitleNoInsert.'.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              '.$FN.' '.$LN.' uploaded a new file to the project <strong>'.$projectTitleNoInsert.'</strong>.
              
			  <p>"'.$file_name.'"</p>
              <br>
			   <a href="https://dashboard.coat.com/dashboard/team-projects/view/'.$pathEmail.'" class="button">Download File</a>
			   <br>
			  <a href="https://dashboard.coat.com/dashboard/team-projects/view?projectID='.$projectID.'" class="button">View Project</a>
			  <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	}  
	
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "added a new file: <strong>$file_name</strong>";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','File','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
    
		  
         echo "Your file was successfully uploaded.";
		  
		  
      }
	   else{
         echo ($errors);
      }
		   
	   }
	   
      
  
	   
   
	
}

function deleteProjectFile($projectID, $file, $path) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	unlink($_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/'.$path.'/'.$file.'');

/////////// INSERTING NOTIFICATION ///////////
	
	//Getting project members
	$getProjectMembers = "SELECT * FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID' AND `userID` != '$userID'";	
	$getProjectMembers_result = mysqli_query($connection, $getProjectMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	
	while($row = mysqli_fetch_array($getProjectMembers_result)) {
		$projectMembers[] =$row["userID"];	
	}
	
	foreach ($projectMembers as $name) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>The file: <strong>$file</strong> has been removed from the project: <strong>$projectTitle</strong>.</a>";
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','File','$name','$projectID')";

		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());

	}
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "removed the file: <strong>$file</strong>";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','FileUpload','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
}


//**COPY 
function updateProjectCopy($projectID,$copy) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	
	//saving all 
	$saveTicket = "UPDATE `Tickets` SET `Copy`='$copy' WHERE `ProjectID` = '$projectID'";
	$saveTicket_result = mysqli_query($connection, $saveTicket) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	$saveProject = "UPDATE `Team Projects` SET `Copy`='$copy' WHERE `ProjectID` = '$projectID'";
	$saveProject_result = mysqli_query($connection, $saveProject) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	
	//Getting project members
	$getProjectMembers = "SELECT * FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID' AND `userID` != '$userID'";
	$getProjectMembers_result = mysqli_query($connection, $getProjectMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMembers_result)) {
		$projectMembers[] =$row["userID"];	
	}
	
	foreach ($projectMembers as $name) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>The copy has been updated by <strong>$FN $LN</strong> in the project: <strong>$projectTitle</strong>.</a>";
	
		
			$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Copy','$name','$projectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	
	}
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "updated the copy.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Copy','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
}


//**TASKS
function addTask($projectID,$taskTitle,$taskTitleNoInsert,$taskDueDate,$eventCategory,$taskEndDate,$taskCategory,$taskDescription,$taskDescriptionNoInsert,$TaskMemberUserID) {
	
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	//if aslo an event
	if ($taskCategory == "7") {
		//$InsertTask1 = "";
		$InsertTask1= mysqli_query($connection,"INSERT INTO `Tasks`(`Title`, `Description`, `Due Date`, `End Date`, `Category`, `Requested By`, `ProjectID`, `userID`) VALUES ('$taskTitle','$taskDescription','$taskDueDate','$taskEndDate','$taskCategory','$userID','$projectID','$TaskMemberUserID')");
		$taskID = mysqli_insert_id($connection);
		
		$insert = mysqli_query($connection,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`,`ProjectID`,`TaskID`) VALUES('$taskTitle','$taskDueDate','$taskEndDate','$eventCategory','$userID','$taskDescription','false','$projectID','$taskID')");
		$eventID = mysqli_insert_id($connection);

		////////// CREATING FILE UPLOAD FOLDER //////////
		$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID;

		mkdir($path, 0777, true);
		chmod($path, 0777);
		
		$taskInfo = getTaskInfo($taskID);
	
		$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
		
		/////////// INSERTING NOTIFICATION ///////////
	
	//Getting project members
	$getGroupMembers = "SELECT `SubscriptionID`, `userID`, `Calendar Categories`.`Category`,`Notification Subscription`.`CalendarCategoryID` FROM `Notification Subscription` JOIN `Calendar Categories` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Notification Subscription`.`CalendarCategoryID` = '$taskEventCategoryID' AND `userID` != '$userID'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];	
		}
	
	foreach ($groupMembers as $name2) {
		$notification = "<a href=/dashboard/content-calendar/?eventID=$taskEventID>The <strong>$taskEventCategory</strong> event: <strong>$taskEventTitle&nbsp;</strong>has been added to the Content Calendar.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name2','$taskEventID')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////	
		
	if (isset($name2)) {
		
	//Getting user info
	$userInfo1 = getUserInfo($name2);
	$emailToEmail =$userInfo1["userEmail"];
	$emailToFN = $userInfo1["userFirstName"];
		

	
	$subject = "New Content Calendar ".$taskEventCategory." Event: ".$taskEventTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new '.$taskEventCategory.' event has been added to the Content Calendar.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>'.$FN.' '.$LN.' added the following '.$taskEventCategory.' event to the Content Calendar.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskEventCategory.'</p><p class="pull-left"> <strong>Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).' -  '.date('m/d/Y @ g:ia',strtotime($taskEndDate)).'</p>
				 
             <br><h2>'.$taskEventTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/content-calendar/?eventID='.$taskEventID.'" class="button">View Event</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		
		if ($name2 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}
	}
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "added the <strong>$taskEventCategory</strong> event: <strong>$taskEventTitle</strong> to the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$taskEventID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
		
	
	
	}
	else {
		$InsertTask = "INSERT INTO `Tasks`(`Title`, `Description`, `Due Date`,`End Date`, `Category`, `Requested By`, `ProjectID`, `userID`) VALUES ('$taskTitle','$taskDescription','$taskDueDate',NULL,'$taskCategory','$userID','$projectID','$TaskMemberUserID')";
		$InsertTask_result = mysqli_query($connection, $InsertTask) or die(mysqli_error($connection));
		$taskID = $connection->insert_id;
		
		$taskInfo = getTaskInfo($taskID);
	
		$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	}


	
		projectIsNotCompleted($taskProjectID);
	
	
/////////// INSERTING NOTIFICATION ///////////

	
	if ($taskAssignedToUserID != $userID) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$taskID>A new task: <strong>$taskTitle</strong> has been assigned to you in: <strong>$taskProjectTitle</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskAssignedToUserID','$taskProjectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());

		
	//////// SENDING EMAIL ///////	
		
	if (isset($taskAssignedToUserID)) {
	
	//Getting user info
	$userInfo2 = getUserInfo($taskAssignedToUserID);
	$emailToEmail =$userInfo2["userEmail"];
	$emailToFN = $userInfo2["userFirstName"];
		
	$userInfoRequestedBy = getUserInfo($taskRequestedByUserID);
	$fullNameRequestedBy = $userInfoRequestedBy["userFirstName"] ." ". $userInfoRequestedBy["userLastName"];
		
	$subject = "You have been assigned a new task in the project: $taskProjectTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              New task assigned to you in the project <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>the following task has been assigned to you.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$fullNameRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescriptionNoInsert.'<br>
             </p></div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	}
	else {
		
	}
	
	
	
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	//Getting ADDEE'S name
	$getAddeeUsername = "SELECT `username` FROM `user` WHERE `userID` = '$taskAssignedToUserID'";
	$getAddeeUsername_result = mysqli_query($connection, $getAddeeUsername) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getAddeeUsername_result->fetch_assoc()) {
			$AddeeUsername = $row["username"];
	}
	
	$activity = "created a new task: <em>$taskTitle</em> assigned to <strong>@$AddeeUsername</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	

	
	
	//////// END SENDING EMAILS ////////
	
///////////
	if (isset($eventID)) {
		$result = ["taskID" => $taskID,"eventID" => $eventID];
	
	}
	else {
		$result = ["taskID" => $taskID];
	}
	header('Content-Type: application/json'); 
	echo json_encode($result);
}

function deleteTask($taskID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	

	
	
	/////////// INSERTING NOTIFICATION ///////////

	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "deleted the task: <em>$taskTitle</em> assigned to <strong>$taskAssignedToFullName</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	////// DELETING ASSOCIATED EVENT /////

	//DOES EVENT EXIST?
	$getAllEvents = "SELECT `id` FROM `calendar` WHERE `TaskID`='$taskID'";
	$getAllEvents_result = mysqli_query($connection, $getAllEvents) or die(mysqli_error($connection));
	$eventCount = mysqli_num_rows($getAllEvents_result);

	if ($eventCount == 1) {
		/////////// INSERTING NOTIFICATION ///////////
		
		//Getting project members
		$getGroupMembers = "SELECT `SubscriptionID`, `userID`, `Calendar Categories`.`Category`,`Notification Subscription`.`CalendarCategoryID` FROM `Notification Subscription` JOIN `Calendar Categories` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Notification Subscription`.`CalendarCategoryID` = '$taskEventCategoryID' AND `userID` != '$userID'";
		$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
			while($row = mysqli_fetch_array($getGroupMembers_result)) {
				$groupMembers[] =$row["userID"];
				
			}

		foreach ($groupMembers as $name5) {
			$notification = "<a href=/dashboard/content-calendar/?>The <strong>$taskEventCategory</strong> event: <strong>$taskEventTitle</strong> has been deleted.</a>";
			$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name5','$taskEventID')";
			$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		}

		/////////// INSERTING ACTIVITY /////////////
		$activity = "deleted the <strong>$taskEventCategory</strong> event: <strong>$taskEventTitle</strong> from the Content Calendar.";
		$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Event','$userID','$taskProjectID')";
		$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());

		
		//DELETING EVENT
		$deleteEvent = "DELETE FROM `calendar` where id='$taskEventID'";
		$deleteEvent_result = mysqli_query($connection, $deleteEvent) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//// DELETING UPLOAD FOLDER
		$path=$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$taskEventID;
		foreach(glob("$path/*") as $file)
		{
			if(is_dir($file)) { 
				recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir( $path );

	}
	
	if ($taskProjectTaskType == "Cadence") {
			$query = "SELECT `TaskID`, `Tasks`.`Title`, `Description`, DATE_FORMAT(`Due Date`, '%l:%i %p %b %e, %Y') as 'Due Date', `End Date`, `Status`, `Tasks`.`Category`,`Task Categories`.`Category` AS 'Category Name', `Tasks`.`Requested By`, `ProjectID`, `Tasks`.`userID`, `allDay`, `Task Date Created`, `Task Date Completed`,`First Name`,`Last Name` FROM `Tasks` JOIN user on `Tasks`.`Requested By` = `user`.`userID` JOIN `Task Categories` on `Tasks`.`Category` = `Task Categories`.`CategoryID` WHERE `Due Date` > '$taskDueDate' AND `ProjectID`='$taskProjectID' AND `TaskID`!='$taskID' AND `Status`!='Completed' ORDER BY `TaskID` LIMIT 1";
			$query_result = mysqli_query($connection, $query) or die ("NEw Query to get data from Team Project failed: ".mysql_error());
			while($row = $query_result->fetch_assoc()) {
				$nextUser = $row["userID"];	
				$nextTaskID = $row["TaskID"];	
				$nextTask = addslashes($row["Title"]);	
				$nextTaskNoInsert = $row["Title"];	
				$nextCategoryID = $row["Category"];	
				$nextTaskCategoryName = $row["Category Name"];
				$nextTaskDueDate = $row["Due Date"];	
				$nextTaskRequestedBy = $row["First Name"].' '.$row["Last Name"];
				$nextTaskDescription = addslashes($row["Description"]);
				$nextTaskDescriptionNoInsert = $row["Description"];
			}
		
		if (isset($nextUser) && $userID != $nextUser) {
			$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$nextTaskID>The task: <strong>$taskTitle</strong> has been deleted from the project: <strong>$taskProjectTitle</strong>. Your task: <strong>$nextTask</strong> is due next.</a>";
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$nextUser','$taskProjectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
	
		/////////// SENDING EMAIL /////////// 		
				
	//Getting user info
	$userInfo = getUserInfo($nextUser);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
				
	
	$subject = "PROJECT UPDATE: Your task: $nextTaskNoInsert is due next.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
				Your task:  <span style="text-decoration: underline">'.$nextTaskNoInsert.'</span> is due next in the project: <span style="text-decoration: underline">'.$taskProjectTitle.'</span>.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$nextTaskCategoryName.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($nextTaskDueDate)).'</p>
				 
             <br><h2>'.$nextTaskNoInsert.'</h2>
             <p><strong> Description: </strong><br>'.$nextTaskDescriptionNoInsert.'<br>
             </p></div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$nextTaskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
			
		if ($nextUser != $userID){
			mail($to, $subject, $message, $headers);
		}	
		

	
	
}	
		
			// INSERTING NOTIFICATION //
		
	}
	
	///////// DELETING AFTER NOTIFICAITON
	$RemoveTaskComments = "DELETE FROM `Task Comments` WHERE `TaskID` = '$taskID'";
    $RemoveTaskComments_result = mysqli_query($connection, $RemoveTaskComments) or die(mysqli_error($connection));
	$RemoveTaskID = "DELETE FROM `Tasks` WHERE `TaskID` = '$taskID'";
    $RemoveTaskID_result = mysqli_query($connection, $RemoveTaskID) or die(mysqli_error($connection));
	
	//getting task count
	$getTaskTotalCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$taskProjectID'";
	$getTaskTotalCount_result = mysqli_query($connection, $getTaskTotalCount) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getTaskTotalCount_result->fetch_assoc()) {
				$TaskTotalCount = $row["COUNT(*)"];
			 }

	// GETS NUMBER OF COMPLETED TASKS IN A SPECIFIC PROJECT
	$getTaskCompletedCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$taskProjectID' AND `Status` = 'Completed'";
	$getTaskCompletedCount_result = mysqli_query($connection, $getTaskCompletedCount) or die ("Query to get data from Team Project failed: ".mysql_error());

	 while($row = $getTaskCompletedCount_result->fetch_assoc()) {
		$TaskCompletedCount = $row["COUNT(*)"];
	}
	
	if ($TaskTotalCount === $TaskCompletedCount) {
	 projectIsCompleted($taskProjectID);
	
	}
	else {
		 projectIsNotCompleted($taskProjectID);
	}
	
	
	///////// DELETING END NOTIFICAITON
	if ($taskAssignedToUserID !== $userID) {
	$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID>The task: <strong>$taskTitle</strong> assigned to you has been deleted in: <strong>$taskProjectTitle</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskAssignedToUserID','$taskProjectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
}

}

function completeTask($taskID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
		// INSERTING NOTIFICATION //
	if ($taskRequestedByUserID !== $userID) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$taskID>Your requested task: <strong>$taskTitle</strong> has been completed in: <strong>$taskProjectTitle</strong>.</a>";
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskRequestedByUserID','$taskProjectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
		
	
		$dateCompleted = date('Y-m-d G:i:s');
		$setDateCompleted = "UPDATE `Tasks` SET `Task Date Completed`='$dateCompleted',`Status`='Completed' WHERE `TaskID` = '$taskID'";
		$setDateCompleted_result = mysqli_query($connection, $setDateCompleted) or die ("Query to get data from Team Project failed: ".mysql_error());
		
		/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
		$activity = "completed the task: <em>$taskTitle</em>.";
		$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$taskProjectID')";
		$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
		

		
		if ($taskProjectTaskType == "Cadence") {
			$query = "SELECT `TaskID`, `Tasks`.`Title`, `Description`, DATE_FORMAT(`Due Date`, '%l:%i %p %b %e, %Y') as 'Due Date', `End Date`, `Status`, `Category`, `Tasks`.`Requested By`, `ProjectID`, `Tasks`.`userID`, `allDay`, `Task Date Created`, `Task Date Completed`,`First Name`,`Last Name` FROM `Tasks` JOIN user on `Tasks`.`Requested By` = `user`.`userID` WHERE `Due Date` > '$taskDueDate' AND `ProjectID`='$taskProjectID' AND `TaskID`!='$taskID' AND `Status`!='Completed' ORDER BY `TaskID` LIMIT 1";
			$query_result = mysqli_query($connection, $query) or die ("NEw Query to get data from Team Project failed: ".mysql_error());
			while($row = $query_result->fetch_assoc()) {
				$nextUser = $row["userID"];	
				$nextTaskID = $row["TaskID"];	
				$nextTask = addslashes($row["Title"]);	
				$nextTaskNoInsert = $row["Title"];	
				$nextCategory = $row["Category"];	
				$nextTaskDueDate = $row["Due Date"];	
				$nextTaskRequestedBy = $row["First Name"].' '.$row["Last Name"];
				$nextTaskDescription = $row["Description"];
			}
			
		
		
		if (isset($nextUser) && $userID != $nextUser) {
	
			// INSERTING NOTIFICATION //
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$nextTaskID>The task: <strong>$taskTitle</strong> has been completed in: <strong>$taskProjectTitle</strong>. Your task: <strong>$nextTask</strong> is due next.</a>";
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$nextUser','$taskProjectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	//Getting task cat information
	$getTaskCat = "SELECT * FROM `Task Categories` WHERE `CategoryID` = '$nextCategory'";
	$getTaskCat_result = mysqli_query($connection, $getTaskCat) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getTaskCat_result->fetch_assoc()) {
			$nextTaskCategoryName = $row["Category"];
	}	
				
		/////////// SENDING EMAIL /////////// 		
	$userInfo = getUserInfo($nextUser);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	
	$subject = "PROJECT UPDATE: Your task: $nextTaskNoInsert is due next.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
				The task: <span style="text-decoration: underline">'.$taskTitleNoInsert.'</span> has been completed in: <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>. Your task:  <span style="text-decoration: underline">'.$nextTaskNoInsert.'</span> is due next.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$nextTaskCategoryName.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($nextTaskDueDate)).'</p>
				 
             <br><h2>'.$nextTaskNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$nextTaskRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$nextTaskDescription.'<br>
             </p></div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$nextTaskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		if ($nextUser != $userID) {
			mail($to, $subject, $message, $headers);
		}	
		

	
	
}	
			
			
		}
		else {
			/////////// SENDING EMAIL /////////// 
		if (isset($taskRequestedByUserID) && $taskRequestedByUserID !== $userID) {
		
	//get user info			
	$userInfo = getUserInfo($taskRequestedByUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
				
	
	$subject = "Your assigned task has been completed in the project: ".$taskProjectTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your assigned task has been completed in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'</h2>
             <p><strong> Description: </strong><br>'.$taskDescNoInsert.'<br>
             </p></div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
		}
	
	
	
}

function inReviewTask($taskID,$taskMessage) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	

		if (isset($taskRequestedByUserID) && $taskRequestedByUserID != $userID) {
		// INSERTING NOTIFICATION //
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$taskID>PENDING REVIEW: <strong>$taskTitle</strong> in: <strong>Project - $taskProjectTitle</strong>.</a>";
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskRequestedByUserID','$taskProjectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		}
	
		$setDateCompleted = "UPDATE `Tasks` SET `Task Date Completed`=NULL,`Status`='In Review' WHERE `TaskID` = '$taskID'";
		$setDateCompleted_result = mysqli_query($connection, $setDateCompleted) or die ("Query to get data from Team Project failed: ".mysql_error());
		
		
		//ADDING TASK COMMENT IF THERE IS ONE
		if (isset($taskMessage)) {

			$addTaskMessage2 = "INSERT INTO `Task Comments`(`Message`, `userID`, `ProjectID`, `TaskID`, `Sent By`) VALUES ('$taskMessage','$taskAssignedToUserID','$taskProjectID','$taskID','$userID')";
			$addTaskMessage2_result = mysqli_query($connection, $addTaskMessage2) or die ("Query to get data from Team Project failed: ".mysql_error());
			
		}
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "submitted the task: <em>$taskTitle</em> for review.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
		
	/////////// SENDING EMAIL ///////////	
		
	if (isset($taskRequestedByUserID) && $taskRequestedByUserID != $userID) {
			
				
	$userInfo = getUserInfo($taskRequestedByUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
		
	$userInfoRequestedBy = getUserInfo($taskRequestedByUserID);
	$fullNameRequestedBy = $userInfoRequestedBy["userFirstName"] ." ". $userInfoRequestedBy["userLastName"];	
	
		
	$subject = "REVIEW: Your assigned task has been updated in the project: $taskProjectTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your assigned task has been submitted for review in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$fullNameRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescNoInsert.'<br>
             </p>
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$taskMessage.'</div>
             <br>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	
	
	
}

function approveTask($taskID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	
	$setDateCompleted = "UPDATE `Tasks` SET `Task Date Completed`=NULL,`Status`='Approved' WHERE `TaskID` = '$taskID'";
		$setDateCompleted_result = mysqli_query($connection, $setDateCompleted) or die ("Query to get data from Team Project failed: ".mysql_error());

		// INSERTING NOTIFICATION //
	if (isset($taskAssignedToUserID) && $taskAssignedToUserID != $userID) {
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$taskID>APPROVED: <strong>$taskTitle</strong> in: <strong>Project - $taskProjectTitle</strong>.</a>";
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskAssignedToUserID','$taskProjectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	
		
	}
		
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "approved the task: <em>$taskTitle</em>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
		
	/////////// SENDING EMAIL ///////////	
		
	if (isset($taskAssignedToUserID) && $taskAssignedToUserID != $userID) {
			
				
	//getting info
	$userInfo = getUserInfo($taskAssignedToUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
		
	$userInfoRequestedBy = getUserInfo($taskRequestedByUserID);
	$fullNameRequestedBy = $userInfoRequestedBy["userFirstName"] ." ". $userInfoRequestedBy["userLastName"];		
		
	$subject = "APPROVED: Your task has been approved in the project: $taskProjectTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your task has been approved in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$fullNameRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}	
	
	
	
	
	
}

function kickbackTask($taskID, $message) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	
	$taskMessage = addslashes($message);
	$taskMessageNoInsert = $message;
	$update = mysqli_query($connection,"UPDATE `Tasks` SET `Status`='New' where `TaskID`='$taskID'");
	

	$addTaskMessage = "INSERT INTO `Task Comments`(`Message`, `userID`, `ProjectID`, `TaskID`, `Sent By`) VALUES ('$taskMessage','$taskAssignedToUserID','$taskProjectID','$taskID','$userID')";
	$addTaskMessage_result = mysqli_query($connection, $addTaskMessage) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	//CHECKING IF MESSAGE SENT
	$newTaskMessageID =mysqli_insert_id($connection);
	
	if (isset($taskAssignedToUserID) && $taskAssignedToUserID != $userID) {
	// INSERTING NOTIFICATION //
	$notification = "<a href=/dashboard/todo/calendar/?eventID=$taskID>KICKBACK: <strong>$taskTitle</strong> in: <strong>Project - $taskProjectTitle</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskAssignedToUserID','$taskProjectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "rejected the task: <em>$taskTitle</em>. Comment below:<br>&#34;<em>$taskMessage</em>&#34;";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Tasks','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	/////////// SENDING EMAIL ///////////	
		
	if (isset($taskAssignedToUserID) && $taskAssignedToUserID !== $taskRequestedByUserID && $taskAssignedToUserID !== $userID) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$taskRequestedByUserID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $taskRequestedBy = $row["First Name"].' '.$row["Last Name"];
	}
				
	
				
	//getting info
	$userInfo = getUserInfo($taskAssignedToUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
				
	$taskDueDateEditFinal = date('m/d/Y @ g:ia',strtotime($taskDueDate));
	
		
	$subject = "KICKBACK: Your task has been rejected in the project: ".$taskProjectTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your task has been rejected in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$taskRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescriptionNoInsert.'<br>
             </p>
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$taskMessageNoInsert.'</div>
             <br>
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

}

function reassignTask($taskID, $memberID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	$prevAssignedTo = $taskAssignedToUserID;
	
	$userInfoReassignedTo = getUserInfo($memberID);
	$emailToEmailReassignedTo =$userInfoReassignedTo["userEmail"];
	$fullNameReassignedTo = $userInfoReassignedTo["userFirstName"] ." ". $userInfoReassignedTo["userLastName"];
	
	$update = mysqli_query($connection,"UPDATE `Tasks` SET `userID`='$memberID' WHERE `TaskID`='$taskID'");

	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "reassigned the task: <strong>$taskTitle</strong> to <em>$fullNameReassignedTo</em>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Tasks','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	
	
	//letting user that was previously assigned to the task that it has been reassigned
	if (isset($prevAssignedTo) && $prevAssignedTo !== $taskRequestedByUserID && $prevAssignedTo !== $userID) {
	// INSERTING NOTIFICATION //
	$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&eventID=$taskID>Your task <strong>$taskTitle</strong> in: <strong>Project - $taskProjectTitle</strong>. has been reassigned to <em>$fullNameReassignedTo</em></a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$prevAssignedTo','$taskProjectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	/////////// SENDING EMAIL to user that was previously assigned to the task ///////////	
	
			
	$userInfoPrevAssignedTo = getUserInfo($prevAssignedTo);
	$emailToEmailPrevAssignedTo =$userInfoPrevAssignedTo["userEmail"];
	$fullNamePrevAssignedTo = $userInfoPrevAssignedTo["userFirstName"] ." ".$userInfoPrevAssignedTo["userLastName"];
		
	$userInfoRequestedBy = getUserInfo($taskRequestedByUserID);
	$fullNameRequestedBy = $userInfoRequestedBy["userFirstName"] ." ". $userInfoRequestedBy["userLastName"];
		
	$taskDueDateEditFinal = date('m/d/Y @ g:ia',strtotime($taskDueDate));
	
		
	$subject = "Your task has been reassigned in the project: ".$taskProjectTitleNoInsert.".";
	
		$to = $emailToEmailPrevAssignedTo;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your task has been reassigned to <em>'.$fullNameReassignedTo.'</em> in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$fullNameRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescriptionNoInsert.'<br>
             </p>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	//letting user that task IS NOW ASSIGNED TO
	if (isset($memberID) && $memberID !== $taskRequestedByUserID && $memberID !== $userID) {
		// INSERTING NOTIFICATION //
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$taskID>A new task: <strong>$taskTitle</strong> has been assigned to you in: <strong>$taskProjectTitle</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$memberID','$taskProjectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());

		
	//////// SENDING EMAIL ///////	
		
	if (isset($memberID)) {
	
	//Getting user info
	$userInfo2 = getUserInfo($memberID);
	$emailToEmail =$userInfo2["userEmail"];
	$emailToFN = $userInfo2["userFirstName"];
		
	$userInfoRequestedBy = getUserInfo($taskRequestedByUserID);
	$fullNameRequestedBy = $userInfoRequestedBy["userFirstName"] ." ". $userInfoRequestedBy["userLastName"];
		
	$subject = "You have been assigned a new task in the project: $taskProjectTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              New task assigned to you in the project <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>the following task has been assigned to you.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$fullNameRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescriptionNoInsert.'<br>
             </p></div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	}
	

}

function updateTask($taskID,$newTitle,$newDesc,$newCategoryID, $newDueDate, $newStatus) {
	global $connection;
	global $userID;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	if ($newStatus === "Completed") {
		$updateTask = "UPDATE `Tasks` SET `Title`='$newTitle',`Description`='$newDesc',`Due Date`='$newDueDate',`End Date`=NULL,`Category`='$newCategoryID',`Status`='$newStatus',`Task Date Completed`=now() WHERE `TaskID` = '$taskID'";
	$updateTask_result = mysqli_query($connection, $updateTask) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	else {
		$updateTask = "UPDATE `Tasks` SET `Title`='$newTitle',`Description`='$newDesc',`Due Date`='$newDueDate',`End Date`=NULL,`Category`='$newCategoryID',`Status`='$newStatus',`Task Date Completed`=NULL WHERE `TaskID` = '$taskID'";
	$updateTask_result = mysqli_query($connection, $updateTask) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	
	
	
	
}

function updateTaskEndDate($taskID,$newTitle,$newDesc,$newCategoryID, $newDueDate, $newStatus, $newEndDate) {
	global $connection;
	global $userID;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	if ($newStatus === "Completed") {
		$updateTask = "UPDATE `Tasks` SET `Title`='$newTitle',`Description`='$newDesc',`Due Date`='$newDueDate',`End Date`='$newEndDate',`Category`='$newCategoryID',`Status`='$newStatus',`Task Date Completed`=now() WHERE `TaskID` = '$taskID'";
	$updateTask_result = mysqli_query($connection, $updateTask) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	else {
		$updateTask = "UPDATE `Tasks` SET `Title`='$newTitle',`Description`='$newDesc',`Due Date`='$newDueDate',`End Date`='$newEndDate',`Category`='$newCategoryID',`Status`='$newStatus',`Task Date Completed`=null WHERE `TaskID` = '$taskID'";
	$updateTask_result = mysqli_query($connection, $updateTask) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	
	
	
	
	
}

function createTaskCalendarEvent($taskID,$newTitle,$newDesc,$newCategoryID, $newStartDate, $newEndDate, $newProjectID) {
	global $connection;
	global $userID;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	
	$t1 = strtotime($taskDueDate);
$t2 = strtotime($taskEventEndDate);
$diff = $t1 - $t2;
$hours = $diff / ( 60 * 60 );
	
	if ($hours>24) {
		$allDayVal = "true";
	}
	else {
		$allDayVal = "false";
	}
	
	
$insert = mysqli_query($connection,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`,`ProjectID`,`TaskID`) VALUES('$newTitle','$newStartDate','$newEndDate','$newCategoryID','$userID','$newDesc','$allDayVal','$newProjectID','$taskID')");
			$eventID = mysqli_insert_id($connection);

			////////// CREATING FILE UPLOAD FOLDER //////////
			$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID;

			mkdir($path, 0777, true);
			chmod($path, 0777);
}

function updateTaskCalendarEvent($taskID,$newTitle,$newDesc,$newCategoryID, $newStartDate, $newEndDate) {
	global $connection;
	global $userID;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	
	$t1 = strtotime($taskDueDate);
$t2 = strtotime($taskEventEndDate);
$diff = $t1 - $t2;
$hours = $diff / ( 60 * 60 );
	
	if ($hours>24) {
		$allDayVal = "true";
	}
	else {
		$allDayVal = "false";
	}
	
	
$updateEvent = "UPDATE `calendar` SET `title`='$newTitle',`description`='$newDesc',`category`='$newCategoryID',`startdate`='$newStartDate',`enddate`='$newEndDate',`allDay`='$allDayVal' WHERE `TaskID` = '$taskID'";
$updateEvent_result = mysqli_query($connection, $updateEvent) or die ("Query to get data from Team Project failed: ".mysql_error());
	
}

function deleteTaskCalendarEvent($taskID) {
	global $connection;
	global $userID;
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	
	/////////// INSERTING NOTIFICATION ///////////
			
			//Getting project members
			$getGroupMembers = "SELECT `SubscriptionID`, `userID`, `Calendar Categories`.`Category`,`Notification Subscription`.`CalendarCategoryID` FROM `Notification Subscription` JOIN `Calendar Categories` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Notification Subscription`.`CalendarCategoryID` = '$taskCategoryID' AND `userID` != '$userID'";
			$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
				while($row = mysqli_fetch_array($getGroupMembers_result)) {
					$groupMembers[] =$row["userID"];
				}

			foreach ($groupMembers as $name5) {
				$notification = "<a href=/dashboard/content-calendar/?>The <strong>$taskEventCategory</strong> event: <strong>$taskEventTitle</strong> has been deleted.</a>";
				$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name5','$taskEventID')";
				$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			}

			/////////// INSERTING ACTIVITY /////////////
			$activity = "deleted the <strong>$taskEventCategory</strong> event: <strong>$taskEventTitle</strong> on the Content Calendar.";
			$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Event','$userID','$taskProjectID')";
			$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());

			$delete = mysqli_query($connection,"DELETE FROM `calendar` where id='$taskEventID'");
			if($delete)
				echo json_encode(array('status'=>'success'));
			else
				echo json_encode(array('status'=>'failed'));

			//// DELETING UPLOAD FOLDER
			//delete_files('uploads/'.$taskEventID);
			$path=$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$taskEventID;
			foreach(glob("$path/*") as $file)
			{
				if(is_dir($file)) { 
					recursiveRemoveDirectory($file);
				} else {
					unlink($file);
				}
			}
			rmdir( $path );
	
}

function addTaskComment($taskID,$comment) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	$commentNoInsert=$comment;
	$comment=addslashes($comment);
	
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = addslashes($taskInfo["title"]);
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = addslashes($taskInfo["description"]);
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = addslashes($taskInfo["projectTitle"]);
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = addslashes($taskInfo["eventTitle"]);
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];


	$addTaskMessage = "INSERT INTO `Task Comments`(`Message`, `userID`, `ProjectID`, `TaskID`, `Sent By`) VALUES ('$comment','$taskAssignedToUserID','$taskProjectID','$taskID','$userID')";
	$addTaskMessage_result = mysqli_query($connection, $addTaskMessage) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	// INSERTING NOTIFICATION //
	
		$notification = "<a href=/dashboard/team-projects/view/?projectID=$taskProjectID&taskID=$taskID>A new comment has been added to the task: <strong>$taskTitle</strong> in: <strong>Project - $taskProjectTitle</strong>.<br><br><em>&#34;$comment&#34;</em></a>";
		
	if ($taskRequestedByUserID !== $userID){
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskRequestedByUserID','$taskProjectID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	
	if ($taskAssignedToUserID !== $userID){
		$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$taskAssignedToUserID','$taskProjectID')";
		$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}	
	
	
		
		
		
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new comment to the task: <em>$taskTitle</em>.<br><br><em><p>$comment</p></em>";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$taskProjectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	/////////// SENDING EMAIL ///////////
	
	if (isset($taskRequestedByUserID) && $ticketRequestedByUserID !== $userID && $taskRequestedByUserID !== $userID) {
	
						
	//getting info
	$userInfo = getUserInfo($taskRequestedByUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
				
		
	$subject = "NEW COMMENT: A new comment has been added to your task in the project: ".$taskProjectTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new comment has been added to your task: '.$taskTitleNoInsert.' in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span> by <strong>'.$FN.' '.$LN.'</strong>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'</h2>
             
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$commentNoInsert.'</div>
             <br>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

	if (isset($taskAssignedToUserID) && $taskAssignedToUserID !== $userID) {
	
	//getting info
	$userInfo = getUserInfo($taskAssignedToUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
				
	$userInfoRequestedBy = getUserInfo($taskRequestedByUserID);
	$fullNameRequestedBy = $userInfoRequestedBy["userFirstName"] ." ". $userInfoRequestedBy["userLastName"];
		
	$subject = "NEW COMMENT: A new comment has been added to your task in the project: ".$taskProjectTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new comment has been added to your task: '.$taskTitleNoInsert.' in <span style="text-decoration: underline">'.$taskProjectTitleNoInsert.'</span> by <strong>'.$FN.' '.$LN.'</strong>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$taskCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$fullNameRequestedBy.'</span></h2>
             
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$commentNoInsert.'</div>
             <br>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$taskProjectID.'&taskID='.$taskID.'" class="button">View Task</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

}

function deleteComment($commentID) {
	global $connection;
	global $userID;
	
	//////// DELETING AFTER NOTIFICATION
	$RemoveTaskComment = "DELETE FROM `Task Comments` WHERE `CommentID` = '$commentID'";
    $RemoveTaskComment_result = mysqli_query($connection, $RemoveTaskComment) or die(mysqli_error($connection));
}


//**MEMBERSHIP
function addProjectMember($memberUserID,$projectID) {
	
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	
	//getting all 
	$projectInfo = getProjectInfo($projectID);
	
	$projectTitle = addslashes($projectInfo["title"]);
	$projectTitleNoInsert = $projectInfo["title"];
	$projectDesc = $projectInfo["description"];
	$projectDueDate = $projectInfo["dueDate"];
	$projectDueDateDisplay = $projectInfo["dueDateDisplay"];
	$projectDateCreated = $projectInfo["dateCreated"];
	$projectDateCreatedDisplay = $projectInfo["dateCreatedDisplay"];
	$projectDateCompleted = $projectInfo["dateCompleted"];
	$projectDateCompletedDisplay = $projectInfo["dateCompletedDisplay"];
	$projectStatus = $projectInfo["Status"];
	$projectCategoryID = $projectInfo["categoryID"];
	$projectCategoryTitle = $projectInfo["categoryTitle"];
	$projectTaskType = $projectInfo["taskType"];
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectOwnerFullName = $projectInfo["ownerFullName"];
	$projectOwnerPP = $projectInfo["ownerPP"];
	
	//checking to see if user is already in project
	$getAllMembers = "SELECT `userID` FROM `Team Projects Member List` WHERE `userID`='$memberUserID' AND `ProjectID` = '$projectID'";
	$getAllMembers_result = mysqli_query($connection, $getAllMembers) or die(mysqli_error($connection));
	$memberCount = mysqli_num_rows($getAllMembers_result);
	
	if ($memberCount == 0) {
		$AddMember = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$memberUserID')";
    $AddMember_result = mysqli_query($connection, $AddMember) or die(mysqli_error($connection));

/////////// INSERTING NOTIFICATION ///////////
	
	
	$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>You have been added to the project: <strong>$projectTitle</strong>.</a>";
	$notification = addslashes($notification);
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Membership','$memberUserID','$projectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	//Getting ADDEE'S name
	$getAddeeUsername = "SELECT `username` FROM `user` WHERE `userID` = '$memberUserID'";
	$getAddeeUsername_result = mysqli_query($connection, $getAddeeUsername) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getAddeeUsername_result->fetch_assoc()) {
			$AddeeUsername = $row["username"];
	}
	$activity = "added <strong>@$AddeeUsername</strong> to the project.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Membership','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
		
	//////// SENDING EMAIL ///////
		
	if (isset($memberUserID)) {
	
	//getting info
	$userInfo = getUserInfo($memberUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	
	$subject = "You have been added to the project: ".$projectTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Welcome to the project <span style="text-decoration: underline">'.$projectTitleNoInsert.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>you have been added to a new project.
              <br><br>
              <a href="https://dashboard.coat.com/dashboard/team-projects/view/?projectID='.$projectID.'" class="button">View Project</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
		

		
 
	}
	else {
		
	}
	
	

}

function deleteProjectMember($memberUserID,$projectID) {
	
	global $connection;
	global $userID;
	global $myRole;
	global $groupID;
	
	$projectInfo = getProjectInfo($projectID);
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectCreatedByGroupID = $projectInfo["ownerGroupID"];
	
	//getting membership list 
	$getMembershipList = "SELECT * FROM `user` JOIN `Team Projects Member List` ON `user`.`userID`= `Team Projects Member List`.`userID` WHERE `ProjectID` = '$projectID'";
	
	$getMembershipList_result = mysqli_query($connection, $getMembershipList) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMembershipList_result->fetch_assoc()) {
		$memberIDArray[] = $row["userID"];
		$memberID = $row["userID"];
		$memberFN = $row["First Name"];
		$memberPic = $row["PP Link"];
		$memberUsername = $row["username"];
	
		if ($memberID != $userID || $myRole != 'Admin' || ($myRole != 'Editor' && $groupID != $projectCreatedByGroupID)) {
			return false;
			
		}
	}
	//check to see if user has any tasks in this project - then reassign to project owner
	$memberAssignedToInfo = getTaskAssignedToCountInProjectForMember($memberUserID,$projectID);
	$taskCountAssignedTo = $memberAssignedToInfo["taskCount"];
	$taskIDsAssignedTo = $memberAssignedToInfo["taskIDs"];
	
	$memberRequestedByInfo = getTaskRequestedByCountInProjectForMember($memberUserID,$projectID);
	$taskCountRequestedBy = $memberRequestedByInfo["taskCount"];
	$taskIDsRequestedBy = $memberRequestedByInfo["taskIDs"];
	
	
	
	
	//if user has more than 0 assigned to tasks, return information for popup
	if ($taskCountAssignedTo > 0) {
		
		foreach ($taskIDsAssignedTo as $taskIDAssignedTo) {
			reassignTask($taskIDAssignedTo, $projectOwnerUserID);
		}	
		
	}
	
	//if user has more than 0 requested by tasks, return information for popup
	if ($taskCountRequestedBy > 0) {
		
		foreach ($taskIDsRequestedBy as $taskIDRequestedBy) {
			$update = mysqli_query($connection,"UPDATE `Tasks` SET `Requested By`='$projectOwnerUserID' WHERE `TaskID`='$taskIDRequestedBy'");
		}	
		
	}
	
	
	$RemoveMember = "DELETE FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID' AND `userID` = '$memberUserID'";
    	$RemoveMember_result = mysqli_query($connection, $RemoveMember) or die(mysqli_error($connection));
		
			//Getting user info
			$userInfo = getUserInfo($memberUserID);
			$thisUserUsername = $userInfo["userUsername"];
		
	$activity = "deleted <strong>@$thisUserUsername</strong> from the project.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Membership','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	

}


//**REVIEWS
function addReview($projectID,$reviewType,$reviewTitle,$reviewTitleNoInsert,$dueDate,$members,$desktopFile,$mobileFile){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;
	
	
	$query = "INSERT INTO `Tickets Review`(`userID`, `ProjectID`, `Title`, `Type`, `Due Date`, `Desktop Preview Image Link`, `Mobile Preview Image Link`) VALUES ('$userID','$projectID','$reviewTitle','$reviewType','$dueDate','','')";
	$query_result = mysqli_query($connection, $query) or die ("query1 failed: ".mysql_error());
	
	$reviewID = $connection->insert_id;
	//ADDING MEMBERS
	//ADDING YOURSELF
	$query2 = "INSERT INTO `Tickets Review Members`(`userID`, `ReviewID`, `Status`) VALUES ('$userID','$reviewID','Approved')";
	$query2_result = mysqli_query($connection, $query2) or die ("Query2 failed: ".mysql_error());
	
	if (isset($members)) {
		foreach($members as $val){
			if ($val !== $userID) {
				addReviewMember($val, $reviewID);
			}
			
		}
	
}
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/review/uploads/'.$reviewID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	
	//inserting desktop image if set
	if (isset($desktopFile)) {
		addReviewImage("Desktop", $desktopFile, $reviewID);
	}
	//inserting mobile image if set
	if (isset($mobileFile)) {
		addReviewImage("Mobile", $mobileFile, $reviewID);
	}
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new $reviewType Review: <strong>$reviewTitle</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ReviewID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("Query7 failed: ".mysql_error());	
	
	
	return $reviewID;
}

function updateReviewTitle($reviewID,$reviewTitle){
	global $connection;
	
	$query = "UPDATE `Tickets Review` SET `Title`='$reviewTitle' WHERE `ReviewID`='$reviewID'";
	$query_result = mysqli_query($connection, $query) or die(mysqli_error($connection));
}

function deleteReview($reviewID){
	
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	//Deleting review
	$query = "DELETE FROM `Tickets Review` WHERE `ReviewID` = '$reviewID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//Deleting review comments
	$query2 = "DELETE FROM `Tickets Review Comments` WHERE `ReviewID` = '$reviewID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//Deleting review members
	$query3 = "DELETE FROM `Tickets Review Members` WHERE `ReviewID` = '$reviewID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//Deleting review images
	$query4 = "DELETE FROM `Tickets Review Preview Images` WHERE `ReviewID` = '$reviewID'";
	$query4_result = mysqli_query($connection, $query4) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//deleting markups
	$query6 = "DELETE FROM `Tickets Review MarkUps` WHERE `ReviewID` = '$reviewID'";
	$query6_result = mysqli_query($connection, $query6) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//deleted files also
	$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/review/uploads/'.$reviewID;
	
	chmod($path, 0777);
	$files = glob($path.'/*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
		unlink($file); // delete file
	}
	rmdir($path);
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "deleted the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
}

function checkReviewApprovalStatus($reviewID) {
	
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;
	
	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	$getMemberCount = "SELECT COUNT(`userID`) FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID'";
	$getMemberCount_result = mysqli_query($connection, $getMemberCount) or die ("Query to get data from Team task failed: ".mysql_error());
	while($row = $getMemberCount_result->fetch_assoc()) {
		$memberCount = $row["COUNT(`userID`)"];
	}
	
	$getApprovalCount = "SELECT COUNT(`userID`) FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `Status` ='Approved'";
	$getApprovalCount_result = mysqli_query($connection, $getApprovalCount) or die ("Query to get data from Team task failed: ".mysql_error());
	while($row = $getApprovalCount_result->fetch_assoc()) {
		$approvalCount = $row["COUNT(`userID`)"];
	}
	
	if ($approvalCount === $memberCount) {
		$updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Approved' WHERE `ReviewID` = '$reviewID'";
		$updateReviewStatus_result = mysqli_query($connection, $updateReviewStatus) or die ("Query to get data from Team task failed: ".mysql_error());
		
		//getting members in db
	$query355 = "SELECT DISTINCT `userID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID'";
	$query355_result = mysqli_query($connection, $query355) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query355_result->fetch_assoc()) {
		$memberUserID1s[]=$row["userID"];
	}
		
		
		foreach ($memberUserID1s as $thisname) {
			
			
				//notifications
			$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID>APPROVED - $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$thisname','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			//////// SENDING EMAIL ///////	
		
	if (isset($thisname)) {
	
	$userInfo = getUserInfo($thisname);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];		
	
	$subject = "APPROVED - $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" >
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						 
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              The '.$reviewTypeTitle.' Review: <span style="text-decoration:underline">'.$reviewTitleNoInsert.'</span> has been approved!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
			
			
		}
		
	}
	else {
		$updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Not Approved' WHERE `ReviewID` = '$reviewID'";
		$updateReviewStatus_result = mysqli_query($connection, $updateReviewStatus) or die ("Query to get data from Team task failed: ".mysql_error());
	}
}

function addReviewMember($memberUserID, $reviewID){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;
	
	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	
		
		$query444 = "INSERT INTO `Tickets Review Members`(`userID`, `ReviewID`) VALUES ('$memberUserID','$reviewID')";
		$query444_result = mysqli_query($connection, $query444) or die ("Query3 failed: ".mysql_error());
		
		$notification = "<a href=/dashboard/team-projects/view/review/?reviewID=$reviewID target=_blank>You have been added to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$memberUserID','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("Query6 failed: ".mysql_error());
		

		//////// SENDING EMAIL ///////	
		
		if (isset($memberUserID)) {
	
	$userInfo = getUserInfo($memberUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];		
	
	$subject = "$reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              You have been added to a '.$reviewTypeTitle.' Review!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>'.$FN.' '.$LN.' added you to the '.$reviewTypeTitle.' Review: <strong>'.$reviewTitleNoInsert.'</strong>.
              <br><br>
             
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added <strong>$emailToFullName</strong> to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ReviewID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("Query7 failed: ".mysql_error());	 
	
	//setting review to not approved
	$updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Not Approved' WHERE `ReviewID` = '$reviewID'";
		$updateReviewStatus_result = mysqli_query($connection, $updateReviewStatus) or die ("Query to get data from Team task failed: ".mysql_error());
	
}

function deleteReviewMember($memberUserID, $reviewID){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;
	
	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	$query2 = "DELETE FROM `Tickets Review Members` WHERE `userID`='$memberUserID' AND `ReviewID`='$reviewID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	/////////// INSERTING NOTIFICATION ///////////
	$notification = "<a href=#>You have been removed from the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$memberUserID','$reviewID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("Query6 failed: ".mysql_error());
	
	checkReviewApprovalStatus($reviewID);
}

function addReviewImage($imageType, $file, $reviewID){
	global $connection;
	global $userID;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	//inserting desktop image if set
	
		
      $errors= array();
      $file_name = $file['name'];
      $file_size =$file['size'];
      $file_tmp =$file['tmp_name'];
      $file_type=$file['type'];
      $file_ext=strtolower(end(explode('.',$file['name'])));
      
      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed.";
      }
      
      if($file_size > 104857600000){
         $errors[]='File size must be less than 2 MB';
      }
      
      if(empty($errors)==true){
		  
		  //getting last id for version control
		  $getLastID = "SELECT `ImageID` FROM `Tickets Review Preview Images` WHERE `ReviewID` ='$reviewID' ORDER BY `ImageID` DESC LIMIT 1";
	$getLastID_result = mysqli_query($connection, $getLastID) or die ("Query to get data from Team task failed: ".mysql_error());
	while($row = $getLastID_result->fetch_assoc()) {
		$lastID = $row["ImageID"];
		if (isset($lastID)) {
			$lastID = $lastID+1;
		}
		else {
			$lastID = 0;
		}
		
		
	}
		  
		  $path =$_SERVER["DOCUMENT_ROOT"].'/dashboard/team-projects/view/review/uploads/'.$reviewID.'/'.$reviewTypeTitle.'_'.$lastID.'_'.$file_name;
		  $pathInsert ='uploads/'.$reviewID.'/'.$reviewTypeTitle.'_'.$lastID.'_'.$file_name;
         move_uploaded_file($file_tmp,$path);
		 chmod($path, 0777);
		  //move_uploaded_file($file_tmp,"uploads/".$file_name);
         echo "Your file was successfully uploaded.";
      	//adding image url to db
	   $update = mysqli_query($connection,"UPDATE `Tickets Review` SET `$imageType Preview Image Link`='$pathInsert' WHERE `ReviewID`='$reviewID'");
		  
		  
		  
	   $query = "INSERT INTO `Tickets Review Preview Images`(`Preview Image Link`, `Type`, `ReviewID`, `userID`) VALUES ('$pathInsert','$imageType','$reviewID','$userID')";
		$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

		  
	  }else{
         echo ($errors);
      }
   
   
	
	
	
	
}

function addReviewComment($comment, $reviewID, $mentionUserIDs){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $username;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	$comment = addslashes($comment);
	$commentEmail = $comment;
	
	
	
	//getting members in db
	$query3 = "SELECT `userID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query3_result->fetch_assoc()) {
		$memberUserIDs[]=$row["userID"];
	}
	
	if ($mentionUserIDs) {
		
		foreach($mentionUserIDs as $mentionID){
			$getMentionUsername = "SELECT * FROM `user` WHERE `userID` = '$mentionID'";
			$getMentionUsername_result = mysqli_query($connection, $getMentionUsername) or die ("Query to get data from Team Project failed: ".mysql_error());
			while($row = $getMentionUsername_result->fetch_assoc()) {
				$mentionUsernames[] = $row["username"];
			}
		}
		
		$arrayString = implode(" @",$mentionUsernames);
		
		$newMentionMessage = "<br><strong>@".$arrayString."</strong>:<br>".$comment;
		$newMentionMessageNoInsert = "<strong>@".$arrayString."</strong>:<br>".$commentEmail;
		
		$query = "INSERT INTO `Tickets Review Comments`(`ReviewID`, `userID`, `Comment`) VALUES ('$reviewID','$userID','$newMentionMessage')";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
		
		
		
		
		foreach ($mentionUserIDs as $name) {
		$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID><strong>$FN $LN</strong> mentioned you in the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.<br><br><em>$newMentionMessage</em></a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$name','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////	
		
	if (isset($name)) {
	
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];		
	
	$subject = "$FN $LN mentioned you in the $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new comment has been added to the '.$reviewType.' Review: '.$reviewTitleNoInsert.'.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>'.$FN.' '.$LN.' mentioned you in the '.$reviewTypeTitle.' Review: <strong>'.$reviewTitleNoInsert.'</strong>.
              <br><br>
             
             <div class="task">
			 
			 <p class="pull-left"> <strong>Timestamp: </strong>'.date("M/d/Y @ h:ia").'</p>
			
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$newMentionMessageNoInsert.'</div>
             <br>
			 
			 </div>
              <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	}
		
		
		/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a comment to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.<br><em>$newMentionMessage</em>";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ReviewID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("Query7 failed: ".mysql_error());	 
		
		
	}
	else {
		
		$query = "INSERT INTO `Tickets Review Comments`(`ReviewID`, `userID`, `Comment`) VALUES ('$reviewID','$userID','$comment')";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
		/////////// INSERTING NOTIFICATION ///////////
	foreach ($memberUserIDs as $name) {
		$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID>A new comment has been added to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.<br><br><strong>@$username</strong>:<br><em>$comment</em></a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$name','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////	
		
	if (isset($name)) {
	
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];		
	
	$subject = "NEW COMMENT - $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new comment has been added to the '.$reviewTypeTitle.' Review.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>'.$FN.' '.$LN.' added a new comment to the '.$reviewTypeTitle.' Review: <strong>'.$reviewTitleNoInsert.'</strong>.
              <br><br>
             
             <div class="task">
			 
			 <p class="pull-left"> <strong>Timestamp: </strong>'.date("M d Y @ h:ia").'</p>
			
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$commentEmail.'</div>
             <br>
			 
			 </div>
              <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	}
	
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a comment to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.<br><br><em>$comment</em>";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ReviewID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("Query7 failed: ".mysql_error());	 
	
	}
	
}

function userApprovedReview($reviewID){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	$getAll = "SELECT `userID` FROM `Tickets Review` WHERE `ReviewID` ='$reviewID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
		$ownerUserID = $row["userID"];
	}
	
	
	
	//setting personal approval
	$query = "UPDATE `Tickets Review Members` SET `Status`='Approved' WHERE `userID` = '$userID' AND `ReviewID` ='$reviewID'";
		$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	
	if (isset($ownerUserID) && $ownerUserID != $userID) {
		
		$userInfo = getUserInfo($ownerUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];	
		
		//notifications
			$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID>$FN $LN has <strong>approved</strong> the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$ownerUserID','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
		
	$subject = "APPROVED - $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
     
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              '.$FN.' '.$LN.' has approved the '.$reviewTypeTitle.' Review: <span style="text-decoration:underline">'.$reviewTitleNoInsert.'</span>.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	checkReviewApprovalStatus($reviewID);

}

function userNotApprovedReview($reviewID){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	$query = "UPDATE `Tickets Review Members` SET `Status`=NULL WHERE `userID` = '$userID' AND `ReviewID` ='$reviewID'";
		$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Not Approved' WHERE `ReviewID` = '$reviewID'";
		$updateReviewStatus_result = mysqli_query($connection, $updateReviewStatus) or die ("Query to get data from Team task failed: ".mysql_error());
	
	
	//getting owner
	$query355 = "SELECT `userID` FROM `Tickets Review` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID'";
	$query355_result = mysqli_query($connection, $query355) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query355_result->fetch_assoc()) {
		$ownerUserID=$row["userID"];
	}
	
	if (isset($ownerUserID)) {
	
	//notifications
		$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID>$FN $LN has <strong>unapproved</strong> the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$ownerUserID','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		
	$userInfo = getUserInfo($ownerUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];		
	
	$subject = "DISAPPROVED - $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
     
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              '.$FN.' '.$LN.' has unapproved the '.$reviewTypTitle.' Review: <span style="text-decoration:underline">'.$reviewTitleNoInsert.'</span>.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

}

function addReviewMockupMarkup($reviewID,$markUp, $xPos, $yPos, $imageID, $whichImage){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	$markUp = addslashes($markUp);
	$markUpEmail = $markUp;


	if (strpos($whichImage, 'Desktop') !== false) {
		$markUpType = "Desktop";
	}
	else {
		$markUpType = "Mobile";
	}
	
	$query = "INSERT INTO `Tickets Review MarkUps`(`ReviewID`,`ImageID`, `userID`, `markUp`, `xPos`, `yPos`) VALUES ('$reviewID','$imageID','$userID','$markUp','$xPos','$yPos')";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//getting members in db
	$query3 = "SELECT `userID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query3_result->fetch_assoc()) {
		$memberUserIDs[]=$row["userID"];
	}
	
	///////// INSERTING NOTIFICATION ///////////
	foreach ($memberUserIDs as $name) {
		$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID><strong>$FN $LN</strong> added a new $markUpType markup to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$name','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////	
		
	if (isset($name)) {
	
	//getting members
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];		
	
	$subject = "NEW MARKUP - $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new '.$markUpType.' markup has been added to this '.$reviewTypeTitle.' Review.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>'.$FN.' '.$LN.' added a new '.$markUpType.' markup to the '.$reviewTypeTitle.' Review: <strong>'.$reviewTitleNoInsert.'</strong>.
              <br><br>
             
             <div class="task">
			 <p class="pull-left"> <strong>Timestamp: </strong>'.date("M,d,Y @ h:ia").'</p>
			
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$markUpEmail.'</div>
             <br>
			 
			 </div>
              <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	}
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new markup to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ReviewID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("Query7 failed: ".mysql_error());	 
	
}

function updateReviewMockupMarkup($reviewID,$markUpID, $markUp, $whichImage){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	$markUp = addslashes($markUp);
	$markUpEmail = $markUp;


	if (strpos($whichImage, 'Desktop') !== false) {
		$markUpType = "Desktop";
	}
	else {
		$markUpType = "Mobile";
	}
	
	$query = "UPDATE `Tickets Review MarkUps` SET `markUp` ='$markUp',`Timestamp` =now() WHERE `markUpID` = '$markUpID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//getting members in db
	$query3 = "SELECT `userID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query3_result->fetch_assoc()) {
		$memberUserIDs[]=$row["userID"];
	}
	
	/////////// INSERTING NOTIFICATION ///////////
	foreach ($memberUserIDs as $name) {
		$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID><strong>$FN $LN</strong> updated their markup in the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$name','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
	}
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "updated their markup to the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ReviewID`) VALUES ('$activity','Review','$userID','$reviewID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("Query7 failed: ".mysql_error());	 
}

function deleteReviewMockupMarkup($markUpID){
	global $connection;
	
	$query2 = "DELETE FROM `Tickets Review MarkUps` WHERE `markUpID`='$markUpID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
}

function sendReviewMockupUpdateEmail($reviewID,$mockupType){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	
	//getting members in db
	$query3 = "SELECT `userID`,`memberID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query3_result->fetch_assoc()) {
		$memberUserIDs[]=$row["userID"];
	}
	
	foreach ($memberUserIDs as $name) {
	//////// SENDING EMAIL ///////	
		
	if (isset($name)) {
	
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];	
	
	$subject = "New $mockupType Mockup: $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new '.$mockupType.' mockup has been added to the '.$reviewTypeTitle.' Review: '.$reviewTitleNoInsert.'.
            </td>
          </tr>
          <tr>
            <td class="free-text">
  
              
			  <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
			  <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	}

}

function nudgeReviewUsers($reviewID){
	global $connection;
	global $headers;
	global $emailCss;
	global $userID;
	global $FN;
	global $LN;

	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitle = addslashes($reviewInfo["title"]);
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDesktopImageID = $reviewInfo["desktopImageID"];
	$reviewDesktopImage = $reviewInfo["desktopImage"];
	$reviewDesktopMarkupCount = $reviewInfo["desktopMarkupCount"];
	$reviewMobileImageID = $reviewInfo["mobileImageID"];
	$reviewMobileImage = $reviewInfo["mobileImage"];
	$reviewMobileMarkupCount = $reviewInfo["mobileMarkupCount"];
	$reviewDueDate = $reviewInfo["dueDate"];
	$reviewDueDateDisplay = $reviewInfo["dueDateDisplay"];
	$reviewDateCreated = $reviewInfo["dateCreated"];
	$reviewDateCreatedDisplay = $reviewInfo["dateCreatedDisplay"];
	$reviewStatus = $reviewInfo["Status"];
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewProjectID = $reviewInfo["projectID"];
	$reviewProjectTitle = addslashes($reviewInfo["projectTitle"]);
	$reviewProjectTitleNoInsert = $reviewInfo["projectTitle"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
		//getting members in db
	$query3 = "SELECT `userID` FROM `Tickets Review Members` WHERE `ReviewID` ='$reviewID' AND `userID` !='$userID' AND `Status` IS NULL";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while($row = $query3_result->fetch_assoc()) {
		$memberUserIDs[]=$row["userID"];
	}
	
	foreach ($memberUserIDs as $memberUserID) {

		if (isset($memberUserID)) {
			
		/////////// INSERTING NOTIFICATION ///////////
		$notification = "<a target=_blank href=/dashboard/team-projects/view/review/?reviewID=$reviewID><strong>REMINDER:</strong> please review and approve the $reviewTypeTitle Review: <strong>$reviewTitle</strong>.</a>";
	
		$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ReviewID`) VALUES ('$notification','Review','$memberUserID','$reviewID')";
		$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			
		//////// SENDING EMAIL ///////	
		
	$userInfo = getUserInfo($memberUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];	
	
	$subject = "ACTION REQUIRED - $reviewTypeTitle Review: $reviewTitleNoInsert.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
             
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          
          <tr>
            <td class="free-text">
              Hi <strong>'.$emailToFN.'</strong>,<br>'.$FN.' '.$LN.' is requesting your approval of the mockup(s) in the '.$reviewTypeTitle.' Review: <strong>'.$reviewTitleNoInsert.'</strong>.
              <br><br>
             
			 
              <a href="https://dashboard.coat.com/dashboard/team-projects/view/review/?reviewID='.$reviewID.'" class="button">View Review</a>
              
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	

			
		}
		
	}

}
	
function markAllReviewApproved($reviewID){
	global $connection;
	global $userID;

	//setting personal approval
	$query = "UPDATE `Tickets Review Members` SET `Status`='Approved' WHERE `ReviewID` ='$reviewID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Approved' WHERE `ReviewID` = '$reviewID'";
	$updateReviewStatus_result = mysqli_query($connection, $updateReviewStatus) or die ("Query to get data from Team task failed: ".mysql_error());
	
	

}

function updateReview($reviewID,$reviewTitle,$reviewType,$reviewDueDate) {
	global $connection;
	global $userID;
	
	$reviewTitleNew = addslashes($reviewTitle);
	$reviewDueDateNew = date("Y-m-d H:i:s",strtotime($reviewDueDate));
	
	
	$query = "UPDATE `Tickets Review` SET `Title` ='$reviewTitleNew',`Type` ='$reviewType',`Due Date` ='$reviewDueDateNew' WHERE `ReviewID` = '$reviewID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
}

//********* FUNCTIONS FOR NOTEBOOKS *********

//**NOTEBOOKS
function addNotebook($title, $color){
	global $connection;
	global $userID;
	
	$title = addslashes($title);
	
	
	$insert = mysqli_query($connection,"INSERT INTO Notebooks(`title`, `color`, `userID`) VALUES('$title','$color','$userID')");
	
	$notebookID = mysqli_insert_id($connection);
	
	
	return $notebookID;

}

function updateNotebook($notebookID, $title, $color){
	global $connection;
	global $userID;
	
	
	$title = addslashes($title);
	
	$update = mysqli_query($connection,"UPDATE Notebooks SET `title`='$title', `color`='$color', `userID`='$userID' WHERE `NotebookID`='$notebookID'");
	
	$notebookID = mysqli_insert_id($connection);
	
	return $notebookID;

}

function deleteNotebook($notebookID){
	global $connection;
	global $userID;
	
	
	$query2 = "DELETE FROM `Notebooks Pages` WHERE `NotebookID` = '$notebookID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	
	$query3 = "DELETE FROM `Notebooks` WHERE `NotebookID` = '$notebookID'";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

}

//**PAGES
function addNotebookPage($notebookID, $title){
	global $connection;
	global $userID;
	
	$title = addslashes($title);
	$insert = mysqli_query($connection,"INSERT INTO `Notebooks Pages`(`NotebookID`,`title`) VALUES('$notebookID','$title')");
	
	$pageID = mysqli_insert_id($connection);

	return $pageID;

}

function updateNotebookPage($pageID, $title, $content){
	global $connection;
	global $userID;
	
	
	$title = addslashes($title);
	$content = addslashes($content);
	
	$update = mysqli_query($connection,"UPDATE `Notebooks Pages` SET `Title`='$title', `Content`='$content', `Last Updated`=NOW() WHERE `PageID`='$pageID'");
	
	$pageID = mysqli_insert_id($connection);
	
	return $pageID;

}

function deleteNotebookPage($pageID){
	global $connection;
	global $userID;
	
	
	$query2 = "DELETE FROM `Notebooks Pages` WHERE `PageID` = '$pageID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());

}
	

//********* FUNCTIONS FOR CONTENT CALENDAR *********

//**EVENTS
function addEvent($title, $startdate, $enddate, $categoryID,$description, $allday,$desktopImage, $mobileImage){
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	$titleNoInsert = $title;
	$title = addslashes($title);
	$descriptionNoInsert = $description;
	$description = addslashes($description);
	
	$insert = mysqli_query($connection,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`) VALUES('$title','$startdate','$enddate','$categoryID','$userID','$description','$allDay')");
	
	$eventID = $connection->insert_id;
	
	//getting all 
	$eventInfo = getEventInfo($eventID);
	
	$eventTitle = addslashes($eventInfo["title"]);
	$eventTitleNoInsert = $eventInfo["title"];
	$eventDesc = addslashes($eventInfo["description"]);
	$eventDescNoInsert = $eventInfo["description"];
	$eventStartDate = $eventInfo["startDate"];
	$eventEndDateDisplay = $eventInfo["endDate"];
	$eventCategoryID = $eventInfo["categoryID"];
	$eventCategoryTitle = $eventInfo["categoryTitle"];
	$eventProjectID = $eventInfo["projectID"];
	$eventTaskID = $eventInfo["taskID"];
	$eventOwnerUserID = $eventInfo["ownerUserID"];
	$eventOwnerFullName = $eventInfo["ownerFullName"];
	$eventOwnerPP = $eventInfo["ownerPP"];
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	
	$path = $_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	
	//if desktop image exists
	if(isset($desktopImage)){
      $errors= array();
      $file_name = $desktopImage['name'];
      $file_size =$desktopImage['size'];
      $file_tmp =$desktopImage['tmp_name'];
      $file_type=$desktopImage['type'];
      $file_ext=strtolower(end(explode('.',$desktopImage['name'])));
      
      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed.";
      }
      
      if($file_size > 104857600000){
         $errors[]='File size must be less than 2 MB';
      }
      
      if(empty($errors)==true){
		  $path2 =$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID.'/Desktop_'.$file_name;
		  $pathForInsert ='uploads/'.$eventID.'/Desktop_'.$file_name;
         move_uploaded_file($file_tmp,$path2);
		 chmod($path2, 0777);
		
         echo "Your desktop file was successfully uploaded.";
      	//adding image url to db
	   $update = mysqli_query($connection,"UPDATE `calendar` SET `Preview Image Link`='$pathForInsert' WHERE `id`='$eventID'");

		  
	  }
		else{
         echo ($errors);
      }
   
   }

	else {
		echo "ERROR: desktop file not uploaded. Please try again.";
	}
	
	//if mobile image exists
	if(isset($mobileImage)){
      $errors= array();
      $file_name = $mobileImage['name'];
      $file_size =$mobileImage['size'];
      $file_tmp =$mobileImage['tmp_name'];
      $file_type=$mobileImage['type'];
      $file_ext=strtolower(end(explode('.',$mobileImage['name'])));
      
      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed.";
      }
      
      if($file_size > 104857600000){
         $errors[]='File size must be less than 2 MB';
      }
      
      if(empty($errors)==true){
		  $path2 =$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID.'/Mobile_'.$file_name;
		  $pathForInsert ='uploads/'.$eventID.'/Mobile_'.$file_name;
         move_uploaded_file($file_tmp,$path2);
		 chmod($path2, 0777);
		
         echo "Your mobile file was successfully uploaded.";
      	//adding image url to db
	   $update = mysqli_query($connection,"UPDATE `calendar` SET `Preview Image Link Mobile`='$pathForInsert' WHERE `id`='$eventID'");

		  
	  }
		else{
         echo ($errors);
      }
   
   }

	else {
		echo "ERROR: mobile file not uploaded. Please try again.";
	}
	
	/////////// INSERTING NOTIFICATION ///////////
	
	//Getting project members
	$getGroupMembers = "SELECT `userID` FROM `Notification Subscription` WHERE `CalendarCategoryID` = '$eventCategoryID' AND `userID` != '$userID'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];	
		
			
		}
	if (isset($groupMembers)) {
		foreach ($groupMembers as $name2) {
		$notification = "<a href=/dashboard/content-calendar/?eventID=$eventID>The <strong>$eventCategoryTitle</strong> event: <strong>$eventTitle&nbsp;</strong>has been added to the Content Calendar.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name2','$eventID')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////	

	if (isset($name2)) {
	
	$userInfo = getUserInfo($name2);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];
	
	$subject = "New Content Calendar ".$eventCategoryTitle." Event: ".$eventTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
     
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new '.$eventCategoryTitle.' event has been added to the Content Calendar.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              '.$FN.' '.$LN.' added the following '.$eventCategoryTitle.' event to the Content Calendar.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$eventCategoryTitle.'</p><p class="pull-left"> <strong>Date: </strong>'.date('m/d/Y @ g:ia',strtotime($startdate)).' -  '.date('m/d/Y @ g:ia',strtotime($enddate)).'</p>
			</div>
             
             <a href="https://dashboard.coat.com/dashboard/content-calendar/?eventID='.$eventID.'" class="button">View Event</a>
              <br><br>
			  <br>
		 <a href="https://dashboard.coat.com/dashboard/content-calendar/unsubscribe/?categoryID='.$eventCategoryID.'" style="text-decoration:underline !important;font-size:12px !important;">Unsubscribe from <strong>'.$eventCategoryTitle.'</strong> Calendar Event alerts.</a>
            </td>
          </tr>
          
        </table>
		
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
       
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		
		if ($name2 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}
		
		}
	}
	else {
		$groupMembers[] = '';
	}
	
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "added the <strong>$eventCategoryTitle</strong> event: <strong>$eventTitle</strong> to the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	
	
	
	return $eventID;
	
	

	
}

function updateEvent($eventID, $title, $startdate, $enddate, $categoryID,$description, $allday){
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	
	$titleNoInsert = $title;
	$title = addslashes($title);
	$descriptionNoInsert = $description;
	$description = addslashes($description);
	
	$update = mysqli_query($connection,"UPDATE `calendar` SET `title`='$title',`startdate`='$startdate',`enddate`='$enddate',`Category`='$categoryID',`Description`='$description',`allDay`='$allDay' where `id`='$eventID'");
	
	
	//getting all 
	$eventInfo = getEventInfo($eventID);
	
	$eventTitle = addslashes($eventInfo["title"]);
	$eventTitleNoInsert = $eventInfo["title"];
	$eventDesc = addslashes($eventInfo["description"]);
	$eventDescNoInsert = $eventInfo["description"];
	$eventStartDate = $eventInfo["startDate"];
	$eventEndDateDisplay = $eventInfo["endDate"];
	$eventCategoryID = $eventInfo["categoryID"];
	$eventCategoryTitle = $eventInfo["categoryTitle"];
	$eventProjectID = $eventInfo["projectID"];
	$eventTaskID = $eventInfo["taskID"];
	$eventOwnerUserID = $eventInfo["ownerUserID"];
	$eventOwnerFullName = $eventInfo["ownerFullName"];
	$eventOwnerPP = $eventInfo["ownerPP"];
	
	
	if (isset($eventTaskID)) {
		$update2 = mysqli_query($connection,"UPDATE `Tasks` SET `Title`='$title',`Due Date`='$startdate',`End Date`='$enddate',`Description`='$description',`allDay`='$allDay' where `TaskID`='$eventTaskID'");
	}
	
	
	
	/////////// INSERTING NOTIFICATION ///////////
	
	//Getting project members
	$getGroupMembers = "SELECT `userID` FROM `Notification Subscription` WHERE `CalendarCategoryID` = '$categoryID' AND `userID` != '$userID'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];
			
		}
	if (isset($groupMembers)) {
	foreach ($groupMembers as $name3) {
		$notification = "<a href=/dashboard/content-calendar/?eventID=$eventID>The <strong>$eventCategoryTitle</strong> event: <strong>$eventTitle</strong> has been updated.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name3','$eventID')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////		
			
	if (isset($name3)) {
	
	//getting members
	$userInfo = getUserInfo($name3);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];
	
	$subject = "Updated Content Calendar ".$eventCategoryTitle." Event: ".$titleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
             
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A '.$eventCategoryTitle.' event has been updated in the Content Calendar.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              '.$FN.' '.$LN.' added the following '.$eventCategoryTitle.' event to the Content Calendar.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$eventCategoryTitle.'</p><p class="pull-left"> <strong>Date: </strong>'.date('m/d/Y @ g:ia',strtotime($eventStartDate)).'</p>
				 
             <br><h2>'.$titleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/content-calendar/?eventID='.$eventID.'" class="button">View Event</a>
              <br><br>
			  <br>
		 <a href="https://dashboard.coat.com/dashboard/content-calendar/unsubscribe/?categoryID='.$eventCategoryID.'" style="text-decoration:underline !important;font-size:12px !important;">Unsubscribe from <strong>'.$eventCategoryTitle.'</strong> Calendar Event alerts.</a>
            </td>
          </tr>
          
        </table>
		
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		
		if ($name3 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}
	}
	}
	else {
		$groupMembers[] = '';
	}
	/////////// INSERTING ACTIVITY /////////////
	$activity = "updated the <strong>$eventCategoryTitle</strong> event: <strong>$eventTitle</strong> on the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	

}

function deleteEvent($eventID){
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	//getting all 
	$eventInfo = getEventInfo($eventID);
	
	$eventTitle = addslashes($eventInfo["title"]);
	$eventTitleNoInsert = $eventInfo["title"];
	$eventDesc = addslashes($eventInfo["description"]);
	$eventDescNoInsert = $eventInfo["description"];
	$eventStartDate = $eventInfo["startDate"];
	$eventEndDateDisplay = $eventInfo["endDate"];
	$eventCategoryID = $eventInfo["categoryID"];
	$eventCategoryTitle = $eventInfo["categoryTitle"];
	$eventProjectID = $eventInfo["projectID"];
	$eventTaskID = $eventInfo["taskID"];
	$eventOwnerUserID = $eventInfo["ownerUserID"];
	$eventOwnerFullName = $eventInfo["ownerFullName"];
	$eventOwnerPP = $eventInfo["ownerPP"];
	

	//Getting project members
	$getGroupMembers = "SELECT `userID` FROM `Notification Subscription` WHERE `CalendarCategoryID` = '$eventCategoryID' AND `userID` != '$userID'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];
			
		}
	if (isset($groupMembers)) {
	foreach ($groupMembers as $name5) {
		$notification = "<a href=/dashboard/content-calendar/?>The <strong>$eventCategoryTitle</strong> event: <strong>$eventTitle</strong> has been deleted.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name5','$eventID')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	}
	else {
		$groupMembers[] = '';
	}
	
	/////////// INSERTING ACTIVITY /////////////
	$activity = "deleted the <strong>$eventCategoryTitle</strong> event: <strong>$eventTitle</strong> on the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	$delete = mysqli_query($connection,"DELETE FROM `calendar` where id='$eventID'");
	
	//// DELETING UPLOAD FOLDER
	//delete_files('uploads/'.$eventid);
	$path=$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID;
	foreach(glob("$path/*") as $file)
    {
        if(is_dir($file)) { 
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
	rmdir( $path );



	
}

//**MOCKUPS
function addEventMockup($eventID, $mockupType, $image){
	global $connection;
	global $userID;
	
	//getting all 
	$eventInfo = getEventInfo($eventID);
	
	$eventTitle = addslashes($eventInfo["title"]);
	$eventTitleNoInsert = $eventInfo["title"];
	$eventDesc = addslashes($eventInfo["description"]);
	$eventDescNoInsert = $eventInfo["description"];
	$eventStartDate = $eventInfo["startDate"];
	$eventEndDateDisplay = $eventInfo["endDate"];
	$eventCategoryID = $eventInfo["categoryID"];
	$eventCategoryTitle = $eventInfo["categoryTitle"];
	$eventProjectID = $eventInfo["projectID"];
	$eventTaskID = $eventInfo["taskID"];
	$eventOwnerUserID = $eventInfo["ownerUserID"];
	$eventOwnerFullName = $eventInfo["ownerFullName"];
	$eventOwnerPP = $eventInfo["ownerPP"];
	$eventDesktopImagePath = $eventInfo["desktopImagePath"];
	$eventMobileImagePath = $eventInfo["mobileImagePath"];
	
	if($mockupType === "Desktop"){
	//if desktop image exists
	if(isset($image)){
      $errors= array();
      $file_name = $image['name'];
      $file_size =$image['size'];
      $file_tmp =$image['tmp_name'];
      $file_type=$image['type'];
      $file_ext=strtolower(end(explode('.',$image['name'])));
      
      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed.";
      }
      
      if($file_size > 104857600000){
         $errors[]='File size must be less than 2 MB';
      }
      
      if(empty($errors)==true){
		  $path2 =$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID.'/Desktop_'.$file_name;
		  $pathForInsert ='uploads/'.$eventID.'/Desktop_'.$file_name;
         move_uploaded_file($file_tmp,$path2);
		 chmod($path2, 0777);
		
         $result = "success";
      	//adding image url to db
	   $update = mysqli_query($connection,"UPDATE `calendar` SET `Preview Image Link`='$pathForInsert' WHERE `id`='$eventID'");

		  //deleting prev file here...
		  unlink($_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/'.$eventDesktopImagePath);
	  }
		else{
         $result = "fail";
      }
   
   }

	else {
		$result = "ERROR: desktop file not uploaded. Please try again.";
	}
	}
	else {
		//if mobile image exists
	if(isset($image)){
      $errors= array();
      $file_name = $image['name'];
      $file_size =$image['size'];
      $file_tmp =$image['tmp_name'];
      $file_type=$image['type'];
      $file_ext=strtolower(end(explode('.',$image['name'])));
      
      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed.";
      }
      
      if($file_size > 104857600000){
         $errors[]='File size must be less than 2 MB';
      }
      
      if(empty($errors)==true){
		  $path2 =$_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/uploads/'.$eventID.'/Mobile_'.$file_name;
		  $pathForInsert ='uploads/'.$eventID.'/Mobile_'.$file_name;
         move_uploaded_file($file_tmp,$path2);
		 chmod($path2, 0777);
		
         $result = "success";
      	//adding image url to db
	   $update = mysqli_query($connection,"UPDATE `calendar` SET `Preview Image Link Mobile`='$pathForInsert' WHERE `id`='$eventID'");
		 
		  //deleting prev file here...
		  unlink($_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/'.$eventMobileImagePath);
		  
	  }
		else{
         $result = "fail";
      }
   
   }

	else {
		$result = "ERROR: mobile file not uploaded. Please try again.";
	}
	}
	
	
	

}

function deleteEventMockup($eventID, $path, $mockupType) {
	global $connection;
	global $userID;
	
	if ($mockupType === "Desktop") {
		$update = mysqli_query($connection,"UPDATE `calendar` SET `Preview Image Link`='' where id='$eventID'");
	}
	else {
		$update = mysqli_query($connection,"UPDATE `calendar` SET `Preview Image Link Mobile`='' where id='$eventID'");
	}
   
	//deleting file
	unlink($_SERVER["DOCUMENT_ROOT"].'/dashboard/content-calendar/'.$path);
	
}


//********* FUNCTIONS FOR TICKETS *********

function addTicket($title, $url, $description, $dueDate, $categoryID, $copy) {

global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	$titleNoInsert = $title;
	$title = addslashes($title);
	$descriptionNoInsert = $description;
	$description = addslashes($description);
	$copyNoInsert = $copy;
	$copy = addslashes($copy);
	$urlNoInsert = $url;
	$url = addslashes($url);
	
//getting owner
$getTicketsOwner = "SELECT * FROM `Tickets Admin Owner`";
	$getTicketsOwner_result = mysqli_query($connection, $getTicketsOwner) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());

	while($row = mysqli_fetch_array($getTicketsOwner_result)) {
		$ownerUserID =$row["userID"];	
	}	
	

$addTicket = "INSERT INTO `Tickets`(`Title`, `Description`, `URL`, `Due Date`, `Requested By`,`Owner`, `Category`, `Copy`) VALUES ('$title','$description','$url','$dueDate','$userID','$ownerUserID','$categoryID','$copy')";
$addTicket_result = mysqli_query($connection, $addTicket) or die(mysqli_error($connection));

$ticketID = $connection->insert_id;


//Getting team members
	$getTeamMembers = "SELECT * FROM `Group Membership` WHERE `GroupID` = '1' AND `userID` != '$userID' AND `userID` != '9'";
	$getTeamMembers_result = mysqli_query($connection, $getTeamMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());

	while($row = mysqli_fetch_array($getTeamMembers_result)) {
		$teamMembers[] =$row["userID"];	
	}
	
	$ticketInfo = getTicketInfo($ticketID);
	
	$ticketProjectID = $ticketInfo["projectID"];
	$ticketTitle = addslashes($ticketInfo["title"]);
	$ticketTitleNoInsert = $ticketInfo["title"];
	$ticketDesc = addslashes($ticketInfo["description"]);
	$ticketDescNoInsert = $ticketInfo["description"];
	$ticketDueDate = $ticketInfo["dueDate"];
	$ticketStatus = $ticketInfo["Status"];
	$ticketCategoryID = $ticketInfo["categoryID"];
	$ticketCategoryTitle = $ticketInfo["categoryTitle"];
	$ticketURL = $ticketInfo["URL"];
	$ticketRequestedByUserID = $ticketInfo["requestedByUserID"];
	$ticketRequestedByFullName = $ticketInfo["requestedByFullName"];
	$ticketRequestedByPP = $ticketInfo["requestedByPP"];
	$ticketTimestamp = $ticketInfo["Timestamp"];
	$ticketCopy = addslashes($ticketInfo["Copy"]);
	$ticketCopyNoInsert = $ticketInfo["Copy"];
	

		foreach ($teamMembers as $name) {
			$notification2 = "<a href=/dashboard/requests/view/?ticketID=$ticketID>A new ticket: <strong>$ticketTitle</strong> has been submitted.</a>";
			$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification2','Ticket','$name')";
			$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			//////// SENDING EMAIL ///////	

	if (isset($name)) {
	
	$userInfo = getUserInfo($name);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];
	
	$subject = "A new ticket: $ticketTitleNoInsert has been submitted.";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new ticket has been submitted to the Dashboard.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              '.$FN.' '.$LN.' added a new ticket to the Dashboard.
              <br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$ticketCategoryTitle.'</p><p class="pull-left"> <strong>Go Live Date: </strong>'.date('m/d/Y @ g:ia',strtotime($ticketDueDate)).'</p>
			 <p class="pull-left"> <strong>Status: </strong>'.$ticketStatus.'</p>
			 <p class="pull-left"> <strong>URL: </strong>'.$ticketURL.'</p>
			 
			 
			 
				 
             <br><h2>'.$ticketTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
			 
			 <p><strong> Description: </strong><br>'.$ticketDescNoInsert.'<br>
             </p>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/requests/view/?ticketID='.$ticketID.'" class="button">View Ticket</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		
		if ($name2 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}
		}

	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new ticket to the Dashboard: <strong>$title</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$ticketID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
	
	
	return $ticketID;
	

}

function updateTicket($ticketID, $title, $url, $description, $copy, $dueDate, $status, $categoryID){
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	

	$title=addslashes($title);
$url=addslashes($url);
$description=addslashes($description);
$copy=addslashes($copy);
	


	//saving all 
	$saveTicket = "UPDATE `Tickets` SET `Title`='$title',`URL`='$url',`Description`='$description',`Copy`='$copy',`Due Date`='$dueDate',`Status`='$status',`Category`='$categoryID' WHERE `TicketID` = '$ticketID'";
	$saveTicket_result = mysqli_query($connection, $saveTicket) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	
	
	$ticketInfo = getTicketInfo($ticketID);
	
	$ticketProjectID = $ticketInfo["projectID"];
	$ticketTitle = addslashes($ticketInfo["title"]);
	$ticketTitleNoInsert = $ticketInfo["title"];
	$ticketDesc = addslashes($ticketInfo["description"]);
	$ticketDescNoInsert = $ticketInfo["description"];
	$ticketDueDate = $ticketInfo["dueDate"];
	$ticketStatus = $ticketInfo["Status"];
	$ticketCategoryID = $ticketInfo["categoryID"];
	$ticketCategoryTitle = $ticketInfo["categoryTitle"];
	$ticketURL = $ticketInfo["URL"];
	$ticketRequestedByUserID = $ticketInfo["requestedByUserID"];
	$ticketRequestedByFullName = $ticketInfo["requestedByFullName"];
	$ticketRequestedByPP = $ticketInfo["requestedByPP"];
	$ticketTimestamp = $ticketInfo["Timestamp"];
	$ticketCopy = addslashes($ticketInfo["Copy"]);
	$ticketCopyNoInsert = $ticketInfo["Copy"];
	
	//saving in project too if project exists
	
	if (isset($ticketProjectID)) {
		$saveProject = "UPDATE `Team Projects` SET `Title`='$ticketTitle',`URL To Use`='$ticketURL',`Description`='$ticketDesc',`Copy`='$ticketCopy',`Due Date`='$ticketDueDate',`Status`='$ticketStatus',`Category`='$ticketCategoryID' WHERE `ProjectID` = '$ticketProjectID'";
		$saveProject_result = mysqli_query($connection, $saveProject) or die ("Query to get data from Team Project failed: ".mysql_error());
		
		
	}
	
		/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "updated the ticket: <strong>$ticketTitle</strong>.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$ticketID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	

	
	//if project is completed - notification + email to requested by
	
	if($ticketStatus === 'Complete' && isset($ticketRequestedByUserID) && $ticketRequestedByUserID != $userID) {
			
			$notification3 = "<a href=/dashboard/requests/my-requests/?ticketID=$ticketID><strong>TICKET #$ticketID: $ticketTitle</strong> has been completed!</a>";
			$addNotification3 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `TicketID`) VALUES ('$notification3','Task','$ticketRequestedByUserID','$ticketID')";
			$addNotification3_result = mysqli_query($connection, $addNotification3) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	//getting members
	$userInfo = getUserInfo($ticketRequestedByUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
	$emailToFullName = $userInfo["userFirstName"]." ".$userInfo["userLastName"];
		
	$subject = "TICKET #$ticketID: $ticketTitleNoInsert has been completed!";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              TICKET #'.$ticketID.': <span style="text-decoration: underline">'.$ticketTitleNoInsert.'</span> has been completed!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              
             <a href="https://dashboard.coat.com/dashboard/requests/my-requests/?ticketID='.$ticketID.'" class="button">View Ticket</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);
		}
	


	
}

function deleteTicket($ticketID) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	
	$ticketInfo = getTicketInfo($ticketID);
	
	$ticketProjectID = $ticketInfo["projectID"];
	$ticketTitle = addslashes($ticketInfo["title"]);
	$ticketTitleNoInsert = $ticketInfo["title"];
	$ticketDesc = addslashes($ticketInfo["description"]);
	$ticketDescNoInsert = $ticketInfo["description"];
	$ticketDueDate = $ticketInfo["dueDate"];
	$ticketStatus = $ticketInfo["Status"];
	$ticketCategoryID = $ticketInfo["categoryID"];
	$ticketCategoryTitle = $ticketInfo["categoryTitle"];
	$ticketURL = $ticketInfo["URL"];
	$ticketRequestedByUserID = $ticketInfo["requestedByUserID"];
	$ticketRequestedByFullName = $ticketInfo["requestedByFullName"];
	$ticketRequestedByPP = $ticketInfo["requestedByPP"];
	$ticketOwnerUserID = $ticketInfo["ownerUserID"];
	$ticketTimestamp = $ticketInfo["Timestamp"];
	$ticketCopy = addslashes($ticketInfo["Copy"]);
	$ticketCopyNoInsert = $ticketInfo["Copy"];
	
	
	/////////// NOTIFYING REQUESTED BY ///////////
	//if requested by did not delete, 
	if ($ticketRequestedByUserID && $ticketRequestedByUserID != $userID) {
			$notification2 = "<a href=/dashboard/requests/my-requests>Your ticket: <strong>$ticketTitle</strong> has been deleted by $FN $LN.</a>";
			$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification2','Ticket','$ticketRequestedByUserID')";
			$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	}
	//if owner did not delete, 
	if ($ticketOwnerUserID && $ticketOwnerUserID != $userID) {
			/////////// NOTIFYING REQUESTED BY ///////////
	
			$notification3 = "<a href=/dashboard/requests/my-requests>Your ticket: <strong>$ticketTitle</strong> has been deleted by $FN $LN.</a>";
			$addNotification3 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification3','Ticket','$ticketOwnerUserID')";
			$addNotification3_result = mysqli_query($connection, $addNotification3) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "deleted the ticket: <em>$ticketTitle</em>.";
	$activity = addslashes($activity);
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$ticketID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
	$DeleteRequest = "DELETE FROM `Tickets` WHERE `TicketID` = '$ticketID'";
    $DeleteRequest_result = mysqli_query($connection, $DeleteRequest) or die(mysqli_error($connection));

}

function addTicketComment($ticketID,$comment) {
	global $connection;
	global $userID;
	global $headers;
	global $emailCss;
	global $FN;
	global $LN;
	
	
	$commentNoInsert=$comment;
	$comment=addslashes($comment);
	
	$ticketInfo = getTicketInfo($ticketID);
	
	$ticketProjectID = $ticketInfo["projectID"];
	$ticketTitle = addslashes($ticketInfo["title"]);
	$ticketTitleNoInsert = $ticketInfo["title"];
	$ticketDesc = addslashes($ticketInfo["description"]);
	$ticketDescNoInsert = $ticketInfo["description"];
	$ticketDueDate = $ticketInfo["dueDate"];
	$ticketStatus = $ticketInfo["Status"];
	$ticketCategoryID = $ticketInfo["categoryID"];
	$ticketCategoryTitle = $ticketInfo["categoryTitle"];
	$ticketURL = $ticketInfo["URL"];
	$ticketRequestedByUserID = $ticketInfo["requestedByUserID"];
	$ticketRequestedByFullName = $ticketInfo["requestedByFullName"];
	$ticketRequestedByPP = $ticketInfo["requestedByPP"];
	$ticketOwnerUserID = $ticketInfo["ownerUserID"];
	$ticketTimestamp = $ticketInfo["Timestamp"];
	$ticketCopy = addslashes($ticketInfo["Copy"]);
	$ticketCopyNoInsert = $ticketInfo["Copy"];


	$addMessage = "INSERT INTO `Tickets Comments`(`Message`, `TicketID`, `Sent By`) VALUES ('$comment','$ticketID','$userID')";
	$addMessage_result = mysqli_query($connection, $addMessage) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	// INSERTING NOTIFICATION //
$notification = "<a href=/dashboard/requests/my-requests/?ticketID=$ticketID>A new comment has been added to the ticket: <strong>$ticketTitle</strong>.<br><br><em>&#34;$comment&#34;</em></a>";
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "added a new comment to the ticket: <em>$ticketTitle</em> by <strong>$FN $LN</strong>.<br><br><em><p>$comment</p></em>";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$ticketID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	/////////// SENDING EMAIL ///////////
	
	if (isset($ticketRequestedByUserID) && $ticketRequestedByUserID !== $userID) {
	
		
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `TicketID`) VALUES ('$notification','Ticket','$ticketRequestedByUserID','$ticketID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
						
	//getting info
	$userInfo = getUserInfo($ticketRequestedByUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
		
	$subject = "NEW COMMENT: A new comment has been added to your ticket: ".$ticketTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new comment has been added to your ticket: '.$ticketTitleNoInsert.' by <strong>'.$FN.' '.$LN.'</strong>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$ticketCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($ticketDueDate)).'</p>
				 
             <br><h2>'.$ticketTitleNoInsert.'</h2>
             
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$commentNoInsert.'</div>
             <br>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/requests/my-requests/?ticketID='.$ticketID.'" class="button">View Ticket</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

	if (isset($ticketOwnerUserID) && $ticketOwnerUserID !== $userID) {
	
	$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `TicketID`) VALUES ('$notification','Ticket','$ticketOwnerUserID','$ticketID')";
	$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
	//getting info
	$userInfo = getUserInfo($ticketOwnerUserID);
	$emailToEmail =$userInfo["userEmail"];
	$emailToFN = $userInfo["userFirstName"];
				
	
		
	$subject = "NEW COMMENT: A new comment has been added to your ticket: ".$ticketTitleNoInsert.".";
	
		$to = $emailToEmail;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
            
              
              
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
              
            
            
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              A new comment has been added to the ticket: '.$ticketTitleNoInsert.' by <strong>'.$FN.' '.$LN.'</strong>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$ticketCategoryTitle.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($ticketDueDate)).'</p>
				 
             <br><h2>'.$ticketTitleNoInsert.'</h2>
             
			 
			 <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$commentNoInsert.'</div>
             <br>
			 
			 </div>
             
             <a href="https://dashboard.coat.com/dashboard/requests/my-requests/?ticketID='.$ticketID.'" class="button">View Ticket</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

}

function deleteTicketComment($commentID) {
	global $connection;
	global $userID;
	
	//////// DELETING AFTER NOTIFICATION
	$RemoveComment = "DELETE FROM `Tickets Comments` WHERE `CommentID` = '$commentID'";
    $RemoveComment_result = mysqli_query($connection, $RemoveComment) or die(mysqli_error($connection));
}



?>