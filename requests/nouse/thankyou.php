<?php  //Start the Session

 require('../connect.php');

$name = $_POST["requestContactName"];
$email = $_POST["requestContactEmail"];
$url = $_POST["requestURL"];
$duedate = $_POST["requestDueDate"];
$title = $_POST["requestTitle"];
$description = $_POST["requestDescription"];
$copy = $_POST["requestCopy"];
$category = $_POST["requestCategory"];

$query = "SELECT DISTINCT `Team Projects Categories`.`Category` AS 'Category Name' FROM `Team Projects Categories` JOIN `Tickets` ON `Tickets`.`Category` = `Team Projects Categories`.`ProjectCategoryID` WHERE `Team Projects Categories`.`ProjectCategoryID` = '$category'";
$query_result = mysqli_query($connection, $query) or die ("getProject ID Query to get data from Team Project failed: ".mysql_error());
	while($row = $query_result->fetch_assoc()) {
        $categoryName = $row["Category Name"];
	 }

$printDate = date("m/d/Y", strtotime($duedate));;

//INSERTING
$addRequest = "INSERT INTO `Tickets`(`Title`, `Description`, `URL`, `Due Date`, `Contact Name`, `Contact Email`,`Owner`, `Category`, `Copy`) VALUES ('$title','$description','$url','$duedate','$name','$email','2','$category','$copy')";
$addRequest_result = mysqli_query($connection, $addRequest) or die(mysqli_error($connection));

$RequestID = $connection->insert_id;


	//getting ticket information
	$getRequestID = "SELECT * FROM `Tickets` JOIN `user` ON `Tickets`.`Owner` = `user`.`userID` WHERE `Tickets`.`TicketID` = '$RequestID'";
	$getRequestID_result = mysqli_query($connection, $getRequestID) or die ("getProject ID Query to get data from Team Project failed: ".mysql_error());
	while($row = $getRequestID_result->fetch_assoc()) {
        $owner = $row["First Name"]." ".$row["Last Name"];
		$ownerEmail = $row["email"];
	 }

//Getting team members
	$getTeamMembers = "SELECT * FROM `Group Membership` WHERE `GroupID` = '1'";
	$getTeamMembers_result = mysqli_query($connection, $getTeamMembers) or die ("getNotes_result to get data from Team Project failed: ".mysql_error());

	while($row = mysqli_fetch_array($getTeamMembers_result)) {
		$teamMembers[] =$row["userID"];	
	}

		foreach ($teamMembers as $name) {
			$notification2 = "<a href=/dashboard/requests/view.php?ticketID=$RequestID>A new ticket: <strong>$title</strong> has been submitted.</a>";
			$addNotification2 = "INSERT INTO `Notifications`(`Notification`, `Type`, `userID`) VALUES ('$notification2','Ticket','$name')";
			$addNotification2_result = mysqli_query($connection, $addNotification2) or die ("notifications to get data from Team Project failed: ".mysql_error());
		}

	/////////// INSERTING ACTIVITY IN ACTIVITY FEED ///////////
	$activity = "A new ticket has been added to the Dashboard: <strong>$title</strong>.";
	$addActivity = "INSERT INTO `Activity Feed`(`Activity`, `Type`, `userID`, `TicketID`) VALUES ('$activity','Ticket','0','$RequestID')";
	$addActivity_result = mysqli_query($connection, $addActivity) or die ("activity failed: ".mysql_error());	

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

	</script>
    <style>
		
		body {    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);}

		.leftField {
			    float: left;
    width: 49.5%;
			margin-right:1%;
		}
		.rightField {
			    float: right;
    width: 49.5%;
			
		}
		</style>
      </head>

    <body>
    

<div class="container">
<br><br>
	<div class="min_height" id="pending" style="
   margin:0 auto;
    width: 60%;
">
    	 			<div class="header">
					<center><h1 style="color:#ffffff !important;">Thank you!</h1>
					<p class="text-center" style="color:#ffffff !important;"><em>Please screenshot or print this page to keep the <strong>TICKET ID</strong> for your reference.</em></p></center>
					</div>
 	 		 
 	 		 		<center>
 	 		 		
 	 		 			<div class="ticket">
 	 		 				<h3 class="text-center">Ticket Summary</h3>
 	 		 				<p class="pull-right"><strong>Due Date:</strong><br><?php echo $printDate ?></p>
 	 		 				<p><strong>Ticket ID: <?php echo $RequestID ?> </strong><br></p>
 	 		 				
							<p><strong>Title:</strong> <?php echo $title ?></p>
							<p><strong>Category:</strong> <?php echo $categoryName ?></p>
	 		 			<p><strong>Description:</strong><br><?php echo $description ?></p>
	 		 			
	 		 			<div class="contactbox">
	 		 			<p><?php echo $owner ?> will contact you with any changes to the ticket or if additional information is required. If you have any questions, please contact <?php echo $owner ?> by email: <a href="mailto:<?php echo $ownerEmail ?>"><?php echo $ownerEmail ?></a>.
	 		 			</div>
 	 		 		</div>
	 		 		
 	 		 		</center>	
  	 		 <br>
   	 		  </div>
</div>    

        
         


    </body>
</html>
