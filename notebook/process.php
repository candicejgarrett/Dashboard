<?php

include_once('../header.php');
 require('../connect.php');
include('../functions/global.php');

$type = $_POST['type'];

if($type == 'getAll')
{
	$query = "SELECT DISTINCT * FROM `Notebooks` WHERE `userID` ='$userID' ORDER BY `Notebook Order` ASC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
		$title = $row["Title"];
		$notebookID = $row["NotebookID"];
		$color = $row["Color"];
		
		$notebooks[]= '
		<div class="noteBookContainer" notebookID="'.$notebookID.'">
			<div class="tabIcon" style="border-color:'.$color.'">
				<div class="tabBox" style="background:'.$color.'"></div>
			</div>
			<div class="title"><h3>'.$title.'</h3></div>
			<div class="notebookOrderBy"><i class="fa fa-bars" aria-hidden="true"></i></div>
		</div>
									';
	}

	
	$response = ["notebooks" => $notebooks];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if($type == 'getPages')
{
	
	if (isset($_POST['notebookID'])) {
		
	
	$notebookID = $_POST['notebookID'];
	
	$query = "SELECT DISTINCT * FROM `Notebooks Pages` WHERE `NotebookID` ='$notebookID' ORDER BY `Page Order` ASC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
		$title = $row["Title"];
		$notebookID = $row["NotebookID"];
		$pageID = $row["PageID"];
		$pages[]= '
		<div class="pageContainer" pageID="'.$pageID.'" pageNotebookID="'.$notebookID.'">
			<div class="notebookOrderBy"><i class="fa fa-bars" aria-hidden="true"></i></div>
			<div class="title"><h3>'.$title.'</h3></div>
			
		</div>
									';
	}

	
	$response = ["pages" => $pages];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	}
	else {
		return false;
	}
	
}

if($type == 'newNotebook')
{
	$title = addslashes($_POST['title']);
	$color = addslashes($_POST['color']);

	addNotebook($title, $color);
	
	$results = ["notebookID" => $notebookID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
}

if($type == 'newPage')
{
	$title = addslashes($_POST['title']);
	$notebookID =$_POST['notebookID'];
	
	$pageID = addNotebookPage($notebookID, $title);
	
	$results = ["pageID" => $pageID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
}

if($type == 'deleteNotebook')
{
	$notebookID = $_POST['notebookID'];

deleteNotebook($notebookID);
	

}

if($type == 'deletePage')
{
	$pageID = $_POST['pageID'];

	deleteNotebookPage($pageID);

}
	
if($type == 'saveNotebook')
{
	$title = addslashes($_POST['title']);
	$color = addslashes($_POST['color']);
	$notebookID = $_POST['notebookID'];
	
	$notebookID = updateNotebook($notebookID, $title, $color);
	

	$notebookID = mysqli_insert_id($connection);
	$results = ["notebookID" => $notebookID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
}

if($type == 'savePage')
{
	$title = addslashes($_POST['title']);
	$content = addslashes($_POST['content']);
	$pageID = $_POST['pageID'];
	
	$pageID = updateNotebookPage($pageID, $title, $content);
	
	$results = ["pageID" => $pageID];
			   
	header('Content-Type: application/json'); 
	echo json_encode($results);
}

if($type == 'viewPage')
{
	$notebookID = $_POST['notebookID'];
	$pageID = $_POST['pageID'];
	
	$query = "SELECT DISTINCT `PageID`, `NotebookID`, DATE_FORMAT(`Date Created`, '%l:%i %p | %b %e, %Y') AS 'Date Created', DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y') AS 'Last Updated', `Title`, `Content` FROM `Notebooks Pages` WHERE `pageID` ='$pageID'";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
		$title = $row["Title"];
		$pageID = $row["PageID"];
		$notebookID = $row["notebookID"];
		$dateCreated = $row["Date Created"];
		$lastUpdated = $row["Last Updated"];
		$content = $row['Content'];
						
	}

	
	$response = ["title" => $title,
				"pageID" => $pageID,
				"notebookID" => $notebookID,
				"dateCreated" => $dateCreated,
				"content" => $content,
				"lastUpdated" => $lastUpdated];
	
	header('Content-Type: application/json'); 
	echo json_encode($response);
	
}

if($type == 'search')
{
	$searchTerm = addslashes($_POST['searchTerm']);
	
	///// NOTEBOOKS /////

$query = "SELECT DISTINCT * FROM `Notebooks` WHERE `userID` ='$userID' AND `Title` LIKE '%$searchTerm%' ORDER BY `Notebook Order` ASC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
		$title = $row["Title"];
		$notebookID = $row["NotebookID"];
		$color = $row["Color"];
		
		$notebooks[]= '
		<div class="noteBookContainer" notebookID="'.$notebookID.'">
			<div class="tabIcon" style="border-color:'.$color.'">
				<div class="tabBox" style="background:'.$color.'"></div>
			</div>
			<div class="title"><h3>'.$title.'</h3></div>
			<div class="notebookOrderBy"><i class="fa fa-bars" aria-hidden="true"></i></div>
		</div>
									';
	}

///// PAGES /////

$query = "SELECT DISTINCT `Notebooks Pages`.`PageID`,`Notebooks Pages`.`NotebookID`, `Notebooks Pages`.`Title`, `Notebooks Pages`.`Date Created`,`Page Order` FROM `Notebooks Pages` JOIN `Notebooks` ON `Notebooks`.`NotebookID` = `Notebooks Pages`.`NotebookID` WHERE `userID` ='$userID' AND (`Notebooks Pages`.`Title` LIKE '%$searchTerm%' OR `Notebooks Pages`.`Content` LIKE '%$searchTerm%') ORDER BY `Page Order` ASC";
	$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
	while ($row = mysqli_fetch_array($query_result)) {
		$title = addslashes($row["Title"]);
		$pageID = $row["PageID"];
		$notebookID = $row["NotebookID"];
		$pages[]= '
		<div class="pageContainer" pageID="'.$pageID.'" pageNotebookID="'.$notebookID.'">
			<div class="notebookOrderBy"><i class="fa fa-bars" aria-hidden="true"></i></div>
			<div class="title"><h3>'.$title.'</h3></div>
			
		</div>
									';
	}

$results = ["notebooks" => $notebooks,
		   "pages" => $pages];
	
	header('Content-Type: application/json'); 
	echo json_encode($results);

}

if($type == 'saveOrder')
{
	
	$notebookID_array = explode(",",$_POST['notebookID_array']);
	$pageID_array = explode(",",$_POST['pageID_array']);
	
	// NOTEBOOK //
		$count = 1;
        foreach ($notebookID_array as $id){
			$query2 = "UPDATE `Notebooks` SET `Notebook Order` = '$count' WHERE `NotebookID` = '$id'";
			$query2_result = mysqli_query($connection, $query2) or die ("Query to get data from Team task failed: ".mysql_error());
			$count ++;    
        }
		$count2 = 1;
		foreach ($pageID_array as $id2){
			$query3 = "UPDATE `Notebooks Pages` SET `Page Order` = '$count2' WHERE `PageID` = '$id2'";
			$query3_result = mysqli_query($connection, $query3) or die ("Query to get data from Team task failed: ".mysql_error());
			$count2 ++;    
        }
}











?>