<?php 
include_once('../header.php');
require('../connect.php');
require('process.php');

$KCTag2 = $_GET["tag"];
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
	//search funtion
		    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});
		 //search function
		  $('#postSearch').keyup(function(){
				  var valThis = $(this).val();
					if (valThis == ""){
						$('.blogPost').fadeIn();
					}
				 	
					$('.blogPost').each(function(){
					
						$(this).find('.headingSearch:contains("'+valThis+'")').parent().parent().parent().fadeIn();
						$('.blogPost').find('.headingSearch:not(:contains("'+valThis+'"))').parent().parent().parent().fadeOut();
						
					if (!$('.blogPost').length){
						 
					}	
						
				   });
			});
		   
		 //getting tags while typing
		   $('#postTags').keyup(function(){
			  
				  var valThis = $(this).val();
			    var categoryID = "<?php echo $KCcategoryID;?>";
					if (valThis == ""){
						$('.showTags').fadeOut();
					}
			   		else {
						$('.showTags').fadeIn();
						var dataString = {'type':"getTags",typedTag:valThis,categoryID:categoryID};	

						$.ajax({
						type: "POST",
						url: "process.php",
						data: dataString,
						cache: false,
						success: function(response){
						$(".showTags").html(response.listTags);	

						}
						});
					} 
				 	
					
			});
		   
		   $(document).on('click','.showTags .KCTags', function() {
			  var tagVal = $(this).text();
			  $(this).parent().prev().val(tagVal);
			   $(this).hide();
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
    	 			<?php if (isset($KCcategory)) {
						echo '<button id="createNewPost-btn" class="pull-right createNew" style="margin-top:-25px;"><i class="fa fa-plus" aria-hidden="true"></i></button>';
					}?>
    	 			
    	 			
					<h3><a style="color:#333;" href="../knowledge-center/">Knowledge Center:</a> <strong><a href="../knowledge-center/category.php?cat=<?php echo $KCcategory;?>"><?php echo $KCcategory;?></a></strong>  <em><?php if (isset($KCTag2)) {echo "> ".$KCTag2;}?></em></h3>
					
			</div>
			<div class="row">
			<div class="col-sm-6">
			<h4>Search:</h4>
						<input type="text" placeholder="Search by post title" style="width:100%;" id="postSearch">	
						
			</div>
			<div class="col-sm-6">
					<h4>Tags</h4>
						<?php
							if (isset($KCcategoryID)) {
								$getTags = "SELECT DISTINCT `Tag` FROM `Knowledge Center Tags` WHERE `KC CategoryID` = '$KCcategoryID'";
								$getTags_result = mysqli_query($connection, $getTags) or die ("Query to get data from Team task failed: ".mysql_error());

								while ($row = mysqli_fetch_array($getTags_result)) {
									$KCTag = $row['Tag'];
								echo '<div class="KCTags"><a href="category.php?cat='.$KCcategory.'&tag='.$KCTag.'">'.$KCTag.'</a></div>';
								}
							}
							else {
								$getTags = "SELECT DISTINCT `Tag`,`Category` FROM `Knowledge Center Tags` JOIN `Knowledge Center Categories` ON `Knowledge Center Tags`.`KC CategoryID`=`Knowledge Center Categories`.`KC CategoryID`";
								$getTags_result = mysqli_query($connection, $getTags) or die ("Query to get data from Team task failed: ".mysql_error());

								while ($row = mysqli_fetch_array($getTags_result)) {
									$KCTag = $row['Tag'];
									$KCTagCategory = $row['Category'];
								echo '<div class="KCTags"><a href="category.php?cat='.$KCTagCategory.'&tag='.$KCTag.'">'.$KCTag.'</a></div>';
								}
							}
							
							echo '<div class="KCTags clear"><a href="category.php?cat='.$KCcategory.'" style="color:#ffffff !important">Clear Tags</a></div>'
						?>
						
				
					
				
				<br><br>
			</div>
			
			</div>
			
			<div class="row" id="createNewPost">
					
						<div class="col-sm-12">
							<h3>Create A New Post </h3>
							<p>This post will be added to the <strong><?php echo $KCcategory;?></strong> Category.</p>
							<div id="#validate" class="validate"></div>
						</div>
       				
       		<div class="col-sm-6">
       		
      			<input type="text" id="postTitle" name="postTitle" placeholder="Post Title">
      			
      			</div>
      			<div class="col-sm-6">
      			<input type="text" id="postTags" name="postTags" placeholder="Post tag">
      			<div class="showTags"></div>		
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
       		
       			<?php
				$tag = $_GET['tag'];
				
				if (isset($tag)){
					$getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag` FROM `Knowledge Center` JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` WHERE `Knowledge Center`.`KC CategoryID` ='$KCcategoryID' AND `Tag`='$tag' ORDER BY `Date Created` DESC";
				}
				else if (isset($KCcategoryID)){
					$getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag` FROM `Knowledge Center` JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` WHERE `Knowledge Center`.`KC CategoryID` ='$KCcategoryID' ORDER BY `Date Created` DESC";
				}
				else {
					$getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag` FROM `Knowledge Center` JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` ORDER BY `Date Created` DESC";
				}
				
							
							$getPosts_result = mysqli_query($connection, $getPosts) or die ("Query to get data from Team task failed: ".mysql_error());

							while ($row = mysqli_fetch_array($getPosts_result)) {
								$postDateCreated = $row["DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y')"];
								$postID= $row['PostID'];
								$postTitle= $row['Post Title'];
								$postBody = $row['Post Description'];
								$postImage = $row['Post Image'];
								$postUserID = $row['userID'];
								
								if (isset($row['Tag'])){
									$postTag = '<div class="KCTags">'.$row['Tag'].'</div>';
								}
									$getWriter = "SELECT * FROM `user` WHERE `userID` = '$postUserID'";
									$getWriter_result = mysqli_query($connection, $getWriter) or die ("Query to get data from Team task failed: ".mysql_error());
									while ($row = mysqli_fetch_array($getWriter_result)) {
										$writerName = $row['First Name'].' '.$row['Last Name'];
										$writerPP = $row['PP Link'];
									}
								
							
								echo '<div class="row blogPost"><div class="col-sm-2"><div class="creatorInfo"><h3>Written By:</h3><a href="../users/profile.php?userID='.$postUserID.'"><img src="'.$writerPP.'"><h1>'.$writerName.'</h1></a></div></div><div class="col-sm-10"><a href="post.php?ID='.$postID.'"><h3 class="headingSearch">'.$postTitle.'</h3></a><span class="date">'.$postDateCreated.'</span><br>'.$postTag.'</div></div>';
							
								
								
								
							
							}
       			
					
      					
      				
      			
				?>
      			
      			</div>
      			
       		<dvi class="col-sm-12">
       		
       		
       		<br>
       		<!--<table width="100%" border="1" cellspacing="0" cellpadding="10" class="myPagination">
			<tbody>
				<tr>
				  <td align="center"><div class="pageCount"></div><br><div class="pagerP"><b>Prev</b></div> <div class="pagerF"><b>Next</b></div></td>
				  
				</tr>
			</tbody>
			</table>-->
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