<?php 
include_once('../../header.php');
$todaysDate = date("Y-m-d H:i:s",strtotime('today'));
?>
   <html class="x-template-todo">
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
		<script src="/dashboard/js/pages/todo.js"></script>
<script>

	$(document).ready(function() {

		//on selectedMenu items/opening secondary menu
		$(document).on("click",".selectedMenu li",function() {
			var projectID = $("#projectsTableContainer tbody tr.selected").attr("projectID");
			var taskID = $("#projectsTableContainer tbody tr.selected").attr("taskID");
			if ($(this).hasClass("hasSecondaryMenu")) {
				
				var controllerID = $(this).attr("id");
				var thisMenu =$( "li[controller='"+controllerID+"']" );
				
				$( "li[controller='"+controllerID+"']" ).toggle().toggleClass("activeMenu");
				
				}
			
			var selectedRow =$("#projectsTableContainer tbody tr.selected");
			
			var type = $(this).attr("id");
			
			if (type === "approve" || type === "delete"){
				
				if (type === "delete") {
					var alertMessage = "Are you sure? This cannot be undone!";
				}
				else {
					var alertMessage = "Are you sure?";
					}
				
				$.alertable.confirm(alertMessage).then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					
					var dataString = {'type':type,'taskID':taskID};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								
								var entries = result.printBack;
								reloadSuccess(entries, taskID);
								
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			}
			
			//if view more is selected
			else if (type === "viewInfo"){
				window.open('/dashboard/team-projects/view/?projectID='+projectID+'&taskID='+taskID,'_blank');
				
			}
			else {
				return false;	
			}
			
			
		});
	
		
		//clicking secondary button save
		$(document).on("click",".secondaryMenu button",function() {
			
			var type = $(this).parent().attr("controller");
			var taskID = $("#projectsTableContainer tbody tr.selected").attr("taskID");
			var selectedRow =$("#projectsTableContainer tbody tr.selected");
			var thisMenusInput = $( "li[controller='"+type+"']" ).find("textarea");
			
			if (type === "kickback") {
				$.alertable.confirm('Are you sure?').then(function() { 
					$( "#closeMenu").trigger( "click" );
					
					var newVal = $(thisMenusInput).val();
					
					var dataString = {'type':type,'taskID':taskID,'newVal':newVal};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, taskID);
						},
						error: function(result){
							alert("Error.");
						}
						});
				})
			}
		
		
		});
		
		
	
		//bulk approve
		$(document).on('click','#approveButton', function() {
			
			var taskIDs = [];
			
		$.alertable.confirm('Are you sure you want approve multiple tasks at once?').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var taskID = $(this).attr("taskid");
			  if ($(this).is(':checked')) {
					
					taskIDs.push(taskID);
			  }
			else 
			{
				taskIDs = $.grep(taskIDs, function(value) {
						  return value != taskID;
						});
			}
			
		});
			
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=approveMultiple&taskIDs='+taskIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, taskIDs);
						},
						error: function(result){
							alert("Error.");
						}
					});	
			
			
			
		});
		
	});
	
		//bulk kickback
		$(document).on('click','#kickbackButton', function() {
			
			var taskIDs = [];
			
		$.alertable.confirm('Are you sure you want to kickback multiple tasks at once?').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var taskID = $(this).attr("taskid");
			  if ($(this).is(':checked')) {
					
					taskIDs.push(taskID);
			  }
			else 
			{
				taskIDs = $.grep(taskIDs, function(value) {
						  return value != taskID;
						});
			}
			
		});
			
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=kickbackMultiple&taskIDs='+taskIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, taskIDs);
						},
						error: function(result){
							alert("Error.");
						}
					});	
			
			
			
		});
		
	});
		
		//bulk delete
		$(document).on('click','#deleteButton', function() {
			
			var taskIDs = [];
			
		$.alertable.confirm('Are you sure you want to delete multiple tasks at once?').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var taskID = $(this).attr("taskid");
			  if ($(this).is(':checked')) {
					
					taskIDs.push(taskID);
			  }
			else 
			{
				taskIDs = $.grep(taskIDs, function(value) {
						  return value != taskID;
						});
			}
			
		});
			
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=deleteMultiple&taskIDs='+taskIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, taskIDs);
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
					<h3>Approvals</h3>
					
					</div>
					
				<div class="row" style="margin-bottom:20px;">
					
         		
					<div class="col-sm-12">
			
				<div id="projectsTableContainer" class="table-responsive approvalsTable">
				<table class="projectsTable myAssignedTasks dataTable no-footer" id="printBack">
					<thead>
					<tr>
						<th>Title</th>
						<th>Project</th>
						<th>Category</th>
						
						<th id="orderBy">Due Date</th>
						<th>Assigned To</th>
						<th>Status</th>
						<th>Check All</th>
					</tr>
					</thead>
					<tbody>
				
						
					</tbody>
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

    <?php echo $scripts ?>
     
    </body>
</html>