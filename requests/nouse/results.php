<?php  //Start the Session
session_start();
 require('../connect.php');

$TicketID = $_POST["TicketID"];

	//getting ticket information
	$getTicketInfo = "SELECT `TicketID`, `Tickets`.`Title`, `Description`, `URL`, DATE_FORMAT(`Timestamp`, '%m/%d/%y'), DATE_FORMAT(`Due Date`, '%m/%d/%y'), `Contact Name`, `Contact Email`, `Owner`, `Status`, `ProjectID`, `Team Projects Categories`.`Category`,`First Name`,`Last Name`,`email` FROM `Tickets` JOIN `user` ON `Tickets`.`Owner` = `user`.`userID` JOIN `Team Projects Categories` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` WHERE `TicketID` = '$TicketID'";
	$getTicketInfo_result = mysqli_query($connection, $getTicketInfo) or die ("getProject ID Query to get data from Team Project failed: ".mysql_error());
	while($row = $getTicketInfo_result->fetch_assoc()) {
        $url = $row["URL"];
		$duedate = $row["DATE_FORMAT(`Due Date`, '%m/%d/%y')"];
		$projectid = $row["ProjectID"];
		$title = $row["Title"];
		$description = $row["Description"];
		$category = $row["Category"];
		$contactname = $row["Contact Name"];
		$contactemail = $row["Contact Email"];
		$timestamp = $row["DATE_FORMAT(`Timestamp`, '%m/%d/%y')"];
		$status = $row["Status"];
		$owner = $row["First Name"]." ".$row["Last Name"];
		$ownerEmail = $row["email"];
		$printDate = date("m/d/Y", strtotime($duedate));
		//getting ticket information
	
	 }
$getOpenTaskCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectid' AND `Status` != 'Completed'";
	$getOpenTaskCount_result = mysqli_query($connection, $getOpenTaskCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
        $row_count= $getOpenTaskCount_result->num_rows;
 		$row = $getOpenTaskCount_result->fetch_assoc();
		$openTasks =$row['COUNT(*)'];

$getCompletedTaskCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectid' AND `Status` = 'Completed'";
	$getCompletedTaskCount_result = mysqli_query($connection, $getCompletedTaskCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
        $row_count= $getCompletedTaskCount_result->num_rows;
 		$row = $getCompletedTaskCount_result->fetch_assoc();
		$completedTasks =$row['COUNT(*)'];


$getAllTaskCount = "SELECT COUNT(*) FROM `Tasks` WHERE `ProjectID` = '$projectid'";
	$getAllTaskCount_result = mysqli_query($connection, $getAllTaskCount) or die ("getProjectCount Query to get data from Team Project failed: ".mysql_error());
	
        $row_count= $getAllTaskCount_result->num_rows;
 		$row = $getAllTaskCount_result->fetch_assoc();
		$allTasks =$row['COUNT(*)'];
if ($allTasks == 0) {
	$finalPercentage = 0;
}
else {
	$finalPercentage = ($completedTasks/$allTasks)*100;
}



?>
   <html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dashboard/css/todo.css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/dashboard/css/spectrum.css">
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<script src="/dashboard/js/spectrum.js"></script>
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script src="/dashboard/js/highlight.js"></script>
<link rel="apple-touch-icon" sizes="57x57" href="/dashboard/images/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/dashboard/images/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/dashboard/images/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/dashboard/images/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/dashboard/images/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/dashboard/images/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/dashboard/images/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/dashboard/images/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/images/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/dashboard/images/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/dashboard/images/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/images/favicon/favicon-16x16.png">
<link rel="shortcut icon" href="/dashboard/images/favicon/favicon.ico" >
<link rel="manifest" href="/dashboard/images/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/dashboard/images/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
 <script>
		 $("#pending").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
	 
	 $(document).on('click','#more', function() {	
			$("#front").slideToggle();
		 	$(this).toggleClass("addWhite");
		 	$("#back").slideToggle();
		});
	</script>
    <style>
		
		.latestActivity {
			background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%) !important; 
			color:#ffffff !important;
			display:none;
			    max-height: 500px;
    overflow-y: scroll;
  } 
		.addWhite {color:#ffffff !important}
		#front {
			overflow: scroll;
    max-height: 700px;
		}
		
		#more {
			    z-index: 100;
			    font-size: 20px;
    padding: 10px;
			margin-left: 161px;
			position: absolute;
		}

		.leftField {
			    float: left;
    width: 49.5%;
			margin-right:1%;
		}
		.rightField {
			    float: right;
    width: 49.5%;
			
		}
		.feed-item p {
    color: #ffffff !important;
}
		
		</style>
      </head>

    <body>
    

<div class="container">
<br><br>
	<div class="min_height" id="pending" style="
   margin:0 auto;
    width: 60%;margin-top:5%;
">
    	 			
 	 		 		<center>
 	 		 		
 	 		 			<div class="ticket">
 	 		 			<i class="fa fa-info-circle pull-right" aria-hidden="true" id="more"></i>
 	 		 			<div class="latestActivity" id="back">
	 		 				<h3 class="text-center" style="color:#fff;">Latest Activity</h3>
	 		 				
	 		 				<?php 
							
							$query_result = mysqli_query($connection, "SELECT DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y'), `userID`, `Activity` FROM `Activity Feed` WHERE `ProjectID` = '$projectid' ORDER BY `Timestamp` DESC") or die ("getProject ID Query to get data from Team Project failed: ".mysql_error());
							echo '<ol class="activity-feed">';
							while($row = $query_result->fetch_assoc()) {
							
								$who = $row["userID"];
								$ProjectID = $row["ProjectID"];
								$Timestamp = $row["DATE_FORMAT(`Timestamp`, '%l:%i %p %b %e, %Y')"];
								$Activity = $row["Activity"];
								$getWho = "SELECT * FROM `user` WHERE `userID` = '$who'";
								$getWho_result = mysqli_query($connection, $getWho) or die ("getTasks_result to get data from Team Project failed: ".mysql_error());

								while($row3 = $getWho_result->fetch_assoc()) {	
								$WhoFN = $row3["username"];
								}
								
							echo "<li class='feed-item' style='    text-align: left;'><span class='date'>$Timestamp</span><span class='text'><span class='member'>@$WhoFN</span> $Activity</span></li>";
							}
							echo '</ol>';
							
							
							?>
	 		 			</div>
 	 		 			<div id="front">
 	 		 				

 	 		 				<h3 class="text-center">Ticket Summary</h3>
 	 		 				<p class="pull-right"><strong>Due Date:</strong><br><?php echo $printDate ?></p>
 	 		 				<p><strong>Ticket ID: <?php echo $TicketID ?> </strong><br></p>
 	 		 				<br>
 	 		 				<center>
 	 		 				<h1><span style="font-size:15px;">Status:</span><br><?php echo $status ?><br><strong style="font-size:70px"><?php echo $finalPercentage ?>%</strong></h1>
 	 		 				</center>
 	 		 				<br>
 	 		 				
 	 		 				
 	 		 				<p><strong>Date Created:</strong><br><?php echo $timestamp ?></p>
 	 		 				<p><strong>Category:</strong> <?php echo $category ?></p>
 	 		 				<p><strong>URL:</strong> <?php echo $url ?></p>
 	 		 				
 	 		 				<p><strong>Contact Name:</strong> <?php echo $contactname ?></p>
							<p><strong>Contact Email:</strong> <?php echo $contactemail ?></p>
							
	 		 			<p style="max-height:400px;overflow-y:scroll"><strong>Description:</strong><br><?php echo $description ?></p>
	 		 			
	 		 			
	 		 			<div class="contactbox">
	 		 			<p><?php echo $owner ?> will contact you with any changes to the ticket or if additional information is required. If you have any questions, please contact <?php echo $owner ?> by email: <a href="mailto:<?php echo $ownerEmail ?>"><?php echo $ownerEmail ?></a>.
	 		 			</div>
	 		 			</div>
	 		 			
 	 		 		</div>
	 		 			
 	 		 		</center>	
  	 		 <br>
   	 		  </div>
</div>    

        
         
           
    <script>
		
</script> 

    </body>
</html>
<?php  ?>