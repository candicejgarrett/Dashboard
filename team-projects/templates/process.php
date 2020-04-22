<?php
require( '../../connect.php' );
require( '../../header.php' );
require( '../../emailDependents.php' );
$type = $_POST[ 'type' ];


if ( $type == "loadTemplates" ) {

  $getAll = "SELECT `Team Projects Categories`.`Category`,`TemplateID`, `Name`, `Task Type`, `Visible`, `Team Projects Templates`.`userID`, `Group Membership`.`GroupID` 
FROM `Team Projects Templates` 
JOIN `Team Projects Categories` ON `Team Projects Templates`.`Category`=`Team Projects Categories`.`ProjectCategoryID`
JOIN `Group Membership` ON `Team Projects Templates`.`userID`=`Group Membership`.`userID`
WHERE `Group Membership`.`GroupID` = '$groupID'
ORDER BY `TemplateID` DESC";
  $getAll_result = mysqli_query( $connection, $getAll )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $getAll_result ) ) {
    $templateID = $row[ 'TemplateID' ];
    $templateTitle = $row[ 'Name' ];
    $templateTaskType = $row[ "Task Type" ];
    $templateCategory = $row[ 'Category' ];
    $templateVisible = $row[ 'Visible' ];
    $templateCreatorID = $row[ 'userID' ];


    $gettemplateCreatorFNByID = "SELECT * FROM `user` WHERE `userID` = '$templateCreatorID'";
    $gettemplateCreatorFNByID_result = mysqli_query( $connection, $gettemplateCreatorFNByID )or die( "Query to get data from Team Project failed: " . mysql_error() );

    while ( $row2 = mysqli_fetch_array( $gettemplateCreatorFNByID_result ) ) {
      $gettemplateCreatorFN = $row2[ 'First Name' ] . " " . $row2[ 'Last Name' ];
    }

    $canDelete;
    if ( $userID == $templateCreatorID || strpos( $myRole, 'Admin' ) == true ) {
      $canDelete = "<div class='deleteTemplate' id='$templateID'><i class='fa fa-ban' aria-hidden='true'></i></div>";
    } else {
      $canDelete = "";
    }

    $printBackTemplates[] = "<div class='col-sm-4 text-center'>$canDelete<div class='templates'><h4 class='text-center' style='line-height:25px;'>$templateTitle<br><span class='muted'>Created By: $gettemplateCreatorFN</span></h4><img src='../../images/template.png' style='width:100%'><div class='templateInfo'>
											<p><strong>Task Type</strong>: $templateTaskType</p>
											<p><strong>Category</strong>: $templateCategory</p>
											<p><strong>Visible</strong>: $templateVisible</p>
											<br>
											<center><button class='project-btn' style='width:auto' templateid='$templateID' data-toggle='modal' data-target='#editTemplate'>View</button></center><br><br>
											</div></div><br></div>";
  }

  //////////////

  $result = [ "printBack" => $printBackTemplates ];
  header( 'Content-Type: application/json' );
  echo json_encode( $result );

}

if ( $type == "newTemplate" ) {

  $templateName = $_POST[ 'templateName' ];
  $templateCategory = $_POST[ 'templateCategory' ];
  $templateVisible = $_POST[ 'templateVisible' ];
  $templateTaskType = $_POST[ 'templateTaskType' ];
  $templateMembers = json_decode( $_POST[ 'templateMembers' ], true );
  $taskVariables = json_decode( $_POST[ 'taskVariables' ], true );
  $templateCadenceOrder = json_decode( $_POST[ 'templateCadenceOrder' ], true );


  $query = "INSERT INTO `Team Projects Templates`(`Name`, `Category`, `Task Type`, `Visible`, `userID`) VALUES ('$templateName','$templateCategory','$templateTaskType','$templateVisible','$userID')";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );

  $templateID = mysqli_insert_id( $connection );

  //ADDING MEMBERS
  foreach ( $templateMembers as $member ) {

    $memberUserID = $member[ 0 ];


    $query4 = "INSERT INTO `Team Projects Templates Statements`(`TemplateID`, `Type`, `Value`) VALUES ('$templateID','Membership','$memberUserID')";
    $query4_result = mysqli_query( $connection, $query4 )or die( "Query to get data from Team task failed: " . mysql_error() );
  }


  //ADDING TASKS

  if ( $templateTaskType == "Standard" ) {
    $templateTaskDuration = 0;


    foreach ( $taskVariables as $task ) {
      $taskUserID = $task[ 0 ];
      $taskTypeID = $task[ 1 ];
      $taskEventID = $task[ 2 ];

      $query6 = "INSERT INTO `Team Projects Templates Statements`(`TemplateID`, `Type`, `Value`, `Task Type`, `Task Duration`) VALUES ('$templateID','Task','$taskUserID','$taskTypeID','$templateTaskDuration')";
      $query6_result = mysqli_query( $connection, $query6 )or die( "Query to get data from Team task failed: " . mysql_error() );
      //if this is a calendar event...
      if ( $taskEventID == 0 ) {

      } else {
        $query5 = "INSERT INTO `Team Projects Templates Statements`(`TemplateID`, `Type`, `Value`, `CalendarCategoryID`) VALUES ('$templateID','Task','$taskUserID','$taskEventID')";
        $query5_result = mysqli_query( $connection, $query5 )or die( "Query to get data from Team task failed: " . mysql_error() );


      }


    }

  } else {

    foreach ( $taskVariables as $task ) {
      $taskUserID = $task[ 0 ];
      $taskTypeID = $task[ 1 ];
      $taskDuration = $task[ 2 ];
      $taskEventID = $task[ 3 ];

      $query5 = "INSERT INTO `Team Projects Templates Statements`(`TemplateID`, `Type`, `Value`, `Task Type`, `Task Duration`) VALUES ('$templateID','Task','$taskUserID','$taskTypeID','$taskDuration')";
      $query5_result = mysqli_query( $connection, $query5 )or die( "Query to get data from Team task failed: " . mysql_error() );


      if ( $taskEventID == 0 ) {

      } else {
        $query5 = "INSERT INTO `Team Projects Templates Statements`(`TemplateID`, `Type`, `Value`, `CalendarCategoryID`) VALUES ('$templateID','Task','$taskUserID','$taskEventID')";
        $query5_result = mysqli_query( $connection, $query5 )or die( "Query to get data from Team task failed: " . mysql_error() );


      }


    }

  }

}

if ( $type == "editLoad" ) {
  $templateID = $_POST[ 'templateID' ];

  $getAll = "SELECT `Team Projects Categories`.`Category`,`Team Projects Templates`.`Category` AS 'CategoryID',`TemplateID`, `Name`, `Task Type`, `Visible`, `userID` FROM `Team Projects Templates` JOIN `Team Projects Categories` ON `Team Projects Templates`.`Category`=`Team Projects Categories`.`ProjectCategoryID` AND `TemplateID` ='$templateID'";
  $getAll_result = mysqli_query( $connection, $getAll )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $getAll_result ) ) {
    $thisTemplateID = $row[ 'TemplateID' ];
    $templateTitle = $row[ 'Name' ];
    $templateTaskType = $row[ "Task Type" ];
    $templateCategory = $row[ 'Category' ];
    $templateCategoryID = $row[ 'CategoryID' ];
    $templateVisible = $row[ 'Visible' ];

  }
  //getting members
  $query = "SELECT `StatementID`, `Value` AS 'userID',`username`,`PP Link` FROM `Team Projects Templates Statements` JOIN `user` ON `user`.`userID` = `Team Projects Templates Statements`.`Value` WHERE `TemplateID` = '$templateID' AND `Type` = 'Membership'";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = $query_result->fetch_assoc() ) {
    $memberUserID[] = $row[ 'userID' ];
    $memberUsername = $row[ 'username' ];
    $memberPP = $row[ 'PP Link' ];

    $templateMembers[] = '<div><img src="' . $memberPP . '" class="templateMembers"><span>@' . $memberUsername . '</span></div>';
  }

  //getting tasks 
  $query = "SELECT `StatementID`, `Value` AS 'userID',`Team Projects Templates`.`Task Type` AS 'Template Task Type',`Task Duration`,`Type`,`username`,`email`,`Task Categories`.`Category` AS 'TaskCategory',`Team Projects Templates Statements`.`Task Type` AS 'TaskCategoryID' FROM `Team Projects Templates Statements` JOIN `user` ON `user`.`userID` = `Team Projects Templates Statements`.`Value` JOIN `Task Categories` ON `Task Categories`.`CategoryID` = `Team Projects Templates Statements`.`Task Type` JOIN `Team Projects Templates` ON `Team Projects Templates`.`TemplateID` = `Team Projects Templates Statements`.`TemplateID` WHERE `Team Projects Templates Statements`.`TemplateID` = '$templateID' AND `Type` = 'Task' ORDER BY `StatementID` ASC";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = $query_result->fetch_assoc() ) {
    $memberUserID = $row[ 'userID' ];
    $memberUsername = $row[ 'username' ];
    $memberEmail = $row[ "email" ];
    $templateTaskType = $row[ 'Template Task Type' ];
    $memberTaskType = $row[ 'TaskCategory' ];
    $memberTaskTypeID = $row[ 'TaskCategoryID' ];
    $memberTaskDuration = $row[ 'Task Duration' ];

    if ( $templateTaskType == "Cadence" ) {
      $duration = '<strong>Duration:</strong><br>' . $memberTaskDuration . ' day(s)';
    } else {
      $duration = '';
    }

    $templateTasks[] = '<div class="templateRow"><div class="row"><div class="col-sm-9"><strong>' . $memberTaskType . '</strong><br>@<em>' . $memberUsername . '</em></div><div class="col-sm-3 text-center">' . $duration . '</div></div></div>';
  }


  //////////////

  $result = [ "templateID" => $templateID,
    "templateTitle" => $templateTitle,
    "templateTaskType" => $templateTaskType,
    "templateCategory" => $templateCategory,
    "templateCategoryID" => $templateCategoryID,
    "templateVisible" => $templateVisible,
    "templateMembers" => $templateMembers,
    "templateTasks" => $templateTasks
  ];
  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}

if ( $type == "deleteTemplate" ) {
  $templateID = $_POST[ 'templateID' ];
  $query = "DELETE FROM `Team Projects Templates Statements` WHERE `TemplateID` = '$templateID'";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );

  $query2 = "DELETE FROM `Team Projects Templates` WHERE `TemplateID` = '$templateID'";
  $query2_result = mysqli_query( $connection, $query2 )or die( "Query to get data from Team task failed: " . mysql_error() );
}
if ( $type == "newProjectFromTemplate" ) {

  $query2 = "SELECT * FROM `Team Projects Templates` WHERE `TemplateID` ='$templateID'";
  $query2_result = mysqli_query( $connection, $query2 )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = $query2_result->fetch_assoc() ) {
    $templateName = $row[ "Name" ];
    $templateCategory = $row[ "Category" ];
    $templateVisible = $row[ "Visible" ];
    $templateTaskType = $row[ "Task Type" ];
    $templateDaysToComplete = $row[ "Days To Complete" ];
  }
  if ( $templateDaysToComplete > 1 || $templateDaysToComplete == 0 ) {
    $s = 's';
  } else {
    $s = '';
  }

  $currentDate = time();
  $templateDueDate = date( 'Y-m-d H:i:s', strtotime( '+' . $templateDaysToComplete . ' day' . $s . '', $currentDate ) );


  //inserting record
  $addProject = "INSERT INTO `Team Projects`(`Status`,`Title`, `Category`, `Due Date`, `userID`, `Visible`, `Task Type`) VALUES ('Incomplete','$templateName','$templateCategory','$templateDueDate','$userID','$templateVisible','$templateTaskType')";
  $addProject_result = mysqli_query( $connection, $addProject )or die( mysqli_error( $connection ) );

  $projectID = mysqli_insert_id( $connection );

  /////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////

  $activity = "created the project: <em>$templateName</em>.";
  $addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `ProjectID`) VALUES ('$activity','Project','$userID','$projectID')";
  $addActivity_result = mysqli_query( $connection, $addActivity )or die( "activity failed: " . mysql_error() );

  //getting membership statements
  $query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$templateID' AND `Type` = 'Membership'";
  $query3_result = mysqli_query( $connection, $query3 )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = $query3_result->fetch_assoc() ) {
    $statements[] = $row[ "StatementID" ];
  }

  foreach ( $statements as $statement ) {

    $query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `StatementID` ='$statement'";
    $query3_result = mysqli_query( $connection, $query3 )or die( "Query to get data from Team task failed: " . mysql_error() );

    while ( $row = $query3_result->fetch_assoc() ) {
      $memberUserID = $row[ "Value" ];
    }

    $newCall = "INSERT INTO `Team Projects Member List`(`ProjectID`, `userID`) VALUES ('$projectID','$memberUserID')";
    mysqli_query( $connection, $newCall )or die( "Query to get data from Team task failed: " . mysql_error() );


  }

  //getting task statements
  $query3 = "SELECT * FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$templateID' AND `Type` = 'Task' ORDER BY `StatementID` DESC";
  $query3_result = mysqli_query( $connection, $query3 )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = $query3_result->fetch_assoc() ) {
    $statements2[] = $row[ "StatementID" ];
  }

  $query33 = "SELECT COUNT(*) FROM `Team Projects Templates Statements` WHERE `TemplateID` ='$templateID' AND `Type` = 'Task'";
  $query33_result = mysqli_query( $connection, $query33 )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = $query33_result->fetch_assoc() ) {
    $row_count = $row[ "COUNT(*)" ];
  }
  $automatedDays = $templateDaysToComplete / $row_count;


  foreach ( $statements2 as $statement2 ) {

    $query3 = "SELECT `StatementID`, `TemplateID`, `Type`, `Value`, `Task Type`, `Category` FROM `Team Projects Templates Statements` JOIN `Task Categories` ON `Task Categories`.`CategoryID` =`Team Projects Templates Statements`.`Task Type` WHERE `StatementID` ='$statement2'";
    $query3_result = mysqli_query( $connection, $query3 )or die( "Query to get data from Team task failed: " . mysql_error() );

    while ( $row = $query3_result->fetch_assoc() ) {
      $memberUserID = $row[ "Value" ];
      $taskCategoryID = $row[ "Task Type" ];
      $taskCategory = $row[ "Category" ];
    }

    if ( $automatedDays > 1 || $automatedDays == 0 ) {
      $s = 's';
    } else {
      $s = '';
    }

    $currentDate2 = time();
    $finalDueDate = date( 'Y-m-d H:i:s', strtotime( '+' . $automatedDays . ' day' . $s . '', $currentDate2 ) );

    if ( $finalDueDate > $templateDueDate ) {
      $finalDueDate = $templateDueDate;
    } else {
      $finalDueDate = $finalDueDate;
    }

    $newCall = "INSERT INTO `Tasks`(`Title`, `Due Date`, `Category`, `Requested By`, `ProjectID`, `userID`) VALUES ('$taskCategory Task','$finalDueDate','$taskCategoryID','$userID','$projectID','$memberUserID')";
    mysqli_query( $connection, $newCall )or die( "Query to get data from Team task failed: " . mysql_error() );

    $automatedDays = $automatedDays + $automatedDays;

  }


  ////////// CREATING FILE UPLOAD FOLDER //////////
  $path = '../uploads/' . $projectID;

  mkdir( $path, 0777, true );
  chmod( $path, 0777 );

  ////////// CREATING CONTENT FILE UPLOAD FOLDER //////////
  $path = '../review/uploads/' . $projectID;

  mkdir( $path, 0777, true );
  chmod( $path, 0777 );


  //////////////

  $result = [ "projectID" => $projectID ];
  header( 'Content-Type: application/json' );
  echo json_encode( $result );

}

if ( $type == "newTemplateCheckUsername" ) {
  $newname = $_POST[ 'username' ];
  $query56 = "SELECT * FROM `user` WHERE `username`='$newname'";
  $query56_result = mysqli_query( $connection, $query56 )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $query56_result ) ) {
    $newEmail = $row[ "email" ];
    $newUserID = $row[ "userID" ];
  }

  if ( !isset( $newUserID ) ) {
    echo "User does not exist.";
    exit;
  } else {

  }
}

if ( $type == "getUsernames" ) {
  $searchTerm = $_POST[ "typedUsername" ];

  $query = "SELECT DISTINCT `username`,`userID` FROM `user` WHERE `username` LIKE '%$searchTerm%'";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $query_result ) ) {
    $foundUsernames[] = "<div class='userTags' userID=" . $row[ 'userID' ] . ">" . $row[ 'username' ] . "</div>";

  }

  ////////////
  $results = [ "foundUsernames" => $foundUsernames ];

  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}

?>