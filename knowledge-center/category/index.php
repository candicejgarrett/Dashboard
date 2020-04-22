<?php
require( '../../header.php' );
require( '../../connect.php' );
require( '../process.php' );

$KCTag2 = $_GET[ "tag" ];


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

		 CKEDITOR.replace('postBody');
		 CKEDITOR.config.filebrowserBrowseUrl = 'browser/browse.php';
		 CKEDITOR.config.filebrowserUploadUrl = 'uploader/upload.php';
		 CKEDITOR.config.basicEntities = false;

		   $("#printCopyLink").prepend('<center><div class="copyLink"><i class="fa fa-link" aria-hidden="true"></i> Share</div></center>');
		   $("#copyLinkInput").val(window.location.href);
		   
});
</script>
<style>
.selectTags:empty {
    margin-right: 0px;
    padding-right: 0px;
}
.selectTags {
    float: left !important;
    margin-bottom: 10px;
    border-right: 2px solid #ccc;
    margin-right: 10px;
}
</style>
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
              <?php
              if ( isset( $KCcategory ) ) {
                echo '<button id="createNewPost-btn" class="pull-right createNew" style="margin-top:-25px;"><i class="fa fa-plus" aria-hidden="true"></i></button>';
              }
              ?>
              <div id="printCopyLink" class="pull-right" style="margin-top: -20px;">
                <input type="text" id="copyLinkInput">
              </div>
              <h3><a style="color:#333;" href="/dashboard/knowledge-center/">Knowledge Center:</a> <strong><a href="/dashboard/knowledge-center/category/?cat=<?php echo $KCcategory;?>"><?php echo $KCcategory;?></a></strong> <em>
                <?php if (isset($KCTag2)) {echo "> ".$KCTag2;}?>
                </em></h3>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <h4>Search:</h4>
                <input type="text" placeholder="Search by post title" style="width:100%;" id="postSearch" categoryID="<?php echo $KCcategoryID;?>">
              </div>
              <div class="col-sm-6">
                <h4>Tags</h4>
                <?php
                if ( isset( $KCcategoryID ) ) {
                  $getTags = "SELECT DISTINCT `Tag` FROM `Knowledge Center Tags` WHERE `KC CategoryID` = '$KCcategoryID'";
                  $getTags_result = mysqli_query( $connection, $getTags )or die( "Query to get data from Team task failed: " . mysql_error() );

                  while ( $row = mysqli_fetch_array( $getTags_result ) ) {
                    $KCTag = $row[ 'Tag' ];
                    echo '<div class="KCTags"><a href="?cat=' . $KCcategory . '&tag=' . $KCTag . '">' . $KCTag . '</a></div>';
                  }
                } else {
                  $getTags = "SELECT DISTINCT `Tag`,`Category` FROM `Knowledge Center Tags` JOIN `Knowledge Center Categories` ON `Knowledge Center Tags`.`KC CategoryID`=`Knowledge Center Categories`.`KC CategoryID`";
                  $getTags_result = mysqli_query( $connection, $getTags )or die( "Query to get data from Team task failed: " . mysql_error() );

                  while ( $row = mysqli_fetch_array( $getTags_result ) ) {
                    $KCTag = $row[ 'Tag' ];
                    $KCTagCategory = $row[ 'Category' ];
                    echo '<div class="KCTags"><a href="/dashboard/knowledge-center/category/?cat=' . $KCTagCategory . '&tag=' . $KCTag . '">' . $KCTag . '</a></div>';
                  }
                }

                echo '<div class="KCTags clear"><a href="?cat=' . $KCcategory . '" style="color:#ffffff !important">Clear Tags</a></div>'
                ?>
                <br>
                <br>
              </div>
            </div>
            <div class="row" id="createNewPost">
              <div class="col-sm-12">
                <h3>Create A New Post </h3>
                <p>This post will be added to the <strong><?php echo $KCcategory;?></strong> Category.</p>
                <div id="#validate" class="validate"></div>
              </div>
              <div class="col-sm-6">
                <div class="formLabels">Title:*</div>
                <input type="text" id="postTitle" name="postTitle" placeholder="Post Title" class="validate">
              </div>
              <div class="col-sm-6">
                <div class="formLabels">Tag(s):</div>
                <input type="text" id="postTagsCreate" name="postTags" placeholder="Post tag(s), comma separated" categoryID="<?php echo $KCcategoryID;?>">
                <div class="selectTags"> </div>
                <div id="printPostTags" style="display:inline-block"> </div>
              </div>
              <div class="col-sm-12">
                <textarea id="postBody" name="postBody"></textarea>
              </div>
              <div class="col-sm-12"><br>
                <button id="addNewPost-btn" style="margin-left:0px;" class="genericbtn" type="submit" name="new" categoryID="<?php echo $KCcategoryID;?>">Add New Post</button>
                <br>
                <br>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12"> </div>
              <div class="col-sm-12">
                <?php
                $tag = $_GET[ 'tag' ];

                if ( isset( $tag ) ) {
                  $getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag` FROM `Knowledge Center` JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` WHERE `Knowledge Center`.`KC CategoryID` ='$KCcategoryID' AND `Tag`='$tag' ORDER BY `Date Created` DESC";
                } else if ( isset( $KCcategoryID ) ) {
                  //$getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag` FROM `Knowledge Center` JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` WHERE `Knowledge Center`.`KC CategoryID` ='$KCcategoryID' ORDER BY `Date Created` DESC";
                  $getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image` FROM `Knowledge Center` WHERE `Knowledge Center`.`KC CategoryID` ='$KCcategoryID' ORDER BY `Date Created` DESC";
                } else {
                  $getPosts = "SELECT `Knowledge Center`.`PostID`, `userID`, DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y'), `Post Title`, `Post Description`, `Knowledge Center`.`KC CategoryID`, `Post Image`, `Tag` FROM `Knowledge Center` JOIN `Knowledge Center Tags` ON `Knowledge Center`.`PostID`=`Knowledge Center Tags`.`PostID` ORDER BY `Date Created` DESC";
                }


                $getPosts_result = mysqli_query( $connection, $getPosts )or die( "Query to get data from Team task failed: " . mysql_error() );

                while ( $row = mysqli_fetch_array( $getPosts_result ) ) {
                  $postDateCreated = $row[ "DATE_FORMAT(`Date Created`, '%l:%i %p %b %e, %Y')" ];
                  $postID = $row[ 'PostID' ];
                  $postTitle = $row[ 'Post Title' ];
                  $postBody = $row[ 'Post Description' ];
                  $postImage = $row[ 'Post Image' ];
                  $postUserID = $row[ 'userID' ];

                  $getWriter = "SELECT * FROM `user` WHERE `userID` = '$postUserID'";
                  $getWriter_result = mysqli_query( $connection, $getWriter )or die( "Query to get data from Team task failed: " . mysql_error() );
                  while ( $row = mysqli_fetch_array( $getWriter_result ) ) {
                    $writerName = $row[ 'First Name' ] . ' ' . $row[ 'Last Name' ];
                    $writerPP = $row[ 'PP Link' ];
                  }

                  echo '<div class="row blogPost"><div class="col-sm-2"><div class="creatorInfo"><h3>Written By:</h3><a href="/dashboard/users/profile/?userID=' . $postUserID . '"><img src="' . $writerPP . '"><h1>' . $writerName . '</h1></a></div></div><div class="col-sm-10"><a href="../post/?ID=' . $postID . '"><h3 class="headingSearch">' . $postTitle . '</h3></a><span class="date">' . $postDateCreated . '</span><br>';
                  //getting tags for this post
                  $getTags = "SELECT `KC TagID`, `KC CategoryID`, `Tag`, `PostID` FROM `Knowledge Center Tags` WHERE `PostID`='$postID'";
                  $getTags_result = mysqli_query( $connection, $getTags )or die( "Query to get data from Team task failed: " . mysql_error() );

                  while ( $row = mysqli_fetch_array( $getTags_result ) ) {
                    echo '<div class="KCTags">' . $row[ 'Tag' ] . '</div>';
                  }

                  echo '</div></div>';


                }


                ?>
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