<?php  //Start the Session
session_start();
 require('connect.php');

?>
   <html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dashboard/css/todo.css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/dashboard/css/spectrum.css">
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<script src="/dashboard/js/spectrum.js"></script>
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script src="/dashboard/js/highlight.js"></script>
<link rel="apple-touch-icon" sizes="57x57" href="/dashboard/images/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/dashboard/images/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/dashboard/images/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/dashboard/images/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/dashboard/images/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/dashboard/images/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/dashboard/images/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/dashboard/images/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/images/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/dashboard/images/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/dashboard/images/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/images/favicon/favicon-16x16.png">
<link rel="shortcut icon" href="/dashboard/images/favicon/favicon.ico" >
<link rel="manifest" href="/dashboard/images/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/dashboard/images/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
$(document).ready(function(){	
	$(document).on('click','#login', function() {	
		
		var type = "newUser";	
		var firstName = $('#firstname').val();
		var lastName = $('#lastname').val();
		var email = $('#email').val();
		var title = $('#title').val();
		var groupMembershipID = $('#groupMembership').find(":selected").val();
		var username = $('#username').val();
		var password = $('#password').val();
		var confirmPassword = $('#confirmPassword').val();
		
		var dataString = {'type':type,'firstName':firstName,'lastName':lastName,'email':email,'title':title,'groupMembershipID':groupMembershipID,'username':username,'password':password,'confirmPassword':confirmPassword};	
		
				$.ajax({
				type: "POST",
				url: "/dashboard/users/new-user.php",
				data: dataString,
				cache: false,
				success: function(result){
					
					$("#message").html(result.message);
					
					if (result.approved === "Yes") {
						
					}
					else {
						
					}
					
				}
					
				});
		
		
		});
	
	$(document).on('keyup','#firstname', function() {	
		var input = $.trim($(this).val());
		$("#username").val(input);
	});
	$(document).on('focusout','#lastname', function() {	
		var input = $(this).val().substr(0, 1);
		$("#username").val($("#username").val() + input);
	});
	$(document).on('focusin','#lastname', function() {	
		$("#username").val($("#firstname").val());
	});
});	
</script>
    <script>
		 $("#pending").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
	</script>
    <style>
		body {    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);}
	</style>
      </head>

    <body>
    

<div class="container">
<div class="col-lg-12 text-center"><center>
	<div class="whitebg" id="pending" style="margin-top:20%;width: 400px;
">
    	 			<div class="header">
					<center><h1>Dashboard<br><span style="font-size:24px;font-style: italic">Request Access</span></h1></center>
					</div>
 	 		 
 	 		 		<center>
 	 		 		<p class="message" id="message"></p>
 	 		 			<input type="text" id="firstname" placeholder="First Name" name="firstname">
 	 		 			<input type="text" id="lastname" placeholder="Last Name" name="lastname">
 	 		 			<input type="text" id="email" placeholder="Email" name="email">
 	 		 			<input type="text" id="title" placeholder="Title" name="title">
 	 		 			<select id="groupMembership">
 	 		 			<?php 
						$query = "SELECT DISTINCT * FROM `Groups` ORDER BY `Group Name` ASC";
						$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team task failed: ".mysql_error());
						while ($row = mysqli_fetch_array($query_result)) {
							echo "<option value='".$row["GroupID"]."'>".$row["Group Name"]." Team</option>";
						}?>
 	 		 			</select>
 	 		 			
 	 		 			
 	 		 			
						<input type="text" id="username" placeholder="Username" name="username" disabled>
						<input type="password" id="password" placeholder="Password" name="password">
						<input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword">
						
						
						<p style="color:#ff0000;text-align:center;font-weight:bold;" id="wrong"></p>											  
						<button id="login" class="addbtn">Request Access</button>		
					
 	 		 		</center>	
  	 		 
  	 		
   	 		  </div>
   	 		 
</div>  
</center> 	 	
</div>    

        
         
           
    <script>
		
</script> 

    </body>
</html>
