<?php 
include_once('../header.php');
require('../connect.php');

$type = $_POST["type"];
$taskID = $_POST["taskID"];

	$getTaskInfo = "SELECT `Tasks`.`Title`, `Tasks`.`Description`, DATE_FORMAT(`Tasks`.`Due Date`, '%b %e, %Y @ %l:%i %p') AS 'Due Date', `Tasks`.`Status`, `Task Categories`.`Category`,`Tasks`.`ProjectID`, `Team Projects`.`Title` AS 'Project Title', DATE_FORMAT(`Team Projects`.`Due Date`, '%b %e, %Y @ %l:%i %p') AS 'Project Due Date', `First Name`, `Last Name`,`Tasks`.`userID`,`Tasks`.`Requested By`,`Team Projects`.`Task Type` FROM `Tasks` JOIN `user` ON `user`.`userID` = `Tasks`.`Requested By` JOIN `Task Categories` ON `Task Categories`.`categoryID` = `Tasks`.`Category` JOIN `Team Projects` ON `Tasks`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `TaskID` = '$taskID'";
	$getTaskInfo_result = mysqli_query($connection, $getTaskInfo) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getTaskInfo_result)) {
		$projectName = $row["Project Title"];
		$taskRequestedBy = $row["First Name"]." ".$row["Last Name"];
		$taskTitle = $row["Title"];
		$taskAssignedToID = $row["userID"];
		$taskRequestedByID = $row["Requested By"];
		$taskDescription = $row["Description"];
		$taskDueDate = $row["Due Date"];
		$taskCategory = $row["Category"];
		$taskType = $row["Task Type"];
		$ProjectID = $row["ProjectID"];
	}

	
		$headers = "From: info@candicejgarrett.com\r\n";
		$headers .= "Reply-To: candice.garrett@burlingtonstores.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

if ($type == "newTask") {
	$MemberUserID = $_POST["memberUserID"];
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$MemberUserID' AND `userID` != '$userID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "A new task has been assigned to you in: ".$projectName.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .noteNew a {
		  color:#ffffff !important;
		  
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              A new task has been assigned to you in the project <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$projectMemberFN.'</strong>,<br>the following task has been assigned to you.
              <br><br>
             <div class="task"><p class="pull-left"> <strong>Due Date: </strong>'.$taskDueDate.'</p>
				 <p class="pull-right"> <strong>Category: </strong>'.$taskCategory.'</p>
             <br><br><h2>'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$taskRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p></div>
             
             <a href="https://candicejgarrett.com/dashboard/team-projects/display.php?projectSelector1='.$ProjectID.'" class="button">View Project</a>
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

if ($type == "InReview") {
	$taskComment = $_POST["taskComment"];
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$taskRequestedByID' AND `userID` != '$userID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$getAssignedTo = "SELECT * FROM `user` WHERE `userID` = '$taskAssignedToID'";
	$getAssignedTo_result = mysqli_query($connection, $getAssignedTo) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getAssignedTo_result)) {
		$taskAssignedTo = $row["First Name"]." ".$row["Last Name"];
	}
	
	
	
	$subject = "PENDING APPROVAL: Task - ".$taskTitle.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              Your assigned task has been submitted for review in <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$projectMemberFN.'</strong>,<br>the following task needs to be reviewed.
              <br><br>
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Assigned To: '.$taskAssignedTo.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$taskComment.'</div>
             <br>
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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

if ($type == "Approved") {
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$taskAssignedToID' AND `userID` != '$userID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "APPROVED: Task - ".$taskTitle.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              Your task has been approved in <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$taskRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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

if ($type == "Kickback") {
	$taskComment = $_POST["taskComment"];
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$taskAssignedToID' AND `userID` != '$userID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$subject = "KICKBACK: Task - ".$taskTitle.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              Your task has been rejected in <span style="text-decoration: underline">'.$projectName.'</span>.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$taskRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             <hr>
             <h3>Comment</h3>
             <div class="noteNew">'.$taskComment.'</div>
             <br>
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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

if ($type == "Completed") {
	
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$taskRequestedByID' AND `userID` != '$userID'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	$getAssignedTo = "SELECT * FROM `user` WHERE `userID` = '$taskAssignedToID'";
	$getAssignedTo_result = mysqli_query($connection, $getAssignedTo) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getAssignedTo_result)) {
		$taskAssignedTo = $row["First Name"]." ".$row["Last Name"];
	}
	
	
	
	$subject = "COMPLETED: Task - ".$taskTitle.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              Your assigned task has been completed in <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Assigned To: '.$taskAssignedTo.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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

	if ($taskType == "Cadence") {
			$query = "SELECT `Tasks`.`userID`,`Tasks`.`Title`,`user`.`email`,`user`.`PP Link` FROM `Tasks` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` WHERE `TaskID` > '$taskID' ORDER BY `TaskID` LIMIT 1";
			$query_result = mysqli_query($connection, $query) or die ("NEw Query to get data from Team Project failed: ".mysql_error());
			while($row = $query_result->fetch_assoc()) {
				$nextUser = $row["userID"];	
				$nextTask = $row["Title"];
				$nextMember = $row["email"];
				$nextMemberPP =$row["PP Link"];
			}
									  
			$subject2 = "COMPLETED: Task - ".$taskTitle.". Your task is due next.";
	
		$to2 = $nextMember;
		$message2 = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$nextMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              The previous task has been completed in <span style="text-decoration: underline">'.$projectName.'</span>. Your task  is due next.
            </td>
          </tr>
          <tr>
            <td class="free-text">
             
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Assigned To: '.$taskAssignedTo.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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
		mail($to2, $subject2, $message2, $headers);						  
	}
	
}

if ($type == "newTaskCommentAssignedTo") {
	$taskComment = $_POST["comment"];
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$taskAssignedToID' AND `email` != '$Email'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	
	
	$getLatestCommentInfo = "SELECT `First Name`,`Last Name`,DATE_FORMAT(`Timestamp`, '%b %e, %Y @ %l:%i %p') AS 'Timestamp' FROM `Task Comments` JOIN `user` ON `Task Comments`.`Sent By` = `user`.`userID` WHERE `TaskID` = '$taskID' AND `Task Comments`.`userID` = '$taskAssignedToID' ORDER BY `CommentID` DESC LIMIT 1";
	$getLatestCommentInfo_result = mysqli_query($connection, $getLatestCommentInfo) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getLatestCommentInfo_result)) {
		$commentorName =$row["First Name"]." ".$row["Last Name"];
		$taskCommentTimestamp = $row["Timestamp"];
	}
	
	$subject = "NEW COMMENT: Task - ".$taskTitle.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .noteNew a {
		  color:#ffffff !important;
		  
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              A new comment has been added to your task: <strong>'.$taskTitle.'</strong> in <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Comment By: '.$commentorName.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             <hr>
             <h3>Comment</h3>
             <div class="timestamp">'.$taskCommentTimestamp.'</div>
             <div class="noteNew">'.$taskComment.'</div>
             <br>
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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

if ($type == "newTaskCommentRequestedBy") {
	$taskComment = $_POST["comment"];
	//getting members
	$getProjectMember = "SELECT * FROM `user` WHERE `userID` = '$taskRequestedByID' AND `email` != '$Email'";
	$getProjectMember_result = mysqli_query($connection, $getProjectMember) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getProjectMember_result)) {
		$projectMember =$row["email"];
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
	}
	
	
	
	
	$getLatestCommentInfo = "SELECT `First Name`,`Last Name`,DATE_FORMAT(`Timestamp`, '%b %e, %Y @ %l:%i %p') AS 'Timestamp' FROM `Task Comments` JOIN `user` ON `Task Comments`.`Sent By` = `user`.`userID` WHERE `TaskID` = '$taskID' AND `Task Comments`.`userID` = '$taskAssignedToID' ORDER BY `CommentID` DESC LIMIT 1";
	$getLatestCommentInfo_result = mysqli_query($connection, $getLatestCommentInfo) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getLatestCommentInfo_result)) {
		$commentorName =$row["First Name"]." ".$row["Last Name"];
		$taskCommentTimestamp = $row["Timestamp"];
	}
	
	$subject = "NEW COMMENT: Task - ".$taskTitle.".";
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .noteNew a {
		  color:#ffffff !important;
		  
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              A new comment has been added to your assigned task: <strong>'.$taskTitle.'</strong> in <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              
             <div class="task"><p class="pull-left" style="line-height:23px;"> <strong>Due Date: </strong><br>'.$taskDueDate.'</p>
				 <p class="pull-right" style="line-height:23px;"> <strong>Category: </strong><br>'.$taskCategory.'</p>
             <br><br><h2 style="clear:both">'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Comment By: '.$commentorName.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p>
             <hr>
             <h3>Comment</h3>
             <div class="timestamp">'.$taskCommentTimestamp.'</div>
             <div class="noteNew">'.$taskComment.'</div>
             <br>
             </div>
            
             <a href="https://candicejgarrett.com/dashboard/todo/calendar.php?eventID='.$taskID.'" class="button">View Task</a>
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

if ($type == "newTaskByTemplate") {
	$ProjectID = $_POST["projectID"];
	
	$getTaskMembers = "SELECT `TaskID` FROM `Tasks` WHERE `ProjectID` = '$ProjectID'";
	$getTaskMembers_result = mysqli_query($connection, $getTaskMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getTaskMembers_result)) {
		$taskIDs[] =$row["TaskID"];
	}
	
	$subject = "A new task has been assigned to you in: ".$projectName.".";
	
	foreach($taskIDs as $taskID){
		
		//getting members
	$getOwner = "SELECT `Tasks`.`userID`,`First Name`,`PP Link`, `email` FROM `Tasks` JOIN `user` ON `user`.`userID` = `Tasks`.`userID` WHERE `TaskID` = '$taskID' AND `userID` != '$userID'";
	$getOwner_result = mysqli_query($connection, $getOwner) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getOwner_result)) {
		$projectMemberFN = $row["First Name"];
		$projectMemberPP = $row["PP Link"];
		$projectMember = $row["email"];
	}
		
	$getTaskInfo2 = "SELECT `Tasks`.`Title`, `Tasks`.`Description`, DATE_FORMAT(`Tasks`.`Due Date`, '%b %e, %Y @ %l:%i %p') AS 'Due Date', `Tasks`.`Status`, `Task Categories`.`Category`,`Tasks`.`ProjectID`, `Team Projects`.`Title` AS 'Project Title', DATE_FORMAT(`Team Projects`.`Due Date`, '%b %e, %Y @ %l:%i %p') AS 'Project Due Date', `First Name`, `Last Name`,`Tasks`.`userID`,`Tasks`.`Requested By` FROM `Tasks` JOIN `user` ON `user`.`userID` = `Tasks`.`Requested By` JOIN `Task Categories` ON `Task Categories`.`categoryID` = `Tasks`.`Category` JOIN `Team Projects` ON `Tasks`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `TaskID` = '$taskID'";
	$getTaskInfo2_result = mysqli_query($connection, $getTaskInfo2) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());
	while($row = mysqli_fetch_array($getTaskInfo2_result)) {
		$projectName = $row["Project Title"];
		$taskRequestedBy = $row["First Name"]." ".$row["Last Name"];
		$taskTitle = $row["Title"];
		$taskAssignedToID = $row["userID"];
		$taskRequestedByID = $row["Requested By"];
		$taskDescription = $row["Description"];
		$taskDueDate = $row["Due Date"];
		$taskCategory = $row["Category"];
	}
	
	
	
		$to = $projectMember;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
  <style type="text/css">
	  @import "https://fonts.googleapis.com/css?family=Roboto:300,400,700,900";
    /* Take care of image borders and formatting, client hacks */
    img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
    a img { border: none; }
    table { border-collapse: collapse !important;}
    #outlook a { padding:0; }
    .ReadMsgBody { width: 100%; }
    .ExternalClass { width: 100%; }
    .backgroundTable { margin: 0 auto; padding: 0; width: 100% !important; }
    table td { border-collapse: collapse; }
    .ExternalClass * { line-height: 115%; }
    .container-for-gmail-android { min-width: 600px; }


    /* General styling */
    * {
      font-family: "Quicksand", sans-serif !important;
    }

    body {
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: none;
      width: 100% !important;
      margin: 0 !important;
      height: 100%;
      color: #676767;
    }

    td {
      font-family: "Quicksand", sans-serif !important;
      font-size: 14px;
      color: #777777;
      text-align: center;
      line-height: 21px;
    }

    a {
      color: #676767;
      text-decoration: none !important;
    }

    .pull-left {
      text-align: left;
		float:left;
    }

    .pull-right {
      text-align: right;
		float:right;
    }
	  .pp {
		  width:30px;
		  height:30px;
		  border-radius: 50%;
	  }
	  .gradient {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
	  }
	  .noteNew {
		   background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  border-radius:25px;
		  padding:10px;
		  font-size:16px;
	  }
	  .noteNew a {
		  color:#ffffff !important;
		  
	  }
	  .task {
		   background: #fff !important;
		  color:#333 !important;
		  
		  padding:10px;
		  font-size:16px;
		  border:1px solid #eaeaea;
		  margin:20px 0px
	  }
	  .task p {
		  margin:0px;
		  text-align: left;font-size:16px !important;
	  }
	  
	  .timestamp {
		  font-size:14px; text-align: right;
		  color:#333;
		  font-style: italic;
		  line-height: 20px;
		  padding-bottom: 10px !important;
	  }
	  .button {
		 background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important;
		  color:#ffffff !important;
		  text-transform: uppercase;
		  padding:5px 10px !important;
		  clear: both;
		  display:block;
		  font-size:14px;
		  margin:0 auto;
		  width:200px;
	  }

    .header-lg,
    .header-md,
    .header-sm {
      font-size: 32px;
      font-weight: 700;
      line-height: normal;
      padding: 35px 0 0;
      color: #4d4d4d;
    }

    .header-md {
      font-size: 24px;
    }

    .header-sm {
      padding: 5px 0;
      font-size: 18px;
      line-height: 1.3;
    }

    .content-padding {
      padding: 20px 0 5px;
    }

    .mobile-header-padding-right {
      width: 290px;
      text-align: right;
      padding-left: 10px;
    }

    .mobile-header-padding-left {
      width: 290px;
      text-align: left;
      padding-left: 10px;
    }

    .free-text {
      width: 100% !important;
          font-size: 25px;
    line-height: 35px;
    padding: 15px 0px;
    }

    .button {
      padding: 30px 0;
    }
	  
	  .allbutton {
		background-color:#71b0ff;border-radius:3px;color:#ffffff;display:inline-block;font-family:"Cabin", Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;line-height:45px;text-align:center;text-decoration:none;width:155px;-webkit-text-size-adjust:none;mso-hide:all;  
	  }

    .mini-block {
      border: 1px solid #e5e5e5;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 12px 15px 15px;
      text-align: center;
      width: 253px;
    }

    .mini-container-left {
      width: 278px;
      padding: 10px 0 10px 15px;
    }

    .mini-container-right {
      width: 278px;
      padding: 10px 14px 10px 15px;
    }

    .product {
      text-align: left;
      vertical-align: top;
      width: 175px;
    }

    .total-space {
      padding-bottom: 8px;
      display: inline-block;
    }

    .item-table {
      padding: 50px 20px;
      width: 560px;
    }

    .item {
      width: 300px;
    }

    .mobile-hide-img {
      text-align: left;
      width: 125px;
    }

    .mobile-hide-img img {
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }

    .title-dark {
      text-align: left;
      border-bottom: 1px solid #cccccc;
      color: #4d4d4d;
      font-weight: 700;
      padding-bottom: 5px;
    }

    .item-col {
      padding-top: 20px;
      text-align: left;
      vertical-align: top;
    }

    .force-width-gmail {
      min-width:600px;
      height: 0px !important;
      line-height: 1px !important;
      font-size: 1px !important;
    }

  </style>

  <style type="text/css" media="screen">
    @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
  </style>

  <style type="text/css" media="screen">
    @media screen {
      /* Thanks Outlook 2013! */
      * {
        font-family: "Oxygen", "Helvetica Neue", "Arial", "sans-serif" !important;
      }
    }
  </style>

  <style type="text/css" media="only screen and (max-width: 480px)">
    /* Mobile styles */
    @media only screen and (max-width: 480px) {

      table[class*="container-for-gmail-android"] {
        min-width: 290px !important;
        width: 100% !important;
      }

      img[class="force-width-gmail"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
      }

      table[class="w320"] {
        width: 320px !important;
      }

      td[class*="mobile-header-padding-left"] {
        width: 160px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-header-padding-right"] {
        width: 160px !important;
        padding-right: 0 !important;
      }

      td[class="header-lg"] {
        font-size: 24px !important;
        padding-bottom: 5px !important;
      }

      td[class="content-padding"] {
        padding: 5px 0 5px !important;
      }

       td[class="button"] {
        padding: 5px 5px 30px !important;
      }

      td[class*="free-text"] {
        padding: 10px 18px 30px !important;
      }

      td[class~="mobile-hide-img"] {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        line-height: 0 !important;
      }

      td[class~="item"] {
        width: 140px !important;
        vertical-align: top !important;
      }

      td[class~="quantity"] {
        width: 50px !important;
      }

      td[class~="price"] {
        width: 90px !important;
      }

      td[class="item-table"] {
        padding: 30px 20px !important;
      }

      td[class="mini-container-left"],
      td[class="mini-container-right"] {
        padding: 0 15px 15px !important;
        display: block !important;
        width: 290px !important;
      }

    }
	  
	 
  </style>
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
                      <a href="https://candicejgarrett.com/dashboard/">Dashboard</a>
                    </td>
                    <td align="right" class="pull-right mobile-header-padding-right" style="color: #4d4d4d;">
                      <a href="https://candicejgarrett.com/dashboard/users/me.php"><table width="110" border="0" cellspacing="0" cellpadding="1" style="float:right">
					  <tbody>
						<tr>
						  <td align="right" width="30px"><img src="https://candicejgarrett.com'.$projectMemberPP.'" class="pp"></td>
						  <td align="right"><a href="https://candicejgarrett.com/me.php">My Profile</a></td>
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
              A new task has been assigned to you in the project <span style="text-decoration: underline">'.$projectName.'</span>!
            </td>
          </tr>
          <tr>
            <td class="free-text">
              Hi <strong>'.$projectMemberFN.'</strong>,<br>the following task has been assigned to you.
              <br><br>
             <div class="task"><p class="pull-left"> <strong>Due Date: </strong>'.$taskDueDate.'</p>
				 <p class="pull-right"> <strong>Category: </strong>'.$taskCategory.'</p>
             <br><br><h2>'.$taskTitle.'<br><span style="font-weight:300; font-style: italic;font-size:14px;color:#4801ff">Requested By: '.$taskRequestedBy.'</span></h2>
             <p><strong> Description: </strong><br>'.$taskDescription.'<br>
             </p></div>
             
             <a href="https://candicejgarrett.com/dashboard/team-projects/display.php?projectSelector1='.$ProjectID.'" class="button">View Project</a>
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



?>
