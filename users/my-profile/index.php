<?php 
include_once('../../header.php');


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
	function loadMyProfile() {
			var dataString = {'type':"loadMyProfile"};
				
				$.ajax({
					type: "POST",
					url: "process.php",
					data: dataString,
					dataType: 'json',
					cache: false,
					success: function(results){
						
						$("#printName,#printName2").html(results.printFirstName + " "+ results.printLastName).fadeIn();
						$("#printProfilePic").attr("src",results.printProfilePicture).fadeIn();
						$("#printTitle").html(results.printTitle).fadeIn();
						$("#printGroup").html(results.printGroupName+" Team").css("background-color", results.printGroupColor).fadeIn();
						$("#printGroup").parent().attr("href","/dashboard/users/teams/?team="+results.printGroupName);
						$("#printRole").html(results.printRole).fadeIn();
						$("#printUsername").html(results.printUsername).fadeIn();
						$("#printEmail").html(results.printEmail).fadeIn();
						
						$("#printNewsfeed").html(results.newsfeedItems).fadeIn();
						$("#printSubscriptions").html(results.printSubscriptions).fadeIn();
						
						$("#loadingPageSpinner").hide();
						$("#loadingPage").fadeIn();
					}
				});
		}
		loadMyProfile();
	
	function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}
	
//CHANGING PROFILE PICTURE
$("#updateProfilePic-btn").click(function(){
		
			var type = "updateProfilePic";	
			var file_data = $('#myPP').prop('files')[0];   
		
				var thisButton = $(this);
		if ($('#myPP').val() === '') {
			$('#myPP').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	
				var form_data = new FormData();                  
				form_data.append('type', type);
				form_data.append('file', file_data);
				$.ajax({
                url: 'process.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
                    
					location.reload();
                }
     			});
				
		});
	
	
//CHANGING TITLE/EMAIL
$("#updateMyInfo-btn").click(function(){
		
			var type = "updateInfo";	
			var myEmail = $("#myEmail").val();  
			var myTitle = $("#myTitle").val();  
		
			var thisButton = $(this);
		if (myEmail === '' || !validateEmail(myEmail)) {
			$('#myEmail').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	
		
		if (myTitle === '') {
			$('#myTitle').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	
	
				var form_data = new FormData();                  
				form_data.append('type', type);
				form_data.append('myEmail', myEmail); 
				form_data.append('myTitle', myTitle); 
				$.ajax({
                url: 'process.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
                    
					location.reload();
                }
     			});
				
		});
	  
//CHANGING PASSWORD

$("#changePassword-btn").click(function(){
			var type = "updatePassword";	
			var myPassword = $("#currentPassword").val();  
			var newPassword = $("#newPassword").val();  
			var confirmNewPassword = $("#confirmNewPassword").val(); 
	
			var thisButton = $(this);
		if (myPassword === '') {
			$('#currentPassword').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	if (newPassword === '') {
			$('#newPassword').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	if (confirmNewPassword === '') {
			$('#confirmNewPassword').addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
	
	
	
			var dataString = {type:type,password:myPassword,newPassword:newPassword,confirmNewPassword:confirmNewPassword};	
				
				// AJAX Code To Submit Form.
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(result){
					
					$("#message").html(result.message);
					setTimeout(function(){
					location.reload();
					}, 1300);
				}
				});
				
		});
	 
/* Notification Subscriptions */
$(document).on('click','#updateSubscriptions', function() {
		
		$( ".notificationSubscriptions" ).each(function() {
			var checkedSub = $(this).val();
			  if ($(this).is(':checked')) {
					var isChecked = "checked";
				}
			else 
			{
				var isChecked = "notChecked";
			}
			
		
					$.ajax({
				    		url: 'process.php',
				    		data: 'type=updateSubscription&isChecked='+isChecked+'&categoryID='+checkedSub,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(response){
								location.reload();
							}
					});
			
			
		});
		
	});
	
$(document).on('click','.newsfeed-item .actions .fa-ellipsis-h', function() {
			var thisMenu = $(this).parent().next();
			$(".actionMenu").not(thisMenu).slideUp();
			$(this).parent().next().slideToggle();
		});
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
			<div id="loadingPageSpinner"><center><img src="/dashboard/images/grey-spinner.gif"></center></div>	
    	 	<div  id="loadingPage">
			<div class="header">
					<h3>My Profile</h3>
					<p></p>
					</div>
			<div class="row">
			<div class="col-sm-12">
			
			</div>
			</div>
			
   	 		<div class="row">
    	 		<div class="col-sm-4">
    	 			<center><div id="changeProfilePictureBtn" data-toggle='modal' data-target='#changeProfilePicture' class="circlebtn primaryColorBG"><i class="fa fa-camera" aria-hidden="true"></i></div><img src='' style='max-height: 300px;' id="printProfilePic"></center>
					
					<h3 class='text-center' id="printName2"></h3>
					
					<a href=''>
						<div class='directory' style='font-weight: bold; position: relative;' id="printGroup"></div>
					</a>
					<hr>
					<p><div class="formLabels">Role:</div> <span id="printRole"></span></p>
					<p><div class="formLabels">Username:</div> @<span id="printUsername"></span></p>
					<p><div class="formLabels">Title:</div> <span id="printTitle"></span></p>
					<p><div class="formLabels">Email:</div> <span id="printEmail"></span></p>
					
					<br>
					<div class="row">
    	 				<div class="col-sm-12 text-center">
						
						</div>
						
					</div>
				</div>
    	 		<div class="col-sm-8">
						
    	 			<div class="row">
    	 				
						
     	 			<div class="row">
    	 				<div class="col-sm-12">
   	 						
    	 						<center>
      							<ul class="nav nav-pills">
									<li id="heading1" class="active"><a data-toggle="pill" href="#content1">Recent Activity</a></li>
									<li id="heading2"><a data-toggle="pill" href="#content2">Calendar Subscriptions</a></li>
									<li id="heading5"><a data-toggle="pill" href="#content5">Settings</a></li>
							 	 </ul>
							 	 <br><br>
							 	 </center>
							 	 <div class="tab-content">
								<div id="content1" class="tab-pane fade in active">
								 <div class="header">
					<h3>Latest Activity</h3>
					</div>
								<div id="printNewsfeed" class="newsfeedContainer"></div>
   	 					
								</div>
								<div id="content2" class="tab-pane fade text-center">
										<div class="row">
										<div class="col-sm-8 col-xs-offset-2"><br>
											<div id="printSubscriptions"></div>
										</div>
										</div>
											<br><br>
									<button id="updateSubscriptions" class="genericbtn">Update</button>	
											
								</div>
								
								
								<div id="content5" class="tab-pane fade in">
								 
								<br>
									<div class="row" style="overflow: scroll;height: 266px; padding-bottom: 5px;">
										<div class="col-sm-8 col-xs-offset-2">
											<div class="col-sm-6 text-center" style="border-right: 1px solid #eaeaea;">
												<button id="updateProfile" class="genericbtn" data-toggle='modal' data-target='#myInfo'>Update Information</button>
											</div>
											<div class="col-sm-6 text-center">
												<button id="updatePassword" class="genericbtn" data-toggle='modal' data-target='#myPassword' style="padding: 8px 24px !important;">Change Password</button>
											</div>
											

										</div>
									</div>
								
   	 					
								</div>
							  </div>
    	 				
    	 				
    	 				</div>
    	 				
     	 			</div><br>&nbsp;<br><br>
     	 			
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

 <!-- Change profile picture Modal -->
<div class="modal fade" id="changeProfilePicture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Change Profile Picture</h4>
      </div>
      <div class="modal-body">
        		<div class="form-sm">
        		<div class="row">
        			<div class="col-sm-12">
        			<div class="formLabels">Profile Picture:</div> <input type="file" id='myPP'><br>
        			
        			</div>
					
     				
      			</div>
      			
     			</div>
      </div>
      <div class="modal-footer">
       
        <button class="save noExpand" id="updateProfilePic-btn"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
         
      </div>
    </div>
  </div>
</div>   

 <!-- Update Info Modal -->
<div class="modal fade" id="myInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">My Information</h4>
      </div>
      <div class="modal-body">
        		<div class="form-sm">
        		<div class="row">
        			<div class="col-sm-12">
        			<div class="formLabels">My Title:*</div> <input type="text" id="myTitle" class="validate" value="<?php echo $Title ?>">
        			<div class="formLabels">My Email:*</div> <input type="text" id="myEmail" class="validate" value="<?php echo $Email ?>">
        			</div>
					
     				
      			</div>
      			
     			</div>
      </div>
      <div class="modal-footer">
       
        <button class="save noExpand" id="updateMyInfo-btn"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
         
      </div>
    </div>
  </div>
</div>           
 <!-- Update Password Modal -->
<div class="modal fade" id="myPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">My Information</h4>
      </div>
      <div class="modal-body">
        		<div class="form-sm">
        		<div class="row">
        			<div class="col-sm-12">
        			<div class="formLabels">Current Password:*</div> <input type="password" class="validate" id='currentPassword'><br>
        			<div class="formLabels">New Password:*</div> <input type="password" class="validate" id="newPassword">
        			<div class="formLabels">Confirm New Password:*</div> <input type="password" class="validate" id="confirmNewPassword">
        			<p id="message"></p>
        			</div>
				</div>
      			</div>
      </div>
      <div class="modal-footer">
       <button type="button" class="save noExpand" href="#" id="changePassword-btn"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
         
      </div>
    </div>
  </div>
</div>           
    
    </body>
</html>