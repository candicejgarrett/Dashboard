<?php 
include_once('../../header.php');
 require('../../connect.php');

?>
  
  
   <html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php echo $stylesjs ?>
   <?php echo $scripts ?>
   <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->


    </head>
    <body>
<div class="allContent">   
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
   <?php include("../../templates/topNav.php") ?>
  </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
	<div class="row">
        <?php include("../../templates/lhn.php") ?>
      
		<div class="col-sm-10" style="height: auto;">
		   <div class="row">
     	<div class="col-sm-12">
			<?php include("../../templates/alerts.php") ?>
		</div>
		</div>
      		<div class="row">
      		<div class="col-sm-12">
      		
      		<!--<div class="gradientBackground"><p class="daysLeft">This project was completed in <strong>3</strong> days.</p></div>-->
      			<div class="whitebg" style="margin-bottom: 0px;">
					<div class='row'><div class='col-sm-12 text-center'><h1 class='fourohfour'><i style='font-size:100px; color:#ff0000' class='fa fa-exclamation-triangle' aria-hidden='true'></i><br>404</h1><h1>This project has been deleted or does not exist.</h1><br><a href='/dashboard/team-projects' class='genericbtn noExpand'>View All Projects</a><br><br></div></div>
       		</div>
       		
		   </div>
       </div>
        
       
	</div>
</div>    
</div>

    
    
    </body>
</html>






