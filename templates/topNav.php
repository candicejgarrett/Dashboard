<?php
//initializing variables
$settingsTopBar = "";

//SETTINGS DROPDOWN
//admin
if ($myRole === 'Admin') {
	
	$settingsTopBar = "
	<li>
		<a href='/dashboard/users/me.php'>My Profile</a>
	</li>
	<li>
		<a href='/dashboard/users/'>User Directory</a>
	</li>
	<li>
		<a href='/dashboard/settings/'>Dashboard Settings</a>
	</li>
	<li>
		<a href='/dashboard/logout.php'>Sign Out</a>
	</li>";
}
else {
	//if manager
	if ($myLevel === '1') {
		$settingsTopBar = "
	<li>
		<a href='/dashboard/users/me.php'>My Profile</a>
	</li>
	<li>
		<a href='/dashboard/users/'>User Directory</a>
	</li>
	<li>
		<a href='/dashboard/logout.php'>Sign Out</a>
	</li>
	";
	}
	//else not manageer
	else {
		$settingsTopBar = "
	<li>
		<a href='/dashboard/users/me.php'>My Profile</a>
	</li>
	<li>
		<a href='/dashboard/users/'>User Directory</a>
	</li>
	<li>
		<a href='/dashboard/logout.php'>Sign Out</a>
	</li>";
	}
	
}

//END SETTINGS DROPDOWN


//getting Todo Info

$query = "SELECT * FROM `Priority Levels`";
$query_result = mysqli_query($connection, $query) or die ("1 failed: ".mysql_error());
	
	 while($row = $query_result->fetch_assoc()) {
        $priorityLevelID = $row["levelID"];
		$priorityTitle = $row["Title"];
		$priorityColor = $row["Color"];
		
	 }



echo "<a class='navbar-brand' href='/dashboard/'>Dashboard</a><div class='navbar-collapse'>
      <div id='searchBar'><i class='fa fa-search' aria-hidden='true'></i></div>
	  <div id='searchBarInput'><input type='text' placeholder='Search everything...'></div>
	  <div id='searchResultsContainer'>
	  	<div class='row' style='margin:0px;'>
			<div class='col-sm-12'>
			<div id='closeTopSearchResults' class='pull-right'><i class='fa fa-times' aria-hidden='true'></i></div>
				<div class='header'>
					<h1>Search Results</h1>
				</div>
				<div id='projectResultsContainer'>
					<h3 class='searchHeader'>Projects</h3>
					<div class='row'>
						<div id='printProjectResults'></div>
					</div>
				</div>
				
				
				<div id='calendarResultsContainer'>
					<h3 class='searchHeader'>Content Calendar</h3>
					<div class='row'>
						<div id='printCalendarResults'></div>
					</div>
				</div>
				<div id='KCResultsContainer'>
					<h3 class='searchHeader'>Knowledge Center</h3>
					<div class='row'>
						<div id='printKCResults'></div>
					</div>
				</div>
				<div id='userResultsContainer'>
					<h3 class='searchHeader'>Users</h3>
					<div class='row'>
						<div id='printUserResults'></div>
					</div>
				</div>
			</div>
	  	</div>
	  </div>
	  <ul class='nav navbar-nav navbar-right topNav-bar'>
	 <li class='topNav dropdown' id='showTodoList'>
	<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
		<div class='todoCount'></div>
		<i class='fa fa-list-ul' aria-hidden='true' style='font-weight:300 !important;'></i>
	</a>
	<ul class='dropdown-menu todoList'>
		<div class='todoListContainer'>
			<div class='header'>
			<p class='pull-right' aria-hidden='true' id='showAddNewTodo'>+</p>
			<p class='pull-right' aria-hidden='true' id='showTodoFiters'><i class='fa fa-filter' aria-hidden='true'></i></p>
			<h1>My Quick Tasks</h1>
			<p><span class='printTodoCount'></span> of <span class='printTotalCount'></span> Item(s) Left</p>
			</div>
		<div class='todoListFilterContainer'>
			
			<div class='row'>
				<div class='col-sm-6'>
					<p>Filter:</p>
					<ul id='filterPriorityLevels'>
							
					</ul>
					
				</div>
				<div class='col-sm-6'>
					<p>Search:</p>
					<input type='text' id='searchTodo' placeholder='Search title...'>
				</div>
				<div class='col-sm-12'>
					<p id='clearTodoFilter'>Clear</p>
				</div>
			</div>
			<hr>
			
		
		</div>
		
		<div class='printTodoList'>
		
			
		
		</div>
		<div class='addNewTodoContainer'>
			<div class='row'>
				<center>
				<div class='choosePriorityContainer'>
					<p><strong>Priority</strong><br><span style='text-decoration:none;color:#757575'>Low | Medium | High | Critical</span></p>
						<ul id='printPriorityLevels'>
							
						</ul>
				</div>
				</center>
			</div>
			<div class='row'>
				<div class='col-sm-9'>
					
					<input type='text' placeholder='Enter new todo item...' id='newTodoItem' style='padding: 7.5px;'></input>
				</div>
				<div class='col-sm-3' style='padding-left: 0px;float: right;text-align: right;'>
					<div id='addNewTodoItem' class='genericbtn'>Add</div>
				</div>
			</div>
		</div>
		</div>
		<li style='border: 0px;'>
			<div id='clearTodoList' style='float:right !important'>Clear All</div>
			
		</li>
	</ul>
	</li>
	 <li class='topNav dropdown' id='showNotifications'>
	<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
		<div class='notificationCount'></div>
		<i class='fa fa-bell changeNew' aria-hidden='true'></i>
	</a>
	<div class='dropdown-menu' style='padding-bottom: 0px;'>
		<div class='notificationListContainer'>
		<div class='header'>
			<h1>Notifications</h1>
		</div>
		<div id='notificationSort'>
		<center>
		<div class='formLabels active' controlsNotif='printAllNotifications' whichNotif='All' id='allNotif'><div class='dot'></div>All</div>
		<div class='formLabels' controlsNotif='printProjectNotifications' whichNotif='Project' id='projectNotif'><div class='dot'></div>Projects</div>
		<div class='formLabels' controlsNotif='printTaskNotifications' whichNotif='Task' id='taskNotif'><div class='dot'></div>Tasks</div>
		<div class='formLabels' controlsNotif='printReviewNotifications' whichNotif='Review' id='reviewNotif'><div class='dot'></div>Reviews</div>
		<div class='formLabels' controlsNotif='printCalendarNotifications' whichNotif='Event' id='eventNotif'><div class='dot'></div>Calendar</div>
		<div class='formLabels' controlsNotif='printRequestNotifications' whichNotif='Ticket' id='requestNotif'><div class='dot'></div>Requests</div>
		</center>
		</div>
		<hr style='clear: both;display: block;margin: 6px 0px 5px;'>
		<div style='height:402px'>
		<div class='printNotifications' id='printAllNotifications'></div>
		<div class='printNotifications' id='printProjectNotifications'></div>
		<div class='printNotifications' id='printTaskNotifications'></div>
		<div class='printNotifications' id='printReviewNotifications'></div>
		<div class='printNotifications' id='printCalendarNotifications'></div>
		<div class='printNotifications' id='printRequestNotifications'></div>
		</div>
		<div>
			<div id='clearNotifications' class='clearNotifications'>Clear All</div>
			<div id='readNotifications'>Mark All Read</div>
		</div>
		</div>
	</div>
</li>
<li class='topNav dropdown' id='showFavoritesList'>
	<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
		<i class='fa fa-heart' aria-hidden='true'></i>
	</a>
	<ul class='dropdown-menu favoritesList'>
		<div class='favoritesListContainer'>
			<div class='header'>
			<h1>My Favorites</h1>
			<p><span class='printFavoriteCount'></span> Bookmarked Project(s)</p>
			</div>
		<div class='printFavoritesList'>
			
			
		
		</div>
		
		</div>
		
	</ul>
	</li>
<li class='topNav dropdown' id='settings'>
	<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
		<i class='fa fa-cog' aria-hidden='true'></i>
	</a>
	
	<div class='dropdown-menu' id='settingsList'>
		<div class='settingsListContainer'>
		<div class='header'>
				<h1>Settings</h1>
				</div>
		<div class='printSettingsList'>
		<ul>".$settingsTopBar."</ul>
		</div>
		</div>
	</div>
</li>
 </ul>
 </div>
";



?>