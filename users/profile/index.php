<?php
require( '../../connect.php' );
require( '../../header.php' );
$viewingUserID = $_GET[ 'userID' ];
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
	$(document).ready(function(){
		
		 var viewingUserID = <?php echo $viewingUserID?>;
		
		function loadProfile() {
			var dataString = {'type':"loadProfile",'viewingUserID':viewingUserID};
				
				$.ajax({
					type: "POST",
					url: "process.php",
					data: dataString,
					dataType: 'json',
					cache: false,
					success: function(results){
						
						if (!results.printFirstName){
							$("#loadingPage").hide();
							$("#noUserPage").show();
							$("#loadingPageSpinner").hide();
							}
						else {
							$("#isOnline").html(results.online).fadeIn();
						$("#printName,#printName2").html(results.printFirstName + " "+ results.printLastName).fadeIn();
						$("#printProfilePic").attr("src",results.printProfilePicture).fadeIn();
						$("#printTitle").html(results.printTitle).fadeIn();
						$("#printGroup").html(results.printGroupName+" Team").css("background-color", results.printGroupColor).fadeIn();
						$("#printGroup").parent().attr("href","/dashboard/users/teams/?team="+results.printGroupName);
						$("#printRole").html(results.printRole).fadeIn();
						$("#printUsername").html(results.printUsername).fadeIn();
						$("#printEmail").attr("href","mailto:"+results.printEmail).html(results.printEmail).fadeIn();
						$("#printNewsfeed").html(results.newsfeedItems).fadeIn();
						
						$("#loadingPageSpinner").hide();
						$("#loadingPage").fadeIn();
							}
						
						
					}
				});
		}
		loadProfile();
		
		$(document).on('click','.newsfeed-item .actions .fa-ellipsis-h', function() {
			var thisMenu = $(this).parent().next();
			$(".actionMenu").not(thisMenu).slideUp();
			$(this).parent().next().slideToggle();
		});
	});
</script>
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
      <div class="row">
        <div class="col-sm-12">
          <div class="whitebg">
            <div id="loadingPageSpinner">
              <center>
                <img src="/dashboard/images/grey-spinner.gif">
              </center>
            </div>
            <div id="noUserPage">
              <center>
                <br>
                <br>
                <h1 class="text-center">This user has been deleted or does not exist.</h1>
                <br>
                <a href="/dashboard/users" class="genericbtn noExpand">User Directory</a>
                </h3>
              </center>
            </div>
            <div  id="loadingPage">
              <div class="header">
                <div class="pull-right" id="isOnline"></div>
                <h3>User Profile: <span id="printName"></span></h3>
              </div>
              <div class="row">
                <div class="col-sm-12"> </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <center>
                    <img src='' style='max-height: 300px;' id="printProfilePic">
                  </center>
                  <h3 class='text-center' id="printName2"></h3>
                  <a href=''>
                  <div class='directory' style='font-weight: bold; position: relative;' id="printGroup"></div>
                  </a>
                  <hr>
                  <p>
                  <div class="formLabels">Role:</div>
                  <span id="printRole"></span>
                  </p>
                  <p>
                  <div class="formLabels">Username:</div>
                  @<span id="printUsername"></span>
                  </p>
                  <p>
                  <div class="formLabels">Title:</div>
                  <span id="printTitle"></span>
                  </p>
                  <p>
                  <div class="formLabels">Email:</div>
                  <a href='' id="printEmail"></a>
                  </p>
                </div>
                <div class="col-sm-8">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="header">
                        <h3>Latest Activity</h3>
                      </div>
                      <div id="printNewsfeed" style="max-height: 700px; overflow: scroll"> </div>
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
</div>
<?php echo $scripts?>
</body>
</html>