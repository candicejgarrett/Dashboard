<?php 
include_once('../header.php');

require('process.php');

$postID = $_GET['ID'];

$getPost = "SELECT `Knowledge Center`.`PostID`, `userID`, `Category`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'),DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag`, `KC TagID` FROM `Knowledge Center` JOIN `Knowledge Center Categories` ON `Knowledge Center`.`KC CategoryID`=`Knowledge Center Categories`.`KC CategoryID` LEFT JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` WHERE `Knowledge Center`.`PostID` ='$postID'";
$getPost_result = mysqli_query($connection, $getPost) or die ("Query to get data from Team task failed: ".mysql_error());
while ($row = mysqli_fetch_array($getPost_result)) {
								$postDateCreated = $row["DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y')"];
								$postID= $row['PostID'];
								$postTag=$row['Tag'];
								$postTagID= $row['KC TagID'];
								$postTitle= $row['Post Title'];
								$postBody2 = $row['Post Description'];
								$postImage = $row['Post Image'];
								$postUserID = $row['userID'];
								$postCategory = $row['Category'];
								$postCategoryID = $row['KC CategoryID'];
								$postLastUpdated = $row["DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y')"];
								
								
									$getWriter = "SELECT * FROM `user` WHERE `userID` = '$postUserID'";
									$getWriter_result = mysqli_query($connection, $getWriter) or die ("Query to get data from Team task failed: ".mysql_error());
									while ($row = mysqli_fetch_array($getWriter_result)) {
										$writerName = $row['First Name'].' '.$row['Last Name'];
										$writerPP = $row['PP Link'];
									}
								
							
											
}


if ($userID == $postUserID || strpos($myRole, 'Admin') == true || $groupName == $postCategory) {
} 
else {
	header("location:../404/no-access.php"); 	
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
		   
	//save ajax call
		$(document).on('click','#savePost-btn', function() {
		
					var postTitle = $("#updateTitle").val();
				  	var postBody = CKEDITOR.instances.updateBody.getData();
				 	var postID = "<?php echo $postID?>";
					
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=updatePost&postTitle='+postTitle+'&postBody='+postBody+'&postID='+postID,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								console.log(response.message);
								
							}
					});
				setTimeout(function(){
				 window.location.replace("post.php?ID="+postID);
				}, 500);
				

	});
	//delete ajax call
		
		$(document).on('click','.trashicon', function() {
					var category = "<?php echo $postCategory;?>";
					var postID = "<?php echo $postID?>";
					
					if (confirm('Are you sure?')) {
						$.ajax({
				    		url: 'process.php',
				    		data: 'type=deletePost&postID='+postID,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								console.log(response.message);
								
							}
						});
					}
			
				setTimeout(function(){
				 window.location.replace("category.php?cat="+category);
				}, 500);
				

	});
	//changing tag
	$(".KCTags").on('dblclick', function(e) {
			var currentVal = $(this).attr("id");
			var postID = "<?php echo $postID?>";
			var postCategoryID = "<?php echo $postCategoryID?>";
			
			$(this).html('<select class="postTag editInput"><option>Select...</option><?php $getTags = "SELECT DISTINCT `Tag` FROM `Knowledge Center Tags` WHERE`KC CategoryID`='$postCategoryID' ORDER BY `Tag` ASC";$getTags_result = mysqli_query($connection, $getTags) or die ("Query to get data from Team task failed: ".mysql_error());while ($row = mysqli_fetch_array($getTags_result)) {echo "<option>".$row["Tag"]."</option>";}?></select>');
		
				$(this).find(".postTag").on('change', function() {
						
						if (confirm('Are you sure?')) {
							var selectedVal = $("option:selected", this).val();
							
								$.ajax({
								url: 'process.php',
								data: 'type=updateTag&postID='+postID+'&newTag='+selectedVal+'&tagID='+currentVal+'&postCategoryID='+postCategoryID,
								type: 'POST',
								dataType: 'json',
								success: function(response){
									console.log(response);
								}
								});
							$(this).parent().html(selectedVal);
							
				
						}
					
				});
		
		
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
    	 			
					<h3><a style="color:#333;" href="../knowledge-center/">Knowledge Center:</a> <strong><?php echo $KCcategory;?></strong> <?php echo '<a href="category.php?cat='.$postCategory.'">'.$postCategory.'</a>';?> > <em><?php echo $postTitle ?></em></h3>
					
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
									if ($userID == $postUserID || $myRole == "Super Admin" || $myRole == "Admin") {
										echo "<br><div class='trashicon'><i class='fa fa-trash' aria-hidden='true'></i></div>";
									}
								?>
       						
       					</div>
       				</div>
       				<div class="col-sm-10">
       				
       					<h3><input id="updateTitle" type="text" value="<?php echo $postTitle ?>"></input></h3>
       				
       				<span class="date"><?php echo $postDateCreated ?></span><br><div class="lastUpdated">Last Updated: <?php echo $postLastUpdated ?></div>
       				<hr>
       				<div class="KCTags" id="<?php echo $postTagID ?>"><?php echo $postTag ?></div>&nbsp;<br><br>
       				<textarea id="updateBody"><?php echo $postBody2 ?></textarea>
       				<script>CKEDITOR.replace('updateBody', {
							
						});
						CKEDITOR.config.filebrowserBrowseUrl = 'browser/browse.php';
						CKEDITOR.config.filebrowserUploadUrl = 'uploader/upload.php';
						CKEDITOR.config.basicEntities = false;
					</script>
       				<br><button class="save" id="savePost-btn"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
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