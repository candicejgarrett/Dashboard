<?php 
include_once('../header.php');
require('../connect.php');
require('process.php');

$postID = $_GET['ID'];

$getAllPosts = "SELECT `PostID` FROM `Knowledge Center` WHERE `PostID`='$postID'";
	$getAllPosts_result = mysqli_query($connection, $getAllPosts) or die(mysqli_error($connection));
	$postCount = mysqli_num_rows($getAllPosts_result);
	
	if ($postCount == 0) {
		header("location:../404/404.php"); 	
	}
else {}

$getPost = "SELECT `Knowledge Center`.`PostID`, `Knowledge Center`.`userID`, `Category`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'),DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag`, `First Name`, `Last Name` FROM `Knowledge Center` JOIN `Knowledge Center Categories` ON `Knowledge Center`.`KC CategoryID`=`Knowledge Center Categories`.`KC CategoryID` LEFT JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID`LEFT JOIN `user` ON `Knowledge Center`.`Last Updated By`=`user`.`userID` WHERE `Knowledge Center`.`PostID` ='$postID'";
$getPost_result = mysqli_query($connection, $getPost) or die ("Query to get data from Team task failed: ".mysql_error());
while ($row = mysqli_fetch_array($getPost_result)) {
								$postDateCreated = $row["DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y')"];
								$postID= $row['PostID'];
	
								if ($row["Tag"] === NULL) {
									$postTag="";
								}
								else {
									$postTag= '<div class="KCTags">'.$row['Tag'].'</div>';
								}
								
								$postTitle= $row['Post Title'];
								$postBody = $row['Post Description'];
								$postImage = $row['Post Image'];
								$postUserID = $row['userID'];
								$postCategory = $row['Category'];
								$postLastUpdated = $row["DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y')"];
								$postLastUpdatedBy = $row['First Name']." ".$row['Last Name'];
								
									$getWriter = "SELECT * FROM `user` WHERE `userID` = '$postUserID'";
									$getWriter_result = mysqli_query($connection, $getWriter) or die ("Query to get data from Team task failed: ".mysql_error());
									while ($row = mysqli_fetch_array($getWriter_result)) {
										$writerName = $row['First Name'].' '.$row['Last Name'];
										$writerPP = $row['PP Link'];
									}
								
							
											
}

?>
   <html>
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
<script src="../js/ckeditor/ckeditor.js"></script>
   <script>
	   $(document).ready(function() {
		$("#createNewPost").hide();
		$('#createNewPost-btn').click(function(){
			$("#createNewPost").slideToggle();
		});
		
		 CKEDITOR.replace('postBody');
		 CKEDITOR.config.filebrowserBrowseUrl = 'browser/browse.php';
		 CKEDITOR.config.filebrowserUploadUrl = 'uploader/upload.php';
		 CKEDITOR.config.basicEntities = false;
	//create new ajax call
		$(document).on('click','#addNewPost-btn', function() {
		
					var postTitle = $("#postTitle").val();
				  	var postTags = $("#postTags").val();
					var postBody = CKEDITOR.instances.postBody.getData();
				 	var postCategoryID = "<?php echo $KCcategoryID?>";
			
					if ($('#postTitle').val() === '') {
						$('.validate').html("<p>The post title is required.</p>");
						$('#postTitle').addClass("required");
						return false;
					}
					if ($('#postTags').val() === '') {
						$('.validate').html("<p>The post tag is required.</p>");
						$('#postTags').addClass("required");
						return false;
					}
			
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=newPost&postTitle='+postTitle+'&postTags='+postTags+'&postBody='+postBody+'&postCategoryID='+postCategoryID,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								alert(response.message);
								
							}
					});
			
				setTimeout(function(){
				 location.reload();
				}, 300);
				

	});
	
});
</script>
    </head>

    <body>
    
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
   <?php include("../templates/topNav.php") ?>
  </div><!-- /.container-fluid -->
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
    	 	<div class="header">
    	 			<button id="createNewPost-btn" class="pull-right createNew" style="margin-top:-25px;"><i class="fa fa-plus" aria-hidden="true"></i><div class="btnExpand">Create New Post</div></button>
					<h3><a style="color:#333;" href="../knowledge-center/">Knowledge Center:</a> <strong> <?php echo '<a href="category.php?cat='.$postCategory.'">'.$postCategory.'</a>';?></strong> > <em><?php echo $postTitle ?></em></h3>
					
			</div>
			
			
			<div class="row" id="createNewPost">
					
						<div class="col-sm-12">
							<h3>Create A New Post </h3>
							<p>This post will be added to the <strong><?php echo $postCategory;?></strong> Category.</p>
							<div id="#validate" class="validate"></div>
						</div>
       				
       		<div class="col-sm-6">
       		
      			<input type="text" id="postTitle" name="postTitle" placeholder="Post Title">
      			
      			</div>
      			<div class="col-sm-6">
      			<input type="text" id="postTags" name="postTags" placeholder="Post tags, separated by commas">
      			
      			</div>
      			<div class="col-sm-12">
      			<textarea id="postBody" name="postBody"></textarea>
      			
      			</div>
      			
      			<div class="col-sm-12"><br>
      				<button id="addNewPost-btn" style="margin-left:0px;" class="genericbtn" type="submit" name="new">Add New Post</button><br><br>
      			</div>
       		
       		</div>
       		<div class="row">
					
			<div class="col-sm-12">
							
			</div>
       				
       		<div class="col-sm-12">
       			<div class="row blogPost">
       				<div class="col-sm-2">
       					<div class="creatorInfo"><h3>Written By:</h3>
       						<a href="../users/profile.php?userID=<?php echo $postUserID ?>">
       							<img src="<?php echo $writerPP ?>">
       							<h1><?php echo $writerName ?></h1>
       							</a>
       							<?php 
									if ($userID == $postUserID || $myRole == "Super Admin" || $myRole == "Admin" || $groupName == $postCategory) {
										echo "<br><a class='editicon' href='edit.php?ID=".$postID."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
									}
								?>
       						
       					</div>
       				</div>
       				<div class="col-sm-10">
       				
       					<h3><?php echo $postTitle ?></h3>
       				
       				<span class="date"><?php echo $postDateCreated ?></span><br><div class="lastUpdated">Last Updated By: <?php echo $postLastUpdatedBy." @ ".$postLastUpdated ?></div>
       				<?php echo $postTag ?>
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
   
    
    <script type="text/javascript" src="../js/main.js"></script>

    </body>
</html>