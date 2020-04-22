<?php
include_once( '../../header.php' );
require( '../../connect.php' );
include( '../../functions/global.php' );
$type = $_POST[ 'type' ];
$reviewID = $_POST[ 'reviewID' ];

function returnResults() {

  global $connection;
  global $userID;

  $query = "SELECT DISTINCT `Tickets Review`.`ReviewID`, `Tickets Review`.`userID` AS 'Review Owner', `Tickets Review`.`ProjectID`, `Tickets Review`.`Title`, `Type`, DATE_FORMAT(`Tickets Review`.`Date Created`, '%m/%d/%y'), DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y'), `Tickets Review`.`Status`, `Desktop Preview Image Link`, `Mobile Preview Image Link`, `First Name`, `Last Name`, `Team Projects`.`Title` AS 'Project Title'
FROM `Tickets Review` 
JOIN `Tickets Review Members` on `Tickets Review`.`ReviewID` = `Tickets Review Members`.`ReviewID`
JOIN `user` on `Tickets Review`.`userID` = `user`.`userID`
JOIN `Team Projects` on `Tickets Review`.`ProjectID` = `Team Projects`.`ProjectID`";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $reviewID = $row[ 'ReviewID' ];
    $reviewTitle = $row[ 'Title' ];
    $reviewProjectTitle = $row[ 'Project Title' ];
    $reviewDueDate = $row[ "DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y')" ];
    $reviewCreatedDate = $row[ "DATE_FORMAT(`Tickets Review`.`Date Created`, '%m/%d/%y')" ];
    $reviewCategory = $row[ 'Type' ];
    $reviewStatus = $row[ 'Status' ];
    $reviewOwner = $row[ 'First Name' ] . " " . $row[ 'Last Name' ];

    if ( isset( $row[ 'Desktop Preview Image Link' ] ) && $row[ 'Desktop Preview Image Link' ] !== null && $row[ 'Desktop Preview Image Link' ] !== '' ) {
      $reviewDesktopMockup = '<img src="/dashboard/team-projects/view/review/' . $row[ 'Desktop Preview Image Link' ] . '" class="previewImage">';
    } else {
      $reviewDesktopMockup = '';
    }

    if ( isset( $row[ 'Mobile Preview Image Link' ] ) && $row[ 'Mobile Preview Image Link' ] !== null && $row[ 'Mobile Preview Image Link' ] !== '' ) {
      $reviewMobileMockup = '<img src="/dashboard/team-projects/view/review/' . $row[ 'Mobile Preview Image Link' ] . '" class="previewImage">';
    } else {
      $reviewMobileMockup = '';
    }

    $printBack[] = '<tr reviewID="' . $reviewID . '">
				<td>' . $reviewTitle . '</td>
				<td>' . $reviewProjectTitle . '</td>
				<td>' . $reviewCategory . '</td>
				<td>' . $reviewCreatedDate . '</td>
				<td>' . $reviewDueDate . '</td>
				<td>' . $reviewOwner . '</td>
				<td><div class="taskStatus ' . $reviewStatus . '">' . $reviewStatus . '</div></td>
				
				<td><input type="checkbox" reviewID="' . $reviewID . '" value="' . $reviewID . '"></td>
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

if ( $type == 'markApproved' ) {
  $updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Approved' WHERE `ReviewID` = '$reviewID'";
  $updateReviewStatus_result = mysqli_query( $connection, $updateReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );

  $updateUsersReviewStatus = "UPDATE `Tickets Review Members` SET `Status`='Approved' WHERE `ReviewID` = '$reviewID'";
  $updateUsersReviewStatus_result = mysqli_query( $connection, $updateUsersReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "approveMultiple" ) {

  $reviewIDs = explode( ",", $_POST[ 'reviewIDs' ] );


  if ( is_array( $reviewIDs ) ) {
    foreach ( $reviewIDs as $reviewID ) {
      $updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Approved' WHERE `ReviewID` = '$reviewID'";
      $updateReviewStatus_result = mysqli_query( $connection, $updateReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );

      $updateUsersReviewStatus = "UPDATE `Tickets Review Members` SET `Status`='Approved' WHERE `ReviewID` = '$reviewID'";
      $updateUsersReviewStatus_result = mysqli_query( $connection, $updateUsersReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );

    }

  } else {

  }

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );

}

if ( $type == 'markNotApproved' ) {
  $updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Not Approved' WHERE `ReviewID` = '$reviewID'";
  $updateReviewStatus_result = mysqli_query( $connection, $updateReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "markNotApprovedMultiple" ) {

  $reviewIDs = explode( ",", $_POST[ 'reviewIDs' ] );

  if ( is_array( $reviewIDs ) ) {
    foreach ( $reviewIDs as $reviewID ) {
      $updateReviewStatus = "UPDATE `Tickets Review` SET `Status`='Not Approved' WHERE `ReviewID` = '$reviewID'";
      $updateReviewStatus_result = mysqli_query( $connection, $updateReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );
    }

  } else {

  }

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );

}

if ( $type == 'changeType' ) {
  $newVal = $_POST[ 'newVal' ];

  $updateReviewStatus = "UPDATE `Tickets Review` SET `Type`='$newVal' WHERE `ReviewID` = '$reviewID'";
  $updateReviewStatus_result = mysqli_query( $connection, $updateReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'changeTitle' ) {
  $newVal = addslashes( $_POST[ 'newVal' ] );

  $updateReviewStatus = "UPDATE `Tickets Review` SET `Title`='$newVal' WHERE `ReviewID` = '$reviewID'";
  $updateReviewStatus_result = mysqli_query( $connection, $updateReviewStatus )or die( "Query to get data from Team task failed: " . mysql_error() );


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'delete' ) {

  deleteReview( $reviewID );

  ////////////////////////

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "deleteMultiple" ) {

  $reviewIDs = explode( ",", $_POST[ 'reviewIDs' ] );

  if ( is_array( $reviewIDs ) ) {
    foreach ( $reviewIDs as $reviewID ) {
      deleteReview( $reviewID );
    }

  } else {

  }

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );

}

?>