<?php
session_start();
require( '../connect.php' );
$username = $_SESSION[ 'username' ];

$query2 = "SELECT `user`.`userID`, `username`, `email`, `password`, `First Name`, `Last Name`, `Role`, `Title`, `PP Link`, `Member Status`, `Requested Group`, `Groups`.`Group Name` FROM `user` JOIN `Group Membership` ON `user`.`userID`=`Group Membership`.`userID`  JOIN `Groups` ON `Groups`.`GroupID`=`Group Membership`.`GroupID` WHERE `username` = '$username'";

$result2 = mysqli_query( $connection, $query2 )or die( mysqli_error( $connection ) );

while ( $row = $result2->fetch_assoc() ) {
  $userID = $row[ "userID" ];
  $myRole = $row[ "Role" ];
  $FN = $row[ "First Name" ];
  $LN = $row[ "Last Name" ];
  $Title = $row[ "Title" ];
  $Email = $row[ "email" ];
  $ProfilePic = $row[ "PP Link" ];
  $groupName = $row[ "Group Name" ];

}
if ( !isset( $_SESSION[ 'username' ] ) ) {
  header( "location:/dashboard" );
  die();
} else {

  if ( isset( $_POST[ 'password' ] ) ) {

    $password = $_POST[ 'password' ];

    //3.1.2 Checking the values are existing in the database or not
    $query = "SELECT * FROM `user` WHERE `username` = '$username'";

    $result = mysqli_query( $connection, $query )or die( mysqli_error( $connection ) );
    $count = mysqli_num_rows( $result );
    while ( $row = $result->fetch_assoc() ) {
      $currentUserID = $row[ "userID" ];
      $passwordHash = $row[ "password" ];
    }
    if ( $count == 1 && password_verify( $password, $passwordHash ) ) {

      $_SESSION[ 'start' ] = time();
      $redirect_url = ( isset( $_SESSION[ 'redirect_url' ] ) ) ? $_SESSION[ 'redirect_url' ] : '/';
      unset( $_SESSION[ 'redirect_url' ] );
      header( "Location: $redirect_url", true, 303 );
      //header("location:index.php"); 
      $wrong = "";
      exit;
    } else {

      $wrong = "<em>Your password is incorrect. Please try again.</em><br><br>";

    }
  }
}


?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<?php echo $stylesjs ?> <?php echo $scripts ?> 
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
	</script>
<style>
body {
    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);
}
</style>
<script>
		 $("#pending").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
	</script>
</head>

<body>
<div class="container">
  <div class="col-lg-12 text-center" style="height: 100%;">
    <div id="pending" style="border-radius:0px; color:#ffffff !important;margin-top:15%">
      <center>
        <h1 style="color:#ffffff;">Dashboard</h1>
        <h4 style="color:#ffffff;"><em>Your session has timed out. Please enter your password to log back in.</em></h4>
        <br>
        <img class="profilePic group_<?php echo $groupName?>" src="<?php echo $ProfilePic?>" style="width: 200px !important;height: 200px;"><strong><?php echo $FN?> <?php echo $LN?></strong><br>
        <em><?php echo $Title?></em><br>
        <em style="font-size:12px;"><i class="fa fa-users" aria-hidden="true"></i> <?php echo $groupName?> Team</em><br>
        <br>
        <p style="color:#ffffff;text-align: center;">
          <?php  echo $wrong;?>
        </p>
        <form class="login" id="loginform" name="login" method="post">
          <input type="password" id="password" name="password" style="background:#ffffff !important;color:#000 !important">
          <br>
          <p style="color:#ff0000;text-align:center;font-weight:bold;" id="wrong"></p>
          <button id="login" class="genericbtn" style="margin-right:0px !important;background:#ffffff !important;color:#333 !important;">Login</button>
        </form>
      </center>
    </div>
  </div>
</div>
<script>
		
</script>
</body>
</html>
