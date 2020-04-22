<?php
include_once( '../../header.php' );

$categoryID = $_GET[ "categoryID" ];

$query = "SELECT DISTINCT * FROM `Calendar Categories` WHERE `CalendarCategoryID`= '$categoryID'";
$query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $query_result ) ) {
  $categoryName = $row[ 'Category' ];

}

$deleteSub = "DELETE FROM `Notification Subscription` WHERE `userID` = '$userID' AND `CalendarCategoryID` = '$categoryID'";
$deleteSub_result = mysqli_query( $connection, $deleteSub )or die( "Query to get data from Team task failed: " . mysql_error() );


?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<?php echo $stylesjs ?> 

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --> 
<!-- WARNING: Respond.js doesn't work if you view the page via file:// --> 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]--> 

<script>


$(document).ready(function() {
	
});
</script>
</head>

<body>
<nav class="navbar navbar-default print_remove" style="background:#ffffff; border:none;">
  <div class="container-fluid">
    <?php include("../../templates/topNav.php") ?>
  </div>
  <!-- /.container-fluid --> 
</nav>
<div class="container-fluid">
  <div class="row">
    <?php include("../../templates/lhn.php") ?>
    <div class="col-sm-10" style="height: 100%;">
      <div class="row">
        <div class="col-sm-12">
          <?php include("../../templates/alerts.php") ?>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="whitebg" style="min-height: auto !important;">
            <div class="row">
              <div class="col-sm-12">
                <div class="header print_remove">
                  <h3>Content Calendar</h3>
                </div>
                <div class="col-sm-12 text-center">
                  <h1>You have successfully unsubscribed from<br>
                    <strong><?php echo $categoryName ?></strong> Content Calendar events.</h1>
                  <br>
                  <p class="text-center">You can opt back in or change other email settings from your profile.</p>
                  <br>
                  <a href="/dashboard/users/me.php" class="genericbtn noExpand" style="margin-right:10px;">My Profile</a> <a href="/dashboard/content-calendar/" class="genericbtn noExpand">Content Calendar</a> <br>
                  <br>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php echo $scripts?>
</body>
</html>