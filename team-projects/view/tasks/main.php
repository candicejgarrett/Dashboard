<?php 
require('../../../connect.php');
require('../../../header.php');
require('../../../emailDependents.php');
include('../../../functions/global.php');
$type = $_POST["type"];
$thisyear= date("Y");




if ($type=="add") {
	$projectID=$_POST['projectID1'];
	$taskTitle=addslashes($_POST['taskTitle1']);	
	$taskTitleNoInsert= $_POST['taskTitle1'];	
	$taskDueDate = date("Y-m-d H:i:s",strtotime($_POST['taskDueDate1']));
	$eventCategory = $_POST['eventCategory'];
	$taskEndDate = $_POST['taskEndDate'];
	$taskCategory = $_POST['taskCategory1'];	
	$taskDescription = addslashes($_POST['taskDescription1']);
	$taskDescriptionNoInsert = $_POST['taskDescription1'];
	$TaskMemberUserID =$_POST['addTaskMember1'];	
	addTask($projectID,$taskTitle,$taskTitleNoInsert,$taskDueDate,$eventCategory,$taskEndDate,$taskCategory,$taskDescription,$taskDescriptionNoInsert,$TaskMemberUserID);

}

if ($type=="viewTask") {
	$taskID=$_POST['taskID'];
	//getting all 
	$taskInfo = getTaskInfo($taskID);
	
	$taskTitle = $taskInfo["title"];
	$taskTitleNoInsert = $taskInfo["title"];
	$taskDesc = $taskInfo["description"];
	$taskDescNoInsert = $taskInfo["description"];
	$taskDueDate = $taskInfo["dueDate"];
	$taskDueDateDisplay = $taskInfo["dueDateDisplay"];
	$taskStatus = $taskInfo["Status"];
	$taskCategoryID = $taskInfo["categoryID"];
	$taskCategoryTitle = $taskInfo["categoryTitle"];
	$taskProjectID = $taskInfo["projectID"];
	$taskProjectTitle = $taskInfo["projectTitle"];
	$taskProjectTitleNoInsert = $taskInfo["projectTitle"];
	$taskProjectTaskType = $taskInfo["projectTaskType"];
	$taskRequestedByUserID = $taskInfo["requestedByUserID"];
	$taskAssignedToUserID = $taskInfo["assignedToUserID"];
	$taskAssignedToFullName = $taskInfo["assignedToFullName"];
	$taskAssignedToPP = $taskInfo["assignedToPP"];
	$taskEventID = $taskInfo["eventID"];
	$taskEventTitle = $taskInfo["eventTitle"];
	$taskEventTitleNoInsert = $taskInfo["eventTitle"];
	$taskEventEndDate = $taskInfo["eventEndDate"];
	$taskEventCategoryID = $taskInfo["eventCategoryID"];
	$taskEventCategory = $taskInfo["eventCategory"];
	$taskStatusNumber = $taskInfo["statusNumber"];
	
	//getting comments
	$getTaskComments = getTaskComments($taskID);
	
	$taskComments = $getTaskComments["printComments"];
	$taskCanComment = $getTaskComments["canComment"];
	
	//getting project created by
	$projectInfo = getProjectInfo($taskProjectID);
	$projectOwnerUserID = $projectInfo["ownerUserID"];
	$projectCreatedByGroupID = $projectInfo["ownerGroupID"];
	
	if ($userID == $taskRequestedByUserID || $myRole === 'Admin' || ($myRole === 'Editor' && $groupID === $projectCreatedByGroupID) || $userID == $projectOwnerUserID) {
		
		//get members 
		//getting membership list 
	$getMembershipList = "SELECT * FROM `user` JOIN `Team Projects Member List` ON `user`.`userID`= `Team Projects Member List`.`userID` WHERE `ProjectID` = '$taskProjectID' AND `user`.`userID` != '$taskAssignedToUserID'";
	
	$getMembershipList_result = mysqli_query($connection, $getMembershipList) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMembershipList_result->fetch_assoc()) {
		$memberIDArray[] = $row["userID"];
		$memberID = $row["userID"];
		$memberFN = $row["First Name"];
		$memberPic = $row["PP Link"];
		$memberUsername = $row["username"];
		
		$members[] = "
		<div class='projectMember' memberid='$memberID' taskid='$taskID'>
		<img class='profilePicMembers' src='".$memberPic."'/>
		<p>$memberUsername</p>
		</div>";  // or $row if you want whole row;
	}
		
			$canReassignTask = '
			<div class="reassignTaskContainer">
			<p>Reassign Task To:</p>
				<div class="reassignTaskInnerDiv">
					
					<div class="reassignTaskTo">
					
					</div>
				</div>
				
			</div>';	
		
		$canEditTask = '<button type="button" class="genericbtn" id="editTaskBtn">Edit</button>
		  <button type="button" class="genericbtn greenbtn" id="editTaskModal-btn">Save</button>';
														
	}
	
	else if ($userID == $taskAssignedToUserID) {
		$canReassignTask = '';
		$members = '';
		$canEditTask = '<button type="button" class="genericbtn" id="editTaskBtn">Edit</button>
		  <button type="button" class="genericbtn greenbtn" id="editTaskModal-btn">Save</button>';
	}
	
	else {
		$canReassignTask = '';
		$members = '';
		$canEditTask = '';
	}
	
	
	
////////////
	
	$results = ["taskTitle" => $taskTitle, 
				"taskDescriptionDisplay" => $taskDesc, 
				"taskDescription" => $taskDesc, 
				"taskDueDateDisplay" => $taskDueDateDisplay, 
				"taskDueDate" => $taskDueDate, 
				"taskStatus" => $taskStatus, 
				"taskCategory" => $taskCategoryTitle, 
				"taskCategoryID" => $taskCategoryID, 
				"taskAssignedTo" => $taskAssignedToFullName, 
				"taskAssignedToID" => $taskAssignedToUserID, 
				"taskID" => $taskID, 
				"taskStatusNumber" => $taskStatusNumber, 
				"taskAssignedToPP" => $taskAssignedToPP, 
				"eventID" => $taskEventID, 
				"eventEndDate" => $taskEventEndDate, 
				"eventCategoryID" => $taskEventCategoryID, 
				"eventCategory" => $taskEventCategory, 
				"printComments" => $taskComments, 
				"canComment" => $taskCanComment,
				"canReassignTask" => $canReassignTask,
				"canEditTask" => $canEditTask,
				"members" => $members
			   ];
	

	header('Content-Type: application/json'); 
	echo json_encode($results);
}
if ($type=="save") {
	$taskID=$_POST['taskID'];
	$taskTitleEdit=addslashes($_POST['taskTitleEdit']);		
	$taskDueDateEdit = date("Y-m-d H:i:s",strtotime($_POST['taskDueDateEdit']));	
	$eventCategoryEdit = $_POST['eventCategoryEdit'];	
	$taskCategoryEdit = $_POST['taskCategoryEdit'];	
	$taskDescriptionEdit = addslashes($_POST['taskDescriptionEdit']);	
	$taskDescriptionNoInsert = $_POST['taskDescriptionEdit'];	
	$taskStatusEdit = $_POST['taskStatusEdit'];
	$taskMessageEdit = addslashes($_POST['taskMessageEdit']);	

	//getting all 
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
	//if the task has not been changed to a LAUNCH task - delete calendar event if it exists
	if ($taskCategoryEdit !== "7") {
		
		
		updateTask($taskID,$taskTitleEdit,$taskDescriptionEdit,$taskCategoryEdit, $taskDueDateEdit, $taskStatusEdit);
		
		//DOES EVENT EXIST?
		$getAllEvents = "SELECT `id` FROM `calendar` WHERE `TaskID`='$taskID'";
		$getAllEvents_result = mysqli_query($connection, $getAllEvents) or die(mysqli_error($connection));
		$eventCount = mysqli_num_rows($getAllEvents_result);
		
		if ($eventCount == 1) {
			deleteTaskCalendarEvent($taskID);
		}
	}
	else {
		$taskEndDateEdit = $_POST['taskEndDateEdit'];	
		
		updateTaskEndDate($taskID,$taskTitleEdit,$taskDescriptionEdit,$taskCategoryEdit, $taskDueDateEdit, $taskStatusEdit, $taskEndDateEdit);
		
		//DOES EVENT EXIST?
		$getAllEvents = "SELECT `id` FROM `calendar` WHERE `TaskID`='$taskID'";
		$getAllEvents_result = mysqli_query($connection, $getAllEvents) or die(mysqli_error($connection));
		$eventCount = mysqli_num_rows($getAllEvents_result);
		
		if ($eventCount == 1) {
			updateTaskCalendarEvent($taskID,$taskTitleEdit,$taskDescriptionEdit,$eventCategoryEdit, $taskDueDateEdit, $taskEndDateEdit);
		}
		else {
			createTaskCalendarEvent($taskID,$taskTitleEdit,$taskDescriptionEdit,$eventCategoryEdit, $taskDueDateEdit, $taskEndDateEdit, $taskProjectID);
		}
	
		
	}
	

	
	/////////// INSERTING NOTIFICATION ///////////

	if ($taskStatusEdit == "Completed") {
		completeTask($taskID);
	}
	else if ($taskStatusEdit == "In Review") {
		if (!isset($taskMessageEdit)) {
			$taskMessageFinal = "";
		}
		else {
			$taskMessageFinal = $taskMessageEdit;
		}
		inReviewTask($taskID,$taskMessageFinal);
	}
	else if ($taskStatusEdit == "Approved") {
	approveTask($taskID);
	}
	else {
		
	}
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
	
	//if project is completed
	if ($TaskTotalCount === $TaskCompletedCount) {
		
		projectIsCompleted($taskProjectID);	
		
	}
	//else if not completed
	else if ($TaskTotalCount !== $TaskCompletedCount)  {
		projectIsNotCompleted($taskProjectID);	
	
	}
	
	
	
	

 }
if ($type=="delete") {
	$taskID=$_POST['taskID'];
	
	deleteTask($taskID);
	
}
if ($type == "addComment") {
	$comment=$_POST['comment'];
	$taskID=$_POST['taskID'];
	
	addTaskComment($taskID,$comment);
	
	//getting comments
	$getTaskComments = getTaskComments($taskID);
	
	$taskComments = $getTaskComments["printComments"];
	$taskCanComment = $getTaskComments["canComment"];
	
	////////////
	
	$result = [
				"printComments" => $taskComments, 
				"canComment" => $taskCanComment
			   ];
	

	header('Content-Type: application/json'); 
	echo json_encode($result);
}
if ($type == "deleteComment") {
	$taskID=$_POST['taskID'];
	$commentID=$_POST['commentID'];
	
	
	deleteComment($commentID);
	
	//getting comments
	$getTaskComments = getTaskComments($taskID);
	
	$taskComments = $getTaskComments["printComments"];
	$taskCanComment = $getTaskComments["canComment"];
	
	////////////
	
	$result = [
				"printComments" => $taskComments, 
				"canComment" => $taskCanComment
			   ];
	

	header('Content-Type: application/json'); 
	echo json_encode($result);
	
	

}
if($type == 'approveTask'){
	$taskID = $_POST['taskID'];
	approveTask($taskID);

}
if($type == 'kickbackEvent'){
	$taskID = $_POST['taskID'];
	$message = addslashes($_POST['message']);
	
	kickbackTask($taskID, $message);
	
	
	
}

if($type == 'reassignTask'){
	$taskID = $_POST['taskID'];
	$memberID = $_POST['memberID'];
	
	reassignTask($taskID, $memberID);
	
	
	
}
?>