<?php 
include_once('../../header.php');
require('../../connect.php');
require('../process.php');

$postID = $_GET['ID'];

$getAllPosts = "SELECT `PostID` FROM `Knowledge Center` WHERE `PostID`='$postID'";
	$getAllPosts_result = mysqli_query($connection, $getAllPosts) or die(mysqli_error($connection));
	$postCount = mysqli_num_rows($getAllPosts_result);
	
	if ($postCount == 0) {
		header("location:/dashboard/404/"); 	
	}
else {}

$getPost = "SELECT `Knowledge Center`.`PostID`, `Knowledge Center`.`userID`, `Category`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'),DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `First Name`, `Last Name` FROM `Knowledge Center` JOIN `Knowledge Center Categories` ON `Knowledge Center`.`KC CategoryID`=`Knowledge Center Categories`.`KC CategoryID` LEFT JOIN `user` ON `Knowledge Center`.`Last Updated By`=`user`.`userID` WHERE `Knowledge Center`.`PostID` ='$postID'";
$getPost_result = mysqli_query($connection, $getPost) or die ("Query to get data from Team task failed: ".mysql_error());
while ($row = mysqli_fetch_array($getPost_result)) {
								$postDateCreated = $row["DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y')"];
								$postID= $row['PostID'];
								
								$postTitle= $row['Post Title'];
								$postBody = $row['Post Description'];
								$postImage = $row['Post Image'];
								$postUserID = $row['userID'];
								$postCategory = $row['Category'];
								$postCategoryID = $row['KC CategoryID'];
								$postLastUpdated = $row["DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y')"];
								$postLastUpdatedBy = $row['First Name']." ".$row['Last Name'];
								
									$getWriter = "SELECT * FROM `user` WHERE `userID` = '$postUserID'";
									$getWriter_result = mysqli_query($connection, $getWriter) or die ("Query to get data from Team task failed: ".mysql_error());
									while ($row = mysqli_fetch_array($getWriter_result)) {
										$writerName = $row['First Name'].' '.$row['Last Name'];
										$writerPP = $row['PP Link'];
									}
	
									$getTags = "SELECT * FROM `Knowledge Center Tags` WHERE `PostID`='$postID'";
								$getTags_result = mysqli_query($connection, $getTags) or die ("Query to get data from Team task failed: ".mysql_error());

								while ($row = mysqli_fetch_array($getTags_result)) {
										$postTags[]= '<div class="KCTags">'.$row['Tag'].'</div>';
								}
								
							
											
}

?>
   <html class="x-template-kc">
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php echo $stylesjs ?>
   <?php echo $scripts ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

		<script src="/dashboard/js/pages/knowledgecenter.js"></script>
   <script>
	   $(document).ready(function() {
		
		
		 CKEDITOR.replace('postBody');
		 CKEDITOR.config.filebrowserBrowseUrl = 'browser/browse.php';
		 CKEDITOR.config.filebrowserUploadUrl = 'uploader/upload.php';
		 CKEDITOR.config.basicEntities = false;
	
		   $("#printCopyLink").prepend('<center><div class="copyLink"><i class="fa fa-link" aria-hidden="true"></i> Share</div></center>');
		   $("#copyLinkInput").val(window.location.href);
		
	
});
</script>
    </head>

    <body>
    
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
   <?php include("../../templates/topNav.php") ?>
  </div><!-- /.container-fluid -->
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
     	<div class="row">
		 <div class="col-sm-12">
			<div class="whitebg">
    	 	<div class="header">
    	 			<button id="createNewPost-btn" class="pull-right createNew" style="margin-top:-25px;"><i class="fa fa-plus" aria-hidden="true"></i><div class="btnExpand">Create New Post</div></button>
					<div id="printCopyLink" class="pull-right" style="margin-top: -20px;"><input type="text" id="copyLinkInput"></div>
				<h3><a style="color:#333;" href="/dashboard/knowledge-center/">Knowledge Center:</a> <strong> <?php echo '<a href="../category/?cat='.$postCategory.'">'.$postCategory.'</a>';?></strong> > <em><?php echo $postTitle ?></em></h3>
					
			</div>
			
			
			<div class="row" id="createNewPost">
					
						<div class="col-sm-12">
							<h3>Create A New Post </h3>
							<p>This post will be added to the <strong><?php echo $postCategory;?></strong> Category.</p>
							<div id="#validate" class="validate"></div>
						</div>
       				
       		<div class="col-sm-6">
       			<div class="formLabels">Title:*</div>
      			<input type="text" id="postTitle" name="postTitle" placeholder="Post Title" class="validate">
      			<div id="printPostTags" style="display:inline-block">
				
				</div>
      			</div>
      			<div class="col-sm-6">
				<div class="formLabels">Tag(s):</div>
      			<input type="text" id="postTagsCreate" name="postTags" placeholder="Post tag(s), comma separated" categoryID="<?php echo $KCcategoryID;?>">
					
      			<div class="selectTags" style="loat: left !important;margin-bottom: 10px;">
					
				</div>		
      			</div>
      			<div class="col-sm-12">
      			<textarea id="postBody" name="postBody"></textarea>
      			
      			</div>
      			
      			<div class="col-sm-12"><br>
      				<button id="addNewPost-btn" style="margin-left:0px;" class="genericbtn" type="submit" name="new" categoryID="<?php echo $postCategoryID;?>">Add New Post</button><br><br>
      			</div>
       		
       		</div>
       		<div class="row">
					
			<div class="col-sm-12">
							
			</div>
       				
       		<div class="col-sm-12">
       			<div class="row blogPost">
       				<div class="col-sm-2">
       					<div class="creatorInfo"><h3>Written By:</h3>
       						<a href="/dashboard/users/profile/?userID=<?php echo $postUserID ?>">
       							<img src="<?php echo $writerPP ?>">
       							<h1><?php echo $writerName ?></h1>
       							</a>
       							<?php 
									if ($userID == $postUserID || $myRole == "Admin" || $myRole == "Editor") {
										echo "<br><a class='circlebtn primaryColorBG' href='edit.php?ID=".$postID."' style='margin-left: 0px;'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
									}
								?>
       						
       					</div>
       				</div>
       				<div class="col-sm-10">
       				
       					<h3><?php echo $postTitle ?></h3>
       				
       				<span class="date"><?php echo $postDateCreated ?></span><br><div class="lastUpdated">Last Updated By: <?php echo $postLastUpdatedBy." @ ".$postLastUpdated ?></div>
       				<?php foreach ($postTags as $tag) {
						echo $tag;
					} ?>
       				<hr>
       				<div class="postBody"><?php echo $postBody ?></div></div>
       				
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
   
    
  

    </body>
</html>