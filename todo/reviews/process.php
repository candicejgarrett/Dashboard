<?php
include_once( '../../header.php' );
require( '../../connect.php' );
require( '../../emailDependents.php' );
include( '../../functions/global.php' );

$type = $_POST[ 'type' ];
$reviewID = $_POST[ 'reviewID' ];

function returnResults() {

  global $connection;
  global $userID;
  global $todaysDate;

  $query = "SELECT `Tickets Review`.`ReviewID`, `Tickets Review`.`userID` AS 'Review Owner', `Tickets Review`.`ProjectID`, `Tickets Review`.`Title` AS 'Review Title',`Team Projects`.`Title` AS 'Project Title', `Tickets Review`.`Type`, `Tickets Review`.`Date Created`, DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y'), `Tickets Review`.`Status`, `Desktop Preview Image Link`, `Mobile Preview Image Link`, `Tickets Review Members`.`Status` AS 'userStatus' FROM `Tickets Review` JOIN `Tickets Review Members` on `Tickets Review`.`ReviewID` = `Tickets Review Members`.`ReviewID` JOIN `Team Projects` on `Tickets Review`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `Tickets Review Members`.`userID` = '$userID' AND (`Tickets Review`.`Status` = 'Not Approved' OR `Tickets Review`.`Status` IS NULL)";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $reviewID = $row[ 'ReviewID' ];
    $reviewTitle = $row[ 'Review Title' ];
    $reviewProjectTitle = $row[ 'Project Title' ];
    $reviewDueDate = $row[ "DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y')" ];
    $reviewCategory = $row[ 'Type' ];
    $reviewStatus = $row[ 'Status' ];
    $reviewCreatorID = $row[ 'Review Owner' ];
    $reviewProjectID = $row[ 'ProjectID' ];
    $individualStatus = $row[ 'userStatus' ];

    if ( $individualStatus == "" || $individualStatus == null || $individualStatus == "Not Approved" ) {
      $individualStatusText = "<span style='display:block'>Pending your approval.</span>";
    } else {
      $individualStatusText = "<span style='display:block'>Pending approval from other members.</span>";
    }

    //getting timeline status
    $timelineStatus = getReviewTimelineStatus( $reviewID );

    $printBack[] = '<tr reviewid="' . $reviewID . '" projectid="' . $reviewProjectID . '">
				<td>' . $reviewTitle . ' ' . $timelineStatus . '</td>
				<td>' . $reviewProjectTitle . '</h2>
				<td>' . $reviewCategory . '</td>
				<td>' . $reviewDueDate . '</td>
				<td><div class="taskStatus ' . $reviewStatus . '">' . $reviewStatus . '</div>' . $individualStatusText . '</td>
				<td><input type="checkbox" reviewid="' . $reviewID . '" value="' . $reviewID . '"></td>
				
			  </tr>';


  }

  return $printBack;
}

if ( $type == 'load' ) {
  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'approve' ) {
  userApprovedReview( $reviewID );

  /////////
  $printBack = returnResults();
  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'approveMultiple' ) {
  $reviewIDs = explode( ",", $_POST[ 'reviewIDs' ] );

  if ( is_array( $reviewIDs ) ) {
    foreach ( $reviewIDs as $reviewID ) {
      userApprovedReview( $reviewID );
    }

  }


  /////////
  $printBack = returnResults();
  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}


?>