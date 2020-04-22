<?php 
include_once('../header.php');
require('../connect.php');


?>
   <html class="x-template-kc">
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

   <script>
	   $(document).ready(function(){
		   
	   });
</script>
    </head>

    <body>
    
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
   <?php include("../templates/topNav.php") ?>
  </div><!-- /.container-fluid -->
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
    	 			<h3>Knowledge Center</h3>
					<p></p>
			</div>
			<div class="row">
			<div class="col-sm-9 col-xs-offset-1">
				
				<br>
				<div class="row">
						<?php
					
							$getCategories = "SELECT DISTINCT `Category` FROM `Knowledge Center Categories` ORDER BY `Category` ASC";
							$getCategories_result = mysqli_query($connection, $getCategories) or die ("Query to get data from Team task failed: ".mysql_error());

							while ($row = mysqli_fetch_array($getCategories_result)) {
								$category = $row['Category'];
							echo '<div class="col-sm-4" style="margin-bottom:20px;"><a href="category/?cat='.$category.'"><div class="gradientBackground settingsIcon"><h3>'.$category.'</h3></div></a></div>';
							}
						?>
						<div class="col-sm-4" style="margin-bottom:20px;"><a href="category"><div class="gradientBackground settingsIcon"><h3>View All</h3></div></a></div>
				
					
				</div>
				
				
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
         
    </body>
</html>