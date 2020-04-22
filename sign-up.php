<?php  //Start the Session
session_start();
 require('connect.php');

?>
   <html>
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
	<div class="whitebg" id="pending" style="margin-top:10%;width: 400px;
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
