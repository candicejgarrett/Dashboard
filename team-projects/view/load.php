<?php 
require('../../header.php');
require('../../functions/global.php');
$projectSelector=$_POST['project'];

if(isset($projectSelector)){
	
	$printProjectID = $projectSelector;
	
	//does it exist?
	$query = "SELECT COUNT(`ProjectID`) FROM `Team Projects` WHERE `ProjectID` = '$printProjectID'";
	$query_result = mysqli_query($connection, $query) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	$query_row = $query_result->fetch_assoc();
	$projectCount =$query_row['COUNT(`ProjectID`)'];
	
	if ($projectCount == 1) {
		
		//getting favorite?
	$isFavorite = "SELECT COUNT(`ProjectID`) FROM `Team Projects Favorites` WHERE `userID` = '$userID' AND `ProjectID` = '$printProjectID'";
	$isFavorite_result = mysqli_query($connection, $isFavorite) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	$isFavoriteRow_count= $isFavorite_result->num_rows;
	$row = $isFavorite_result->fetch_assoc();
	$printableFavoriteCount =$row['COUNT(`ProjectID`)'];
	
	if ($printableFavoriteCount >= 1) {
		$favorite = '<div class="pull-left heart2"><span id="favMessage2"></span><span id="hearticon2" class="heartFilled"><i class="fa fa-heart" aria-hidden="true"></i></span></div>';
	}
	else {
		$favorite = '<div class="pull-left heart"><span id="favMessage"></span><span id="hearticon" class="heartEmpty"><i class="fa fa-heart" aria-hidden="true"></i></span></div>';
	}
		
	//getting ticket?
	//getting all 
	$getAll = "SELECT `TicketID`,`Tickets`.`Title`,`Requested By`, `First Name`, `Last Name`, `PP Link` FROM `Tickets` JOIN `user` ON `Tickets`.`Requested By` = `user`.`userID` WHERE `Tickets`.`ProjectID` = '$printProjectID'";
	$getAll_result = mysqli_query($connection, $getAll) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getAll_result->fetch_assoc()) {
        $ContactName = $row["First Name"]." ".$row["Last Name"];
		$ticketTitle = $row["Title"];
		$ticketID = $row["TicketID"];
	}
		
	if (isset($ContactName)) {
		
		$ticketAvailable = '<div class="col-sm-12">
		<hr>
      				<p></p><div class="formLabels">Ticket:</div>
					<em><a href="/dashboard/requests/view/?ticketID='.$ticketID.'">'.$ticketTitle.'</a></em><br>
					<em>Requested by '.$ContactName.'
     				<br></div>';
	}
	else {
		$ticketAvailable = '';
	}
	
	//getting all 
	$getAllDetails = "SELECT `Team Projects`.`Task Type`,`Team Projects`.`Title`,`Team Projects`.`Copy`, `Description`, `Team Projects Categories`.`Category`,DATE_FORMAT(`Date Completed`, '%W, %b. %e, %Y @ %h:%i %p'), DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p'),`Status`, `Visible`,`Project Folder Link`,`URL To Use`,`Team Projects`.`userID`,`First Name`,`Last Name`,`PP Link`, `Group Membership`.`GroupID` FROM `Team Projects` JOIN `user` ON  `user`.`userID`= `Team Projects`.`userID` JOIN `Team Projects Categories` ON `Team Projects Categories`.`ProjectCategoryID`=`Team Projects`.`Category` JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID` WHERE `ProjectID`='$printProjectID'";
	$getAllDetails_result = mysqli_query($connection, $getAllDetails) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $getAllDetails_result->fetch_assoc()) {
        $printProjectTitle = $row["Title"];
		$printProjectCompletedDate = $row["DATE_FORMAT(`Date Completed`, '%W, %b. %e, %Y @ %h:%i %p')"]; 
		 $printProjectStatus = $row["Status"];
		 $printProjectDueDate = $row["DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p')"];
		 $printProjectDescription = $row["Description"];
		 $printProjectCopy = $row["Copy"];
		 $printProjectCategory = $row["Category"];
		 $printProjectTaskType = $row["Task Type"];
		 $printVisible = $row["Visible"];
		 $printProjectFolder = $row["Project Folder Link"];
		 $printProjectURL = $row["URL To Use"];
		 $printProjectCreatedByUserID = $row["userID"];
		 $printProjectCreatedByGroupID = $row["GroupID"];
		 $projectCreatedByPP = $row["PP Link"];
		 $printFN2=$row["First Name"];
		 $printLN2=$row["Last Name"];
		 $printProjectCreatedBy = $printFN2." ".$printLN2;
	 }
	//end getting all
		
	//getting membership list 
	$getMembershipList = "SELECT * FROM `user` JOIN `Team Projects Member List` ON `user`.`userID`= `Team Projects Member List`.`userID` WHERE `ProjectID` = '$printProjectID'";
	
	$getMembershipList_result = mysqli_query($connection, $getMembershipList) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMembershipList_result->fetch_assoc()) {
		$memberIDArray[] = $row["userID"];
		$memberID = $row["userID"];
		$memberFN = $row["First Name"];
		$memberPic = $row["PP Link"];
		$memberUsername = $row["username"];
		
		if ($memberID == $printProjectCreatedByUserID) {
			$disable = "disabled"; 
			
		}
		else {
			$disable = "not_disabled";
			
		}
		

		
		//getting progress bar
		
		//completed tasks
		$getCompletedCount = "SELECT COUNT(`TaskID`) FROM `Tasks` WHERE `ProjectID` = '$printProjectID' AND `userID` = '$memberID' AND `Status` = 'Completed'";
	$getCompletedCount_result = mysqli_query($connection, $getCompletedCount) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $getCompletedCount_result->fetch_assoc()) {
		 $completedCount = $row["COUNT(`TaskID`)"];
	 }
		
		//completed tasks
		$getAllTaskCount = "SELECT COUNT(`TaskID`) FROM `Tasks` WHERE `ProjectID` = '$printProjectID' AND `userID` = '$memberID'";
	$getAllTaskCount_result = mysqli_query($connection, $getAllTaskCount) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $getAllTaskCount_result->fetch_assoc()) {
		 $totalCount = $row["COUNT(`TaskID`)"];
	 }
		if ($totalCount == 0) {
			$width = 0;
		}
		else {
			$myTaskpercentage = round(($completedCount/$totalCount) * 100,0);
			$width = $myTaskpercentage;
		}
		
		
		
		$printMembers[] = "<div class='row'><div class='col-sm-3' style='padding-right: 0px;'><div class='projectMember $disable' id='$memberID'><img class='profilePicMembers' src='".$memberPic."'/><p>$memberUsername</p></div></div><div class='col-sm-8' style='padding-right: 0px;'><div class='memberTaskCount'>$completedCount/$totalCount</div><div class='progressBar-container'><div class='progressBar' style='width:".$width."%'></div></div></div></div><br>";  // or $row if you want whole row
		//GETTING ASSIGNED TO DROPDOWN
		$printMembersDropdown[] ="<option value='$memberID'>$memberFN</option>";
	}
	
		//get members 
		//getting membership list 
	$getMembershipListExcludingYou = "SELECT * FROM `user` JOIN `Team Projects Member List` ON `user`.`userID`= `Team Projects Member List`.`userID` WHERE `ProjectID` = '$printProjectID' AND `user`.`userID` != '$userID'";
	
	$getMembershipListExcludingYou_result = mysqli_query($connection, $getMembershipListExcludingYou) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMembershipListExcludingYou_result->fetch_assoc()) {
		$memberIDArray[] = $row["userID"];
		$memberID = $row["userID"];
		$memberFN = $row["First Name"];
		$memberPic = $row["PP Link"];
		$memberUsername = $row["username"];
		
		$mentionUsers[] = "
		<div class='userTags' userid='$memberID'>
		$memberUsername
		</div>";  // or $row if you want whole row;
	}	
		
	//PERMISSIONS
	if ($userID == $printProjectCreatedByUserID || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $printProjectCreatedByGroupID)) {
		
		if ($printProjectStatus !== "Archived") {
			$checked = " checked";
		}
		else {
			$checked = "";
		}
		
		$canRemoveMembers ='yes';
		$canAddMembers ='<button class="createNew noExpand fadeIn" id="addMembers" style="margin-top: -10px;"><i class="fa fa-plus" aria-hidden="true"></i></button>';
		$canEditProject = '<button data-toggle="modal" data-target="#editInfo" id="editProject-btn" style="color:#ffffff;" class="edit noExpand"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><button type="button" id="delete-btn" name="delete" class="remove deleteConfirm"><i class="fa fa-trash-o" aria-hidden="true"></i></button><label class="switch">
  <input type="checkbox" id="archiveToggle"'.$checked.'>
  <span class="slider round"></span>
</label>';
		$canAddTask = '<button type="button" class="pull-right createNew noExpand" style="color:#ffffff;margin-top:-10px;" data-toggle="modal" data-target="#addNewTask" id="addNewTask-btn"><i class="fa fa-plus" aria-hidden="true"></i></button>';
		$canAddReview = '<button type="button" class="pull-right createNew noExpand" style="color:#ffffff;margin-top:-10px;" id="addNewReview-btn" data-toggle="modal" data-target="#addNewReview"><i class="fa fa-plus" aria-hidden="true"></i></button>';
		$canMention ='<div id="showMentions"><div class="formLabels">Mention:</div><hr></div>';
		$canAddNote ='
		<div class="formLabels">Message:*</div> 
						<div class="showUsernames"></div>
		<div class="row" id="removeInput">
      						<div class="col-sm-12" style="height: 56px;overflow: hidden !important;">
     					<table width="100%" border="0" cellspacing="0" cellpadding="010">
  <tbody>
    <tr>
      <td width="90%"><pre style="margin: 0px !important; padding: 0px !important;"><textarea id="projectNotesMessage" style="width:98%;height: 34px; padding: 7px;" class="validate"></textarea></pre></td>
      <td width="10%" valign="top"><button id="sendNote" style="color:#ffffff;" class="pull-right send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button></td>
    </tr>
  </tbody>
</table>
 </div> 
       					</div>';
		$canAddFile = '<div class="row"><div class="col-sm-4" style="height: 56px;overflow: hidden !important;">
     					<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tbody>
    <tr>
      <td width="90%"><input type="file" id="projectFiles" name="file[]" multiple="multiple" style="height: 39px;"></td>
      <td width="10%" valign="top"><button id="fileUpload" style="color:#ffffff;" class="pull-right upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button></td>
    </tr>
  </tbody>
</table>
 </div></div><hr>  ';
		$canAddCopy = '<button type="button" class="pull-right createNew noExpand" style="color:#ffffff;margin-top:-10px;" id="addNewCopy-btn" data-toggle="modal" data-target="#editCopy"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
		
			
	}
	else if (in_array($userID, $memberIDArray)) {
		$canAddTask = '<button type="button" class="pull-right createNew noExpand" style="color:#ffffff;margin-top:-25px;" data-toggle="modal" data-target="#addNewTask" id="addNewTask-btn"><i class="fa fa-plus" aria-hidden="true"></i></button>';
		$canMention ='<div id="showMentions"></div>';
		$canAddNote ='<div class="formLabels">Message:*</div> 
						<div class="row" id="removeInput" style="padding-right: 20px;">
      						<div class="col-sm-12" style="height: 56px;overflow: hidden !important;">
     					<table width="100%" border="0" cellspacing="0" cellpadding="010">
  <tbody>
    <tr>
      <td width="90%"><pre style="margin: 0px !important; padding: 0px !important;"><textarea id="projectNotesMessage" placeholder="Enter your message here..." style="width:98%;height: 34px;padding: 7px;" class="validate"></textarea></pre></td>
      <td width="10%" valign="top"><button id="sendNote" style="color:#ffffff;" class="pull-right send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button></td>
    </tr>
  </tbody>
</table>
 </div> 
       					</div>';
		$canAddFile = '<div class="row"><div class="col-sm-4" style="height: 56px;overflow: hidden !important;">
     					<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tbody>
    <tr>
      <td width="90%"><input type="file" id="projectFiles" multiple="multiple" name="file[]" style="height: 39px;"></td>
      <td width="10%" valign="top"><button id="fileUpload" style="color:#ffffff;" class="pull-right upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button></td>
    </tr>
  </tbody>
</table>
 </div></div><hr>';
		$canAddReview = '<button type="button" class="pull-right createNew noExpand" style="color:#ffffff;margin-top:-25px;" id="addNewReview-btn" data-toggle="modal" data-target="#addNewReview"><i class="fa fa-plus" aria-hidden="true"></i></button>';
		$canAddCopy = '<button type="button" class="pull-right createNew noExpand" style="color:#ffffff;margin-top:-25px;" id="addNewCopy-btn" data-toggle="modal" data-target="#editCopy"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
		$canAddMembers ='';
		$canEditProject = '';
		$canRemoveMembers = '';
	
	}
	else {
		$canAddMembers ='';
		$canEditProject = '';
		$canAddTask = '';
		$canAddNote ='';
		$canMention ='';
		$canAddFile = '';
		$canAddReview = '';
		$canAddCopy = '';
		$canRemoveMembers = '';
		
	}
	
//////////// TASK LIST RETRIEVAL ////////////
	
	// getting all project tasks
	//getting project count
	$getTasksCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$printProjectID'";
	$getTasksCount_result = mysqli_query($connection, $getTasksCount) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	while($row = $getTasksCount_result->fetch_assoc()) {
		$TasksCount = $row["COUNT(*)"];
	}
	
	$getTasks = "SELECT `Title`, `Description`, DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y'), `Status`,`Category`, `Requested By`,`Task Date Created`, `userID`,`TaskID` FROM `Tasks` WHERE `ProjectID` = '$printProjectID' ORDER BY `Due Date` ASC";
	$getTasks_result = mysqli_query($connection, $getTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getTasks_result->fetch_assoc()) {
		$printTasks[] = $row["TaskID"];
	}
	$allTaskCount=mysqli_num_rows($getTasks_result);
	if (!empty($printTasks)){
		foreach($printTasks as $value3){
											
												$getAllProjectTasks = "SELECT `calendar`.`title` AS 'Event Title',`id` AS 'EventID',`username`,`CommentID`, `PP Link`,`Tasks`.`Title`,DATE_FORMAT(`Tasks`.`Task Date Created`, '%e %b %Y') AS 'printableTaskDateCreated',`Tasks`.`Task Date Created`,`Task Date Completed`, `Tasks`.`Description`,`Due Date` AS 'Task Due Date Standard', DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p'), `Status`,`Tasks`.`Category` AS 'TaskCategoryID',`Task Categories`.`Category`, `Requested By`, `Tasks`.`userID`,`Tasks`.`TaskID`, datediff(`Due Date`,now()) AS 'Days Left To Complete' FROM `Tasks` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` LEFT JOIN `calendar` ON `calendar`.`TaskID`=`Tasks`.`TaskID` LEFT JOIN `Task Comments` ON `Task Comments`.`TaskID`=`Tasks`.`TaskID` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` WHERE `Tasks`.`TaskID` =  '$value3' LIMIT 1";
												$getAllProjectTasks_result = mysqli_query($connection, $getAllProjectTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

													while($row = $getAllProjectTasks_result->fetch_assoc()) {
													$AssignedToID = $row["userID"];
													$printableTaskDateCreated = $row["printableTaskDateCreated"];
													$TaskTitle = $row["Title"];
													$TaskCategory = $row["Category"];
													$TaskCategoryID = $row["TaskCategoryID"];
													$TaskDescription = $row["Description"];
													$TaskStatus = $row["Status"];
													$TaskRequestedBy = $row["Requested By"];
													$TaskID = $row["TaskID"];
													$TaskDueDate = $row["DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p')"];
													$TaskDueDateStandard = $row["Task Due Date Standard"];
													$AssignedToFN = $row["username"];
													$ppLink = $row["PP Link"];
													$eventID = $row["EventID"];
													$areComments = $row["CommentID"];
													$daysLeftToComplete = $row["Days Left To Complete"];
													
													if (!isset($AssignedToID))	{
														$printFinalTasks[] = "";
													}
														
													else {
													if ($areComments !== NULL) {
														
														$getCommentCount = "SELECT COUNT(*) FROM `Task Comments` WHERE `TaskID` = '$value3'";
														$getCommentCount_result = mysqli_query($connection, $getCommentCount) or die ("Query to get data from Team Project failed: ".mysql_error());

														while ($row2 = mysqli_fetch_array($getCommentCount_result)) {
															$commentCount = $row2['COUNT(*)'];
														}
														
														
															$dot = '<div class="dot"><i class="fa fa-comments" aria-hidden="true"></i>'.$commentCount.'</div>';
														}
														else {
															$dot = "";
														}
														
														if (isset($eventID)) {
															$calendarIcon = "<a href='/dashboard/content-calendar/?eventID=$eventID'><div class='calendaricon'><i class='fa fa-calendar-check-o' aria-hidden='true'></i></div></a>";
															
														}
														else {
															$calendarIcon = "";
															
														}
																						
														
													//GETTING PERMISSIONS
													$creatorActions = "<div class='infoicon' id='#infoicon' data-toggle='modal' data-target='#viewTask'>$dot<i class='fa fa-info-circle' aria-hidden='true'></i></div><a class='editicon' data-toggle='modal' data-target='#viewTask'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>$calendarIcon<div class='trashicon'><i class='fa fa-trash' aria-hidden='true'></i></div>";
													$assignedToActions = "<div class='infoicon' id='#infoicon' data-toggle='modal' data-target='#viewTask'>$dot<i class='fa fa-info-circle' aria-hidden='true'></i></div><a class='editicon' data-toggle='modal' data-target='#viewTask'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></div>$calendarIcon";
													$nonCreatorActions = "<div class='infoicon' id='#infoicon' data-toggle='modal' data-target='#viewTask'>$dot<i class='fa fa-info-circle' aria-hidden='true'></i></div>";
														
													//MISC
													
														
													

													if ($userID == $TaskRequestedBy || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $printProjectCreatedByGroupID) || $userID == $printProjectCreatedByUserID) {
														$Actions = $creatorActions;
														
														
													}	
													else if ($userID == $AssignedToID) {
														$Actions = $assignedToActions;
														
														
													}
													else{
														$Actions = $nonCreatorActions;
														
													}	
														
													//getting timeline status
													$timelineStatus = getTimelineStatus($value3);
													
													
													$printFinalTasks[] = "<tr id='$value3' class='Tasks_$AssignedToID'><td><div class='taskCategory'>$TaskCategory</div><span class='heading'>$TaskTitle $isOverdue</span><span class='smallDesc'>$TaskDescription</span><div class='taskAssignedTo'>Assigned to $AssignedToFN</div>
													$timelineStatus</td><td align='center'>$TaskDueDate</td><td align='center' class='$TaskStatus'><strong class='taskStatus $TaskStatus'>$TaskStatus</strong></td><td align='center'>$Actions</td></tr>";
														}		
												
														
												}
											}									
	}
	
	// getting MY project tasks
	$getMyTasks = "SELECT `Title`, `Description`, DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y'), `Status`,`Category`, `Requested By`, `userID`,`TaskID` FROM `Tasks` WHERE `ProjectID` = '$printProjectID' AND `userID` = '$userID' ORDER BY `Due Date` ASC";
	$getMyTasks_result = mysqli_query($connection, $getMyTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMyTasks_result->fetch_assoc()) {
			$printMyTasks[] = $row["TaskID"];
	}
	
	if (!empty($printMyTasks)){
											foreach($printMyTasks as $value){
											//echo $value;
												$getMyTask = "SELECT `calendar`.`title` AS 'Event Title',`id` AS 'EventID',`username`,`CommentID`, `PP Link`,`Tasks`.`Title`,DATE_FORMAT(`Tasks`.`Task Date Created`, '%e %b %Y') AS 'printableTaskDateCreated',`Tasks`.`Task Date Created`,`Task Date Completed`, `Tasks`.`Description`,`Due Date` AS 'Task Due Date Standard', DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p'), `Status`,`Tasks`.`Category` AS 'TaskCategoryID',`Task Categories`.`Category`, `Requested By`, `Tasks`.`userID`,`Tasks`.`TaskID`, datediff(`Due Date`,now())  AS 'Days Left To Complete' FROM `Tasks` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` LEFT JOIN `calendar` ON `calendar`.`TaskID`=`Tasks`.`TaskID` LEFT JOIN `Task Comments` ON `Task Comments`.`TaskID`=`Tasks`.`TaskID` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` WHERE `Tasks`.`TaskID` = '$value' LIMIT 1";
												$getMyTask_result = mysqli_query($connection, $getMyTask) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

													while($row = $getMyTask_result->fetch_assoc()) {
													$printableTaskDateCreated = $row["printableTaskDateCreated"];
													$AssignedToID = $row["userID"];
													$TaskTitle = $row["Title"];
													$TaskCategory = $row["Category"];
													$TaskCategoryID = $row["TaskCategoryID"];
													$TaskDescription = $row["Description"];
													$TaskStatus = $row["Status"];
													$TaskID = $row["TaskID"];
													$TaskDueDate = $row["DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p')"];
													$TaskDueDateStandard = $row["Task Due Date Standard"];
													$AssignedToFN = $row["username"];
													$ppLink = $row["PP Link"];
													$eventID = $row["EventID"];
													$eventTitle = $row["Event Title"];
													$daysLeftToComplete = $row["Days Left To Complete"];
													
													$areComments = $row["CommentID"];
														
													if (!isset($AssignedToID))	{
														$printFinalTasks[] = "";
													}
													else {
														
													if ($areComments !== NULL) {
														
														$getCommentCount = "SELECT COUNT(*) FROM `Task Comments` WHERE `TaskID` = '$value'";
														$getCommentCount_result = mysqli_query($connection, $getCommentCount) or die ("Query to get data from Team Project failed: ".mysql_error());

														while ($row2 = mysqli_fetch_array($getCommentCount_result)) {
															$commentCount = $row2['COUNT(*)'];
														}
														
														
															$dot = '<div class="dot"><i class="fa fa-comments" aria-hidden="true"></i>'.$commentCount.'</div>';
														}
														else {
															$dot = "";
														}
													
														if (isset($eventID)) {
															$calendarIcon = "<a href='/dashboard//content-calendar/?eventID=$eventID'><div class='calendaricon'><i class='fa fa-calendar-check-o' aria-hidden='true'></i></div></a>";
															
														}
														
														else {
															$calendarIcon = "";
															
														}

														
													//GETTING PERMISSIONS
													$creatorActions = "<div class='infoicon' id='#infoicon' data-toggle='modal' data-target='#viewTask'>$dot<i class='fa fa-info-circle' aria-hidden='true'></i></div><a class='editicon' data-toggle='modal' data-target='#viewTask'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>$calendarIcon<div class='trashicon'><i class='fa fa-trash' aria-hidden='true'></i></div>";
													$assignedToActions = "<div class='infoicon' id='#infoicon' data-toggle='modal' data-target='#viewTask'>$dot<i class='fa fa-info-circle' aria-hidden='true'></i></div><a class='editicon' data-toggle='modal' data-target='#viewTask'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></div>$calendarIcon";
													$nonCreatorActions = "<div class='infoicon' id='#infoicon' data-toggle='modal' data-target='#viewTask'>$dot<i class='fa fa-info-circle' aria-hidden='true'></i></div>";
														
													

													if ($userID == $TaskRequestedBy || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $printProjectCreatedByGroupID) || $userID == $printProjectCreatedByUserID) {
														
														$Actions = $creatorActions;
														
														
													}	
													else if ($userID == $AssignedToID) {
														$Actions = $assignedToActions;
														
														
													}
													else{
														
														  $Actions = $nonCreatorActions;
														
														}
													
													//getting timeline status
													$timelineStatus = getTimelineStatus($TaskID);
														
													
													$printFinalMyTasks[]= "<tr id='$TaskID'>
													<td>
														<div class='taskCategory'>$TaskCategory</div>
														<span class='heading'>$TaskTitle</span>
														<span class='smallDesc'>$TaskDescription</span>
														 $timelineStatus
													</td>
													<td align='center'>
														$TaskDueDate
													</td>
													<td align='center'>
														<div>
															<strong class='taskStatus $TaskStatus'>$TaskStatus</strong>
														</div>
													</td>
													<td align='center'>
														$Actions
													</td>
													</tr>";
													}	
														
												}
											}
										}
	
	//////////// REVIEW RETRIEVAL ////////////
	$getReviews = "SELECT `username`,`ReviewID`, `Tickets Review`.`userID`, `ProjectID`,`Tickets Review`.`Title`, `Type`, DATE_FORMAT(`Date Created`, '%W, %b. %e, %Y @ %h:%i %p'), DATE_FORMAT(`Due Date`, '%m/%d/%Y @ %h:%i%p') AS 'Due Date', `Tickets Review`.`Status`, `Desktop Preview Image Link`, `Mobile Preview Image Link` FROM `Tickets Review` JOIN `user` ON `Tickets Review`.`userID` = `user`.`userID` WHERE `ProjectID` = '$printProjectID'";
	$getReviews_result = mysqli_query($connection, $getReviews) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $getReviews_result->fetch_assoc()) {
		 $printReviewID = $row["ReviewID"];
		 $printReviewTitle = $row["Title"];
		 $printReviewCreatorID = $row["userID"];
		 $printReviewCreatorUsername = $row["username"];
         $printReviewCreatedDate = $row["DATE_FORMAT(`Date Created`, '%W, %b. %e, %Y @ %h:%i %p')"]; 
		 $printReviewDueDate = $row["Due Date"];
		 $printReviewType = $row["Type"];
		 $printReviewStatus = $row["Status"];
		 $printReviewDesktopImage = $row["Desktop Preview Image Link"];
		 $printReviewMobileImage = $row["Desktop Mobile Image Link"];
		 
		 if (!isset($printReviewID)) {
			 $printReviews[] = '';
		 }
		 else {
		 
		 if ($userID == $printProjectCreatedByUserID ||$userID == $printReviewCreatorID || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $printProjectCreatedByGroupID)) {
									$canDeleteReview = "<i class='fa fa-pencil-square-o editReview' reviewid='$printReviewID' aria-hidden='true' data-toggle='modal' data-target='#editReview'></i>
									<i class='fa fa-trash deleteReview' reviewid='$printReviewID' aria-hidden='true'></i>";
								}
								  else {
									  $canDeleteReview ="";
								  }
	
		 
		 if ($printReviewStatus === "Approved") {
			 $finalApproval = "<strong class='approved' style='color:#00b304'>APPROVED</strong>";
		 }
		 else {
			  $finalApproval = "<strong class='pending' style='color:#ff0000'>PENDING</strong>";
		 }
		 
		 
		 $printReviews[] = "<tr style='border-bottom:1px solid #f1f1f1;'><td style='font-size: 14px;padding:10px 5px'><a href='review/?reviewID=$printReviewID' target='_blank'>$printReviewTitle</a>
		 <span class='muted grey'>Owner: $printReviewCreatorUsername</span></td><td style='font-size: 14px;' align='center'>$printReviewDueDate</td><td style='font-size: 14px;' align='center'>$finalApproval</td><td align='center' style='padding-right: 5px;'>$canDeleteReview</td></tr>";
		 
	 }
	 }	
		
	$allReviewCount=mysqli_num_rows($getReviews_result);	
		
	//////////// NOTE RETRIEVAL ////////////
	$getNotes = "SELECT `NoteID`,`Message`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'),`PP Link`, `Project Notes`.`userID`, `ProjectID` FROM `Project Notes` JOIN `user` ON `user`.`userID`= `Project Notes`.`userID` WHERE `ProjectID` = '$printProjectID' ORDER BY `Timestamp` ASC";
	
	$getNotes_result = mysqli_query($connection, $getNotes) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getNotes_result->fetch_assoc()) {
			$whoSent = $row["userID"];
			$ProjectID = $row["ProjectID"];
			$Timestamp = $row["DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')"];
			$Message = $row["Message"];
			$MessageID = $row["NoteID"];
			$messagePic = $row["PP Link"];
			
		
			if (!isset($whoSent)) {
			 $printMessages[] = '';
		 }
		else {
		
			$getWhoSent = "SELECT * FROM `user` WHERE `userID` = '$whoSent'";
			$getWhoSent_result = mysqli_query($connection, $getWhoSent) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

			while($row3 = $getWhoSent_result->fetch_assoc()) {	
			$WhoSentFN = $row3["username"];
			}
		
			
			if ($whoSent != $userID){
				$messageCSS = "incomingCom";
				$printMessages[] = "<table class='comments $messageCSS' id='$MessageIDC'><tr><td style='border:0px !important;' class='sender'><img class='commentsImage' src='$messagePic'></td><td style='border:0px !important;width: 100% !important;'><span>@$WhoSentFN</span><div class='timestamp'>$Timestamp</div></td></tr><tr><td colspan='2'><pre class='message'>$Message</pre><div class='removeNoteContainer'><div class='removeNote' noteid='$MessageID'><br><i class='fa fa-trash' aria-hidden='true'></i></div></div></td></tr></table>";
			}
			else {
				$messageCSS = "outgoingCom";
				$printMessages[] = "<table class='comments $messageCSS' id='$MessageID'><tr><td style='border:0px !important;width: 100% !important;'><span>@$WhoSentFN</span><div class='timestamp'>$Timestamp</div></td><td style='border:0px !important;' class='sender'><img class='commentsImage' src='$messagePic'></td></tr><tr><td colspan='2'><pre class='message'>$Message</pre><div class='removeNoteContainer'><div class='removeNote' noteid='$MessageID'><br><i class='fa fa-trash' aria-hidden='true'></i></div></div></td></tr></table>";
			}
			
			
		}
		
		
		
	
	}
		$allNotesCount=mysqli_num_rows($getNotes_result);
	
	//////////// ACTIVITY FEED RETRIEVAL ////////////
	
	// getting all project activity feed
	
	//getting project related reviews
	$getReviewArray = "SELECT * FROM `Tickets Review` WHERE `ProjectID` = '$printProjectID'";
	$getReviewArray_result = mysqli_query($connection, $getReviewArray) or die ("getActivity_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getReviewArray_result->fetch_assoc()) {
		$reviewIDArray[] = $row["ReviewID"];;
	}	
	
	if (empty($reviewIDArray)) {
		$getActivity = "SELECT `ActivityID`,DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `userID`, `Activity` FROM `Activity Feed` WHERE `ProjectID` = '$printProjectID' ORDER BY `ActivityID` ASC";
	}
	else {
			$reviewIDArrayImploded = implode(", ",$reviewIDArray);
		
	$getActivity = "SELECT `ActivityID`,DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `userID`, `Activity` FROM `Activity Feed` WHERE `ProjectID` = '$printProjectID' OR `ReviewID` IN ($reviewIDArrayImploded) ORDER BY `ActivityID` ASC";
		}
	
		
	
		
	
	
	$getActivity_result = mysqli_query($connection, $getActivity) or die ("getActivity_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getActivity_result->fetch_assoc()) {
			$who = $row["userID"];
			$Timestamp = $row["DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')"];
			$Activity = $row["Activity"];
			$getWho = "SELECT * FROM `user` WHERE `userID` = '$who'";
			$getWho_result = mysqli_query($connection, $getWho) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

			while($row3 = $getWho_result->fetch_assoc()) {	
			$WhoFN = $row3["username"];
			}
		
		$printActivities[] = "<li class='feed-item'><span class='date'>$Timestamp</span><span class='text'><span class='member'>@$WhoFN</span> $Activity</span></li>";
		
	
	}
	
	//////////// GETTING CADENCE ////////////	
	$query = "SELECT `TaskID`,`username`,`Tasks`.`TaskID`, `Tasks`.`Title`, `Tasks`.`Description`,`Tasks`.`Due Date`, DATE_FORMAT(`Tasks`.`Due Date`, '%b %d %Y @ %h:%i%p') AS 'Task Due Date', `Tasks`.`Status`, `Tasks`.`Category` AS ' TaskCategoryID',`Task Categories`.`Category` AS 'TaskCategory', `Requested By`, `Tasks`.`ProjectID`, `Tasks`.`userID` AS 'Assigned To ID', `Tasks`.`allDay`, `Task Date Created`, `Task Date Completed`, datediff(`Tasks`.`Due Date`,now())  AS 'Days Left To Complete' FROM `Tasks` JOIN `Task Categories` ON `Tasks`.`Category` = `Task Categories`.`CategoryID` JOIN `user` ON `user`.`userID` = `Tasks`.`userID` JOIN `Team Projects` ON `Tasks`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `Tasks`.`ProjectID` ='$printProjectID' AND `Task Type`='Cadence' ORDER BY `Tasks`.`Due Date` ASC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

	while($row = $query_result->fetch_assoc()) {
		$cadenceTaskID=$row["TaskID"];
		$cadenceTaskTitle=$row["Title"];
		$cadenceTaskDescription=$row["Description"];
		$cadenceTaskDueDate=$row["Task Due Date"];
		$cadenceTaskStatus=$row["Status"];
		$cadenceTaskCategoryID=$row["TaskCategoryID"];
		$cadenceTaskCategory=$row["TaskCategory"];
		$cadenceTaskRequestedBy=$row["Requested By"];
		$cadenceTaskAssignedToID=$row["Assigned To ID"];
		$cadenceTaskAssignedTo=$row["username"];
		$cadenceTaskDueDateReg=$row["Due Date"];
		$cadenceDateCompleted=$row["Task Date Completed"];
		$cadenceDaysLeftToComplete = $row["Days Left To Complete"];
		
		if (isset($cadenceTaskID)) {
			$query2 = "SELECT `TaskID` FROM `Tasks` WHERE `ProjectID` ='$printProjectID' AND `Status` != 'Completed' ORDER BY `Due Date` ASC LIMIT 1";
		$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query2_result->fetch_assoc()) {
			$currentlyActiveID = $row["TaskID"];
		}
		
		//if overdue
		$today = date("Y-m-d h:i:s");
		if ($currentlyActiveID == $cadenceTaskID && !isset($cadenceDateCompleted) && $cadenceTaskDueDateReg < $today) {
			$cadenceOverdue = "overdueCadence";
			$overdueCadenceIcon = "overdueCadenceIcon";
		}
		else {
			$cadenceOverdue = $currentlyActiveID. " ".$cadenceTaskID;
			$overdueCadenceIcon = "";
		}
		//icon
		
		if ($currentlyActiveID == $cadenceTaskID) {
			$cadenceStatus = "activeCadence";
			$cadenceIcon = '<div class="icon '.$overdueCadenceIcon.'" style="background:#4801FF"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></div>';
		}
		else if ($cadenceTaskStatus == "Completed") {
			$cadenceStatus = "completedCadence";
			$cadenceIcon = '<div class="icon '.$overdueCadenceIcon.'" style="background:#17B300"><i class="fa fa-check" aria-hidden="true"></i></div>';
		}
		else {
			$cadenceStatus = "";
			$cadenceIcon = '<div class="icon '.$overdueCadenceIcon.'" style="background:#ccc"><i class="fa fa-question-circle" aria-hidden="true"></i></div>';
		}
		
			
		$timelineStatus = getTimelineStatus($cadenceTaskID);
		
		
		$query4 = "SELECT COUNT(`TaskID`) FROM `Tasks` JOIN `Team Projects` ON `Tasks`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `Tasks`.`ProjectID` ='$printProjectID' AND `Task Type`='Cadence'";
		$query4_result = mysqli_query($connection, $query4) or die ("Query to get data from Team task failed: ".mysql_error());

		while($row = $query4_result->fetch_assoc()) {
			$preTaskCount = round(12/$row["COUNT(`TaskID`)"]);
			if ($row["COUNT(`TaskID`)"] == 5 || $row["COUNT(`TaskID`)"] >= 7) {
				$taskCount = 3;
			}
			else {
				$taskCount = $preTaskCount;
			}
		}
		
		
		$printCadence[] = '<div class="col-sm-'.$taskCount.'">
		<div class="cadenceContainer" taskid="'.$cadenceTaskID.'">
		<div class="cadence '.$cadenceStatus.' '.$cadenceOverdue.'">'.$cadenceIcon.'
		<div class="taskStatus '.$cadenceTaskStatus.'">'.$cadenceTaskStatus.'</div>
		<div class="contain1">
		<div class="category">'.$cadenceTaskCategory.'</div>
		<h1>'.$cadenceTaskTitle.'</h1>
		</div>
		<div class="contain2">
		'.$timelineStatus.'
		<p><i class="fa fa-user" aria-hidden="true"></i> @'.$cadenceTaskAssignedTo.'</p>
		<p><i class="fa fa-calendar-o" aria-hidden="true"></i>'.$cadenceTaskDueDate.'</p>
		</div>
		</div>
		</div>
		</div>';
		}
		else {
			$printCadence[] = '';
		}
		
	}	
	
//GETTING FILES
	// Opens directory
		$dir = 'uploads/'.$projectSelector;
		if (!file_exists($dir) && !is_dir($dir)) {
    		$printFiles[] ="";
		}
		else {
			$myDirectory=opendir('uploads/'.$projectSelector);
							$path = 'uploads/'.$projectSelector;
							// Gets each entry
							while($entryName=readdir($myDirectory)) {
							  $dirArray[]=$entryName;
							}

							// Finds extensions of files
							function findexts ($filename) {
							  $filename=strtolower($filename);
							  $exts=split("[/\\.]", $filename);
							  $n=count($exts)-1;
							  $exts=$exts[$n];
							  return $exts;
							}

							// Closes directory
							closedir($myDirectory);

							// Counts elements in array
							$indexCount=count($dirArray);
							
							$fileCount = count(scandir($path )) - 2;
							
			

							// Sorts files
							sort($dirArray);

							// Loops through the array of files
							for($index=0; $index < $indexCount; $index++) {

							  // Allows ./?hidden to show hidden files
							  if($_SERVER['QUERY_STRING']=="hidden")
							  {$hide="";
							  $ahref="./";
							  $atext="Hide";}
							  else
							  {$hide=".";
							  $ahref="./?hidden";
							  $atext="Show";}
							  if(substr("$dirArray[$index]", 0, 1) != $hide) {

							  // Gets File Names
							  $name=$dirArray[$index];
							  $namehref=$dirArray[$index];

								//getting last modified date
								$lastModified=  date ("M d Y h:i:s A.", filemtime("uploads/".$projectSelector."/".$name));
							  
							  // Print 'em
								 
								if ($userID == $printProjectCreatedByUserID || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $printProjectCreatedByGroupID) || in_array($userID, $memberIDArray) ) {
									$canDeleteFile = "<div file='$name' path='$path' class='delete_link'><i class='fa fa-trash' aria-hidden='true'></i>
									</div>";
									$canDownloadFile = "<a href='$path/$name' target='_blank' download>$name</a>";
								}
								  else {
									  $canDeleteFile ="";
									  $canDownloadFile ="$name";
								  }
								  
								$printFiles[] = "<div class='col-sm-3'>
								<div class='file'>
									$canDeleteFile
									<table width='100%'>
									<tr>
										<td valign='top'><div class='fileIcon'><i class='fa fa-file-o' aria-hidden='true'></i></div></td>
										<td><div class='content'>
									$canDownloadFile
									
									<p>Last Modified: $lastModified</p>
									</div></td>
									</tr>
									</table>
									
									
									</div>
									</div>";
							 
							  }
							}
		}
		
							

////////////
	
	
	$results = ["printProjectTitle" => $printProjectTitle,
				"printProjectDueDate" => $printProjectDueDate, 
				"printProjectCategory" => $printProjectCategory, 
				"printProjectTaskType" => $printProjectTaskType, 
				"printProjectDescription" => $printProjectDescription, 
				"printProjectCopy" => $printProjectCopy,
				"printProjectCreatedBy" => $printProjectCreatedBy,
				"printVisible" => $printVisible, 
				"printMembers" => $printMembers,
				"printFiles" => $printFiles,
				"printReviews" => $printReviews,
	"printTasks" => $printFinalTasks, 
	"printMyTasks" => $printFinalMyTasks,
	"printMessages" => $printMessages,
	"printActivities" => $printActivities,
	"printProjectFolder" => $printProjectFolder,
	"printProjectURL" => $printProjectURL,
				"projectCreatedByPP" => $projectCreatedByPP,
	"printProjectStatus" => $printProjectStatus,
			   "projectTaskCount" => $TasksCount,
			   "favorite" => $favorite,
			   "canAddMembers" => $canAddMembers,
				"canRemoveMembers" => $canRemoveMembers,
			   "canEditProject" => $canEditProject,
			   "canAddNote" => $canAddNote,
				"canMention" => $canMention,
				"mentionUsers" => $mentionUsers,
			   "canAddFile" => $canAddFile,
				"canDeleteFile" => $canDeleteFile,
				"canAddCopy" => $canAddCopy,
				"canAddTask" => $canAddTask,
			   "canAddReview" => $canAddReview,
			   "printMembersDropdown" => $printMembersDropdown,
				"printCadence" => $printCadence,
		"allTaskCount" =>$allTaskCount,
			   "allReviewCount" => $allReviewCount,
			   "allNotesCount" => $allNotesCount,
			   "fileCount" => $fileCount,
			   "ticketAvailable" =>$ticketAvailable];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);

	}
	
	
	

}

if (isset($filterType)) {

	if ($filter=="All") {
		$getAllProjects = "SELECT DISTINCT `Team Projects`.`ProjectID`, `Team Projects`.`Status`, `Team Projects`.`Title`, `Team Projects`.`Description`, `Team Projects Categories`.`Category`, DATE_FORMAT(`Due Date`, '%M %e, %Y'), `Team Projects`.`userID`, `Date Created`, `Visible` FROM `Team Projects` JOIN `Team Projects Categories` ON `Team Projects Categories`.`ProjectCategoryID`=`Team Projects`.`Category` JOIN `Team Projects Member List` ON `Team Projects Member List`.`ProjectID`=`Team Projects`.`ProjectID` WHERE (`Team Projects`.`userID`='$userID' OR `Team Projects Member List`.`userID`='$userID') ORDER BY `Date Created` DESC";
	}
	else {
		$getAllProjects = "SELECT DISTINCT `Team Projects`.`ProjectID`, `Team Projects`.`Status`, `Team Projects`.`Title`, `Team Projects`.`Description`, `Team Projects Categories`.`Category`, DATE_FORMAT(`Due Date`, '%M %e, %Y'), `Team Projects`.`userID`, `Date Created`, `Visible` FROM `Team Projects` JOIN `Team Projects Categories` ON `Team Projects Categories`.`ProjectCategoryID`=`Team Projects`.`Category` JOIN `Team Projects Member List` ON `Team Projects Member List`.`ProjectID`=`Team Projects`.`ProjectID` WHERE (`Team Projects`.`userID`='$userID' OR `Team Projects Member List`.`userID`='$userID') AND `Team Projects`.`$filterType` = '$filter' ORDER BY `Date Created` DESC";
	}
	
	
	$getAllProjects_result = mysqli_query($connection, $getAllProjects) or die ("Query to get data from Team Project failed: ".mysql_error());

	while ($row = mysqli_fetch_array($getAllProjects_result)) {
											$projectID = $row['ProjectID'];
											$projectTitle = $row['Title'];
											$projectDueDate = $row["DATE_FORMAT(`Due Date`, '%M %e, %Y')"];
											$projectCategory = $row['Category'];
											$projectCategory2 = str_replace(' ', '', $row['Category']);
											$projectStatus = $row['Status'];
											$projectCreatorID = $row['userID'];
											$projectVisibility = $row['Visible'];
											
											
											$getprojectCreatorFNByID = "SELECT * FROM `user` WHERE `userID` = '$projectCreatorID'";
											$getprojectCreatorFNByID_result = mysqli_query($connection, $getprojectCreatorFNByID) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getprojectCreatorFNByID_result)) {
												$getprojectCreatorFN = $row2['First Name']." ".$row2['Last Name'];
											}
										

											$getAllTasks = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectID'";
											$getAllTasks_result = mysqli_query($connection, $getAllTasks) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getAllTasks_result)) {
												$projectTaskCount = $row2['COUNT(*)'];
											}
											$tS="";
											if ($projectTaskCount >= 2) {
												$tS="s";
											}
											else if ($projectTaskCount < 1) {
												$tS="s";
											}
											
											$getAllNotes = "SELECT COUNT(*) FROM `Project Notes` WHERE `ProjectID` = '$projectID'";
											$getAllNotes_result = mysqli_query($connection, $getAllNotes) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getAllNotes_result)) {
												$projectNotesCount = $row2['COUNT(*)'];
											}
											$tN="";
											if ($projectNotesCount >= 2) {
												$tN="s";
											}
											else if ($projectNotesCount == 0) {
												$tN="s";
											}
											else if ($projectNotesCount == 1) {
												$tN="";
											}
											
											$getAllMembers = "SELECT COUNT(*) FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID'";
											$getAllMembers_result = mysqli_query($connection, $getAllMembers) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getAllMembers_result)) {
												$projectMembersCount = $row2['COUNT(*)'];
											}
											$tM="";
											if ($projectMembersCount >= 2) {
												$tM="s";
											}
											else if ($projectMembersCount == 0) {
												$tM="s";
											}
											else if ($projectMembersCount == 1) {
												$tM="";
											}
											
											$getLastEditedID = "SELECT MAX(`ActivityID`) FROM `Activity Feed` WHERE `ProjectID`='$projectID'";
											$getLastEditedID_result = mysqli_query($connection, $getLastEditedID) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getLastEditedID_result)) {
												$projectLastEditedID = $row2['MAX(`ActivityID`)'];
											
												$getLastEdited = "SELECT * FROM `Activity Feed` JOIN `user` ON `Activity Feed`.`userID` = `user`.`userID` WHERE `ActivityID` = '$projectLastEditedID'";
												$getLastEdited_result = mysqli_query($connection, $getLastEdited) or die ("Query to get data from Team Project failed: ".mysql_error());

												while ($row2 = mysqli_fetch_array($getLastEdited_result)) {
													$projectLastEditedTimestamp = $row2['Timestamp'];
													$projectLastEdited = $row2['First Name']." ".$row2['Last Name'];
													$currentDate = date("Y/m/d");
														$getLastEditedDay = "SELECT DATEDIFF('$currentDate', '$projectLastEditedTimestamp') AS 'Difference'";
														$getLastEditedDay_result = mysqli_query($connection, $getLastEditedDay) or die ("Query to get data from Team Project failed: ".mysql_error());

														while ($row2 = mysqli_fetch_array($getLastEditedDay_result)) {
															$projectLastEditedDaysAgoCount = $row2['Difference'];
															

															}
												
												
												}
												
											}
											
											$getRegDueDate = "SELECT `Due Date`,`Status` FROM `Team Projects` WHERE `ProjectID`='$projectID'";
											$getRegDueDate_result = mysqli_query($connection, $getRegDueDate) or die ("Query to get data from Team Project failed: ".mysql_error());

											while ($row = mysqli_fetch_array($getRegDueDate_result)) {
												$regDueDate = $row["Due Date"];
												$projStatus = $row["Status"];
												$getcurDate = date("Y/m/d");
												
													
													$getDaysLeft = "SELECT DATEDIFF('$regDueDate','$getcurDate') AS 'Difference'";
													$getDaysLeft_result = mysqli_query($connection, $getDaysLeft) or die ("Query to get data from Team Project failed: ".mysql_error());

													while ($row2 = mysqli_fetch_array($getDaysLeft_result)) {
															if ($projStatus == "Complete") {
																$projectDaysLeft = '<strong>COMPLETE</strong>';
																$archived="";
															}
															else if ($projStatus == "Archived") {
																$projectDaysLeft = '<strong>ARCHIVED</strong>';
																$archived = "project_Archived";
															}
															else {
																$archived="";
																	$dayCount = abs($row2['Difference']);
																	$days="";
																	if ($dayCount >= 2) {
																		$days="s";
																	}
																	else if ($dayCount <= 1) {
																		$days="";
																	}
																	if ($regDueDate>$getcurDate){
																		$projectDaysLeft = '<strong style="color:#ff0000">'.abs($row2['Difference']).'</strong> day'.$days.' overdue';
																	}
																	else {$projectDaysLeft = '<strong>'.abs($row2['Difference']).'</strong> day'.$days.' left';}
																
															}
															
																
															
														}
														
											}
											
											if ($projectVisibility =="Private") {
												$private = "<span class='pull-right' style='font-weight:bold;color:#ff0000'>PRIVATE</span>";
											} 
											else {
												$private="";
											} 
											
											$printBackProjects[]= "<div class='col-sm-4'><div class='project $archived fadeInUp'><div class='heading'><div class='pull-right'>$projectDaysLeft</div><h4>$projectCategory</h4><h1>$projectTitle</h1><h5><strong>Created By:</strong> <span class='getprojectCreatorFN'>$getprojectCreatorFN</span><br><br><strong>Due:</strong> <span class='projectDueDate'>$projectDueDate</span> $private</h5></div><div class='information'><div class='row'><div class='col-sm-4 text-center'><h1>$projectTaskCount</h1><p>Task$tS</p></div><div class='col-sm-4 text-center'><h1>$projectNotesCount</h1><p>Note$tN</p></div><div class='col-sm-4 text-center'><h1>$projectMembersCount</h1><p>Member$tM</p></div></div></div><p><strong>Last edited by:</strong> $projectLastEdited<br><span style='font-size:12px;'>".$projectLastEditedDaysAgoCount."d ago</span></p>
											<center><a class='project-btn' id='$projectID' href='view/?projectID=$projectID'>Open</a></center></div></div>";
										}
	
	$results = ["printBackProjects" => $printBackProjects];
	header('Content-Type: application/json'); 
	echo json_encode($results);
}


?>