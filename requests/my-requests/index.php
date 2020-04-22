<?php 
include_once('../../header.php');



$query = "SELECT DISTINCT COUNT(`TicketID`) FROM `Tickets` WHERE `Status` = 'Complete' AND `Requested By` = '$userID'";
$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $query_result->fetch_assoc()) {
		if ($row["COUNT(`TicketID`)"]==0) {
			$completed = 0;
		}
		else {
			$completed = $row["COUNT(`TicketID`)"];
		}
}

$query2 = "SELECT DISTINCT COUNT(`TicketID`) FROM `Tickets` WHERE `Status` = 'In Progress' AND `Requested By` = '$userID'";
$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $query2_result->fetch_assoc()) {
		if ($row["COUNT(`TicketID`)"]==0) {
			$pending = 0;
		}
		else {
			$pending = $row["COUNT(`TicketID`)"];
		}
}
$query3 = "SELECT DISTINCT COUNT(`TicketID`) FROM `Tickets` WHERE `Status` = 'Incomplete' AND `Requested By` = '$userID'";
$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team Project failed: ".mysql_error());
	
	 while($row = $query3_result->fetch_assoc()) {
		if ($row["COUNT(`TicketID`)"]==0) {
			$incomplete = 0;
		}
		else {
			$incomplete = $row["COUNT(`TicketID`)"];
		}
}

?>
   <html class="x-template-tickets">
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
	.overviewSlid .row {
		margin-top:-10px !important; 
	}
</style>
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function() {
	
	function applyFilter() {
		var appliedFilter = $('.filterBlock.active').attr("id");
					
					if (appliedFilter === undefined) {
						$('.individualTickets .ticketStatus:not(:contains("Incomplete"))').parent().hide();
						$('.individualTickets .ticketStatus:contains("Incomplete")').parent().fadeIn();
						$("#Incomplete").addClass("active");
						}
					else {
						$('.individualTickets .ticketStatus:not(:contains("'+appliedFilter+'"))').parent().hide();
						$('.individualTickets .ticketStatus:contains("'+appliedFilter+'")').parent().fadeIn();
						$('.filterBlock:contains("'+appliedFilter+'")').addClass("active");
					}
					
	}
	
	function loadTicket(ticketID) {
		$(".overview").show().addClass("overviewSlid");
		//making hover state stay when clicked
		$("#noTicketFound").remove();
		var dataString = {'type':"load",'ticketID':ticketID};
		
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					
					if (results.requestTitle == null) {
						$("#viewTicket").hide();
						$(".results").before("<p id='noTicketFound'>This ticket has been deleted or does not exist.</p>");
						}
					else {
					$("#allTickets .individualTickets").removeClass("hoverState");
					$("#actions").html(results.actions);
					$("#printComments").html(results.comments);
					$("#requestTitle").html(results.requestTitle);
					$("#requestDescription").html(results.requestDescription);
					$("#requestCopy").html(results.requestCopy);
					$("#ticketID").html(results.ticketID);
					$("#addNewTicketComment").attr("ticketid",results.ticketID);
					$("#requestDueDate").html(results.requestDueDate);
					if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
						$("#requestDueDateEdit").datetimepicker('setDate', (new Date(results.requestDueDateEdit)));
					}
					else {
						$("#requestDueDateEdit").val(results.requestDueDateEdit);
					}
					$("#requestURL").html(results.requestURL);
					$("#requestCategory").html(results.requestCategory);
					$("#requestCategory").attr("ProjectCategoryID",results.requestCategoryID);
					$("#requestStatus").html(results.requestStatus).attr('class', 'taskStatus').addClass(results.requestStatus);
					$("#contactPP").html("<a href='/dashboard/users/profile/?userID="+results.requestContactUserID+"'><img src='"+results.requestContactPP+"'></a>");
					$("#requestContactName").html("<a href='/dashboard/users/profile/?userID="+results.requestContactUserID+"'>"+results.requestContactName+"</a>");
					
					$("#requestTimestamp").html(results.requestTimestamp);
					$(".results").attr("id",results.ticketID);
					
					$(".results").fadeIn();
					$("#viewTicket").fadeIn();
					$("#allTickets").find("#"+ticketID).addClass("hoverState");
						//set height of comment section
	$(".ticketCommentsContainer").height($("#ticketInfo").height());
	$(".commentScroll").height($("#ticketInfo").height()-150);
					
					}
					
					//
					applyFilter();
					
				}
				});
				
		}

	function loadAllTickets() {
		
		var dataString = {'type':"getAll"};
	
		$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					
					$("#allTickets").html(results.printTickets);
					
					//
					applyFilter();
				}
				});
	}
	
	
	function GetURLParameter(sParam)
		{
			var sPageURL = window.location.search.substring(1);
			var sURLVariables = sPageURL.split('&');
			for (var i = 0; i < sURLVariables.length; i++)
			{
				var sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam)
				{
					return sParameterName[1];
				}
			}
		}

	var getTicketID = GetURLParameter('ticketID');
	if (getTicketID != null) {
		
		loadAllTickets();
		loadTicket(getTicketID);
		
		
	}
	else {
		$(".overview").show();
		loadAllTickets();
	}
	
	
	CKEDITOR.replace('requestCopyEdit');
	CKEDITOR.config.basicEntities = false;

	//add new comment
	$(document).on('focusin','#ticketComment', function() {
		$(this).parent().prev().hide("slide", { direction: "left" }, 200);
		$(this).parent().next().delay(300).show("slide", { direction: "left" }, 300);
	});
	$(document).on('focusout','#ticketComment', function() {
		if ($(this).val() === "") {
			$(this).parent().next().hide("slide", { direction: "left" }, 200);
			$(this).parent().prev().delay(300).show("slide", { direction: "left" }, 300);
			
			}
		else {
			
		}
		$("#ticketComment").attr("style", "");
	});
	
		 //search function
		 $('#searchTickets').keyup(function(){
				   var valThis = $(this).val();
					if (valThis == ""){
						$('.individualTickets').fadeIn();
					}
		
		
					$('.individualTickets').each(function(){
						
					if ($(this).find('h4:contains("'+valThis+'")').length > 0) {
							$(this).fadeIn();
					}
					else {
						$(this).hide();
					}
						
					});
		
					
			});
	
	//add comment
	$(document).on('click','#addNewTicketComment', function() {
		var ticketID = $(this).attr("ticketid");
		
		var comment = $("#ticketComment").val();
		
		
		if (!comment) {
			return false;
			}
		else {
			var dataString = {'type':"addComment",'ticketID':ticketID,'comment':comment};
		
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#ticketComment").attr("style", "");
					$("#ticketComment").val("");
					$("#ticketComment").parent().next().hide("slide", { direction: "left" }, 200);
					$("#ticketComment").parent().prev().delay(300).show("slide", { direction: "left" }, 300);
					loadAllTickets();
					loadTicket(ticketID);
				}
				});
			}
		
	});
	
	//delete comment
	$(document).on('click','.deleteComment', function() {
		var commentID = $(this).attr("commentid");
		var ticketID = $("#ticketID").text();
		
		if (!commentID) {
			return false;
			}
		else {
			var dataString = {'type':"deleteComment",'commentID':commentID};
		
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					loadAllTickets();
					loadTicket(ticketID);
				}
				});
			}
		
	});
	
	//filter
	
	$(document).on('click','.showFilters', function() {
				   $(".filterBlock").slideToggle();
	});
	
	$(document).on('click','.filterBlock:not(".active")', function() {
				   var valThis = $(this).attr("id");
					
					$(".filterBlock").removeClass("active");
		
					$(this).addClass("active");
		
				
					$('.individualTickets').each(function(){
						
					if ($(this).find('.ticketStatus').text() === valThis ) {
							$(this).fadeIn();
					}
					else {
						$(this).fadeOut();
					}
						
					});
		
					
			});
	
	$(document).on('click','.filterBlock.active', function() {
				   $('.individualTickets').fadeIn();
		$(".filterBlock").removeClass("active");
	
			});
	
	$(document).on('click','.individualTickets', function() {
		var ticketID = $(this).attr("id");
		loadTicket(ticketID);
		
	});
	//deleting
	$(document).on('click','#deleteRequest', function() {
		
		
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
			
		var ticketID = $("#ticketID").text();
		var dataString = {'type':"delete",'ticketID':ticketID};

				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					
					$(".results").fadeOut();
					
					loadAllTickets();
				}
				});
		
		});
		});
	
	//editing
	$(document).on('click','#editRequest', function() {
		$(this).after('<div id="saveRequest" class="smallIcon green"><i class="fa fa-floppy-o" aria-hidden="true"></i></div>');
		$(this).remove();
		
		var ticketID = $("#ticketID").text();
		$("#viewTicket").hide();
		$("#editTicket").show();
		$("#requestTitleEdit").val($("#requestTitle").text());	
		$("#requestURLEdit").val($("#requestURL").text());		
		$("#requestStatusEdit").val($("#requestStatus").text());	
		CKEDITOR.instances.requestCopyEdit.setData($("#requestCopy").html());
		$("#requestDescriptionEdit").val($("#requestDescription").text());		
		$("#requestCategoryEdit").val($("#requestCategory").attr("ProjectCategoryID"));
		$("#requestContactEmailEdit").val($("#requestContactEmail").text());	
			$(document).on('click','.individualTickets', function() {
			$("#editTicket").hide();
			});
		});
	
	//save
	$(document).on('click','#saveRequest', function() {
		var ticketID = $("#ticketID").text();
		var newRequestTitle = $("#requestTitleEdit").val();	
		var newRequestURL = $("#requestURLEdit").val();			
		var newRequestCopy = CKEDITOR.instances.requestCopyEdit.getData();		
		var newRequestDescription = $("#requestDescriptionEdit").val();			
		var newRequestDueDate = $("#requestDueDateEdit").val();			
		var newRequestStatus = $("#requestStatusEdit").val();
		var newRequestCategory = $("#requestCategoryEdit").find(":selected").val(); 
		var newRequestCategoryText = $("#requestCategoryEdit").find(":selected").text(); 
		
		if (!newRequestTitle || newRequestTitle === "") {
			$("#requestTitleEdit").addClass("required");
			return false;
		}
		if (!newRequestCategory ||  newRequestCategory === "") {
			$("#requestCategoryEdit").addClass("required");
			return false;
		}
		if (!newRequestDueDate ||  newRequestDueDate === "") {
			$("#requestDueDateEdit").addClass("required");
			return false;
		}
		
		
		var dataString = {'type':"save",'ticketID':ticketID,'requestTitle':newRequestTitle,'requestURL':newRequestURL,'requestDescription':newRequestDescription,'requestCopy':newRequestCopy,'requestDueDate':newRequestDueDate,'requestStatus':newRequestStatus,'requestCategory':newRequestCategory};
		
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#editTicket").hide();
					loadAllTickets();
					loadTicket(ticketID);
				}
				});	
		
				
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
						<a href="../submit/" class="genericbtn pull-right" style="margin-top:-18px;">Submit A New Ticket</a>
					<h3>My Tickets</h3>
					
					</div>
					<div class="row">
						<div class="col-sm-12">
          				
							<div class="row">
								<div class="col-sm-3">
									<div class="background ticketSide" style="height: 1170px;">
									<input type="text" id="searchTickets" placeholder="Ticket Title" style="background:none;border:1px solid #ffffff !important;color:#ffffff !important;">
									
									<div id="filters">
										<div class="showFilters">Filter By Status</div>
										<div class="filterBlock" id="Incomplete">Incomplete</div>
										<div class="filterBlock" id="In Progress">In Progress</div>
										<div class="filterBlock" id="Complete">Complete</div>
									</div>	
											
										
									<div id="allTickets">
									
									</div>
									</div>
								</div>
								<div class="col-sm-9" style="height:700px;">
									<div class="overview text-center" style="margin-left: -15px;">
										<h1>Overview</h1>
										<div class="row">
					<div class="col-sm-4">
						<h3 class="text-center">Incomplete</h3>
						<div class="requestsIcon" style="display: block;">
						
							<h3><?php echo $incomplete ?></h3>
						</div>
					</div>
					<div class="col-sm-4">
						<h3 class="text-center">In Progress</h3>
						<div class="requestsIcon" style="display: block;">
							
							<h3><?php echo $pending ?></h3>
						</div>
					</div>
					
					<div class="col-sm-4">
						<h3 class="text-center">Completed</h3>
						<div class="requestsIcon" style="display: block;">
						
							<h3><?php echo $completed ?></h3>
						</div>
					</div>
				</div>
									
									
									
									</div>
									
									
								
									<div class="results">
										
										<div class="row">
											<div class="col-sm-7" id="ticketInfo">
										<div class="ticketHeader">
											<div class="row">
											<div class="col-sm-4">
											<h5>Ticket ID: <span id="ticketID"><span></h5>
											</div>
											<div class="col-sm-8 text-right">
											<div id="actions">
											
											</div>
											</div>
										</div>
										</div>
										<div class="row">
											<div class="col-sm-12" id="viewTicket">
												
												<div class="row">
											<div class="col-sm-12">
													<h2 id="requestTitle"></h2>
													
													<hr>
													</div>
													
												<div class="col-sm-4">
													<div class="formLabels">Assigned To:</div>
												<table id="contactTable">
													<tr>
													<td id="contactPP"></td>
													<td id="requestContactName"></td>
													</tr>
												</table>
													
												</div>
													
												<div class="col-sm-4">
													<div class="formLabels">Status:</div>
												<div id="requestStatus" class=""></div>
													
												</div>	
												
												<div class="col-sm-4">
													<div class="formLabels">Due Date:</div>
												<p><span id="requestDueDate"></span></p>
													
												</div>
													</div>
												<br>
												<div class="row">
												<div class="col-sm-6">
													<div class="formLabels">Category:</div>
												<p><span id="requestCategory"></span></p>
												</div>
												
												<div class="col-sm-6">
													<div class="formLabels">URL:</div>
												<p id="requestURL"></p></div>
													
													<div class="col-sm-12">
													<div class="formLabels">Description:</div>
												<p id="requestDescription"></p></div>
												
												<div class="col-sm-12">	
												<br><div class="formLabels">Copy:</div>
												<p id="requestCopy"></p>
												</div>
													</div>
											</div>
											<div class="col-sm-12" id="editTicket">
												<br>
											<div class="row">
													<div class="col-sm-6">
														<div class="formLabels">Title:*</div>
											<input type="text" id="requestTitleEdit" class="validate">
												</div>
												<div class="col-sm-6">
												<div class="formLabels">Due Date:*</div>
												<input type="datetime-local" id="requestDueDateEdit" class="validate">
												</div>
												</div>
											<div class="row">
													<div class="col-sm-6">
														<div class="formLabels">Status:*</div>
													<select id="requestStatusEdit">
														<option value="Incomplete">Incomplete</option>
														<option value="In Progress">In Progress</option>
														<option value="Complete">Complete</option>
													</select>

													</div>
												<div class="col-sm-6">
													<div class="formLabels">Category:*</div>
													<?php
					
					$getCategories = "SELECT * FROM `Team Projects Categories` WHERE `GroupID` = '1'";
					$getCategories_result = mysqli_query($connection, $getCategories) or die ("Query to get data from Team task failed: ".mysql_error());

							echo '<select name="requestCategoryEdit" id="requestCategoryEdit" style="width:100%"><option value="">Select</option>'; // Open your drop down box

							// Loop through the query results, outputing the options one by one
							while ($row = mysqli_fetch_array($getCategories_result)) {
							echo "<option value='" . $row['ProjectCategoryID'] ."'>" . $row['Category'] ."</option>";
							}

							echo '</select>';

					?>			
													</div>
													
											</div>
											
											<div class="row">
												<div class="col-sm-12">
													<div class="formLabels">URL:</div>
												<input type="text" id="requestURLEdit">
														
													</div>
												
													<div class="col-sm-12">
													<div class="formLabels">Description:</div>
												<textarea id="requestDescriptionEdit"></textarea></p>
														
													</div>
													
													<div class="col-sm-12">
													<div class="formLabels">Copy:</div>
												<textarea id="requestCopyEdit"></textarea></p>
														
													</div>
													
											</div>
											
											
												
												
											</div>
										</div>
											</div>
											<div class="col-sm-5" id="ticketComments">
												<div class="ticketCommentsContainer commentsContainer">
													<div class="commentHeader">
														<h3>Comments</h3>
													</div>
													
													<div class="commentScroll">
													<div id="printComments">
													
													
													
													
													</div>
													</div>
													<div class="addNewTicketComment">
														<div class="row">
															<div class="col-sm-2">
																<div class="pp">
																	<img src="<?php echo $ProfilePic ?>">
																</div>
															</div>
															<div class="col-sm-10">
																<textarea id="ticketComment" placeholder="Write comment..."></textarea>
															</div>
															<div class="col-sm-2" id="showCommentSend">
																<button id="addNewTicketComment" class="smallSend noExpand"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
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
     	 	
     	 	 
		 </div>	
     	
     	
     	
     	</div>
     
       </div>
       
      
	</div>
</div>    


   
</div>  
   
    <?php echo $scripts?>

    </body>
</html>