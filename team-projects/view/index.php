<?php
include_once( '../../header.php' );
require( '../../connect.php' );

$projectSelector = $_GET[ 'projectID' ];
//getting all 

$query1 = "SELECT `Visible` FROM `Team Projects` WHERE `ProjectID` = '$projectSelector'";
$query1_result = mysqli_query( $connection, $query1 )or die( "Query to get data from Team Project failed: " . mysql_error() );

while ( $row = $query1_result->fetch_assoc() ) {
  $printVisible2 = $row[ "Visible" ];

}
//end getting all

//getting membership list 
$getMembershipList2 = "SELECT * FROM `user` JOIN `Team Projects Member List` ON `user`.`userID`= `Team Projects Member List`.`userID` WHERE `ProjectID` = '$projectSelector'";

$getMembershipList2_result = mysqli_query( $connection, $getMembershipList2 )or die( "NEWWWW Query to get data from Team Project failed: " . mysql_error() );

while ( $row = $getMembershipList2_result->fetch_assoc() ) {
  $memberID2 = $row[ "userID" ];
}

if ( $myRole == 'Admin' || $myRole == 'Editor' || $memberID2 == $userI ) {

} else {
  if ( $printVisible2 == 'Private' && $memberID2 != $userID ) {
    header( "location:/dashboard/404/no-access.php" );
  }
}


?>
<html class="x-team-projects">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<?php echo $stylesjs ?> <?php echo $scripts ?> 
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --> 
<!-- WARNING: Respond.js doesn't work if you view the page via file:// --> 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
#search, #searchAll {
    cursor: pointer;
}
#step2, #step3 {
    display: none;
}
.reviewerEmails span {
    font-weight: bold;
    font-style: italic;
}
.delete-review {
    cursor: pointer;
}
.sorter, .printFilter {
    display: none;
}
#taskStatusEdit2 {
    display: none;
}
#printActivities .feed-item p {
    color: #ffffff !important;
}
.hiding {
    display: none;
}
</style>
<script>
	 $(document).ready(function(){
		 
	
		 //setting height for activity feed
		 $(window).resize(function(){
			 if (navigator.userAgent.indexOf("Firefox") > 0) {
  $(".projectActivityFeed").height($(".col-sm-10.projectLoad").height());
			 }
			 else {
				 $(".projectActivityFeed").height($(".col-sm-10.projectLoad").height()-17.5);
			 }
});
		 
		 CKEDITOR.replace('requestCopyEdit');
			CKEDITOR.config.basicEntities = false;
		 


});

	 
</script> 
<script type="text/javascript" src="/dashboard/js/pages/teamprojects.js"></script>
</head>
<body>
<div class="allContent">
  <nav class="navbar navbar-default" style="background:#ffffff; border:none;">
    <div class="container-fluid">
      <?php include("../../templates/topNav.php") ?>
    </div>
    <!-- /.container-fluid --> 
  </nav>
  <div class="container-fluid">
    <div class="row">
      <?php include("../../templates/lhn.php") ?>
      <center>
        <div class="working">
          <p>Loading...</p>
          <br>
          <img src="/dashboard/images/Gear.gif" style="width:100px !important;"></div>
      </center>
      <div class="col-sm-10 projectLoad" style="height: auto;">
        <div class="row">
          <div class="col-sm-12">
            <?php include("../../templates/alerts.php") ?>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12"> 
            
            <!--<div class="gradientBackground"><p class="daysLeft">This project was completed in <strong>3</strong> days.</p></div>-->
            <div class="whitebg" style="margin-bottom: 0px;">
              <div class="row" id="insertNoProject">
                <div class="col-sm-6 noProject">
                  <div class="header"><span id="canEditProject" class="pull-right" style="margin-top:-10px;"></span> <span id="favorite"></span>
                    <h3 id="printProjectTitle"></h3>
                  </div>
                  <div class="row">
                    <div class="col-sm-4">
                      <p>
                      <div class="formLabels">Created By: </div>
                      <span id="printProjectCreatedBy"></span>
                      </p>
                      <p>
                      <div class="formLabels">Status:</div>
                      <em><span id="printProjectStatus"></span></em>
                      </p>
                      <p>
                      <div class="formLabels">Project Folder Link:</div>
                      <span id="printProjectFolder" style="word-wrap: break-word;"></span>
                      </p>
                      <p>
                      <div class="formLabels">URL:</div>
                      <a href="" id="printProjectURL" style="word-wrap: break-word;color:#000000" target="_blank"></a>
                      </p>
                    </div>
                    <div class="col-sm-5">
                      <p>
                      <div class="formLabels">Due Date:</div>
                      <em><span id="printProjectDueDate"></span></em>
                      </p>
                      <p>
                      <div class="formLabels">Category:</div>
                      <em><span id="printProjectCategory"></span></em>
                      </p>
                      <p>
                      <div class="formLabels">Visibility:</div>
                      <em><span id="printVisible"></span></em>
                      </p>
                    </div>
                    <div class="col-sm-3">
                      <h1 class="projectPercentage pulse pull-right"><img src="../../images/Spinner.gif"></h1>
                      <div id="printCopyLink">
                        <center>
                          <div class="copyLink"><i class="fa fa-link" aria-hidden="true"></i> Share</div>
                        </center>
                        <input type="text" id="copyLinkInput">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <p>
                      <div class="formLabels">Project Description:</div>
                      <em>
                      <pre class="text-scroll" id="printProjectDescription"></pre>
                      </em></div>
                    </p>
                    <div id="ticketAvailable"></div>
                  </div>
                </div>
                <div class="col-sm-6 fadeIn noProject">
                  <div class="header"><span id="canAddMembers" class="pull-right"></span>
                    <h3>Project Members</h3>
                  </div>
                  <div class="row" id="addNewMembers" style="border-bottom:1px solid #f1f1f1;margin-bottom:10px;">
                    <div class="col-sm-12">
                      <div class="formLabels">Users: (Use the @ symbol to find a user.)</div>
                      <div class="showUsernames"></div>
                      <input type="text" id="newProjectMembers" name="newProjectMembers" placeholder="Enter the user's @username here.">
                    </div>
                  </div>
                  <p id="clickToRemove-Members">Click individual members to remove.</p>
                  <div id="printMembers"> </div>
                </div>
              </div>
              <center>
                <ul class="nav nav-pills" style="margin-top: 30px;background: #f9f7ff;padding: 10px;">
                  <li id="heading1" class="active"><a data-toggle="pill" href="#content1">Tasks <span class="count" id="allTaskCount"></span></a></li>
                  <li id="heading2"><a data-toggle="pill" href="#content2">Reviews <span class="count" id="allReviewCount"></span></a></li>
                  <li id="heading2"><a data-toggle="pill" href="#content2">Notes <span class="count" id="allNotesCount"></span></a></li>
                  <li id="heading3"><a data-toggle="pill" href="#content3">Files <span class="count" id="fileCount"></span></a></li>
                  <li id="heading4"><a data-toggle="pill" href="#content4">Copy <span class="count" id="copyCount"></span></a></li>
                </ul>
              </center>
              <div class="tab-content">
                <div id="content1" class="tab-pane fade in active">
                  <div class="row fadeIn noProject" id="Tasks">
                    <div class="col-sm-12">
                      <div style="min-height: 569px;">
                        <div class="header"> <span id="canAddTask"></span>
                          <h3>Tasks</h3>
                        </div>
                        <div class="row">
                          <div class="col-sm-5">
                            <ul class="nav toggler" style="width:187px;margin-top: 10px;">
                              <li id="myTasks1" class="active" style="width:50%"><a data-toggle="pill" href="#myTasks">My Tasks</a></li>
                              <li id="allTasks1" style="width:50%;float:right"><a data-toggle="pill" href="#allTasks">All Tasks</a></li>
                              <li id="cadence1" style="float:right;display:none"><a data-toggle="pill" href="#cadence">Cadence</a></li>
                            </ul>
                            <br>
                            <div id="successfulTaskMessage"></div>
                          </div>
                          <div class="col-sm-7 pull-right"> </div>
                        </div>
                        <div class="tab-content">
                          <div id="myTasks" class="tab-pane fade in active">
                            <p class="pull-right" style="margin-top: -57px;" id="search">Sort/Filter/Search &nbsp; <i class="fa fa-search" aria-hidden="true"></i></p>
                            <div class="sorter row" style="clear:both">
                              <div class="col-sm-4">
                                <div class="formLabels">Search:</div>
                                <input type="text" class="pull-right filterSearch" placeholder="Search by title" style="width:100%;">
                              </div>
                              <div class="col-sm-2">
                                <div class="formLabels">Status:</div>
                                <?php

                                $getStatus = "SELECT DISTINCT `Status` FROM `Tasks` WHERE `userID` = '$userID' AND `ProjectID` = '$projectSelector'";
                                $getStatus_result = mysqli_query( $connection, $getStatus )or die( "Query to get data from Team task failed: " . mysql_error() );

                                echo '<select class="Status"><option value="All">Select...</option>'; // Open your drop down box

                                // Loop through the query results, outputing the options one by one
                                while ( $row = mysqli_fetch_array( $getStatus_result ) ) {
                                  $statusName = $row[ 'Status' ];
                                  echo "<option value='$statusName'>$statusName</option>";
                                }

                                echo '<option value="All">Show All</option></select>';

                                ?>
                              </div>
                              <div class="col-sm-2">
                                <div class="formLabels">Requested By:</div>
                                <?php

                                $getUsers = "SELECT * FROM `Team Projects Member List` JOIN `user` ON `Team Projects Member List`.`userID` = `user`.`userID` WHERE `ProjectID` = '$projectSelector'";
                                $getUsers_result = mysqli_query( $connection, $getUsers )or die( "Query to get data from Team task failed: " . mysql_error() );

                                echo '<select name="addMoreMembersList" class="userID"><option value="All">Select...</option>'; // Open your drop down box

                                // Loop through the query results, outputing the options one by one
                                while ( $row = mysqli_fetch_array( $getUsers_result ) ) {
                                  echo "<option value='" . $row[ 'userID' ] . "'>" . $row[ 'First Name' ] . "</option>";
                                }

                                echo '<option value="All">Show All</option></select>';

                                ?>
                              </div>
                              <div class="col-sm-2">
                                <div class="formLabels">Category:</div>
                                <?php

                                $getCategories = "SELECT DISTINCT `CategoryID`,`Task Categories`.`Category` FROM `Task Categories` JOIN `Tasks` ON `Tasks`.`Category` = `Task Categories`.`CategoryID` WHERE `Tasks`.`ProjectID` = '$projectSelector'";
                                $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

                                echo '<select class="Category"><option value="All">Select...</option>'; // Open your drop down box

                                // Loop through the query results, outputing the options one by one
                                while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                                  $categoryName = $row[ 'Category' ];
                                  $categoryID = $row[ 'CategoryID' ];
                                  echo "<option value='$categoryID'>$categoryName</option>";
                                }

                                echo '<option value="All">Show All</option></select>';

                                ?>
                              </div>
                              <div class="col-sm-2"><br>
                                <button class="genericbtn goFilter" style="margin-left: 0px;margin-right: 7px;">Filter</button>
                                <button class="genericbtn clearFilters" style="margin-left: 0px;background:#ff0000 !important;">Clear</button>
                              </div>
                            </div>
                            <hr style="margin-bottom: 10px;">
                            <div class="printFilter row">
                              <div class="col-sm-4">
                                <div class="printSearchTermFilter"></div>
                              </div>
                              <div class="col-sm-2">
                                <div class="printStatusFilter"></div>
                              </div>
                              <div class="col-sm-2">
                                <div class="printOwnerFilter"></div>
                              </div>
                              <div class="col-sm-2">
                                <div class="printCategoryFilter"></div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="pull-right">
                                  <div class="formLabels">Sort:</div>
                                  <select name="sortBy" class="sortBy">
                                    <option value="Date Created DESC">Most Recent</option>
                                    <option value="Date Created ASC">Least Recent</option>
                                    <option value="Due Date DESC">Due Date DESC</option>
                                    <option value="Due Date ASC">Due Date ASC</option>
                                    <option value="AtoZ">A to Z</option>
                                    <option value="ZtoA">Z to A</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <table class="printTasks" cellpadding="10" cellspacing="10">
                              <tr class='TasksHeader'>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                            </table>
                            <div class="overflow">
                              <table id="printMyTasks" class="projectTasks">
                              </table>
                            </div>
                          </div>
                          <div id="allTasks" class="tab-pane fade">
                            <p class="pull-right" style="margin-top: -57px;" id="searchAll">Sort/Filter/Search &nbsp; <i class="fa fa-search" aria-hidden="true"></i></p>
                            <div class="sorter row" style="clear:both">
                              <div class="col-sm-4">
                                <div class="formLabels">Search:</div>
                                <input type="text" class="pull-right filterSearch" placeholder="Search by title" style="width:100%;">
                              </div>
                              <div class="col-sm-2">
                                <div class="formLabels">Status:</div>
                                <?php

                                $getStatus = "SELECT DISTINCT `Status` FROM `Tasks` WHERE `userID` = '$userID' AND `ProjectID` = '$projectSelector'";
                                $getStatus_result = mysqli_query( $connection, $getStatus )or die( "Query to get data from Team task failed: " . mysql_error() );

                                echo '<select class="Status"><option value="All">Select...</option>'; // Open your drop down box

                                // Loop through the query results, outputing the options one by one
                                while ( $row = mysqli_fetch_array( $getStatus_result ) ) {
                                  $statusName = $row[ 'Status' ];
                                  echo "<option value='$statusName'>$statusName</option>";
                                }

                                echo '<option value="All">Show All</option></select>';

                                ?>
                              </div>
                              <div class="col-sm-2">
                                <div class="formLabels">Requested By:</div>
                                <?php

                                $getUsers = "SELECT * FROM `Team Projects Member List` JOIN `user` ON `Team Projects Member List`.`userID` = `user`.`userID` WHERE `ProjectID` = '$projectSelector'";
                                $getUsers_result = mysqli_query( $connection, $getUsers )or die( "Query to get data from Team task failed: " . mysql_error() );

                                echo '<select name="addMoreMembersList" class="userID"><option value="All">Select...</option>'; // Open your drop down box

                                // Loop through the query results, outputing the options one by one
                                while ( $row = mysqli_fetch_array( $getUsers_result ) ) {
                                  echo "<option value='" . $row[ 'userID' ] . "'>" . $row[ 'First Name' ] . "</option>";
                                }

                                echo '<option value="All">Show All</option></select>';

                                ?>
                              </div>
                              <div class="col-sm-2">
                                <div class="formLabels">Category:</div>
                                <?php

                                $getCategories = "SELECT DISTINCT `CategoryID`,`Task Categories`.`Category` FROM `Task Categories` JOIN `Tasks` ON `Tasks`.`Category` = `Task Categories`.`CategoryID` WHERE `Tasks`.`ProjectID` = '$projectSelector'";
                                $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

                                echo '<select class="Category"><option value="All">Select...</option>'; // Open your drop down box

                                // Loop through the query results, outputing the options one by one
                                while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                                  $categoryName = $row[ 'Category' ];
                                  $categoryID = $row[ 'CategoryID' ];
                                  echo "<option value='$categoryID'>$categoryName</option>";
                                }

                                echo '<option value="All">Show All</option></select>';

                                ?>
                              </div>
                              <div class="col-sm-2"><br>
                                <button class="genericbtn goFilter" style="margin-left: 0px;margin-right: 7px;">Filter</button>
                                <button class="genericbtn clearFilters" style="margin-left: 0px;background:#ff0000 !important;">Clear</button>
                              </div>
                            </div>
                            <hr style="margin-bottom: 10px;">
                            <div class="printFilter row">
                              <div class="col-sm-4">
                                <div class="printSearchTermFilter"></div>
                              </div>
                              <div class="col-sm-2">
                                <div class="printStatusFilter"></div>
                              </div>
                              <div class="col-sm-2">
                                <div class="printOwnerFilter"></div>
                              </div>
                              <div class="col-sm-2">
                                <div class="printCategoryFilter"></div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="pull-right">
                                  <div class="formLabels">Sort:</div>
                                  <select name="sortBy" class="sortBy">
                                    <option value="Date Created DESC">Most Recent</option>
                                    <option value="Date Created ASC">Least Recent</option>
                                    <option value="Due Date DESC">Due Date DESC</option>
                                    <option value="Due Date ASC">Due Date ASC</option>
                                    <option value="AtoZ">A to Z</option>
                                    <option value="ZtoA">Z to A</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <table class="printTasks" cellpadding="10" cellspacing="10">
                              <tr class='TasksHeader'>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                            </table>
                            <div class="overflow">
                              <table id="printTasks" class="projectTasks">
                              </table>
                            </div>
                          </div>
                          <div id="cadence" class="tab-pane fade">
                            <div class="row" style="margin: 0px !important;"> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="content2" class="tab-pane fade in">
                  <div class="row fadeIn noProject">
                    <div class="col-sm-6">
                      <div style="height:569px;">
                        <div class="header"> <span id="canAddReview"></span>
                          <h3>Reviews</h3>
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <table class="printFiles" id="printReviews">
                              <thead>
                                <tr class="printFiles_header">
                                  <th style="width: 25%;text-align: left !important;">Title</th>
                                  <th style="width: 25%;text-align: center !important;">Due Date</th>
                                  <th style="width: 20%;text-align: center !important;">Status</th>
                                  <th style="width: 10%;text-align: center !important;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6" id="ProjectNotes">
                      <div style="height:569px;">
                        <div class="header">
                          <h3>Notes</h3>
                        </div>
                        <div class="projectNotes">
                          <div id="printMessages"> </div>
                        </div>
                        <br>
                        <span id="canMention"></span> <span id="canAddNote"></span> </div>
                    </div>
                  </div>
                </div>
                <div id="content3" class="tab-pane fade in">
                  <div class="row fadeIn noProject">
                    <div class="col-sm-12">
                      <div style="height:569px;">
                        <div class="header">
                          <h3>Files</h3>
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <p>File upload limit: 10MB.</p>
                            <span id="canAddFile"></span>
                            <div style="height: 328px; overflow: scroll;  margin-bottom: 40px;">
                              <div class="row" id="printFiles"> </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="content4" class="tab-pane fade in">
                  <div class="row fadeIn noProject">
                    <div class="col-sm-12">
                      <div style="height:569px;">
                        <div class="header"> <span id="canAddCopy"></span>
                          <h3>Copy</h3>
                        </div>
                        <div class="row">
                          <div class="col-sm-6 make100">
                            <h4>Latest</h4>
                            <div class="text-scroll" id="printProjectCopy"></div>
                          </div>
                          <div class="col-sm-6 hiding">
                            <h4>Update</h4>
                            <textarea id="requestCopyEdit"></textarea>
                            <br>
                            <button class="save pull-right" id="saveCopy"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
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
      <div class="col-sm-1 projectActivityFeed">
        <h3>Activity Feed</h3>
        <div class="scroll" style="height:90%;">
          <ol class="activity-feed" id="printActivities">
          </ol>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ADD TASK Modal -->
<div class="modal fade" id="addNewTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Add A New Task</h4>
      </div>
      <div class="modal-body">
        <div class="form-sm">
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Title:*</div>
              <input type="text" id="taskTitle" name="taskTitle" placeholder="" style="width:100%" class="validate">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="formLabels">Category:* </div>
              <?php

              $getCategories = "SELECT * FROM `Task Categories` WHERE `GroupID` = '$groupID'";
              $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

              echo '<select name="taskCategory" id="taskCategory" style="width:100%" class="validate">'; // Open your drop down box

              // Loop through the query results, outputing the options one by one
              while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                echo "<option value='" . $row[ 'CategoryID' ] . "'>" . $row[ 'Category' ] . "</option>";
              }

              echo '</select>';

              ?>
            </div>
            <div class="col-sm-6">
              <div class="formLabels">Due Date:*</div>
              <input type="datetime-local" id="taskDueDate" name="taskDueDate" style="width:100%" class="validate">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Assign to:* </div>
              <select name="addTaskMembershipList" id="printMembersDropdown" style="width:100%" class="validate">
                <option value="">Select</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Description:</div>
              <pre style="padding:0px !important"><textarea id="taskDescription" name="taskDescription" style="width:100%"></textarea>
</pre>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="save" id="addNewTaskModal-btn"><i class="fa fa-floppy-o"></i></button>
      </div>
    </div>
  </div>
</div>

<!-- VIEW TASK Modal -->
<div class="modal fade" id="viewTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modalHeader">
        <table border="0" cellspacing="0" cellpadding="5" id="reassignTask">
          <tbody>
            <tr>
              <td valign="middle" width="80px"><img src="" id="taskAssignedToPPView" style="border-radius:50%;width:60px;"></td>
              <td valign="middle"><p>Task Assigned To:<br>
                  <strong id="taskAssignedTo"></strong></p></td>
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
      <div class="modal-body"  id="viewTaskModal">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-sm">
              <div class="row">
                <div class="col-sm-6">
                  <div class="formLabels">Title:</div>
                  <div id="taskTitleView"></div>
                </div>
                <div class="col-sm-6">
                  <div class="formLabels">Status:</div>
                  <div id="taskStatusView"><strong class='taskStatus'></strong></div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-6">
                  <div class="formLabels">Category: </div>
                  <div id="taskCategoryView" style="display: inline-block;"></div>
                  <div id="taskEventCategory" style="display: inline-block;"></div>
                </div>
                <div class="col-sm-6">
                  <div class="formLabels">Due Date:</div>
                  <div id="taskDueDateView"></div>
                </div>
              </div>
              <br>
              <br>
              <div class="row">
                <div class="col-sm-12">
                  <div class="formLabels">Description:</div>
                  <div id="taskDescriptionView"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="commentSide">
              <div id="printComments"></div>
              <div id="canComment"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-body" id="editTaskModal">
        <div class="form-sm">
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Title:*</div>
              <input type="text" id="taskTitleEdit" name="taskTitle" placeholder="" style="width:100%" class="validate">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="formLabels">Category:* </div>
              <?php

              $getCategories = "SELECT * FROM `Task Categories` WHERE `GroupID` = '$groupID'";
              $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

              echo '<select name="taskCategoryEdit" id="taskCategoryEdit" style="width:100%">'; // Open your drop down box

              // Loop through the query results, outputing the options one by one
              while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                echo "<option value='" . $row[ 'CategoryID' ] . "'>" . $row[ 'Category' ] . "</option>";
              }

              echo '</select>';

              ?>
            </div>
            <div class="col-sm-6">
              <div class="formLabels">Due Date:*</div>
              <input type="datetime-local" id="taskDueDateEdit" name="taskDueDate" style="width:100%" class="validate">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Description:</div>
              <pre style="padding:0px !important"><textarea id="taskDescriptionEdit" name="taskDescription" style="width:100%"></textarea>
</pre>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Status:*</div>
              <div class="statusSelectorContainer">
                <div status="New" class="statusSelector">New</div>
                <div status="In Review" class="statusSelector">In Review</div>
                <div status="Approved" class="statusSelector">Approved</div>
                <div status="Completed" class="statusSelector">Completed</div>
              </div>
              <br>
              <div id="showMessage">
                <div class="formLabels">Message:</div>
                <textarea type="datetime-local" id="taskMessage" name="taskMessage" style="width:100%"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <hr>
        <div id="taskCTAs" style="display:inline-block"></div>
        <button type="button" class="genericbtn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>

<!-- EDIT INFO Modal -->
<div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modalHeader">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tbody>
            <tr>
              <td valign="middle" width="80px"><img src="" id="projectCreatedByPP" style="border-radius:50%;width:60px;"></td>
              <td valign="middle"><p>Project Created By:<br>
                  <strong><span id="printProjectCreatedByTop"></span></strong></p></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-body">
        <div class="form-sm">
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Title:*</div>
              <input type="text" id="projectTitleEdit" name="projectTitleEdit" placeholder="" style="width:100%" class="validate">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="formLabels">Due Date:*</div>
              <input type="datetime-local" id="projectDueDateEdit" name="projectDueDateEdit" style="width:100%" class="validate">
            </div>
            <div class="col-sm-6">
              <div class="formLabels">Category:*</div>
              <?php

              $getCategories = "SELECT DISTINCT * FROM `Team Projects Categories` WHERE `GroupID` = '$groupID'";
              $getCategories_result = mysqli_query( $connection, $getCategories )or die( "Query to get data from Team task failed: " . mysql_error() );

              echo '<select id="projectCategoryEdit" name="projectCategoryEdit" style="width:100%">>'; // Open your drop down box

              // Loop through the query results, outputing the options one by one
              while ( $row = mysqli_fetch_array( $getCategories_result ) ) {
                $categoryName = $row[ 'Category' ];
                $categoryID = $row[ 'ProjectCategoryID' ];
                echo "<option value='$categoryID'>$categoryName</option>";
              }

              echo '</select>';

              ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6"> <strong class="formLabels">Project Folder:</strong>
              <input type="text" id="projectFolderEdit" name="projectFolder" placeholder="Project Folder Link">
            </div>
            <div class="col-sm-6"> <strong class="formLabels">Visibility: </strong>
              <select type="text" id="projectVisibleEdit" name="projectVisibleEdit" style="width:100%">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12"> <strong class="formLabels">URL:</strong>
              <input type="text" id="projectURLEdit" name="projectURL" placeholder="Project URL">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12"> <strong class="formLabels">Description:</strong><br>
              <textarea id="projectDescriptionEdit" name="projectDescriptionEdit" style="width:100%"></textarea>
            </div>
          </div>
          <br>
          <button type="button" class="save pull-right" id="editProjectModal-btn" data-dismiss="modal"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ADD REVIEW Modal -->
<div class="modal fade" id="addNewReview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Add A New Review</h4>
      </div>
      <div class="modal-body">
        <div class="form-sm">
          <div id="step1">
            <div class="row">
              <div class="col-sm-12">
                <div class="formLabels">Title:*</div>
                <input type="text" id="reviewTitle" name="reviewTitle" style="width:100%" class="validate">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="formLabels">Type:*</div>
                <select id="reviewType">
                  <option value="Requester">Requester</option>
                  <option value="Content">Content</option>
                </select>
              </div>
              <div class="col-sm-6">
                <div class="formLabels">Due Date:*</div>
                <input type="datetime-local" id="reviewDueDate" name="reviewDueDate" style="width:100%" class="validate">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="formLabels">Reviewers: (Use the @ symbol to find a user.)</div>
                <div class="showUsernames"></div>
                <input type="text" id="reviewMembers" name="reviewMembers" placeholder="Enter the reviewer's @username here.">
                <br>
                <ol class="reviewerEmails">
                </ol>
              </div>
            </div>
            <div class="row">
              <hr>
              <div class="col-sm-12">
                <ul class="myTabs" role="tablist">
                  <li role="presentation" class="active"><a href="#desktopImage" role="tab" data-toggle="tab" class="active one">Desktop Mockup</a> </li>
                  <li role="presentation"><a href="#mobileImage" role="tab" data-toggle="tab" class="two">Mobile Mockup</a> </li>
                </ul>
                <div class="tab-content">
                  <div id="desktopImage" role="tabpanel" class="tab-pane fade in active">
                    <div class="row">
                      <div class="col-sm-12"> <br>
                        <div class="formLabels">Desktop Preview Image:</div>
                        <input type="file" id="desktopPreviewImage" name="desktopPreviewImage[]">
                      </div>
                    </div>
                  </div>
                  <div id="mobileImage" role="tabpanel" class="tab-pane fade">
                    <div class="row">
                      <div class="col-sm-12"> <br>
                        <div class="formLabels">Mobile Preview Image:
                          <input type="file" id="mobilePreviewImage" name="mobilePreviewImage[]">
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
      <div class="modal-footer">
        <button type="button" class="save" id="addNewReviewFinal"><i class="fa fa-floppy-o"></i></button>
      </div>
    </div>
  </div>
</div>

<!-- EDIT REVIEW Modal -->
<div class="modal fade" id="editReview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modalHeader">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tbody>
            <tr>
              <td valign="middle" width="80px"><img src="" id="reviewCreatedByPP" style="border-radius:50%;width:60px;"></td>
              <td valign="middle"><p>Review Created By:<br>
                  <strong><span id="reviewCreatedByFullName"></span></strong></p></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-body">
        <div class="form-sm">
          <div class="row">
            <div class="col-sm-12">
              <div class="formLabels">Title:*</div>
              <input type="text" id="reviewTitleEdit" name="reviewTitleEdit" placeholder="" style="width:100%" class="validate">
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="formLabels">Due Date:*</div>
              <input type="datetime-local" id="reviewDueDateEdit" name="projectDueDateEdit" style="width:100%" class="validate">
            </div>
            <div class="col-sm-6">
              <div class="formLabels">Type:*</div>
              <select id="reviewTypeEdit">
                <option value="Requester">Requester</option>
                <option value="Content">Content</option>
              </select>
            </div>
          </div>
          <br>
          <button type="button" class="save pull-right" id="editReviewModal-btn" data-dismiss="modal"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="holdingTaskID">
<script type="text/javascript" src="/dashboard/js/taskFilter.js"></script>
</body>
</html>
