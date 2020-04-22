
//setting defaults
$(document).ready(function() {
		"use strict";
	

	
	
		var newsfeedCount = 15;
		function getMoreNewsfeed() {
			$("#viewMoreNewsfeed").remove();
			newsfeedCount = newsfeedCount+15;
			var dataString = {'type':"getMoreNewsfeed",'newsfeedCount':newsfeedCount};

				$.ajax({
					type: "POST",
					url: "/dashboard/home/process.php",
					data: dataString,
					cache: false,
					success: function(results){
						$("#spinner").remove();
						$("#printNewsfeed").html(results.newsfeedItems).fadeIn();
						$("#printNewsfeed").after('<center><div id="viewMoreNewsfeed">View More</div></center>');
					}
				});
		}
		function refreshNewsfeed() {
			$("#viewMoreNewsfeed").remove();
			newsfeedCount = 15;
			var dataString = {'type':"getMoreNewsfeed",'newsfeedCount':newsfeedCount};

				$.ajax({
					type: "POST",
					url: "/dashboard/home/process.php",
					data: dataString,
					cache: false,
					success: function(results){
						
						$("#spinner").remove();
						$("#printNewsfeed").html(results.newsfeedItems).fadeIn();
						$("#printNewsfeed").after('<center><div id="viewMoreNewsfeed">View More</div></center>');
					}
				});
		}
	
		//refreshNewsfeed();
	
		function loadAllHomeData(){
			
			var dataString = {'type':"loadAllHomeData"};

				$.ajax({
					type: "POST",
					url: "/dashboard/home/process.php",
					data: dataString,
				    dataType: 'json',
					cache: false,
					success: function(results){
						$("#spinner").remove();
						//todays todo list
						if (results.fullTodoList === "" || results.fullTodoList === null) {
							$(".noTasksDue").fadeIn();
							
							$("#printFullTodoList").remove();
						}
						else {
							$("#printBackFullTodoList tbody").html(results.fullTodoList);
						
						$('#printBackFullTodoList').DataTable({
								'destroy': true,
							"order": [[ 4, "asc" ]]
							});

						$("#printBackFullTodoList_length").remove();	
						
						$("#printFullTodoList").parent().height($("#printFullTodoList").height()+100);
							$("#printFullTodoList").fadeIn();
						}
						
						//active projects
						if (results.activeProjects) {
							$("#activeProjects").html(results.activeProjects).fadeIn();
						var boxCount = $(".projectBox").length;
							
							if (boxCount< 3) {
								$(".dragOverflow .prev,.dragOverflow .next").hide();
								}
							
						$(".projectHorzScroll").width(boxCount*350);
						$(".projectBox").css("display","inline-block");
						//newsfeed
						$("#printNewsfeed").html(results.newsfeedItems).fadeIn();
						$("#printNewsfeed").after('<center><div id="viewMoreNewsfeed">View More</div></center>');
							}
						else {
							$(".prev,.next").hide();
							$("#activeProjects").html('<div style="width:400px;">No active projects within your team. <a href="/dashboard/team-projects">View all Projects.</a></div>').fadeIn();
							}
						
					getMoreNewsfeed();
						
						
					
						
						
						
					}
				});
		
		}
		
	
		function loadAllEvents() {
			$.ajax({
				url: '/dashboard/content-calendar/process.php',
				type: 'POST', // Send post data
				data: 'type=fetch',
				async: false,
				success: function(s){
					
					var json_events = s;
					
					$("#calendar").fadeIn(); 
					
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
			views: {
				month: {
					eventLimit: 6 // adjust to 8 only for agendaWeek/agendaDay
				}
			},
			hiddenDays: [ 0, 6 ],
			editable: true,
			droppable: true, 
			slotDuration: '00:30:00',
			eventClick: function(event, jsEvent, view) {
		    	
				var eventID = event.id;
				
				window.location.href = '/dashboard/content-calendar/?eventID='+eventID;
				
			},
			
			
		});
					
					setTimeout(function(){
$("#refreshFeed").parent().height($("#upcomingEvents").height());
$(".newsfeedContainer").height($("#upcomingEvents").height()-42);
					}, 200);
				}
			});
		}
	
		loadAllEvents();
		loadAllHomeData();
	
	
		
		//scroll
	$('.dragOverflow .next').click(function(event) {
		
    var pos = $('.dragContainer').scrollLeft() + 325;
	$('.dragContainer').animate({scrollLeft: pos}, 600);
		
});
	
	$('.dragOverflow .prev').click(function(event) {
    var pos = $('.dragContainer').scrollLeft() - 325;
	$('.dragContainer').animate({scrollLeft: pos}, 600);
		

});
	
	
		$(document).on('click','.newsfeed-item .actions .fa-ellipsis-h', function() {
			var thisMenu = $(this).parent().next();
			$(".actionMenu").not(thisMenu).slideUp();
			$(this).parent().next().slideToggle();
		});
	
	$(document).on('click','#viewMoreNewsfeed', function() {
		$(this).remove();
		$("#printNewsfeed").after('<center><img src="../images/grey-spinner.gif" id="spinner"></center>').fadeIn();
		getMoreNewsfeed();
		});
	
	$(document).on('click','#refreshFeed', function() {
		$("#viewMoreNewsfeed").remove();
		$("#printNewsfeed").html('<center><img src="../images/grey-spinner.gif" id="spinner"></center>').fadeIn();
		getMoreNewsfeed();
		});
	
	$(document).on('click','.todoListTable tbody tr > td', function() {
		
		if ($(this).index() == 2) {
			
		//  return false; // disable 3rd column
		}
		
		var thisType = $(this).parent().attr("type");
		var thisItemID = $(this).parent().attr("itemid");
		var thisProjectID = $(this).parent().attr("projectid");
		
		if (thisType === "Task") {
			window.location.href = '/dashboard/team-projects/view/?projectID='+thisProjectID+'&taskID='+thisItemID;
		}
		else {
			
			window.open('/dashboard/team-projects/view/review/?reviewID='+thisItemID, '_blank');
		}
		
	});
	
	

});
 








