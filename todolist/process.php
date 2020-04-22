<?php
session_start();
require( '../connect.php' );
require( '../header.php' );

$type = $_POST[ "type" ];

if ( $type == "getAll" ) {

  $query = "SELECT * FROM `Priority Levels`";
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

    if ( $totalCount == 0 ) {
      $printTodoList[] = "";
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


  /////////////

  $results = [ "printPriorityLevels" => $printPriorityLevels,
    "printTodoList" => $printTodoList,
    "todoCount" => $todoCount,
    "totalCount" => $totalCount
  ];
  header( 'Content-Type: application/json' );
  echo json_encode( $results );


}

if ( $type == "getFiltered" ) {

  $filterType = $_POST[ "filterType" ];
  $theID = $_POST[ "theID" ];

  $query2 = "SELECT `todoID`, `userID`, `Todo List`.`Title`, `PriorityID`, `Timestamp`, `isComplete`, `whenCompleted`, `Color`, `levelID` FROM `Todo List` JOIN `Priority Levels` ON `Todo List`.`PriorityID` = `Priority Levels`.`levelID` WHERE `userID` = '$userID' AND `$filterType` = '$theID' ORDER BY `isComplete` ASC, `PriorityID` ASC";
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


  /////////////

  $results = [ "printTodoList" => $printTodoList ];
  header( 'Content-Type: application/json' );
  echo json_encode( $results );


}

if ( $type == "addTodoItem" ) {
  $title = addslashes( $_POST[ 'title' ] );
  $priorityID = $_POST[ 'priorityID' ];

  $insert = "INSERT INTO `Todo List`(`userID`, `Title`, `PriorityID`) VALUES ('$userID','$title','$priorityID')";
  $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );

}

if ( $type == "updateTodoItem" ) {
  $title = addslashes( $_POST[ 'title' ] );
  $priorityID = $_POST[ 'priorityID' ];
  $todoID = $_POST[ 'todoID' ];

  $update = "UPDATE `Todo List` SET `Title` = '$title', `PriorityID`='$priorityID' WHERE `todoID` = '$todoID' AND `userID` = '$userID'";
  $update_result = mysqli_query( $connection, $update )or die( mysqli_error( $connection ) );


}

if ( $type == "checkTodoItem" ) {
  $todoID = $_POST[ 'todoID' ];
  $value = $_POST[ 'value' ];
  $update = "UPDATE `Todo List` SET `isComplete` = '$value', `whenCompleted`=now() WHERE `todoID` = '$todoID' AND `userID` = '$userID'";
  $update_result = mysqli_query( $connection, $update )or die( mysqli_error( $connection ) );

}

if ( $type == "unCheckTodoItem" ) {
  $todoID = $_POST[ 'todoID' ];
  $value = $_POST[ 'value' ];
  $update = "UPDATE `Todo List` SET `isComplete` = '$value', `whenCompleted`=null WHERE `todoID` = '$todoID' AND `userID` = '$userID'";
  $update_result = mysqli_query( $connection, $update )or die( mysqli_error( $connection ) );

}

if ( $type == "deleteTodoItem" ) {
  $todoID = $_POST[ 'todoID' ];

  $delete = "DELETE FROM `Todo List` WHERE `todoID` = '$todoID' AND `userID` = '$userID'";
  $delete_result = mysqli_query( $connection, $delete )or die( mysqli_error( $connection ) );

}

if ( $type == "deleteAll" ) {

  $delete = "DELETE FROM `Todo List` WHERE `userID` = '$userID'";
  $delete_result = mysqli_query( $connection, $delete )or die( mysqli_error( $connection ) );

}


?>