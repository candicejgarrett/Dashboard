<?php 
include_once('../../header.php');



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

<style>
	.hoverState {
		background-color: rgba(0,0,0,0.3);
	}
	.reviewerEmails span {
		font-weight:bold;
		font-style: italic;
	}
	#step2,#step3 {
		display:none;
	}
</style>
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function() {
	
	
		 var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var finalCurrentDate = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
	
	setTimeout(function(){
	if (navigator.userAgent.indexOf("Firefox") > 0) {
              
     }
	else {
		$("#ticketDueDate").val(finalCurrentDate+"T16:30");
		
	}
	}, 1);
		 $("#pending").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
		 setTimeout(function(){
			if (navigator.userAgent.indexOf("Firefox") > 0) {
						
			}else {}
			}, 1);		
	
	
	 CKEDITOR.replace('ticketCopy');
	CKEDITOR.config.basicEntities = false;
	});
	
	
	$(document).on('click','#submitTicket', function(){
		
		
		
		var url = $("#ticketURL").val();
		var duedate = $("#ticketDueDate").val();
		var title = $("#ticketTitle").val();
		var description = $("#ticketDescription").val();
		var copy = CKEDITOR.instances.ticketCopy.getData();
		var categoryID = $("#ticketCategory").val();
		
		if (!title) {
			$("#ticketTitle").addClass("required");
			return false;
		}
		if (!categoryID) {
			$("#ticketCategory").addClass("required");
			return false;
		}
		var now = new Date();
		var formattedDuedate = new Date(duedate);
		
		now.setHours(0,0,0,0);
		formattedDuedate.setHours(0,0,0,0);
		
		if (formattedDuedate < now) {
			
			$("#ticketDueDate").addClass("required");
			return false;
		}
		
			var dataString = {'type':"submitTicket",'url':url,'dueDate':duedate,'title':title,'description':description,'copy':copy,'categoryID':categoryID};
	
		$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					if (!results.ticketID){
						alert("ERROR: Please contact Candice Garrett for assistance.");
						}
					else {
						
						alert("Your ticket has been received.");
						window.location.href = "/dashboard/requests/my-requests/?ticketID="+results.ticketID;
					}
					
					
					
				}
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
			<div class="whitebg" style="height: 1300px;">
    	 		
    	 		
					<div class="header">
					<h3>Submit A Ticket</h3>
					
					</div>
					<div class="row">
						<div class="col-sm-12">
          				
							<div class="row">
								<div class="col-sm-6">
									<div class="formLabels">Title:*</div>
							<input type="text" id="ticketTitle" required name="ticketTitle" class="validate">
									
									<div class="formLabels">Go Live Date:*</div>
						<input type="datetime-local" id="ticketDueDate" required name="ticketDueDate" class="rightField validate">
								</div>
								
								<div class="col-sm-6">
								
						<div class="formLabels">URL:</div>
						<input type="text" id="ticketURL" name="ticketURL" class="leftField">
						
						
						
						<div class="formLabels">Category:*</div>
						<?php
					
					$getCategories = "SELECT * FROM `Team Projects Categories` WHERE `GroupID` = '1'";
					$getCategories_result = mysqli_query($connection, $getCategories) or die ("Query to get data from Team task failed: ".mysql_error());

							echo '<select name="ticketCategory" id="ticketCategory" style="width:100%" class="validate"><option value="">Select</option>'; // Open your drop down box

							// Loop through the query results, outputing the options one by one
							while ($row = mysqli_fetch_array($getCategories_result)) {
							echo "<option value='" . $row['ProjectCategoryID'] ."'>" . $row['Category'] ."</option>";
							}

							echo '</select>';

					?>	

						
								
								</div>
								
								<div class="col-sm-12">
									<div class="formLabels">Description:</div>
						<textarea id="ticketDescription" name="ticketDescription"></textarea>
								</div>
								<div class="col-sm-12">
									
						<div class="formLabels">Copy:</div>
						<textarea style="height:300px;" id="ticketCopy" placeholder="Copy" name="ticketCopy">
							

						</textarea>
						<br>
							  
						<button id="submitTicket" class="genericbtn">Submit</button>		
					
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
   
    <?php echo $scripts?>

    </body>
</html>