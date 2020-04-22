<?php
require( '../connect.php' );
require( '../header.php' );
require( '../emailDependents.php' );

if ( isset( $_POST[ 'type' ] ) ) {
  $type = $_POST[ 'type' ];
} else {
  $type = "";
}


if ( $type == "getDirectory" ) {

  $getMembershipList = "SELECT DISTINCT * FROM `user` JOIN `Group Membership` ON `user`.`userID` = `Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Member Status` = 'Active' ORDER BY `user`.`Last Name` ASC";

  $getMembershipList_result = mysqli_query( $connection, $getMembershipList )or die( "NEWWWW Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getMembershipList_result->fetch_assoc() ) {
    $memberID = $row[ "userID" ];
    $memberGroupName = $row[ "Group Name" ];
    $memberGroupName2 = str_replace( ' ', '', $memberGroupName );
    $memberColor = $row[ "Group Color" ];
    $memberFN = $row[ "First Name" ];
    $memberLN = $row[ "Last Name" ];
    $memberTitle = addslashes( $row[ "Title" ] );
    $memberEmail = $row[ "email" ];
    $memberPic = $row[ "PP Link" ];
    $memberRole = addslashes( $row[ "Role" ] );
    $memberRole2 = str_replace( ' ', '', $memberRole );
    $memberLastActive = $row[ "Last Active" ];


    $time = date( "Y-m-d H:i:s" );
    $timePlus10 = date( "Y-m-d H:i:s", strtotime( "-10 minutes" ) );
    if ( $memberLastActive > $timePlus10 ) {
      $online = '<div class="online-icon" style="margin-left: -20px;">&nbsp;</div>';
    } else {
      $online = '<div class="offline-icon" style="margin-left: -20px;">&nbsp;</div>';
    }


    //<div class='userCardHeader'>
    $printMembers[] = "<div class='col-sm-3' style='padding-bottom: 20px;'><div class='userCard role_$memberRole2 group_$memberGroupName2 hover'><div class='userCardHeader'><div class='pull-right'>$online</div><div class='text-center'><a href='profile/?userID=$memberID'><center><div class='largeRoundPP'><img src='" . $memberPic . "' id='$memberID'></div></center></a><h3>$memberFN $memberLN<br><span style='font-size:14px; font-weight:300; display: block;margin-top:3px'>$memberTitle</span></div></div><div class='col-sm-12'><p class='text-center'>Role: $memberRole</p><a href='teams/?team=$memberGroupName'><div class='directory' style='font-weight: bold; position: relative;background:$memberColor !important'>$memberGroupName Team</div></a></h3></div></div></div>";
  }

  ///////////

  $results = [ "printMembers" => $printMembers ];

  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}

?>