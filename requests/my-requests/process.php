<?php
require( '../../connect.php' );
require( '../../header.php' );
require( '../../emailDependents.php' );
include( '../../functions/global.php' );

$type = $_POST[ 'type' ];

$ticketID = $_POST[ 'ticketID' ];

if ( $type == "getAll" ) {

  $getRequests = "SELECT * FROM `Tickets` WHERE `Requested By`='$userID' ORDER BY `Timestamp` DESC";
  $getRequests_result = mysqli_query( $connection, $getRequests )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $getRequests_result ) ) {
    $ticketID = $row[ "TicketID" ];
    $ticketTitle = $row[ "Title" ];
    $ticketDescription = $row[ "Description" ];
    $ticketURL = $row[ "URL" ];
    $ticketTimestamp = $row[ "Timestamp" ];
    $ticketDueDate = $row[ "Due Date" ];
    $ticketContactName = $row[ "Contact Name" ];
    $ticketContactEmail = $row[ "Contact Email" ];
    $ticketStatus = $row[ "Status" ];
    $ticketProjectID = $row[ "ProjectID" ];
    $printDate = date( "m/d/Y", strtotime( $ticketDueDate ) );
    $printTimestamp = date( "m/d/Y", strtotime( $ticketTimestamp ) );

    $printTickets[] = "<div class='individualTickets' id='$ticketID'><p class='pull-right ticketStatus $ticketStatus'>$ticketStatus</p><p>Ticket ID: <strong class='ticketID'>$ticketID</strong></p><br><h4>$ticketTitle</h4><p>Submitted: <strong>$printTimestamp</strong><p>Due: <strong>$printDate</strong></div>";
  }

  ////////////

  $results = [ "printTickets" => $printTickets ];

  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}
if ( $type == "load" ) {

  //getting all 
  $getAll = "SELECT `Tickets`.`TicketID`, `Tickets`.`Title`, `Description`, `Copy`, `URL`, `Tickets`.`Timestamp`, DATE_FORMAT(`Tickets`.`Due Date`,'%Y-%m-%dT%H:%i:%s') AS 'Due Date', `Requested By`, `Owner`, `Tickets`.`Status`, `ProjectID`, `Team Projects Categories`.`Category`,`Team Projects Categories`.`ProjectCategoryID`, `First Name`, `Last Name`, `PP Link` FROM `Tickets` JOIN `Team Projects Categories` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` JOIN `user` ON `Tickets`.`Owner` = `user`.`userID` WHERE `Requested By`='$userID' AND `Tickets`.`TicketID` = '$ticketID'";
  $getAll_result = mysqli_query( $connection, $getAll )or die( "Query to get data from Team Project failed: " . mysql_error() );
  while ( $row = $getAll_result->fetch_assoc() ) {
    $TicketTitle = $row[ "Title" ];
    $Description = $row[ "Description" ];
    $DueDate1 = $row[ "Due Date" ];
    $DueDate = date( "m/d/Y @ h:ia", strtotime( $DueDate1 ) );
    $Status = $row[ "Status" ];
    $Category = $row[ "Category" ];
    $CategoryID = $row[ "ProjectCategoryID" ];
    $URL = $row[ "URL" ];
    $ContactUserID = $row[ "Owner" ];
    $ContactName = $row[ "First Name" ] . " " . $row[ "Last Name" ];
    $Timestamp = $row[ "Timestamp" ];
    $ProjectID = $row[ "ProjectID" ];
    $Copy = $row[ "Copy" ];
    $ContactPP = $row[ "PP Link" ];

  }
  if ( isset( $ProjectID ) ) {
    $actions = '<a href="/dashboard/team-projects/view/?projectID=' . $ProjectID . '" class="smallIcon grey"><i class="fa fa-eye" aria-hidden="true"></i></a><div id="deleteRequest" class="smallIcon red"><i class="fa fa-trash" aria-hidden="true"></i></div>';
  } else {
    $actions = '<div id="editRequest" class="smallIcon grey"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div><div id="deleteRequest" class="smallIcon red"><i class="fa fa-trash" aria-hidden="true"></i></div>';
  }

  //getting comments
  $getComments = "SELECT `CommentID`, `Tickets Comments`.`Timestamp`, `Message`, `Tickets Comments`.`TicketID`, `Sent By`, `username`, `PP Link`, `Requested By` FROM `Tickets Comments` JOIN `Tickets` ON `Tickets Comments`.`TicketID` = `Tickets`.`TicketID` JOIN `user` ON `Tickets Comments`.`Sent By` = `user`.`userID` WHERE `Requested By`='$userID' AND `Tickets Comments`.`TicketID` = '$ticketID' ORDER BY `Timestamp` DESC";
  $getComments_result = mysqli_query( $connection, $getComments )or die( "Query to get data from Team Project failed: " . mysql_error() );
  while ( $row = $getComments_result->fetch_assoc() ) {
    $commentUserID = $row[ "Sent By" ];
    $commentID = $row[ "CommentID" ];
    $comment = $row[ "Message" ];
    $fullName = $row[ "username" ];
    $timestamp = date( ' g:i A M d, Y', strtotime( $row[ "Timestamp" ] ) );
    $pp = $row[ "PP Link" ];

    if ( $commentUserID === $userID || $myRole === 'Admin' || $myRole === 'Editor' ) {
      $canDelete = '<div class="deleteComment" commentid="' . $commentID . '"><i class="fa fa-times" aria-hidden="true"></i></div>';
    } else {

    }


    $comments[] = '
		<div class="commentContainer row">
			<div class="col-sm-12">
				<div class="pp" style="float:left;margin-right:10px;">
						<a href="/dashboard/users/profile/?userID=' . $commentUserID . '"><img src="' . $pp . '">
						</a>
					</div>
					' . $canDelete . '
					<div class="name">@' . $fullName . '</div>
					<div class="timestamp">' . $timestamp . '</div>
					<div class="comment">' . $comment . '</div>
					<hr>
				</div>
			
		</div>
		';

  }


  ////////////

  $results = [ "requestTitle" => $TicketTitle, "requestDescription" => $Description, "requestDueDateEdit" => $DueDate1, "requestDueDate" => $DueDate, "requestStatus" => $Status, "requestURL" => $URL, "requestContactName" => $ContactName, "requestContactPP" => $ContactPP, "requestContactUserID" => $ContactUserID, "requestTimestamp" => $Timestamp, "requestCategory" => $Category, "requestCategoryID" => $CategoryID, "requestCopy" => $Copy, "ticketID" => $ticketID, "actions" => $actions, "comments" => $comments ];

  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}
if ( $type == "addComment" ) {
  $comment = $_POST[ 'comment' ];
  addTicketComment( $ticketID, $comment );
}
if ( $type == "deleteComment" ) {
  $commentID = $_POST[ 'commentID' ];
  deleteTicketComment( $commentID );
}
if ( $type == "save" ) {

  $title = $_POST[ 'requestTitle' ];
  $url = $_POST[ 'requestURL' ];
  $description = $_POST[ 'requestDescription' ];
  $copy = $_POST[ 'requestCopy' ];
  $dueDate = date( "Y-m-d H:i:s", strtotime( $_POST[ 'requestDueDate' ] ) );
  $status = $_POST[ 'requestStatus' ];
  $categoryID = $_POST[ 'requestCategory' ];

  updateTicket( $ticketID, $title, $url, $description, $copy, $dueDate, $status, $categoryID );

}
if ( $type == "delete" ) {
  deleteTicket( $ticketID );
}


?>