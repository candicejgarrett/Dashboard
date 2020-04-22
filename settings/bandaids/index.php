<?php
include_once( '../../header.php' );
require( '../../connect.php' );

if ( $myRole != 'Admin' ) {
  header( "location:/dashboard/404/no-access.php" );
}

$query = "SELECT DISTINCT * FROM `Code Bandaids` WHERE `Type` = 'style'";
$query_result = mysqli_query( $connection, $query )or die( "Query to get data from Team task failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $query_result ) ) {
  if ( isset( $row[ 'Code' ] ) ) {
    $styleCode = $row[ 'Code' ];
  } else {
    $styleCode = '';
  }

}
$query2 = "SELECT DISTINCT * FROM `Code Bandaids` WHERE `Type` = 'script'";
$query2_result = mysqli_query( $connection, $query2 )or die( "Query to get data from Team task failed: " . mysql_error() );

while ( $row = mysqli_fetch_array( $query2_result ) ) {
  if ( isset( $row[ 'Code' ] ) ) {
    $scriptCode = $row[ 'Code' ];
  } else {
    $scriptCode = '';
  }

}
?>
<html class="x-template-settings">
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
<script>
	$(document).ready(function() {
		
		$(document).on('click','#submitStyle', function() {
			
			$.alertable.confirm('Are you sure? The current bandaid style will be overwritten!').then(function() {	
					
					var code = $("#styleCode").val();
					
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=updateStyleBandaid&code='+code,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(response){
								alert("Successfully added.");
							},
							error: function(response){
								alert("Failed!");
							}
					});
				
	
			
		
		})
	});
		
		$(document).on('click','#submitScript', function() {
			
			$.alertable.confirm('Are you sure? The current bandaid script will be overwritten!').then(function() {	
					
					var code = $("#scriptCode").val();
					
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=updateScriptBandaid&code='+code,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(response){
								alert("Successfully added.");
							},
							error: function(response){
								alert("Failed!");
							}
					});
				
	
			
		
		})
	});
		
	
		
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
              <h3><strong><a href="/dashboard/settings/">Settings:</a></strong> Code Bandaids</h3>
            </div>
            <div class="row">
              <div class="col-sm-6" id="styleCollumn">
                <h4 style="font-weight:bold;">Style:</h4>
                <input type="hidden" id="typeStyle" value="style">
                </input>
                <div class="formLabels">Code (Include style tags):</div>
                <textarea id="styleCode" placeholder=""><?php echo $styleCode?>
				</textarea>
                <br>
                <br>
                <button class="genericbtn" id="submitStyle">Submit</button>
                <br>
              </div>
              <div class="col-sm-6" id="scriptCollumn">
                <h4 style="font-weight:bold;">Script:</h4>
                <input type="hidden" id="typeScript" value="script">
                </input>
                <div class="formLabels">Code (Include script tags):</div>
                <textarea id="scriptCode" placeholder="" value=""><?php echo $scriptCode?></textarea>
                <br>
                <br>
                <button class="genericbtn" id="submitScript">Submit</button>
                <br>
              </div>
              <br>
              <br>
              &nbsp; </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<?php echo $scripts?>
</body>
</html>