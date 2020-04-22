<?php
include_once('../../header.php');
 require('../../connect.php');
require('../../emailDependents.php');
$type = $_POST['type'];
$thisUserID = $_POST['userID'];

function returnResults() {
	
	global $connection;
	global $userID;
	
	$query = "SELECT DISTINCT `user`.`userID`, `username`, `email`, `password`, `First Name`, `Last Name`, `Role`, DATE_FORMAT(`Last Active`, '%b %d %Y @ %h:%i%p') AS 'Last Active', `Title`, `PP Link`, `Member Status`,
RequestedGroup.`Group Name` AS 'Requested Group',
`user`.`Requested Group` AS 'Requested GroupID',
CurrentGroup.`Group Name` AS 'Current Group',
CurrentGroup.`GroupID` AS 'Current GroupID' 
FROM `user`
LEFT JOIN `Groups` AS RequestedGroup ON RequestedGroup.`GroupID`=`user`.`Requested Group` 
LEFT JOIN `Group Membership` ON `Group Membership`.`userID` = `user`.`userID`
LEFT JOIN `Groups` AS CurrentGroup ON CurrentGroup.`GroupID` = `Group Membership`.`GroupID`
WHERE `user`.`userID` != '1'";
	
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

	while ($row = mysqli_fetch_array($query_result)) {
					$printUserID = $row["userID"];
					$printUserRole = $row["Role"];
					$printUserEmail = $row["email"];
					$printUserUsername = $row["username"];
					$printUserLastName = $row["Last Name"];
					 $printUserFirstName = $row["First Name"];
					 $printUserTitle = $row["Title"];
					 $printUserRequestedGroup = $row["Requested Group"];
					 $printUserRequestedGroupID = $row["Requested GroupID"];
					$printUserMemberStatus = $row["Member Status"];
					$printUserLastActive = $row["Last Active"];
					$printUserCurrentGroup = $row["Current Group"];
								$printUserCurrentGroupID = $row["Current GroupID"];		
					$printBack[]= '<tr userID="'.$printUserID.'">
				<td><p>'.$printUserID.'</p></td>
				<td><p>'.$printUserUsername.'</p></td>
				<td><p>'.$printUserEmail.'</p></td>
				<td><p>'.$printUserFirstName.'</p></td>
				<td><p>'.$printUserLastName.'</p></td>
				<td><p>'.$printUserRole.'</p></td>
				
				<td><p>'.$printUserTitle.'</p></td>
				<td><p>'.$printUserMemberStatus.'</p></td>
				<td id="'.$printUserRequestedGroupID.'"><p>'.$printUserRequestedGroup.'</p></td>
				<td id="'.$printUserCurrentGroupID.'"><p>'.$printUserCurrentGroup.'</p></td>
				<td><p>'.$printUserLastActive.'</p></td>
				<td><input type="checkbox" userID="'.$printUserID.'" value="'.$printUserID.'"></td>
			  </tr>';
											
											
										}
	
	return $printBack;
}

if($type == 'load')
{
	$printBack = returnResults();
	
	////////////
	
	$result = ["printBack" => $printBack];

	header('Content-Type: application/json'); 
	echo json_encode($result);
}

if ($type == "deactivateMultiple") {
	
	$userIDs= explode(",", $_POST['userIDs']);
	
	if (is_array($userIDs))
	{
		foreach ($userIDs as $thisUserID) {
			
	$setInactive = "UPDATE `user` SET `Member Status`='Inactive' WHERE `userID` = '$thisUserID'";
			$setInactive_result = mysqli_query($connection, $setInactive) or die ("Query to get data from Team task failed: ".mysql_error());
		
			$getUserInfo = "SELECT `First Name`, `Last Name`, `GroupID` FROM `user` JOIN `Group Membership` ON `user`.`userID` = `Group Membership`.`userID` WHERE `user`.`userID` = '$thisUserID'";
			$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("Query to get data from Team task failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUserInfo_result)) {
					$firstName2 =$row["First Name"];	
					$lastName2 =$row["Last Name"];
					$currentGroup =$row["GroupID"];
				}
			
			$removeFromGroup = "DELETE FROM `Group Membership` WHERE `userID`='$thisUserID'";
	$removeFromGroup_result = mysqli_query($connection, $removeFromGroup) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$removeSub = "DELETE FROM `Notification Subscription` WHERE `userID`='$thisUserID'";
	$removeSub_result = mysqli_query($connection, $removeSub) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$addRequestedGroup = "UPDATE `user` SET `Requested Group`='$currentGroup' WHERE `userID`='$thisUserID'";
	$addRequestedGroup_result = mysqli_query($connection, $addRequestedGroup) or die ("Query to get data from Team task failed: ".mysql_error());

	
	/////////// SENDING EMAIL TO DEACTIVATED USER ///////////	
		
	if (isset($thisUserID)) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
							
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "Dashboard Access Revoked.";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%" style="background: #ffffff;">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
             
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              You have been removed from the Dashboard.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please contact '.$FN.' '.$LN.' if you have any questions.</p>
             
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}

		}
		
	}
	else {
		
	}
	
$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	
}

if ($type == "reactivateMultiple") {
	
	$userIDs= explode(",", $_POST['userIDs']);
	
	if (is_array($userIDs))
	{
		foreach ($userIDs as $thisUserID) {
			
	
$setActive = "UPDATE `user` SET `Member Status`='Active' WHERE `userID` = '$thisUserID'";
			$setActive_result = mysqli_query($connection, $setActive) or die ("Query to get data from Team task failed: ".mysql_error());
		
			$getUserInfo = "SELECT `First Name`, `Last Name`, `Requested Group` FROM `user` WHERE `userID` = '$thisUserID'";
			$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("Query to get data from Team task failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUserInfo_result)) {
					$firstName2 =$row["First Name"];	
					$lastName2 =$row["Last Name"];
					if ($row["Requested Group"]) {
					$requestedGroup =$row["Requested Group"];
					}
					else {
						//other
						$requestedGroup =10;
					}
			}
	
			
				
			$addToGroup = "INSERT INTO `Group Membership`(`GroupID`, `userID`) VALUES ('$requestedGroup','$thisUserID')";
	$addToGroup_result = mysqli_query($connection, $addToGroup) or die ("Query to get data from Team task failed: ".mysql_error());
	
	
	$removeRequestedGroup = "UPDATE `user` SET `Requested Group`=NULL WHERE `userID`='$thisUserID'";
	$removeRequestedGroup_result = mysqli_query($connection, $removeRequestedGroup) or die ("Query to get data from Team task failed: ".mysql_error());
				
				
	/////////// SENDING EMAIL TO NEW USER ///////////	
		
	if (isset($thisUserID)) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$userID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
							
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "Dashboard Access Granted.";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%" style="background:#ffffff;">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              You have been added the Dashboard! 
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please contact '.$FN.' '.$LN.' if you have any questions.</p>
             
             <a href="https://dashboard.coat.com/dashboard/" class="button">Login</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}			

		}
		
	}
	else {
		
	}
	
	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

}

if ($type == "deleteMultiple") {
	
	$userIDs= explode(",", $_POST['userIDs']);
	
	if (is_array($userIDs))
	{
		foreach ($userIDs as $thisUserID) {
			$getUserInfo = "SELECT `First Name`, `Last Name` FROM `user` WHERE `user`.`userID` = '$thisUserID'";
			$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("Query to get data from Team task failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUserInfo_result)) {
					$firstName2 =$row["First Name"];	
					$lastName2 =$row["Last Name"];
				}
			
			
			
			$deleteGroupMembership = "DELETE FROM `Group Membership` WHERE `userID` = '$thisUserID'";
	$deleteGroupMembership_result = mysqli_query($connection, $deleteGroupMembership) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$deleteNotificationSubscription = "DELETE FROM `Notification Subscription` WHERE `userID` = '$thisUserID'";
	$deleteNotificationSubscription_result = mysqli_query($connection, $deleteNotificationSubscription) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$deleteNotifications = "DELETE FROM `Notifications` WHERE `userID` = '$thisUserID'";
	$deleteNotifications_result = mysqli_query($connection, $deleteNotifications) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//if notebook exists
	$query1 = "SELECT `NotebookID` FROM `Notebooks` WHERE `userID` ='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($query1_result)) {
			$notebookID=$row["NotebookID"];	
		}
	
	if (isset($notebookID)) {
		$deletePages = "DELETE FROM `Notebooks Pages` WHERE `NotebookID` = '$notebookID'";
		$deletePages_result = mysqli_query($connection, $deletePages) or die ("Query to get data from Team task failed: ".mysql_error());
		
		$deleteNotebooks = "DELETE FROM `Notebooks` WHERE `userID` = '$thisUserID'";
		$deleteNotebooks_result = mysqli_query($connection, $deleteNotebooks) or die ("Query to get data from Team task failed: ".mysql_error());

		
	}
	else {
		
	}

	
	$deleteProjectMembership = "DELETE FROM `Team Projects Member List` WHERE `userID` = '$thisUserID'";
	$deleteProjectMembership_result = mysqli_query($connection, $deleteProjectMembership) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$deleteTodoList = "DELETE FROM `Todo List` WHERE `userID` = '$thisUserID'";
	$deleteTodoList_result = mysqli_query($connection, $deleteTodoList) or die ("Query to get data from Team task failed: ".mysql_error());
		
			
	/////////// SENDING EMAIL TO DELETED USER ///////////	
		
	if (isset($thisUserID)) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$userID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
							
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "Dashboard Account Deleted.";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%" style="background:repeat-x url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) #ffffff;">
      <center>
      <img src="http://s3.amazonaws.com/swu-filepicker/SBb2fQPrQ5ezxmqUTgCr_transparent.png" class="force-width-gmail">
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" background="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            <!--[if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:80px; v-text-anchor:middle;">
              <v:fill type="tile" src="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" color="#ffffff" />
              <v:textbox inset="0,0,0,0">
            <![endif]-->
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      
                    </td>
                  </tr>
                </table>
              </center>
              <!--[if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your account has been permanently deleted from the Dashboard.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please contact '.$commentBy.' if you have any questions.</p>
             
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
			
	//delete user
	$deleteUser = "DELETE FROM `user` WHERE `userID` = '$thisUserID'";
	$deleteUser_result = mysqli_query($connection, $deleteUser) or die ("Query to get data from Team task failed: ".mysql_error());
	
	if ($deletedUsername == $_SESSION['username']) {
		session_destroy();
		header('Location: index.php');
	}
	else {
		
	}
			
			
		}
		
	}
	else {
		
	}
	
	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

}


if($type == 'deactivate')
{
	$setInactive = "UPDATE `user` SET `Member Status`='Inactive' WHERE `userID` = '$thisUserID'";
			$setInactive_result = mysqli_query($connection, $setInactive) or die ("Query to get data from Team task failed: ".mysql_error());
		
			$getUserInfo = "SELECT `First Name`, `Last Name`, `GroupID` FROM `user` JOIN `Group Membership` ON `user`.`userID` = `Group Membership`.`userID` WHERE `user`.`userID` = '$thisUserID'";
			$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("Query to get data from Team task failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUserInfo_result)) {
					$firstName2 =$row["First Name"];	
					$lastName2 =$row["Last Name"];
					$currentGroup =$row["GroupID"];
				}
			
			$removeFromGroup = "DELETE FROM `Group Membership` WHERE `userID`='$thisUserID'";
	$removeFromGroup_result = mysqli_query($connection, $removeFromGroup) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$removeSub = "DELETE FROM `Notification Subscription` WHERE `userID`='$thisUserID'";
	$removeSub_result = mysqli_query($connection, $removeSub) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$addRequestedGroup = "UPDATE `user` SET `Requested Group`='$currentGroup' WHERE `userID`='$thisUserID'";
	$addRequestedGroup_result = mysqli_query($connection, $addRequestedGroup) or die ("Query to get data from Team task failed: ".mysql_error());

	
	/////////// SENDING EMAIL TO DEACTIVATED USER ///////////	
		
	if (isset($thisUserID)) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
							
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "Dashboard Access Revoked.";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%" style="background: #ffffff;">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff"  style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
             
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              You have been removed from the Dashboard.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please contact '.$FN.' '.$LN.' if you have any questions.</p>
             
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
	
	
	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

if($type == 'reactivate')
{
	
$setActive = "UPDATE `user` SET `Member Status`='Active' WHERE `userID` = '$thisUserID'";
			$setActive_result = mysqli_query($connection, $setActive) or die ("Query to get data from Team task failed: ".mysql_error());
		
			$getUserInfo = "SELECT `First Name`, `Last Name`, `Requested Group` FROM `user` WHERE `userID` = '$thisUserID'";
			$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("Query to get data from Team task failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUserInfo_result)) {
					$firstName2 =$row["First Name"];	
					$lastName2 =$row["Last Name"];
					if ($row["Requested Group"]) {
					$requestedGroup =$row["Requested Group"];
					}
					else {
						//other
						$requestedGroup =10;
					}
			}
	
			
				
			$addToGroup = "INSERT INTO `Group Membership`(`GroupID`, `userID`) VALUES ('$requestedGroup','$thisUserID')";
	$addToGroup_result = mysqli_query($connection, $addToGroup) or die ("Query to get data from Team task failed: ".mysql_error());
	
	
	$removeRequestedGroup = "UPDATE `user` SET `Requested Group`=null WHERE `userID`='$thisUserID'";
	$removeRequestedGroup_result = mysqli_query($connection, $removeRequestedGroup) or die ("Query to get data from Team task failed: ".mysql_error());
				
				
	/////////// SENDING EMAIL TO NEW USER ///////////	
		
	if (isset($thisUserID)) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$userID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
							
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "Dashboard Access Granted.";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%" style="background:repeat-x url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) #ffffff;">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
           
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              You have been added the Dashboard! 
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please contact '.$FN.' '.$LN.' if you have any questions.</p>
             
             <a href="https://dashboard.coat.com/dashboard/" class="button">Login</a>
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}			
	
	
	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

if($type == 'delete')
{
	

			$getUserInfo = "SELECT `First Name`, `Last Name` FROM `user` WHERE `user`.`userID` = '$thisUserID'";
			$getUserInfo_result = mysqli_query($connection, $getUserInfo) or die ("Query to get data from Team task failed: ".mysql_error());
			while($row = mysqli_fetch_array($getUserInfo_result)) {
					$firstName2 =$row["First Name"];	
					$lastName2 =$row["Last Name"];
				}
			
			
			
			$deleteGroupMembership = "DELETE FROM `Group Membership` WHERE `userID` = '$thisUserID'";
	$deleteGroupMembership_result = mysqli_query($connection, $deleteGroupMembership) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$deleteNotificationSubscription = "DELETE FROM `Notification Subscription` WHERE `userID` = '$thisUserID'";
	$deleteNotificationSubscription_result = mysqli_query($connection, $deleteNotificationSubscription) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$deleteNotifications = "DELETE FROM `Notifications` WHERE `userID` = '$thisUserID'";
	$deleteNotifications_result = mysqli_query($connection, $deleteNotifications) or die ("Query to get data from Team task failed: ".mysql_error());
	
	//if notebook exists
	$query1 = "SELECT `NotebookID` FROM `Notebooks` WHERE `userID` ='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($query1_result)) {
			$notebookID=$row["NotebookID"];	
		}
	
	if (isset($notebookID)) {
		$deletePages = "DELETE FROM `Notebooks Pages` WHERE `NotebookID` = '$notebookID'";
		$deletePages_result = mysqli_query($connection, $deletePages) or die ("Query to get data from Team task failed: ".mysql_error());
		
		$deleteNotebooks = "DELETE FROM `Notebooks` WHERE `userID` = '$thisUserID'";
		$deleteNotebooks_result = mysqli_query($connection, $deleteNotebooks) or die ("Query to get data from Team task failed: ".mysql_error());

		
	}
	else {
		
	}

	
	$deleteProjectMembership = "DELETE FROM `Team Projects Member List` WHERE `userID` = '$thisUserID'";
	$deleteProjectMembership_result = mysqli_query($connection, $deleteProjectMembership) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$deleteTodoList = "DELETE FROM `Todo List` WHERE `userID` = '$thisUserID'";
	$deleteTodoList_result = mysqli_query($connection, $deleteTodoList) or die ("Query to get data from Team task failed: ".mysql_error());
		
			
	/////////// SENDING EMAIL TO DELETED USER ///////////	
		
	if (isset($thisUserID)) {
	
	//Getting requester info 
	$getRequesterUserID = "SELECT * FROM `user` WHERE `userID` = '$userID'";
	$getRequesterUserID_result = mysqli_query($connection, $getRequesterUserID) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequesterUserID_result->fetch_assoc()) {
        $commentBy = $row["First Name"].' '.$row["Last Name"];
	}
							
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$thisUserID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "Dashboard Account Deleted.";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
'.$emailCss.'
</head>

<body bgcolor="#f7f7f7">
<table align="center" cellpadding="0" cellspacing="0" class="container-for-gmail-android" width="100%">
  <tr>
    <td align="left" valign="top" width="100%" style="background: #ffffff;">
      <center>
      
        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" style="background-color:transparent">
          <tr>
            <td width="100%" height="80" valign="top" style="text-align: center; vertical-align:middle;">
            
              <center>
                <table cellpadding="0" cellspacing="0" width="600" class="w320">
                  <tr>
                    <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                      <a href="https://dashboard.coat.com/dashboard">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      
                    </td>
                  </tr>
                </table>
              </center>
              
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7;" class="content-padding">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td class="header-lg">
              Your account has been permanently deleted from the Dashboard.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please contact '.$FN.' '.$LN.' if you have any questions.</p>
             
              <br><br>
            </td>
          </tr>
          
        </table>
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
      <center>
        
      </center>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" width="100%" style="background-color: #f7f7f7; height: 100px;">
      <center>
        <table cellspacing="0" cellpadding="0" width="600" class="w320">
          <tr>
            <td style="padding: 25px 0 25px">
              DO NOT RESPOND TO THIS EMAIL!<br>If you are having any issues, please contact Candice Garrett @ <a href="mailto:candice.garrett@burlingtonstores.com">candice.garrett@burlingtonstores.com</a> directly.<br /><br />
            </td>
          </tr>
        </table>
      </center>
    </td>
  </tr>
</table>

</body>
</html>';
		mail($to, $subject, $message, $headers);

	
	
}
			
	//delete user
	$deleteUser = "DELETE FROM `user` WHERE `userID` = '$thisUserID'";
	$deleteUser_result = mysqli_query($connection, $deleteUser) or die ("Query to get data from Team task failed: ".mysql_error());
	
	if ($deletedUsername == $_SESSION['username']) {
		session_destroy();
		header('Location: index.php');
	}
	else {
		
	}
			
		
$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	


}

if($type == 'changeEmail')
{
	
	$newVal = $_POST['newVal'];
	
	$query1 = "UPDATE `user` SET `email`='$newVal' WHERE `userID`='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("Query to get data from Team Project failed: ".mysql_error());

	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

if($type == 'changeRole')
{
	
	$newVal = $_POST['newVal'];
	
	$query1 = "UPDATE `user` SET `Role`='$newVal' WHERE `userID`='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("Query to get data from Team Project failed: ".mysql_error());

	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

if($type == 'changeLevel')
{
	
	$newVal = $_POST['newVal'];
	
	$query1 = "UPDATE `user` SET `Level`='$newVal' WHERE `userID`='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("Query to get data from Team Project failed: ".mysql_error());

	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

if($type == 'changeGroup')
{
	
	$newVal = $_POST['newVal'];
	
	$query1 = "UPDATE `Group Membership` SET `GroupID`='$newVal' WHERE `userID`='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("Query to get data from Team Project failed: ".mysql_error());

	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

if($type == 'changeTitle')
{
	
	$newVal = addslashes($_POST['newVal']);
	
	$query1 = "UPDATE `user` SET `Title`='$newVal' WHERE `userID`='$thisUserID'";
	$query1_result = mysqli_query($connection, $query1) or die ("Query to get data from Team Project failed: ".mysql_error());

	$printBack = returnResults();
	
	////////////
	
		$result = ["printBack" => $printBack];

		header('Content-Type: application/json'); 
		echo json_encode($result);

	

}

?>