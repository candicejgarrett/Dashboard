<?php
include_once( '../../header.php' );

require( '../process.php' );

$postID = $_GET[ 'ID' ];

$getPost = "SELECT `Knowledge Center`.`PostID`, `userID`, `Category`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'),DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image` FROM `Knowledge Center` JOIN `Knowledge Center Categories` ON `Knowledge Center`.`KC CategoryID`=`Knowledge Center Categories`.`KC CategoryID` WHERE `Knowledge Center`.`PostID` ='$postID'";
$getPost_result = mysqli_query( $connection, $getPost )or die( "Query to get data from Team task failed: " . mysql_error() );
while ( $row = mysqli_fetch_array( $getPost_result ) ) {
  $postDateCreated = $row[ "DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y')" ];
  $postID = $row[ 'PostID' ];
  $postTitle = $row[ 'Post Title' ];
  $postBody2 = $row[ 'Post Description' ];
  $postImage = $row[ 'Post Image' ];
  $postUserID = $row[ 'userID' ];
  $postCategory = $row[ 'Category' ];
  $postCategoryID = $row[ 'KC CategoryID' ];
  $postLastUpdated = $row[ "DATE_FORMAT(`Last Updated`, '%l:%i %p %b %e, %Y')" ];


  $getWriter = "SELECT * FROM `user` WHERE `userID` = '$postUserID'";
  $getWriter_result = mysqli_query( $connection, $getWriter )or die( "Query to get data from Team task failed: " . mysql_error() );
  while ( $row = mysqli_fetch_array( $getWriter_result ) ) {
    $writerName = $row[ 'First Name' ] . ' ' . $row[ 'Last Name' ];
    $writerPP = $row[ 'PP Link' ];
  }

  $getTags = "SELECT * FROM `Knowledge Center Tags` WHERE `PostID`='$postID'";
  $getTags_result = mysqli_query( $connection, $getTags )or die( "Query to get data from Team task failed: " . mysql_error() );

  while ( $row = mysqli_fetch_array( $getTags_result ) ) {
    $postTags[] = '<div class="KCTags addedTagFixed">' . $row[ 'Tag' ] . ' <div class="deleteTagIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="addTagIcon"><i class="fa fa-plus" aria-hidden="true"></i></div></div>';
  }


}


if ( $userID == $postUserID || $myRole == 'Admin' || $myRole == 'Editor' ) {} else {
  header( "location:../../404/no-access.php" );
}
?>
<html class="x-template-kc">
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

<script src="/dashboard/js/pages/knowledgecenter.js"></script> 
<script>
	   $(document).ready(function() {
		
		
	
});
</script>
</head>

<body>
<nav class="navbar navbar-default" style="background:#ffffff; border:none;">
  <div class="container-fluid">
    <?php include("../../templates/topNav.php") ?>
  </div>
  <!-- /.container-fluid --> 
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
              <h3><a style="color:#333;" href="/dashboard/knowledge-center/">Knowledge Center:</a> <strong><?php echo $KCcategory;?></strong> <?php echo '<a href="../category/?cat='.$postCategory.'">'.$postCategory.'</a>';?> > <em><?php echo $postTitle ?></em></h3>
            </div>
            <div class="row">
              <div class="col-sm-12"> </div>
              <div class="col-sm-12">
                <div class="row blogPost">
                  <div class="col-sm-2">
                    <div class="creatorInfo">
                      <h3>Written By:</h3>
                      <a href="/dashboard/users/profile/?userID=<?php echo $postUserID ?>"> <img src="<?php echo $writerPP ?>">
                      <h1><?php echo $writerName ?></h1>
                      </a>
                      <?php
                      if ( $userID == $postUserID || $myRole == "Admin" || $myRole == "Editor" ) {
                        echo "<br><div class='remove deleteConfirm' postID='$postID' categoryID='$postCategoryID'><i class='fa fa-trash' aria-hidden='true'></i></div>";
                      }
                      ?>
                    </div>
                  </div>
                  <div class="col-sm-10">
                    <h3>
                      <div class="formLabels">Title:*</div>
                      <input id="updateTitle" class="validate" type="text" value="<?php echo $postTitle ?>">
                      </input>
                    </h3>
                    <span class="date"><?php echo $postDateCreated ?></span><br>
                    <div class="lastUpdated">Last Updated: <?php echo $postLastUpdated ?></div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="editTags">
                          <div id="printPostTags" style="display:inline-block">
                            <?php
                            foreach ( $postTags as $tag ) {
                              echo $tag;
                            }
                            ?>
                            <button id="editPostTags-btn" class="createNew"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </div>
                          <div id="addTagsContainer">
                            <input type="text" id="postTags" name="postTags" placeholder="Post tag(s), comma separated">
                            <div class="selectTags"> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <textarea id="updateBody"><?php echo $postBody2 ?></textarea>
                    <script>CKEDITOR.replace('updateBody', {
							
						});
						CKEDITOR.config.filebrowserBrowseUrl = '../browser/browse.php';
						CKEDITOR.config.filebrowserUploadUrl = '../uploader/upload.php';
						CKEDITOR.config.basicEntities = false;
					</script> 
                    <br>
                    <button class="save" id="savePost-btn" postID="<?php echo $postID ?>" categoryID="<?php echo $postCategoryID ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
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