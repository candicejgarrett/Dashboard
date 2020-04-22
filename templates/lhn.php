<?php

//USER CARD
$userInfo = '
<a href="/dashboard/users/my-profile/" class="myInfo">
	<img class="profilePic group_' . $groupName . '" src="' . $ProfilePic . '" id="userDetermination"/>
</a>
<strong>' . $FN . ' ' . $LN . '</strong>
<br>
<em>' . $Title . '</em>
<br>
<a href="/dashboard/users/teams/?team=' . $groupName . '">
	<div class="directory" style="font-weight: bold; position: relative;background:' . $groupColor . ' !important">' . $groupName . ' Team</div>
</a>';


//GETTING COUNTS
$getOpenReviewApprovalCount = "SELECT COUNT(*) FROM `Tickets Review Members` WHERE `userID` = '$userID' AND `Status` IS NULL OR `Status` = 'Not Approved'";
$getOpenReviewApprovalCount_result = mysqli_query( $connection, $getOpenReviewApprovalCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $getOpenReviewApprovalCount_result ) ) {
  $openReviewApprovals = $row[ 'COUNT(*)' ];
  if ( $row[ 'COUNT(*)' ] == 0 ) {
    $finalOpenReviewApprovals = "";
  } else {
    $finalOpenReviewApprovals = "<span class='requestsCount'>" . $openReviewApprovals . "</span>";
  }
}


//getting idividual task counts

$getAllOpenTasksCount = "SELECT COUNT(*) FROM `Tasks` WHERE (`Status` = 'New' OR `Status` = 'Approved') AND `userID` = '$userID'";
$getAllOpenTasksCount_result = mysqli_query( $connection, $getAllOpenTasksCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
$row = $getAllOpenTasksCount_result->fetch_assoc();
$approvalCount = $row[ 'COUNT(*)' ];

$getAllOpenTasksCount2 = "SELECT COUNT(*) FROM `Tasks` WHERE `Status` = 'In Review' AND `Requested By` = '$userID'";
$getAllOpenTasksCount2_result = mysqli_query( $connection, $getAllOpenTasksCount2 )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );
$row = $getAllOpenTasksCount2_result->fetch_assoc();
$approvalCount2 = $row[ 'COUNT(*)' ];


$taskCount = $approvalCount + $approvalCount2 + $openReviewApprovals;
if ( $taskCount == 0 ) {
  $finalTaskCount = "";
} else {
  $finalTaskCount = "<span class='taskCount'>" . $taskCount . "</span>";
}

$getOpenRequestsCount = "SELECT COUNT(*) FROM `Tickets` WHERE `Status` != 'Complete'";
$getOpenRequestsCount_result = mysqli_query( $connection, $getOpenRequestsCount )or die( "getProjectCount Query to get data from Team Project failed: " . mysql_error() );

$row_count = $getOpenRequestsCount_result->num_rows;
$row = $getOpenRequestsCount_result->fetch_assoc();
$openRequests = $row[ 'COUNT(*)' ];
if ( $openRequests == 0 ) {
  $finalOpenRequestCount = "";
} else {
  $finalOpenRequestCount = "<span class='requestsCount'>" . $openRequests . "</span>";
}
//get open tasks that are assigned to me
$query = "SELECT COUNT(*) FROM `Tasks` WHERE (`Status` = 'New' OR `Status` = 'Approved') AND `userID` = '$userID'";
$query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
while ( $row = mysqli_fetch_array( $query_result ) ) {

  if ( $row[ 'COUNT(*)' ] == 0 ) {
    $otherRequestsCount = "";
  } else {
    $otherRequestsCount = '<span class="taskCount">' . $row[ 'COUNT(*)' ] . '</span>';
  }
}
//get open tasks that i assigned
$myAssignedTasks = "SELECT COUNT(*) FROM `Tasks` WHERE `Status` = 'In Review' AND `Requested By` = '$userID'";
$myAssignedTasks_result = mysqli_query( $connection, $myAssignedTasks )or die( "Query to get data from Team task failed: " . mysql_error() );
while ( $row = mysqli_fetch_array( $myAssignedTasks_result ) ) {

  $myAssignedTasksCount = $row[ 'COUNT(*)' ];

  if ( $myAssignedTasksCount == 0 ) {
    $myAssignedTasksSpan = "";
  } else {
    $myAssignedTasksSpan = '<span class="taskCount">' . $myAssignedTasksCount . '</span>';
  }
}


$totalRequestCounts = $openRequests;

if ( $totalRequestCounts == 0 ) {
  $finalTotalRequestCounts = "";
} else {
  $finalTotalRequestCounts = "<span class='requestsCount'>" . $totalRequestCounts . "</span>";
}

//getting LHN
//if you are a ADMIN - show everything
if ( $myRole === "Admin" ) {

  $menu = "
	<table border='0' cellspacing='0' cellpadding='10' class='navcenter' id='myNavbar'>
		<tbody>
			<tr>
				<td class='link'>
					<a href='/dashboard/home/'><i class='fa fa-home' aria-hidden='true'></i>&nbsp;&nbsp; Home</a>
				</td>
			</tr>
			<tr>
				<td class='link'>
					<a href='/dashboard/team-projects/'><i class='fa fa-users' aria-hidden='true'></i>&nbsp;&nbsp; Team Projects</a>
				</td>
			</tr>
			<tr class='lhnDropdown' controller='workflowDropdown'>
				<td class='link'>
					<i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; Workflow " . $finalTaskCount . "
				</td>
			</tr>
			
			
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/calendar'><i class='fa fa-calendar' aria-hidden='true'></i>&nbsp;&nbsp; Calendar</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/my-tasks'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; My Open Tasks " . $otherRequestsCount . "</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/approvals'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; Task Approvals " . $myAssignedTasksSpan . "</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/reviews/'><i class='fa fa-check-circle' aria-hidden='true'></i>&nbsp;&nbsp; Reviews " . $finalOpenReviewApprovals . "</a>
				</td>
				</tr>

			<tr>
				<td class='link'>
					<a href='/dashboard/content-calendar/'><i class='fa fa-calendar-check-o' aria-hidden='true'></i>&nbsp;&nbsp; Content Calendar</a>
				</td>
			</tr>
			
			<tr class='lhnDropdown' controller='requestsDropdown'>
				<td class='link'>
					<i class='fa fa-list' aria-hidden='true'></i>&nbsp;&nbsp; Requests " . $finalTotalRequestCounts . "
				</td>
			</tr>
			
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/view/'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; Ticket Center " . $finalOpenRequestCount . "</a>
				</td>
			</tr>
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/submit'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; Submit A Ticket</a>
				</td>
			</tr>
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/my-requests'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; My Tickets</a>
				</td>
			</tr>

			<tr>
				<td class='link'>
					<a href='/dashboard/knowledge-center/'><i class='fa fa-book' aria-hidden='true' style='font-size: 18px;'></i>&nbsp;&nbsp;&nbsp;Knowledge Center</a>
				</td>
			</tr>
			
		</tbody>
	</table>";

}

//if you are a EDITOR
else if ( $myRole === "Editor" ) {

  $menu = "
	<table border='0' cellspacing='0' cellpadding='10' class='navcenter' id='myNavbar'>
		<tbody>
			<tr>
				<td class='link'>
					<a href='/dashboard/home/'><i class='fa fa-home' aria-hidden='true'></i>&nbsp;&nbsp; Home</a>
				</td>
			</tr>
			<tr>
				<td class='link'>
					<a href='/dashboard/team-projects/'><i class='fa fa-users' aria-hidden='true'></i>&nbsp;&nbsp; Team Projects</a>
				</td>
			</tr>
			<tr class='lhnDropdown' controller='workflowDropdown'>
				<td class='link'>
					<i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; Workflow " . $finalTaskCount . "
				</td>
			</tr>
			
			
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/calendar'><i class='fa fa-calendar' aria-hidden='true'></i>&nbsp;&nbsp; Calendar</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/my-tasks'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; My Open Tasks " . $otherRequestsCount . "</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/approvals'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; Task Approvals " . $myAssignedTasksSpan . "</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/reviews/'><i class='fa fa-check-circle' aria-hidden='true'></i>&nbsp;&nbsp; Reviews " . $finalOpenReviewApprovals . "</a>
				</td>
				</tr>
		
			
			
			<tr>
				<td class='link'>
					<a href='/dashboard/content-calendar/'><i class='fa fa-calendar-check-o' aria-hidden='true'></i>&nbsp;&nbsp; Content Calendar</a>
				</td>
			</tr>
			
			<tr class='lhnDropdown' controller='requestsDropdown'>
				<td class='link'>
					<i class='fa fa-list' aria-hidden='true'></i>&nbsp;&nbsp; Requests " . $finalTotalRequestCounts . "
				</td>
			</tr>
			
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/view/'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; Ticket Center " . $finalOpenRequestCount . "</a>
				</td>
			</tr>
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/submit'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; Submit A Ticket</a>
				</td>
			</tr>
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/my-requests'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; My Tickets</a>
				</td>
			</tr>
			
			<tr>
				<td class='link'>
					<a href='/dashboard/knowledge-center/'><i class='fa fa-book' aria-hidden='true' style='font-size: 18px;'></i>&nbsp;&nbsp;&nbsp;Knowledge Center</a>
				</td>
			</tr>
			
		</tbody>
	</table>";

}

//if you are A CONTRIBUTOR
else {

  $menu = "
	<table border='0' cellspacing='0' cellpadding='10' class='navcenter' id='myNavbar'>
		<tbody>
			<tr>
				<td class='link'>
					<a href='/dashboard/home/'><i class='fa fa-home' aria-hidden='true'></i>&nbsp;&nbsp; Home</a>
				</td>
			</tr>
			<tr>
				<td class='link'>
					<a href='/dashboard/team-projects/'><i class='fa fa-users' aria-hidden='true'></i>&nbsp;&nbsp; Team Projects</a>
				</td>
			</tr>
			<tr class='lhnDropdown' controller='workflowDropdown'>
				<td class='link'>
					<i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; Workflow " . $finalTaskCount . "
				</td>
			</tr>
			
			
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/calendar'><i class='fa fa-calendar' aria-hidden='true'></i>&nbsp;&nbsp; Calendar</a>
				</td>
				</tr>
				
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/my-tasks'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; My Open Tasks " . $otherRequestsCount . "</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/approvals'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp;&nbsp; Task Approvals " . $myAssignedTasksSpan . "</a>
				</td>
				</tr>
				<tr class='lhnDropdownMenu' controlledby='workflowDropdown'>
				<td class='link'>
					<a href='/dashboard/todo/reviews/'><i class='fa fa-check-circle' aria-hidden='true'></i>&nbsp;&nbsp; Reviews " . $finalOpenReviewApprovals . "</a>
				</td>
				</tr>
				
				
			<tr>
				<td class='link'>
					<a href='/dashboard/content-calendar/'><i class='fa fa-calendar-check-o' aria-hidden='true'></i>&nbsp;&nbsp; Content Calendar</a>
				</td>
			</tr>
			
			<tr class='lhnDropdown' controller='requestsDropdown'>
				<td class='link'>
					<i class='fa fa-list' aria-hidden='true'></i>&nbsp;&nbsp; Requests " . $showRequestCenterCount . "
				</td>
			</tr>
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/submit'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; Submit A Ticket</a>
				</td>
			</tr>
			<tr class='lhnDropdownMenu' controlledby='requestsDropdown'>
				<td class='link'>
					<a href='/dashboard/requests/my-requests'><i class='fa fa-ticket' aria-hidden='true'></i>&nbsp;&nbsp; My Tickets</a>
				</td>
			</tr>
			
			<tr>
				<td class='link'>
					<a href='/dashboard/knowledge-center/'><i class='fa fa-book' aria-hidden='true' style='font-size: 18px;'></i>&nbsp;&nbsp;&nbsp;Knowledge Center</a>
				</td>
			</tr>
		</tbody>
	</table>";

}


echo '
<div class="col-sm-2 nav navFix">
	
        <div class="userInfo">
         ' . $userInfo . '
        </div>
      <nav class="navMe">
       ' . $menu . '
      </nav>
	  
    </div>
';

?>