<?php
include_once( '../../header.php' );
require( '../../connect.php' );

$tasks = array();

$query = mysqli_query( $connection, "SELECT DISTINCT `TaskID`,`Tasks`.`Title`,`Tasks`.`Due Date`,`Tasks`.`Category`,`Tasks`.`Status`,`Tasks`.`userID`,`Tasks`.`Requested By`,`Task Type`,`Tasks`.`ProjectID`,`Team Projects`.`Title` AS 'Project Title' FROM `Tasks` JOIN `Team Projects Member List` ON `Team Projects Member List`.`ProjectID` = `Tasks`.`ProjectID` JOIN `Team Projects` ON `Team Projects`.`ProjectID` = `Tasks`.`ProjectID` WHERE `Tasks`.`userID` = '$userID' ORDER By `Tasks`.`Due Date` DESC" );
while ( $fetch = mysqli_fetch_array( $query, MYSQLI_ASSOC ) ) {

  $taskProjectID = $fetch[ 'ProjectID' ];
  $taskDueDate = $fetch[ 'Due Date' ];
  $taskID = $fetch[ 'TaskID' ];

  if ( $fetch[ 'Task Type' ] === "Cadence" ) {


    $query2 = "SELECT `TaskID`, `Status` FROM `Tasks` WHERE `Due Date` < '$taskDueDate' AND `ProjectID`='$taskProjectID' AND `TaskID`!='$taskID' ORDER BY `Due Date` DESC LIMIT 1";
    $query2_result = mysqli_query( $connection, $query2 )or die( "NEw Query to get data from Team Project failed: " . mysql_error() );
    while ( $row = $query2_result->fetch_assoc() ) {
      $prevTaskID = $row[ "TaskID" ];
      $prevTaskStatus = $row[ "Status" ];
    }

    if ( $prevTaskStatus === "Completed" ) {
      $cadenceVal = "ready";
    } else {
      $cadenceVal = "notReady";
    }
  } else {
    $cadenceVal = "";
  }


  $e = array();
  $e[ 'id' ] = $fetch[ 'TaskID' ];
  $e[ 'title' ] = $fetch[ 'Title' ];
  $e[ 'start' ] = $fetch[ 'Due Date' ];
  $e[ 'end' ] = $fetch[ 'Due Date' ];
  $e[ 'Category' ] = $fetch[ 'Category' ];
  $e[ 'Status' ] = $fetch[ 'Status' ];
  $e[ 'Owner' ] = $fetch[ 'userID' ];
  $e[ 'OwnerTop' ] = $fetch[ 'Requested By' ];
  $e[ 'cadence' ] = $cadenceVal;
  $e[ 'projectTitle' ] = $fetch[ 'Project Title' ];

  $allday = ( $fetch[ 'allDay' ] == "true" ) ? true : false;
  $e[ 'allDay' ] = $allday;

  array_push( $tasks, $e );
}


header( 'Content-Type: application/json' );

echo json_encode( $tasks );


?>