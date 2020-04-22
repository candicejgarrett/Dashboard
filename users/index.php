<?php 
include_once('../header.php');

?>
   <html class="x-template-users">
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
<script>
	$(document).ready(function(){
		
		function getDirectory() {
			var dataString = {'type':"getDirectory"};

				$.ajax({
					type: "POST",
					url: "process.php",
					data: dataString,
					dataType: 'json',
					cache: false,
					success: function(results){
						$("#printMembers").html(results.printMembers).fadeIn();
						$("#loadingPageSpinner").hide();
						$("#loadingPage").fadeIn();
						
					}
				});
		}
		getDirectory();
		
		$('#sortRole').on('change', function() {
		
		var selectedVal = $("#sortRole option:selected" ).val().replace(/\s/g, '');
		
		if (selectedVal === "All") {
			$('.userCard').parent().fadeIn();
		}
		else {
				$('.userCard').not('.role_' + selectedVal).parent().fadeOut();
				$('.role_' + selectedVal).parent().fadeIn();
					
			}
		
		}
		);
	
	$('#sortGroup').on('change', function() {
		 var selectedVal = $("#sortGroup option:selected" ).val().replace(/\s/g, '');
		
		
		if (selectedVal === "All") {
			$('.userCard').parent().fadeIn();
			
		}
		else {$('.userCard').not('.group_' + selectedVal).parent().fadeOut();
		 $('.group_' + selectedVal).parent().fadeIn();
			 }
		 
		});
		 
		$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});
		 //search function
			 $('#memberSearch').keyup(function(){
				   var valThis = $(this).val();
					if (valThis == ""){
						$('.userCard').fadeIn();
					}
				 	
					$('.userCard').each(function(){
					
						$('.userCard h3:contains("'+valThis+'")').parent().parent().parent().parent().fadeIn();
						$('.userCard h3:not(:contains("'+valThis+'"))').parent().parent().parent().parent().fadeOut();
					
						
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
			 <div class="col-sm-12" id="moveOver">
				<div class="whitebg">
					<div class="header">
						
							<h3>Directory</h3>
							<p></p>
					</div>
					<div class="sorter row">
						<div class="col-sm-4">
						<p>Search:</p>
						<input type="text" class="pull-right" placeholder="Search by name" style="width:100%;" id="memberSearch">
						</div>
						<div class="col-sm-4">
						<p>Role:
						<select name="RoleNameList" id="sortRole" style="margin-top: 10px !important;"><option value="All">Show All</option>
						<?php 
						$query = "SELECT DISTINCT * FROM `Roles` ORDER BY `Role` ASC";
						$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
						while ($row = mysqli_fetch_array($query_result)) {
							echo "<option value='".addslashes($row["Role"])."'>".addslashes($row["Role"])."</option>";
						}?>
						</select>
						
							
						</p>
						</div>
						<div class="col-sm-4">
						<p>Team Name:
						<select name="GroupNameList" id="sortGroup" style="margin-top: 10px !important;"><option value="All">Show All</option>
						<?php 
						$query = "SELECT DISTINCT * FROM `Groups` ORDER BY `Group Name` ASC";
						$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
						while ($row = mysqli_fetch_array($query_result)) {
							echo "<option value='".addslashes($row["Group Name"])."'>".addslashes($row["Group Name"])." Team</option>";
						}?>
						</select>
						
					</p>
						</div>
						
						
					</div>
					<div id="loadingPageSpinner"><center><img src="/dashboard/images/grey-spinner.gif"></center></div>
					<div id="loadingPage">
						<div class="row" style="padding: 5px;" id="printMembers">
						
							
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