<?php
include_once( '../../header.php' );
require( '../../connect.php' );


$reviews = array();


$query3 = mysqli_query( $connection, "SELECT `Tickets Review`.`ReviewID`, `Tickets Review`.`userID` AS 'Review Owner', `Tickets Review`.`ProjectID`, `Tickets Review`.`Title` AS 'Review Title',`Team Projects`.`Title` AS 'Project Title', `Tickets Review`.`Type`, `Tickets Review`.`Date Created`,`Tickets Review`.`Due Date` AS 'Standard Due Date', DATE_FORMAT(`Tickets Review`.`Due Date`, '%m/%d/%y'), `Tickets Review`.`Status` AS 'Review Status', `Desktop Preview Image Link`, `Mobile Preview Image Link`, `Tickets Review Members`.`Status` AS 'userStatus' FROM `Tickets Review` JOIN `Tickets Review Members` on `Tickets Review`.`ReviewID` = `Tickets Review Members`.`ReviewID` JOIN `Team Projects` on `Tickets Review`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `Tickets Review Members`.`userID` = '$userID'" );
while ( $row = mysqli_fetch_array( $query3, MYSQLI_ASSOC ) ) {

  $reviewID = $row[ 'ReviewID' ];
  $reviewDueDate = $row[ 'Standard Due Date' ];
  $reviewTitle = $row[ 'Review Title' ];
  $reviewType = $row[ '`Tickets Review`.`Type`' ];
  $reviewStatus = $row[ 'Review Status' ];
  $reviewProjectTitle = $row[ 'Project Title' ];
  $reviewIndividualStatus = $row[ 'userStatus' ];
  $reviewOwner = $row[ 'Review Owner' ];


  $r = array();
  $r[ 'id' ] = $reviewID;
  $r[ 'title' ] = $reviewTitle;
  $r[ 'start' ] = $reviewDueDate;
  $r[ 'end' ] = $reviewDueDate;
  $r[ 'Category' ] = $reviewType;
  $r[ 'Status' ] = $reviewStatus;
  $r[ 'projectTitle' ] = $reviewProjectTitle;
  $r[ 'userStatus' ] = $reviewIndividualStatus;
  $r[ 'OwnerTop' ] = $reviewOwner;

  array_push( $reviews, $r );
}


header( 'Content-Type: application/json' );

echo json_encode( $reviews );


?>