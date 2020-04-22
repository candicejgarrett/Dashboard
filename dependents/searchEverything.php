<?php

require( '../connect.php' );
require( '../header.php' );

///////// SEARCH EVERYTHING //////////
$searchTerm = $_POST[ 'searchTerm' ];

///// PROJECTS /////

$projectsQuery = "SELECT `ProjectID`, `Status`, `Title`, `Description`, `Category`, DATE_FORMAT(`Due Date`, '%b %e, %Y'), `Task Type`, `userID`, `Date Created`, `Date Completed`, `Visible`, `Project Folder Link`, `URL To Use`, `TicketID`, `Copy` FROM `Team Projects` WHERE `Title` LIKE '%$searchTerm%' ORDER BY `Due Date`";
$projectsQuery_result = mysqli_query( $connection, $projectsQuery )or die( "Query to get data from Team Project failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $projectsQuery_result ) ) {
  $ID = $row[ 'ProjectID' ];
  $title = $row[ 'Title' ];
  $dueDate = $row[ "DATE_FORMAT(`Due Date`, '%b %e, %Y')" ];
  $status = $row[ 'Status' ];
  $creatorID = $row[ 'userID' ];
  $visibility = $row[ 'Visible' ];

  $getCreatorByID = "SELECT * FROM `user` WHERE `userID` = '$creatorID'";
  $getCreatorByID_result = mysqli_query( $connection, $getCreatorByID )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row2 = mysqli_fetch_array( $getCreatorByID_result ) ) {
    $creatorName = $row2[ 'First Name' ] . " " . $row2[ 'Last Name' ];
  }
  if ( isset( $ID ) ) {
    $printProjectResults[] = "
		<div class='col-sm-2'>
			<a href='/dashboard/team-projects/view/?projectID=$ID'>
				<div class='searchResultItem'>
					<h3>$title</h3>
					<div class='createdBy'><i class='fa fa-user' aria-hidden='true'></i> <span>$creatorName</span></div>
					<div class='duedate'><i class='fa fa-calendar-o' aria-hidden='true'></i> <span>$dueDate</span></div>
					<div class='status'>$status</div>
				</div>
			</a>
		</div>
		";
  } else {
    $printProjectResults[] = "";
  }
}

///// CALENDAR /////

$calendarQuery = "SELECT `id`, `title`, DATE_FORMAT(`startdate`, '%b %e, %Y'), `enddate`, `Category`, `userID`, `Description`, `allDay`, `Preview Image Link`, `Preview Image Link Mobile`, `ProjectID`, `TaskID`, `dow` FROM `calendar` WHERE `title` LIKE '%$searchTerm%' ORDER BY `startdate`";
$calendarQuery_result = mysqli_query( $connection, $calendarQuery )or die( "Query to get data from Team Project failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $calendarQuery_result ) ) {
  $ID = $row[ 'id' ];
  $title = $row[ 'title' ];
  $dueDate = $row[ "DATE_FORMAT(`startdate`, '%b %e, %Y')" ];
  $creatorID = $row[ 'userID' ];
  $categoryID = $row[ 'Category' ];

  $getCreatorByID = "SELECT * FROM `user` WHERE `userID` = '$creatorID'";
  $getCreatorByID_result = mysqli_query( $connection, $getCreatorByID )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row2 = mysqli_fetch_array( $getCreatorByID_result ) ) {
    $creatorName = $row2[ 'First Name' ] . " " . $row2[ 'Last Name' ];
  }

  $getCategoryByID = "SELECT * FROM `Calendar Categories` WHERE `CalendarCategoryID` = '$categoryID'";
  $getCategoryByID_result = mysqli_query( $connection, $getCategoryByID )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row2 = mysqli_fetch_array( $getCategoryByID_result ) ) {
    $categoryName = $row2[ 'Category' ];
  }
  if ( isset( $ID ) ) {
    $printCalendarResults[] = "
		<div class='col-sm-2'>
			<a href='/dashboard/content-calendar/?eventID=$ID'>
				<div class='searchResultItem'>
					<h3>$title</h3>
					<div class='createdBy'><i class='fa fa-user' aria-hidden='true'></i> <span>$creatorName</span></div>
					<div class='duedate'><i class='fa fa-calendar-o' aria-hidden='true'></i> <span>$dueDate</span></div>
					
					<div class='status'><span>$categoryName</span></div>
				</div>
			</a>
		</div>
		";
  } else {
    $printCalendarResults[] = "";
  }
}

///// KNOWLEDGE CENTER /////

$kcQuery = "SELECT `PostID`, `userID`, DATE_FORMAT(`Date Created`, '%b %e, %Y'), `Post Title`, `Post Description`, `KC CategoryID`, `Post Image`, `Last Updated`, `Last Updated By` FROM `Knowledge Center` WHERE `Post Title` LIKE '%$searchTerm%' ORDER BY `Date Created`";
$kcQuery_result = mysqli_query( $connection, $kcQuery )or die( "Query to get data from Team Project failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $kcQuery_result ) ) {
  $ID = $row[ 'PostID' ];
  $title = $row[ 'Post Title' ];
  $dueDate = $row[ "DATE_FORMAT(`Date Created`, '%b %e, %Y')" ];
  $creatorID = $row[ 'userID' ];

  $getCreatorByID = "SELECT * FROM `user` WHERE `userID` = '$creatorID'";
  $getCreatorByID_result = mysqli_query( $connection, $getCreatorByID )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row2 = mysqli_fetch_array( $getCreatorByID_result ) ) {
    $creatorName = $row2[ 'First Name' ] . " " . $row2[ 'Last Name' ];
  }

  if ( isset( $ID ) ) {
    $printKCResults[] = "
		<div class='col-sm-2'>
			<a href='/dashboard/knowledge-center/post/?ID=$ID'>
				<div class='searchResultItem'>
					<h3>$title</h3>
					<div class='createdBy'><i class='fa fa-user' aria-hidden='true'></i> <span>$creatorName</span></div>
					<div class='duedate'><i class='fa fa-calendar-o' aria-hidden='true'></i> <span>$dueDate</span></div>
				</div>
			</a>
		</div>
		";
  } else {
    $printKCResults[] = "";
  }


}

///// USERS /////

$userQuery = "SELECT `userID`, `username`, `email`, `password`, `First Name`, `Last Name`, `Role`, `Title`, `PP Link`, `Member Status`, `Requested Group`, DATE_FORMAT(`Last Active`, '%b %e, %Y') FROM `user` WHERE (`First Name` LIKE '%$searchTerm%' OR `Last Name` LIKE '%$searchTerm%') AND `Member Status` = 'Active' ORDER BY `Last Active`";
$userQuery_result = mysqli_query( $connection, $userQuery )or die( "Query to get data from Team Project failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $userQuery_result ) ) {
  $ID = $row[ 'userID' ];
  $title = $row[ 'Title' ];
  $dueDate = $row[ "DATE_FORMAT(`Last Active`, '%b %e, %Y')" ];
  $creatorID = $row[ 'userID' ];
  $image = $row[ 'PP Link' ];
  $role = $row[ 'Role' ];
  $getCreatorByID = "SELECT * FROM `user` WHERE `userID` = '$creatorID'";
  $getCreatorByID_result = mysqli_query( $connection, $getCreatorByID )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row2 = mysqli_fetch_array( $getCreatorByID_result ) ) {
    $creatorName = $row2[ 'First Name' ] . " " . $row2[ 'Last Name' ];
  }

  if ( isset( $ID ) ) {
    $printUserResults[] = "
		<div class='col-sm-2'>
			<a href='/dashboard/users/profile/?userID=$ID'>
				<div class='searchResultItem'>
					<img src='$image'>
					<div class='createdBy'><i class='fa fa-user' aria-hidden='true'></i> <span>$creatorName</span></div>
					<div class='duedate'>Role: <span>$role</span></div>
				</div>
			</a>
		</div>
		";
  } else {
    $printUserResults[] = '';
  }


}


$results = [ "printProjectResults" => $printProjectResults,
  "printCalendarResults" => $printCalendarResults,
  "printKCResults" => $printKCResults,
  "printUserResults" => $printUserResults
];
header( 'Content-Type: application/json' );
echo json_encode( $results );

?>