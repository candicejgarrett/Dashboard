<?php
include_once( '../../header.php' );

$teamName = addslashes( $_GET[ 'team' ] );
?>
<html class="x-template-users">
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
	
	var teamName = "<?php echo $teamName?>";
		function loadTeamProfile() {
			var dataString = {'type':"loadTeamProfile",'teamName':teamName};
				
				$.ajax({
					type: "POST",
					url: "process.php",
					data: dataString,
					dataType: 'json',
					cache: false,
					success: function(results){
						$("#printTeamName").html(results.printTeamName + " Team").fadeIn();
						$("#printTotalMembers").html(results.printTotalMembers).fadeIn();
						$("#printOpenProjects").html(results.printOpenProjects).fadeIn();
						$("#printCompletedProjects").html(results.printCompletedProjects).fadeIn();
						$("#printTotalProjects").html(results.printTotalProjects).fadeIn();
						$("#printMembers").html(results.printMembers).fadeIn();
						$("#printProjects").html(results.printProjects).fadeIn();
					
						$("#loadingPageSpinner").parent().removeClass("whitebg");
						$("#loadingPageSpinner").hide();
						$("#loadingPage").fadeIn();
					}
				});
		}
		loadTeamProfile();
});
</script>
<style>
.gradient {
    padding: 20px 0px;
}
</style>
</head>

<body>
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
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
      <div class="whitebg">
        <div id="loadingPageSpinner">
          <center>
            <img src="/dashboard/images/grey-spinner.gif">
          </center>
        </div>
        <div id="loadingPage">
          <div class="row">
            <div class="col-sm-12">
              <div class="whitebg">
                <div class="header">
                  <h3 id="printTeamName"></h3>
                </div>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="gradient text-center">
                      <h3 class="text-center" style="margin-top: 5px;">Total<br>
                        Members</h3>
                      <h1 class="myProfileCompleted" id="printTotalMembers"></h1>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="gradient text-center">
                      <h3 class="text-center" style="margin-top: 5px;">Open<br>
                        Projects</h3>
                      <h1 class="myProfileCompleted" id="printOpenProjects"></h1>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="gradient text-center">
                      <h3 class="text-center" style="margin-top: 5px;">Completed<br>
                        Projects</h3>
                      <h1 class="myProfileCompleted" id="printCompletedProjects"></h1>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="gradient text-center">
                      <h3 class="text-center" style="margin-top: 5px;">Total<br>
                        Projects</h3>
                      <h1 class="myProfileCompleted" id="printTotalProjects"></h1>
                    </div>
                  </div>
                </div>
                <br>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="whitebg">
                <div class="header">
                  <h3>Team Members</h3>
                </div>
                <div class="row" style="overflow: scroll;padding-bottom: 5px;height:469px;" id="printMembers"> </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="whitebg">
                <div class="header">
                  <h3>Projects</h3>
                </div>
                <div class="row" style="padding-bottom:25px;">
                  <table class="projectTable" style="margin:0px 20px;">
                    <tr class="header-text">
                      <td style="width:50%;padding:5px 0px;border:none !important;">Title</td>
                      <td style="width:50%;padding:5px 0px;border:none !important;">Status</td>
                    </tr>
                  </table>
                  <div class="general-scroll" style="padding: 0px 20px;">
                    <table class="projectTable" id="printProjects">
                    </table>
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
</div>
<?php echo $scripts?>
</body>
</html>