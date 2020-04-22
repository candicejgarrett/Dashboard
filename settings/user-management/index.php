<?php 
include_once('../../header.php');
if ($myRole !== 'Admin') {
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
	$("#projectsTableContainer").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewInfo">View <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="changeEmail" class="hasSecondaryMenu">Change Email</li><li id="changeEmailSecondaryMenu" controller="changeEmail" class="secondaryMenu"><input type="email"><button class="genericbtn">Save</button></li><li id="changeRole" class="hasSecondaryMenu">Change Role</li><li id="changeRoleSecondaryMenu" controller="changeRole" class="secondaryMenu"><select><?php $getRoles = "SELECT DISTINCT * FROM `Roles`";$getRoles_result = mysqli_query($connection, $getRoles) or die ("Query to get data from Group task failed: ".mysql_error());while ($row = mysqli_fetch_array($getRoles_result)) {echo "<option value=".$row["Role"].">".$row["Role"]."</option>";}?></select><button class="genericbtn">Save</button></li><li id="changeGroup" class="hasSecondaryMenu">Change Group</li><li id="changeRoleSecondaryMenu" controller="changeGroup"class="secondaryMenu"><select><?php $getGroups = "SELECT DISTINCT * FROM `Groups`";$getGroups_result = mysqli_query($connection, $getGroups) or die ("Query to get data from Group task failed: ".mysql_error());while ($row = mysqli_fetch_array($getGroups_result)) {echo "<option value=".$row["GroupID"].">".$row["Group Name"]."</option>";}?></select><button class="genericbtn">Save</button></li><li id="changeTitle" class="hasSecondaryMenu">Change Title</li><li id="changeTitleSecondaryMenu" controller="changeTitle" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="reactivate">Reactivate</li><li id="deactivate">Deactivate</li><li id="delete">Delete</li></ul></div>');
			
	$(".selectedMenu").css({
    top: yPos + '%',
    left: xPos + '%'
  });
			
});
	
		//on selectedMenu items/opening secondary menu
		$(document).on("click",".selectedMenu li",function() {
			
			var userID = $("#projectsTableContainer tbody tr.selected").attr("userID");
			if ($(this).hasClass("hasSecondaryMenu")) {
				
				var controllerID = $(this).attr("id");
				var thisMenu =$( "li[controller='"+controllerID+"']" );
				
				$( "li[controller='"+controllerID+"']" ).toggle().toggleClass("activeMenu");
				
				}
			
			var selectedRow =$("#projectsTableContainer tbody tr.selected");
			
			var type = $(this).attr("id");
			if (type === "deactivate" || type === "reactivate" || type === "delete"){
				$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					
					var dataString = {'type':type,'userID':userID};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userID);
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			}
			
			else if (type === "changeEmail") {
				var controllerID = $(this).attr("id");
				
				var currentVal = selectedRow.find("td:nth-child(3) p").text();
				var thisMenusInput = $( "li[controller='"+controllerID+"']" ).find("input");
				$(thisMenusInput).val(currentVal);
					 
					 }
			
			else if (type === "changeRole") {
				var controllerID = $(this).attr("id");
				
				var currentVal = selectedRow.find("td:nth-child(6) p").text();
				var thisMenusInput = $( "li[controller='"+controllerID+"']" ).find("select");
				$(thisMenusInput).val(currentVal);
					 
					 }
			else if (type === "changeGroup") {
				var controllerID = $(this).attr("id");
				
				var currentVal = selectedRow.find("td:nth-child(10)").attr("id");
				var thisMenusInput = $( "li[controller='"+controllerID+"']" ).find("select");
				
				$(thisMenusInput).val(currentVal);
					 
					 }
			
			else if (type === "changeTitle") {
				var controllerID = $(this).attr("id");
				
				var currentVal = selectedRow.find("td:nth-child(7) p").text();
				var thisMenusInput = $( "li[controller='"+controllerID+"']" ).find("input");
				$(thisMenusInput).val(currentVal);
					 
					 }
			
			
			//if view more is selected
			else if (type === "viewInfo"){
				window.open('/dashboard/users/profile/?userID='+userID,'_blank');
				
			}
			else {
				return false;	
			}
				
			
			
			
			
			
			
		});
	
	
		//clicking secondary button save
		$(document).on("click",".secondaryMenu button",function() {
			
			var type = $(this).parent().attr("controller");
			var userID = $("#projectsTableContainer tbody tr.selected").attr("userID");
			var selectedRow =$("#projectsTableContainer tbody tr.selected");
			var thisMenusInput = $( "li[controller='"+type+"']" ).find("input");
			var thisMenusSelect = $( "li[controller='"+type+"']" ).find("select");
			
			if (type === "changeEmail") {
				
				
				
				$.alertable.confirm('Are you sure?').then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					var newVal = $(thisMenusInput).val();
					var dataString = {'type':type,'userID':userID,'newVal':newVal};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userID);
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			
				
				
			}
			
			else if (type === "changeRole") {

				
				$.alertable.confirm('Are you sure?').then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					var newVal = $(thisMenusSelect).children("option:selected").val();
					var dataString = {'type':type,'userID':userID,'newVal':newVal};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userID);
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			
				
				
			}
			
			else if (type === "changeLevel") {

				
				$.alertable.confirm('Are you sure?').then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					var newVal = $(thisMenusSelect).children("option:selected").val();
					var dataString = {'type':type,'userID':userID,'newVal':newVal};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userID);
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			
				
				
			}
			
			else if (type === "changeGroup") {

				
				$.alertable.confirm('Are you sure?').then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					var newVal = $(thisMenusSelect).children("option:selected").val();
					var dataString = {'type':type,'userID':userID,'newVal':newVal};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userID);
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			
				
				
			}
			
			else if (type === "changeTitle") {
				
				
				
				$.alertable.confirm('Are you sure?').then(function() {
					//closing menu
					$( "#closeMenu").trigger( "click" );
					var newVal = $(thisMenusInput).val();
					var dataString = {'type':type,'userID':userID,'newVal':newVal};
		  
						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userID);
						},
						error: function(result){
							alert("Error.");
						}
						});
					
				});
			
				
				
			}
		
		
		
		});

		//bulk archive
		$(document).on('click','#archive-btn2', function() {
			
			var userIDs = [];
			
		$.alertable.confirm('Are you sure?').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var userID = $(this).attr("userid");
			  if ($(this).is(':checked')) {
					
					userIDs.push(userID);
			  }
			else 
			{
				userIDs = $.grep(userIDs, function(value) {
						  return value != userID;
						});
			}
			
		});
			
		
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=deactivateMultiple&userIDs='+userIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userIDs);
						},
						error: function(result){
							alert("Error.");
						}
					});	
			
			
			
		});
		
	});
	
		//bulk reactivate
		$(document).on('click','#reactivate-btn2', function() {
			
			var userIDs = [];
			
		$.alertable.confirm('Are you sure?').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var userID = $(this).attr("userid");
			  if ($(this).is(':checked')) {
					
					userIDs.push(userID);
			  }
			else 
			{
				userIDs = $.grep(userIDs, function(value) {
						  return value != userID;
						});
			}
			
		});
			
		
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=reactivateMultiple&userIDs='+userIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userIDs);
						},
						error: function(result){
							alert("Error.");
						}
					});	
			
			
			
		});
		
	});
	
		//bulk delete
		$(document).on('click','#delete-btn2', function() {
			
			var userIDs = [];
			
		$.alertable.confirm('Are you sure? THIS CANNOT BE UNDONE!').then(function() {
		$( ".projectsTable tbody tr td input[type='checkbox']" ).each(function() {
			var userID = $(this).attr("userid");
			  if ($(this).is(':checked')) {
					
					userIDs.push(userID);
			  }
			else 
			{
				userIDs = $.grep(userIDs, function(value) {
						  return value != userID;
						});
			}
			
		});
			
		
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=deleteMultiple&userIDs='+userIDs,
				    		type: 'POST',
							success: function(result){
								var entries = result.printBack;
								reloadSuccess(entries, userIDs);
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
					<h3><strong><a href="/dashboard/settings/">Settings:</a></strong> User Management</h3>
					
					</div>
					
				<div class="row" style="margin-bottom:20px;">
					<div class="col-sm-12">
        			
			
			<div class="col-sm-12">
			
				<div id="projectsTableContainer" class="table-responsive usersSettingsTable">
				<table class="projectsTable" id="printBack">
					<thead>
					<tr>
						<th id="orderBy">ID</th>
						<th>Username</th>
						<th>Email</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Role</th>
						<th>Title</th>
						<th>Status</th>
						<th>Requested Group</th>
						<th>Current Group</th>
						<th>Last Active</th>
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
</div>    

    
     <?php echo $scripts ?>
     
    </body>
</html>