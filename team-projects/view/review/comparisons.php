<?php 

require('../../../connect.php');
$type=$_POST['type'];
$reviewID=$_POST['reviewID'];
$projectID=$_POST['projectID'];

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

?>