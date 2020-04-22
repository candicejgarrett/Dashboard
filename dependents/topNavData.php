<?php

require( '../connect.php' );
require( '../header.php' );

///////// QUICK TASKS //////////

$query = "SELECT * FROM `Priority Levels` ORDER BY `levelID` DESC";
$query_result = mysqli_query( $connection, $query )or die( "getNotifications to get data from Team Project failed: " . mysql_error() );

while ( $row = $query_result->fetch_assoc() ) {
  $priorityLevelID = $row[ "levelID" ];
  $priorityTitle = $row[ "Title" ];
  $priorityColor = $row[ "Color" ];

  $printPriorityLevels[] = "<li><div class='priorityIcon' style='background:$priorityColor' priorityID='$priorityLevelID'></div></li>
	
	";
}

$getCount = "SELECT COUNT(`todoID`) FROM `Todo List` WHERE `userID` = '$userID' AND `isComplete` = 'no'";
$getCount_result = mysqli_query( $connection, $getCount )or die( "getNotifications to get data from Team Project failed: " . mysql_error() );
while ( $row = $getCount_result->fetch_assoc() ) {
  $todoCount = $row[ "COUNT(`todoID`)" ];
}


$query2 = "SELECT `todoID`, `userID`, `Todo List`.`Title`, `PriorityID`, `Timestamp`, `isComplete`, `whenCompleted`, `Color`, `levelID` FROM `Todo List` JOIN `Priority Levels` ON `Todo List`.`PriorityID` = `Priority Levels`.`levelID` WHERE `userID` = '$userID' ORDER BY `isComplete` ASC, `PriorityID` ASC";
$query2_result = mysqli_query( $connection, $query2 )or die( "getNotifications to get data from Team Project failed: " . mysql_error() );
$totalCount = $query2_result->num_rows;
while ( $row = $query2_result->fetch_assoc() ) {
  $priorityID = $row[ "levelID" ];
  $todoTitle = $row[ "Title" ];
  $todoID = $row[ "todoID" ];
  $todoTimestamp = $row[ "Timestamp" ];
  $todoIsComplete = $row[ "isComplete" ];
  $todoWhenCompleted = $row[ "whenCompleted" ];
  $priorityColor = $row[ "Color" ];

  if ( !isset( $priorityID ) ) {
    $printTodoList[] = '';
  } else {
    if ( $todoIsComplete === 'yes' ) {
      $isChecked = ' todoChecked';
      $isStrike = ' strikeout';
    } else {
      $isChecked = "";
      $isStrike = "";
    }

    $printTodoList[] = "
		<div class='todoItem' todoID='$todoID'>
				<div class='priority' priorityID='$priorityID' style='background:$priorityColor'></div>
				<div class='todoCheck$isChecked'></div>
				<div class='todoTitle$isStrike'>$todoTitle</div>
				<div class='todoMenu'>
					<ul>
						<li class='editTodo' todoID='$todoID'><i class='fa fa-pencil' aria-hidden='true'></i></li>
						<li class='deleteTodo' todoID='$todoID'><i class='fa fa-times' aria-hidden='true'></i></li>
					</ul>
				</div>
			</div>
	";
  }

}

///////// NOTIFICATIONS //////////

$query = "SELECT * FROM `Notifications` WHERE `userID`='$userID' ORDER BY `Timestamp` DESC LIMIT 100";
$query_result = mysqli_query( $connection, $query )or die( "getNotifications to get data from Team Project failed: " . mysql_error() );

while ( $row = $query_result->fetch_assoc() ) {
  $NotificationID = $row[ "NotificationID" ];
  $notificationText = $row[ "Notification" ];
  $seen = $row[ "Seen" ];
  $type = $row[ "Type" ];
  $Timestamp = $row[ "Timestamp" ];

  if ( $type == "Event" ) {
    $icon = '<i class="fa fa-calendar" aria-hidden="true"></i>';
  } else if ( $type == "Task" ) {
    $icon = '<i class="fa fa-tasks" aria-hidden="true"></i>';
  } else if ( $type == "Note" ) {
    $icon = '<i class="fa fa-commenting" aria-hidden="true"></i>';
  } else if ( $type == "Membership" || $type == "Users" ) {
    $icon = '<i class="fa fa-users" aria-hidden="true"></i>';
  } else if ( $type == "Ticket" || $type == "LHN" ) {
    $icon = '<i class="fa fa-ticket" aria-hidden="true"></i>';
  } else if ( strpos( $type, 'Review' ) !== false ) {
    $icon = '<i class="fa fa-comments-o" aria-hidden="true"></i>';
  } else if ( strpos( $type, 'Copy' ) !== false ) {
    $icon = '<i class="fa fa-commenting-o" aria-hidden="true"></i>';
  } else if ( strpos( $type, 'File' ) !== false ) {
    $icon = '<i class="fa fa-file" aria-hidden="true"></i>';
  }

  //get days,hours,mins

  $getSeperatedDate = "SELECT DAY(`Timestamp`),HOUR(`Timestamp`), MINUTE(`Timestamp`), SECOND(`Timestamp`) FROM `Notifications` WHERE `NotificationID`='$NotificationID'";
  $getSeperatedDate_result = mysqli_query( $connection, $getSeperatedDate )or die( "Query to get data from Team Project failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $getSeperatedDate_result ) ) {
    $day = $row[ "DAY(`Timestamp`)" ];
    $hour = $row[ "HOUR(`Timestamp`)" ];
    $minute = $row[ "MINUTE(`Timestamp`)" ];
    $second = $row[ "SECOND(`Timestamp`)" ];
  }
  $currentDay = date( "d" );
  $currentHour = date( "g" );
  $currentMinute = date( "i" );
  $currentSecond = date( "s" );
  $currentAMPM = date( "a" );


  if ( $hour > 12 ) {
    $hour = $hour - 12;
  }

  $finalDay = $currentDay - $day;
  $finalHour = $currentHour - $hour;
  $finalMinute = $currentMinute - $minute;
  $finalSecond = $currentSecond - $second;

  if ( $finalDay > 1 ) {

    $final = $finalDay . " days ago.";

  } else if ( $finalDay == 0 ) {

    if ( $finalHour == 0 ) {
      if ( $finalMinute == 0 || $finalMinute == 1 ) {
        $final = "Just now";
      } else if ( $finalMinute > 1 && $finalMinute < 60 ) {
        $final = $finalMinute . " minutes ago.";
      } else {
        $final = "1 hour ago.";

      }
    } else if ( $finalHour == 1 ) {
      $final = $finalHour . " hour ago.";

    } else if ( $finalHour > 1 && $finalHour < 12 ) {

      $final = $finalHour . " hours ago.";
    }


  } else if ( $finalDay == 1 ) {
    $final = $finalDay . " day ago.";
  } else {
    $final = '';
  }

  $printAllNotifications[] = "<div class='$seen' id='$NotificationID'><table width='100%' border='0' cellpadding='5'><tbody><tr><td><div class='notifcationIcon'>$icon</div></td><td><span class='notificationTime pull-right'>$final</span>$notificationText</td> </tr> </tbody></table></div>";

}

///////// FAVORITES //////////

$getFavCount = "SELECT COUNT(`FavoriteID`) FROM `Team Projects Favorites` WHERE `userID` = '$userID'";
$getFavCount_result = mysqli_query( $connection, $getFavCount )or die( "getNotifications to get data from Team Project failed: " . mysql_error() );
while ( $row = $getFavCount_result->fetch_assoc() ) {
  $favoriteCount = $row[ "COUNT(`FavoriteID`)" ];
}

$getAllProjects = "SELECT DISTINCT `Team Projects`.`ProjectID`, `Status`, `Title`, `Description`, DATE_FORMAT(`Due Date`, '%b %e, %Y'), `Team Projects`.`userID`, `Date Created`, `Visible` FROM `Team Projects Favorites` JOIN `Team Projects` ON `Team Projects`.`ProjectID`=`Team Projects Favorites`.`ProjectID` WHERE `Team Projects Favorites`.`userID`='$userID' ORDER BY `Date Created` DESC";
$getAllProjects_result = mysqli_query( $connection, $getAllProjects )or die( "Query to get data from Team Project failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $getAllProjects_result ) ) {
  $projectID = $row[ 'ProjectID' ];
  $projectTitle = $row[ 'Title' ];
  $projectDueDate = $row[ "DATE_FORMAT(`Due Date`, '%b %e, %Y')" ];
  $projectCategory = $row[ 'Category' ];
  $projectCategory2 = str_replace( ' ', '', $row[ 'Category' ] );
  $projectStatus = $row[ 'Status' ];
  $projectCreatorID = $row[ 'userID' ];
  $projectVisibility = $row[ 'Visible' ];


  $printFavoritesList[] = "
			<a href='/dashboard/team-projects/view/?projectID=$projectID' class='favoriteButton'>
			<div class='favoriteItem'>
				
				<div class='favoriteStatus'>$projectStatus</div>
				<div class='favoriteTitle'>$projectTitle</div>
				<div class='favoriteDueDate'>Due: $projectDueDate</div>
			</div>
			</a>";
}


///////// RETURN DATA //////////

$results = [ "printPriorityLevels" => $printPriorityLevels,
  "printTodoList" => $printTodoList,
  "todoCount" => $todoCount,
  "totalCount" => $totalCount,
  "printAllNotifications" => $printAllNotifications,
  "printFavoritesList" => $printFavoritesList,
  "printFavoriteCount" => $favoriteCount
];
header( 'Content-Type: application/json' );
echo json_encode( $results );


?>