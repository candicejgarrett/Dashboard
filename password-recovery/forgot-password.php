<?php  
 require('../connect.php');


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
