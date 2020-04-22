
$(document).ready(function(){
"use strict";
//get url parameter
	var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
	
//set notes to bottom of box
if ($('#printMessages').length === 1){
	$('#printMessages').scrollTop($('#printMessages')[0].scrollHeight);	
	}

	
// display timestamp on task comments
 $(document).on("click",".comments td > .message",function() {
				$(this).parent().find(".timestamp").fadeToggle();
				$(this).parent().next().find(".removeNote").fadeToggle();
		});


	
	
//activityTab
		  $(document).on('click','.projectActivityFeed', function() { 
			$(this).addClass("active slideRight");
			 $(this).find(".scroll").show();
			$(".projectLoad").prepend("<div class='overlay'></div>").addClass("slideLeft");
			
		});
		 $(document).on('click','.active.projectActivityFeed', function() { 
			$('.overlay').remove();
			 $(this).find(".scroll").hide();
			 $(this).removeClass("active slideRight");
			$(".projectLoad").removeClass("slideLeft");
			
		});
	
//search sort filter 
		$(document).on('click','#search', function() { 
			
			var thisPrintFilter = $(this).parent().parent().parent().find(".printFilter");
			var thisSorter = $(this).parent().parent().parent().find(".sorter");
			thisSorter.slideToggle();
			thisPrintFilter.slideToggle();
		});
		 
		 $(document).on('click','#searchAll', function() { 
			$(this).parent().find(".sorter,.printFilter").slideToggle();
			
		});
	
//favoriting
 $(document).on('mousedown touchstart','#hearticon', function() {
			  $(this).toggleClass("pop");
		  });
		 $(document).on('mousedown touchstart','#hearticon2', function() {
			  $(this).toggleClass("pop");
		  });
		 
		 $(document).on('click','.heartEmpty', function() {
			
				$(this).addClass("heartFilled");
			 	var message = $(this).prev();
			 	message.html("Added to Favorites.&nbsp;&nbsp;&nbsp;");
			 	message.fadeIn();
			 	setTimeout(function(){
				  message.fadeOut();
				}, 3000);
			 	
			 	var dataString = {'type':"addToFavorites",'ProjectID':ProjectID};
			 
			 	$.ajax({
				type: "POST",
				url: "info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				 
				}
				});	
		}); 
		 
		$(document).on('click','.heartFilled', function() {
				$(this).removeClass("heartFilled").addClass("heartEmpty");
				var message = $(this).prev();
				message.html("Removed from Favorites.&nbsp;&nbsp;&nbsp;");
			 	message.fadeIn();
			 	setTimeout(function(){
				  message.fadeOut();
				}, 3000);
			
				var dataString = {'type':"RemoveFromFavorites",'ProjectID':ProjectID};
			 
			 	$.ajax({
				type: "POST",
				url: "info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				 
				}
				});	
		}); 
		 
	
//check for task percentage
function checkForNewPercentage() {
		
				var taskCount = $('#printTasks tr').length;
				
					if (taskCount === 0 ){
						$(".projectPercentage").html("0%");
						}
					
					else {
						
						var finalTaskCount = (taskCount/2);
						
						var completed = $('#printTasks td.Completed').length;
						var percentage = Math.round((completed/finalTaskCount) * 100);
						
						$(".projectPercentage").html(percentage+"%");
					}
			 
			 	$(".projectPercentage").fadeIn();
				
				
		}	

//refresh progress bar 
		function refreshProgressbar() {
			setTimeout(function() { 
				$(".projectMember").each(function(){
					var memberName = $(this).attr("id");
					
					var myTaskCompletedCount = $('.Tasks_'+memberName+' td.Completed').length;
					var myTaskNewCount = $('.Tasks_'+memberName+' td.New').length;
					var myTaskReviewCount = $('.Tasks_'+memberName+' td.In').length;
					var myTaskApprovedCount = $('.Tasks_'+memberName+' td.Approved').length;
					
					var totalTaskCount = myTaskCompletedCount+myTaskNewCount+myTaskReviewCount+myTaskApprovedCount;		
					
					var myTaskpercentage = Math.round((myTaskCompletedCount/totalTaskCount) * 100)+"%";
					
					if (myTaskCompletedCount === 0) {
						$(this).parent().next().find($('.pb-'+memberName+'')).css("width","0%");
					}
					
					if (totalTaskCount === 0 && myTaskCompletedCount === 0) {
						$(this).parent().next().find($('.pb-'+memberName+'')).css("width","0%");
					}
					
					if (totalTaskCount === 0 && myTaskCompletedCount !== 0) {
						$(this).parent().next().find($('.pb-'+memberName+'')).css("width","100%");
					}
					
					$(this).parent().next().find($('.pb-'+memberName+'')).css("width",myTaskpercentage);
					$(this).parent().next().find($('.pb-'+memberName+'')).addClass("growGreen");
					
				});
			
		}, 500);
		}
		function loadProject() {
			var projectID = getUrlParameter('projectID');
			
			
			$.ajax({
								type: "POST",
								url: "load.php",
								data: {'project':projectID},
								cache: false,
								success: function(results){
					$(".working").fadeOut(); 
					
					
					$("#printProjectCreatedByTop").html(results.printProjectCreatedBy);
					$("#projectCreatedByPP").attr("src",results.projectCreatedByPP);
					$("#printProjectCreatedBy").html(results.printProjectCreatedBy);
					$("#printProjectTitle").html(results.printProjectTitle);
					$("#printProjectDueDate").html(results.printProjectDueDate);
					$("#printProjectCategory").html(results.printProjectCategory);
					$("#printProjectDescription").html(results.printProjectDescription);
					$("#copyLinkInput").val(window.location.href);
					$("#printProjectCopy").html(results.printProjectCopy);
					CKEDITOR.instances.requestCopyEdit.setData(''+results.printProjectCopy+'');
					$("#printProjectCreatedBy").html(results.printProjectCreatedBy);
					$("#printVisible").html(results.printVisible);
					$("#printMembers").html(results.printMembers);
					$("#printFiles").html(results.printFiles);
					$("#printReviews tbody").html(results.printReviews);
					$("#printTasks").html(results.printTasks);
					$("#printMyTasks").html(results.printMyTasks);
					$("#printMessages").html(results.printMessages);
					$("#printActivities").html(results.printActivities);
					$("#printProjectFolder").html(results.printProjectFolder);
					$("#printProjectURL").html(results.printProjectURL);
					$("#printProjectURL").attr("href", results.printProjectURL);
					$("#cadence .row").html(results.printCadence);
					$("#printProjectStatus").html(results.printProjectStatus);
					$(".projectTaskCount").html(results.projectTaskCount);
					$("#favorite").html(results.favorite);
					$("#canAddMembers").html(results.canAddMembers);
					if(results.canRemoveMembers == 'yes'){
						$(".projectMember").removeClass("no_click").attr("style",'');
					}
					else{
						$("#clickToRemove-Members").remove();
					}
					$("#canEditProject").html(results.canEditProject);
					$("#canAddTask").html(results.canAddTask);
					$("#canAddNote").html(results.canAddNote);
					$("#canAddFile").html(results.canAddFile);
					$("#canAddReview").html(results.canAddReview);
					$("#canAddCopy").html(results.canAddCopy);
					$("#canArchiveDeleteProject").html(results.canArchiveDeleteProject);
					$("#printMembersDropdown").html(results.printMembersDropdown);
					$("#allTaskCount").html(results.allTaskCount);
					$("#allReviewCount").html(results.allReviewCount);
					$("#allNotesCount").html(results.allNotesCount);
					
					$("#ticketAvailable").html(results.ticketAvailable);
					
					if (results.fileCount !== null) {
						$("#fileCount").html(results.fileCount);
					}
					else {
						$("#fileCount").html("0");
					}
					
					if (results.printProjectCopy !== null) {
						$("#copyCount").html("1");
					}
					else {
						$("#copyCount").html("0");
					}
					
					
					var label = $("#taskDueDate").prev();
					if (results.printProjectTaskType === "Cadence") {
						$(".toggler").css("width","288px");
						$(".toggler li").css("width","33%");
						$("#cadence1").show();
						
					}
					else {
						
					}
					
					checkForNewPercentage();
					
					
					if (!results.printProjectTitle) {
						window.location.href = "404.php";
					}
					setTimeout(function(){
					$(".projectLoad").fadeIn();	
					$(".projectActivityFeed").fadeIn();	
						//setting activity feed height
					if (navigator.userAgent.indexOf("Firefox") > 0) {
  $(".projectActivityFeed").height($(".col-sm-10.projectLoad").height());
			 }
			 else {
				 $(".projectActivityFeed").height($(".col-sm-10.projectLoad").height()-17.5);
			 }
					}, 500);
					$('#printMessages').scrollTop($('#printMessages')[0].scrollHeight);
					
				},
				error: function(ts) { 
					alert("ERROR CODE: "+error.responseStatus+". Please refresh the page and try again.");
					console.log(error.responseStatus) 
				}
				});
			
			
		}
		if (window.location.href.indexOf("/view/?projectID") > -1) {
			loadProject();
		refreshProgressbar();	
			}
		
	
	
var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var finalCurrentDate = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;	
	
//ADDING PROJECT
$("#projectTemplate").on('change', function() {	
	if ($(this).val() === "Blank") {
		$(".templateDepends").fadeIn();
	}
	else {
		$(".templateDepends").hide();
	}
});	
$(document).on('click','#addNewProject-btn', function(){
		var projectTitle = $("#projectTitle").val();  
		var projectDueDate = $("#projectDueDate").val();  
		var projectCategory = $("#projectCategory").find(":selected").val();  
		var projectDescription = $("#projectDescription").val();  
		var projectVisible = $("#projectVisible").find(":selected").val(); 
		var projectTaskType = $("#projectTaskType").find(":selected").val(); 
		var projectFolder = $("#projectFolder").val();  
		var projectURL = $("#projectURL").val(); 
		var projectTemplate = $("#projectTemplate").val(); 
		
		if ($('#projectTitle').val() === '') {
			$('.validate').html("<p>The project title is required.</p>");
			$('#projectTitle').addClass("required");
			return false;
		}
		if ($('#projectCategory').val() === '') {
			$('.validate').html("<p>The project category is required.</p>");
			$('#projectCategory').addClass("required");
			return false;
		}
	
		var currentDate = new Date();
		//var yesterday = new Date();
		currentDate.setDate(currentDate.getDate() - 1);
		
		if (Date.parse($('#projectDueDate').val()) <= Date.parse(currentDate)) {
			$('.validate').html("<p>The project due date must be today or later.</p>");
			$('#projectDueDate').addClass("required");
			return false;
		}
		
		if ($('#projectCategory').val() === '') {
			$('.validate').html("<p>A project category is required.</p>");
			$('#projectCategory').addClass("required");
			return false;
		}
		
		var currentButton = $(this);
			$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
			setTimeout(function(){
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
						setTimeout(function(){
						  $.ajax({
								type: "POST",
								url: "add.php",
								data: {'projectTitle1':projectTitle, 'projectDueDate1':projectDueDate, 'projectCategory1':projectCategory, 'projectDescription1':projectDescription, 'projectVisible1':projectVisible, 'projectFolder1':projectFolder, 'projectURL1':projectURL,'projectTaskType':projectTaskType,'projectTemplate':projectTemplate},
								cache: false,
								success: function(result){
									location.href = "view/?projectID="+(result.projectID)+"";
								
								}
							});
							
						  
						}, 1000);
					
			}, 500);
		
				
		});

$(".progressBar").hide();


	//EDITING PROJECT	
	$(document).on("click", "#editProject-btn", function() {
		
		var editingProjectID = getUrlParameter('projectID'); 
			
			 var dataString = {'type':"editLoad",'editingProjectID1':editingProjectID};
		
		  
		  		$.ajax({
				type: "POST",
				url: "info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				$("#projectTitleEdit").val(result.projectTitleEdit);
    			$("#projectDueDateEdit").val(result.projectDueDateEdit);
				$("#projectCategoryEdit").val(result.projectCategoryEdit);
				$("#projectDescriptionEdit").html(result.projectDescriptionEdit);
				$("#projectVisibleEdit").val(result.projectVisibleEdit);
				$("#projectFolderEdit").val(result.projectFolderEdit);
				$("#projectURLEdit").val(result.projectURLEdit);
				
				}
				});
				
				
		});

//SAVING PROJECT		
	  $("#editProjectModal-btn").on('click', function(){
		var projectID = getUrlParameter('projectID'); 
		var projectTitleEdit = $("#projectTitleEdit").val();	    
		var projectDueDateEdit = $("#projectDueDateEdit").val();  
		var projectCategoryEdit = $("#projectCategoryEdit").find(":selected").val();
		var projectCategoryEditName = $("#projectCategoryEdit").find(":selected").text();
		var projectDescriptionEdit = $("#projectDescriptionEdit").val();	  
		var projectVisibleEdit = $("#projectVisibleEdit").val();
		var projectFolderEdit = $("#projectFolderEdit").val();
		var projectURLEdit = $("#projectURLEdit").val();
		
		  if (projectTitleEdit === '') {
			$('#projectTitleEdit').addClass("required");
			return false;
		}
		
		
		if ($('#projectCategoryEdit').val() === '') {
			$('#projectCategoryEdit').addClass("required");
			return false;
		}  
		
			var dataString = {'type':"save",'editingProjectID1':projectID,'projectTitleEdit1':projectTitleEdit, 'projectDueDateEdit1':projectDueDateEdit, 'projectCategoryEditName':projectCategoryEditName, 'projectCategoryEdit1':projectCategoryEdit, 'projectDescriptionEdit1':projectDescriptionEdit, 'projectVisibleEdit1':projectVisibleEdit,'projectFolderEdit1':projectFolderEdit, 'projectURLEdit1':projectURLEdit};
		  
		  		$.ajax({
				type: "POST",
				url: "info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject();
				}
				});
		  	
				
		});

//ARCHIVING PROJECT
	  $(document).on("click", "#archive-btn", function() {
		var projectID = getUrlParameter('projectID'); 
		var dataString = {'type':"archive",'editingProjectID1':projectID};
				  
		  		$.ajax({
				type: "POST",
				url: "info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject();
				}
				});
		  		
				
		});
//REACTIVATING PROJECT
	  $(document).on("click", "#unarchive-btn", function() {
		 
		var projectID = getUrlParameter('projectID'); 
		var dataString = {'type':"reactivate",'editingProjectID1':projectID};
		  
		  		$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject();
				}
				});
				
				
		});
//DELETING PROJECT	
	  $(document).on('click','#delete-btn', function() {
		
		var projectID = getUrlParameter('projectID'); 
		  
		var dataString = {'type':"delete",'projectID':projectID};
		
		 if (confirm('Are you sure? This CANNOT be undone!')) {
				var currentButton = $(this);
			$(currentButton).addClass('waitingDelete').html('<img src="/dashboard/images/RollingDelete.gif" style="height:25px;">');
			setTimeout(function(){
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
					
						setTimeout(function(){
						 	$.ajax({
							type: "POST",
							url: "/dashboard/team-projects/view/info/main.php",
							data: dataString,
							cache: false,
							success: function(result){
							window.location.href = '/dashboard/team-projects';
							}
							});
						}, 500);
					
			}, 500);
			
			
				}
		  
		 
		  		
		});

//PROJECT  TASKS
// DISPLAYING TASK DESC
	$( '#printMyTasks,#printTasks' ).on( 'click', '.infoicon', function() {
		$(this).parent().parent().next("#taskDesc").fadeToggle();
		var getArea = $(this).parent().parent().next().find("#printComments");
		var TaskID = $(this).parent().parent().attr("id");
		var dataString = {'type':"getComments",'TaskID':TaskID};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/tasks/main.php",
				data: dataString,
				cache: false,
				success: function(results){
				getArea.html(results.printComments);
				}
				});	
	
	});
	
// DELETING TASKS	
	$("#printTasks, #printMyTasks").on('click', '.trashicon', function(){
		 var removedTaskID = $(this).parent().parent().attr("id");
		 var projectID = getUrlParameter('projectID'); 
		 
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
	
					
		 
			var dataString = {'type':"delete",'removedTaskID1':removedTaskID, 'projectSelector1':projectID};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/tasks/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				loadProject();
					
				}
					
				});
			 
		 
});
		
		 
		
		});
	  	
	//ADDING TASKS
	$(document).on('click', '#addNewTask-btn', function(){
	
		$("#taskDueDate").val(finalCurrentDate+"T16:30");
	});
	
	$("#taskCategory").on('change', function() {
		if ($(this).find(":selected").val() === '7') {
			var d = new Date();
			var month = d.getMonth()+1;
			var day = d.getDate();
			var finalCurrentDate = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
				
			$('#taskDueDate').prev().text("Start Date:");
			$('#taskDueDate').after('<div class="formLabels">End Date:</div><input type="datetime-local" id="taskEndDate" name="taskEndDate" style="width:100%">');
			$(this).after('<div class="formLabels">Event Category:</div><select id="addEventCategorySelect"></select>');
			setTimeout(function(){
			 $("#taskEndDate").val(finalCurrentDate+"T17:00");
							$.ajax({
				    		url: '/dashboard/content-calendar/process.php',
				    		data: 'type=getEventCategories',
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								$("#addEventCategorySelect").html(response.getOptions.join(' '));
							}
							});
							
			}, 200);
			
			
				 
			
		}
		else {
			$('#taskDueDate').prev().text("Due Date:");
			$('#taskEndDate').prev().remove();
			$('#taskEndDate').remove();
			$('#addEventCategorySelect').prev().remove();
			$('#addEventCategorySelect').remove();
			
		}
	});
	
	$(document).on('click', '#addNewTaskModal-btn', function(){
		
		var projectID = getUrlParameter('projectID'); 
		
		var taskTitle = $("#taskTitle").val();
		var taskDueDate = $("#taskDueDate").val();  
		var taskEndDate = $("#taskEndDate").val();  
		var taskCategory = $("#taskCategory").find(":selected").val();  
		var eventCategory = $("#addEventCategorySelect").find(":selected").val();  
		var taskDescription = $("#taskDescription").val(); 
		var addTaskMember = $("#printMembersDropdown").find(":selected").val();  
		
		var currentDate = new Date();
		var yesterday = new Date(currentDate);
		yesterday.setDate(currentDate.getDate() - 1);
		
		if ($('#taskTitle').val() === '') {
			$('#taskTitle').addClass("required");
			$('#taskTitle').attr("placeholder","A task title is required.");
			return false;
		}
		if ($('#addEventCategorySelect').val() === '') {
			$('#addEventCategorySelect').addClass("required");
			return false;
		}
		if (Date.parse(taskDueDate) <= Date.parse(yesterday)) {
			$('#taskDueDate').addClass("required");
			return false;
		}
		
		if ($('#taskCategory').val() === '') {
			$('#taskCategory').addClass("required");
			return false;
		}
		
		if ($('#addTaskMembershipList').val() === '') {
			$('#addTaskMembershipList').addClass("required");
			return false;
		}
		else {
			$('#addNewTask').modal('hide');
			
			var dataString = {'type':"add",'projectID1':projectID, 'taskTitle1':taskTitle, 'taskDueDate1':taskDueDate, 'taskCategory1':taskCategory, 'taskDescription1':taskDescription, 'addTaskMember1':addTaskMember, 'taskEndDate':taskEndDate, 'eventCategory':eventCategory};

				$.ajax({
					type: "POST",
					url: "/dashboard/team-projects/view/tasks/main.php",
					data: dataString,
					cache: false,
					success: function(result){
						loadProject();
					}
				});
		}
		
		
			
		
				
				
		});
	$('#editTask').on('hide.bs.modal', function () { 
		$("#editTask .modal-body").hide();
		
    $('#taskDueDateEdit').prev().text("Due Date:");
						$('#taskEndDateEdit').prev().remove();
						$('#taskEndDateEdit').remove();
						$('#eventCategoryEdit').prev().remove();
						$('#eventCategoryEdit').remove();
						$('#editTask input').val();

});  
	$('#addNewTask').on('shown.bs.modal', function () { 
		
    					$('#taskDueDate').prev().text("Due Date:");
						$('#taskEndDate').prev().remove();
						$('#taskEndDate').remove();
						$('#addEventCategorySelect').prev().remove();
						$('#addEventCategorySelect').remove();
						$('#addNewTask input').val();
						$('#taskCategory,#taskTitle,#taskDescription').val('');

});  

	//EDITING TASKS
	$("#taskCategoryEdit").on('change', function() {
		if ($(this).find(":selected").val() === '7') {
			$('#taskDueDateEdit').prev().text("Start Date:");
			$('#taskDueDateEdit').after('<div class="formLabels">End Date:</div><input type="datetime-local" id="taskEndDateEdit" name="taskEndDateEdit" style="width:100%">');
			$(this).after('<div class="formLabels">Event Category:</div><select id="eventCategoryEdit"></select>');
			setTimeout(function(){
			var d = new Date($("#taskDueDateEdit").val());
			d.setMinutes(d.getMinutes() + 30);
			var newFinalDate = d.getFullYear()+ "-" + ("00" + (d.getMonth() + 1)).slice(-2) + "-" +("00" + d.getDate()).slice(-2)+"T"+ ("00" + d.getHours()).slice(-2) + ":" +("00" + d.getMinutes()).slice(-2) + ":" + ("00" + d.getSeconds()).slice(-2);	
			 $("#taskEndDateEdit").val(newFinalDate);
							$.ajax({
				    		url: '/dashboard/content-calendar/process.php',
				    		data: 'type=getEventCategories',
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								$("#eventCategoryEdit").html(response.getOptions.join(' '));
								
							}
							});		
			}, 100);
			
			
				 
			
		}
		else {
			
			$.alertable.confirm('Are you sure? Changing the category of this Launch event will REMOVE the launch from the Content Calendar. This CANNOT be undone!').then(function() {
	
			$('#taskDueDateEdit').prev().text("Due Date:");
			$('#taskEndDateEdit').prev().remove();
			$('#taskEndDateEdit').remove();
			$('#eventCategoryEdit').prev().remove();
			$('#eventCategoryEdit').remove();
			
			});
			
			
		}
	});
	$("#printTasks, #printMyTasks").on('click', '.editicon', function(){
		
		var editingTaskID = $(this).parent().parent().attr("id");
		$("#holdingTaskID").val(editingTaskID);
		$("#editTask .loading").fadeIn(); 
		var dataString = {'type':"editLoad",'editingTaskID1':editingTaskID};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/tasks/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				$("#editTask .loading").fadeOut();
				
				$("#taskTitleEdit").val(result.taskTitleEdit);
    			$("#taskDueDateEdit").val(result.taskDueDateEdit);
				$("#taskCategoryEdit").val(result.taskCategoryEdit);
					
					if (result.taskCategoryEdit === "7") {
						$('#taskDueDateEdit').prev().text("Start Date:");
						$('#taskDueDateEdit').after('<div class="formLabels">End Date:</div><input type="datetime-local" id="taskEndDateEdit" name="taskEndDateEdit" style="width:100%">');
						$("#taskCategoryEdit").after('<div class="formLabels">Event Category:</div><select id="eventCategoryEdit"></select>');
						setTimeout(function(){
							$.ajax({
				    		url: '/dashboard/content-calendar/process.php',
				    		data: 'type=getEventCategories',
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								
								$("#eventCategoryEdit").html(response.getOptions.join(' '));
								setTimeout(function(){
								$("#eventCategoryEdit").val(result.eventCategoryID);
								}, 300);
							}
							});
						 $("#taskEndDateEdit").val(result.eventEndDate);
						}, 500);
						
					}
					else {
						$('#taskDueDateEdit').prev().text("Due Date:");
						$('#taskEndDateEdit').prev().remove();
						$('#taskEndDateEdit').remove();
						$('#eventCategoryEdit').prev().remove();
						$('#eventCategoryEdit').remove();

					}
				$("#taskDescriptionEdit").html(result.taskDescriptionEdit);
				$("#taskStatusEdit").html(result.taskStatusEdit);
				$("#taskStatusEdit2").val(result.taskStatusEdit);
				$("#taskAssignedToEdit").html(result.taskAssignedToEdit);
				$("#taskAssignedToIDEdit").html(result.taskAssignedToIDEdit);
				$("#taskAssignedToEdit").val(result.taskAssignedToIDEdit);
				
				$("#taskAssignedToPP").attr("src",result.taskAssignedToPP);
				var statusValue = result.taskStatusNumber;
					
					//slider 
					 $( function() {
								$( "#slider" ).slider({
								  value:statusValue,
								  min: 0,
								  max: 300,
								  step: 100,
								  slide: function( event, ui ) {
									  	if (ui.value == 0) {
											$("#taskStatusEdit2").val("New");
										    $("#taskStatusEdit").html("New");
											$("#showMessage").fadeOut();
										}
										else if (ui.value == 100) {
											$("#taskStatusEdit2").val("Review");
											$("#taskStatusEdit").html("In Review");
											$("#showMessage").fadeIn();
										}
										else if (ui.value == 200) {
											$("#taskStatusEdit2").val("Approved");
											$("#taskStatusEdit").html("Approved");
											$("#showMessage").fadeOut();
										}
										else {
											$("#taskStatusEdit2").val("Completed");
											$("#taskStatusEdit").html("Completed");
											$("#showMessage").fadeOut();
										}
									  
									  
									  
								  }
								});

					  }); 					
					
					setTimeout(function(){
					$("#editTask .modal-body").fadeOut("slow", function() {
						$(this).attr("style", "display: block !important");
    				});
				}, 700);	
					
					
				}
				});
		});

	//SAVING TASKS
	$("#editTaskModal-btn").on('click', function(){
		var projectID = getUrlParameter('projectID');
		var editingTaskID = $("#holdingTaskID").val();
		var taskTitleEdit = $("#taskTitleEdit").val();  
		var taskDueDateEdit = $("#taskDueDateEdit").val();  
		var taskCategoryEdit = $("#taskCategoryEdit").find(":selected").val();  
		var taskDescriptionEdit = $("#taskDescriptionEdit").val();  
		var taskStatusEdit = $("#taskStatusEdit2").find(":selected").text(); 
		var taskMessageEdit = $("#taskMessage").val(); 
		var taskEndDateEdit = $("#taskEndDateEdit").val();  
		var eventCategoryEdit = $("#eventCategoryEdit").find(":selected").val();  
	
		if ($('#taskTitleEdit').val() === '') {
			$('#taskTitleEdit').addClass("required");
			alert("Task title required.");
			return false;
		}
		
		if ($("#taskCategoryEdit").val() === "7" && $('#eventCategoryEdit').val() === '') {
			alert("Event category required.");
			$('#eventCategoryEdit').addClass("required");
			return false;
		}
		
		if (taskMessageEdit === '') {
			taskMessageEdit = "Please review.";
		}
		
		if ($('#taskEndDateEdit').val() < $('#taskDueDateEdit').val()) {
			$('#taskEndDateEdit').addClass("required");
			alert("End date must be at least 30 minutes after the start date.");
			return false;
		}
		else {
			$('#editTask').modal('hide');
		
			var dataString = {'type':"save",'taskTitleEdit1':taskTitleEdit, 'taskDueDateEdit1':taskDueDateEdit, 'taskCategoryEdit1':taskCategoryEdit, 'taskDescriptionEdit1':taskDescriptionEdit, 'taskStatusEdit1':taskStatusEdit, 'editingTaskID1':editingTaskID, 'taskMessageEdit1':taskMessageEdit, 'taskEndDateEdit':taskEndDateEdit, 'eventCategoryEdit':eventCategoryEdit};

				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/tasks/main.php",
						data: dataString,
						cache: false,
						success: function(result){
							console.log(result);
							loadProject();
							}
						});
				
				}		
		});
	
//PROJECT MEMBERS 
//ADD
	$(document).on('click','#addMembers', function() {
		$("#addNewMembers").toggle("slide", {direction: "up" }, 500);	
	});
	
	//getting usernames while typing
	$('#newProjectMembers').keyup(function(){
				  var valThis = $(this).val();
		
		var projectID = getUrlParameter('projectID'); 
				if(valThis.charAt(0) === "@" && this.value.length > 1) {
					var newVal = valThis.substring(1, valThis.length);
					var thisShowUsernames = $(this).prev();
						$(thisShowUsernames).fadeIn();
					var dataString = {'type':"getUsernames",typedUsername:newVal,projectID:projectID};	
					$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/members/main.php",
						data: dataString,
						cache: false,
						success: function(results){

								$(thisShowUsernames).html(results.foundUsernames);						
						}
						});
				}
			  else {
				  	$(thisShowUsernames).html("");
			  }
		});
	
	
	$(document).on('click','#projectMemberAdd-btn', function() {
		
		if (!$("#newProjectMembers").val()) {
			return false;
			}
		var currentButton = $(this);
		$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
		setTimeout(function(){
			  
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
						setTimeout(function(){
						  var memberUserID = $("#newProjectMembers").attr("userid");
							
							var projectID = getUrlParameter('projectID'); 
							
							var dataString = {'type':"add",'projectID':projectID, 'memberUserID1':memberUserID};
							$.ajax({
									type: "POST",
									url: "/dashboard/team-projects/view/members/main.php",
									data: dataString,
									cache: false,
									success: function(results){
									
										loadProject();
						  $(currentButton).removeClass('waiting').html('<i class="fa fa-plus" aria-hidden="true"></i>');
						 	$("#addNewMembers").toggle("slide", {direction: "up" }, 500);
}
									});

							
						}, 1000);
					
				}, 500);
		
		
		});

//DELETE
	$(document).on('click','.projectMember:not(".no_click")', function() {
		var memberUserID = $(this).attr("id");
		var projectID = getUrlParameter('projectID'); 
		conosole.log(memberUserID);
		return false;
		var dataString = {'type':"delete",'projectID1':projectID, 'memberUserID1':memberUserID};
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
				$.ajax({
					type: "POST",
					url: "/dashboard/team-projects/view/members/main.php",
					data: dataString,
					cache: false,
					success: function(result){
						loadProject();
					}
				});
		});
		
		
				

		
		});
	  	
//PROJECT NOTES 
// ADD/DELETE NOTE

	$(document).on("click", ".outgoing table td .removeNote", function() {
			// hover starts code here
				var noteID = $(this).attr("id");
				var dataString = {'type':"delete",'noteID1':noteID};
		
				$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
					$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/notes/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				loadProject();
					
				}
				});	
					
				});
				
			});
	$(document).on("click", "#sendNote", function(){
		var addNoteMessage = $("#projectNotesMessage").val();
		var projectID = getUrlParameter('projectID');  
		
		if ($('#projectNotesMessage').val() === '') {
			$('#projectNotesMessage').addClass("required");
			return false;
		}
		
		var currentButton = $(this);
			$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
			setTimeout(function(){
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
						setTimeout(function(){
						  $(currentButton).removeClass('waiting').html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');
							var dataString = {'type':"add",'addNoteProjectID1':projectID,'addNoteMessage1':addNoteMessage};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/notes/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				var noteID = result.noteID;
						loadProject();		
						
					$("#projectNotesMessage").val('');
					
				}
				});	
				
		
		
		
						}, 1000);
					
				
				}, 500);
		
				
		
		});

//ADDING TASK COMMENT
	$(document).on( 'click', '#addNewComment', function() {
		var projectID = getUrlParameter('projectID');  
		var currentButton = $(this);
			$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
		var comment =$(this).parent().find("#newComment").val();
		var TaskID = $(this).parent().parent().parent().parent().parent().prev().attr("id");
		
		var getArea = $(this).parent().prev();
			setTimeout(function(){
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
						setTimeout(function(){
						  
							var dataString = {'type':"addComment",'comment':comment,'TaskID':TaskID};

									$.ajax({
									type: "POST",
									url: "/dashboard/team-projects/view/tasks/main.php",
									data: dataString,
									cache: false,
									success: function(results){
									getArea.html(results.printComments);
									$("#newComment").val("");
									
									
									$.ajax({
									type: "POST",
									url: "/dashboard/team-projects/view/tasks/main.php",
									data: {'type':"getComments",'TaskID':TaskID},
									cache: false,
									success: function(results){
									getArea.html(results.printComments);
										$(currentButton).removeClass('waiting').html('<i class="fa fa-send" aria-hidden="true"></i>');
									}

									});
										
									}

									});	
									
							
								
							
						}, 1000);
					
			}, 500);
		
		
		
		
		
	
	});
//DELETING TASK COMMENT
	$(document).on("click", ".comments td .removeNote", function() {
			// hover starts code here
				var commentID = $(this).attr("id");
				var currentComment = $(this).parent().parent().parent().parent();
				var dataString = {'type':"deleteComment",'commentID':commentID};
				
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
	
					$.ajax({
					type: "POST",
					url: "/dashboard/team-projects/view/tasks/main.php",
					data: dataString,
					cache: false,
					success: function(result){
						$(currentComment).fadeOut();
					}
					});

});
				
				
			});
//PROJECT FILE UPLOADS	
	$(document).on("click","#fileUpload",function() {
		var fileVal = $("#projectFiles").val(); 
		if (fileVal==='') {
			return false;
		}
		else {
			var currentButton = $(this);
			$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
			
			
			var projectID = getUrlParameter('projectID'); 
			
			
			
			//var file_data = $('#projectFiles').prop('files')[0];  
			
				var form_data = new FormData();  
				var fileCount = $('#projectFiles').get(0).files.length;
                    for (var x = 0; x < fileCount; x++) {
						form_data.append('file[]', $('#projectFiles').get(0).files[x]);
                    }
			
				//form_data.append('file', file_data);
				form_data.append('projectID1',projectID);
				
				$.ajax({
                url: '/dashboard/team-projects/view/files/upload.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(results){
					
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
						setTimeout(function(){
						  loadProject();
						}, 1000);
					
				
				}
     			});
		
				
				
				
				
				
		}
			
	});
	
	$(document).on('click','.delete_link', function() {
			event.preventDefault();	
			var thisLink =$(this).attr("href");
				$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {			
					
					window.location = thisLink;

				});
				
	});	
// editing copy
	$(document).on('click','#addNewCopy-btn', function() {
			
		$("#printProjectCopy").parent().toggleClass("make100");
		$(".hiding").slideToggle();	
	});
	
//saving copy
	$(document).on('click','#saveCopy', function() {
		
			$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
	
				var projectID = getUrlParameter('projectID'); 
				var copy = CKEDITOR.instances.requestCopyEdit.getData();	
				var dataString = {'type':"saveCopy",projectID:projectID,copy:copy};
				
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/copy/main.php",
						data: dataString,
						cache: false,
						success: function(response){
							loadProject();
							$("#printProjectCopy").parent().addClass("make100");
							$(".hiding").slideUp();	
						}
						});
				
				
			
			});
			
		
	});

//REVIEWS
	var reviewersList = [];
	$('#addNewReview').on('hide.bs.modal', function () {
		$("#step1,#addNewReviewStep2").show();
		$("#step2,#step3,#addNewReviewFinal").hide();
		$("#successMessage,#successMessage").remove();
		//emptying all inputs/returning to defaults
		$("#reviewMembers,#desktopPreviewImage,#mobilePreviewImage,#reviewTitle").val("");
		$("#reviewType").val($("#reviewType option:first").val());
		$("#reviewDueDate").val(finalCurrentDate+"T16:30");
		//emptying array
		reviewersList = [];
		$(".reviewerEmails").html("");
	});
		//getting emails while typing
	$('#reviewMembers').keyup(function(){
				  var valThis = $(this).val();
		
		
				if(valThis.charAt(0) === "@" && this.value.length > 1) {
					var newVal = valThis.substring(1, valThis.length);
					var thisShowUsernames = $(this).prev();
						$(thisShowUsernames).fadeIn();
					var dataString = {'type':"getUsernames",typedUsername:newVal};	
					$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/review/review-process.php",
						data: dataString,
						cache: false,
						success: function(results){
							
							if (results.foundUsernames !== null) {
								$(thisShowUsernames).html(results.foundUsernames);
							}
							else {
								
							}
						}
						});
				}
			  else {
				  	$(thisShowUsernames).html("");
			  }
		});
		 
		 //adding reviewers
	$(document).on('click','.userTags', function() {
			var thisUserID = $(this).attr("userid");
			var thisTag = $(this).text();
			$(this).parent().next().val(thisTag).attr("userid", thisUserID);
	});
	
	$(document).on('click','#addReviewers', function() {
		$("#showUsernames").html("");
		var newMember = $('#reviewMembers').val();
		
		var dataString = {'type':"newReviewCheckUsername",username:newMember};	
					
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/review/review-process.php",
						data: dataString,
						cache: false,
						success: function(results){
							
							if (results !== "") {
								console.log(results);
							}
							else {
								
							reviewersList.push(newMember);
		$('.reviewerEmails').html( '<li><span>' + reviewersList.join('</span><i class="fa fa-trash pull-right removeReviewer"></i></li><li><span>')+'</span><i class="fa fa-trash pull-right removeReviewer"></i></li>');
		$('#reviewMembers').val("");
							
							}
						}
						});
		
		
		
		
		
		
	});
	$(document).on('click','.reviewerEmails li .fa-trash', function() {
		$("#showUsernames").html("");
		var itemtoRemove = $(this).parent().find("span").html();
		reviewersList.splice($.inArray(itemtoRemove, reviewersList),1);
		$(this).parent().remove().fadeOut();
	});
	
	//create a new content review
	$(document).on('click','#addNewReviewStep2', function() {
		var title = $("#reviewTitle").val();
		
				//requiring title
				if ($.trim($('#reviewTitle').val()) === '') {
					
					$("#reviewTitle").addClass("required");
					alert("Title required.");
				}
				else {
					
					$("#step1,#step3,#addNewReviewStep2").hide();
				$("#step2,#addNewReviewStep3").fadeIn();
				}
		
				

	});
		 
	$(document).on('click','#addNewReviewStep3', function() {
				$("#step1,#step2,#addNewReviewStep2,#addNewReviewStep3").hide();
				$("#step3,#addNewReviewFinal").fadeIn();
		
			
	});
	$(document).on('click','#addNewReviewFinal', function() {
				$("#step1,#step2,#step3,#addNewReviewStep2,#addNewReviewStep3,#addNewReviewFinal").hide();
				
				var projectID = getUrlParameter('projectID');
				var type = "newReview";
				//creating review
				var reviewType = $('#reviewType').val();
				var dueDate = $('#reviewDueDate').val();
				var title = $("#reviewTitle").val();
				
				var members = JSON.stringify(reviewersList);

				var dataString = {'type':type,'projectID':projectID,'dueDate':dueDate,'members':members,'reviewType':reviewType,'reviewTitle':title};
			 
			 	$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/review/review-process.php",
				dataType: 'json',
				data: dataString,
				cache: false,
				success: function(result){
					console.log(result);
					var reviewID=result.reviewID;
					var type3 = "firstNewReviewMobileImage";
					//checking for a mobile image
				var mobile_file_data = $('#mobilePreviewImage');  
		
				if (mobile_file_data !== undefined || mobile_file_data !== null || mobile_file_data !== "") {
					var form_data = new FormData();                  
				form_data.append('type', type3);
				form_data.append('mobileFile', mobile_file_data.prop('files')[0]);
				form_data.append('projectID',projectID); 
				form_data.append('reviewID',reviewID); 
				$.ajax({
                url: '/dashboard/team-projects/view/review/review-process.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
					
				
                }
     			});
				
				}
		
				else {
					
				}
				//checking for a desktop image
				var desktop_file_data = $('#desktopPreviewImage').prop('files')[0];  
				
				if (desktop_file_data !== undefined) {
					
					var type2 = "firstNewReviewDesktopImage";
				var form_data2 = new FormData();                  
				form_data2.append('type', type2);
				form_data2.append('desktopFile', desktop_file_data);
				form_data2.append('projectID',projectID); 
				form_data2.append('reviewID',reviewID); 
					
					$.ajax({
                url: '/dashboard/team-projects/view/review/review-process.php', 
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                data: form_data2,                         
                type: 'post',
                success: function(php_script_response){
                    
                }
     			});
				}
			
				else {
					
				}
					
					$("#step3").before("<div id='successMessage'><h1 class='text-center' id='successMessage'>Success!</h1><br><center><a href='review/?reviewID="+reviewID+"' class='genericbtn noExpand' target='_blank'>Go To Review</a></center></div>");
					loadProject();
					
					
				}
				});	
		
		
				
					
				
	});
	
	//delete content review
	$(document).on('click','.deleteReview', function() {
		var reviewID = $(this).attr("reviewid");
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
			
				
				var dataString = {'type':"deleteReview",reviewID:reviewID};
				console.log(reviewID);
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/review/review-process.php",
						data: dataString,
						cache: false,
						success: function(response){
							loadProject();
						}
						});
				
				
			
		});
			
			
	});


});




