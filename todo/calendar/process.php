<?php
include_once( '../../header.php' );
require( '../../connect.php' );
$type = $_POST[ 'type' ];

if ( $type == 'viewEvent' ) {
  $eventid = $_POST[ 'eventid' ];
  $viewEvent = mysqli_query( $connection, "SELECT DISTINCT `TaskID`,`Tasks`.`Title`,`Tasks`.`Description`,`Tasks`.`Status`,`Requested By`,`Tasks`.`ProjectID`,`Tasks`.`userID`,`First Name`,`allDay`, DATE_FORMAT(`Tasks`.`Due Date`, '%b. %d %Y @ %h:%i%p'),DATE_FORMAT(`Tasks`.`Due Date`, '%Y-%m-%dT%T') AS 'Standard Start Date',DATE_FORMAT(`Tasks`.`Due Date`, '%Y-%m-%d') AS 'Jump To Date',`Task Categories`.`Category`,`Team Projects`.`Title` AS 'Project Title' FROM `Tasks` JOIN `user` on `Tasks`.`userID` JOIN `Team Projects` on `Tasks`.`ProjectID` = `Team Projects`.`ProjectID` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` WHERE `TaskID`='$eventid' and `Tasks`.`userID`=`user`.`userID`" );

  while ( $row = $viewEvent->fetch_assoc() ) {
    $printEventID = $row[ "TaskID" ];
    $printEventProjectID = $row[ "ProjectID" ];
    $printEventProjectTitle = $row[ "Project Title" ];
    $printEventTitle = $row[ "Title" ];
    $printEventStartDate = $row[ "DATE_FORMAT(`Tasks`.`Due Date`, '%b. %d %Y @ %h:%i%p')" ];
    $printEventStartDateStandard = $row[ "Standard Start Date" ];
    $printEventJumpToDate = $row[ "Jump To Date" ];
    $printEventDescription = $row[ "Description" ];
    $printEventCategory = $row[ "Category" ];
    $printEventAllDay = $row[ "allDay" ];
    $printEventStatus = $row[ "Status" ];
    $printEventRequestedBy = $row[ "Requested By" ];
    $printEventProjectLink = "/dashboard/team-projects/view/?projectID=$printEventProjectID";
    $getFirstName = mysqli_query( $connection, "SELECT * FROM `user` WHERE `userID`='$printEventRequestedBy'" );
    while ( $row2 = $getFirstName->fetch_assoc() ) {
      $printEventCreatedBy = $row2[ "First Name" ] . ' ' . $row2[ "Last Name" ];
      $printEventCreator = $row2[ "PP Link" ];
    }
  }
  //getting comments
  $getComments = "SELECT `CommentID`,`Message`, DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'),`userID`, `userID`, `ProjectID`, `TaskID`, `Sent By` FROM `Task Comments` WHERE `TaskID` = '$eventid' ORDER BY `Timestamp` ASC";

  $getComments_result = mysqli_query( $connection, $getComments )or die( "getComments to get data from Team Project failed: " . mysql_error() );

  while ( $row5 = $getComments_result->fetch_assoc() ) {
    $whoSentCom = $row5[ "Sent By" ];
    $ProjectIDCom = $row5[ "ProjectID" ];
    $TimestampCom = $row5[ "DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')" ];
    $MessageCom = $row5[ "Message" ];
    $MessageIDCom = $row5[ "CommentID" ];
    $getWhoSentCom = "SELECT * FROM `user` WHERE `userID` = '$whoSentCom'";

    $getWhoSentCom_result = mysqli_query( $connection, $getWhoSentCom )or die( "getTasks_result to get data from Team Project failed: " . mysql_error() );

    while ( $row6 = $getWhoSentCom_result->fetch_assoc() ) {
      $WhoSentFNCom = $row6[ "username" ];
      $ppLink = $row6[ "PP Link" ];
    }

    if ( $whoSentCom != $userID ) {
      $messageCSS = "incomingCom";
      $printMessagesCom[] = "<table class='comments $messageCSS' id='$MessageIDCom'><tr><td style='border:0px !important;' class='sender'><img class='commentsImage' src='$ppLink'></td><td style='border:0px !important;width: 100% !important;'><span>@$WhoSentFNCom</span><div class='timestamp'>$TimestampCom</div></td></tr><tr><td colspan='2'><pre class='message'>$MessageCom</pre><div class='removeNoteContainer'><div class='removeNote' commentid='$MessageIDCom' taskid='$eventid'><br><i class='fa fa-trash' aria-hidden='true'></i></div></div></td></tr></table>";
    } else {
      $messageCSS = "outgoingCom";
      $printMessagesCom[] = "<table class='comments $messageCSS' id='$MessageIDCom'><tr><td style='border:0px !important;width: 100% !important;'><span>@$WhoSentFNCom</span><div class='timestamp'>$TimestampCom</div></td><td style='border:0px !important;' class='sender'><img class='commentsImage' src='$ppLink'></td></tr><tr><td colspan='2'><pre class='message'>$MessageCom</pre><div class='removeNoteContainer'><div class='removeNote' commentid='$MessageIDCom' taskid='$eventid'><br><i class='fa fa-trash' aria-hidden='true'></i></div></div></td></tr></table>";
    }


  }

  ////////////

  if ( isset( $printEventID ) ) {
    $status = 'success';
  } else {
    $status = 'failed';
  }

  $response = [ "status" => $status,
    "printEventID" => $printEventID,
    "printEventTitle" => $printEventTitle,
    "printEventStartDate" => $printEventStartDate,
    "printEventStartDateStandard" => $printEventStartDateStandard,
    "printEventCategory" => $printEventCategory,
    "printEventDescription" => $printEventDescription,
    "printEventCreatedBy" => $printEventCreatedBy,
    "printEventAllDay" => $printEventAllDay,
    "printEventStatus" => $printEventStatus,
    "printEventProjectLink" => $printEventProjectLink,
    "printEventProjectTitle" => $printEventProjectTitle,
    "printComments" => $printMessagesCom,
    "printEventJumpToDate" => $printEventJumpToDate,
    "printEventCreator" => $printEventCreator
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $response );

}

if ( $type == 'viewReview' ) {
  $eventid = $_POST[ 'eventid' ];
  $viewEvent = mysqli_query( $connection, "SELECT `Tickets Review`.`ReviewID`, `Tickets Review`.`userID` AS 'Review Owner', `Tickets Review`.`ProjectID`, `Tickets Review`.`Title` AS 'Review Title',`Team Projects`.`Title` AS 'Project Title', `Tickets Review`.`Type`, `Tickets Review`.`Date Created`,DATE_FORMAT(`Tickets Review`.`Due Date`, '%b. %d %Y @ %h:%i%p') AS 'Formatted Date',`Tickets Review`.`Due Date` AS 'Standard Due Date', DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y') AS 'Jump To Date', `Tickets Review`.`Status` AS 'Review Status', `Desktop Preview Image Link`, `Mobile Preview Image Link`, `Tickets Review Members`.`Status` AS 'userStatus' FROM `Tickets Review` JOIN `Tickets Review Members` on `Tickets Review`.`ReviewID` = `Tickets Review Members`.`ReviewID` JOIN `Team Projects` on `Tickets Review`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `Tickets Review`.`ReviewID` = '$eventid'" );

  while ( $row = $viewEvent->fetch_assoc() ) {
    $printEventID = $eventid;
    $printEventProjectID = $row[ "ProjectID" ];
    $printEventProjectTitle = $row[ "Project Title" ];
    $printEventTitle = $row[ "Review Title" ];
    $printEventStartDate = $row[ "Formatted Date" ];
    $printEventStartDateStandard = $row[ "Standard Start Date" ];
    $printEventJumpToDate = $row[ "Jump To Date" ];
    $printEventCategory = $row[ "Type" ];
    $printEventStatus = $row[ "Review Status" ];
    $printEventOwner = $row[ "Review Owner" ];
    $printEventProjectLink = "/dashboard/team-projects/view/?projectID=$printEventProjectID";
    $getFirstName = mysqli_query( $connection, "SELECT * FROM `user` WHERE `userID`='$printEventOwner'" );
    while ( $row2 = $getFirstName->fetch_assoc() ) {
      $printEventCreatedBy = $row2[ "First Name" ] . ' ' . $row2[ "Last Name" ];
      $printEventCreator = $row2[ "PP Link" ];
    }
  }


  //getting membership list 
  $getMembershipList = "SELECT `user`.`userID`,`PP Link`,`username`,`First Name`,`Status` FROM `Tickets Review Members` JOIN `user` ON `user`.`userID` = `Tickets Review Members`.`userID` WHERE `ReviewID` = '$eventid' ORDER BY `MemberID` ASC";

  $getMembershipList_result = mysqli_query( $connection, $getMembershipList )or die( "NEWWWW Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getMembershipList_result->fetch_assoc() ) {
    $memberIDArray[] = $row[ "userID" ];
    $memberUserID = $row[ "userID" ];
    $memberFN = $row[ "First Name" ];
    $memberPic = $row[ "PP Link" ];
    $memberStatus = $row[ "Status" ];
    $memberUsername = $row[ "username" ];
    $memberApproval = '<div class="revNotApproved" style="color:#707070"><i class="fa fa-question-circle" aria-hidden="true"></i></div>';
    if ( $memberStatus == "Approved" ) {
      $memberApproval = '<div class="revApproved"><i class="fa fa-check-circle" aria-hidden="true"></i></div>';
    } else {
      $memberApproval = '<div class="revNotApproved" style="color:#707070"><i class="fa fa-question-circle" aria-hidden="true"></i></div>';
    }


    $printMembers[] = "<div class='reviewers'><div class='revNotApproved deleteReviewer' userid='$memberUserID' style='margin-left:51px;display:none;cursor:pointer'><i class='fa fa-minus-circle' aria-hidden='true'></i></div>$memberApproval<img src='" . $memberPic . "'/><p>@" . $memberUsername . "</p></div>";


  }

  ////////////

  if ( isset( $printEventID ) ) {
    $status = 'success';
  } else {
    $status = 'failed';
  }

  $response = [ "status" => $status,
    "printEventID" => $printEventID,
    "printEventTitle" => $printEventTitle,
    "printEventStartDate" => $printEventStartDate,
    "printEventStartDateStandard" => $printEventStartDateStandard,
    "printEventCategory" => $printEventCategory,
    "printEventCreatedBy" => $printEventCreatedBy,
    "printEventStatus" => $printEventStatus,
    "printEventProjectLink" => $printEventProjectLink,
    "printEventProjectTitle" => $printEventProjectTitle,
    "printEventJumpToDate" => $printEventJumpToDate,
    "printEventCreator" => $printEventCreator,
    "printEventMembers" => $printMembers
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $response );

}

if ( $type == 'resetdate' ) {
  $title = addslashes( $_POST[ 'title' ] );
  $startdate = $_POST[ 'start' ];
  $enddate = $_POST[ 'end' ];
  $eventid = $_POST[ 'eventid' ];
  $update = mysqli_query( $connection, "UPDATE `Tasks` SET `Due Date` = '$startdate' where `TaskID`='$eventid'" );

  /////////// INSERTING ACTIVITY /////////////
  //getting project id
  $getUpdatedProjectID = "SELECT * FROM `Tasks` WHERE `TaskID` = '$eventid'";
  $getUpdatedProjectID_result = mysqli_query( $connection, $getUpdatedProjectID )or die( "getNotes_result to get data from Team Project failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $getUpdatedProjectID_result ) ) {
    $updatedProjectID = $row[ "ProjectID" ];
  }
  $activity = "updated the date for the task: <strong>$title</strong>.";
  $addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Task','$userID','$updatedProjectID')";
  $addActivity_result = mysqli_query( $connection, $addActivity )or die( "activity failed: " . mysql_error() );

  if ( $update )
    echo json_encode( array( 'status' => 'success' ) );
  else
    echo json_encode( array( 'status' => 'failed' ) );

  //DOES EVENT EXIST?
  $getAllEvents = "SELECT `id` FROM `calendar` WHERE `TaskID`='$eventid'";
  $getAllEvents_result = mysqli_query( $connection, $getAllEvents )or die( mysqli_error( $connection ) );
  $eventCount = mysqli_num_rows( $getAllEvents_result );

  if ( $eventCount == 1 ) {
    $updateEvent = "UPDATE `calendar` SET `startdate`='$startdate',`enddate`='$startdate' WHERE `TaskID` = '$eventid'";
    $updateEvent_result = mysqli_query( $connection, $updateEvent )or die( "Query to get data from Team Project failed: " . mysql_error() );
  } else {

  }


}

if ( $type == 'resetReviewDate' ) {

  $startdate = $_POST[ 'start' ];
  $eventid = $_POST[ 'eventid' ];
  $update = mysqli_query( $connection, "UPDATE `Tickets Review` SET `Due Date` = '$startdate' where `ReviewID`='$eventid'" );

  /////////// INSERTING ACTIVITY /////////////
  //getting project id
  $getUpdatedProjectID = "SELECT * FROM `Tasks` WHERE `TaskID` = '$eventid'";
  $getUpdatedProjectID_result = mysqli_query( $connection, $getUpdatedProjectID )or die( "getNotes_result to get data from Team Project failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $getUpdatedProjectID_result ) ) {
    $updatedProjectID = $row[ "ProjectID" ];
  }

  if ( $update )
    echo json_encode( array( 'status' => 'success' ) );
  else
    echo json_encode( array( 'status' => 'failed' ) );

}


?>