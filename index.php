<?php //Start the Session
session_start();
require( 'connect.php' );
$returnURL = $_GET[ 'returnURL' ];


//3.1 If the form is submitted
if ( isset( $_POST[ 'username' ] )and isset( $_POST[ 'password' ] ) ) {
  //3.1.1 Assigning posted values to variables.
  $username = $_POST[ 'username' ];
  $password = $_POST[ 'password' ];

  //3.1.2 Checking the values are existing in the database or not
  $query = "SELECT * FROM `user` WHERE username='$username' AND `Member Status` = 'Active'";

  $result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
  $count = mysqli_num_rows( $result );
  while ( $row = $result->fetch_assoc() ) {
    $currentUserID = $row[ "userID" ];
    $passwordHash = $row[ "password" ];

  }
  //3.1.2 If the posted values are equal to the database values, then session will be created for the user.
  if ( $count == 1 && password_verify( $password, $passwordHash ) ) {
    $_SESSION[ 'username' ] = $username;
    $username = $_SESSION[ 'username' ];
    $_SESSION[ 'start' ] = time();
    $_SESSION[ 'expire' ] = 5000;

    $currentDate = date( "Y-m-d" );

  } else {
    //3.1.3 If the login credentials doesn't match, he will be shown with an error message.

    $wrong = "<em>Your username/password combination is incorrect. Please try again.</em>";

  }
}
//3.1.4 if the user is logged in Greets the user with message
if ( isset( $_SESSION[ 'username' ] ) ) {

  if ( isset( $returnURL ) ) {
    header( 'Location: ' . $returnURL );
  } else {
    header( 'Location: home/' );
  }
} else {}
//3.2 When the user visits the page first time, simple login form will be displayed.
?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Login</title>
<?php echo $stylesjs ?> 
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --> 
<!-- WARNING: Respond.js doesn't work if you view the page via file:// --> 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
body {
    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);
}
@media only screen and (max-width: 728px) {
#pending {
    width: 95% !important;
}
</style>
<script>
		$(document).ready(function() {
			$(".whitebg").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
			
			$("#forgotPassword").on('click', function() {
				
				$("#pleaseContact").fadeToggle();
			});
		});
	</script>
</head>

<body>
<div class="container">
  <div class="col-lg-12 text-center" style="height: 100%;">
    <center>
      <div class="whitebg min_height" id="pending" style="width: 400px;border-radius:0px;margin-top:15%">
        <div class="header">
          <center>
            <h1>Dashboard</h1>
          </center>
        </div>
        <center>
          <form class="login" id="loginform" name="login" method="post">
            <input type="text" id="username" placeholder="Username" name="username">
            <input type="password" id="password" placeholder="Password" name="password" autocomplete="on">
            <br>
            <p style="color:#ff0000;text-align:center;font-weight:bold;" id="wrong"><?php echo $wrong;?></p>
            <button id="login" class="genericbtn" style="margin-right:0px !important;">Login</button>
            <a href="sign-up.php" class="genericbtn" style="margin-right:0px !important;"><em>Sign Up</em></a>
          </form>
          <br>
          <a href="password-recovery/forgot-password.php">Forgot Password?</a>
        </center>
      </div>
    </center>
  </div>
</div>
<script>
		
</script>
</body>
</html>
