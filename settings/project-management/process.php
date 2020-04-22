<?php
include_once( '../../header.php' );
require( '../../connect.php' );
include( '../../functions/global.php' );
$type = $_POST[ 'type' ];
$projectID = $_POST[ 'projectID' ];


function returnResults() {

  global $connection;
  global $userID;

  $query = "SELECT DISTINCT `Team Projects`.`ProjectID`, `Team Projects`.`Status`, `Team Projects`.`Title`, `Team Projects`.`Description`, `Team Projects Categories`.`Category`, DATE_FORMAT(`Due Date`, '%m/%d/%y'), `Team Projects`.`userID`, DATE_FORMAT(`Date Created`, '%m/%d/%y'),`Date Created`, `Visible`,`First Name`,`Last Name` FROM `Team Projects` LEFT JOIN `Team Projects Categories` ON `Team Projects Categories`.`ProjectCategoryID`=`Team Projects`.`Category` JOIN `user` ON `Team Projects`.`userID`=`user`.`userID` ORDER BY `Date Created` DESC";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $printCreatedDate = $row[ "DATE_FORMAT(`Date Created`, '%m/%d/%y')" ];
    $printTitle = $row[ "Title" ];
    $printStatus = $row[ "Status" ];
    $printDueDate = $row[ "DATE_FORMAT(`Due Date`, '%m/%d/%y')" ];
    $printCategory = $row[ "Category" ];
    $projectID = $row[ "ProjectID" ];
    $printOwner = $row[ "First Name" ] . " " . $row[ "Last Name" ];
    $printBack[] = '<tr projectID="' . $projectID . '">
							<td style="width:25%;" class="projectTitle">' . $printTitle . '</td>
							<td>' . $printCategory . '</td>
							<td>' . $printCreatedDate . '</td>
							<td>' . $printDueDate . '</td>
							<td>' . $printOwner . '</td>
							<td><div class="taskStatus ' . $printStatus . '">' . $printStatus . '</div></td>
							<td><input type="checkbox" projectID="' . $projectID . '" value="' . $projectID . '"></td>
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

if ( $type == 'archive' ) {
  $query1 = "UPDATE `Team Projects` SET `Status`='Archived' WHERE `ProjectID` = '$projectID'";
  $query1_result = mysqli_query( $connection, $query1 )or die( "Query to get data from Team Project failed: " . mysql_error() );


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "archiveMultiple" ) {

  $projectIDs = explode( ",", $_POST[ 'projectIDs' ] );

  if ( is_array( $projectIDs ) ) {
    foreach ( $projectIDs as $projectID ) {

      $query1 = "UPDATE `Team Projects` SET `Status`='Archived' WHERE `ProjectID` = '$projectID'";
      $query1_result = mysqli_query( $connection, $query1 )or die( "Query to get data from Team Project failed: " . mysql_error() );

    }

  } else {

  }

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'reactivate' ) {

  reactivateProject( $projectID );


  $printBack = returnResults();
  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "reactivateMultiple" ) {

  $projectIDs = explode( ",", $_POST[ 'projectIDs' ] );

  if ( is_array( $projectIDs ) ) {
    foreach ( $projectIDs as $projectID ) {

      reactivateProject( $projectID );
    }

  } else {

  }

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'delete' ) {

  deleteProject( $projectID );

  ////////////////


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "deleteMultiple" ) {

  $projectIDs = explode( ",", $_POST[ 'projectIDs' ] );

  if ( is_array( $projectIDs ) ) {
    foreach ( $projectIDs as $projectID ) {
      deleteProject( $projectID );
    }

  } else {

  }

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeStatus' ) {
  $newVal = $_POST[ 'newVal' ];

  $query1 = "UPDATE `Team Projects` SET `Status`='$newVal' WHERE `ProjectID` = '$projectID'";
  $query1_result = mysqli_query( $connection, $query1 )or die( "Query to get data from Team Project failed: " . mysql_error() );

  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeTitle' ) {

  $newVal = addslashes( $_POST[ 'newVal' ] );

  $query1 = "UPDATE `Team Projects` SET `Title`='$newVal' WHERE `ProjectID` = '$projectID'";
  $query1_result = mysqli_query( $connection, $query1 )or die( "Query to get data from Team Project failed: " . mysql_error() );


  $printBack = returnResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}


?>