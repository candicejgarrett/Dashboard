<?php
include_once( '../../header.php' );
require( '../../connect.php' );
?>
<html class="x-template-todo">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<?php echo $stylesjs ?>
<link href='/dashboard/css/fullcalendar.css' rel='stylesheet' />
<link href='/dashboard/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='/dashboard/js/moment.min.js'></script> 
<script src='/dashboard/js/fullcalendar.min.js'></script>
<style>
.modal-body {
    display: none;
}
</style>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --> 
<!-- WARNING: Respond.js doesn't work if you view the page via file:// --> 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]--> 
<script>

	$(document).ready(function() {
		
		$(document).on('click','#datePicker', function() {
					var newDate = $(this).parent().prev().find("input").val();
		
					if (!newDate) {
							$(this).parent().prev().find("input").addClass("required");
							return false;
						}
					else {
						$(this).parent().prev().find("input").removeClass("required");
						$('#calendar').fullCalendar( 'gotoDate', newDate );
						}
					
			 		
			 });
		
		
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

		var getEventID = GetURLParameter('eventID');
		
		function noEvent() {
			$("#viewTask .modal-body").after("<div class='col-sm-12 text-center' id='missingEvent'><h1 class='fourohfour' style='font-size:30px !important'><i style='font-size:50px; color:#ff0000' class='fa fa-exclamation-triangle' aria-hidden='true'></i><br>404</h1><h3 class='text-center'>This event has been deleted or does not exist.</h3></div>");
			$("#previewEventInfo,#removeEvent,#viewTaskModal-btn,.modalHeader,.modal-body").hide();
			
			
			setTimeout(function(){
$("#missingEvent").fadeIn();
}, 500);
		}
		
		function noReview() {
			$("#viewReview .modal-body").after("<div class='col-sm-12 text-center' id='missingReview'><h1 class='fourohfour' style='font-size:30px !important'><i style='font-size:50px; color:#ff0000' class='fa fa-exclamation-triangle' aria-hidden='true'></i><br>404</h1><h3 class='text-center'>This event has been deleted or does not exist.</h3></div>");
			$("#previewEventInfo,#removeEvent,#viewTaskModal-btn,.modalHeader,.modal-body").hide();
			
			
			setTimeout(function(){
$("#missingReview").fadeIn();
}, 500);
		}
		
		function loadEvent(eventID) {
			$('#missingEvent').remove();
						$.ajax({
				    		url: 'process.php',
				    		data: 'type=viewEvent&eventid='+eventID,
				    		type: 'POST',
				    		dataType: 'json',
				    		success: function(response){	
								$("#viewTask .loading").hide();
								
				    			if (response.printEventID === null) {
										noEvent();
									}
									else {
										$("#printEventTitle").html(response.printEventTitle);
									$("#printEventDescription").html(response.printEventDescription);
									$("#printEventStartDate").html(response.printEventStartDate);
									$("#printEventEndDate").html(response.printEventEndDate);
									$("#printEventCategory").html(response.printEventCategory);
									$("#printEventCreatedBy").html(response.printEventCreatedBy);
									$("#printEventPreviewImage").html(response.printEventPreviewImage);
									$("#printEventCreator").attr("src",response.printEventCreator);
									$("#printEventCreatorName").html(response.printEventCreatedBy);
									$("#printEventStatus strong").html(response.printEventStatus).addClass(response.printEventStatus);
									$("#viewTaskModal-btn").attr("href",response.printEventProjectLink+"&taskID="+response.printEventID);
									$("#printEventProjectTitle").html(response.printEventProjectTitle);
									$("#printComments").html(response.printComments).hide();
									$("#addNewComment").attr("taskid",response.printEventID);
										//forcing comments to go bottom of scroll
										setTimeout(function(){
									$('#printComments').scrollTop($('#printComments')[0].scrollHeight);	
											$('#printComments').show();
								}, 100);
									 $("#previewEventInfo,#removeEvent,#viewTaskModal-btn,.modalHeader,.modal-body").show();
										$('#calendar').fullCalendar( 'gotoDate', response.printEventJumpToDate );
									
										
										setTimeout(function(){
									$("#viewTask .modal-body").fadeIn("slow", function() {
										$(this).attr("style", "display: block !important");
									});
								}, 600);	
									}
									
		              				
									
									
									
									
				    		},
				    		error: function(e){
								alert('Error processing your request: '+e.responseText);
				    		}
				    	});
			
		}
		
		function loadReview(eventID) {
			
			$("#missingReview").remove();
						$.ajax({
				    		url: 'process.php',
				    		data: 'type=viewReview&eventid='+eventID,
				    		type: 'POST',
				    		dataType: 'json',
				    		success: function(response){	
								$("#viewReview .loading").hide();
								
				    			if (response.printEventID === null) {
										noReview();
									}
									else {
										$("#printReviewTitle").html(response.printEventTitle);
										
									$("#printReviewStartDate").html(response.printEventStartDate);
									$("#printReviewCategory").html(response.printEventCategory);
									$("#printReviewCreatedBy").html(response.printEventCreatedBy);
									$("#printReviewMembers").html(response.printEventMembers);
									$("#printReviewCreator").attr("src",response.printEventCreator);
									$("#printReviewCreatorName").html(response.printEventCreatedBy);
									$("#printReviewStatus strong").html(response.printEventStatus).addClass(response.printEventStatus);
									$("#viewReviewProjectModal-btn").attr("href",response.printEventProjectLink);
										$("#viewReviewModal-btn").attr("href","/dashboard/team-projects/view/review/?reviewID="+response.printEventID);
									$("#printReviewProjectTitle").html(response.printEventProjectTitle);
									
									 $("#previewEventInfo,#removeEvent,#viewTaskModal-btn,.modalHeader,.modal-body").show();
										$('#calendar').fullCalendar( 'gotoDate', response.printEventJumpToDate );
									
										
										setTimeout(function(){
									$("#viewReview .modal-body").fadeIn("slow", function() {
										$(this).attr("style", "display: block !important");
									});
								}, 600);	
									}
									
		              				
									
									
									
									
				    		},
				    		error: function(e){
								alert('Error processing your request: '+e.responseText);
				    		}
				    	});
			
		}
		
		function loadAllEvents() {
			$.ajax({
				url: 'process.php',
				type: 'POST', // Send post data
				data: 'type=fetch',
				async: false,
				success: function(response){
					//json_events = s;
					
					json_tasks = response.tasks;
					json_reviews = response.reviews;
					$(".working").fadeOut('fast'); 
					
					$("#calendar").fadeIn('slow'); 
				}
			});
		}
		
		function getFreshEvents(){
		$.ajax({
			url: 'process.php',
	        type: 'POST', // Send post data
	        data: 'type=fetch',
	        async: false,
	        success: function(s){
	        	freshevents = s;
	        }
		});
		$('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
	}
		
		if (getEventID != null) {
			$('#viewTask').modal('show');
		 	loadEvent(getEventID);
			loadAllEvents();
		}
		else {
			$('#viewTask').modal('hide');
			loadAllEvents();
		}
		
		
		$('#viewTask,#viewReview').on('hidden.bs.modal', function () {
			$(".modal-body").hide();
		})
		

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
var currentUser = <?php echo $userID ?>;
var isAdmin = "<?php echo $myRole ?>";

var todaysdate = new Date().getTime();
		
		$('#calendar').fullCalendar({
			
			eventSources: [
				 // tasks
				{
				  url: 'tasksFetch.php',
				  method: 'POST',
				  extraParams: {
					custom_param1: 'something',
					custom_param2: 'somethingelse'
				  }
				},
				  // reviews
				{
				  url: 'reviewsFetch.php',
				  method: 'POST',
				  color: '#AC32E4',
				  extraParams: {
					custom_param1: 'something',
					custom_param2: 'somethingelse'
				  }
				}
			 ]
		,
			utc: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			eventRender: function(event, eventElement) {
				
				var eventSource = JSON.stringify(event.source.url);
				if (eventSource.indexOf("tasks") >= 0) {
					
					$(eventElement).find(".fc-content").attr("data-target","#viewTask");
				
				
				eventElement.addClass(event.cadence).addClass("taskEvent");
					
					if (event.start < todaysdate && event.Status !== "Completed") {
					  eventElement.addClass('calendar_Overdue');
					}
					if (event.Status == "In Review") {
						$(eventElement).find(".fc-content").prepend('<div class="dot inReview"></div>');
					}
					if (event.Status == "Approved") {
						$(eventElement).find(".fc-content").prepend('<div class="dot approved"></div>');
					}
					
					
					if (event.Status == "Completed" || event.Status == "Complete" || event.Status == "Archived") {
				  eventElement.addClass('calendar_Complete');
				}
					
					}
				
				if (eventSource.indexOf("reviews") >= 0) {
					
					eventElement.addClass("reviewEvent");
					$(eventElement).find(".fc-content").attr("data-target","#viewReview");
					
					if (event.Status == "Approved") {
					  eventElement.addClass('calendar_Complete');
					}
					
					if (event.userStatus == "" || event.userStatus == null || event.userStatus == "Not Approved") {
						$(eventElement).find(".fc-content").prepend('<div class="dot new"></div>');
					}
					else if (event.Status == "Approved") {
						
					}
					
				}
				
				
				
				
				
				//adding hoverable content...
				$(eventElement).find(".fc-content").append('<div class="calendarHover"><div class="formLabels">Project:</div><h1>'+event.projectTitle+'</h1></div>');
				
				
				if (event.OwnerTop != currentUser) {
					event.editable = false;
				}
				if (isAdmin === "Admin") {
					event.editable = true;
				}	
			},
			eventLimit: true,
			views: {
				month: {
					eventLimit: 8 // adjust to 8 only for agendaWeek/agendaDay
				}
			},
			editable: true,
			droppable: true, 
			eventDurationEditable: false,
			slotDuration: '00:30:00',
			eventReceive: function(event){
				var title = event.title;
				var id = event.id;
				var Category = event.Category;
				var Status = event.Status;
				var start = event.start.format("YYYY-MM-DD[T]HH:mm:SS");
				$.ajax({
		    		url: 'process.php',
					data: 'type=new&title='+title+'&startdate='+start+'&enddate='+start+'&zone='+zone+'&Category='+Category+'&PreviewImage=',
		    		type: 'POST',
		    		dataType: 'json',
		    		success: function(response){
		    			event.id = response.eventid;
		    			$('#calendar').fullCalendar('updateEvent',event);
		    		},
		    		error: function(e){
		    			alert("Error!");

		    		}
		    	});
				$('#calendar').fullCalendar('updateEvent',event);
				
			},
			eventDragStart: function (event) {
                $(" .calendarHover").css("display","none").css("height","1px").css("opacity","0");
            },
			eventDrop: function(event, delta, revertFunc) {
		        var title = event.title;
		        var start = event.start.format();
		        var end = (event.end == null) ? start : event.end.format();
				var eventSource = JSON.stringify(event.source.url);
				
				$(" .calendarHover").removeAttr("style");
				
				
				if (eventSource.indexOf("tasks") >= 0) {
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
				
				}
				if (eventSource.indexOf("reviews") >= 0) {
		        $.ajax({
					url: 'process.php',
					data: 'type=resetReviewDate&start='+start+'&eventid='+event.id,
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
				
				}
				
		    },
			eventClick: function(event, jsEvent, view) {
				
				var eventSource = JSON.stringify(event.source.url);
				var eventID = event.id;
				
				if (eventSource.indexOf("tasks") >= 0) {
		        
					loadEvent(eventID);
				}
				else {
					loadReview(eventID);
				}
				
			},
			
			
		});

		
		//hover event for task items
		$(document).on('mouseenter', '.fc-event', function() {
			var calendarHover =$(this).find(" .calendarHover");
			$(calendarHover).slideDown();
		});
		$(document).on('mouseleave', '.fc-event', function() {
			var calendarHover =$(this).find(" .calendarHover");
			$(calendarHover).hide();
		});
		

		
	});

</script>
</head>

<body>
<nav class="navbar navbar-default print_remove" style="background:#ffffff; border:none;">
  <div class="container-fluid">
    <?php include("../../templates/topNav.php") ?>
  </div>
  <!-- /.container-fluid --> 
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
      <div class="row contentCalendar">
        <div class="col-sm-12" id="moveOver">
          <div class="whitebg">
            <div class="header">
              <h3>Calendar</h3>
            </div>
            <div class="row print_remove">
              <div class="col-sm-4 text-left">
                <div class="accordionHeader">
                  <h3>Key</h3>
                  <div class="expandIcon"><i class="fa fa-plus" aria-hidden="true"></i></div>
                  <div class="collapseIcon"><i class="fa fa-minus" aria-hidden="true"></i></div>
                </div>
                <div  class="accordionContent">
                  <div class="row">
                    <div class="col-sm-6">
                      <h4>Tasks</h4>
                      <div class="fc-event" style="margin-bottom:5px">Standard</div>
                      <div class="fc-event ready" style="margin-bottom:5px">Cadence - Ready</div>
                      <div class="fc-event notReady" style="margin-bottom:5px">Cadence - Not Ready</div>
                      <div class="fc-event" style="margin-bottom:5px;">
                        <div class="dot inReview"></div>
                        In Review</div>
                      <div class="fc-event" style="margin-bottom:5px;">
                        <div class="dot approved"></div>
                        Approved</div>
                      <div class="fc-event calendar_Overdue" style="margin-bottom:5px">Overdue</div>
                      <div class="fc-event calendar_Complete">Completed</div>
                    </div>
                    <div class="col-sm-6">
                      <h4>Reviews</h4>
                      <div class="fc-event" style="margin-bottom:5px;background-color: #AC32E4;">
                        <div class="dot new"></div>
                        Pending Your Approval</div>
                      <div class="fc-event" style="margin-bottom:5px;background-color: #AC32E4;">
                        <div class="dot approved"></div>
                        Pending Other Members Approval</div>
                      <div class="fc-event calendar_Overdue" style="margin-bottom:5px">Overdue</div>
                      <div class="fc-event calendar_Complete" style="background-color: #AC32E4;">Completed</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 text-left pull-right">
                <label class="formLabels text-left">Jump To Date:</label>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td width="90%"><input type="date" style="width:100%"></td>
                      <td width="10%" valign="top"><button style="margin-left:5px;" id="datePicker" class="createNew noExpand"><i class="fa fa-arrow-right" aria-hidden="true"></i></button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <br>
            <br>
            <div class="row">
              <div class="col-sm-12">
                <center>
                  <div class="working">
                    <p>Loading...</p>
                    <br>
                    <img src="/dashboard/images/Gear.gif" style="width:100px !important;"></div>
                </center>
                <div id='calendar'></div>
                <div style='clear:both'></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- View Event Modal -->
<div class="modal fade" id="viewTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modalHeader">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tbody>
            <tr>
              <td valign="middle" width="80px"><img src="" id="printEventCreator" style="border-radius:50%;width:60px;"></td>
              <td valign="middle"><p>Task Assigned By:<br>
                  <strong id="printEventCreatorName"></strong></p></td>
            </tr>
          </tbody>
        </table>
      </div>
      <center>
        <div class="loading">
          <p>Loading...</p>
          <br>
          <img src="/dashboard/images/Gear.gif" style="width:100px !important;"></div>
      </center>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-sm">
              <div class="row">
                <div class="col-sm-6">
                  <div class="formLabels">Title: </div>
                  <span id="printEventTitle"></span><br>
                  <br>
                  <div class="formLabels">Project: </div>
                  <em><span id="printEventProjectTitle"></span></em><br>
                  <br>
                  <div class="formLabels">Due Date:</div>
                  <span id="printEventStartDate"></span><br>
                  <br>
                </div>
                <div class="col-sm-6">
                  <div class="formLabels">Status:</div>
                  <div id="printEventStatus"><strong class='taskStatus'></strong></div>
                  <br>
                  <br>
                  <div class="formLabels">Category:</div>
                  <span id="printEventCategory"></span><br>
                  <br>
                </div>
                <div class="col-sm-12">
                  <div class="formLabels">Description:</div>
                  <br>
                  <pre id="printEventDescription" style="word-break: break-all;"></pre>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="commentSide">
              <div id="printComments" class="comment-container"></div>
              <div id='newCommentContainer'>
                <hr>
                <table style='width: 100%;'>
                  <tr>
                    <td><pre style='width: 100%;'><textarea id='newComment' rows='1' placeholder='Message...'></textarea>
</pre></td>
                    <td><button id='addNewComment' class='smallSend' style='margin-top: -10px;' taskid=''><i class='fa fa-paper-plane' aria-hidden='true'></i></button></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <hr>
        <a href="" class="genericbtn" id="viewTaskModal-btn">View Project</a>
        <button type="button" class="genericbtn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- View Review Modal -->
<div class="modal fade" id="viewReview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modalHeader">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tbody>
            <tr>
              <td valign="middle" width="80px"><img src="" id="printReviewCreator" style="border-radius:50%;width:60px;"></td>
              <td valign="middle"><p>Review Created By:<br>
                  <strong id="printReviewCreatorName"></strong></p></td>
            </tr>
          </tbody>
        </table>
      </div>
      <center>
        <div class="loading">
          <p>Loading...</p>
          <br>
          <img src="/dashboard/images/Gear.gif" style="width:100px !important;"></div>
      </center>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-sm">
              <div class="row">
                <div class="col-sm-6">
                  <div class="formLabels">Title: </div>
                  <span id="printReviewTitle"></span><br>
                  <br>
                  <div class="formLabels">Project: </div>
                  <em><span id="printReviewProjectTitle"></span></em><br>
                  <br>
                  <div class="formLabels">Due Date:</div>
                  <span id="printReviewStartDate"></span><br>
                  <br>
                </div>
                <div class="col-sm-6">
                  <div class="formLabels">Status:</div>
                  <div id="printReviewStatus"><strong class='taskStatus'></strong></div>
                  <br>
                  <br>
                  <div class="formLabels">Category:</div>
                  <span id="printReviewCategory"></span><br>
                  <br>
                </div>
                <div class="col-sm-12">
                  <div class="formLabels">Members:</div>
                  <div id="printReviewMembers"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <hr>
        <a href="" class="genericbtn" id="viewReviewProjectModal-btn">View Project</a> <a href="" class="genericbtn" id="viewReviewModal-btn" target="_blank">View Review</a>
        <button type="button" class="genericbtn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php echo $scripts ?>
</body>
</html>