<?php 
require('../header.php');
$searchTerm=addslashes($_POST['searchTerm']);
$searchTermPrintBack=$_POST['searchTerm'];
$status=$_POST['status'];
$team=$_POST['team'];
$category=$_POST['category'];
$sortBy = $_POST['sortBy'];

if (isset($searchTerm)) {
	$addSearchTerm="AND `Team Projects`.`Title` LIKE '%$searchTerm%'";
}
else {
	$addSearchTerm="";
}
if ($status != "All") {
	$addStatus="AND `Team Projects`.`Status` = '$status'";
}
else {
	$addStatus="";
}
if ($team != "All") {
	$addTeam="AND `Group Membership`.`GroupID` = '$team'";
}
else {
	$addTeam="";
}
if ($category != "All") {
	$addCategory="AND `Team Projects`.`Category` = '$category'";
}
else {
	$addCategory="";
}
if ($sortBy == "Date Created DESC") {
	$addSortBy = "ORDER BY `Team Projects`.`Date Created` DESC";
}
else if ($sortBy == "Date Created ASC") {
	$addSortBy = "ORDER BY `Team Projects`.`Date Created` ASC";
}
else if ($sortBy == "Due Date DESC") {
	$addSortBy = "ORDER BY `Team Projects`.`Due Date` DESC";
}
else if ($sortBy == "Due Date ASC") {
	$addSortBy = "ORDER BY `Team Projects`.`Due Date` ASC";
}
else if ($sortBy == "AtoZ") {
	$addSortBy = "ORDER BY `Team Projects`.`Title` ASC";
}
else if ($sortBy == "ZtoA") {
	$addSortBy = "ORDER BY `Team Projects`.`Title` DESC";
}
else {
	$addSortBy = "ORDER BY `Team Projects`.`Date Created` DESC";
}

$getAllProjects = "SELECT DISTINCT `Team Projects`.`ProjectID`, `Team Projects`.`Status`, `Team Projects`.`Title`, `Team Projects`.`Description`, `Team Projects Categories`.`Category`,`Team Projects`.`Due Date`, DATE_FORMAT(`Due Date`, '%b %e, %Y'), `Team Projects`.`userID`, `Date Created`, `Visible`, `Group Membership`.`GroupID` 
FROM `Team Projects` 
JOIN `Team Projects Categories` ON `Team Projects Categories`.`ProjectCategoryID`=`Team Projects`.`Category` 
JOIN `Team Projects Member List` ON `Team Projects Member List`.`ProjectID`=`Team Projects`.`ProjectID` 
JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID` WHERE (`Team Projects`.`Visible`='Public' OR `Team Projects Member List`.`userID`='$userID') $addSearchTerm $addStatus $addTeam $addCategory $addSortBy";
$getAllProjects_result = mysqli_query($connection, $getAllProjects) or die ("Query to get data from Team Project failed: ".mysql_error());

while ($row = mysqli_fetch_array($getAllProjects_result)) {
											$projectID = $row['ProjectID'];
											$projectTitle = $row['Title'];
											$projectDueDate = $row["DATE_FORMAT(`Due Date`, '%b %e, %Y')"];
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
											
											
											$getAllReviews = "SELECT COUNT(*) FROM `Tickets Review` WHERE `ProjectID` = '$projectID'";
											$getAllReviews_result = mysqli_query($connection, $getAllReviews) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getAllReviews_result)) {
												$projectReviewsCount = $row2['COUNT(*)'];
											}
											
											
											$getAllMembers = "SELECT COUNT(*) FROM `Team Projects Member List` WHERE `ProjectID` = '$projectID'";
											$getAllMembers_result = mysqli_query($connection, $getAllMembers) or die ("Query to get data from Team Project failed: ".mysql_error());
											
											while ($row2 = mysqli_fetch_array($getAllMembers_result)) {
												$projectMembersCount = $row2['COUNT(*)'];
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
																	if ($dayCount >= 2 || $dayCount == 0) {
																		$days="s";
																	}
																	else if ($dayCount = 1) {
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
											
											$printBackProjects[]= "<div class='col-sm-4'><div class='project $archived fadeInUp'><div class='heading'><div class='pull-right'>$projectDaysLeft</div><h4>$projectCategory</h4><h1>$projectTitle</h1><h5><strong><i class='fa fa-user' aria-hidden='true'></i></strong> <span class='getprojectCreatorFN'>$getprojectCreatorFN</span><br><br>$private
											<div class='projectDueDate1'><strong><i class='fa fa-clock-o' aria-hidden='true'></i></strong> <span class='projectDueDate'>$projectDueDate</span></div></h5></div>
											
											<p><strong>Last edited by:</strong> $projectLastEdited<br><span style='font-size:12px;'>".$projectLastEditedDaysAgoCount."d ago</span></p>
											
											<center><a class='project-btn' id='$projectID' href='view/?projectID=$projectID'>Open</a></center>
											
											<div class='information'><div class='row'><div class='col-sm-4 text-center' style='border-right:1px solid #ffffff'><h1><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp; $projectTaskCount</h1></div><div class='col-sm-4 text-center' style='border-right:1px solid #ffffff'><h1><i class='fa fa-check-circle' aria-hidden='true'></i>&nbsp; $projectReviewsCount</h1></div><div class='col-sm-4 text-center'><h1><i class='fa fa-users' aria-hidden='true'></i>&nbsp; $projectMembersCount</h1></div></div></div>
											</div>
											</div>";
										}
	
	$results = ["printBackProjects" => $printBackProjects,
			   "searchTerm" => $searchTermPrintBack,
			   "status" => $status,
			   "team" => $team,
			   "category" => $category,
			   "sortBy" => $sortBy];
	header('Content-Type: application/json'); 
	echo json_encode($results);
?>