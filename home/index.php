<?php 
include_once('../header.php');
require('../connect.php');

?>
   <html class="x-template-home">
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
		<script src="/dashboard/js/pages/home.js"></script>
		<link href="/dashboard/css/fullcalendar.css" rel="stylesheet" />
<link href="/dashboard/css/fullcalendar.print.css" rel="stylesheet" media="print" />
<script src="/dashboard/js/moment.min.js"></script>
<script src="/dashboard/js/fullcalendar.min.js"></script>
<script src="/dashboard/js/pages/content-calendar.js"></script>
    </head>

    <body>

  <div id="containerAll"> 
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
   
    <?php include("../templates/topNav.php") ?>

  </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
	<div class="row">
		
         <?php include("../templates/lhn.php") ?>
       
       <div class="col-sm-10" style="height: 100%;">
      <div>
     	 <div class="row">
     	<div class="col-sm-12">
			<?php include("../templates/alerts.php") ?>
		</div>
		</div>
     	
		 <div class="row">
			 
		 <div class="col-sm-12">
			 <div class="whitebg noMinHeight">
   	 		  		<div class="header"><h3>Today's To-Do List:</h3></div>
   	 		  		<div class="noTasksDue">
				 		 <div class="row">
			 				<div class="col-sm-12">
								 <h4><em><strong>You're all caught up!</strong></em> You don't have any tasks or reviews due today. </h4>
								<br>
							</div>
						</div>
				 	</div>
   	 		  		<div id="printFullTodoList" class="table-responsive">
				<table class="projectsTable todoListTable" id="printBackFullTodoList">
					<thead>
					<tr>
						<th>Title</th>
						<th>Project</th>
						<th>Status</th>
						<th>Type</th>
						<th>Due Date</th>
						
					</tr>
					</thead>
					<tbody>
				
						
					</tbody>
				</table>
				</div>
  		 			
   		 		</div>
			 
     	 </div>	
     	 
     	 	
     	</div>
     	
		  

		  
		  
		 <div class="row">
			 
			 
     	<div class="col-sm-12">
     		
			<div class="gradient" style="min-height: auto;padding:15px;-webkit-box-shadow: 0px 4px 25px -5px rgba(0,0,0,0.10);-moz-box-shadow: 0px 4px 25px -5px rgba(0,0,0,0.10); box-shadow: 0px 4px 25px -5px rgba(0,0,0,0.10);margin-bottom: 15px;">
					
				
				
					
					<div class="row">
						<div class="col-sm-12">
						<div class="header" style="padding-top: 0px;border: 0px;">
					<h3>Active Team Projects</h3>
						</div>
							<div class="col-sm-12 dragOverflow" style="padding:0px;">
						<div class="prev"><i class="fa fa-chevron-left" aria-hidden="true"></i><span>Previous</span></div>
								<div class="next"><span>Next</span><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
						</div>
						</div>
						<div class="col-sm-12 dragContainer">
							<div class=" wrapper">
								
							<div class="projectHorzScroll" id="activeProjects">
								
								
							</div>
							
							
						
						</div>
     					
     						
		 			</div>
					
				</div>
   	 	  	
		 </div>	
		 </div>
     	</div>
     	<div class="row">
     		 <div class="col-sm-6">
			 
			<div class="whitebg">
					<i class="fa fa-refresh pull-right" aria-hidden="true" id="refreshFeed"></i>
   	 		  		<div class="header"><h3>Latest Activity</h3></div>
   	 		  		
   	 		  		<div class="newsfeedContainer">
					<div id="printNewsfeed">
   	 		  		<center><img src="/dashboard/images/grey-spinner.gif" id="spinner"></center>
				</div>
						
  		 		</div>
				
   		 		</div>
     	 	
			 
     	 </div>	
     	 
     	 <div class="col-sm-6">
			
		   
     	 	<div class="whitebg" id="upcomingEvents">
    	 		<div class="header" style="margin-bottom: 0px;"><h3>Events</h3></div><br>
							<div id='calendar' style="padding: 10px;"></div> 
					
    	 		
   	 	   </div>
     	 	
			 
		
				
		 </div>	
   		 </div>  
     	
     	</div>
    </div>  
      
       </div>
       
     
	</div>
</div>    

	<?php echo $scripts ?>
   

    </body>
</html>