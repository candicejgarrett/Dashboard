<?php 
require('../connect.php');
require('../header.php');
require('../emailDependents.php');
$type=$_POST['type'];
$requestID=$_POST['requestID'];

if ($type=="getAll") {
	
	$getRequests = "SELECT * FROM `Tickets` ORDER BY `Timestamp` DESC";
	$getRequests_result = mysqli_query($connection, $getRequests) or die ("Query to get data from Team Project failed: ".mysql_error());
											
								while ($row = mysqli_fetch_array($getRequests_result)) {
									$ticketID = $row["TicketID"];
									$ticketTitle = $row["Title"];
									$ticketDescription = $row["Description"];
									$ticketURL = $row["URL"];
									$ticketTimestamp = $row["Timestamp"];
									$ticketDueDate = $row["Due Date"];
									$ticketContactName = $row["Contact Name"];
									$ticketContactEmail = $row["Contact Email"];
									$ticketStatus = $row["Status"];
									$ticketProjectID = $row["ProjectID"];
									$printDate = date("m/d/Y", strtotime($ticketDueDate));
									$printTimestamp = date("m/d/Y", strtotime($ticketTimestamp));
									
									$printTickets[] = "<div class='individualTickets' id='$ticketID'><p class='pull-right ticketStatus'>$ticketStatus</p><p>Ticket ID: <strong class='ticketID'>$ticketID</strong></p><br><h4>$ticketTitle</h4><p>Submitted On: <strong>$printTimestamp</strong><p>Due Date: <strong>$printDate</strong></div>";
								}

////////////
	
	$results = ["printTickets" => $printTickets];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
}
if ($type=="load") {
	
	//getting all 
	$getAll = "SELECT `Tickets`.`TicketID`, `Tickets`.`Title`, `Description`, `Copy`, `URL`, `Tickets`.`Timestamp`, DATE_FORMAT(`Tickets`.`Due Date`,'%Y-%m-%dT%H:%i:%s') AS 'Due Date', `Requested By`, `Owner`, `Tickets`.`Status`, `ProjectID`, `Team Projects Categories`.`Category`,`Team Projects Categories`.`ProjectCategoryID`, `First Name`, `Last Name`, `PP Link` FROM `Tickets` JOIN `Team Projects Categories` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` JOIN `user` ON `Tickets`.`Requested By` = `user`.`userID` WHERE `Tickets`.`TicketID` = '$requestID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $TicketTitle = $row["Title"];
		$Description = $row["Description"];
		$DueDate1 = $row["Due Date"];
		$DueDate = date("m/d/Y", strtotime($DueDate1));
		$Status = $row["Status"];
		$Category = $row["Category"];
		$CategoryID = $row["ProjectCategoryID"];
		$URL = $row["URL"];
		$ContactUserID = $row["Requested By"];
		$ContactName = $row["First Name"]." ".$row["Last Name"];
		$Timestamp = $row["Timestamp"];
		$ProjectID = $row["ProjectID"];
		$Copy = $row["Copy"];
		$ContactPP = $row["PP Link"];
		
	}
	if (isset($ProjectID)) {
		$actions = '<button id="editRequest" class="edit noExpand"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><a href="../team-projects/view/?projectID='.$ProjectID.'" class="createNew btnCorrect"><i class="fa fa-eye" aria-hidden="true"></i></a><button id="deleteRequest" class="remove"><i class="fa fa-trash" aria-hidden="true"></i></button>';
	}
	else {
		$actions = '<button id="editRequest" class="edit noExpand"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><button id="showModal" data-toggle="modal" data-target="#addNewProject" class="createNew"><i class="fa fa-plus" aria-hidden="true"></i><div class="btnExpand">Create New Project</div></button><button id="deleteRequest" class="remove noExpand"><i class="fa fa-trash" aria-hidden="true"></i></button>';
	}

	
	
////////////
	
	$results = ["requestTitle" => $TicketTitle, "requestDescription" => $Description, "requestDueDateEdit" => $DueDate1,"requestDueDate" => $DueDate, "requestStatus" => $Status, "requestURL" => $URL, "requestContactName" => $ContactName, "requestContactPP" => $ContactPP, "requestContactUserID" => $ContactUserID, "requestTimestamp" => $Timestamp, "requestCategory" => $Category, "requestCategoryID" => $CategoryID,  "requestCopy" => $Copy,"requestID" => $requestID, "actions" => $actions];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
}
if ($type=="save") {
	

$requestTitle=addslashes($_POST['requestTitle']);
$requestURL=$_POST['requestURL'];
$requestDescription=addslashes($_POST['requestDescription']);
$requestCopy=addslashes($_POST['requestCopy']);
$requestDueDate=$_POST['requestDueDate'];
$requestStatus=$_POST['requestStatus'];
$requestCategory=$_POST['requestCategory'];

	//saving all 
	$saveTicket = "UPDATE `Tickets` SET `Title`='$requestTitle',`URL`='$requestURL',`Description`='$requestDescription',`Copy`='$requestCopy',`Due Date`='$requestDueDate',`Status`='$requestStatus',`Category`='$requestCategory' WHERE `TicketID` = '$requestID'";
	$saveTicket_result = mysqli_query($connection, $saveTicket) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	//getting all 
	$getAll = "SELECT * FROM `Tickets` WHERE `TicketID` = '$requestID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $Title = $row["Title"];
		$Description = $row["Description"];
		$Copy = $row["Copy"];
		$DueDate = $row["Due Date"];
		$DueDate = date("m/d/Y", strtotime($DueDate));
		$Status = $row["Status"];
		$URL = $row["URL"];
		$Category = $row["Category"];
		$ContactUserID = $row["Requested By"];
		$Timestamp = $row["Timestamp"];
		$ProjectID = $row["ProjectID"];
	}
	
	//saving in project too if project exists
	
	if (isset($ProjectID)) {
		$saveProject = "UPDATE `Team Projects` SET `Title`='$requestTitle',`URL To Use`='$requestURL',`Description`='$requestDescription',`Copy`='$requestCopy',`Due Date`='$requestDueDate',`Status`='$requestStatus',`Category`='$requestCategory' WHERE `ProjectID` = '$ProjectID'";
	$saveProject_result = mysqli_query($connection, $saveProject) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	
		/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "updated the ticket: <strong>$requestTitle</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$requestID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	

	
	//if project is completed - notification + email to requested by
	
	if($requestStatus === 'Complete' && isset($ContactUserID) && $ContactUserID != $userID) {
			
			$notification3 = "<a href=/dashboard/requests/my-requests/?ticketID=$requestID><strong>TICKET #$requestID: $Title</strong> has been completed!</a>";
			$addNotification3 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `TicketID`) VALUES ('$notification3','Task','$ContactUserID','$requestID')";
			$addNotification3_result = mysqli_query($connection, $addNotification3) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$ContactUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "TICKET #$requestID: $Title has been completed!";
	
		$to = $projectMember;
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
    <td align="left" valign="top" width="100%" style="background:repeat-x url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) #ffffff;">
      <center>
      <img src="http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png" class="force-width-gmail">
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" background="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            <!--[if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:80px; v-text-anchor:middle;">
              <v:fill type="tile" src="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" color="#ffffff" />
              <v:textbox inset="0,0,0,0">
            <![endif]-->
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/me.php">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
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
              TICKET #'.$requestID.': <span style="text-decoration: underline">'.$Title.'</span> has been completed!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              
             <a href="https://dashboard.coat.com/dashboard/requests/my-requests/?ticketID='.$requestID.'" class="button">View Ticket</a>
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
if ($type=="delete") {
	$getRequest = "SELECT * FROM `Tickets` WHERE `TicketID` = '$requestID'";
	$getRequest_result = mysqli_query($connection, $getRequest) or die ("Query to get data from Team Project failed: ".mysql_error());
											
								while ($row = mysqli_fetch_array($getRequest_result)) {
									$ticketID = $row["TicketID"];
									$ticketTitle = $row["Title"];
									$ticketDescription = $row["Description"];
									$ticketURL = $row["URL"];
									$ticketTimestamp = $row["Timestamp"];
									$ticketDueDate = $row["Due Date"];
									$ticketRequestedBy = $row["Requested By"];
									$ticketOwner = $row["Owner"];
									$ticketStatus = $row["Status"];
									$ticketProjectID = $row["ProjectID"];
									$printDate = date("m/d/Y", strtotime($ticketDueDate));
									$printTimestamp = date("m/d/Y", strtotime($ticketTimestamp));
									
								}
	
	
	/////////// NOTIFYING REQUESTED BY ///////////
	//if requested by did not delete, 
	if ($ticketRequestedBy != $userID) {
			$notification2 = "<a href=/dashboard/requests/my-requests>Your ticket: <strong>$title</strong> has been deleted by @$username.</a>";
			$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification2','Ticket','$ticketRequestedBy')";
			$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
	}
	//if owner did not delete, 
	if ($ticketOwner != $userID) {
			/////////// NOTIFYING REQUESTED BY ///////////
	
			$notification3 = "<a href=/dashboard/requests/view.php>Your assigned ticket: <strong>$title</strong> has been deleted by @$username.</a>";
			$addNotification3 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification3','Ticket','$ticketOwner')";
			$addNotification3_result = mysqli_query($connection, $addNotification3) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
	
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "deleted the ticket: <em>$ticketTitle</em>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$requestID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
	$DeleteRequest = "DELETE FROM `Tickets` WHERE `TicketID` = '$requestID'";
    $DeleteRequest_result = mysqli_query($connection, $DeleteRequest) or die(mysqli_error($connection));
}
if ($type=="createProject") {
	
	$projectTaskType=$_POST['taskType'];
	$projectTemplate=$_POST['templateID'];
	//getting all 
	$getAll = "SELECT * FROM `Tickets` WHERE `TicketID` = '$requestID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $projectTitle = $row["Title"];
		$projectDescription = $row["Description"];
		if (!isset($row["Copy"])) {
		$Copy = '';
		}
		else {
			$Copy = $row["Copy"];
		}
		
		$projectDueDate = $row["Due Date"];
		$projectCategory = $row["Category"];
		$Status = $row["Status"];
		$projectURL = $row["URL"];
		$ContactName = $row["Contact Name"];
		$ContactEmail = $row["Contact Email"];
		$Timestamp = $row["Timestamp"];
	}
	
	
	
	if ($projectTemplate == "Blank") {
		//inserting record
	$addProject = "INSERT INTO `Team Projects`(`Status`,`Title`, `Description`, `Category`, `Due Date`, `userID`, `Visible`,`URL To Use`,`Task Type`,`Copy`) VALUES ('Incomplete','$projectTitle','$projectDescription','$projectCategory','$projectDueDate','$userID','Public','$projectURL','$projectTaskType','$Copy')";
	$addProject_result = mysqli_query($connection, $addProject) or die(mysqli_error($connection));
	
	$projectID = mysqli_insert_id($connection);
		
	//update ticket 
	$updateTicket = "UPDATE `Tickets` SET `ProjectID`='$projectID',`Status`='In Progress' WHERE `TicketID` ='$requestID'";
	$updateTicket_result = mysqli_query($connection, $updateTicket) or die ("Query to get data from Team Project failed: ".mysql_error());
		
	//initally insert Project Creator Into Member List
	$InsertCreatortoMemberList = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$userID')";
    $InsertCreatortoMemberList_result = mysqli_query($connection, $InsertCreatortoMemberList) or die(mysqli_error($connection));
	
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "created the project: <em>$projectTitle</em>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path = '../team-projects/view/uploads/'.$projectID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	
	////////// CREATING CONTENT FILE UPLOAD FOLDER //////////
	$path = '../team-projects/view/review/uploads/'.$projectID;

	mkdir($path, 0777, true);
	chmod($path, 0777);	
	}
	else {
		

	$query2 = "SELECT * FROM `Team Projects Templates` WHERE `TemplateID` ='$projectTemplate'";
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
	
	//inserting project
	$addProject = "INSERT INTO `Team Projects`(`Status`,`Title`, `Description`, `Category`, `Due Date`, `userID`, `Visible`,`Project Folder Link`,`URL To Use`, `Task Type`, `Copy`) VALUES ('Incomplete','$projectTitle','$projectDescription','$templateCategory','$projectDueDate','$userID','$templateVisible','$projectFolder','$projectURL','$templateTaskType','$Copy')";
	$addProject_result = mysqli_query($connection, $addProject) or die(mysqli_error($connection));

	$projectID = mysqli_insert_id($connection);
	
	//update ticket 
	$updateTicket = "UPDATE `Tickets` SET `ProjectID`='$projectID',`Status`='In Progress' WHERE `TicketID` ='$requestID'";
	$updateTicket_result = mysqli_query($connection, $updateTicket) or die ("Query to get data from Team Project failed: ".mysql_error());	
		
	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	$activity = "created the project: <em>$projectTitle</em>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	//getting membership statements
	$query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$projectTemplate' AND `Type` = 'Membership'";
		$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$statements[]=$row["StatementID"];
			$memberIDs[]=$row["Value"];
	}
	
	foreach ($memberIDs as $individual) {
	if ($individual == $userID) {
		
	}
	else {
		
	$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>You have been added to the project: <strong>$projectTitle</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Membership','$individual','$projectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	//Getting ADDEE'S name
	$getAddeeUsername = "SELECT `username` FROM `user` WHERE `userID` = '$individual'";
	$getAddeeUsername_result = mysqli_query($connection, $getAddeeUsername) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getAddeeUsername_result->fetch_assoc()) {
			$AddeeUsername = $row["username"];
	}
	$activity = "added <strong>@$AddeeUsername</strong> to the project.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Membership','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	
	}
		
	}
	//adding memberships
	foreach($statements as $statement){
		
		$query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `StatementID` ='$statement'";
		$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$memberUserID=$row["Value"];
		}
	
		$newCall = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$memberUserID')";
		mysqli_query($connection, $newCall) or die ("Query to get data from Team task failed: ".mysql_error());

	}
	
	//getting task statements
	$query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$projectTemplate' AND `Type` = 'Task' ORDER BY `StatementID` ASC";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$statements2[]=$row["StatementID"];
	}
	
	
	$startDate = time();
	foreach($statements2 as $statement2){
		
		$query3 = "SELECT `StatementID`, `TemplateID`, `Type`, `Value`, `Task Type`, `Category`, `Task Duration`, `CalendarCategoryID` FROM `Team Projects Templates Statements` LEFT JOIN `Task Categories` ON `Task Categories`.`CategoryID` =`Team Projects Templates Statements`.`Task Type` WHERE `StatementID` ='$statement2'";
		$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query3_result->fetch_assoc()) {
			$memberUserID=$row["Value"];
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
		
		$taskDueDate2 = date('Y-m-d H:i:s', strtotime('+'.$taskDuration.' day'.$s.'', $startDate));
		$taskDueDate3 = strtotime($taskDueDate2);
		
		$dayOfWeek =date("w",strtotime($taskDueDate2));
		
		if ($dayOfWeek == 6) {
				$taskDueDate = date('Y-m-d H:i:s', strtotime('+2 days', $taskDueDate3));
            	//$taskDueDate=strtotime("+2 days", strtotime($taskDueDate2));
		}
		else if ($dayOfWeek == 0) {
			//$taskDueDate=strtotime("+1 day", strtotime($taskDueDate2));
			$taskDueDate = date('Y-m-d H:i:s', strtotime('+1 day', $taskDueDate3));
		}
		else {
			$taskDueDate = $taskDueDate2;
		}
		
		$projectDueDate2 = date('Y-m-d H:i:s', strtotime($projectDueDate));
		if ($taskDueDate > $projectDueDate2) {
			$finalTaskDueDate = $projectDueDate;
		}
		else {
			$finalTaskDueDate = $taskDueDate;
		}
		if ($taskCategoryID == "7") {
			$launchTitle = $projectTitle." ";
			$finalTaskDueDate = $projectDueDate;
		}
		else {
			$launchTitle = "";
		}
		if ($templateTaskType == "Standard") {
			$finalTaskDueDate = $projectDueDate;
		}
		
		if (!isset($taskCalendarCategoryID)) {
		$newCall = "INSERT INTO `Tasks`(`Title`, `Due Date`, `Category`, `Requested By`, `ProjectID`, `userID`) VALUES ('$launchTitle$taskCategory','$finalTaskDueDate','$taskCategoryID','$userID','$projectID','$memberUserID')";
		mysqli_query($connection, $newCall) or die ("Query to get data from Team task failed: ".mysql_error());
			
		/////////// INSERTING NOTIFICATION ///////////
	
	$notification = "<a href=/dashboard/team-projects/view/?projectID=$projectID>A new task: <strong>$taskCategory</strong> has been assigned to you in: <strong>$projectTitle</strong>.</a>";
	$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `ProjectID`) VALUES ('$notification','Task','$memberUserID','$projectID')";
	$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());	
	
	//////// SENDING EMAIL ///////	
		
	if (isset($memberUserID)) {
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$memberUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "You have been assigned a new task in the project: ".$projectTitle.".";
	
		$to = $projectMember;
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
    <td align="left" valign="top" width="100%" style="background:repeat-x url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) #ffffff;">
      <center>
      <img src="http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png" class="force-width-gmail">
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" background="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            <!--[if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:80px; v-text-anchor:middle;">
              <v:fill type="tile" src="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" color="#ffffff" />
              <v:textbox inset="0,0,0,0">
            <![endif]-->
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/me.php">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
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
              Welcome to the <span style="text-decoration: underline">'.$projectTitle.'</span> project! A new task has been assigned to you.  
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
		
		if ($memberUserID !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}			
		
		
/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	
	//Getting ADDEE'S name
	$getAddeeUsername = "SELECT `username` FROM `user` WHERE `userID` = '$memberUserID'";
	$getAddeeUsername_result = mysqli_query($connection, $getAddeeUsername) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = $getAddeeUsername_result->fetch_assoc()) {
			$AddeeUsername = $row["username"];
	}
	
	$activity = "created a new task: <em>$taskCategory</em> assigned to <strong>@$AddeeUsername</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Tasks','$userID','$projectID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
		}
		else {
		$query35 = "SELECT MAX(`TaskID`) FROM `Tasks` WHERE `ProjectID`='$projectID'";
		$query35_result = mysqli_query($connection, $query35) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query35_result->fetch_assoc()) {
		$taskID = $row["MAX(`TaskID`)"];
		}
			$step1 = strtotime('+30 minutes', strtotime($finalTaskDueDate));
			$projectEndDate = date('Y-m-d H:i:s', $step1);
			$newCall2 = "INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`,`TaskID`,`ProjectID`) VALUES('$launchTitle$projectTitle','$projectDueDate','$projectEndDate','$taskCalendarCategoryID','$userID','$projectDescription','false','$taskID','$projectID')";
		mysqli_query($connection, $newCall2) or die ("Query to get data from Team task failed: ".mysql_error());
		
			$eventID = mysqli_insert_id($connection);
			
			////////// CREATING FILE UPLOAD FOLDER //////////
			$path = '../content-calendar/uploads/'.$eventID;

			mkdir($path, 0777, true);
			chmod($path, 0777);
			
			////////// NOTIFICATIONS //////////
			//Getting project members
			$getGroupMembers = "SELECT `userID`, `Calendar Categories`.`Category` FROM `Calendar Categories` LEFT JOIN `Notification Subscription` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Calendar Categories`.`CalendarCategoryID` = '$taskCalendarCategoryID'";
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

			if (!empty($groupMembers)) {
				foreach ($groupMembers as $name2) {
				$notification = "<a href=/dashboard/content-calendar/?eventID=$eventID>The <strong>$categoryName</strong> event: <strong>$projectTitle&nbsp;</strong>has been added to the Content Calendar.</a>";
				$addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name2','$eventID')";
				$addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
				
				//////// SENDING EMAIL ///////	
		
	if (isset($name2)) {
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$name2'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "New Content Calendar ".$categoryName." Event: ".$projectTitle.".";
	
		$to = $projectMember;
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
    <td align="left" valign="top" width="100%" style="background:repeat-x url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) #ffffff;">
      <center>
      <img src="http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png" class="force-width-gmail">
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" background="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            <!--[if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:80px; v-text-anchor:middle;">
              <v:fill type="tile" src="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" color="#ffffff" />
              <v:textbox inset="0,0,0,0">
            <![endif]-->
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/me.php">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
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
              Hi <strong>'.$projectMemberFN.'</strong>,<br>'.$FN.' '.$LN.' added the following '.$categoryName.' event to the Content Calendar.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$categoryName.'</p><p class="pull-left"> <strong>Due Date: </strong>'.date('m/d/Y @ g:ia',strtotime($finalTaskDueDate)).'</p>
				 
             <br><h2>'.$projectTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
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
		
		if ($name2 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}
				
				}
			}
			else {
				
			}

			/////////// INSERTING ACTIVITY /////////////
			$activity = "added the <strong>$categoryName</strong> event: <strong>$projectTitle</strong> to the Content Calendar.";
			$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
			$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
		
		
		
		}
		
		
	
		
		$startDate = strtotime($finalTaskDueDate);
		
			
	}
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path2 = '../team-projects/view/uploads/'.$projectID;

	mkdir($path2, 0777, true);
	chmod($path2, 0777);
	
	////////// CREATING CONTENT FILE UPLOAD FOLDER //////////
	$path2 = '../team-projects/view/review/uploads/'.$projectID;

	mkdir($path2, 0777, true);
	chmod($path2, 0777);
	
	}
	
	
	//////////////
	
	$result = ["projectID" => $projectID];
	header('Content-Type: application/json'); 
	echo json_encode($result);
}	
if ($type == "getEmails") {
	$searchTerm = $_POST["typedEmail"];
	
	$query ="SELECT DISTINCT `email` FROM `user` WHERE `email` LIKE '%$searchTerm%' AND `userID` != '$userID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
		
	while ($row = mysqli_fetch_array($query_result)) {
	$foundEmail = $row['email'];

	}
	
	////////////
	$response = ["foundEmail" => $foundEmail];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
}
if ($type=="submitRequest") {

//getting katie
$getKatie = "SELECT * FROM `user` WHERE `username` = 'KatieT'";
	$getKatie_result = mysqli_query($connection, $getKatie) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());

	while($row = mysqli_fetch_array($getKatie_result)) {
		$katieUserID =$row["userID"];	
	}	
	
//INSERTING
$url = $_POST["requestURL"];
$duedate = $_POST["requestDueDate"];
$title = addslashes($_POST["requestTitle"]);
$description = addslashes($_POST["requestDescription"]);
$copy = addslashes($_POST["requestCopy"]);
$category = $_POST["requestCategory"];		
	
$addRequest = "INSERT INTO `Tickets`(`Title`, `Description`, `URL`, `Due Date`, `Requested By`,`Owner`, `Category`, `Copy`) VALUES ('$title','$description','$url','$duedate','$userID','$katieUserID','$category','$copy')";
$addRequest_result = mysqli_query($connection, $addRequest) or die(mysqli_error($connection));

$RequestID = $connection->insert_id;


//Getting team members
	$getTeamMembers = "SELECT * FROM `Group Membership` WHERE `GroupID` = '1' AND `userID` != '$userID' AND `userID` != '9'";
	$getTeamMembers_result = mysqli_query($connection, $getTeamMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());

	while($row = mysqli_fetch_array($getTeamMembers_result)) {
		$teamMembers[] =$row["userID"];	
	}

		foreach ($teamMembers as $name) {
			$notification2 = "<a href=/dashboard/requests/view.php?ticketID=$RequestID>A new ticket: <strong>$title</strong> has been submitted.</a>";
			$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification2','Ticket','$name')";
			$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
			
			//////// SENDING EMAIL ///////	

	//getting all 
	$getAll = "SELECT `Tickets`.`TicketID`, `Tickets`.`Title`, `Description`, `Copy`, `URL`, `Tickets`.`Timestamp`, DATE_FORMAT(`Tickets`.`Due Date`,'%Y-%m-%dT%H:%i:%s') AS 'Due Date', `Requested By`, `Owner`, `Tickets`.`Status`, `ProjectID`, `Team Projects Categories`.`Category`,`Team Projects Categories`.`ProjectCategoryID`, `First Name`, `Last Name`, `PP Link` FROM `Tickets` JOIN `Team Projects Categories` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` JOIN `user` ON `Tickets`.`Requested By` = `user`.`userID` WHERE `Tickets`.`TicketID` = '$RequestID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $TicketTitle = $row["Title"];
		$Description = $row["Description"];
		$DueDate1 = $row["Due Date"];
		$DueDate = date("m/d/Y", strtotime($DueDate1));
		$Status = $row["Status"];
		$Category = $row["Category"];
		$CategoryID = $row["ProjectCategoryID"];
		$URL = $row["URL"];
		$ContactUserID = $row["Requested By"];
		$ContactName = $row["First Name"]." ".$row["Last Name"];
		$Timestamp = $row["Timestamp"];
		$ProjectID = $row["ProjectID"];
		$Copy = $row["Copy"];
		$ContactPP = $row["PP Link"];
		
	}		
			
	if (isset($name)) {
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$name'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "A new ticket: $TicketTitle has been submitted.";
	
		$to = $projectMember;
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
    <td align="left" valign="top" width="100%" style="background:repeat-x url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) #ffffff;">
      <center>
      <img src="http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png" class="force-width-gmail">
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" background="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            <!--[if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:80px; v-text-anchor:middle;">
              <v:fill type="tile" src="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" color="#ffffff" />
              <v:textbox inset="0,0,0,0">
            <![endif]-->
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/me.php">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
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
              Hi <strong>'.$projectMemberFN.'</strong>,<br>'.$FN.' '.$LN.' added a new ticket to the Dashboard.
              <br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$Category.'</p><p class="pull-left"> <strong>Go Live Date: </strong>'.date('m/d/Y @ g:ia',strtotime($DueDate1)).'</p>
			 <p class="pull-left"> <strong>Status: </strong>'.$Status.'</p>
			 <p class="pull-left"> <strong>URL: </strong>'.$URL.'</p>
			 
			 
			 
				 
             <br><h2>'.$TicketTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
			 
			 <p><strong> Description: </strong><br>'.$Description.'<br>
             </p>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/requests/view.php?ticketID='.$RequestID.'" class="button">View Ticket</a>
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
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','$userID','$RequestID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	
	
	
	//////////////
	
	$result = ["ticketID" => $RequestID];
	header('Content-Type: application/json'); 
	echo json_encode($result);
	
	
}

?>