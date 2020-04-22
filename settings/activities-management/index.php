<?php 
include_once('../../header.php');
if ($myRole != 'Admin') {
	header("location:/dashboard/404/no-access.php"); 	
} 
?>
   <html class="x-template-settings">
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php echo $stylesjs ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="/dashboard/js/pages/db-settings.js"></script>
<script>

$(document).ready(function() {

		//bulk delete
		$(document).on('click','#delete-btn2', function() {
			
			var activityIDs = [];
			
		$.alertable.confirm('Are you sure? THIS CANNOT BE UNDONE!').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var activityID = $(this).attr("activityid");
			  if ($(this).is(':checked')) {
					
					activityIDs.push(activityID);
			  }
			else 
			{
				activityIDs = $.grep(activityIDs, function(value) {
						  return value != activityID;
						});
			}
			
		});
			
		
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=deleteMultiple&activityIDs='+activityIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, activityIDs);
								
						},
						error: function(result){
							alert("Error.");
						}
					});	
			
			
			
		});
		
	});
		
		
});

</script>

    </head>

    <body>
    
<nav class="navbar navbar-default print_remove" style="background:#ffffff; border:none;">
  <div class="container-fluid">
   <?php include("../../templates/topNav.php") ?>
  </div><!-- /.container-fluid -->
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
		 <div class="col-sm-12" id="moveOver">
			<div class="whitebg">
    	 		
    	 		
					<div class="header">
					<h3><strong><a href="/dashboard/settings/">Settings:</a></strong> Activities Management</h3>
					
					</div>
					
				<div class="row" style="margin-bottom:20px;">
					<div class="col-sm-12">
        			
			
			<div class="col-sm-12">
			
				<div id="projectsTableContainer" class="table-responsive activitiesSettingsTable">
				<table class="projectsTable noSingleClick" id="printBack">
					<thead>
					<tr>
						<th id="orderBy">Activity ID</th>
						<th>Activity</th>
						<th>Timestamp</th>
						<th>Type</th>
						<th>User</th>
						<th>Check All</th>
					</tr>
					</thead>
					 
					</table>
				</div>
				<br><br>
			</div>
			
			
			
			
				
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