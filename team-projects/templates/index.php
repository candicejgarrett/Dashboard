<?php
include_once( '../../header.php' );
require( '../../connect.php' );


?>
<html class="x-team-projects">
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
<script type="text/javascript" src="/dashboard/js/pages/teamprojects.js"></script>
<style>
select:after {
    content: '<>';
    font: 17px "Consolas", monospace;
    color: #333;
    -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
    right: 11px;
    /*Adjust for position however you want*/
  
    top: 18px;
    padding: 0 0 2px;
    border-bottom: 1px solid #999;
    /*left line */
  
    position: absolute;
    pointer-events: none;
}
input[type="checkbox"] {
    display: inline-block;
    width: 20px;
}
input[type="checkbox"] + label {
    margin-right: 20px;
}
.memberEmails {
    font-weight: bold;
    line-height: 25px;
    font-style: italic;
}
</style>
<script>
$(document).ready(function(){
	$( "#createNewProject-btn" ).click(function() {
	$("#createNewProject").slideToggle();
	});
	function loadTemplates() {
	var dataString = {'type':"loadTemplates"};
				
				$.ajax({
								type: "POST",
								url: "process.php",
								data: dataString,
								cache: false,
								success: function(result){
								$("#printBack").html(result.printBack);	
								
								}
							});
	}
	loadTemplates();
	
	var membersList = [];
	
	function refreshMemberList() {
		$('.memberEmails').html("");
		
		$('select[tasktypeid]').find('option').remove();
		
		$.each(membersList, function(key, value) { 


			$('.memberEmails').append( '<li userid="'+value[0]+'"><span>'+value[1]+'</span><i class="fa fa-trash pull-right removeReviewer"></i></li>');
		 	
			$('select[tasktypeid]').append('<option value="'+value[0]+'">'+value[1]+'</option>');


		});
			
		console.log(membersList);	
	}
	
	$('#members').keyup(function(){
				  var valThis = $(this).val();
		
		
				if(valThis.charAt(0) === "@" && this.value.length > 1) {
					var newVal = valThis.substring(1, valThis.length);
					$("#showUsernames").fadeIn();
					var dataString = {'type':"getUsernames",typedUsername:newVal};	
					$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/templates/process.php",
						data: dataString,
						cache: false,
						success: function(results){
							
							if (results.foundUsernames !== null) {
								$("#showUsernames").html(results.foundUsernames);
							}
							else {
								
							}
						}
						});
				}
			  else {
				  	$("#showUsernames").html("");
			  }
		});
	
	$(document).on('click','.userTags', function() {
		$("#showUsernames").html("");
		var newMember = $(this).text();
		var newMemberID = $(this).attr("userid");
		
		var dataString = {'type':"newTemplateCheckUsername",username:newMember};	
					
				$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/templates/process.php",
						data: dataString,
						cache: false,
						success: function(results){
							
							if (results !== "") {
								alert(results);
							}
							else {
							
								if (membersList.length == 0 ){
									membersList.push([newMemberID,newMember]);
									
								}
								else {
									if ($(".memberEmails li[userid='"+newMemberID+"']").length == 1 ) {
										
										}
									else {
										membersList.push([newMemberID,newMember]);
										}
									
								}
									
								
									
								
									//	
									
								  }

							
							
								
								
							 refreshMemberList();
								
							
							$('#members').val("");
							
							}
						
						});

	});
	
	$(document).on('click','.memberEmails li .fa-trash', function() {
		$("#showUsernames").html("");
		var itemtoRemoveID = $(this).parent().attr("userID");
		console.log(itemtoRemoveID);
		$('select[tasktypeid] option[value="'+itemtoRemoveID+'"]').remove();
		
		membersList.splice($.inArray(itemtoRemoveID, membersList),1);
		
		$(this).parent().remove().fadeOut();
		
		 refreshMemberList();
		
		
		
		
	});
	
	//adding tasks	
	var count=0;
	$('.tags:not(:last)').click(function(){
    	
        var currentID = $(this).attr("tasktypeid");
		var currentText = $(this).find("span").text();
		var area = $("#addTasks");
		
		
		if ($("#templateTaskType").find('option:selected').val() == "Standard") {
			
			if (currentID === "7") {
				$(area).append('<div class="row row_'+count+' templateTask"><div class="col-sm-12"><div class="remove"><i class="fa fa-times" aria-hidden="true"></i></div><div class="formLabels">Assign <strong>'+currentText+'</strong> task to:*</div><select tasktypeid="'+currentID+'"></select></div><div class="col-sm-12"><div style="padding-left:20px;"><div class="formLabels calEvent">Calendar Event:*</div></div></div>');
				$(".row_"+count).find(".calEvent").after($( "#calendarCategory" ).clone());
				$(".row_"+count).find("#calendarCategory").removeAttr("style");
				
			}
			else {
				$(area).append('<div class="row row_'+count+' templateTask"><div class="col-sm-12"><div class="remove"><i class="fa fa-times" aria-hidden="true"></i></div><div class="formLabels">Assign <strong>'+currentText+'</strong> task to:*</div><select tasktypeid="'+currentID+'"></select></div></div>');
				
			}
			
		}
		else {
		
			if (currentID === "7") {
				$(area).append('<div class="row row_'+count+' templateTask"><div class="col-sm-8"><div class="formLabels">Assign <strong>'+currentText+'</strong> task to:*</div><select tasktypeid="'+currentID+'"></select></div><div class="col-sm-3"><div class="formLabels">Duration (in days):*</div><input type="number" class="TaskDuration_'+currentID+'"></div><div class="col-sm-1"><div class="remove"><i class="fa fa-times" aria-hidden="true"></i></div></div><div class="col-sm-12"><div style="padding-left:20px;"><div class="formLabels calEvent">Calendar Event:*</div></div></div>');
				$(".row_"+count).find(".calEvent").after($( "#calendarCategory" ).clone());
				$(".row_"+count).find("#calendarCategory").removeAttr("style");
			}
			else {
				$(area).append('<div class="row row_'+currentID+' templateTask"><div class="col-sm-8"><div class="formLabels">Assign <strong>'+currentText+'</strong> task to:*</div><select tasktypeid="'+currentID+'"></select></div><div class="col-sm-3"><div class="formLabels">Duration (in days):*</div><input type="number" class="TaskDuration_'+currentID+'"></div><div class="col-sm-1"><div class="remove"><i class="fa fa-times" aria-hidden="true"></i></div></div></div>');
			}
		}

		count++;
		refreshMemberList();
	
});
	
	$('.tags:last').click(function(){
		
	$(".templateTask").remove();
	});
	
	//removing task types
	$(document).on('click','.templateTask .remove', function() {
		$(this).parent().parent().remove();
	});
	
	//chosing task type
	$("#templateTaskType").on('change', function() {
		if ($(this).find('option:selected').val() === "Cadence") {
			$(this).after('<p class="required" style="padding:5px;">The order you enter the tasks below will indicate the flow of the cadence. (Top is first.) </p>');
		}
		else {
			$(this).next(".required").remove();
		}
		
		
		$('#addTasks').html('');
		
	});
	
	//ajax call
	$( "#addNewProjectTemplate-btn" ).click(function() {
		var templateName = $("#templateName").val();
		var templateCategory = $("#templateCategory").find(":selected").val();
		var templateVisible = $("#templateVisible").find(":selected").val();
		var templateTaskType = $("#templateTaskType").find(":selected").val();
		var templateMembers = JSON.stringify(membersList);
		var eventCategoryID;
		var taskVars = [];

		$("#addTasks select[tasktypeid]").each(function() {
			
			var thisUserID = $(this).find(":selected").val();
			var thisTaskTypeID = $(this).attr("tasktypeid");
			
			if (thisTaskTypeID == "7") {
				eventCategoryID = $(this).parent().parent().find("#calendarCategory").find(":selected").val();
			}
			else {
				eventCategoryID = 0;
			}
			
			
			if ($("#templateTaskType").val() === "Cadence") {
				if ($(this) !== "") {
				
				var thisDuration = $(this).parent().next().find("input").val();
			
				//storing the userID and duration only
					
				taskVars.push([thisUserID,thisTaskTypeID,thisDuration,eventCategoryID]);
						
				
			
				}
				
			}
			else {
				
				if ($(this) !== "") {
			
				//storing the userID only
				
					taskVars.push([thisUserID,thisTaskTypeID,eventCategoryID]);
				}
				
			
				
				
				}
			
			
		  	
		});
		
		
		if (templateName === "") {
			$("#templateName").addClass("required");
			return false;
		}
		
		var taskVariables = JSON.stringify(taskVars);
		
		var dataString = {'type':"newTemplate",'templateName':templateName,'templateCategory':templateCategory,'templateVisible':templateVisible,'templateTaskType':templateTaskType,'templateMembers':templateMembers,'taskVariables':taskVariables};
		
		
		
			 $.ajax({
								type: "POST",
								url: "process.php",
								data: dataString,
								cache: false,
								success: function(result){
								setTimeout(function(){
								location.reload();
								
								}, 10);
								}
							});
		
		
	});
	
	//edit template 
	$(document).on('click','.project-btn', function() {
		var templateID = $(this).attr("templateid")
		var dataString = {'type':"editLoad",'templateID':templateID};
		
			$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(result){
					console.log(result);
					$("#templateNameEdit").html(result.templateTitle);	
					$("#templateCategoryEdit").html(result.templateCategory);	
					$("#templateVisibleEdit").html(result.templateVisible);	
					$("#templateTaskTypeEdit").html(result.templateTaskType);	
					$("#templateMembersEdit").html(result.templateMembers);
					$("#templateTasksEdit").html(result.templateTasks);
				}
			});
	});
	
	$(document).on('click','.deleteTemplate', function() {
			var templateID = $(this).attr("id");
			$.alertable.confirm('Are you sure? This cannot be undone!').then(function() {
			
				
				var dataString = {'type':"deleteTemplate",'templateID':templateID};
				
							$.ajax({
								type: "POST",
								url: "process.php",
								data: dataString,
								cache: false,
								success: function(result){
								setTimeout(function(){
								location.reload();
								
								}, 10);
								}
							});
				
			y
			});
	});
});

		</script>
</head>
<body>
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
    <?php
    include( "../../templates/topNav.php" )
    ?>
  </div>
  <!-- /.container-fluid --> 
</nav>
<div class="container-fluid">
<div class="row">
<?php include("../../templates/lhn.php") ?>
<div class="col-sm-10" style="height: 100%;">
  <div class="row">
    <div class="col-sm-12">
      <div class="whitebg">
        <div class="row">
          <div class="col-sm-12">
            <div class="header">
              <button id="createNewProject-btn" class="pull-right createNew noExpand" style="margin-top:-25px;"><i class="fa fa-plus" aria-hidden="true"></i></button>
              <h3><?php echo $groupName?> Templates</h3>
            </div>
          </div>
        </div>
        <!--<hr>-->
        <div class="row" id="createNewProject">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-12">
                <h3>Create A New Template </h3>
                <div id="#validate" class="validate"></div>
                <br>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="formLabels">Template Name:</div>
                    <input type="text" id="templateName" name="templateName">
                  </div>
                  <div class="col-sm-12">
                    <div class="formLabels">Visibility:</div>
                    <select type="text" id="templateVisible">
                      <option value="Public">Public</option>
                      <option value="Private">Private</option>
                    </select>
                  </div>
                  <div class="col-sm-12">
                    <div class="formLabels">Category:</div>
                    <?php

                    $getCategories = "SELECT DISTINCT * FROM `Team Projects Categories` WHERE `GroupID` = '$groupID'";
                    $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

                    echo '<select id="templateCategory">'; // Open your drop down box

                    // Loop through the query results, outputing the options one by one
                    while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                      $categoryName = $row[ 'Category' ];
                      $categoryID = $row[ 'ProjectCategoryID' ];
                      echo "<option value='$categoryID'>$categoryName</option>";
                    }

                    echo '</select>';

                    ?>
                  </div>
                  <div class="col-sm-12">
                    <div class="formLabels">Task Type:</div>
                    <select type="text" id="templateTaskType">
                      <option value="Standard">Standard</option>
                      <option value="Cadence">Cadence</option>
                    </select>
                  </div>
                  <div class="col-sm-12">
                    <div class="formLabels">Members: (Use the @ symbol to find a user.)</div>
                    <div id="showUsernames"></div>
                    <input type="text" id="members" name="members">
                    <br>
                    <ol class="memberEmails">
                    </ol>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="formLabels">Tasks:</div>
                    <?php

                    $getCategories = "SELECT * FROM `Task Categories` WHERE `GroupID` = '$groupID'";
                    $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );
                    while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                      echo "<div class='tags' tasktypeid='" . $row[ 'CategoryID' ] . "'><i class='fa fa-plus-circle' aria-hidden='true'></i><span>" . $row[ 'Category' ] . "</span></div>";
                    }
                    echo "<div class='tags red_bg' style='color:#ffffff'>Clear All</div>";
                    ?>
                    <br>
                    <div id="addTasks"></div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-sm-12 text-right">
                <button id="addNewProjectTemplate-btn" class="genericbtn green_bg" type="submit" name="new" style="margin-left: 0px;">Save</button>
                <hr>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12"> 
            <!--<div class="pull-right"><select name="sortBy" id="sortBy" style="margin-top: 10px !important;">
     			<option value="Date Created DESC">Most Recent</option>
     			<option value="Date Created ASC">Least Recent</option>
     			<option value="Due Date DESC">Due Date DESC</option>
     			<option value="Due Date ASC">Due Date ASC</option>
     			<option value="AtoZ">A to Z</option>
     			<option value="ZtoA">Z to A</option></select></div>
   				</div>	-->
            <div id="printBack"> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- EDIT TEMPLATE MODAL -->
<div class="modal fade" id="editTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header"> <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
      <h4 class="modal-title" id="myModalLabel">View Template</h4>
    </div>
    <div class="modal-body">
      <div class="form-sm">
        <div class="row">
          <div class="col-sm-6">
            <div class="formLabels">Template Name:</div>
            <div id="templateNameEdit"></div>
            </select>
          </div>
          <div class="col-sm-6">
            <div class="formLabels">Category:</div>
            <div id="templateCategoryEdit"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
            <div class="formLabels">Visibility:</div>
            <div id="templateVisibleEdit"></div>
          </div>
          <div class="col-sm-6"><br>
            <div class="formLabels">Task Type:</div>
            <div id="templateTaskTypeEdit"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12"><br>
            <div class="formLabels">Members:</div>
            <div id="templateMembersEdit"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <hr>
            <div class="formLabels">Tasks:</div>
            <div id="templateTasksEdit"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer"> </div>
    </div>
  </div>
</div>
<input type="hidden" id="holdingNotificationCount">
<select style="display:none" id="calendarCategory">
  <?php
  $query = "SELECT DISTINCT * FROM `Calendar Categories` ORDER BY `Category` ASC";
  $query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $query_result ) ) {
    echo "<option value='" . $row[ "CalendarCategoryID" ] . "'>" . $row[ "Category" ] . " Event</option>";
  }
  ?>
</select>
<?php echo $scripts?>
</body>
</html>