<?php 
include_once('../header.php');


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
			<div class="whitebg">
    	 		
    	 		
					<div class="header">
					<h3>Requests</h3>
					
					</div>
					<div class="row">
						<div class='col-sm-12 text-center'><h1 class='fourohfour'><i style='font-size:100px; color:#ff0000' class='fa fa-exclamation-triangle' aria-hidden='true'></i><br></h1><h1>You do not have access to this page.</h1><br><br><br><br></div>
     	 			</div>	
           			
    	 		
   	 	   </div>
     	 	
     	 	 
		 </div>	
     	
     	
     	
     	</div>
     
       </div>
       
      
	</div>
</div>    

    <script type="text/javascript" src="../js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>

<script>
var clip = new Clipboard('#button1');

clip.on('success', function(e) {
    alert("copied");
});
</script>
     
    </body>
</html>