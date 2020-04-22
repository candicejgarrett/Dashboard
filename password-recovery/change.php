<?php
 require('../connect.php');

//email headers 
$headers = "From: no-reply@dashboard.coat.com/dashboard\r\n";
$headers .= "Reply-To: candice.garrett@burlingtonstores.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

// Was the form submitted?
if (isset($_POST["ForgotPassword"])) {
	
	// Harvest submitted e-mail address
	$username = $_POST["username"];
	// Check to see if a user exists with this e-mail
	$checkEmail = "SELECT * FROM `user` WHERE `username` = '$username'";
	$checkEmail_result = mysqli_query($connection, $checkEmail) or die ("Query to get data from Team Project failed: ".mysql_error());
	while($row = $checkEmail_result->fetch_assoc()) {
        $EmailAd = $row["email"];
		$fullName = $row["First Name"].' '.$row["Last Name"];
	 }

	if ($EmailAd != '')
	{
		// Create a unique salt. This will never leave PHP unencrypted.
		$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

		// Create the unique user password reset key
		$password = hash('sha512', $salt.$EmailAd);

		// Create a url which we will direct them to reset their password
		$pwrurl = "https://dashboard.coat.com/dashboard/password-recovery/reset-password.php?q=".$password;
		
		// Mail them their key
		$mailbody = "Dear " . $fullName . ",<br><br>It appears that you have requested a password reset for the Dashboard: https://dashboard.coat.com/dashboard/.\n\nTo reset your password, please click the link below. If you cannot click it, please paste it into your web browser's address bar.<br><br>" . $pwrurl . "<br><br>Thanks,<br> Administration - Candice Garrett";
		
		mail($EmailAd, "Dashboard - Password Reset", $mailbody, $headers);
		
		
		$wrong= "Your password recovery key has been sent to your e-mail address.<br><br>";
		
	}
	else
		$wrong= "No user with that username exists.<br><br>";
}
?>

<html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Reset Password</title>
    <?php echo $stylesjs ?>
     <?php echo $scripts ?> 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

    <style>
		body {    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);}
		@media only screen and (max-width: 728px) {
	#pending {
width:95% !important;
}
	</style>
     
     <script>
		$(document).ready(function() {
			$(".whitebg").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
			
		});
	</script>

      </head>

    <body>
    

<div class="container">

<div class="col-lg-12 text-center" style="height: 100%;">
	<center>
	<div class="whitebg min_height" id="pending" style="width: 400px;border-radius:0px;margin-top:15%">
    	 			<div class="header">
					<center><h1>Dashboard</h1></center>
					</div>
 	 		 
 	 		 		<center>
						<p style="color:#ff0000;text-align:center;font-weight:bold;" id="wrong"><?php echo $wrong;?></p>	
					
					<a href="/dashboard" class="genericbtn" style="margin-right:0px !important;"><em>Login</em></a>		
 	 		 		<a href="sign-up.php" class="genericbtn" style="margin-right:0px !important;"><em>Sign Up</em></a>	
	 		 		
 	 		 		</center>	
  	 		 
   	 		  </div>
   	 		  </center>
   	</div>
	
</div>    

        
         
           
    <script>
		
</script> 

    </body>
</html>