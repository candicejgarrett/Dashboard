<?php 
include_once('../../header.php');

?>
   <html>
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
<script>

	$(document).ready(function() {
$('#printBack').DataTable({
			'destroy': true,
        "order": [[ 4, "desc" ]],
		"columnDefs": [{ "orderable": false, "targets": 7 }]
    	});
	$(".dataTables_length").appendTo("#printBack_wrapper");
		function reload(pageLength) {
			pageLength = parseInt(pageLength);
			$('#printBack').DataTable({
				'destroy': true,
				'pageLength': pageLength,
			"order": [[ 4, "desc" ]],
			"columnDefs": [{ "orderable": false, "targets": 7 }]
			});
			$(".dataTables_length").appendTo("#printBack_wrapper");
		}

		//check all
		$(document).on("click",".projectsTable thead tr th:last-child",function() {
			
        	var thisTable = $(this).parent().parent().parent();
			$(thisTable).toggleClass("checkAllBoxes");
			
				if ($(thisTable).hasClass("checkAllBoxes")){
					$(thisTable).find("input[type='checkbox']:visible").prop('checked', true);
				}
				else{
				   $(thisTable).find("input[type='checkbox']").prop('checked', false);
				}
	
    	});
		
		//on click row, show menu
	
		$(document).on("click",".projectsTable tbody tr td:not(:last-child)",function(e) {
  
  // Remove any old one
  $(".ripple,.selectedMenu,#moreInfo").remove();
	//removing class selected
  $("#projectsTableContainer tbody tr").removeClass("selected");
			
  // Setup
  var posX = $(this).offset().left,
      posY = $(this).offset().top,
      buttonWidth = 50,
      buttonHeight =  50;
  
	var parentOffset = $("#projectsTableContainer").offset(); 
	var yPos = ((e.pageY - parentOffset.top -17) /$('#projectsTableContainer').height()) * 100;
	var xPos = ((e.pageX - parentOffset.left -23) /$('#projectsTableContainer').width()) * 100;		
			
  // Add the element
  $(this).prepend("<span class='ripple'></span>");

  
 // Make it round!
  if(buttonWidth >= buttonHeight) {
    buttonHeight = buttonWidth;
  } else {
    buttonWidth = buttonHeight; 
  }

  // Add the ripples CSS and start the animation
  $(".ripple").css({
    width: buttonWidth,
    height: buttonHeight,
    top: yPos + '%',
    left: xPos + '%'
  }).addClass("rippleEffect");
			
	//changing to selected row
	$(this).parent().addClass("selected");
			
	//appending menu		
	$("#projectsTableContainer").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewInfo">View Project <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="approve">Approve</li><li id="kickback" class="hasSecondaryMenu">Kickback</li><li id="kickbackSecondaryMenu" controller="kickback" class="secondaryMenu"><input type="text" placeholder="Enter a message..."><button class="genericbtn">Save</button></li><li id="delete">Delete</li></div>');
			
	$(".selectedMenu").css({
    top: yPos + '%',
    left: xPos + '%'
  });
			
});
	
		//close menu/remove
		$(document).on("click","#closeMenu",function() {
			$("#projectsTableContainer tbody tr").removeClass("selected");
			$(".selectedMenu,#moreInfo").remove();
		});
		
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
								//get pageLength
								var pageLength = $("#printBack_length select").children("option:selected").val();
								$("#projectsTableContainer").empty();
							if (result.printBack === null) {
									$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody></tbody></table>');
								
									}
							else {
								$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody>'+result.printBack.join("")+'</tbody></table>');
								
							}
								reload(pageLength);
								
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			}
			
			//if view more is selected
			else if (type === "viewInfo"){
				window.open('/dashboard/team-projects/view/?projectID='+projectID,'_blank');
				
			}
			else {
				return false;	
			}
			
			
		});
	
		//close more info/remove
		$(document).on("click","#closeMoreInfo",function() {
			$("#projectsTableContainer tbody tr").removeClass("selected");
			$(".selectedMenu,#moreInfo").remove();
		});
		
		//clicking secondary button save
		$(document).on("click",".secondaryMenu button",function() {
			
			var type = $(this).parent().attr("controller");
			var taskID = $("#projectsTableContainer tbody tr.selected").attr("taskID");
			var selectedRow =$("#projectsTableContainer tbody tr.selected");
			var thisMenusInput = $( "li[controller='"+type+"']" ).find("input");
			
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
								//get pageLength
								var pageLength = $("#printBack_length select").children("option:selected").val();
								
								$("#projectsTableContainer").empty();
								$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody>'+result.printBack.join("")+'</tbody></table>');
								reload(pageLength);
						},
						error: function(result){
							alert("Error.");
						}
						});
				})
			}
		
		
		});
		
		//if any checkbox is checked show buttons
		$(document).on("click",".projectsTable thead tr th:last-child,.projectsTable tbody tr td input[type='checkbox']",function() {
			var anyBoxesChecked;
			$(".checkedMenu").remove();
			
			$('.projectsTable input[type="checkbox"]').each(function() {
				if ($(this).is(":checked")) {
					anyBoxesChecked = true;
				}
			});
				
			if (anyBoxesChecked == undefined) {
			  	$(".checkedMenu").remove();
			}
			else {
				$("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="deleteButton" name="deleteButton" class="archive pull-right" style="background:#ff0000 !important"><i class="fa fa-trash" aria-hidden="true"></i></button>&nbsp;<button type="button" id="kickbackButton" name="kickbackButton" class="archive pull-right" style="background:#ff0000 !important"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>&nbsp;<button type="button" id="approveButton" name="approveButton" class="createNew noExpand pull-right" style="background:#07CD00"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');
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
								//get pageLength
								var pageLength = $("#printBack_length select").children("option:selected").val();
								
								$("#projectsTableContainer").empty();
								$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody>'+result.printBack.join("")+'</tbody></table>');
								reload(pageLength);
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
								//get pageLength
								var pageLength = $("#printBack_length select").children("option:selected").val();
								
								$("#projectsTableContainer").empty();
								$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody>'+result.printBack.join("")+'</tbody></table>');
								reload(pageLength);
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
								//get pageLength
								var pageLength = $("#printBack_length select").children("option:selected").val();
								
								$("#projectsTableContainer").empty();
								if (result.printBack === null) {
									$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody></tbody></table>');
								
									}
							else {
								$("#projectsTableContainer").html('<table class="projectsTable" id="printBack"><thead><tr><th>Title</th><th>Project</th><th>Category</th><th>Date Created</th><th>Due Date</th><th>Assigned To</th><th>Status</th><th>Check All</th></tr></thead><tbody>'+result.printBack.join("")+'</tbody></table>');
								
							}
								reload(pageLength);
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
					<h3>My Assigned Tasks</h3>
					
					</div>
					
				<div class="row" style="margin-bottom:20px;">
					
         		
					<div class="col-sm-12">
			
				<div id="projectsTableContainer" class="table-responsive">
				<table class="projectsTable myAssignedTasks" id="printBack">
					<thead>
					<tr>
						<th>Title</th>
						<th>Project</th>
						<th>Category</th>
						<th>Date Created</th>
						<th>Due Date</th>
						<th>Assigned To</th>
						<th>Status</th>
						<th>Check All</th>
					</tr>
					</thead>
					<tbody>
				<?php
					
					$query = "SELECT `TaskID`,`First Name`,`Last Name`, `Team Projects`.`Title` AS 'Project Title', `Tasks`.`Title`, `Tasks`.`Description`, DATE_FORMAT(`Tasks`.`Due Date`, '%m/%d/%y'), `Tasks`.`Status`, `Task Categories`.`Category`, `Requested By`, `Tasks`.`ProjectID`, `Tasks`.`userID`, `allDay`, DATE_FORMAT(`Task Date Created`, '%m/%d/%y'), `Task Date Completed` FROM `Tasks` JOIN `Task Categories` ON `Tasks`.`Category`=`Task Categories`.`CategoryID` JOIN `Team Projects` ON `Tasks`.`ProjectID`=`Team Projects`.`ProjectID` JOIN `user` ON `Tasks`.`userID`=`user`.`userID` WHERE `Tasks`.`Requested By`='$userID' AND `Team Projects`.`Status` != 'Archived' ORDER BY `Task Date Created` DESC";
			
					$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
									
										while ($row = mysqli_fetch_array($query_result)) {
											 $printID = $row["TaskID"];
											$printCreatedDate = $row["DATE_FORMAT(`Task Date Created`, '%m/%d/%y')"];
											$printTitle = $row["Title"];
											$printStatus = $row["Status"];
											$printDueDate = $row["DATE_FORMAT(`Tasks`.`Due Date`, '%m/%d/%y')"];
											 $printDescription = $row["Description"];
											 $printCategory = $row["Category"];
											 $printProjectID = $row["ProjectID"];
											$printProjectTitle = $row["Project Title"];
											 $printCreatedByUserID = $row["userID"];
											$printTicketID = $row["TicketID"];
											$printAssignedTo = $row["First Name"]." ".$row["Last Name"];
											echo '<tr taskid="'.$printID.'" projectid="'.$printProjectID.'">
				<td>'.$printTitle.'</td>
				<td>'.$printProjectTitle.'</h2>
				<td>'.$printCategory.'</td>
				<td>'.$printCreatedDate.'</td>
				<td>'.$printDueDate.'</td>
				<td>'.$printAssignedTo.'</td>
				<td><div class="todoStatus_'.$printStatus.'">'.$printStatus.'</div></td>
				<td><input type="checkbox" taskID="'.$printID.'" value="'.$printID.'"></td>
				
			  </tr>';
											
											
										}
				?>
						
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