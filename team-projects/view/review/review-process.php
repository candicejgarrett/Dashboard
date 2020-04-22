<?php 
require('../../../connect.php');
require('../../../header.php');
require('../../../emailDependents.php');
include('../../../functions/global.php');

$type=$_POST['type'];
$reviewID=$_POST['reviewID'];
$projectID=$_POST['projectID'];

//getting review info
	//getting info
	$query4 = "SELECT `Tickets Review`.`userID`, `Tickets Review`.`Title`,`Type`,`Tickets Review`.`ProjectID` FROM `Tickets Review` JOIN `Team Projects` ON `Tickets Review`.`ProjectID` = `Team Projects`.`ProjectID` WHERE `ReviewID` ='$reviewID'";
	$query4_result = mysqli_query($connection, $query4) or die ("Query to get data from Team task failed: ".mysql_error());
	
while($row = $query4_result->fetch_assoc()) {
		$reviewCreator =$row["userID"];
		$reviewTitle =addslashes($row["Title"]);
		$reviewTitleNoInsert1 = $row["Title"];
		$reviewType =$row["Type"];
		$reviewProjectID =$row["ProjectID"];
	}
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
if ($type == "loadReview") {

	
	//getting all 
	$getAllDetails = "SELECT DISTINCT `Tickets Review`.`ReviewID`,`Tickets Review`.`Title` AS 'Review Title', `Tickets Review`.`userID` AS 'Owner UserID', `Tickets Review`.`ProjectID`, `Tickets Review`.`Type`, DATE_FORMAT(`Tickets Review`.`Date Created`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Date Created', DATE_FORMAT(`Tickets Review`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Due Date', `Tickets Review`.`Status`, `Tickets Review`.`Desktop Preview Image Link`, `Tickets Review`.`Mobile Preview Image Link`,`Team Projects`.`Title`,`Team Projects`.`URL To Use`,`Team Projects`.`Category`,`Team Projects`.`Status` AS 'Project Status',DATE_FORMAT(`Team Projects`.`Due Date`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Launch Date' FROM `Tickets Review` JOIN `Team Projects` ON `Tickets Review`.`ProjectID`=`Team Projects`.`ProjectID` WHERE `ReviewID`='$reviewID'";
	$getAllDetails_result = mysqli_query($connection, $getAllDetails) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $getAllDetails_result->fetch_assoc()) {
         $printProjectID = $row["ProjectID"];
		 if ($row["Title"] !== ''){
			 $printProjectTitle = $row["Title"];
		 }
		 else {
			 $printProjectTitle = "Deleted Project";
		 }
		 
		 
		 $printReviewTitle = $row["Review Title"];
		 $printDesktopImage = $row["Desktop Preview Image Link"]; 
		 $printMobileImage = $row["Mobile Preview Image Link"];
		 $printLaunchDate = $row["Launch Date"];
		 $printCategory = $row["Category"];
		 $printDueDate = $row["Due Date"];
		 $printType = $row["Type"];
		 $printStatus = $row["Status"];
		 $printURL = $row["URL"];
		 $printOwnerUserID = $row["Owner UserID"];
		 
		 if (isset($printDesktopImage)) {
			  //get image id of desktop
			 $getDesktopImage = "SELECT * FROM `Tickets Review Preview Images` WHERE `Preview Image Link`='$printDesktopImage' AND `ReviewID`='$reviewID' AND `Type`='Desktop' ORDER BY `ImageID` LIMIT 1";
			 $getDesktopImage_result = mysqli_query($connection, $getDesktopImage) or die ("Query to get data from Team Project failed: ".mysql_error());
			 
			 while($row = $getDesktopImage_result->fetch_assoc()) {
				  $printDesktopImageID = $row["ImageID"];
			 }
			 
			 $getDesktopMockupCount = "SELECT COUNT(`MarkUpID`) FROM `Tickets Review MarkUps` WHERE `ImageID` ='$printDesktopImageID'";
				$getDesktopMockupCount_result = mysqli_query($connection, $getDesktopMockupCount) or die ("Query to get data from Team task failed: ".mysql_error());
				while($row = $getDesktopMockupCount_result->fetch_assoc()) {
					
					$printDesktopMarkupCount = $row["COUNT(`MarkUpID`)"];
				}
			 
		
		 
		 }
		 else {
			 $printDesktopImageID ="";
			 $printDesktopMarkupCount =0;
		 }
		 if (isset($printMobileImage)) {
			  //get image id of desktop
			 $getMobileImage = "SELECT * FROM `Tickets Review Preview Images` WHERE `Preview Image Link`='$printMobileImage' AND `ReviewID`='$reviewID' AND `Type`='Mobile' ORDER BY `ImageID` LIMIT 1";
			 $getMobileImage_result = mysqli_query($connection, $getMobileImage) or die ("Query to get data from Team Project failed: ".mysql_error());
			 
			 while($row = $getMobileImage_result->fetch_assoc()) {
				  $printMobileImageID = $row["ImageID"];
			 }
			 
		
			 $getMobileMockupCount = "SELECT COUNT(`MarkUpID`) FROM `Tickets Review MarkUps` WHERE `ImageID` ='$printMobileImageID'";
				$getMobileMockupCount_result = mysqli_query($connection, $getMobileMockupCount) or die ("Query to get data from Team task failed: ".mysql_error());
				while($row = $getMobileMockupCount_result->fetch_assoc()) {
					
					$printMobileMarkupCount = $row["COUNT(`MarkUpID`)"];
				}
		 
		 }
		 else {
			 $printMobileImageID ="";
			 $printMobileMarkupCount =0;
			 $printMobileImage="";
		 }
		
	 }
	//end getting all
	
	//permissions
	
	if ($printOwnerUserID == $userID || $myRole === "Admin" || $myRole === "Editor") {
		$canUploadDesktop = '
		<div class="row">
		
<div class="col-sm-5">
<div class="formLabels">New Desktop Preview Image:</div> <input type="file" id="desktopPreviewImage" name="desktopPreviewImage[]">
<hr>
<button id="editMockupsDesktop" data-toggle="modal" mockType="Desktop" data-target="#editMockupsModal" class="editMockupLoad genericbtn noExpand pull-left" style="margin-bottom: 10px; margin-right:10px;">Edit Mockups</button>
<div id="viewMoreDesktopButton" class="pull-left"></div>
<br>
</div>
<div class="col-sm-1" style="padding-left: 0px;">
<button id="desktopUpload" class="upload pull-left" style="height: 40px !important;width: 40px !important; margin-left: 0px !important;margin-top: 20px;"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
</div>';
		$canUploadMobile = '<div class="row">
<div class="col-sm-5">
<div class="formLabels">New Mobile Preview Image:</div> <input type="file" id="mobilePreviewImage" name="mobilePreviewImage[]">
<hr>
<button id="editMockupsMobile" data-toggle="modal" mockType="Mobile" data-target="#editMockupsModal" class="editMockupLoad genericbtn noExpand pull-left" style="margin-bottom: 10px;margin-right:10px">Edit Mockups</button>
<div id="viewMoreMobileButton" class="pull-left"></div>
</div>
<div class="col-sm-1" style=" padding-left: 0px;">
<button id="mobileUpload" class="upload pull-left" style="height: 40px !important;width: 40px !important; margin-left: 0px !important;margin-top: 20px;"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
</div>';
		$canEditReviewers = '<button class="smallSend" style="background:#ff0000 !important" id="deleteReviewer"><i class="fa fa-trash" aria-hidden="true"></i></button><button class="smallSend" id="addReviewer"><i class="fa fa-plus" aria-hidden="true"></i></button><button class="smallSend" style="background-color: #3fb34f !important;" id="sendReminder"><i class="fa fa-bell" aria-hidden="true"></i></button>';
		
		$canEditComments = '<button class="deleteReviewComment"><i class="fa fa-trash" aria-hidden="true"></i></button>';
		
$canSendEmailUpdate = '<div class="genericbtn" data-toggle="modal" data-target="#sendEmailUpdateModal" style="margin-right:10px;padding: 6px 18px;font-size: 12px;text-transform: uppercase;height:auto">Send Email Update</div>';
		
$canMarkApproved = '<div class="genericbtn green_bg" style="margin-right:10px;padding: 6px 18px;font-size: 12px;text-transform: uppercase;height:auto">Mark Approved</div>';
		
	if ($printStatus == "Not Approved") {
		$markAs = '<div class="genericbtn approvalBtn" style="margin-right:10px;padding: 6px 18px;font-size: 12px;text-transform: uppercase;background:#3fb34f" id="markApproved">Mark As Approved</div>';
	}
	else{
		$markAs = '<div class="genericbtn approvalBtn" style="margin-right:10px;padding: 6px 18px;font-size: 12px;text-transform: uppercase;background:#ff0000" id="markNotApproved">Mark As Not Approved</div>';	
		}

	}
	else {
		$canUploadDesktop="";
$canUploadMobile = "";
$canEditReviewers = "";
		$canSendEmailUpdate ="";
		$canMarkApproved ="";
		$markAs = '';	
	}
	
	
	//getting membership list 
	$getMembershipList = "SELECT `user`.`userID`,`PP Link`,`username`,`First Name`,`Status` FROM `Tickets Review Members` JOIN `user` ON `user`.`userID` = `Tickets Review Members`.`userID` WHERE `ReviewID` = '$reviewID' ORDER BY `MemberID` ASC";
	
	$getMembershipList_result = mysqli_query($connection, $getMembershipList) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMembershipList_result->fetch_assoc()) {
		$memberIDArray[] = $row["userID"];
		$memberUserID = $row["userID"];
		$memberFN = $row["First Name"];
		$memberPic = $row["PP Link"];
		$memberStatus = $row["Status"];
		$memberUsername = $row["username"];
		$memberApproval = '<div class="revNotApproved" style="color:#707070"><i class="fa fa-question-circle" aria-hidden="true"></i></div>';
		if ($memberStatus == "Approved") {
				$memberApproval = '<div class="revApproved"><i class="fa fa-check-circle" aria-hidden="true"></i></div>';
			}
			else {
				$memberApproval = '<div class="revNotApproved" style="color:#707070"><i class="fa fa-question-circle" aria-hidden="true"></i></div>';
			}

		
		$printMembers[] = "<div class='reviewers'><div class='revNotApproved deleteReviewer' userid='$memberUserID' style='margin-left:51px;display:none;cursor:pointer'><i class='fa fa-minus-circle' aria-hidden='true'></i></div>$memberApproval<img src='".$memberPic."'/><p>@".$memberUsername."</p></div>";  
		
	
		
	}
	
	$getMembershipListExcludingYou = "SELECT `user`.`userID`,`PP Link`,`username`,`First Name`,`Status` FROM `Tickets Review Members` JOIN `user` ON `user`.`userID` = `Tickets Review Members`.`userID` WHERE `ReviewID` = '$reviewID' AND `user`.`userID` != '$userID' ORDER BY `MemberID` ASC";
	
	$getMembershipListExcludingYou_result = mysqli_query($connection, $getMembershipListExcludingYou) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getMembershipListExcludingYou_result->fetch_assoc()) {
		$memberIDArray[] = $row["userID"];
		$memberUserID = $row["userID"];
		$memberFN = $row["First Name"];
		$memberPic = $row["PP Link"];
		$memberStatus = $row["Status"];
		$memberUsername = $row["username"];
		
		$mentionUsers[] = "
		<div class='userTags' userid='$memberUserID'>
		$memberUsername
		</div>";
		
	}
	
	$getComments = "SELECT `CommentID`, `Tickets Review Comments`.`ReviewID`, `Comment`, DATE_FORMAT(`Timestamp`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Timestamp', `username`, `PP Link` FROM `Tickets Review Comments` JOIN `user` ON `Tickets Review Comments`.`userID`= `user`.`userID` WHERE `Tickets Review Comments`.`ReviewID` = '$reviewID' ORDER BY `CommentID` DESC";
	
	$getComments_result = mysqli_query($connection, $getComments) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $getComments_result->fetch_assoc()) {
		$commentID = $row["CommentID"];
		$printComment = $row["Comment"];
		
		$timestamp = $row["Timestamp"];
		$from = '@'.$row["username"];
		$pic = "<img src='".$row["PP Link"]."' class='profilePicMessages'>";
		
		if ($row["Status"] =="Not Approved") {
			$printStatus = "";
		}
		else {
			$printStatus = "<span style='font-weight:bold;color:#20c200'>".$row["Status"]."</span>";
		}
		$printComments[] = "<div class='row' style='border-bottom:1px solid #eaeaea'><div class='col-sm-12'><div class='reviewComment' reviewCommentID='$commentID'>$canEditComments<table width='100%' border='0' cellspacing='0' cellpadding='10'>
  <tbody>
  	<tr><td colspan='2'></td></tr>
    <tr>
      <td width='60' valign='top'>$pic</td>
      <td>
	  <div class='from'>".$from."</div><div class='timestamp' style='display: inline;float: right;text-align:right'>".$timestamp."<br>$printStatus</div><br><p>".$printComment."</p></td>
    </tr>
  </tbody>
</table></div></div></div>";
	}
	
	$getAllImages = "SELECT * FROM `Tickets Review Preview Images` WHERE `ReviewID` = '$reviewID' AND `Type` = 'Desktop' ORDER BY `ImageID` DESC";
	$getAllImages_result = mysqli_query($connection, $getAllImages) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	$count = mysqli_num_rows($getAllImages_result);	
	
	if ($count > 1) {
		$viewMoreDesktop = "
		<button id='viewMoreDesktop' data-toggle='modal' data-target='#imageComparison' class='genericbtn noExpand pull-right'>View Older Versions</button>";
		while($row = $getAllImages_result->fetch_assoc()) {
			$printOtherDesktopImages[] = "";
		}
	}
	else {
		$viewMoreDesktop = "";
	}
	
	$getAllImagesMobile = "SELECT * FROM `Tickets Review Preview Images` WHERE `ReviewID` = '$reviewID' AND `Type` = 'Mobile' ORDER BY `ImageID` DESC";
	$getAllImagesMobile_result = mysqli_query($connection, $getAllImagesMobile) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	$count = mysqli_num_rows($getAllImagesMobile_result);	
	
	if ($count > 1) {
		$viewMoreMobile = "<button id='viewMoreMobile' data-toggle='modal' data-target='#imageComparisonMobile' class='genericbtn noExpand pull-right'>View Older Versions</button>";
		while($row = $getAllImagesMobile_result->fetch_assoc()) {
			$printOtherMobileImages[] = "";
		}
	}
	else {
		$viewMoreMobile = "";
	}
	
	//getting right button for approvals
	
	//getting membership list 
	$checkUserStatus = "SELECT `Tickets Review Members`.`userID`,`Status` FROM `Tickets Review Members` WHERE `ReviewID` = '$reviewID' AND `userID`='$userID'";
	
	$checkUserStatus_result = mysqli_query($connection, $checkUserStatus) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());
	
	while($row = $checkUserStatus_result->fetch_assoc()) {
		
		if (!isset($row["Status"]) || $row["Status"]==="Not Approved") {
			$userMarkAsButton = '<button class="genericbtn noExpand pull-right" id="userMarkApproved" style="margin-bottom:20px;background: #3fb34f;margin-right:20px;">Mark Approved</button>';
		}
		else {
			$userMarkAsButton = '<button class="genericbtn noExpand pull-right" id="userMarkNotApproved" style="margin-bottom:20px;background: #ff0000;margin-right:20px;">Disapprove</button>';
		}
		
	}
	
////////////
	
	$none ="";
	
	$results = ["printProjectTitle" => $printProjectTitle,
				"printProjectID" => $printProjectID,
				"printReviewTitle" => $printReviewTitle,
				"printDueDate" => $printDueDate, 
				"printCategory" => $printCategory, 
				"printDesktopImage" => $printDesktopImage, 
				"printMobileImage" => $printMobileImage, 
				"printMembers" => $printMembers,
				"mentionUsers" => $mentionUsers,
				"printStatus" => $printStatus,
				"printURL" => $printURL, 
				"printType" => $printType,
				"printComments" => $printComments,
				"viewMoreDesktop" => $viewMoreDesktop,
				"viewMoreMobile" => $viewMoreMobile,
				"canUploadDesktop" => $canUploadDesktop,
				"canUploadMobile" => $canUploadMobile,
				"canEditReviewers" => $canEditReviewers,
				"canMarkApproved" => $canMarkApproved,
				"canSendEmailUpdate" => $canSendEmailUpdate,
				"markAs" => $markAs,
				"userMarkAsButton" => $userMarkAsButton,
				"printDesktopImageID" => $printDesktopImageID,
				"printMobileImageID" => $printMobileImageID,
				"printDesktopMarkupCount" => $printDesktopMarkupCount,
				"printMobileMarkupCount" => $printMobileMarkupCount
	];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);

	
	
	
	
		

}
if ($type == "loadMarkUps") {
	$imageID=$_POST['imageID'];
	
		 //get markups
			$getMarkUps = "SELECT `markUpID`, `ReviewID`, `ImageID`, `Tickets Review MarkUps`.`userID`, `markUp`, `xPos`, `yPos`, DATE_FORMAT(`Timestamp`, '%a, %b. %e, %Y @ %h:%i %p') AS 'Timestamp', `First Name`, `Last Name` FROM `Tickets Review MarkUps` JOIN `user` ON `user`.`userID`=`Tickets Review MarkUps`.`userID` WHERE `ImageID` = '$imageID'";
			$getMarkUps_result = mysqli_query($connection, $getMarkUps) or die ("NEWWWW Query to get data from Team Project failed: ".mysql_error());	
		 	 while($row = $getMarkUps_result->fetch_assoc()) {
				  $markUpID = $row["markUpID"];
				 $markUpUserID = $row["userID"];
				 $markUp = $row["markUp"];
				 $markUpTimestamp = $row["Timestamp"];
				 $markUpXPos = $row["xPos"];
				 $markUpYPos = $row["yPos"];
				 $ownerName = $row["First Name"].' '.$row["Last Name"];
				 $markUpInitials = substr($row["First Name"], 0, 1).substr($row["Last Name"], 0, 1);
				 //if owner
				 if ($userID == $markUpUserID){
					 $printMarkUps[] = '<span userid="'.$userID.'" markupid="'.$markUpID.'" class="mark" style="top: '.$markUpXPos.'%; left: '.$markUpYPos.'%;">'.$markUpInitials.'</span>
					 <div class="commentHere" markupid_controller="'.$markUpID.'" style="top: '.$markUpXPos.'%; left: '.$markUpYPos.'%;"">
					 <div class="timestamp"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$markUpTimestamp.'</div>
					 <hr>
					 <textarea>'.$markUp.'</textarea>
					 
					 <div class="smallSend deleteMarkup"><i class="fa fa-times" aria-hidden="true"></i></div><div class="smallSend saveMarkup"><i class="fa fa-check" aria-hidden="true"></i></div></div>';
				 }
				 else {
					 $printMarkUps[] = '<span userid="'.$userID.'" markupid="'.$markUpID.'" class="mark" style="top: '.$markUpXPos.'%; left: '.$markUpYPos.'%;">'.$markUpInitials.'</span>
					 <div class="commentHere" markupid_controller="'.$markUpID.'" style="top: '.$markUpXPos.'%; left: '.$markUpYPos.'%;"">
					 <div class="sender">'.$ownerName.'</div>
					 <p>'.$markUp.'</p>
					 <div class="timestamp"><i class="fa fa-clock-o" aria-hidden="true"></i> '.$markUpTimestamp.'</div>
					 </div>';
				 }
				 
				 $printDisplayMarkUps[] = '<div class="displayMarkup" markupid='.$markUpID.'><div>'.$markUpInitials.'</div><p>'.$markUp.'</p></div>';
				 
			 }
	////////////
	
	$none ="";
	
	$results = ["printMarkUps" => $printMarkUps,
				"printDisplayMarkUps" => $printDisplayMarkUps];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);

}
if ($type == "getUsernames") {
	$searchTerm = $_POST["typedUsername"];
	
	$foundUsernames = getUserTagsNotInReview($searchTerm,$reviewID);
	
	////////////
	$results = ["foundUsernames" => $foundUsernames];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);
}
if ($type=="newReviewCheckUsername") {
	$reviewer = $_POST['username'];
	$query56 = "SELECT * FROM `user` WHERE `username`='$reviewer'";
	$query56_result = mysqli_query($connection, $query56) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query56_result)) {
		$reviewerEmail = $row["email"];
		$reviewerID = $row["userID"];
	}
	
	if (!isset($reviewerID)) {
		echo "User does not exist.";
		exit;
	}
	else {
		
	}
}
if ($type=="newReview") {
	$reviewType=$_POST['reviewType'];
	$reviewTitle=addslashes($_POST['title']);
	$reviewTitleNoInsert = $_POST['title'];
	$dueDate=date("Y-m-d H:i:s",strtotime($_POST['dueDate']));
	
	$members = json_decode($_POST['members'], true);
	$desktopFile=$_FILES['desktopFile'];
	$mobileFile=$_FILES['mobileFile'];
	
	addReview($projectID,$reviewType,$reviewTitle,$reviewTitleNoInsert,$dueDate,$members,$desktopFile,$mobileFile);

	
}
if ($type=="newReviewDesktopImage") {
	
	$desktopFile=$_FILES['desktopFile'];
	
	addReviewImage("Desktop", $desktopFile, $reviewID);

}
if ($type=="newReviewMobileImage") {
	
	$mobileFile=$_FILES['mobileFile'];
	
	addReviewImage("Mobile", $mobileFile, $reviewID);
	
}
if($type == 'updateReviewTitle'){
	$reviewTitle = addslashes($_POST['reviewTitle']);
	
	updateReviewTitle($reviewID,$reviewTitle);
	
}
if ($type=="newReviewer") {
	$memberUserID = $_POST['newUserID'];
	
	addReviewMember($memberUserID, $reviewID);	
}
if ($type=="deleteReviewer") {
	$memberUserID = $_POST['userID'];
	
	deleteReviewMember($memberUserID, $reviewID);	
	
	
}
if ($type=="addComment") {	
	$comment = $_POST['comment'];
	$mentionUserIDs = json_decode($_POST['mentionUserIDs'], true);
	
	addReviewComment($comment, $reviewID, $mentionUserIDs);
}
if ($type=="userMarkApproved") {
	userApprovedReview($reviewID);
}
if ($type=="userMarkNotApproved") {
	userNotApprovedReview($reviewID);
}
if ($type=="addMarkUp") {
	
	$markUp = $_POST['markUp'];
	$xPos = $_POST['xPos'];
	$yPos = $_POST['yPos'];
	$imageID = $_POST['imageID'];
	$whichImage = $_POST['whichImage'];
	
	addReviewMockupMarkup($reviewID,$markUp, $xPos, $yPos, $imageID, $whichImage);
	

}
if ($type=="updateMarkUp") {
	
	$markUp = $_POST['markUp'];
	
	$markUpID = $_POST['markUpID'];
	$whichImage = $_POST['whichImage'];
	
	updateReviewMockupMarkup($reviewID,$markUpID, $markUp, $whichImage);
	
	
	
	

}
if ($type=="deleteMarkUp") {
	$markUpID = $_POST['markUpID'];
	
	deleteReviewMockupMarkup($markUpID);
	
}
if ($type=="viewMoreDesktop") {
	$query2 = "SELECT * FROM `Tickets Review` WHERE `ReviewID`='$reviewID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query2_result)) {
		$desktopCurrent = '<img src="'.$row["Desktop Preview Image Link"].'" style="width:100%;">';
	}
	
	$query3 = "SELECT `Preview Image Link`,DATE_FORMAT(`Timestamp`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Timestamp' FROM `Tickets Review Preview Images` WHERE `ReviewID`='$reviewID' AND `Type` ='Desktop' AND `ImageID` != (SELECT MAX(`ImageID`) FROM `Tickets Review Preview Images`) ORDER BY `ImageID` DESC";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query3_result)) {
		
		$img = $row["Preview Image Link"];
		$timestamp = $row["Timestamp"];
		
		$desktopPrevious[] = '<div class="col-sm-12">
			<p class="text-center showImage" style="font-weight:bold;">Uploaded: '.$timestamp.'</p>
		  <img src="'.$img.'" style="width:100%;border:1px solid #eaeaea" class="image">
		</div>';
	}
	
	$results = ["desktopCurrent" => $desktopCurrent,
				"desktopPrevious" => $desktopPrevious];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);

}
if ($type=="viewMoreMobile") {
	$query2 = "SELECT * FROM `Tickets Review` WHERE `ReviewID`='$reviewID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query2_result)) {
		$mobileCurrent = '<img src="'.$row["Mobile Preview Image Link"].'" style="width:100%;">';
	}
	
	$query3 = "SELECT `Preview Image Link`,DATE_FORMAT(`Timestamp`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Timestamp' FROM `Tickets Review Preview Images` WHERE `ReviewID`='$reviewID' AND `Type` ='Mobile' AND `ImageID` != (SELECT MAX(`ImageID`) FROM `Tickets Review Preview Images`) ORDER BY `ImageID` DESC";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query3_result)) {
		
		$img = $row["Preview Image Link"];
		$timestamp = $row["Timestamp"];
		
		$mobilePrevious[] = '<div class="col-sm-12">
			<p class="text-center showImage" style="font-weight:bold;">Uploaded: '.$timestamp.'</p>
		  <img src="'.$img.'" style="width:100%;border:1px solid #eaeaea" class="image">
		</div>';
	}
	
	$results = ["mobileCurrent" => $mobileCurrent,
				"mobilePrevious" => $mobilePrevious];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);

}
if ($type=="editMockupsLoad") {
	
	$mockType = $_POST['mockType'];
	
	$query2 = "SELECT `$mockType Preview Image Link`, DATE_FORMAT(`Timestamp`, '%W, %b. %e, %Y @ %h:%i %p') AS 'Timestamp',`ImageID` FROM `Tickets Review` JOIN `Tickets Review Preview Images` ON `Tickets Review`.`$mockType Preview Image Link` = `Tickets Review Preview Images`.`Preview Image Link` WHERE `Tickets Review`.`ReviewID`='$reviewID' AND `Tickets Review Preview Images`.`Type`='$mockType' ORDER BY `Timestamp` DESC LIMIT 1";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query2_result)) {
		if (isset($row["$mockType Preview Image Link"])) {
			$printActiveMockup = str_replace(' ', '%20',$row["$mockType Preview Image Link"]);
			$printActiveMockupImageID = $row["ImageID"];
		}
		else {
			$printActiveMockup = "";
			$printActiveMockupImageID = "";
		}
		
	}
	
	$query3 = "SELECT `Preview Image Link`,DATE_FORMAT(`Timestamp`, '%a, %b. %e, %Y @ %h:%i %p') AS 'Timestamp',`ImageID` FROM `Tickets Review Preview Images` WHERE `ReviewID`='$reviewID' AND `Type` ='$mockType' ORDER BY `TIMESTAMP` DESC";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query3_result)) {
		
		$img = $row["Preview Image Link"];
		$timestamp = $row["Timestamp"];
		$imageID = $row["ImageID"];
		
		if (isset($img)) {
			$printPreviousMockups[] = '<div class="col-sm-4">
			<div class="prevMockups">
			<p class="text-center" style="font-weight:bold;">'.$timestamp.'</p>
		  <img src="'.$img.'" imageid="'.$imageID.'">
		  </div>
		</div>';
		}
		else {
			$printPreviousMockups = "";
		}
		
		
	}
	
	$results = ["printActiveMockup" => $printActiveMockup,
				"printPreviousMockups" => $printPreviousMockups,
			   "printActiveMockupImageID" => $printActiveMockupImageID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);

}
if ($type=="deleteMockup") {
	$imageID = $_POST['imageID'];
	$mockType = $_POST['mockType'];
	
	$query = "SELECT `Preview Image Link` FROM `Tickets Review Preview Images` WHERE `ImageID` = '$imageID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query_result)) {
		$removePath = $row["Preview Image Link"];
		unlink($removePath);
	}
	
	$query2 = "DELETE FROM `Tickets Review Preview Images` WHERE `ImageID`='$imageID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	$query3 = "SELECT `ImageID`, `Preview Image Link`, `Type` FROM `Tickets Review Preview Images` WHERE `ReviewID` = '$reviewID' AND `Type` = '$mockType' ORDER BY `Timestamp` ASC LIMIT 1";
	$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query3_result)) {
		
		$path = $row["Preview Image Link"];
			
		
		
	}
	
	if (isset($path)) {
			$update = mysqli_query($connection,"UPDATE `Tickets Review` SET `$mockType Preview Image Link`='$path' WHERE `ReviewID`='$reviewID'");
			
		}
		else {
			$update = mysqli_query($connection,"UPDATE `Tickets Review` SET `$mockType Preview Image Link`=NULL WHERE `ReviewID`='$reviewID'");
		}
	
	
	
	
	
}
if ($type=="updateMockup") {
	$imageID = $_POST['imageID'];
	$mockType = $_POST['mockType'];
	
	$query = "SELECT `Preview Image Link` FROM `Tickets Review Preview Images` WHERE `ImageID` = '$imageID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query_result)) {
		$updatePath = $row["Preview Image Link"];
	}
	
	$update = mysqli_query($connection,"UPDATE `Tickets Review` SET `$mockType Preview Image Link`='$updatePath' WHERE `ReviewID`='$reviewID'");
	
	$query2 = "SELECT * FROM `Tickets Review` WHERE `ReviewID`='$reviewID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
	while ($row = mysqli_fetch_array($query2_result)) {
		$reviewType = $row["Type"];
		$reviewTitle = addslashes($row["Title"]);
	}
}
if ($type=="nudgeUsers") {
	nudgeReviewUsers($reviewID);

}
if ($type=="deleteReviewComment") {
	$commentID = $_POST['commentID'];
	
	$query2 = "DELETE FROM `Tickets Review Comments` WHERE `CommentID`='$commentID'";
	$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
	
}
if ($type=="sendUpdateEmail") {
	$mockupType=$_POST['mockupType'];
	sendReviewMockupUpdateEmail($reviewID,$mockupType);
}
if ($type=="deleteReview") {
	deleteReview($reviewID);
}
if ($type=="markApproved") {
	markAllReviewApproved($reviewID);
}
if ($type=="editReview") {
	
	$reviewInfo = getReviewInfo($reviewID);
	
	$reviewTitleNoInsert = $reviewInfo["title"];
	$reviewDueDate = date("Y-m-d\TH:i:s", strtotime($reviewInfo["dueDate"]));
	$reviewTypeTitle = $reviewInfo["Type"];
	$reviewOwnerUserID = $reviewInfo["ownerUserID"];
	$reviewOwnerFullName = $reviewInfo["ownerFullName"];
	$reviewOwnerPP = $reviewInfo["ownerPP"];
	
	////////
	
	$result = ["reviewTitle" => $reviewTitleNoInsert,
				"reviewDueDate" => $reviewDueDate,
				"reviewType" => $reviewTypeTitle,
			   "reviewOwnerUserID" => $reviewOwnerUserID,
				"reviewCreatedByFullName" => $reviewOwnerFullName,
				"reviewCreatedByPP" => $reviewOwnerPP
	];
			   
	header('Content-Type: application/json'); 
	echo json_encode($result);
	
}

if ($type=="updateReview") {
	
	$reviewTitle=$_POST['title'];
	$reviewType=$_POST['reviewType'];
	$reviewDueDate=$_POST['duedate'];
	
	
	
	updateReview($reviewID,$reviewTitle,$reviewType,$reviewDueDate);
}

?>