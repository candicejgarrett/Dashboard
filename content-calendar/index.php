<?php 
include_once('../header.php');
//CHECKING ROLE
if ($myRole === 'Admin' || ($myRole === 'Editor') && $groupID == "1") {
	$canAdd = '<button class="pull-right createNew noExpand" style="margin-top:-25px;" id="addNewEvent" data-toggle="modal" data-target="#addEvent"><i class="fa fa-plus" aria-hidden="true"></i></button>';
	$canDrag = "";
}
else {
	$canAdd = "";
	$canDrag = 'eventStartEditable: false,';
}

?>
   <html class="x-content-calendar">
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
<?php echo $stylesjs ?>

<link href='/dashboard/css/fullcalendar.css' rel='stylesheet' />
<link href='/dashboard/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='/dashboard/js/moment.min.js'></script>
<script src='/dashboard/js/fullcalendar.min.js'></script>
<script src='/dashboard/js/pages/content-calendar.js'></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
	#addPreviewImage,#addPreviewImageMobile,#addPreviewImageMobile {display:none;}
	#mobile {display:none}
	.mySecondaryTabs .active {background:#4801FF;color:#ffffff}
	.mySecondaryTabs {    margin: 0px;
    padding: 0px 0px 10px;
    border-bottom: 1px solid #f1f1f1;}
	.mySecondaryTabs li {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 50px;
	cursor:pointer;
}
	
	
	</style>
<script>

	$( window ).on( "load", function() {

		
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
		
		function successConfirm() {
		$(".deleteConfirm.confirmed").addClass("successConfirm");
					
					setTimeout(function() {
						$(".deleteConfirm.confirmed").html("Success!");
						$(".deleteConfirm.confirmed").prepend('<span class="successfulConfirm"><i class="fa fa-check" aria-hidden="true"></i></span>');
					}, 200);
	}
		
		function noEvent() {
			$("#previewEventInfo,#removeEvent,#viewEventModal-btn,.modalHeader,.modal-body").hide();
			$("#viewEvent .modal-body").after("<div class='col-sm-12 text-center' id='missingEvent'><h1 class='fourohfour' style='font-size:30px !important'><i style='font-size:50px; color:#ff0000' class='fa fa-exclamation-triangle' aria-hidden='true'></i><br>404</h1><h3 class='text-center'>This event has been deleted or does not exist.</h3></div>");
			
			setTimeout(function(){
$("#missingEvent").fadeIn();
}, 500);
		}
		
		function loadEvent(eventID){
			
			$('#missingEvent').remove();
			
			$("#printCopyLink").html("");
			$.ajax({
				    		url: 'process.php',
				    		data: 'type=viewEvent&eventid='+eventID,
				    		type: 'POST',
				    		dataType: 'json',
				    		success: function(response){	
								
								
				    			if (response.printEventID === null) {
										noEvent();
								}
								else {
										$("#previewEventInfo,#removeEvent,#viewEventModal-btn,.modalHeader").show();
										$("#printEventID").val(response.printEventID);
									$("#printEventTitle").html(response.printEventTitle);
									$("#printEventDescription").html(response.printEventDescription);
									$("#printEventStartDate").html(response.printEventStartDate);
									$("#printEventEndDate").html(response.printEventEndDate);
									$("#printEventPP").attr("src",response.printEventPP);
									$("#printEventCategory").html(response.printEventCategoryName);
									$("#printEventCreatedBy").html(response.printEventCreatedBy);
									$("#printEventPreviewImage").html(response.printEventPreviewImage);
									$("#printCTAs").html(response.printCTAs);
									$("#printUploadLink").html(response.printUploadLink);
									$("#printEventAllDay").attr("disabled", true);
									$("#printPreview").html(response.previewImageFinal);
									if (response.printEventAllDay === "true") {
										
										$('#printEventAllDay,#editEventAllDay').prop('checked', true);
									}
									else {
										$('#printEventAllDay,#editEventAllDay').prop('checked', false);
									}
								
									
										$('#calendar').fullCalendar( 'gotoDate', response.printEventJumpToDate );
										$("#printCopyLink").prepend('<center><div class="copyLink"><i class="fa fa-link" aria-hidden="true"></i> Share</div></center>');
									
										
									$("#copyLinkInput").val(window.location.href+"eventID="+response.printEventID);
									
										$('#calendar').fullCalendar( 'gotoDate', response.printEventJumpToDate );
									
		              				
										
								}
									
				    		}
				    	});
			
		}

		function loadAllEvents() {
			$.ajax({
				url: 'process.php',
				type: 'POST', // Send post data
				data: 'type=fetch',
				async: false,
				success: function(s){
					json_events = s;
					$(".working").fadeOut('fast'); 
					
					$("#calendar").fadeIn(); 
					
					
				}
			});
		}
		
		function getFreshEvents(){
			$('#calendar').fullCalendar('removeEvents');
		$.ajax({
			url: 'process.php',
	        type: 'POST', // Send post data
	        data: 'type=fetch',
	        async: false,
	        success: function(s){
	        	freshevents = s;
				$( "#hideCategories .eventCheckbox" ).removeClass("unchecked");
	        }
		});
		
			setTimeout(function(){
			  $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
			}, 10);
		
	}
		
		function reloadEvent() { 
			var eventID = $("#printEventID").val();
			loadEvent(eventID);
		}

		var getEventID = GetURLParameter('eventID');
		if (getEventID != null) {
			$('#viewEvent').modal('show');
		 	loadEvent(getEventID);
			loadAllEvents();
		}
		else {
			loadAllEvents();
		}
		
		var currentMousePos = {
	    x: -1,
	    y: -1
	};
		jQuery(document).on("mousemove", function (event) {
        currentMousePos.x = event.pageX;
        currentMousePos.y = event.pageY;
    });

		
		/* initialize the calendar
		-----------------------------------------------------------------*/

		$('#calendar').fullCalendar({
			events: JSON.parse(json_events),
			utc: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			eventRender: function(event, eventElement) {
					eventElement.css('background',event.Color);	
					eventElement.attr('categoryid',event.CategoryID);	
					eventElement.attr('hideEventID',event.id);
			},
			eventLimit: true,
			eventOrder: "title",
			views: {
				month: {
					eventLimit: 6, // adjust to 8 only for agendaWeek/agendaDay
					eventOrder: "title"
				}
			},
			editable: true,
			droppable: true, 
			<?php echo $canDrag?>
			slotDuration: '00:30:00',
			eventDrop: function(event, delta, revertFunc) {
		        var title = event.title;
		        var start = event.start.format();
		        var end = (event.end == null) ? start : event.end.format();
				
				
		        $.ajax({
					url: 'process.php',
					data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
					type: 'POST',
					dataType: 'json',
					success: function(response){
						
						if(response.status != 'success')		    				
						revertFunc();
						
						var eventID =event.id;
				
						
					},
					error: function(e){		    			
						revertFunc();
						alert('Error processing your request: '+e.responseText);
					}
				});
		    },
			eventClick: function(event, jsEvent, view) {
		    	
				var eventID = event.id;
				loadEvent(eventID);
				
				
			},
			eventResize: function(event, delta, revertFunc) {
				
				var title = event.title;
				var end = event.end.format();
				var start = event.start.format();
		        $.ajax({
					url: 'process.php',
					data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
					type: 'POST',
					dataType: 'json',
					success: function(response){
						if(response.status != 'success')		    				
						revertFunc();
					},
					error: function(e){		    			
						revertFunc();
						alert('Error processing your request: '+e.responseText);
					}
				});
				var eventID =event.id;
				
		    },
			
		});
		
		//adding event
		$(document).on('click','#addEvent-btn', function() {
			
				var addEventTitle = $("#addEventTitle").val();
				var addEventStartDate = $("#addEventStartDate").val();
				var addEventEndDate = $("#addEventEndDate").val();
				var addEventCategory = $("#addEventCategory").find(":selected").val();
				var addEventDescription = $("#addEventDescription").val();  
				var addEventAllDay;
				if ($('#addEventAllDay').prop('checked')) {
					var addEventAllDay = "true";
				}
				 else {
					 var addEventAllDay = "false";
				 }
				var desktopImage = $('#newEventAddPreviewDesktopImage').prop('files')[0]; 
				var mobileImage = $('#newEventAddPreviewMobileImage').prop('files')[0]; 
			
				 var type = "new";
				 var dataString = {'type':type,'title':addEventTitle,'startdate':addEventStartDate,'enddate':addEventEndDate,'Category':addEventCategory,'Description':addEventDescription,'allDay':addEventAllDay,'desktopImage':desktopImage,'mobileImage':mobileImage};
			
			
					var data = new FormData();
        			data.append("type", type);
					data.append("title", addEventTitle);
					data.append("startdate", addEventStartDate);
					data.append("enddate", addEventEndDate);
					data.append("Category", addEventCategory);
					data.append("Description", addEventDescription);
					data.append("allDay", addEventAllDay);
					data.append("desktopImage", desktopImage);
					data.append("mobileImage", mobileImage);
			
						$.ajax({
				    		type: "POST",
							url: "process.php",
							data: data,
							dataType: 'text',  // what to expect back from the PHP script, if anything
							cache: false,
							contentType: false,
							processData: false,
							success: function(results){
								var eventID = results.eventID; 
								
								getFreshEvents();
							},
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                  }
						});	
			 			
				});
		//removing event
			 $(document).on('click','#removeEvent.confirmed', function() {
				 var eventID = $("#printEventID").val();
				 
					  
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=remove&eventid='+eventID,
				    		type: 'POST',
				    		dataType: 'text',
				    		success: function(response){
								
								//confirmation animation
					successConfirm();
					//END confirmation animation
								
								
								setTimeout(function(){
								 $("#viewEvent").modal("hide");
								}, 1000);
								
								setTimeout(function(){
								 getFreshEvents();	
								}, 1300);
								
				    		}
			    		});	
					 
					
						
				});
		//editing event
			$(document).on('click','#editEventModal-btn', function() {
				$("#previewEventInfo").hide();
				$(this).after('<button type="button" class="remove noExpand" id="cancel-btn"><i class="fa fa-times" aria-hidden="true"></i></button>');
				var eventID = $("#printEventID").val();
				 
				 $.ajax({
				    		url: 'process.php',
				    		data: 'type=viewEvent&eventid='+eventID,
				    		type: 'POST',
				    		dataType: 'json',
				    		success: function(response){	
								
								$("#editEventID").val(response.printEventID);
									$("#editEventTitle").val(response.printEventTitle);
									$("#editEventDescription").val(response.printEventDescription);
									if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
										$("#editEventStartDate").datetimepicker('setDate', (new Date(response.printEventStartDateStandard)));
										$("#editEventEndDate").datetimepicker('setDate', (new Date(response.printEventEndDateStandard)));
										}
									else {
										$("#editEventStartDate").val(response.printEventStartDateStandard);
										$("#editEventEndDate").val(response.printEventEndDateStandard);
									}
									$("#editEventCategory").val(response.printEventCategory);
									$("#editEventCreatedBy").val(response.printEventCreatedBy);
									$("#editPreviewImage").val(response.printPreviewImage);
									$("#editEventModal-btn").hide();
								$("#saveEventModal-btn").show();
								$("#editEventInfo").show();
				    		}
				    		
				    	});
				 
						
				});
			//saving event
			$(document).on('click','#saveEventModal-btn', function() {
				
				var newEventTitle = $("#editEventTitle").val();
				var newEventStartDate = $("#editEventStartDate").val();
				var newEventEndDate = $("#editEventEndDate").val();
				var newEventCategory = $("#editEventCategory").find(":selected").val();
				var newEventDescription = $("#editEventDescription").val();  
				var newEventPreviewImage = $("#editEventPreviewImage").val();
				var eventID = $("#printEventID").val();
				var editEventAllDay;
				
				
				
				if ($('#editEventAllDay').prop('checked')) {
					var editEventAllDay = "true";
				}
				 else {
					 var editEventAllDay = "false";
				 } 
				
				
				if (newEventTitle=== "") {
					$("#editEventTitle").addClass("required");
					return false;
				}
				
				else if (newEventEndDate < newEventStartDate) {
					$('#editEventEndDate').addClass("required");
					return false;
				}
				
				
				
				
				
				 $.ajax({
				    		url: 'process.php',
				    		data: 'type=saveEvent&eventid='+eventID+'&title='+newEventTitle+'&startdate='+newEventStartDate+'&enddate='+newEventEndDate+'&Category='+newEventCategory+'&Description='+newEventDescription+'&PreviewImage='+newEventPreviewImage+'&allDay='+editEventAllDay,
				    		type: 'POST',
				    		dataType: 'text',
				    		success: function(response){	
								$("#cancel-btn").remove();
								setTimeout(function(){
									getFreshEvents();
									reloadEvent();
								}, 300);
							}
				    		
				    	});
				 
	
				});
		
		//file upload
		$(document).on('click','#saveEventPreviewImage-btn,#saveEventPreviewImageMobile-btn', function() {
				var file_data = $(this).prev().prop('files')[0];   
				var eventID = $('#printEventID').val();
				var mockupType = $(this).attr("mockupType");
				var form_data = new FormData();                  
				form_data.append('type', "fileUpload");
			form_data.append('file', file_data);
				form_data.append('mockupType', mockupType);
				form_data.append('eventID',eventID); 
				var thisButton = $(this);
			
				if (!file_data) {
					$(this).prev().addClass("required");
					var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
					
					return false;
					}
				else {
					$.ajax({
                url: 'process.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(results){
                   
					setTimeout(function(){
							getFreshEvents();
							reloadEvent();
					}, 300);
                },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                  }
     			});
				}
			
			
				
				
				
			});

		
		//delete file
		$(document).on('click','#removeEventPreviewImage-btn.confirmed,#removeEventPreviewImageMobile-btn.confirmed', function() {
				var eventID = $('#printEventID').val();
				var path = $(this).parent().next().next("a").attr("href");
				var mockupType = $(this).attr("mockupType");
		
				var form_data = new FormData();  
				form_data.append('type',"deleteFile"); 
				form_data.append('mockupType',mockupType); 
				form_data.append('eventID',eventID); 
				form_data.append('path',path); 
				$.ajax({
                url: 'process.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
					
					//confirmation animation
					successConfirm();
					//END confirmation animation
					   setTimeout(function(){
						getFreshEvents();
						reloadEvent();
						}, 1200);
                },
                  error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                  }
     			});
				
				
			});
		
		
		
		
		//getting event on click from search
		$(document).on('click','.event-btn', function() {
			var thisEventID = $(this).attr("eventid");
			loadEvent(thisEventID);
		});
		
		// cancel edit
		$(document).on('click','#cancel-btn', function() {
			$("#editEventInfo,#saveEventModal-btn").hide();
			$("#previewEventInfo,#editEventModal-btn").fadeIn();
			$('#cancel-btn').remove();
		});

		//clear filtersclearFilter
		$(document).on('click','#clearFilter', function() {
			getFreshEvents();
			
			$(this).remove();
		});
	});

	
	
$(document).ready(function() {
	$('#addEvent').on('hidden.bs.modal', function () {
		//clearing input fields
		var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!

			var yyyy = today.getFullYear();
			if(dd<10){
				dd='0'+dd;
			} 
			if(mm<10){
				mm='0'+mm;
			} 
			var today = yyyy+'-'+mm+'-'+dd;
		
		
		$('#addEvent input,#addEvent textarea').val("");
		$('#addEvent select').val($("#addEvent select option:first").val());
		$("#addEvent input[type='checkbox']").prop("checked", false);
		
		if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
			
		}
		else {
			$('#addEventStartDate').val(today+"T09:00");
			$('#addEventEndDate').val(today+"T09:30");
		}
		
		//END clearing input fields
		
	$("#newEventAddPreviewDesktopImage").replaceWith($("#newEventAddPreviewDesktopImage").val('').clone(true));
		$("#newEventAddPreviewMobileImage").replaceWith($("#newEventAddPreviewMobileImage").val('').clone(true));
		
		if ($("#newEvent").find("#mobileMock").hasClass("active")) {
			$("#newEvent").find("#mobileMock").removeClass("active").removeClass("in");
			$("#newEvent .myTabs").find("li").last().removeClass("active")
			$("#newEvent .myTabs").find("li").first().addClass("active");
			$("#newEvent").find("#desktopMock").addClass("active").addClass("in");
		}
		else {
			
			}
		
		
	});
	
	$('#viewEvent').on('hidden.bs.modal', function () {

		$('#cancel-btn').remove();

	});
});
</script>

    </head>

    <body>
    
<nav class="navbar navbar-default print_remove" style="background:#ffffff; border:none;">
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
			<?php include("../templates/alerts.php") ?>
		</div>
		</div>
     	<div class="row contentCalendar">
			 <div class="col-sm-12">
				<div class="whitebg" style="min-height: auto !important;">
						<div class="row">
			 				<div class="col-sm-12">

							<div class="header print_remove">
							<!--<a href="export.php" class="addbtn pull-right" style="margin-top:-17px;" id="export">Export CSV &nbsp;<i class="fa fa-download" aria-hidden="true"></i></a>-->
							<?php echo $canAdd?>

							<h3>Content Calendar</h3>

							</div>
							<div class="row print_remove">
							<div class="col-sm-6 pull-left">
								 <label class="formLabels">Categories:</label>
								<hr style="margin-top:0px">
						<div id="hideCategories">
							<?php 
								$query = "SELECT DISTINCT * FROM `Calendar Categories` ORDER BY `Category` ASC";
								$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
								while ($row = mysqli_fetch_array($query_result)) {
									$categoryID = $row["CalendarCategoryID"];
									$categoryTitle = $row["Category"];
									$categoryColor = $row["Category Color"];
									//echo '<div class="eventLabels" id="'.$categoryID.'" style="font-weight:bold;background:'.$categoryColor.' !important">'.$categoryTitle.'</div>';
									
									echo '<div class="eventFilter"><div class="eventCheckbox activate" id="'.$categoryID.'" style="background:'.$categoryColor.';border:2px solid '.$categoryColor.'"><i class="fa fa-check" aria-hidden="true"></i></div><div class="eventCheckboxLabel">'.$categoryTitle.'</div></div>';

								}
								
			
							?>
						</div>
							</div>
							<div class="col-sm-6 pull-right">
								<label class="formLabels">Jump To Date:</label>
								<table width="100%" border="0" cellspacing="0" cellpadding="010">
									  <tbody>
										<tr>
										  <td width="90%"><input type="date" style="width:100%;display:inline-block;"></td>
										  <td width="10%" valign="top"><button id="datePicker" class="createNew noExpand"><i class="fa fa-arrow-right" aria-hidden="true"></i></button></td>
										</tr>
									  </tbody>
									</table>
								
								<label class="formLabels">Search:</label>
									
									<table width="100%" border="0" cellspacing="0" cellpadding="010">
									  <tbody>
										<tr>
										  <td width="100%">
											  <input type="text" autocomplete="off" name="searchEventsTitle" id="searchEventsTitle" placeholder="By event title... Ex: &#34;FILA Event&#34;" style="width:100%;display:inline-block;"></input>
										  		<div style="position: relative; width: 100%;">
										  		<div id="ccSearchResultsContainer">
													<div class="ccSearchResultsHeader">
														<div class="filterContainer">
															
															<div class="row">
																<div class="col-sm-12">
																	<div id="closeSearchResults" class="pull-right"><i class="fa fa-times" aria-hidden="true"></i></div>
																	<div id="specifyDate">Specify Date</div>
																	<div id="specifyDateCancel">Clear Dates</div>
																</div>
																<div id="specifyDateContainer">
																	<div class="col-sm-6">
																		<p>Start Date:</p>
																		<input type="datetime-local" id="filterStartDate"></input>
																	</div>
																	<div class="col-sm-6">
																		<p>End Date:</p>
																		<input type="datetime-local" id="filterEndDate"></input>
																	</div>
																</div>
															</div>
														<hr>
														<div class="row">
																<div class="col-sm-12">
																	<p>Exclude categories:</p>
														<?php 
															$query = "SELECT DISTINCT * FROM `Calendar Categories` ORDER BY `Category` ASC";
															$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
															while ($row = mysqli_fetch_array($query_result)) {
																$categoryID = $row["CalendarCategoryID"];
																$categoryTitle = $row["Category"];
																$categoryColor = $row["Category Color"];
																echo '<div class="eventLabels" categoryid="'.$categoryID.'" style="font-weight:bold;background:'.$categoryColor.' !important">'.$categoryTitle.'</div>';

															}


														?>
																</div>
															</div>
													
															
														
														</div>
														
													
										  			<h3>Search Results (<span id="resultCount">0</span>)</h3>
													</div>
										  			<div id="ccSearchResults" class="row"></div>
													
										  		</div>
												</div>
										</td>
										  
										</tr>
									  </tbody>
									</table>
									
								
								
								



							</div>
							</div>
							
				 
				 			<div class="col-sm-12 text-center">
				 
					
					 <hr>
								<center><div class="working"><p>Loading...</p><br><img src="/dashboard/images/Gear.gif" style="width:100px !important;"></div></center>
				 <div id='calendar'></div><br>
					<center>
				<!--<a href="share/" target="_blank" style="font-size:20px;" class="print_remove">Sharable Link</a></center>-->
				 
			 </div>
						</div>
			 		</div>	
			</div>
			 </div>	
    	
			 
			
      		
       </div>
     
       
      </div>
	</div>
</div>    

 <!-- Add Event Modal -->
<div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Add New Event</h4>
      </div>
      <div class="modal-body">
		  <form id="newEvent">
        		<div class="form-sm">
        		<div class="row">
						<div class="col-sm-12">
      						<div class="formLabels">Title:*<span class="ast">*</span></div> <input type="text" id="addEventTitle" class="validate">
      					</div>
      			</div>
      			<div class="row">
						<div class="col-sm-6">
      						<div class="formLabels">Start Date:*<span class="ast">*</span></div><input type="datetime-local" id="addEventStartDate" class="validate">
      					</div>
      					<div class="col-sm-6">
      						<div class="formLabels">End Date:*<span class="ast">*</span></div> <input type="datetime-local" id="addEventEndDate" class="validate">
      					</div>
      			</div>
      			<div class="row">
						<div class="col-sm-6">
      						<div class="formLabels">Category:*<span class="ast">*</span></div>
							<select name="createEventCategory" id="addEventCategory" style="width:100%">
								<?php 
									$query = "SELECT DISTINCT * FROM `Calendar Categories` ORDER BY `Category` ASC";
									$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
									while ($row = mysqli_fetch_array($query_result)) {
										echo "<option value='".$row["CalendarCategoryID"]."'>".$row["Category"]." Event</option>";
									}
								?>
							</select>
      					</div>
      					<div class="col-sm-6">
      						<div class="formLabels">All Day Event:</div><input type="checkbox" id="addEventAllDay" class="">
      					</div>
      			</div>
      			<div class="row">
						<div class="col-sm-12">
      						<div class="formLabels">Description:</strong><pre><textarea id="addEventDescription"></textarea></pre>
      					</div>
      			</div>		
      			</div>
			  <div class="row">
						<div class="col-sm-12">
							<div class="formLabels">Mockups:</div>
							<hr style="margin: 0px 0px 9px;">
			  <ul class="myTabs" role="tablist">
					<li role="presentation" class="active"><a href="#desktopMock" role="tab" data-toggle="tab" class="active one">Desktop</a>
					  </li>
				  <li role="presentation"><a href="#mobileMock" role="tab" data-toggle="tab" class="two">Mobile</a>
				  </li>
			</ul>
					<div class="tab-content">		
				  <div id="desktopMock" role="tabpanel" class="tab-pane fade in active">
					  <br>
						<input type="file" id="newEventAddPreviewDesktopImage" name="file">
					</div>
							
					<div id="mobileMock" role="tabpanel" class="tab-pane fade">
								 <br>
						<input type="file" id="newEventAddPreviewMobileImage" name="file">
						
					</div>
				  
				  </div>
				  
				  
				  </div>		
      			</div>
     			</div>
      </div>
      <div class="modal-footer">
       
        <button type="submit" class="genericbtn green_bg" data-dismiss="modal" id="addEvent-btn">Add</button>
		  <button type="button" class="genericbtn" data-dismiss="modal">Close</button>
         
      </div>
	  </form>
    </div>
  </div>
</div>        
          
<!-- VIEW EVENT-->                           
 <div class="modal fade" id="viewEvent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		
	<div class="showContent">
      <div class="modalHeader">
		  <div id="printCopyLink" class="pull-right"><input type="text" id="copyLinkInput"></div>
      	<table width="60%" border="0" cellspacing="0" cellpadding="5">
			  <tbody>
				<tr>
				  <td valign="middle" width="80px"><img src="" id="printEventPP" style="border-radius:50%;width:60px;"></td>
				  <td valign="middle"><p>Event Created By:<br><strong id="printEventCreatedBy"></strong></p></td>
				</tr>
			  </tbody>
		</table>
		  
      </div>
		
      <div class="modal-body">
		 
		  <div class="row">
		  		<div class="col-sm-6">
		  				<div class="form-sm" id="previewEventInfo">
      							<div class="row">
									<div class="col-sm-12">
									<div class="formLabels" style="margin-top:0px;">Title: </div><span id="printEventTitle"></span></div>
									<div class="col-sm-6">
									<div class="formLabels">Start Date:</div> <span id="printEventStartDate"></span>
									</div>
									<div class="col-sm-6">
										<div class="formLabels">End Date:</div>
										<span id="printEventEndDate"></span>
									</div>
									<div class="col-sm-6">
									<div class="formLabels">Category:</div> <span id="printEventCategory"></span> Event</div>
									<div class="col-sm-6">
									<div class="formLabels">All Day?</div><input type="checkbox" id="printEventAllDay"></div>
									<div class="col-sm-12">
									<div class="formLabels">Description:</div><pre><span id="printEventDescription" style="word-break: break-all;"></span></pre></div>
									</div>
							  		
							  		
								</div>
     						
						<div class="form-sm" id="editEventInfo">
									<div class="row">
										<div class="col-sm-12">
								<div class="formLabels">Title:*</div> <input type="text" id="editEventTitle" class="validate">
									</div>	
										<div class="col-sm-12"><div class="formLabels">Start Date:*</div><input type="datetime-local" id="editEventStartDate"></div>	
										<div class="col-sm-12"><div class="formLabels">End Date:*</div> <input type="datetime-local" id="editEventEndDate"></div>	
										
										
										<div class="col-sm-6"><div class="formLabels">Category:*</div>
											<select type="text" id="editEventCategory" style="width:100%;">
									<?php 
										$query = "SELECT DISTINCT * FROM `Calendar Categories` ORDER BY `Category` ASC";
										$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
										while ($row = mysqli_fetch_array($query_result)) {
											echo "<option value='".$row["CalendarCategoryID"]."'>".$row["Category"]." Event</option>";
										}
									?>
								</select>
								</div>	
										<div class="col-sm-6"><div class="formLabels">All Day?</div><input type="checkbox" id="editEventAllDay"></div>	
										<div class="col-sm-12">
								<div class="formLabels">Description:</div><pre><textarea id="editEventDescription"></textarea></pre>
									</div>	
										
										
								
							</div>
						  
						</div>
				
						<div class="col-sm-12" style="padding:0px">
										<div id="printCTAs" class="pull-right"></div>
										</div>
		  		</div>
			  
			  	<div class="col-sm-6" id="printPreview">
		  		
		  		</div>
		  </div>
		  
		  
        		
      </div>
      <div class="modal-footer">
       <button type="button" class="genericbtn" data-dismiss="modal">Close</button>
        
      </div>
	</div>
      </div>
    </div>
  </div>
</div>
        

    <?php echo $scripts?>

     <input type="hidden" id="holdingNotificationCount"> 
     <input type="hidden" id="printEventID"> 
      <input type="hidden" id="eventID"> 
    </body>
</html>