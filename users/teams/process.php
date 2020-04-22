<?php
require( '../../connect.php' );
require( '../../header.php' );

if ( isset( $_POST[ 'type' ] ) ) {
  $type = $_POST[ 'type' ];
} else {
  $type = "";
}

if ( $type == "loadTeamProfile" ) {
  $team = $_POST[ 'teamName' ];

  //getting member count
  $getTotalMembers = "SELECT DISTINCT COUNT(`Group Membership`.`userID`) FROM `Group Membership` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Groups`.`Group Name` = '$team'";
  $getTotalMembers_result = mysqli_query( $connection, $getTotalMembers )or die( "4 to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getTotalMembers_result->fetch_assoc() ) {
    $printTotalMembers = $row[ "COUNT(`Group Membership`.`userID`)" ];
  }

  //getting open project count
  $getOpenProjects = "SELECT DISTINCT COUNT(`Team Projects`.`ProjectID`) FROM `Team Projects` JOIN `Group Membership` ON `Team Projects`.`userID` = `Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Groups`.`Group Name` = '$team' AND `Status` = 'Incomplete' AND `Visible` = 'Public'";
  $getOpenProjects_result = mysqli_query( $connection, $getOpenProjects )or die( "1 to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getOpenProjects_result->fetch_assoc() ) {
    $printOpenProjects = $row[ "COUNT(`Team Projects`.`ProjectID`)" ];
  }
  //getting completed project count
  $getCompletedProjects = "SELECT DISTINCT COUNT(`Team Projects`.`ProjectID`) FROM `Team Projects` JOIN `Group Membership` ON `Team Projects`.`userID` = `Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Groups`.`Group Name` = '$team' AND `Status` != 'Incomplete' AND `Visible` = 'Public'";
  $getCompletedProjects_result = mysqli_query( $connection, $getCompletedProjects )or die( "2 to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getCompletedProjects_result->fetch_assoc() ) {
    $printCompletedProjects = $row[ "COUNT(`Team Projects`.`ProjectID`)" ];
  }

  //getting project count
  $getProjects = "SELECT DISTINCT COUNT(`Team Projects`.`ProjectID`) FROM `Team Projects` JOIN `Group Membership` ON `Team Projects`.`userID` = `Group Membership`.`userID`JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Groups`.`Group Name` = '$team' AND `Visible` = 'Public'";
  $getProjects_result = mysqli_query( $connection, $getProjects )or die( "3 to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getProjects_result->fetch_assoc() ) {
    $printTotalProjects = $row[ "COUNT(`Team Projects`.`ProjectID`)" ];
  }

  //get members
  $getMembershipList = "SELECT * FROM `user` JOIN `Group Membership` ON `user`.`userID` = `Group Membership`.`userID` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Groups`.`Group Name` = '$team' ORDER BY `user`.`Last Name` ASC";

  $getMembershipList_result = mysqli_query( $connection, $getMembershipList )or die( "NEWWWW Query to get data from Team Project failed: " . mysql_error() );

  while ( $row = $getMembershipList_result->fetch_assoc() ) {
    $memberID = $row[ "userID" ];
    $memberGroupName = $row[ "Group Name" ];
    $memberGroupColor = $row[ "Group Color" ];
    $memberGroupName2 = str_replace( ' ', '', $memberGroupName );
    $memberFN = $row[ "First Name" ];
    $memberLN = $row[ "Last Name" ];
    $memberTitle = $row[ "Title" ];
    $memberEmail = $row[ "email" ];
    $memberPic = $row[ "PP Link" ];
    $memberRole = $row[ "Role" ];
    $memberRole2 = str_replace( ' ', '', $memberRole );

    $printMembers[] = "<div class='col-sm-4 text-center' style='margin-bottom:15px;'><a href='/dashboard/users/profile/?userID=$memberID'><img src='" . $memberPic . "' id='$memberID' class='profilePic'></a><h4><strong>$memberFN $memberLN</strong><span style='font-size:14px;display:block;margin-top:3px'>$memberTitle</span></h4></div>";
  }

  //getting projects
  $getProjects = "SELECT DISTINCT `Team Projects`.`ProjectID`,`Team Projects`.`Status`,`Team Projects`.`Title`,`Team Projects`.`Description`,`Team Projects`.`Category`,DATE_FORMAT(`Team Projects`.`Due Date`, '%a, %b. %e, %Y'),`Team Projects`.`Date Created`,`Team Projects`.`Date Created`,`Visible` FROM `Team Projects` JOIN `Group Membership` JOIN `Groups` ON `Groups`.`GroupID` = `Group Membership`.`GroupID` WHERE `Team Projects`.`userID`= `Group Membership`.`userID` AND `Team Projects`.`Visible` != 'Private' AND `Groups`.`Group Name` ='$team' ORDER BY `Status` DESC, `Date Created` DESC";
  $getProjects_result = mysqli_query( $connection, $getProjects )or die( "getTasks_result to get data from Team Project failed: " . mysql_error() );
  while ( $row = mysqli_fetch_assoc( $getProjects_result ) ) {
    $projectID = $row[ 'ProjectID' ];
    $projectTitle = $row[ 'Title' ];
    $ProjectStatus = $row[ 'Status' ];
    $ProjectDueDate2 = $row[ "DATE_FORMAT(`Team Projects`.`Due Date`, '%a, %b. %e, %Y')" ];

    $getProjectOpenTasks = "SELECT COUNT(*) FROM `Tasks` WHERE `status` != 'Completed' AND `ProjectID`='$projectID'";
    $getProjectOpenTasks_result = mysqli_query( $connection, $getProjectOpenTasks )or die( "getTasks_result to get data from Team Project failed: " . mysql_error() );

    while ( $row = $getProjectOpenTasks_result->fetch_assoc() ) {
      $projectOpenTasksCount = $row[ "COUNT(*)" ];
    }
    $getProjectCompletedTasks = "SELECT COUNT(*) FROM `Tasks` WHERE `status` = 'Completed' AND `ProjectID`='$projectID'";
    $getProjectCompletedTasks_result = mysqli_query( $connection, $getProjectCompletedTasks )or die( "getTasks_result to get data from Team Project failed: " . mysql_error() );

    while ( $row = $getProjectCompletedTasks_result->fetch_assoc() ) {
      $projectCompletedTasksCount = $row[ "COUNT(*)" ];
    }
    $getProjectAllTasks = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID`='$projectID'";
    $getProjectAllTasks_result = mysqli_query( $connection, $getProjectAllTasks )or die( "getTasks_result to get data from Team Project failed: " . mysql_error() );

    while ( $row = $getProjectAllTasks_result->fetch_assoc() ) {
      $projectAllTasksCount = $row[ "COUNT(*)" ];
    }
    if ( $projectOpenTasksCount == 0 && $projectAllTasksCount == 0 && $ProjectStatus !== 'Complete' ) {
      $percentage = 0;
    } else if ( $projectOpenTasksCount == 0 && $projectAllTasksCount == 0 && $ProjectStatus ) {
      $percentage = 100;
    } else {
      $percentage = ( $projectCompletedTasksCount / $projectAllTasksCount ) * 100;
    }


    $printProjects[] = "<tr id='$projectID'>
					  <td style='width:50%;'>
					  <a href='/dashboard/team-projects/view/?projectID=$projectID' class='addbtn'>$projectTitle</a><br><em style='font-size:12px;'><strong>Due:</strong> $ProjectDueDate2</em></td>
					  <td style='width:50%;'>$ProjectStatus<br><div class='progressBar-container'><div class='progressBar' style='width:$percentage%'></div></div></td>
					  </tr>";
  }

  //////////////

  $results = [ "printTeamName" => $team,
    "printTotalMembers" => $printTotalMembers,
    "printOpenProjects" => $printOpenProjects,
    "printCompletedProjects" => $printCompletedProjects,
    "printTotalProjects" => $printTotalProjects,
    "printMembers" => $printMembers,
    "printProjects" => $printProjects
  ];
  header( 'Content-Type: application/json' );
  echo json_encode( $results );
}

?>