<?php
include_once( '../header.php' );
require( '../connect.php' );

if ( $myRole != 'Admin' ) {
  header( "location:/dashboard/404/no-access.php" );
}
?>
<html class="x-template-settings">
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

</head>

<body>
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
    <?php include("../templates/topNav.php") ?>
  </div>
  <!-- /.container-fluid --> 
</nav>
<div class="container-fluid">
  <div class="row">
    <?php include("../templates/lhn.php") ?>
    <div class="col-sm-10" style="height: 100%;">
      <div class="row">
        <div class="col-sm-12">
          <?php include("../templates/alerts.php") ?>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="whitebg">
            <div class="header">
              <h3>Dashboard Settings</h3>
              <p></p>
            </div>
            <div class="row">
              <div class="col-sm-10 col-xs-offset-1 removeOffset"> <br>
                <br>
                <div class="row">
                  <div class="col-sm-4">
                    <div class="primaryGradientBG settingsIcon"> <a href="send-alert">
                      <h3>Send Out<br>
                        Alert</h3>
                      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> </a> </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="primaryGradientBG settingsIcon"> <a href="category-management">
                      <h3>Group/Category<br>
                        Management</h3>
                      <i class="fa fa-tasks" aria-hidden="true"></i> </a> </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="primaryGradientBG settingsIcon"> <a href="bandaids">
                      <h3>Code<br>
                        Bandaids</h3>
                      <i class="fa fa-wrench" aria-hidden="true"></i> </a> </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="primaryGradientBG settingsIcon"> <a href="user-management/">
                      <h3>User<br>
                        Management</h3>
                      <i class="fa fa-users" aria-hidden="true"></i> </a> </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="primaryGradientBG settingsIcon"> <a href="project-management/">
                      <h3>Project<br>
                        Management</h3>
                      <i class="fa fa-database" aria-hidden="true"></i> </a> </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="primaryGradientBG settingsIcon"> <a href="review-management/">
                      <h3>Review<br>
                        Management</h3>
                      <i class="fa fa-check-circle" aria-hidden="true"></i> </a> </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="primaryGradientBG settingsIcon"> <a href="activities-management/">
                      <h3>Activities<br>
                        Management</h3>
                      <i class="fa fa-star" aria-hidden="true"></i> </a> </div>
                  </div>
                </div>
                <br>
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