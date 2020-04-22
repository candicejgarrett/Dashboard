<?php 
include_once('../../header.php');
require('../../connect.php');

if ($myRole != 'Admin') {
	header("location:/dashboard/404/no-access.php"); 	
} 


?>
   <html class="x-template-settings">
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
<script>
	$(document).ready(function() {
		
		
		$('#checkAll').click(function(){
		if (this.checked) {
			$(this).parent().find('.checkBox2').prop('checked', true);
		}
		else {
				$('.checkBox2').prop('checked', false);
			}
		});
		
		$(document).on('change','#selectGroup', function() {
		var selectedVal = $("#selectGroup option:selected" ).val();
		var dataString = {'type':"showAlerts",'GroupID':selectedVal};
					$.ajax({
				    		url: 'process.php',
				    		data: dataString,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								
								$("#alertResults").html(response.showAllAlerts);
								
								if (!response.showAllAlerts){
									$("#alertResults").html("<em>There are no current alerts for this group.</em>");
								
									}
							},
							error: function(response){
								alert("failed!");
							}
					});
		
		});
		
		$(document).on('click','#alertResults .fa-times', function() {
			
			if (confirm('Are you sure? This CANNOT be undone!')) {
				var alertCountID = $(this).parent().attr("alertcountid");
				var groupID = $("#selectGroup").find(":selected").val();
				
				$(this).parent().fadeOut();
				$(this).parent().prev().fadeOut();
				$.ajax({
				    		url: 'process.php',
				    		data: 'type=deleteAlert&alertCountID='+alertCountID+'&groupID='+groupID,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(response){
								alert("Successfully submitted.");
								location.reload();
							},
							error: function(response){
								alert("Failed!");
							}
					});
			}
			
			
		});
		/* Homepage RED Alert */
		$(document).on('click','#submitAlert', function() {
			var groupIDArray = [];
		$.alertable.confirm('Are you sure? A sitewide alert will be sent to all users in the selected group(s)!').then(function() {	
		
		
		$( "#alertCollumn .checkBox2:not(#checkAll)" ).each(function() {
						var groupID = $(this).val();
					  if ($(this).is(':checked')) {
							
						  	groupIDArray.push(groupID);
					  	}
					else 
					{
						groupIDArray = jQuery.grep(groupIDArray, function(value) {
						  return value != groupID;
						});
					}
				});	
		if (groupIDArray.length === 0) {
				alert("Please check at least one group.");	
		}
		else {
					var alertTitle = $("#alertTitle").val();
					var alertType = $("#alertType").find(":selected").val();  
					var alertText = $("#alertText").val();
					var alertTakeDown = $("#alertDate").val();	
			
			
			
			if (!alertTitle || !alertType || !alertText || !alertTakeDown) {
				alert("Please fill out required fields.");
				return false;
				}
			
		
				
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=sendAlert&alertTitle='+alertTitle+'&alertType='+alertType+'&alertText='+alertText+'&alertTakeDown='+alertTakeDown+'&groupIDs='+groupIDArray,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(response){
								alert("Successfully submitted.");
								location.reload();
							},
							error: function(response){
								alert("Failed!");
							}
					});
				
	
			}
		
		})
	});
		
		
});
</script>
   
    </head>

    <body>
    
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
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
		 <div class="col-sm-12">
			<div class="whitebg">
    	 	<div class="header">
					<h3><strong><a href="/dashboard/settings/">Settings:</a></strong> Send An Alert</h3>
					
			</div>
			<div class="row" id="alertCollumn">
			
			<div class="col-sm-12">
				<h4>Create A New Alert:</h4>
				<hr>
			</div>
				<div class="col-sm-6">
				<div class="formLabels">Title:*</div><input type="text" id="alertTitle" class="validate"></input>
				<div class="formLabels">Alert Type:*</div><select id="alertType" class="validate"><option value="green">Green</option><option value="red">Red</option></select>
				<div class="formLabels">Alert Text:*</div><textarea id="alertText" placeholder="" maxlength="200" class="validate"></textarea>
				</div>
				<div class="col-sm-6">
				<div class="formLabels">Take Down Date/Time:*</div> <input id="alertDate" type="datetime-local"></input><br>
				<div class="formLabels">Who should see this alert?*</div>
				<hr style=" margin: 2px 0px 10px;">
				<?php
								
								$query = "SELECT DISTINCT * FROM `Groups`";
								$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());

										while ($row = mysqli_fetch_array($query_result)) {
											$GroupName = $row['Group Name'];
											$GroupID = $row['GroupID'];
											
											echo "<input class='checkBox2' type='checkbox' name='".$GroupName."' value='".$GroupID."'>".$GroupName."<br>";
										}
											echo "<input class='checkBox2' type='checkbox' name='checkAll' id='checkAll' value='checkAll'>All";


								?>
				
				
				</div>
			 
			 <div class="col-sm-12">
				<button class="genericbtn" id="submitAlert">Submit</button>
				
			</div>
				
												
				<br>
			
			
			
			<br><br>&nbsp;
			
			
			</div>
			<hr>
			
			<div class="row">
			
				<div class="col-sm-12">
				<h3>Current Alerts (Real-time):</h3>
				<?php
								
								$getGroups = "SELECT DISTINCT * FROM `Groups`";
								$getGroups_result = mysqli_query($connection, $getGroups) or die ("Query to get data from Team task failed: ".mysql_error());
										echo "<select id='selectGroup'><option>Select</option>";
										while ($row = mysqli_fetch_array($getGroups_result)) {
											$GroupName = $row['Group Name'];
											$GroupID = $row['GroupID'];
											
											echo "<option value='".$GroupID."'>".$GroupName."</option>";
										}
											echo "</select>";


				?>
				<br><br>
				<div id="alertResults"></div>
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
        

   <?php echo $scripts?>
     
    </body>
</html>