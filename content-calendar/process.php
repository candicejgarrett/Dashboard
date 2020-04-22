<?php

include_once('../header.php');
 require('../connect.php');
require('../emailDependents.php');
include('../functions/global.php');

$type = $_POST['type'];

if($type == 'getEventCategories')
{
	$query = "SELECT DISTINCT * FROM `Calendar Categories` ORDER BY `Category` ASC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
		$getOptions[]= "<option value='".$row["CalendarCategoryID"]."'>".$row["Category"]." Event</option>";
	}
	array_unshift($getOptions, "<option value=''>Select...</option>");
	////////////
	
	$response = ["getOptions" => $getOptions];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if($type == 'new')
{
	$title = $_POST['title'];
	$startdate = date("Y-m-d H:i:s",strtotime($_POST['startdate']));
	$enddate = date("Y-m-d H:i:s",strtotime($_POST['enddate']));
	$categoryID = $_POST['Category'];
	$description = $_POST['Description'];
	$allDay = $_POST['allDay'];
	$desktopImage=$_FILES['desktopImage'];
	$mobileImage=$_FILES['mobileImage'];
	
	
	$eventID = addEvent($title, $startdate, $enddate, $categoryID,$description, $allday,$desktopImage, $mobileImage);
	
	
	
	////////////
	$results = ["eventID" => $eventID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
	
}

if($type == 'newFromTask')
{
	$TaskID = $_POST["TaskID"];
	$ProjectID = $_POST["ProjectID"];
	$eventCategory = $_POST["eventCategory"];
	
	$query = "SELECT * FROM `Tasks` WHERE `TaskID` = '$TaskID'";
	$query_result = mysqli_query($connection, $query) or die ("getEventID Query to get data from Team Project failed: ".mysql_error());
	while($row = $query_result->fetch_assoc()) {
        $taskTitle = addslashes($row["Title"]);
		$taskTitleNoInsert = $row["Title"];
		$taskDueDate = $row["Due Date"];
		$taskTitle = $row["Title"];
		$taskDescription = addslashes($row["Description"]);
		$taskDueDate = $row["Due Date"];
	 }
	
	$insert = mysqli_query($connection,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `Category`, `userID`, `Description`,`allDay`,`ProjectID`,`TaskID`) VALUES('$taskTitle','$taskDueDate','$taskDueDate','$eventCategory','$userID','$taskDescription','true','$ProjectID','$TaskID')");
	$eventID = mysqli_insert_id($connection);
	
	////////// CREATING FILE UPLOAD FOLDER //////////
	$path = '../content-calendar/uploads/'.$eventID;

	mkdir($path, 0777, true);
	chmod($path, 0777);
	
	/////////// INSERTING NOTIFICATION ///////////
	
	//Getting project members
	$getGroupMembers = "SELECT `SubscriptionID`, `userID`, `Calendar Categories`.`Category`,`Notification Subscription`.`CalendarCategoryID` FROM `Notification Subscription` JOIN `Calendar Categories` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Notification Subscription`.`CalendarCategoryID` = '$eventCategory'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];	
			if (isset($row["Category"])) {
				$categoryName =$row["Category"];	
			}
			else {
				$categoryName ='';	
			}	
		}
	if (isset($groupMembers)) {
	foreach ($groupMembers as $name2) {
		$notification = "<a href=/dashboard/content-calendar/?eventID=$eventID>The <strong>$categoryName</strong> event: <strong>$taskTitle&nbsp;</strong>has been added to the Content Calendar.</a>";
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
	
	$subject = "New Content Calendar ".$categoryName." Event: ".$taskTitleNoInsert.".";
	
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
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
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
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$categoryName.'</p><p class="pull-left"> <strong>Date: </strong>'.date('m/d/Y @ g:i:sa',strtotime($taskDueDate)).' -  '.date('m/d/Y @ g:i:sa',strtotime($taskDueDate)).'</p>
				 
             <br><h2>'.$taskTitleNoInsert.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/content-calendar/?eventID='.$eventID.'" class="button">View Event</a>
              <br><br>
			  
			  <br>
		 <a href="https://dashboard.coat.com/dashboard/content-calendar/unsubscribe/?categoryID='.$eventCategory.'" style="text-decoration:underline !important;font-size:12px !important;">Unsubscribe from <strong>'.$categoryName.'</strong> Calendar Event alerts.</a>
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
	$activity = "added the <strong>$categoryName</strong> event: <strong>$title</strong> to the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	$activity2 = "added the <strong>$categoryName</strong> event: <strong>$taskTitle</strong> to the Content Calendar.";
	$addActivity2 = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity2','Event','$userID','$ProjectID')";
	$addActivity2_result = mysqli_query($connection, $addActivity2) or die ("activity failed: ".mysql_error());
	
	////////////
	
	$results = ["eventID" => $eventID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
}

if($type == 'viewEvent')
{
	
	$eventid = $_POST['eventid'];
	$viewEvent = mysqli_query($connection,"SELECT DISTINCT `calendar`.`ProjectID`,`Tasks`.`Category` AS 'Task Category',`calendar`.`TaskID`,`calendar`.`id`,`calendar`.`title`,`First Name`,`Last Name`,`PP Link`,`calendar`.`allDay`,`Preview Image Link`,`Preview Image Link Mobile`, DATE_FORMAT(`startdate`, '%b %d %Y @ %h:%i%p'),DATE_FORMAT(`startdate`, '%Y-%m-%dT%T') AS 'Standard Start Date',DATE_FORMAT(`startdate`, '%Y-%m-%d') AS 'Jump To Date', DATE_FORMAT(`enddate`, '%b %d %Y @ %h:%i%p'),DATE_FORMAT(`enddate`, '%Y-%m-%dT%T') AS 'Standard End Date',`Calendar Categories`.`Category` AS 'Category Name', `calendar`.`Category`, `calendar`.`userID`, `calendar`.`Description` FROM `calendar` JOIN `user` on `calendar`.`userID` JOIN `Calendar Categories` on `Calendar Categories`.`CalendarCategoryID`=`calendar`.`category` LEFT JOIN `Tasks` on `Tasks`.`TaskID`=`calendar`.`TaskID` WHERE `id`='$eventid' and `calendar`.`userID`=`user`.`userID`");
	
	while($row = $viewEvent->fetch_assoc()) {
        $printEventID = $row["id"];
		$printEventTaskID = $row["TaskID"];
		$printEventTaskCategory = $row["Task Category"];
		$printEventProjectID = $row["ProjectID"];
		$printEventTitle = $row["title"];
		$printEventStartDate = $row["DATE_FORMAT(`startdate`, '%b %d %Y @ %h:%i%p')"];
		$printEventEndDate = $row["DATE_FORMAT(`enddate`, '%b %d %Y @ %h:%i%p')"];
		$printEventStartDateStandard = $row["Standard Start Date"];
		$printEventJumpToDate = $row["Jump To Date"];
		$printEventEndDateStandard = $row["Standard End Date"];
		$printEventDescription = $row["Description"];
		$printEventCategory = $row["Category"];
		$printEventCategoryName = $row["Category Name"];
		$firstname = $row["First Name"];
		$lastname = $row["Last Name"];
		$printEventCreatedBy = $firstname." ".$lastname;
		$printEventAllDay = $row["allDay"];
		$printEventImage = $row["Preview Image Link"];
		$printEventImageMobile = $row["Preview Image Link Mobile"];
		$creatorUserID = $row["userID"];
		$printEventPP = $row["PP Link"];
		
		if ($printEventImage != "") {
			$printEventPreviewImage = "<a href='$printEventImage' target='_blank' class='ccPreviewImage'><img class='previewImage' src='$printEventImage'/></a>";
		}
		else {
			$printEventPreviewImage = "";
		}
		if ($printEventImageMobile != "") {
			$printEventPreviewImageMobile = "<a href='$printEventImageMobile' target='_blank' class='ccPreviewImage'><img class='previewImage' src='$printEventImageMobile'/></a>";
		}
		else {
			$printEventPreviewImageMobile = "";
		}
	 }
	
	if ($printEventID != NULL) {
		
		if (isset($printEventTaskID) && $printEventTaskCategory== "7") {
			$canDelete = '<a href="/dashboard/team-projects/view/?projectID='.$printEventProjectID.'"><button type="button" class="createNew noExpand"><i class="fa fa-eye" aria-hidden="true"></i></button></a>';
		}
		else {
			$canDelete = '<button type="button" class="remove noExpand deleteConfirm" id="removeEvent"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
		}
		
		
		//permissions
		if ($myRole == "Admin" || ($myRole == "Editor" && $groupID == 1) || $userID == $creatorUserID) {
		$printCTAs = '<button type="button" class="edit noExpand" id="editEventModal-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><button type="button" id="saveEventModal-btn" data-dismiss="modal" class="save noExpand" style="display: none;"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>'.$canDelete.'';
		$printUploadLink ='<p style="cursor: pointer;color:#4801FF; text-align:center;margin-bottom: 0px;" id="addEventPreviewImage-btn" class="addEventPreviewImage-btn">Upload Preview Image &nbsp;<i class="fa fa-cloud-upload" aria-hidden="true"></i></p>';
		
	}
		else {
			$printCTAs = '';
		$printUploadLink ='';
		}
	}
	
	//if image exists show button else dont
	if ($printEventPreviewImage == "") {
		$showDeleteImageButton = '';
	}
	else {
		$showDeleteImageButton = '<button type="button" id="removeEventPreviewImage-btn" class="remove noExpand deleteConfirm" mockupType="Desktop"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
	}
	
	if ($printEventPreviewImageMobile == "") {
		$showDeleteImageButtonMobile = '';
	}
	else {
		$showDeleteImageButtonMobile = '<button type="button" id="removeEventPreviewImageMobile-btn" class="remove noExpand deleteConfirm" mockupType="Mobile"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
	}
	
	
		$previewImageFinal ='<ul class="mySecondaryTabs" role="tablist2">
							<li class="active" id="desktopLink">Desktop</li>
						  <li id="mobileLink">Mobile</li>
							</ul>
					  <div id="desktop" class="active">
					  <br>
					  '.$printUploadLink.'
					  
					  <div class="form-sm" id="addPreviewImage">
					  <hr>
								<label class="formLabels" style="display:block;">Preview Image:</label> <input type="file" id="addEventPreviewImage" name="file">

								<button type="button" class="createNew noExpand" id="saveEventPreviewImage-btn" mockupType="Desktop"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
								'.$showDeleteImageButton.'
						</div>
					<br>
					 '.$printEventPreviewImage.'
					</div>
					
					<div id="mobile">
							<br>
					  		'.$printUploadLink.'
							<div class="form-sm" id="addPreviewImageMobile">
					  <hr>
								<label class="formLabels" style="display:block;">Preview Image:</label> <input type="file" id="addEventPreviewImageMobile" name="file" >

								<button type="button" class="createNew noExpand" id="saveEventPreviewImageMobile-btn" mockupType="Mobile"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
								'.$showDeleteImageButtonMobile.'
						</div>
						<br>
					 '.$printEventPreviewImageMobile.'
					</div>';
	
	
	
	////////////
	
	$response = ["printEventID" => $printEventID,
				 "printEventTitle" => $printEventTitle, 
				 "printEventStartDate" => $printEventStartDate, 
				 "printEventEndDate" => $printEventEndDate,
				 "printEventEndDateStandard" => $printEventEndDateStandard,
				 "printEventStartDateStandard" => $printEventStartDateStandard, 
				 "printEventCategory" => $printEventCategory, 
				 "printEventCategoryName" => $printEventCategoryName, 
				 "printEventDescription" => $printEventDescription,
				 "printEventCreatedBy" => $printEventCreatedBy,
				 "printEventAllDay" => $printEventAllDay,
				 "printEventPreviewImage" => $printEventPreviewImage,
				 "printCTAs" => $printCTAs,
				 "printEventJumpToDate" => $printEventJumpToDate,
				"printEventPP" => $printEventPP,
				"printUploadLink" => $printUploadLink,
				"previewImageFinal" => $previewImageFinal];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if($type == 'saveEvent')
{
	$eventID = $_POST['eventid'];
	$title = $_POST['title'];
	$startdate = date("Y-m-d H:i:s",strtotime($_POST['startdate']));
	$enddate = date("Y-m-d H:i:s",strtotime($_POST['enddate']));
	$categoryID = $_POST['Category'];
	$description = addslashes($_POST['Description']);
	$allDay = $_POST['allDay'];
	
	updateEvent($eventID, $title, $startdate, $enddate, $categoryID,$description, $allday);
		
}

if($type == 'resetdate')
{
	$startdate = date("Y-m-d H:i:s",strtotime($_POST['start']));
	$enddate = date("Y-m-d H:i:s",strtotime($_POST['end']));
	$eventid = $_POST['eventid'];
	$update = mysqli_query($connection,"UPDATE `calendar` SET `startdate` = '$startdate', `enddate` = '$enddate' WHERE `id`='$eventid'");
	
	/////////// INSERTING NOTIFICATION ///////////
	$getCategory = "SELECT * FROM `calendar` WHERE `id` = '$eventid'";
	$getCategory_result = mysqli_query($connection, $getCategory) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getCategory_result)) {
			$Category =$row["Category"];	
			$eventTaskID =$row["TaskID"];	
			$eventTitle =addslashes($row["title"]);	
		}
	
	//Getting project members
	$getGroupMembers = "SELECT `SubscriptionID`, `userID`, `Calendar Categories`.`Category`,`Notification Subscription`.`CalendarCategoryID` FROM `Notification Subscription` JOIN `Calendar Categories` ON `Calendar Categories`.`CalendarCategoryID`= `Notification Subscription`.`CalendarCategoryID` WHERE `Notification Subscription`.`CalendarCategoryID` = '$Category'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];	
			if (isset($row["Category"])) {
				$categoryName =$row["Category"];	
			}
			else {
				$categoryName ='';	
			}
		}
	if (isset($groupMembers)) {
	foreach ($groupMembers as $name4) {
		$notification = "<a href=/dashboard/content-calendar/?eventID=$eventid>The date for the <strong>$categoryName</strong> event: <strong>$eventTitle</strong> has been updated.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`, `EventID`) VALUES ('$notification','Event','$name4','$eventid')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
		
		//////// SENDING EMAIL ///////		
			
	if (isset($name4)) {
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$name4'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "Updated Content Calendar ".$categoryName." Event: ".$eventTitle.".";
	
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
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
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
              A '.$categoryName.' event has been updated in the Content Calendar.
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$projectMemberFN.'</strong>,<br>'.$FN.' '.$LN.' updated the date for the following '.$categoryName.' event to the Content Calendar.
              <br><br>
             <div class="task">
			 
			 <p class="pull-right"> <strong>Category: </strong>'.$categoryName.'</p><p class="pull-left"> <strong>Date: </strong>'.date('m/d/Y @ g:i:sa',strtotime($startdate)).' -  '.date('m/d/Y @ g:i:sa',strtotime($enddate)).'</p>
				 
             <br><h2>'.$eventTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Created By: '.$FN.' '.$LN.'</span></h2>
             </div>
             
             <a href="https://dashboard.coat.com/dashboard/content-calendar/?eventID='.$eventid.'" class="button">View Event</a>
              <br><br>
			  <br>
		 <a href="https://dashboard.coat.com/dashboard/content-calendar/unsubscribe/?categoryID='.$Category.'" style="text-decoration:underline !important;font-size:12px !important;">Unsubscribe from <strong>'.$categoryName.'</strong> Calendar Event alerts.</a>
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
		
		if ($name4 !== $userID) {
			mail($to, $subject, $message, $headers);
		}
		

	
	
}
	}
	}
	else {
		$groupMembers[] = '';
	}
	/////////// INSERTING ACTIVITY /////////////
	$activity = "updated the date for the <strong>$categoryName</strong> event: <strong>$eventTitle</strong> on the Content Calendar.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `EventID`) VALUES ('$activity','Event','$userID','$eventid')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());
	
	if($update)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
	
	
	
	if (isset($eventTaskID)) {
		$updateTask = "UPDATE `Tasks` SET `Due Date`='$startdate',`End Date`='$enddate' WHERE `TaskID` = '$eventTaskID'";
		$updateTask_result = mysqli_query($connection, $updateTask) or die ("Query to get data from Team Project failed: ".mysql_error());
	}
	else {}
	
}

if($type == 'remove')
{
	$eventID = $_POST['eventid'];
	
	deleteEvent($eventID);
}


if($type == 'fetch')
{

	$events = array();
	$query = mysqli_query($connection, "SELECT `id`, `title`, `startdate`, `enddate`, `Calendar Categories`.`Category`, `calendar`.`Category` AS 'CategoryID', `Calendar Categories`.`Category Color`, `userID`, `Description`, `allDay`, `Preview Image Link`, `ProjectID`, `dow` FROM `calendar` JOIN `Calendar Categories` ON `calendar`.`Category`=`Calendar Categories`.`CalendarCategoryID` ORDER BY `startdate`");
	while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
	$e = array();
    $e['id'] = $fetch['id'];
    $e['title'] = $fetch['title'];
    $e['start'] = $fetch['startdate'];
    $e['end'] = $fetch['enddate'];
	$e['Category'] = $fetch['Category'];
	$e['CategoryID'] = $fetch['CategoryID'];
	$e['Color'] = $fetch['Category Color'];

    $allday = ($fetch['allDay'] == "true") ? true : false;
    $e['allDay'] = $allday;

    array_push($events, $e);
	}
	echo json_encode($events);
	//echo json_encode($filteredCategory);
	
}
if($type == 'fetchOnly')
{
$categoryID = $_POST['categoryID'];
	$events = array();
	$query = mysqli_query($connection, "SELECT `id`, `title`, `startdate`, `enddate`, `Calendar Categories`.`Category`, `calendar`.`Category` AS 'CategoryID', `Calendar Categories`.`Category Color`, `userID`, `Description`, `allDay`, `Preview Image Link`, `ProjectID`, `dow` FROM `calendar` JOIN `Calendar Categories` ON `calendar`.`Category`=`Calendar Categories`.`CalendarCategoryID` WHERE `calendar`.`Category`='$categoryID'");
	while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
	$e = array();
    $e['id'] = $fetch['id'];
    $e['title'] = $fetch['title'];
    $e['start'] = $fetch['startdate'];
    $e['end'] = $fetch['enddate'];
	$e['Category'] = $fetch['Category'];
	$e['CategoryID'] = $fetch['CategoryID'];
	$e['Color'] = $fetch['Category Color'];

    $allday = ($fetch['allDay'] == "true") ? true : false;
    $e['allDay'] = $allday;

    array_push($events, $e);
	}
	echo json_encode($events);
	//echo json_encode($filteredCategory);
	
}

if($type == 'fileUpload') {
	
	$eventID = $_POST['eventID'];
	$image = $_FILES['file'];
	$mockupType = $_POST['mockupType'];
	
	addEventMockup($eventID, $mockupType, $image);
	
}

if($type == 'deleteFile') {
	
	$eventID = $_POST['eventID'];
	$path = $_POST['path'];
	$mockupType = $_POST['mockupType'];
	
	deleteEventMockup($eventID, $path, $mockupType);
	
}

if($type == 'search')
{
	$searchTerm = addslashes($_POST['searchTerm']);
	$startDate = $_POST['startdate'];
	$endDate = $_POST['enddate'];
	$excludeCategories = $_POST['categories'];
	
	if(isset($excludeCategories)) {
		$commaList = implode(', ', $excludeCategories);
		$shouldExcludeCats = " AND `calendar`.`Category` NOT IN ($commaList)";
	}
	else {
		$shouldExcludeCats = "";
	}
	
	if ($startDate == "") {
		$filterStartDate = "";
	}
	else {
		$filterStartDate = " AND `startdate` BETWEEN '$startDate' AND '$endDate'";
	}
	

$query = "SELECT `id`, `calendar`.`title`,`First Name`,`Last Name`, DATE_FORMAT(`startdate`, '%m/%d/%Y<br>(%l:%i %p)'), DATE_FORMAT(`enddate`, '%m/%d/%Y (%l:%i %p)'), `calendar`.`Category`, `calendar`.`userID`, `Description`, `allDay`, `Preview Image Link` FROM `calendar` JOIN `user` ON `calendar`.`userID` = `user`.`userID` WHERE `calendar`.`title` LIKE '%$searchTerm%'$shouldExcludeCats $filterStartDate ORDER BY `startdate` DESC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$numberOfEvents = mysqli_num_rows($query_result);
	
	while ($row = mysqli_fetch_array($query_result)) {
							
							$eventID = $row['id'];
							$eventTitle = $row['title'];
							$eventStartDate = $row["DATE_FORMAT(`startdate`, '%m/%d/%Y<br>(%l:%i %p)')"];
							$eventEndDate = $row["DATE_FORMAT(`enddate`, '%m/%d/%Y (%l:%i %p)')"];
							$eventCategory = $row['Category'];
							$eventCategory2 = str_replace(' ', '', $eventCategory);
							$eventDescription = $row['Description'];	
							$eventCreatorID = $row['userID'];
							$eventCreatorName = $row['First Name']." ". $row['Last Name'];
								
								$query2 = "SELECT * FROM `Calendar Categories` WHERE `CalendarCategoryID` ='$eventCategory'";
								$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
								while ($row = mysqli_fetch_array($query2_result)) {
									$eventCategoryColor = $row['Category Color'];
									$eventCategoryName = $row['Category'];
								}
								
								$ccSearchResults[]= "
								<div class='eventContainer'>
									<div class='event' style='background:".$eventCategoryColor."'>
										<div class='row'>
											<div class='col-sm-8'>
												<div class='category' style='color:".$eventCategoryColor."'>$eventCategoryName</div>
												<div class='title'>$eventTitle</div>
												<div class='createdBy'><strong>Created By:</strong> 
												<span class='getprojectCreatorFN'>$eventCreatorName</span>
												</div>
											</div>
											<div class='col-sm-4'>
												<div class='date'><strong>Event Date:</strong><br><span class='projectDueDate'>$eventStartDate</span></div>
												
												<div class='event-btn' eventid='$eventID' data-toggle='modal' data-target='#viewEvent'>View</a>
											
											</div>
										</div>
										
										
										
									</div>
								</div>";
									
	}


$results = ["ccSearchResults" => $ccSearchResults,"numberOfEvents" => $numberOfEvents];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);

}


?>