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

if ( $type == "loadMyProfile" ) {

  $getUserID = "SELECT * FROM `user` JOIN `Group Membership` ON `user`.`userID`= `Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `user`.`userID` ='$userID'";
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
  $getInfo = "SELECT `user`.`userID`, `username`, `email`, `password`, `First Name`, `Last Name`, `Role`, `Title`, `PP Link`, `Member Status`, `Groups`.`Group Name`, `Group Color` FROM `user` JOIN `Group Membership` ON `Group Membership`.`userID` = `user`.`userID` JOIN `Groups` ON `Group Membership`.`GroupID` = `Groups`.`GroupID` WHERE `user`.`userID`='$userID'";
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
  $getNewsfeedResult = getNewsfeed( 25, $userID );

  //SUBSCRIPTIONS
  $getSubscriptions = "SELECT * FROM `Calendar Categories`";
  $getSubscriptions_result = mysqli_query( $connection, $getSubscriptions )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $getSubscriptions_result ) ) {
    $calendarCategoryName = $row[ 'Category' ];
    $calendarCategoryID = $row[ 'CalendarCategoryID' ];
    $calendarCategoryColor = $row[ 'Category Color' ];
    //3.1.2 Checking the values are existing in the database or not
    $query = "SELECT * FROM `Notification Subscription` WHERE `userID` = '$userID' AND `CalendarCategoryID`='$calendarCategoryID'";
    $result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
    $count = mysqli_num_rows( $result );
    if ( $count == 1 ) {
      $printSubscriptions[] = '<div class="checkBoxTag" style="background:' . $calendarCategoryColor . '"><input type="checkbox" class="checkBox2 notificationSubscriptions" name="' . $calendarCategoryName . '" value="' . $calendarCategoryID . '" checked>' . $calendarCategoryName . '</input></div>';
    } else {
      $printSubscriptions[] = '<div class="checkBoxTag" style="background:' . $calendarCategoryColor . '"><input type="checkbox" class="checkBox2 notificationSubscriptions" name="' . $calendarCategoryName . '" value="' . $calendarCategoryID . '">' . $calendarCategoryName . '</input></div>';
    }


  }


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
    "newsfeedItems" => $getNewsfeedResult,
    "printSubscriptions" => $printSubscriptions
  ];
  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}

if ( $type == "updateProfilePic" ) {

  if ( isset( $_FILES[ 'file' ] ) ) {
    $errors = array();
    $file_name = $_FILES[ 'file' ][ 'name' ];
    $file_size = $_FILES[ 'file' ][ 'size' ];
    $file_tmp = $_FILES[ 'file' ][ 'tmp_name' ];
    $file_type = $_FILES[ 'file' ][ 'type' ];
    $file_ext = strtolower( end( explode( '.', $_FILES[ 'file' ][ 'name' ] ) ) );

    $expensions = array( "jpeg", "jpg", "png" );

    if ( in_array( $file_ext, $expensions ) === false ) {
      $errors[] = "extension not allowed.";
    }

    if ( $file_size > 20971520 ) {
      $errors[] = 'File size must be less than 20MB';
    }

    if ( empty( $errors ) == true ) {

      $path = '../profile-pictures/' . $userID . '_' . $file_name;
      $pathForDB = 'profile-pictures/' . $userID . '_' . $file_name;
      move_uploaded_file( $file_tmp, $path );
      chmod( $path, 0777 );
      $insertPP = "UPDATE `user` SET `PP Link`='/dashboard/users/$pathForDB' WHERE `userID`='$userID'";
      $insertPP_result = mysqli_query( $connection, $insertPP )or die( "insertPP_result failed: " . mysql_error() );


    } else {
      echo( $errors );
    }
  }
}

if ( $type == "updateInfo" ) {


  if ( isset( $_POST[ 'myEmail' ] ) ) {
    $newEmail = $_POST[ 'myEmail' ];
    $insertEmail = "UPDATE `user` SET `email`='$newEmail' WHERE `userID`='$userID'";
    $insertEmail_result = mysqli_query( $connection, $insertEmail )or die( "insertEmail_result failed: " . mysql_error() );
  }
  if ( isset( $_POST[ 'myTitle' ] ) ) {
    $newTitle = $_POST[ 'myTitle' ];
    $insertTitle = "UPDATE `user` SET `Title`='$newTitle' WHERE `userID`='$userID'";
    $insertTitle_result = mysqli_query( $connection, $insertTitle )or die( "insertTitle_result failed: " . mysql_error() );
  }


}

if ( $type == "updatePassword" ) {
  if ( isset( $_POST[ 'password' ] ) ) {
    //3.1.1 Assigning posted values to variables.
    $currentPassword = $_POST[ 'password' ];
    $newPassword = $_POST[ 'newPassword' ];
    $confirmNewPassword = $_POST[ 'confirmNewPassword' ];
    $username = $_SESSION[ 'username' ];

    //3.1.2 Checking the values are existing in the database or not
    $query = "SELECT * FROM `user` WHERE username='$username'";
    $result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
    while ( $row = $result->fetch_assoc() ) {
      $passwordHash = $row[ "password" ];
    }

    if ( password_verify( $currentPassword, $passwordHash ) ) {
      if ( $newPassword == $confirmNewPassword ) {
        if ( strlen( $newPassword ) <= '8' ) {
          $message = "Your password must contain at least 8 characters.";
        } elseif ( !preg_match( "#[0-9]+#", $newPassword ) ) {
          $message = "Your password must contain at least 1 number!";
        }
        else {
          $finalPassword = password_hash( $_POST[ 'confirmNewPassword' ], PASSWORD_DEFAULT );
          $updatePassword = "UPDATE `user` SET `password`='$finalPassword' WHERE `userID`='$userID'";
          $updatePassword_result = mysqli_query( $connection, $updatePassword )or die( mysqli_error( $connection ) );
          $message = "Your password has been changed.";
        }

      } else {
        $message = "Your password does not match.";
      }


    } else {
      $message = "Your current password is incorrect.";
    }


  }

  //////////////

  $result = [ "message" => $message ];
  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "updateSubscription" ) {
  $categoryID = $_POST[ 'categoryID' ];
  $isChecked = $_POST[ 'isChecked' ];

  if ( $isChecked == "checked" ) {

    //3.1.2 Checking the values are existing in the database or not
    $query = "SELECT * FROM `Notification Subscription` WHERE `CalendarCategoryID` = '$categoryID' AND `userID` = '$userID'";
    $result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
    $count = mysqli_num_rows( $result );
    if ( $count == 0 ) {
      $addSub = "INSERT INTO `Notification Subscription`(`userID`, `CalendarCategoryID`) VALUES ('$userID','$categoryID')";
      $addSub_result = mysqli_query( $connection, $addSub )or die( mysqli_error( $connection ) );
    } else {

    }
  } else {
    $deleteSub = "DELETE FROM `Notification Subscription` WHERE `userID` = '$userID' AND `CalendarCategoryID` = '$categoryID'";
    $deleteSub_result = mysqli_query( $connection, $deleteSub )or die( "Query to get data from Team task failed: " . mysql_error() );
  }


}


?>