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
		
<script src="/dashboard/js/pages/db-group-settings.js"></script>
<script>

$(document).ready(function() {
		

		
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
					<h3><strong><a href="/dashboard/settings/">Settings:</a></strong> Group/Category Management</h3>
					
					</div>
					
				<div class="row" style="margin-bottom:20px;">
					<div class="col-sm-12">
					
					<ul class="nav nav-pills">
					  <li class="active"><a data-toggle="tab" href="#groups">Teams</a></li>
					  <li><a data-toggle="tab" href="#taskTypes">Task Types Per Team</a></li>
					  <li><a data-toggle="tab" href="#projectTypes">Project Types Per Team</a></li>
					  <li><a data-toggle="tab" href="#categories">Calendar Events</a></li>
					  <li><a data-toggle="tab" href="#KC">Knowledge Center</a></li>
					  
					</ul>
						<hr>
						
					<div class="tab-content">
						<div id="groups" class="tab-pane fade in active">
						  <div class="col-sm-12">
						 <div id="createNewGroup" class="genericbtn showCreateNew">Create New</div>
						<br>
						<div class="createNewContainer">
							
								<hr>
								<h4>Create A New Team:</h4>
								<div id="#validate" class="validate"></div>
							
							<div class="row">
								<div class="col-sm-5">
									<div class="formLabels">Team Title:*</div>
									<input type="text" id="groupTitle" name="groupTitle" placeholder="" class="validate">
								</div>
								<div class="col-sm-5">
									<div class="formLabels">Team Color:* Ex: #000000</div>
									<input type="text" id="groupColor" name="groupColor" placeholder="" class="validate">
								</div>
								<div class="col-sm-2"><div class="formLabels">&nbsp;</div>
									<div id="addNewGroup-btn" class="genericbtn green_bg">Add New</div><br><br>
								</div>
							</div>	
							
						</div>
							<hr>
							</div>
						<div class="col-sm-12">

								<div id="projectsTableContainer" class="table-responsive groupSettingsTable">
									<table class="dataTable" id="printBack">
										<thead>
										<tr>
											<th id="orderBy">Team Name</th>
											<th>Team Color</th>
											<th>Member Count</th>
											<th>Check All</th>
										</tr>
										</thead>
										 <tbody>

										</tbody>
										</table>
								</div>

							</div>

						</div>
						
						<div id="taskTypes" class="tab-pane fade">
						  <div class="col-sm-12">
							  <h4>Select Team:</h4>
							<?php
					
					$getUsers = "SELECT * FROM `Groups`";
					$getUsers_result = mysqli_query($connection, $getUsers) or die ("Query to get data from Team task failed: ".mysql_error());

							echo '<select id="teamID" style="width:200px;"><option value="All">Select...</option>'; // Open your drop down box

							// Loop through the query results, outputing the options one by one
							while ($row = mysqli_fetch_array($getUsers_result)) {
							echo "<option value='" . $row['GroupID'] ."'>".$row['Group Name']."</option>";
							}

							echo '</select>';

					?>  
							  
							<hr style="display:none;">  
						 <div class="genericbtn showCreateNew" style="display:none;">Create New</div><br>
						  
							  
							  
						<div class="createNewContainer">
							
							
							
								<hr>
								<h4>Create A Task Type: </h4>
								<div id="#validate" class="validate"></div>
							
							<div class="row">
								<div class="col-sm-5">
									<div class="formLabels">Task Title:*</div>
									<input type="text" id="taskTitle" name="taskTitle" placeholder="" class="validate">
								</div>
								<div class="col-sm-2"><div class="formLabels">&nbsp;</div>
									<div id="addNewTaskType-btn" class="genericbtn green_bg">Add New</div><br><br>
								</div>
							</div>	
							
						</div>
							<hr>
							</div>
						<div class="col-sm-12">

								<div class="table-responsive taskTypeSettingsTable">
									<table class="dataTable" id="printBack">
										<thead>
										<tr>
											<th id="orderBy">Category Name</th>
											<th>Task Count</th>
											<th>Check All</th>
										</tr>
										</thead>
										 <tbody>

										</tbody>
										</table>
								</div>

							</div>

						</div>
						
						<div id="projectTypes" class="tab-pane fade">
						  <div class="col-sm-12">
							  <h4>Select Team:</h4>
							<?php
					
					$getUsers = "SELECT * FROM `Groups`";
					$getUsers_result = mysqli_query($connection, $getUsers) or die ("Query to get data from Team task failed: ".mysql_error());

							echo '<select id="teamIDProjects" style="width:200px;"><option value="All">Select...</option>'; // Open your drop down box

							// Loop through the query results, outputing the options one by one
							while ($row = mysqli_fetch_array($getUsers_result)) {
							echo "<option value='" . $row['GroupID'] ."'>".$row['Group Name']."</option>";
							}

							echo '</select>';

					?>  
							  
							<hr style="display:none;">  
						 <div class="genericbtn showCreateNew" style="display:none;">Create New</div><br>
						  
							  
							  
						<div class="createNewContainer">
							
							
							
								<hr>
								<h4>Create A Project Type: </h4>
								<div id="#validate" class="validate"></div>
							
							<div class="row">
								<div class="col-sm-5">
									<div class="formLabels">Project Title:*</div>
									<input type="text" id="projectTitle" name="projectTitle" placeholder="" class="validate">
								</div>
								<div class="col-sm-2"><div class="formLabels">&nbsp;</div>
									<div id="addNewProjectType-btn" class="genericbtn green_bg">Add New</div><br><br>
								</div>
							</div>	
							
						</div>
							<hr>
							</div>
						<div class="col-sm-12">

								<div class="table-responsive projectTypeSettingsTable">
									<table class="dataTable" id="printBack">
										<thead>
										<tr>
											<th id="orderBy">Category Name</th>
											<th>Project Count</th>
											<th>Check All</th>
										</tr>
										</thead>
										 <tbody>

										</tbody>
										</table>
								</div>

							</div>

						</div>
						
						<div id="categories" class="tab-pane fade">
						  <div class="col-sm-12">
						 <div class="genericbtn showCreateNew">Create New</div><br>
						  
						<div class="createNewContainer">
							
								<hr>
								<h4>Create A New Calendar Event Category: </h4>
								<div id="#validate" class="validate"></div>
							
							<div class="row">
								<div class="col-sm-5">
									<div class="formLabels">Event Title:*</div>
									<input type="text" id="eventTitle" name="eventTitle" placeholder="" class="validate">
								</div>
								<div class="col-sm-5">
									<div class="formLabels">Event Color:* Ex: #000000</div>
									<input type="text" id="eventColor" name="eventColor" placeholder="" class="validate">
								</div>
								<div class="col-sm-2"><div class="formLabels">&nbsp;</div>
									<div id="addNewEvent-btn" class="genericbtn green_bg">Add New</div><br><br>
								</div>
							</div>	
							
						</div>
							<hr>
							</div>
						<div class="col-sm-12">

								<div class="table-responsive calendarEventsSettingsTable">
									<table class="dataTable" id="printBack">
										<thead>
										<tr>
											<th id="orderBy">Event Name</th>
											<th>Event Color</th>
											<th>Event Count</th>
											<th>Check All</th>
										</tr>
										</thead>
										 <tbody>

										</tbody>
										</table>
								</div>

							</div>

						</div>
						
						<div id="KC" class="tab-pane fade in">
						  <div class="col-sm-12">
						 <div class="genericbtn showCreateNew">Create New</div>
						<br>
						<div class="createNewContainer">
							
								<hr>
								<h4>Create A New Category:</h4>
								<div id="#validate" class="validate"></div>
							
							<div class="row">
								<div class="col-sm-5">
									<div class="formLabels">Team Title:*</div>
									<input type="text" id="KCTitle" name="KCTitle" placeholder="" class="validate">
								</div>
								
								<div class="col-sm-2"><div class="formLabels">&nbsp;</div>
									<div id="addNewKC-btn" class="genericbtn green_bg">Add New</div><br><br>
								</div>
							</div>	
							
						</div>
							<hr>
							</div>
						<div class="col-sm-12">

								<div id="projectsTableContainer" class="table-responsive KCSettingsTable">
									<table class="dataTable" id="printBack">
										<thead>
										<tr>
											<th id="orderBy">Category</th>
											<th>Posts</th>
											<th>Tags</th>
											<th>Check All</th>
										</tr>
										</thead>
										 <tbody>

										</tbody>
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
</div>    

    
     <?php echo $scripts ?>
     
    </body>
</html>