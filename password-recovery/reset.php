<?php  //Start the Session

 require('../connect.php');

//email headers 
$headers = "From: no-reply@dashboard.coat.com/dashboard\r\n";
$headers .= "Reply-To: candice.garrett@burlingtonstores.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

// Was the form submitted?
if (isset($_POST["ResetPasswordForm"]))
{
	// Gather the post data
	$email = $_POST["email"];
	$password = $_POST["password"];
	$confirmpassword = $_POST["passwordConfirm"];
	$hash = $_POST["q"];

	// Use the same salt from the forgot_password.php file
	$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

	// Generate the reset key
	$resetkey = hash('sha512', $salt.$email);

	// Does the new reset key match the old one?
	if ($resetkey == $hash)
	{
		
		if ($password==$confirmpassword) {
				if (strlen($password) <= '8') {
					$message = "Your password must contain at least 8 characters.<br><br>";
				}
				elseif(!preg_match("#[0-9]+#",$password)) {
					$message = "Your password must contain at least 1 number!<br><br>";
				}
				else {
					$finalPassword = password_hash($password, PASSWORD_DEFAULT);
					$updatePassword = "UPDATE `user` SET `password`='$finalPassword' WHERE `email`='$email'";
					$updatePassword_result = mysqli_query($connection, $updatePassword) or die(mysqli_error($connection));
					$message= "Your password has been changed.<br>";
					
					// Mail them success mesage
		$mailbody = "Hello,<br><br>Your password has been successfully reset for the Dashboard: https://dashboard.coat.com/dashboard/. If you did not make this change, please contact <a href='mailto:candice.garrett@burlingtonstores.com'>Candice Garrett</a> immediately.<br><br>" . $pwrurl . "<br><br>Thanks,<br> Administration - Candice Garrett";
		
		mail($email, "Dashboard - Password Reset Successful", $mailbody, $headers);
					
				}
			
				
				
			}
		else
			$message= "Your passwords do not match.<br><br>";
	}
	else
		$message= "Your password reset key is invalid.";
}

?>
<html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Reset Password</title>
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

    <style>
		body {    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);}
		@media only screen and (max-width: 728px) {
	#pending {
width:95% !important;
}
	</style>
     
     <script>
		
	</script>

      </head>

    <body>
    

<div class="container">

<div class="col-lg-12 text-center" style="height: 100%;"><center>
	<div class="whitebg min_height" id="pending" style="width: 400px;border-radius:0px;margin-top:15%">
    	 			<div class="header">
					<center><h1>Dashboard</h1></center>
					</div>
 	 		 
 	 		 		<center>
					<p style="color:#ff0000;text-align:center;font-weight:bold;" id="wrong">	
					<?php echo $message;?></p>
	
						<a href="javascript:history.back()" class="genericbtn" style="margin-right:0px !important;"><em>Back</em></a>		
						<?php 
						
						if (strpos($message, 'changed') !== false) {
								echo '<a href="/dashboard" class="genericbtn" style="margin-right:0px !important;"><em>Login</em></a>';
						}
						
						?>
						
 	 		 		</center>	
  	 		 
   	 		  </div>
   	 		  </center>
   	</div>
	
</div>    

        
         
           
    <script>
		
</script> 

    </body>
</html>

   
