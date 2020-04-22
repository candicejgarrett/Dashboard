<?php
include_once( '../../header.php' );
require( '../../connect.php' );
include( '../../functions/global.php' );
$type = $_POST[ 'type' ];


function returnGroupResults() {

  global $connection;
  global $userID;

  $query = "SELECT `GroupID`, `Group Name`, `Group Color` FROM `Groups`";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $printGroupID = $row[ "GroupID" ];
    $printGroupName = $row[ "Group Name" ];
    $printGroupColor = $row[ "Group Color" ];

    if ( $printGroupName == "Other" ) {
      $disabled = "disabled";
    } else {
      $disabled = "";
    }


    //GETTING  COUNT
    $getCount = "SELECT DISTINCT COUNT(`userID`) FROM `Group Membership` WHERE `GroupID` = '$printGroupID'";
    $getCount_result = mysqli_query( $connection, $getCount )or die( "getProjectCount Query to get data from 2 failed: " . mysql_error() );
    $row_count = $getCount_result->num_rows;
    $row = $getCount_result->fetch_assoc();
    $printCount = $row[ 'COUNT(`userID`)' ];


    $printBack[] = '<tr groupID="' . $printGroupID . '">
							<td>' . $printGroupName . '</td>
							<td style="background:' . $printGroupColor . ';color:#ffffff;font-weight:bold;">' . $printGroupColor . '</td>
							<td>' . $printCount . '</td>
							<td><input type="checkbox" groupID="' . $printGroupID . '" value="' . $printGroupID . '" ' . $disabled . '></td>
							</tr>';


  }


  return $printBack;
}

function returnCalendarEventResults() {

  global $connection;
  global $userID;

  $query = "SELECT * FROM `Calendar Categories` ORDER BY `Category` ASC";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $printCalendarCategoryID = $row[ "CalendarCategoryID" ];
    $printCalendarCategoryTitle = $row[ "Category" ];
    $printCalendarCategoryColor = $row[ "Category Color" ];

    if ( $printCalendarCategoryTitle == "Other" ) {
      $disabled = "disabled";
    } else {
      $disabled = "";
    }


    //GETTING  COUNT
    $getCount = "SELECT DISTINCT COUNT(`id`) FROM `calendar` WHERE `Category` = '$printCalendarCategoryID'";
    $getCount_result = mysqli_query( $connection, $getCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
    $row_count = $getCount_result->num_rows;
    $row = $getCount_result->fetch_assoc();
    $printCount = $row[ 'COUNT(`id`)' ];


    $printBack[] = '<tr eventID="' . $printCalendarCategoryID . '">
							<td>' . $printCalendarCategoryTitle . '</td>
							<td style="background:' . $printCalendarCategoryColor . ';color:#ffffff;font-weight:bold;">' . $printCalendarCategoryColor . '</td>
							<td>' . $printCount . '</td>
							<td><input type="checkbox" eventID="' . $printCalendarCategoryID . '" value="' . $printCalendarCategoryID . '" ' . $disabled . '></td>
							</tr>';


  }


  return $printBack;
}

function returnTaskTypeResults( $groupID ) {

  global $connection;
  global $userID;

  $query = "SELECT * FROM `Task Categories` WHERE `GroupID` = '$groupID' ORDER BY `Category` ASC";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $id = $row[ "CategoryID" ];
    $title = $row[ "Category" ];

    if ( $title == "Other" ) {
      $disabled = "disabled";
    } else {
      $disabled = "";
    }

    //GETTING  COUNT
    $getCount = "SELECT DISTINCT COUNT(`TaskID`) FROM `Tasks` WHERE `Category` = '$id'";
    $getCount_result = mysqli_query( $connection, $getCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
    $row_count = $getCount_result->num_rows;
    $row = $getCount_result->fetch_assoc();
    $printCount = $row[ 'COUNT(`TaskID`)' ];


    $printBack[] = '<tr taskID="' . $id . '">
							<td>' . $title . '</td>
							<td>' . $printCount . '</td>
							<td><input type="checkbox" taskID="' . $id . '" value="' . $id . '" ' . $disabled . '></td>
							</tr>';


  }


  return $printBack;
}

function returnKCResults() {

  global $connection;
  global $userID;

  $query = "SELECT * FROM `Knowledge Center Categories` ORDER BY `Category` ASC";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $printKCCategoryID = $row[ "KC CategoryID" ];
    $printKCCategoryTitle = $row[ "Category" ];

    if ( $printKCCategoryTitle == "Other" ) {
      $disabled = "disabled";
    } else {
      $disabled = "";
    }

    //GETTING  COUNT
    $getCount = "SELECT DISTINCT COUNT(`KC CategoryID`) FROM `Knowledge Center` WHERE `KC CategoryID` = '$printKCCategoryID'";
    $getCount_result = mysqli_query( $connection, $getCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
    $row_count = $getCount_result->num_rows;
    $row = $getCount_result->fetch_assoc();
    $printCount = $row[ 'COUNT(`KC CategoryID`)' ];

    //GETTING  Tag COUNT
    $getTagCount = "SELECT DISTINCT (`Tag`) FROM `Knowledge Center Tags` WHERE `KC CategoryID` = '$printKCCategoryID'";
    $getTagCount_result = mysqli_query( $connection, $getTagCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
    $row_count = $getTagCount_result->num_rows;


    $printBack[] = '<tr KCID="' . $printKCCategoryID . '">
							<td>' . $printKCCategoryTitle . '</td>
							<td>' . $printCount . '</td>
							<td>' . $row_count . '</td>
							<td><input type="checkbox" KCID="' . $printKCCategoryID . '" value="' . $printKCCategoryID . '" ' . $disabled . '></td>
							</tr>';


  }


  return $printBack;
}

function returnProjectTypeResults( $groupID ) {

  global $connection;
  global $userID;

  $query = "SELECT * FROM `Team Projects Categories` WHERE `GroupID` = '$groupID' ORDER BY `Category` ASC";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $id = $row[ "ProjectCategoryID" ];
    $title = $row[ "Category" ];

    if ( $title == "Other" ) {
      $disabled = "disabled";
    } else {
      $disabled = "";
    }

    //GETTING  COUNT
    $getCount = "SELECT DISTINCT COUNT(`ProjectID`) FROM `Team Projects` WHERE `Category` = '$id'";
    $getCount_result = mysqli_query( $connection, $getCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
    $row_count = $getCount_result->num_rows;
    $row = $getCount_result->fetch_assoc();
    $printCount = $row[ 'COUNT(`ProjectID`)' ];


    $printBack[] = '<tr projectID="' . $id . '">
							<td>' . $title . '</td>
							<td>' . $printCount . '</td>
							<td><input type="checkbox" projectID="' . $id . '" value="' . $id . '" ' . $disabled . '></td>
							</tr>';


  }


  return $printBack;
}

if ( $type == 'loadAllData' ) {
  $groups = returnGroupResults();

  $events = returnCalendarEventResults();

  $KC = returnKCResults();

  ////////////

  $result = [ "groups" => $groups,
    "events" => $events,
    "KC" => $KC
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'loadCalendarEvents' ) {
  $printBack = returnCalendarEventResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'loadTaskTypeData' ) {
  $groupID = $_POST[ 'groupID' ];
  $printBack = returnTaskTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'loadProjectTypeData' ) {
  $groupID = $_POST[ 'groupID' ];
  $printBack = returnProjectTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}


if ( $type == 'addGroup' ) {
  $groupName = addslashes( $_POST[ 'groupName' ] );
  $groupColor = addslashes( $_POST[ 'groupColor' ] );
  //getting all emails
  $getAllGroups = "SELECT `Group Name` FROM `Groups` WHERE `Group Name`='$groupName'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That group name is already in use.";

  } else if ( isset( $groupName ) || isset( $groupColor ) ) {
    $insert = "INSERT INTO `Groups`(`Group Name`, `Group Color`) VALUES ('$groupName','$groupColor')";
    $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );

    $message = "";
  }

  $printBack = returnGroupResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'addEvent' ) {
  $eventName = addslashes( $_POST[ 'eventName' ] );
  $eventColor = addslashes( $_POST[ 'eventColor' ] );

  //getting all emails
  $query = "SELECT `Category` FROM `Calendar Categories` WHERE `Category`='$eventName'";
  $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  $count = mysqli_num_rows( $query_result );
  if ( $count != 0 ) {
    $message = "That category name is already in use.";
  } else if ( isset( $eventName ) || isset( $eventColor ) ) {
    $insert = "INSERT INTO `Calendar Categories`(`Category`, `Category Color`) VALUES ('$eventName','$eventColor')";
    $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );


    $message = "";
  }

  $printBack = returnCalendarEventResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'addTaskType' ) {
  $name = addslashes( $_POST[ 'name' ] );
  $id = $_POST[ 'id' ];
  //getting all emails
  $getAllGroups = "SELECT `Category` FROM `Task Categories` WHERE `Category`='$name' AND `GroupID`='$id'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That category name is already in use.";

  } else if ( isset( $name ) || isset( $id ) ) {
    $insert = "INSERT INTO `Task Categories`(`Category`, `GroupID`) VALUES ('$name','$id')";
    $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );

    $message = "";
  }

  $printBack = returnTaskTypeResults( $id );

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'addKC' ) {
  $title = addslashes( $_POST[ 'title' ] );

  //getting all emails
  $query = "SELECT `Category` FROM `Knowledge Center Categories` WHERE `Category`='$title'";
  $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  $count = mysqli_num_rows( $query_result );
  if ( $count != 0 ) {
    $message = "That category name is already in use.";
  } else if ( isset( $title ) ) {
    $insert = "INSERT INTO `Knowledge Center Categories`(`Category`) VALUES ('$title')";
    $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );


    $message = "";
  }

  $printBack = returnKCResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'addProjectType' ) {
  $name = addslashes( $_POST[ 'name' ] );
  $id = $_POST[ 'id' ];
  //getting all emails
  $getAllGroups = "SELECT `Category` FROM `Team Projects Categories` WHERE `Category`='$name' AND `GroupID`='$id'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That category name is already in use.";

  } else if ( isset( $name ) || isset( $id ) ) {
    $insert = "INSERT INTO `Team Projects Categories`(`Category`, `GroupID`) VALUES ('$name','$id')";
    $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );

    $message = "";
  }

  $printBack = returnProjectTypeResults( $id );

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeGroupName' ) {

  $groupName = addslashes( $_POST[ 'newVal' ] );
  $groupID = $_POST[ 'groupID' ];

  $getAllGroups = "SELECT `Group Name` FROM `Groups` WHERE `Group Name`='$groupName'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That group name is already in use.";

  } else if ( isset( $groupName ) || isset( $groupID ) ) {

    $query = "UPDATE `Groups` SET `Group Name`='$groupName' WHERE `GroupID`='$groupID'";
    $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  }


  $printBack = returnGroupResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeEventName' ) {

  $name = addslashes( $_POST[ 'newVal' ] );
  $id = $_POST[ 'id' ];

  $getAllGroups = "SELECT `Category` FROM `Calendar Categories` WHERE `Category`='$name'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That category name is already in use.";

  } else if ( isset( $name ) || isset( $id ) ) {

    $query = "UPDATE `Calendar Categories` SET `Category`='$name' WHERE `CalendarCategoryID`='$id'";
    $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  }


  $printBack = returnCalendarEventResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeTaskTypeName' ) {

  $name = addslashes( $_POST[ 'newVal' ] );
  $id = $_POST[ 'id' ];
  $groupID = $_POST[ 'groupID' ];

  $getAllGroups = "SELECT `Category` FROM `Task Categories` WHERE `Category`='$name' AND `GroupID`='$groupID'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That category name is already in use.";

  } else if ( isset( $name ) || isset( $id ) ) {

    $query = "UPDATE `Task Categories` SET `Category`='$name' WHERE `CategoryID`='$id'";
    $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  }


  $printBack = returnTaskTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeKCName' ) {

  $title = addslashes( $_POST[ 'newVal' ] );
  $id = $_POST[ 'id' ];

  $query = "SELECT `Category` FROM `Knowledge Center Categories` WHERE `Category`='$title'";
  $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  $count = mysqli_num_rows( $query_result );
  if ( $count != 0 ) {
    $message = "That category name is already in use.";
  } else if ( isset( $title ) ) {

    $query2 = "UPDATE `Knowledge Center Categories` SET `Category`='$title' WHERE `KC CategoryID`='$id'";
    $query2_result = mysqli_query( $connection, $query2 )or die( mysqli_error( $connection ) );
  }


  $printBack = returnKCResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeProjectTypeName' ) {

  $name = addslashes( $_POST[ 'newVal' ] );
  $id = $_POST[ 'id' ];
  $groupID = $_POST[ 'groupID' ];

  $getAllGroups = "SELECT `Category` FROM `Team Projects Categories` WHERE `Category`='$name' AND `GroupID`='$groupID'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That category name is already in use.";

  } else if ( isset( $name ) || isset( $id ) ) {

    $query = "UPDATE `Team Projects Categories` SET `Category`='$name' WHERE `ProjectCategoryID`='$id'";
    $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  }


  $printBack = returnProjectTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeGroupColor' ) {

  $groupColor = addslashes( $_POST[ 'newVal' ] );
  $groupID = $_POST[ 'groupID' ];

  $getAllGroups = "SELECT `Group Name` FROM `Groups` WHERE `Group Color`='$groupColor'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That group color is already in use.";

  } else if ( isset( $groupColor ) || isset( $groupID ) ) {

    $query = "UPDATE `Groups` SET `Group Color`='$groupColor' WHERE `GroupID`='$groupID'";
    $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  }


  $printBack = returnGroupResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'changeEventColor' ) {

  $name = addslashes( $_POST[ 'newVal' ] );
  $id = $_POST[ 'id' ];

  $getAllGroups = "SELECT `Category Color` FROM `Calendar Categories` WHERE `Category Color`='$name'";
  $getAllGroups_result = mysqli_query( $connection, $getAllGroups )or die( mysqli_error( $connection ) );
  $groupCount = mysqli_num_rows( $getAllGroups_result );
  if ( $groupCount != 0 ) {
    $message = "That color is already in use.";

  } else if ( isset( $name ) || isset( $id ) ) {

    $query = "UPDATE `Calendar Categories` SET `Category Color`='$name' WHERE `CalendarCategoryID`='$id'";
    $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  }


  $printBack = returnCalendarEventResults();

  ////////////

  $result = [ "printBack" => $printBack,
    "message" => $message
  ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'deleteGroup' ) {
  $groupID = $_POST[ 'groupID' ];

  $query = "SELECT `GroupID`, `Group Name` FROM `Groups` WHERE `Group Name` = 'Other'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "GroupID" ];

  }

  $query2 = "UPDATE `Group Membership` SET `GroupID`='$otherCatID' WHERE `GroupID`='$groupID'";
  $query2_result = mysqli_query( $connection, $query2 )or die( mysqli_error( $connection ) );


  $remove = "DELETE FROM `Groups` WHERE `GroupID` = '$groupID'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );

  $printBack = returnGroupResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'deleteEvent' ) {
  $ID = $_POST[ 'eventID' ];

  $query = "SELECT `CalendarCategoryID`, `Category` FROM `Calendar Categories` WHERE `Category` = 'Other'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "CalendarCategoryID" ];

  }

  $remove2 = "UPDATE `calendar` SET `Category`='$otherCatID' WHERE `Category` = '$ID'";
  $remove2_result = mysqli_query( $connection, $remove2 )or die( mysqli_error( $connection ) );

  $remove = "DELETE FROM `Calendar Categories` WHERE `CalendarCategoryID` = '$ID'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );

  $printBack = returnCalendarEventResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'deleteTaskType' ) {
  $ID = $_POST[ 'id' ];
  $groupID = $_POST[ 'groupID' ];

  $query = "SELECT `CategoryID`, `Category` FROM `Task Categories` WHERE `Category` = 'Other' AND `GroupID`='$groupID'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "CategoryID" ];

  }


  $remove2 = "UPDATE `Tasks` 
INNER JOIN `Team Projects` on `Tasks`.`ProjectID`=`Team Projects`.`ProjectID`
INNER JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID`
SET `Tasks`.`Category`= '$otherCatID'
WHERE `Tasks`.`Category` = '$ID'
AND `Group Membership`.`GroupID` = '$groupID'";
  $remove2_result = mysqli_query( $connection, $remove2 )or die( mysqli_error( $connection ) );

  $remove = "DELETE FROM `Task Categories` WHERE `CategoryID` = '$ID'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );

  $printBack = returnTaskTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'deleteKC' ) {
  $ID = addslashes( $_POST[ 'ID' ] );

  $query = "SELECT `KC CategoryID`, `Category` FROM `Knowledge Center Categories` WHERE `Category` = 'Other'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "KC CategoryID" ];

  }

  $query2 = "UPDATE `Knowledge Center` SET `KC CategoryID`='$otherCatID' WHERE `KC CategoryID`='$ID'";
  $query2_result = mysqli_query( $connection, $query2 )or die( mysqli_error( $connection ) );

  $query3 = "UPDATE `Knowledge Center Tags` SET `KC CategoryID`='$otherCatID' WHERE `KC CategoryID`='$ID'";
  $query3_result = mysqli_query( $connection, $query3 )or die( mysqli_error( $connection ) );


  $remove = "DELETE FROM `Knowledge Center Categories` WHERE `KC CategoryID` = '$ID'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );


  $printBack = returnKCResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'deleteProjectType' ) {
  $ID = $_POST[ 'id' ];
  $groupID = $_POST[ 'groupID' ];

  $query = "SELECT `ProjectCategoryID`, `Category` FROM `Team Projects Categories` WHERE `Category` = 'Other' AND `GroupID`='$groupID'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "ProjectCategoryID" ];

  }


  $remove2 = "UPDATE `Team Projects` 
INNER JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID`
SET `Team Projects`.`Category`= '$otherCatID'
WHERE `Team Projects`.`Category` = '$ID'
AND `Group Membership`.`GroupID` = '$groupID'";
  $remove2_result = mysqli_query( $connection, $remove2 )or die( mysqli_error( $connection ) );

  $remove = "DELETE FROM `Team Projects Categories` WHERE `ProjectCategoryID` = '$ID'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );

  $printBack = returnProjectTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == 'deleteGroupsMultiple' ) {
  $groupIDs = explode( ",", $_POST[ 'groupIDs' ] );

  if ( is_array( $groupIDs ) ) {
    foreach ( $groupIDs as $groupID ) {
      $query = "UPDATE `Group Membership` SET `GroupID`='10' WHERE `GroupID`='$groupID'";
      $query_result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );


      $remove = "DELETE FROM `Groups` WHERE `GroupID` = '$groupID'";
      $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );
    }

  } else {

  }

  $printBack = returnGroupResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'deleteEventsMultiple' ) {
  $IDs = explode( ",", $_POST[ 'IDs' ] );

  if ( is_array( $IDs ) ) {
    foreach ( $IDs as $ID ) {
      $remove2 = "UPDATE `calendar` SET `Category`='10' WHERE `Category` = '$ID'";
      $remove2_result = mysqli_query( $connection, $remove2 )or die( mysqli_error( $connection ) );

      $remove = "DELETE FROM `Calendar Categories` WHERE `CalendarCategoryID` = '$ID'";
      $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );
    }

  } else {

  }

  $printBack = returnCalendarEventResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'deleteTaskTypesMultiple' ) {
  $IDs = explode( ",", $_POST[ 'IDs' ] );
  $groupID = $_POST[ 'groupID' ];


  $query = "SELECT `CategoryID`, `Category` FROM `Task Categories` WHERE `Category` = 'Other' AND `GroupID`='$groupID'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "CategoryID" ];

  }

  if ( is_array( $IDs ) ) {
    foreach ( $IDs as $ID ) {
      $remove2 = "UPDATE `Tasks` 
INNER JOIN `Team Projects` on `Tasks`.`ProjectID`=`Team Projects`.`ProjectID`
INNER JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID`
SET `Tasks`.`Category`= '$otherCatID'
WHERE `Tasks`.`Category` = '$ID'
AND `Group Membership`.`GroupID` = '$groupID'";
      $remove2_result = mysqli_query( $connection, $remove2 )or die( mysqli_error( $connection ) );

      $remove = "DELETE FROM `Task Categories` WHERE `CategoryID` = '$ID'";
      $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );
    }

  } else {

  }

  $printBack = returnTaskTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'deleteKCsMultiple' ) {
  $IDs = explode( ",", $_POST[ 'IDs' ] );

  $query = "SELECT `KC CategoryID`, `Category` FROM `Knowledge Center Categories` WHERE `Category` = 'Other'";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "KC CategoryID" ];

  }

  if ( is_array( $IDs ) ) {
    foreach ( $IDs as $ID ) {
      $query2 = "UPDATE `Knowledge Center` SET `KC CategoryID`='$otherCatID' WHERE `KC CategoryID`='$ID'";
      $query2_result = mysqli_query( $connection, $query2 )or die( mysqli_error( $connection ) );

      $query3 = "UPDATE `Knowledge Center Tags` SET `KC CategoryID`='$otherCatID' WHERE `KC CategoryID`='$ID'";
      $query3_result = mysqli_query( $connection, $query3 )or die( mysqli_error( $connection ) );


      $remove = "DELETE FROM `Knowledge Center Categories` WHERE `KC CategoryID` = '$ID'";
      $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );
    }

  } else {

  }

  $printBack = returnKCResults();

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}

if ( $type == 'deleteProjectTypesMultiple' ) {
  $IDs = explode( ",", $_POST[ 'IDs' ] );
  $groupID = $_POST[ 'groupID' ];

  $query = "SELECT `ProjectCategoryID`, `Category` FROM `Team Projects Categories` WHERE `Category` = 'Other' AND `GroupID`='$groupID'";

  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $otherCatID = $row[ "ProjectCategoryID" ];

  }

  if ( is_array( $IDs ) ) {
    foreach ( $IDs as $ID ) {
      $remove2 = "UPDATE `Team Projects` 
			INNER JOIN `Group Membership` ON `Group Membership`.`userID`=`Team Projects`.`userID`
			SET `Team Projects`.`Category`= '$otherCatID'
			WHERE `Team Projects`.`Category` = '$ID'
			AND `Group Membership`.`GroupID` = '$groupID'";
      $remove2_result = mysqli_query( $connection, $remove2 )or die( mysqli_error( $connection ) );

      $remove = "DELETE FROM `Team Projects Categories` WHERE `ProjectCategoryID` = '$ID'";
      $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );
    }

  } else {

  }

  $printBack = returnProjectTypeResults( $groupID );

  ////////////

  $result = [ "printBack" => $printBack ];

  header( 'Content-Type: application/json' );
  echo json_encode( $result );


}