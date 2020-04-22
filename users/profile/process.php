<?php
require( '../../connect.php' );
require( '../../header.php' );
require( '../../emailDependents.php' );
require( '../../functions/global.php' );

if ( isset( $_POST[ 'type' ] ) ) {
  $type = $_POST[ 'type' ];
} else {
  $type = "";
}

if ( $type == "loadProfile" ) {
  $viewingUserID = $_POST[ 'viewingUserID' ];

  $getUserID = "SELECT * FROM `user` JOIN `Group Membership` ON `user`.`userID`= `Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `user`.`userID` ='$viewingUserID'";
  $getUserID_result = mysqli_query( $connection, $getUserID )or die( "Query to get data from 1 failed: " . mysql_error() );
  while ( $row = $getUserID_result->fetch_assoc() ) {
    $firstName = $row[ "First Name" ];
    $lastName = $row[ "Last Name" ];
    $userProfilePicture = $row[ "PP Link" ];
    $userEmail = $row[ "email" ];
    $userUsername = $row[ "username" ];
    $userRole = $row[ "Role" ];
    $userTitle = $row[ "Title" ];
    $userGroupName = $row[ "Group Name" ];
    $userLastActive = $row[ "Last Active" ];
  }
  $time = date( "Y-m-d H:i:s" );
  $timePlus10 = date( "Y-m-d H:i:s", strtotime( "-10 minutes" ) );
  if ( $userLastActive > $timePlus10 ) {
    $online = '<div class="online">Online <div class="online-icon">&nbsp;</div></div>';
  } else {
    $online = '<div class="online">Offline <div class="offline-icon">&nbsp;</div></div>';
  }

  //GETTING ALL PERSONAL INFO
  $getInfo = "SELECT `user`.`userID`, `username`, `email`, `password`, `First Name`, `Last Name`, `Role`, `Title`, `PP Link`, `Member Status`, `Groups`.`Group Name`, `Group Color` FROM `user` JOIN `Group Membership` ON `Group Membership`.`userID` = `user`.`userID` JOIN `Groups` ON `Group Membership`.`GroupID` = `Groups`.`GroupID` WHERE `user`.`userID`='$viewingUserID'";
  $getInfo_result = mysqli_query( $connection, $getInfo )or die( "2 to get data from Team Project failed: " . mysql_error() );
  while ( $row = $getInfo_result->fetch_assoc() ) {
    $printFirstName = $row[ "First Name" ];
    $printLastName = $row[ "Last Name" ];
    $printProfilePicture = $row[ "PP Link" ];
    $printEmail = $row[ "email" ];
    $printUsername = $row[ "username" ];
    $printRole = $row[ "Role" ];
    $printGroupName = $row[ "Group Name" ];
    $printGroupColor = $row[ "Group Color" ];
    $printTitle = $row[ "Title" ];
  }


  // NEWSFEED

  $getNewsfeedResult = getNewsfeed( 25, $viewingUserID );


  //////////////

  $results = [ "printFirstName" => $printFirstName,
    "printLastName" => $printLastName,
    "printProfilePicture" => $printProfilePicture,
    "printEmail" => $printEmail,
    "printRole" => $printRole,
    "printGroupName" => $printGroupName,
    "printGroupColor" => $printGroupColor,
    "online" => $online,
    "printTitle" => $printTitle,
    "printUsername" => $printUsername,
    "newsfeedItems" => $getNewsfeedResult
  ];
  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}


?>