
$(document).ready(function(){
"use strict";
	
	var reload = "yes";
	
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
	function successConfirm() {
		$(".deleteConfirm.confirmed").addClass("successConfirm");
					
					setTimeout(function() {
						$(".deleteConfirm.confirmed").html("Success!");
						$(".deleteConfirm.confirmed").prepend('<span class="successfulConfirm"><i class="fa fa-check" aria-hidden="true"></i></span>');
					}, 200);
	}
	function getNewCategories(teamID) {
					$.ajax({
								type: "POST",
								url: "view/info/main.php",
								data: {'type':"getNewCategories",'teamID':teamID},
								cache: false,
								success: function(results){
					$("#Category").find('option').not(':first').remove();
					$("#Category").find('option:first').after(results.newCategories);
					$("#Category").find('option:last').after('<option value="All">Show All</option>');
					
								
				},
				error: function(ts) { 
					alert("ERROR CODE: "+error.responseStatus+". Please refresh the page and try again.");
					console.log(error.responseStatus) 
				}
				});
			}
//set notes to bottom of box
if ($('#printMessages').length === 1){
	$('#printMessages').scrollTop($('#printMessages')[0].scrollHeight);	
	}


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
	
//mentioning user tags
		var mentionUserIDs = [];
		$(document).on('click','#showMentions .userTags', function() { 
			var thisUserID = $(this).attr("userid");
			$(this).toggleClass("selected");
			
			if ($(this).hasClass("selected")) {
				
				mentionUserIDs.push(thisUserID);
				
				}
			else {
				mentionUserIDs.splice($.inArray(thisUserID, mentionUserIDs),1);
			}
			
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
		
	//on change team filter - get the right categories for that team
		$("#teamID").on('change', function() {	
			var teamID = $(this).val();
			
			getNewCategories(teamID);
		});	
	
//favoriting
 $(document).on('mousedown touchstart','#hearticon', function() {
			  $(this).toggleClass("pop");
		  });
		 $(document).on('mousedown touchstart','#hearticon2', function() {
			  $(this).toggleClass("pop");
		  });
		 
		 $(document).on('click','.heartEmpty', function() {
			var projectID = getUrlParameter('projectID');
				$(this).addClass("heartFilled");
			 	var message = $(this).prev();
			 	message.html("Added to Favorites.&nbsp;&nbsp;&nbsp;");
			 	message.fadeIn();
			 	setTimeout(function(){
				  message.fadeOut();
				}, 3000);
			 	
			 	var dataString = {'type':"addToFavorites",'ProjectID':projectID};
			 
			 	$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				 
				}
				});	
		}); 
		 
		$(document).on('click','.heartFilled', function() {
			var projectID = getUrlParameter('projectID');
				$(this).removeClass("heartFilled").addClass("heartEmpty");
				var message = $(this).prev();
				message.html("Removed from Favorites.&nbsp;&nbsp;&nbsp;");
			 	message.fadeIn();
			 	setTimeout(function(){
				  message.fadeOut();
				}, 3000);
			
				var dataString = {'type':"RemoveFromFavorites",'ProjectID':projectID};
			 
			 	$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
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
						
						
						var completed = $('#printTasks td.Completed').length;
						var percentage = Math.round((completed/taskCount) * 100);
						
						$(".projectPercentage").html(percentage+"%");
					}
			 
			 	$(".projectPercentage").fadeIn();
				
				
		}	


		function loadProject(reload) {

			var projectID = getUrlParameter('projectID');
			
			var taskID = getUrlParameter('taskID');
			
			
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
					$("#favorite").html(results.favorite);
					$("#canAddMembers").html(results.canAddMembers);
					$("#canEditProject").html(results.canEditProject);
					$("#canAddTask").html(results.canAddTask);
					$("#canAddNote").html(results.canAddNote);
					if (results.canRemoveMembers != "yes") {
						$(".projectMember.not_disabled").removeClass("not_disabled").addClass("disabled");
					}
					else {
						
					}
									
					if (results.canMention) {
						$("#showMentions").remove();
						$("#canMention").html(results.canMention);
						$("#showMentions .formLabels").after(results.mentionUsers);
					}
					else {
						$("#showMentions").remove();

					}
					$("#canAddFile").html(results.canAddFile);
					$("#canAddReview").html(results.canAddReview);
					$("#canAddCopy").html(results.canAddCopy);
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
					
					if (results.printProjectCopy == null || results.printProjectCopy === "") {
						$("#copyCount").html("0");
					}
					else {
						$("#copyCount").html("1");
					}
					
					if (results.printProjectTaskType === "Cadence") {
						$(".toggler").css("width","288px");
						$(".toggler li").css("width","33%");
						$("#cadence1").show();
						
					}
					else {
						$("#cadence1").remove();
					}
					
					
					
					
					if (!results.printProjectTitle) {
						//window.location.href = "404.php";
					}
					setTimeout(function(){
					$(".projectLoad").show();	
					$(".projectActivityFeed").show();	
						//setting activity feed height
					if (navigator.userAgent.indexOf("Firefox") > 0) {
  $(".projectActivityFeed").height($(".col-sm-10.projectLoad").height());
			 }
			 else {
				 $(".projectActivityFeed").height($(".col-sm-10.projectLoad").height()-17.5);
			 }
						
						checkForNewPercentage();
						
					}, 500);
					$('#printMessages').scrollTop($('#printMessages')[0].scrollHeight);
					
						
					//if task is in url...
					if (!reload) {
						if (taskID) {
						viewTask(taskID);
					}
					}
					
								
				},
				error: function(ts) { 
					alert("ERROR CODE: "+error.responseStatus+". Please refresh the page and try again.");
					console.log(error.responseStatus) 
				}
				});
			
			
		}
		if (window.location.href.indexOf("/view/?projectID") > -1) {
			loadProject();
		}
	
	
	
		function viewTask(taskID) {
				$(".statusSelector").removeClass("active");
		var dataString = {'type':"viewTask",'taskID':taskID};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/tasks/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				$("#viewTask .loading").fadeOut();
				
				$("#taskTitleView").html(result.taskTitle);
    			$("#taskDueDateView").html(result.taskDueDateDisplay);
				$("#taskCategoryView").html(result.taskCategory);
					
					if (result.eventCategory !== null) {
						$("#taskEventCategory").html("&nbsp;- "+result.eventCategory);
						}
					else {
						$("#taskEventCategory").html("");
					}
				$("#taskDescriptionView").html(result.taskDescriptionDisplay);
				$("#taskStatusView strong").attr("class","taskStatus").html(result.taskStatus).addClass(result.taskStatus);
				$("#taskAssignedTo").html(result.taskAssignedTo);
				$("#taskAssignedToID").html(result.taskAssignedToID);
				$("#taskAssignedToPPView").attr("src",result.taskAssignedToPP);
				$("#printComments").html(result.printComments);
				if (result.printComments) {
					$(".dot").show();
					setTimeout(function(){
									$('#printComments').scrollTop($('#printComments')[0].scrollHeight);	
											$('#printComments').fadeIn();
								}, 500);
					}
				$("#canComment").html(result.canComment);
				$("#addNewComment").attr("taskid",taskID);
				
				//can reassign tasks
				$("#reassignTask").removeClass("canReassignTask")
					$(".reassignTaskContainer").remove();
					$(".reassignTaskTo").html("");
				
				if (result.canReassignTask) {
					$("#reassignTask").addClass("canReassignTask").after(result.canReassignTask);
					$(".reassignTaskTo").html(result.members);
				}
				else {
					$("#reassignTask").removeClass("canReassignTask")
					$(".reassignTaskContainer").remove();
					$(".reassignTaskTo").html("");
				}

				//get ctas
				$("#taskCTAs").html(result.canEditTask);
					
					
				//loading edit inputs
				$("#editTaskModal-btn").attr("taskid",taskID);
				$("#taskTitleEdit").val(result.taskTitle);
				if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
						$("#taskDueDateEdit").datetimepicker('setDate', (new Date(result.taskDueDate)));
					}
				else {
					$("#taskDueDateEdit").val(result.taskDueDate);
				}
    			
				$("#taskCategoryEdit").val(result.taskCategoryID);
					
					if (result.taskCategoryID === "7" && $("#taskEndDateEdit").length == 0) {
						$('#taskDueDateEdit').prev().text("Start Date:*");
						$('#taskDueDateEdit').after('<div class="formLabels">End Date:*</div><input type="datetime-local" id="taskEndDateEdit" name="taskEndDateEdit" style="width:100%">');
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
						$('#taskDueDateEdit').prev().text("Due Date:*");
						$('#taskEndDateEdit').prev().remove();
						$('#taskEndDateEdit').remove();
						$('#eventCategoryEdit').prev().remove();
						$('#eventCategoryEdit').remove();

					}
				$("#taskDescriptionEdit").html(result.taskDescription);
				$(".statusSelector[status='"+result.taskStatus+"']").addClass("active");	
					//opening content...
					setTimeout(function(){
					  if (!$('#viewTask').hasClass('in')) {
				$('#viewTask').modal('show');
				$("#viewTaskModal").attr("style","display:block !important")
			}
					}, 500);
					
				}
				});
		}
	
	$(document).on('click','.statusSelector', function(){
		$(".statusSelector").removeClass("active");
		$(this).addClass("active");
		
		$("#showMessage").fadeIn();
	});
	
	//can reassign tasks
	$(document).on('click','.canReassignTask', function(){
		//setting width of inside slide div
		var newWidth = $(".reassignTaskTo .projectMember").length * 105;
		$(".reassignTaskTo").width(newWidth+10)
		$(".reassignTaskContainer .reassignTaskInnerDiv,.reassignTaskContainer p:first").slideToggle();
		
	});
	
	$(document).on('click','.reassignTaskContainer .projectMember', function(){
		//setting width of inside slide div
		var taskID = $(this).attr("taskid");
		var memberID = $(this).attr("memberid");
		
		var dataString = {'type':"reassignTask",'taskID':taskID,'memberID':memberID};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/tasks/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject();
					viewTask(taskID);
				}
				});
		
	});
	
	
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
		var thisButton = $(this);
		var projectTitle = $("#projectTitle").val();  
		var projectDueDate = $("#projectDueDate").val();  
		var projectCategory = $("#projectCategory").find(":selected").val();  
		var projectDescription = $("#projectDescription").val();  
		var projectVisible = $("#projectVisible").find(":selected").val(); 
		var projectTaskType = $("#projectTaskType").find(":selected").val(); 
		var projectFolder = $("#projectFolder").val();  
		var projectURL = $("#projectURL").val(); 
		var projectTemplate = $("#projectTemplate").val(); 
		
		if (projectTitle === '') {
			
			$('#projectTitle').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		if (projectCategory === '') {
			
			$('#projectCategory').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		if (projectDueDate === '') {
			
			$('#projectDueDate').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	
		
	var currentButton = $(this);
	$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
	
		$.ajax({
								type: "POST",
								url: "/dashboard/team-projects/view/info/main.php",
								data: {'type':"add",'title':projectTitle, 'dueDate':projectDueDate, 'categoryID':projectCategory, 'description':projectDescription, 'visibility':projectVisible, 'folderLink':projectFolder, 'url':projectURL,'taskType':projectTaskType,'template':projectTemplate},
								cache: false,
								success: function(result){
									
									
									setTimeout(function(){
										  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
												setTimeout(function(){

														location.href = "view/?projectID="+(result.projectID)+"";


												}, 1000);

									}, 500);
									
								
								}
							});	
		
		
			
			
		
				
		});

$(".progressBar").hide();


	//EDITING PROJECT	
	$(document).on("click", "#editProject-btn", function() {
		
		var editingProjectID = getUrlParameter('projectID'); 
			
			 var dataString = {'type':"editLoad",'editingProjectID1':editingProjectID};
		
		  
		  		$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				$("#projectTitleEdit").val(result.projectTitleEdit);
				if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
					$("#projectDueDateEdit").datetimepicker('setDate', (new Date(result.projectDueDateEdit)));
					}
				else {
					$("#projectDueDateEdit").val(result.projectDueDateEdit);
				}
				$("#projectCategoryEdit").val(result.projectCategoryEdit);
				$("#projectDescriptionEdit").html(result.projectDescriptionEdit);
				$("#projectVisibleEdit").val(result.projectVisibleEdit);
				$("#projectFolderEdit").val(resultPriv.projectFolderEdit);
				$("#projectURLEdit").val(result.projectURLEdit);
				
				}
				});
				
				
		});

//SAVING PROJECT		
	  $("#editProjectModal-btn").on('click', function(){
		  var thisButton = $(this);
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
			  var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		if (projectCategoryEdit === '') {
			$('#projectCategoryEdit').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}  
		  
		if (projectDueDateEdit === '') {
			$('#projectDueDateEdit').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		} 
		
			var dataString = {'type':"save",'projectID':projectID,'title':projectTitleEdit, 'dueDate':projectDueDateEdit, 'categoryID':projectCategoryEdit, 'description':projectDescriptionEdit, 'visibility':projectVisibleEdit,'folderLink':projectFolderEdit, 'url':projectURLEdit};
		  
		  		$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					
					loadProject(reload);
				}
				});
		  	
				
		});

//ARCHIVING PROJECT
	$(document).on("click", "#archiveToggle", function() {
	var projectID = getUrlParameter('projectID'); 
		var dataString = {'type':"archive",'projectID':projectID};
             $.alertable.confirm('Are you sure you want to archive/reactivate this project? ').then(function() {	
					  $.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject(reload);
				}
				});
				}, function() {
			  
				 if($("#archiveToggle").is(":checked")){
                	$( "#archiveToggle" ).prop( "checked", false );
				}
				else if($("#archiveToggle").is(":not(:checked)")){
					$( "#archiveToggle" ).prop( "checked", true );
				}
				 
				 
			}).always(function() {
			  // Modal was dismissed
			});
        });
	
	  $(document).on("click", "#archive-btn", function() {
		var projectID = getUrlParameter('projectID'); 
		var dataString = {'type':"archive",'projectID':projectID};
				  $.alertable.confirm('Are you sure you want to archive/reactivate this project? ').then(function() {
					  $.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject(reload);
				}
				});
	});
		  		
		  		
				
		});
//REACTIVATING PROJECT
	  $(document).on("click", "#unarchive-btn", function() {
		 
		var projectID = getUrlParameter('projectID'); 
		var dataString = {'type':"reactivate",'projectID':projectID};
		  
		  		$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/info/main.php",
				data: dataString,
				cache: false,
				success: function(result){
					loadProject(reload);
				}
				});
				
				
		});
//DELETING PROJECT	
	  $(document).on('click','#delete-btn.confirmed', function() {
		
		var projectID = getUrlParameter('projectID'); 
		  
		var dataString = {'type':"delete",'projectID':projectID};
		
	
				var currentButton = $(this);
			
				  
					
						
						 	$.ajax({
							type: "POST",
							url: "/dashboard/team-projects/view/info/main.php",
							data: dataString,
							cache: false,
							success: function(result){
								
								//confirmation animation
					successConfirm();
					//END confirmation animation
								
								
								setTimeout(function(){
								 window.location.href = '/dashboard/team-projects';
								}, 1300);
							
								
								
							}
							});
						
					
			
			
			
				
		  
		 
		  		
		});

//PROJECT  TASKS
// DISPLAYING TASK
	$( '#printMyTasks,#printTasks' ).on( 'click', '.infoicon', function() {
		$("#editTaskBtn").show();
		$("#viewTaskModal").attr("style", "display:block !important");;
		var taskID = $(this).parent().parent().attr("id");
		viewTask(taskID);
	});
	$(document).on('click', '.cadenceContainer', function(){
		$("#editTaskBtn").show();
		$("#viewTaskModal").attr("style", "display:block !important");;
		var taskID = $(this).attr("taskid");
		viewTask(taskID);
	});
	
	
	$(document).on('click', '#editTaskBtn', function(){
		$(this).hide();
		
		$("#viewTaskModal").attr("style", "display:none !important");;
		$("#editTaskModal").attr("style", "display:block !important");
		$("#editTaskModal-btn").attr("style", "display:inline-block !important");
	});
	
	
	
	
// DELETING TASKS	
	$("#printTasks, #printMyTasks").on('click', '.trashicon', function(){
		 var taskID = $(this).parent().parent().attr("id");
		 
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
	
					
		 
			var dataString = {'type':"delete",'taskID':taskID};
		
				$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/tasks/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				loadProject(reload);
					
				}
					
				});
			 
		 
});
		
		 
		
		});
	  	
	//ADDING TASKS
	$(document).on('click', '#addNewTask-btn', function(){
		if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
			
		}
		else {
		$("#taskDueDate").val(finalCurrentDate+"T16:30");	
		}
		
	});
	
	$("#taskCategory").on('change', function() {
		if ($(this).find(":selected").val() === '7') {
			var d = new Date();
			var month = d.getMonth()+1;
			var day = d.getDate();
			var finalCurrentDate = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
				
			$('#taskDueDate').prev().text("Start Date:");
			$('#taskDueDate').after('<div class="formLabels">End Date:</div><input type="datetime-local" id="taskEndDate" name="taskEndDate" style="width:100%" class="validate">');
			$(this).after('<div class="formLabels">Event Category:</div><select id="addEventCategorySelect"></select>');
			setTimeout(function(){
				if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
					
					}
				else {
					 $("#taskEndDate").val(finalCurrentDate+"T17:00");
				}
			
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
		var thisButton = $(this);
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
		
		if (taskTitle === '') {
			$('#taskTitle').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		if (eventCategory === '') {
			$('#addEventCategorySelect').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		if (Date.parse(taskDueDate) <= Date.parse(yesterday) || taskDueDate === "") {
			$('#taskDueDate').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		if (taskCategory === '') {
			$('#taskCategory').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		if (addTaskMember === '') {
			$('#printMembersDropdown').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
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
						loadProject(reload);
					}
				});
		}
		
		
			
		
				
				
		});
	$('#viewTask').on('hide.bs.modal', function () { 
		$("#viewTask #editTaskModal,#editTaskModal-btn,#showMessage").hide();
		
    $('#taskDueDateEdit').prev().text("Due Date:");
						$('#taskEndDateEdit').prev().remove();
						$('#taskEndDateEdit').remove();
						$('#eventCategoryEdit').prev().remove();
						$('#eventCategoryEdit').remove();
						$('#taskStatusView .taskStatus').attr("class","taskStatus");
						$('#viewTaskModal input,#viewTaskModal textarea,#editTaskModal input,#editTaskModal textarea').val("");
		
});  
	$('#addNewTask').on('shown.bs.modal', function () { 
		
    					$('#taskDueDate').prev().text("Due Date:");
						$('#taskEndDate').prev().remove();
						$('#taskEndDate').remove();
						$('#addEventCategorySelect').prev().remove();
						$('#addEventCategorySelect').remove();
						$('#addNewTask input').val();
						$('#taskTitle,#taskDescription').val('');

});  

	//EDITING TASKS
	$("#taskCategoryEdit").on('change', function() {
		
		if ($(this).find(":selected").val() === '7') {
			$('#taskDueDateEdit').prev().text("Start Date:");
			$('#taskDueDateEdit').after('<div class="formLabels">End Date:</div><input type="datetime-local" id="taskEndDateEdit" name="taskEndDateEdit" style="width:100%" class="validate">');
			$(this).after('<div class="formLabels">Event Category:</div><select id="eventCategoryEdit"></select>');
			setTimeout(function(){
			var d = new Date($("#taskDueDateEdit").val());
			d.setMinutes(d.getMinutes() + 30);
			var newFinalDate = d.getFullYear()+ "-" + ("00" + (d.getMonth() + 1)).slice(-2) + "-" +("00" + d.getDate()).slice(-2)+"T"+ ("00" + d.getHours()).slice(-2) + ":" +("00" + d.getMinutes()).slice(-2) + ":" + ("00" + d.getSeconds()).slice(-2);	
				if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
				
				}
				else{
					 $("#taskEndDateEdit").val(newFinalDate);
				}
			
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
			
			$.alertable.confirm('Are you sure? Changing the category of a Launch event will REMOVE the event from the Content Calendar. This CANNOT be undone!').then(function() {
	
			$('#taskDueDateEdit').prev().text("Due Date:");
			$('#taskEndDateEdit').prev().remove();
			$('#taskEndDateEdit').remove();
			$('#eventCategoryEdit').prev().remove();
			$('#eventCategoryEdit').remove();
			
			});
			
			
		}
	});
	$(document).on('click', '.editicon', function(){
		var taskID = $(this).parent().parent().attr("id");
		
		viewTask(taskID);
		
		$("#viewTaskModal").attr("style", "display:none !important");;
		$("#editTaskModal").attr("style", "display:block !important");
		$("#editTaskModal-btn").attr("style", "display:inline-block !important");
		
		setTimeout(function(){
		 $("#editTaskBtn").trigger("click");
		}, 200);
		});

	
	//SAVING TASKS
	$(document).on('click', '#editTaskModal-btn', function(){
		var thisButton = $(this);
		var taskID = $(this).attr("taskid");
		var taskTitleEdit = $("#taskTitleEdit").val();  
		var taskDueDateEdit = $("#taskDueDateEdit").val();  
		var taskCategoryEdit = $("#taskCategoryEdit").find(":selected").val();  
		var taskDescriptionEdit = $("#taskDescriptionEdit").val();  
		var taskStatusEdit = $(".statusSelector.active").attr("status"); 
		var taskMessageEdit = $("#taskMessage").val(); 
		var taskEndDateEdit = $("#taskEndDateEdit").val();  
		var eventCategoryEdit = $("#eventCategoryEdit").find(":selected").val();  
		
		
		
		if (taskTitleEdit === '') {
			$('#taskTitleEdit').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		if (taskCategoryEdit === "7" && eventCategoryEdit === '') {
			$('#eventCategoryEdit').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		if (taskDueDateEdit === '') {
			$('#taskDueDateEdit').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		if (taskMessageEdit === '') {
			taskMessageEdit = "Please review.";
		}
		
		if ($('#taskEndDateEdit').val() < $('#taskDueDateEdit').val()) {
			$('#taskEndDateEdit').addClass("required");
			alert("End date must be at least 30 minutes after the start date.");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		else {
			
		
			var dataString = {'type':"save",'taskTitleEdit':taskTitleEdit, 'taskDueDateEdit':taskDueDateEdit, 'taskCategoryEdit':taskCategoryEdit, 'taskDescriptionEdit':taskDescriptionEdit, 'taskStatusEdit':taskStatusEdit, 'taskID':taskID, 'taskMessageEdit':taskMessageEdit, 'taskEndDateEdit':taskEndDateEdit, 'eventCategoryEdit':eventCategoryEdit};
			
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/tasks/main.php",
						data: dataString,
						cache: false,
						success: function(result){
							
							loadProject(reload);
							viewTask(taskID);
					
							$("#viewTaskModal").attr("style","display: block !important");
							$("#editTaskModal,#editTaskModal-btn").hide();
							$("#editTaskBtn").show();
							
							}
						});
				
				}		
		});
	
//PROJECT MEMBERS 
//ADD
	$(document).on('click','#addMembers', function() {
		$("#addNewMembers").toggle("slide", {direction: "up" }, 500);	
		$("#addNewMembers .showUsernames").html("");
		$("#addNewMembers input").val("");
	});
	
	//getting usernames while typing
	$('#newProjectMembers').keyup(function(){
				  var valThis = $(this).val();
				
						$("#addNewMembers .showUsernames").fadeIn();
		
		var projectID = getUrlParameter('projectID'); 
				if(valThis.charAt(0) === "@" && this.value.length > 1) {
					var newVal = valThis.substring(1, valThis.length);
					
					var dataString = {'type':"getUsernames",typedUsername:newVal,projectID:projectID};	
					$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/members/main.php",
						data: dataString,
						cache: false,
						success: function(results){

								$("#addNewMembers .showUsernames").html(results.foundUsernames);						
						}
						});
				}
			  else {
				  	$("#addNewMembers .showUsernames").html("");
			  }
		});
	
	$(document).on('click','#addNewMembers .userTags', function() {
		
		var newUserID = $(this).attr("userid")
		
		if (!newUserID) {
			return false;
			}
			var projectID = getUrlParameter('projectID'); 

			var dataString = {'type':"add",'projectID':projectID, 'memberUserID':newUserID};
			$.ajax({
					type: "POST",
					url: "/dashboard/team-projects/view/members/main.php",
					data: dataString,
					cache: false,
					success: function(results){
						loadProject(reload);
						$("#newProjectMembers").val("");
						$("#addNewMembers .showUsernames").html("");
					}
					});

		});
	
	
//DELETE
	$(document).on('click','#printMembers .projectMember.not_disabled:not(".disabled")', function() {
		var memberUserID = $(this).attr("id");
		var projectID = getUrlParameter('projectID'); 
		
		var dataString = {'type':"delete",'projectID':projectID, 'memberUserID':memberUserID};
		$.alertable.confirm('Are you sure? If this user has any tasks assigned to them, they will automatically be assigned to the project owner. This CANNOT be undone!').then(function() {
				$.ajax({
					type: "POST",
					url: "/dashboard/team-projects/view/members/main.php",
					data: dataString,
					cache: false,
					success: function(result){
						loadProject(reload);
					}
				});
		});
		
		
				

		
		});
	  	
//PROJECT NOTES 
// ADD/DELETE NOTE
	$(document).on('click','#printMessages .outgoingCom .message', function() {	
			$(this).next().slideToggle();
});
	$(document).on("click", "#printMessages .outgoingCom .removeNote", function() {
			
				var noteID = $(this).attr("noteid");
				var dataString = {'type':"delete",'noteID':noteID};
		
				$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
					$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/notes/main.php",
				data: dataString,
				cache: false,
				success: function(result){
				loadProject(reload);
					
				}
				});	
					
				});
				
			});
	$(document).on("click", "#sendNote", function(){
		var addNoteMessage = $("#projectNotesMessage").val();
		var projectID = getUrlParameter('projectID');  
		var thisButton = $(this);
		if ($('#projectNotesMessage').val() === '') {
			$('#projectNotesMessage').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
		
		var mentionUserIDsJson = JSON.stringify(mentionUserIDs);
		
		var currentButton = $(this);
		$(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');
							var dataString = {'type':"add",'projectID':projectID,'message':addNoteMessage,'mentionUserIDs':mentionUserIDsJson};
		
								$.ajax({
								type: "POST",
								url: "/dashboard/team-projects/view/notes/main.php",
								data: dataString,
								dataType: 'text',
								cache: false,
								success: function(result){

									
									setTimeout(function(){
									  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
											setTimeout(function(){
											  $(currentButton).removeClass('waiting').html('<i class="fa fa-check" aria-hidden="true"></i>');

															mentionUserIDs = [];
															loadProject(reload);		
															$("#projectNotesMessage").val('');

												}, 1000);

									}, 500);

								}
								});	
				
						
		
		});


	

//PROJECT FILE UPLOADS	
	
	
	
	$(document).on("click","#fileUpload:not(.failed)",function() {
		var fileVal = $("#projectFiles").val(); 
		var thisButton = $(this);
		if (fileVal==='') {
			$("#projectFiles").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
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
				form_data.append('type',"upload");
				form_data.append('projectID',projectID);
				$.ajax({
                url: '/dashboard/team-projects/view/files/main.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(results){
					
				  $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
						setTimeout(function(){
						  loadProject(reload);
						}, 1000);
					
				
				},
				  error: function (xhr, ajaxOptions, thrownError) {
					var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
					  
					  alert(xhr.status);
					alert(thrownError);
					  
					  
			return false;
				  }
     			});
		
				
				
				
				
				
		}
			
	});
	
	$(document).on('click','.delete_link', function() {
			event.preventDefault();	
			var projectID = getUrlParameter('projectID'); 
			var thisLink =$(this).attr("href");
			var file = $(this).attr("file");
			var path = $(this).attr("path");
		
				$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {			
					
					
					var dataString = {'type':"delete",projectID:projectID,path:path,file:file};
				
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/files/main.php",
						data: dataString,
						cache: false,
						success: function(response){
							loadProject(reload);
							$("#printProjectCopy").parent().addClass("make100");
							$(".hiding").slideUp();	
						}
						});
					
					
				});
				
	});	
// editing copy
	$(document).on('click','#addNewCopy-btn', function() {
		var copy = $("#printProjectCopy").html();
			CKEDITOR.instances.requestCopyEdit.setData(''+copy+'');
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
							loadProject(reload);
							$("#printProjectCopy").parent().addClass("make100");
							$(".hiding").slideUp();	
						}
						});
				
				
			
			});
			
		
	});

//REVIEWS
	var reviewersList = [];
	var reviewersUserIDList = [];
	$('#addNewReview').on('hide.bs.modal', function () {
		//emptying all inputs/returning to defaults
		if (!$("#addNewReview .myTabs li:first-child").hasClass("test")) {
			$("#addNewReview .myTabs li:first-child").addClass("active");
			$("#addNewReview .myTabs li:last-child").removeClass("active");
			$("#addNewReview #desktopImage").addClass("active in")
			$("#addNewReview #mobileImage").removeClass("active in")
		}
		
		$("#reviewMembers,#desktopPreviewImage,#mobilePreviewImage,#reviewTitle").val("");
		$("#reviewType").val($("#reviewType option:first").val());
		
		if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
		   
		}
			else {
				$("#reviewDueDate").val(finalCurrentDate+"T16:30");
			}
	});
	
	
	
	
	
	
		//getting emails while typing
	$('#reviewMembers').keyup(function(){
				  var valThis = $(this).val();
		var thisShowUsernames = $(this).parent().find(".showUsernames");
		
				if(valThis.charAt(0) === "@" && this.value.length > 1) {
					var newVal = valThis.substring(1, valThis.length);
					
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
	$(document).on('click','#addNewReview .userTags', function() {
			var thisUserID = $(this).attr("userid");
			var thisUsername = $(this).text();
			
		$("#addNewReview .showUsernames").html("");
		
		
		var dataString = {'type':"newReviewCheckUsername",username:thisUsername};	
					
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/review/review-process.php",
						data: dataString,
						cache: false,
						success: function(results){
							
							if (results !== "") {
								
							}
							else {
								
							reviewersList.push(thisUsername);
							reviewersUserIDList.push(thisUserID);
		$('.reviewerEmails').html( '<li><span>' + reviewersList.join('</span><i class="fa fa-trash pull-right removeReviewer"></i></li><li><span>')+'</span><i class="fa fa-trash pull-right removeReviewer"></i></li>');
		$('#reviewMembers').val("");
							
							}
						}
						});
		
	});
	$(document).on('click','.reviewerEmails li .fa-trash', function() {
		$("#addNewReview .showUsernames").html("");
		var itemtoRemove = $(this).parent().find("span").html();
		reviewersList.splice($.inArray(itemtoRemove, reviewersList),1);
		var itemtoRemoveUserID = $(this).parent().attr("userID");
		
		reviewersUserIDList.splice($.inArray(itemtoRemove, reviewersUserIDList),1);
		$(this).parent().remove().fadeOut();
	});
	
	//create a new content review
	$(document).on('click','#addNewReviewFinal', function() {
				var thisButton = $(this);
				var title = $("#reviewTitle").val();
				var type = "newReview";
				var projectID = getUrlParameter('projectID');
				var reviewType = $('#reviewType').val();
				var dueDate= $('#reviewDueDate').val();
				
				var title = $("#reviewTitle").val();
				var members = JSON.stringify(reviewersUserIDList);
				var desktop_file_data = $('#desktopPreviewImage');  
				var mobile_file_data = $('#mobilePreviewImage');  
				//required fields
				if (title === "") {
					
					$("#reviewTitle").addClass("required");
					var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
					return false;
				}
				if (dueDate ==="") {
					
					$("#reviewDueDate").addClass("required");
					var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
					return false;
				}
		
				
				
				var form_data = new FormData();                  
				form_data.append('type', type);
				form_data.append('projectID',projectID);
				form_data.append('reviewType',reviewType);
				form_data.append('dueDate',dueDate);
				form_data.append('title',title);
				form_data.append('members',members);
				form_data.append('desktopFile', desktop_file_data.prop('files')[0]);
				form_data.append('mobileFile', mobile_file_data.prop('files')[0]);
				
				$.ajax({
                url: '/dashboard/team-projects/view/review/review-process.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(result){
					$("#addNewReview").modal("hide");
					//emptying array
					reviewersList = [];
					reviewersUserIDList = [];
					$(".reviewerEmails").html("");
					loadProject(reload);
                }
     			});
	
				
	});
	
	//delete content review
	$(document).on('click','.deleteReview', function() {
		var reviewID = $(this).attr("reviewid");
		$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
			
				
				var dataString = {'type':"deleteReview",reviewID:reviewID};
				
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/review/review-process.php",
						data: dataString,
						cache: false,
						success: function(response){
							loadProject(reload);
						}
						});
				
				
			
		});
			
			
	});

	//EDITing review
	$(document).on("click", "#printReviews .editReview", function() {
		
		var reviewID = $(this).attr("reviewid"); 
			
			 var dataString = {'type':"editReview",'reviewID':reviewID};
		
		  
		  		$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/review/review-process.php",
				data: dataString,
				cache: false,
				success: function(result){
					
					$("#reviewCreatedByFullName").html(result.reviewCreatedByFullName);
					$("#reviewCreatedByPP").attr("src",result.reviewCreatedByPP);
					
					
				$("#reviewTitleEdit").val(result.reviewTitle);
				$("#reviewTypeEdit").val(result.reviewType);
				if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
					$("#reviewDueDateEdit").datetimepicker('setDate', (new Date(result.reviewDueDate)));
					}
				else {
					$("#reviewDueDateEdit").val(result.reviewDueDate);
				}
				
				
					$("#editReviewModal-btn").attr("reviewid",reviewID);
				
				}
				});
				
				
		});
	
	//save review
	$(document).on("click", "#editReviewModal-btn", function() {
		
		var reviewID = $(this).attr("reviewid"); 
			
		var title = $("#reviewTitleEdit").val();
		var reviewType = $("#reviewTypeEdit").val();
		var duedate = $("#reviewDueDateEdit").val();
		
		if (!title || !reviewType || !duedate ||  !reviewID) {
			
			return false;
		}
		
		else{
			var dataString = {'type':"updateReview",'reviewID':reviewID,'title':title,'reviewType':reviewType,'duedate':duedate};
		
		  
		  		$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/view/review/review-process.php",
				data: dataString,
				cache: false,
				success: function(result){
					
					$('#editReview').modal('hide');
					loadProject();
				
				}
				});
		}
		
			 
				
				
		});

	
});




