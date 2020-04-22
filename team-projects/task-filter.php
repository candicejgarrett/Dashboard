<?php 
require('../header.php');
require('../functions/global.php');
$type=$_POST['type'];
$searchTerm=$_POST['searchTerm'];
$searchTermPrintBack=$_POST['searchTerm'];
$status=$_POST['status'];
$owner=$_POST['owner'];
$category=$_POST['category'];
$sortBy = $_POST['sortBy'];
$projectID = $_POST['projectID'];



if ($type =="myTasks") {
	if (isset($searchTerm) || $searchTerm !== "") {
	$addSearchTerm="AND `Tasks`.`Title` LIKE '%$searchTerm%'";
}
else {
	$addSearchTerm="";
}
if ($status != "All" || $status !== "All") {
	$addStatus="AND `Tasks`.`Status` = '$status'";
}
else {
	$addStatus="";
}
if ($owner != "All" || $owner !== "All") {
	$addOwner="AND `Tasks`.`Requested By` = '$owner'";
}
else {
	$addOwner="";
}
if ($category != "All" || $category !== "All") {
	$addCategory="AND `Tasks`.`Category` = '$category'";
}
else {
	$addCategory="";
}
if ($sortBy == "Date Created DESC") {
	$addSortBy = "ORDER BY `Tasks`.`Task Date Created` DESC";
}
else if ($sortBy == "Date Created ASC") {
	$addSortBy = "ORDER BY `Tasks`.`Task Date Created` ASC";
}
else if ($sortBy == "Due Date DESC") {
	$addSortBy = "ORDER BY `Tasks`.`Due Date` DESC";
}
else if ($sortBy == "Due Date ASC") {
	$addSortBy = "ORDER BY `Tasks`.`Due Date` ASC";
}
else if ($sortBy == "AtoZ") {
	$addSortBy = "ORDER BY `Tasks`.`Title` ASC";
}
else if ($sortBy == "ZtoA") {
	$addSortBy = "ORDER BY `Tasks`.`Title` DESC";
}
else {
	$addSortBy = "ORDER BY `Tasks`.`Task Date Created` DESC";
}
	// getting MY project tasks
	$getMyTasks = "SELECT DISTINCT `Title`, `Description`, DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y'), `Status`,`Category`, `Requested By`, `userID`,`TaskID`, `Task Date Created`,`Due Date` FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `userID` = '$userID' $addSearchTerm $addStatus $addOwner $addCategory $addSortBy";
	$getMyTasks_result = mysqli_query($connection, $getMyTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMyTasks_result->fetch_assoc()) {
			$printMyTasks[] = $row["TaskID"];
	}
	
	if (!empty($printMyTasks)){
											foreach($printMyTasks as $value){
											//echo $value;
												$getMyTask = "SELECT `calendar`.`title` AS 'Event Title',`id` AS 'EventID',`username`,`CommentID`, `PP Link`,`Tasks`.`Title`,DATE_FORMAT(`Tasks`.`Task Date Created`, '%e %b %Y') AS 'printableTaskDateCreated',`Tasks`.`Task Date Created`,`Task Date Completed`, `Tasks`.`Description`,`Due Date` AS 'Task Due Date Standard', DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p'), `Status`,`Tasks`.`Category` AS 'TaskCategoryID',`Task Categories`.`Category`, `Requested By`, `Tasks`.`userID`,`Tasks`.`TaskID`, datediff(`Due Date`,now()) AS 'Days Left To Complete' FROM `Tasks` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` LEFT JOIN `calendar` ON `calendar`.`TaskID`=`Tasks`.`TaskID` LEFT JOIN `Task Comments` ON `Task Comments`.`TaskID`=`Tasks`.`TaskID` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` WHERE `Tasks`.`TaskID` = '$value' LIMIT 1";
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
													
													$areComments = $row["CommentID"];
$daysLeftToComplete = $row["Days Left To Complete"];
														
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
														
													

													if ($userID == $TaskRequestedBy || $myRole === 'Admin' || $myRole === 'Editor' || $userID == $printProjectCreatedByUserID) {
														
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
													
													$printFinalMyTasks[]= "<tr id='$TaskID'><td><div class='taskCategory'>$TaskCategory</div><span class='heading'>$TaskTitle $isOverdue</span><span class='smallDesc'>$TaskDescription</span>$timelineStatus</td><td align='center'>$TaskDueDate</td><td align='center'><div><strong class='taskStatus $TaskStatus'>$TaskStatus</strong></div></td><td align='center'>$Actions</td></tr>";
													}	
														
												}
											}
										}
	$results = ["printMyTasks" => $printFinalMyTasks,
			   "searchTerm" => $searchTermPrintBack,
			   "status" => $status,
			   "owner" => $owner,
			   "category" => $category,
			   "sortBy" => $sortBy];
	header('Content-Type: application/json'); 
	echo json_encode($results);
}

if ($type =="allTasks") {
	if (isset($searchTerm)) {
	$addSearchTerm="AND `Tasks`.`Title` LIKE '%$searchTerm%'";
}
else {
	$addSearchTerm="";
}
if ($status != "All") {
	$addStatus="AND `Tasks`.`Status` = '$status'";
}
else {
	$addStatus="";
}
if ($owner != "All") {
	$addOwner="AND `Tasks`.`Requested By` = '$owner'";
}
else {
	$addOwner="";
}
if ($category != "All") {
	$addCategory="AND `Tasks`.`Category` = '$category'";
}
else {
	$addCategory="";
}
if ($sortBy == "Date Created DESC") {
	$addSortBy = "ORDER BY `Tasks`.`Task Date Created` DESC";
}
else if ($sortBy == "Date Created ASC") {
	$addSortBy = "ORDER BY `Tasks`.`Task Date Created` ASC";
}
else if ($sortBy == "Due Date DESC") {
	$addSortBy = "ORDER BY `Tasks`.`Due Date` DESC";
}
else if ($sortBy == "Due Date ASC") {
	$addSortBy = "ORDER BY `Tasks`.`Due Date` ASC";
}
else if ($sortBy == "AtoZ") {
	$addSortBy = "ORDER BY `Tasks`.`Title` ASC";
}
else if ($sortBy == "ZtoA") {
	$addSortBy = "ORDER BY `Tasks`.`Title` DESC";
}
else {
	$addSortBy = "ORDER BY `Tasks`.`Task Date Created` DESC";
}
	
	$getAllTasks = "SELECT DISTINCT `Title`, `Description`, DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y'), `Status`,`Category`, `Requested By`,`Task Date Created`, `userID`,`TaskID`, `Task Date Created`,`Due Date` FROM `Tasks` WHERE `ProjectID` = '$projectID' $addSearchTerm $addStatus $addOwner $addCategory $addSortBy";
	$getAllTasks_result = mysqli_query($connection, $getAllTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getAllTasks_result->fetch_assoc()) {
			$printTasks[] = $row["TaskID"];
	}
	
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
													
														
													

													if ($userID == $TaskRequestedBy || $myRole === 'Admin' || $myRole === 'Editor' || $userID == $printProjectCreatedByUserID) {
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
														
													
													$printFinalTasks[] = "<tr id='$value3' class='Tasks_$AssignedToID'><td><div class='taskCategory'>$TaskCategory</div><span class='heading'>$TaskTitle $isOverdue</span><span class='smallDesc'>$TaskDescription</span><div class='taskAssignedTo'>Assigned to $AssignedToFN $timelineStatus</div></td><td align='center'>$TaskDueDate</td><td align='center' class='$TaskStatus'><strong class='taskStatus $TaskStatus'>$TaskStatus</strong></td><td align='center'>$Actions</td></tr>";
														}		
												
														
												}
											}	
										}
	$results = ["printTasks" => $printFinalTasks,
			   "searchTerm" => $searchTermPrintBack,
			   "status" => $status,
			   "owner" => $owner,
			   "category" => $category,
			   "sortBy" => $sortBy];
	header('Content-Type: application/json'); 
	echo json_encode($results);
}

if ($type =="showMyTasks") {
	// getting MY project tasks
	$getMyTasks = "SELECT DISTINCT `Title`, `Description`, DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y'), `Status`,`Category`, `Requested By`, `userID`,`TaskID`, `Task Date Created`,`Due Date` FROM `Tasks` WHERE `ProjectID` = '$projectID' AND `userID` = '$userID' ORDER BY `Tasks`.`Task Date Created` DESC";
	$getMyTasks_result = mysqli_query($connection, $getMyTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMyTasks_result->fetch_assoc()) {
			$printMyTasks[] = $row["TaskID"];
	}
	
	if (!empty($printMyTasks)){
											foreach($printMyTasks as $value){
											//echo $value;
												$getMyTask = "SELECT `calendar`.`title` AS 'Event Title',`id` AS 'EventID',`username`,`CommentID`, `PP Link`,`Tasks`.`Title`,DATE_FORMAT(`Tasks`.`Task Date Created`, '%e %b %Y') AS 'printableTaskDateCreated',`Tasks`.`Task Date Created`,`Task Date Completed`, `Tasks`.`Description`,`Due Date` AS 'Task Due Date Standard', DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y @ %h:%i %p'), `Status`,`Tasks`.`Category` AS 'TaskCategoryID',`Task Categories`.`Category`, `Requested By`, `Tasks`.`userID`,`Tasks`.`TaskID`, datediff(`Due Date`,now()) AS 'Days Left To Complete' FROM `Tasks` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` LEFT JOIN `calendar` ON `calendar`.`TaskID`=`Tasks`.`TaskID` LEFT JOIN `Task Comments` ON `Task Comments`.`TaskID`=`Tasks`.`TaskID` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` WHERE `Tasks`.`TaskID` = '$value' LIMIT 1";
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
													
													$areComments = $row["CommentID"];
$daysLeftToComplete = $row["Days Left To Complete"];
														
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
														
													

													if ($userID == $TaskRequestedBy || $myRole === 'Admin' || $myRole === 'Editor' || $userID == $printProjectCreatedByUserID) {
														
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
														
													$printFinalMyTasks[]= "<tr id='$TaskID'><td><div class='taskCategory'>$TaskCategory</div><span class='heading'>$TaskTitle $isOverdue</span><span class='smallDesc'>$TaskDescription</span>$timelineStatus</td><td align='center'>$TaskDueDate</td><td align='center'><div><strong class='taskStatus $TaskStatus'>$TaskStatus</strong></div></td><td align='center'>$Actions</td></tr>";
													}	
														
												}
											}
										}
	$results = ["printMyTasks" => $printFinalMyTasks];
	header('Content-Type: application/json'); 
	echo json_encode($results);
	
}

if ($type =="showAllTasks") {
	$getAllTasks = "SELECT DISTINCT `Title`, `Description`, DATE_FORMAT(`Due Date`, '%a, %b. %e, %Y'), `Status`,`Category`, `Requested By`,`Task Date Created`, `userID`,`TaskID`, `Task Date Created`,`Due Date` FROM `Tasks` WHERE `ProjectID` = '$projectID' ORDER BY `Tasks`.`Task Date Created` DESC";
	$getAllTasks_result = mysqli_query($connection, $getAllTasks) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());
	
	while($row = $getAllTasks_result->fetch_assoc()) {
			$printTasks[] = $row["TaskID"];
	}
	
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

													if ($userID == $TaskRequestedBy || $myRole === 'Admin' || $myRole === 'Editor' || $userID == $printProjectCreatedByUserID) {
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
														
													
													$printFinalTasks[] = "<tr id='$value3' class='Tasks_$AssignedToID'><td><div class='taskCategory'>$TaskCategory</div><span class='heading'>$TaskTitle $isOverdue</span><span class='smallDesc'>$TaskDescription</span><div class='taskAssignedTo'>Assigned to $AssignedToFN $timelineStatus</div></td><td align='center'>$TaskDueDate</td><td align='center' class='$TaskStatus'><strong class='taskStatus $TaskStatus'>$TaskStatus</strong></td><td align='center'>$Actions</td></tr>";
														}		
												
														
												}
											}	
										}
	$results = ["printTasks" => $printFinalTasks];
	header('Content-Type: application/json'); 
	echo json_encode($results);
}
?>