<?php  
 require('../connect.php');


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
			}}
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
					<center><h1>Reset Password</h1></center>
					</div>
 	 		 
 	 		 		<center>
					<p style="color:#ff0000;text-align:center;font-weight:bold;" id="wrong"><?php echo $response;?></p>		
						
						
 	 		 		<form class="login" id="psChangeform" name="psChangeform" method="post" action="change.php" >
						<input type="text" id="username" placeholder="Username" name="username">
						
						<br>
								  
						<button type="submit" name="ForgotPassword" class="genericbtn">Reset</button>	
						<a href="/dashboard" class="genericbtn">Login</a>	
					</form>
	 		 		
 	 		 		</center>	
  	 		 
   	 		  </div>
     	 	</center>
     	 	
     	 	
     	 </div>
     	 	
     	 
     	 
     	
     	</div>
    </div>  
       </div>
       
     
	</div>
</div>    

        
         
           
    <script>
		
</script> 

    </body>
</html>
