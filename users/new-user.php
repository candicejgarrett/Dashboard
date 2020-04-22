<?php
require('../connect.php');
require('../emailDependents.php');
//email headers 
$headers = "From: no-reply@dashboard.coat.com/dashboard\r\n";
$headers .= "Reply-To: candice.garrett@burlingtonstores.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$type = $_POST['type'];

if ($type == "newUser") {
	
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$title = addslashes($_POST['title']);
	$groupMembershipID = $_POST['groupMembershipID'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirmPassword = $_POST['confirmPassword'];
	
	//getting all usernames
	$getAllUsernames = "SELECT `username` FROM `user` WHERE `username`='$username'";
	$getAllUsernames_result = mysqli_query($connection, $getAllUsernames) or die(mysqli_error($connection));
	$usernameCount = mysqli_num_rows($getAllUsernames_result);
	
	//getting all emails
	$getAllEmails = "SELECT `email` FROM `user` WHERE `email`='$email'";
	$getAllEmails_result = mysqli_query($connection, $getAllEmails) or die(mysqli_error($connection));
	$emailCount = mysqli_num_rows($getAllEmails_result);
	
	
	if ($firstName==NULL || !preg_match("/^[a-zA-Z ]*$/",$firstName)) {
		$message = "A valid first name is required.";
		$approved ="No";
	}
	else if ($lastName==NULL || !preg_match("/^[a-zA-Z ]*$/",$lastName)) {
		$message = "A valid last name is required.";
		$approved ="No";
	}
	else if ($email==NULL || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = "A valid email is required.";
		$approved ="No";
	}
	else if ($emailCount != 0) {
		$message = "That email address is already in use. Click <a href='forgot-password.php'>here</a> to retrieve your password.";
		$approved ="No";
	}
	else if ($title==NULL) {
		$message = "A valid title is required.";
		$approved ="No";
	}
	else if ($usernameCount != 0) {
		$lastname = substr($lastname, 0, 3);
		$username = $firstName+$lastname;
	}
	
	else if ($username==NULL || !preg_match("/^[a-zA-Z ]*$/",$username)) {
		$message = "A valid username is required. Example: JaneD... No symbols or numbers.";
		$approved ="No";
	}
	else if ($password != $confirmPassword) {
		$message = "Your passwords do not match.";
		$approved ="No";
	}
	else if (strlen($confirmPassword) <= '8') {
		$message = "Your password must contain at least 8 characters.";
		$approved ="No";
	}
	else if(!preg_match("#[0-9]+#",$confirmPassword)) {
					$message = "Your password must contain at least 1 number.";
		$approved ="No";
	}
	else {
		
		$approved ="Yes";
		$message = "<center>Your membership access request has been received. If accepted, you will receive an email with further instructions. Thank you!</center>";
		$finalPassword = password_hash($confirmPassword, PASSWORD_DEFAULT);
		//inserting into user table
		$addMember = "INSERT INTO `user`(`username`, `email`, `password`, `First Name`, `Last Name`, `Title`, `Member Status`,`Requested Group`) VALUES ('$username','$email','$finalPassword','$firstName','$lastName','$title','Pending','$groupMembershipID')";
		$addMember_result = mysqli_query($connection, $addMember) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	//sending email to user requesting access
		
		if (isset($email)) {
		
		$subjectNewUser = "Dashboard Access Request Received.";
	
		$toNewUser = $email;
		$emailMessageNewUser = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
              <strong>Hi '.$firstName.' '.$lastName.'</strong>, your access request has been sent to Candice Garrett for approval.
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
		mail($toNewUser, $subjectNewUser, $emailMessageNewUser, $headers);
		
		}
		
		
		
	/////////// INSERTING NOTIFICATION ///////////
	//Getting super admins 
	$getGroupMembers = "SELECT * FROM `user` WHERE `Role` = 'Super Admin'";
	$getGroupMembers_result = mysqli_query($connection, $getGroupMembers) or die ("getGroupMembers_result to get data from Team Project failed: ".mysql_error());
		while($row = mysqli_fetch_array($getGroupMembers_result)) {
			$groupMembers[] =$row["userID"];
			$adminEmails[] =$row["email"];
		}
	
	foreach ($groupMembers as $name2) {
		$notification = "<a href=/dashboard/settings/user-management.php?><strong>$firstName $lastName</strong> requested access to the Dashboard.</a>";
	    $addNotification = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification','Users','$name2')";
        $addNotification_result = mysqli_query($connection, $addNotification) or die ("notifications to get data from Team Project failed: ".mysql_error());
	}
		
	
						
	//getting info
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '1'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
		
	$subject = "$firstName $lastName has requested access to the Dashboard.";
	
		$to = $projectMember;
		$emailMessage = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                      <a href="https://dashboard.coat.com/dashboard/users/my-profile/"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://dashboard.coat.com/'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://dashboard.coat.com/dashboard/users/my-profile/">My Profile</a></td>
						</tr>
					  </tbody>
					</table>
                      </a>
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
              <strong>'.$firstName.' '.$lastName.'</strong> has requested access to the Dashboard.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             <p>Please login to activate the user.</p>
             
             <a href="https://dashboard.coat.com/dashboard/settings/user-management.php" class="button">User Management</a>
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
		mail($to, $subject, $emailMessage, $headers);

	
	

	
	
	}
	
	
	
	
	
	//////////////
	
	$result = ["message" => $message,
			  "approved" => $approved];
	header('Content-Type: application/json'); 
	echo json_encode($result);
}

?>