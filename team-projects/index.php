<?php
include_once( '../header.php' );
require( '../connect.php' );


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
</style>
<script>
	 $(document).ready(function(){
		 //setting users group as filter default
		 $("#teamID").val(<?php echo $groupID ?>);
		  $("#Status").val("Incomplete");
		 setTimeout(function(){
			$("#goFilter").trigger("click");
			}, 5);
		 
		 
		 $( "#createNewProject-btn" ).click(function() {
			var theHR = $("#createNewProject").prev();
			 theHR.slideToggle();
  $("#createNewProject,.sorter,.printFilter").slideToggle();
});
		
	 });
	 
	 //SORTING PROJECTS 
$(document).ready(function(){
	
	function goFilter(){
		var searchTerm = $("#projectSearch").val();
		var status = $("#Status option:selected" ).val();
		var team = $("#teamID option:selected" ).val();
		var category = $("#Category option:selected" ).val();
		var teamText = $("#teamID option:selected" ).text();
		var categoryText = $("#Category option:selected" ).text();
		var sortBy = $("#sortBy option:selected" ).val();
		$(".printFilter").fadeIn();
			$.ajax({
				type: "POST",
				url: "filter.php",
				data: {'searchTerm':searchTerm,'status':status,'team':team,'category':category,'sortBy':sortBy},
				cache: false,
				success: function(results){
					
					$("#printBackProjects").html(results.printBackProjects).fadeIn();
					if (results.searchTerm !== "") {
						$("#printSearchTermFilter").html('<div class="filterTags" id="clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.status !== "All") {
						$("#printStatusFilter").html('<div class="filterTags" id="clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.team !== "All") {
						$("#printTeamFilter").html('<div class="filterTags" id="clearTeam">'+teamText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.category !== "All") {
						$("#printCategoryFilter").html('<div class="filterTags" id="clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
				}
			});
	}
	
	function clearSingleFilter(thisElement, searchTerm, team, status, category,teamText, categoryText, sortBy) {
		
		$(thisElement).parent().parent().html('');
		
		$.ajax({
				type: "POST",
				url: "filter.php",
				data: {'searchTerm':searchTerm,'status':status,'team':team,'category':category,'sortBy':sortBy},
				cache: false,
				success: function(results){
					
					$("#printBackProjects").html(results.printBackProjects);
					
					if (results.searchTerm !== "") {
						$("#printSearchTermFilter").html('<div class="filterTags" id="clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.status !== "All") {
						$("#printStatusFilter").html('<div class="filterTags" id="clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.team !== "All") {
						$("#printTeamFilter").html('<div class="filterTags" id="clearTeam">'+teamText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.category !== "All") {
						$("#printCategoryFilter").html('<div class="filterTags" id="clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
				}
			});
	}
	
	function clearFilter(searchTerm, team, status, category,teamText, categoryText, sortBy) {
		
		$(".filterTags").remove();
		$("#projectSearch").val("");
		$("#Status,#teamID,#Category").val("All");
		
		$.ajax({
				type: "POST",
				url: "filter.php",
				data: {'searchTerm':searchTerm,'status':status,'team':team,'category':category,'sortBy':sortBy},
				cache: false,
				success: function(results){
					
					$("#printBackProjects").html(results.printBackProjects);
					
				}
			});
	}
	
	function sortBy() {
		var searchTerm = $("#projectSearch").val();
		var status = $("#Status option:selected" ).val();
		var team = $("#teamID option:selected" ).val();
		var category = $("#Category option:selected" ).val();
		var sortBy = $("#sortBy option:selected" ).val();
			$.ajax({
				type: "POST",
				url: "filter.php",
				data: {'searchTerm':searchTerm,'status':status,'team':team,'category':category,'sortBy':sortBy},
				cache: false,
				success: function(results){
					
					$("#printBackProjects").html(results.printBackProjects);
				}
			});
	}
	
	
	$('#goFilter').on('click', function() {
		goFilter();
	});
	
	$('#sortBy').on('change', function() {
		sortBy();
		
	});
	
	$(document).on('click','#clearSearchTerm .fa', function() {
		$("#projectSearch").val("");
		var searchTerm = null;
		var status = $("#Status option:selected" ).val();
		var team = $("#teamID option:selected" ).val();
		var category = $("#Category option:selected" ).val();
		var teamText = $("#teamID option:selected" ).text();
		var categoryText = $("#Category option:selected" ).text();
		var sortBy = $("#sortBy option:selected" ).val();
		
		clearSingleFilter($(this), searchTerm, team, status, category,teamText, categoryText, sortBy);
		
	});
	$(document).on('click','#clearStatus .fa', function() {
		
		var searchTerm = $("#projectSearch").val();
		$("#Status").val("All");
		var status = "All";
		var team = $("#teamID option:selected" ).val();
		var category = $("#Category option:selected" ).val();
		var teamText = $("#teamID option:selected" ).text();
		var categoryText = $("#Category option:selected" ).text();
		var sortBy = $("#sortBy option:selected" ).val();
		
		clearSingleFilter($(this), searchTerm, team, status, category,teamText, categoryText, sortBy);
	});
	
	
	
	
	$(document).on('click','#clearTeam .fa', function() {
		
		var searchTerm = $("#projectSearch").val();
		$("#teamID").val("All");
		var status = $("#Status option:selected" ).val();
		var team = "All";
		var category = $("#Category option:selected" ).val();
		var teamText = $("#teamID option:selected" ).val();
		var categoryText = $("#Category option:selected" ).text();
		var sortBy = $("#sortBy option:selected" ).val();
		
		
		clearSingleFilter($(this), searchTerm, team, status, category,teamText, categoryText, sortBy);
	
	
	
	});
	$(document).on('click','#clearCategory .fa', function() {
		
		var searchTerm = $("#projectSearch").val();
		$("#Category").val("All");
		var status = $("#Status option:selected" ).val();
		var team = $("#teamID option:selected" ).val();
		var category = "All";
		var teamText = $("#teamID option:selected" ).text();
		var categoryText = $("#Category option:selected" ).text();
		var sortBy = $("#sortBy option:selected" ).val();
		
		clearSingleFilter($(this), searchTerm, team, status, category,teamText, categoryText, sortBy);
		
	});
	$(document).on('click','#clearFilters', function() {	
		clearFilter("", "All", "All", "All","All", "All", "Date Created DESC");
	});
	
	
	
});
		</script>
</head>
<body>
<input type="hidden" id="projectUserID" value="<?php echo $userID ?>" name="<?php echo $userID ?>">
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
    <?php include("../templates/topNav.php") ?>
  </div>
  <!-- /.container-fluid --> 
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
    <div class="row">
      <div class="col-sm-12">
        <div class="whitebg">
          <div class="row">
            <div class="col-sm-12">
              <div class="header">
                <button id="createNewProject-btn" class="pull-right createNew noExpand" style="margin-top:-25px;"><i class="fa fa-plus" aria-hidden="true"></i></button>
                <h3>Team Projects</h3>
              </div>
              <p id="message"></p>
              <div class="sorter row">
                <div class="col-sm-4">
                  <div class="formLabels">Title:</div>
                  <input type="text" class="pull-right" placeholder="Search by project title" style="width:100%;" id="projectSearch">
                </div>
                <div class="col-sm-2">
                  <div class="formLabels">Status:</div>
                  <?php

                  $getStatus = "SELECT DISTINCT `Status` FROM `Team Projects`";
                  $getStatus_result = mysqli_query( $connection, $getStatus )or die( "Query to get data from Team task failed: " . mysql_error() );

                  echo '<select id="Status"><option value="All">Select...</option>'; // Open your drop down box

                  // Loop through the query results, outputing the options one by one
                  while ( $row = mysqli_fetch_array( $getStatus_result ) ) {
                    $statusName = $row[ 'Status' ];
                    echo "<option value='$statusName'>$statusName</option>";
                  }

                  echo '<option value="All">Show All</option></select>';

                  ?>
                </div>
                <div class="col-sm-2">
                  <div class="formLabels">Team:</div>
                  <?php

                  $getUsers = "SELECT * FROM `Groups`";
                  $getUsers_result = mysqli_query( $connection, $getUsers )or die( "Query to get data from Team task failed: " . mysql_error() );

                  echo '<select name="addMoreMembersList" id="teamID"><option value="All">Select...</option>'; // Open your drop down box

                  // Loop through the query results, outputing the options one by one
                  while ( $row = mysqli_fetch_array( $getUsers_result ) ) {
                    echo "<option value='" . $row[ 'GroupID' ] . "'>" . $row[ 'Group Name' ] . "</option>";
                  }

                  echo '<option value="All">Show All</option></select>';

                  ?>
                </div>
                <div class="col-sm-2">
                  <div class="formLabels">Category:</div>
                  <?php

                  $getCategories = "SELECT DISTINCT * FROM `Team Projects Categories` WHERE `GroupID` = '$groupID'";
                  $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

                  echo '<select id="Category"><option value="All">Select...</option>'; // Open your drop down box

                  // Loop through the query results, outputing the options one by one
                  while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                    $categoryName = $row[ 'Category' ];
                    $categoryID = $row[ 'ProjectCategoryID' ];
                    echo "<option value='$categoryID'>$categoryName</option>";
                  }

                  echo '<option value="All">Show All</option></select>';

                  ?>
                </div>
                <div class="col-sm-2">
                  <button class="genericbtn" id="goFilter" style="margin-top: 22px;width: 44%;display:inline-block">Filter</button>
                  <button class="genericbtn" id="clearFilters" style="width: 44%;background:#ff0000 !important;display:inline-block;margin-left:10px;">Clear</button>
                </div>
              </div>
              <div class="printFilter row">
                <div class="col-sm-4">
                  <div id="printSearchTermFilter"></div>
                </div>
                <div class="col-sm-2">
                  <div id="printStatusFilter"></div>
                </div>
                <div class="col-sm-2">
                  <div id="printTeamFilter"></div>
                </div>
                <div class="col-sm-2">
                  <div id="printCategoryFilter"></div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row" id="createNewProject">
            <div class="col-sm-12"> <a href="templates/" class="genericbtn pull-right" style=" margin-top: 10px;">Project Templates</a>
              <h3>Create A New Project </h3>
              <br>
              <div id="#validate" class="validate"></div>
            </div>
            <div class="col-sm-6">
              <div class="formLabels">Project Title:*</div>
              <input type="text" id="projectTitle" name="projectTitle" placeholder="" class="validate">
              <br>
              <div class="formLabels">Template:</div>
              <?php

              $getCategories = "SELECT DISTINCT * FROM `Team Projects Templates` JOIN `Group Membership` ON `Team Projects Templates`.`userID`=`Group Membership`.`userID` WHERE `Group Membership`.`GroupID` = '$groupID'";
              $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

              echo '<select id="projectTemplate"><option value="Blank">Blank</option>'; // Open your drop down box

              // Loop through the query results, outputing the options one by one
              while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                $categoryName = $row[ 'Name' ];
                $categoryID = $row[ 'TemplateID' ];
                echo "<option value='$categoryID'>$categoryName</option>";
              }

              echo '</select>';

              ?>
              <div class="templateDepends">
                <div class="formLabels">Category:*</div>
                <?php

                $getCategories = "SELECT DISTINCT * FROM `Team Projects Categories` WHERE `GroupID` = '$groupID'";
                $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

                echo '<select id="projectCategory">'; // Open your drop down box

                // Loop through the query results, outputing the options one by one
                while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                  $categoryName = $row[ 'Category' ];
                  $categoryID = $row[ 'ProjectCategoryID' ];
                  echo "<option value='$categoryID'>$categoryName</option>";
                }

                echo '</select>';

                ?>
                <br>
                <div class="formLabels">Visibility:</div>
                <select type="text" id="projectVisible">
                  <option value="Public">Public</option>
                  <option value="Private">Private</option>
                </select>
                <br>
                <div class="formLabels">Task Type:</div>
                <select type="text" id="projectTaskType">
                  <option value="Standard">Standard</option>
                  <option value="Cadence">Cadence</option>
                </select>
                <br>
                <br>
              </div>
              <br>
            </div>
            <div class="col-sm-6">
              <div class="formLabels">Due Date:*</div>
              <input type="datetime-local" id="projectDueDate" name="projectDueDate" class="validate">
              <br>
              <div class="formLabels">Project URL:</div>
              <input type="text" id="projectURL" name="projectURL" placeholder="">
              <br>
              <div class="formLabels">Project Folder Link:</div>
              <input type="text" id="projectFolder" name="projectFolder" placeholder="">
              <br>
              <div class="formLabels">Project Description:</div>
              <pre style="padding: 0px !important;"><textarea id="projectDescription" placeholder=""></textarea>
</pre>
            </div>
            <br>
            <div class="col-sm-12">
              <button id="addNewProject-btn" class="save noExpand" type="submit" name="new" style="margin-left: 0px;"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
            </div>
            <div class="col-sm-12">
              <hr>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="pull-right">
                <div class="formLabels">Sort:</div>
                <select name="sortBy" id="sortBy">
                  <option value="Date Created DESC">Most Recent</option>
                  <option value="Date Created ASC">Least Recent</option>
                  <option value="Due Date DESC">Due Date DESC</option>
                  <option value="Due Date ASC">Due Date ASC</option>
                  <option value="AtoZ">A to Z</option>
                  <option value="ZtoA">Z to A</option>
                </select>
              </div>
            </div>
            <div id="printBackProjects"> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $scripts?>
</body>
</html>