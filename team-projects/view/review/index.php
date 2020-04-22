<?php  //Start the Sessions
 require('../../../header.php');
require('../../../connect.php');
$reviewID = $_GET['reviewID'];

//PERMISSIONS - if/else owner 

//does exist?
	$query = "SELECT * FROM `Tickets Review` WHERE `ReviewID`='$reviewID'";
	$query_result = mysqli_query($connection, $query) or die(mysqli_error($connection));

	while($row = $query_result->fetch_assoc()) {
		$reviewTitle=$row["Title"];
		$reviewType=$row["Type"];
	}

	$loggedInUsername =$username;


	$reviewCount = mysqli_num_rows($query_result);
	//if review exists...
	if ($reviewCount == 1) {
				
				//PERMISSIONS - if/else owner
				$query = "SELECT `userID` FROM `Tickets Review Members` WHERE `ReviewID`='$reviewID'";
				$query_result = mysqli_query($connection, $query) or die ("Query to get data from Team Project failed: ".mysql_error());

				while($row = $query_result->fetch_assoc()) {
					 $isMember[] = $row["userID"];
				}
		
				
				

				if (in_array($userID, $isMember) || $myRole === "Admin" || $myRole === "Editor") {
					
				}
				
				else {
					header("Location: /dashboard/team-projects/view/review/no-access.php"); 
					exit();
				}
			
			
		
		
	}
	
	else {
		header("location:404.php"); 
	}
?>
   <html class="x-team-projects-reviews">
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php echo $stylesjs ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
		


 <script>
	 
	 var count = 0;
	 
	 $(document).ready(function() {
		var reviewID = "<?php echo $reviewID?>"; 

		
		 CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Flash,Smiley,SpecialChar,Iframe,ShowBlocks,About,CreateDiv,Blockquote,Outdent,Indent,Language,BidiRtl,BidiLtr,Image';
};
	 CKEDITOR.replace('comment');
	CKEDITOR.config.basicEntities = false;
		 
		 
		 function loadReview() {
			$(".copyLink").parent().remove();
			var reviewID = "<?php echo $reviewID?>";
			var dataString = {'type':"loadReview",reviewID:reviewID};	
								$.ajax({
								type: "POST",
								url: "review-process.php",
								data: dataString,
								cache: false,
								success: function(results){
									
									if (results.printDesktopImage === "" || results.printDesktopImage === null) {
										$("#printDesktopImage").hide();
										
									}
									else {
										$("#printDesktopImage").attr("src",results.printDesktopImage).attr("imageID",results.printDesktopImageID);
										
									}
									
									if (results.printMobileImage === "" || results.printMobileImage === null) {
										$("#printMobileImage").hide();
										
									}
									else {
										$("#printMobileImage").attr("src",results.printMobileImage).attr("imageID",results.printMobileImageID);
									}
									
									$("#printDueDate").html(results.printDueDate);
									$("#printCategory").html(results.printCategory);
									$("#printMembers").html(results.printMembers);
									$("#mentionUsers").html(results.mentionUsers);
									$("#printLaunchDate").html(results.printLaunchDate);
									$("#printDateCreated").html(results.printDateCreated);
									$("#printDueDate").html(results.printDueDate);
									$("#printProjectTitle").html(results.printProjectTitle);
									$("#printReviewTitle").html(results.printReviewTitle);
									$(".printProjectTitle").html("Project: <em><u>"+results.printProjectTitle+"</u></em>").attr("href","../?projectID="+results.printProjectID);
									$(".printReviewType").html(results.printType);
									$("#printComments").html(results.printComments);
									$("#canUploadDesktop").html(results.canUploadDesktop);
									$("#canUploadMobile").html(results.canUploadMobile);
									$("#canEditReviewers").html(results.canEditReviewers);
									$("#canSendEmailUpdate").html(results.canSendEmailUpdate);
									$("#canMarkApproved").html(results.canMarkApproved);
									$("#userMarkAsButton").html(results.userMarkAsButton);
									 
									$("#viewMoreDesktopButton").html(results.viewMoreDesktop);
									$("#viewMoreMobileButton").html(results.viewMoreMobile);
									
									$("#printReviewTitle").after(results.canEditReviewTitle);
									$("#printDesktopMarkupCount").html(results.printDesktopMarkupCount);
									$("#printMobileMarkupCount").html(results.printMobileMarkupCount);
									
									$("#copyLinkInput").val(window.location.href);
									
								}
								});
		}
		 
		 loadReview();
		 
		 function loadMarkUps(imageID) {
			
			
			var dataString = {'type':"loadMarkUps",imageID:imageID};	
								$.ajax({
								type: "POST",
								url: "review-process.php",
								data: dataString,
								cache: false,
								success: function(results){
									$("#marks").html(results.printMarkUps);
									$("#printDisplayMarkUps").html(results.printDisplayMarkUps);
								}
								});
		}
		 
		 //mentioning users
	var mentionUserIDs = [];
		$(document).on('click','#mentionUsers .userTags', function() { 
			var thisUserID = $(this).attr("userid");
			$(this).toggleClass("selected");
			
			if ($(this).hasClass("selected")) {
				
				mentionUserIDs.push(thisUserID);
				
				}
			else {
				mentionUserIDs.splice($.inArray(thisUserID, mentionUserIDs),1);
			}
			
		});
		 
		 $(document).on( 'click', '#addComment', function() {
			var comment = CKEDITOR.instances.comment.getData();
			 comment= encodeURIComponent(comment);
			
			var thisButton = $(this);
			 	if (comment==='') {
			$("#commentBox").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
				$("#commentBox").removeClass("required");
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
			 var mentionUserIDsJson = JSON.stringify(mentionUserIDs);
			 
			 $.ajax({
				    		url: 'review-process.php',
				    		data: 'type=addComment&comment='+comment+'&reviewID='+reviewID+'&mentionUserIDs='+mentionUserIDsJson,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(results){
								mentionUserIDs = [];
								CKEDITOR.instances.comment.setData('');
								loadReview();
							}
					});
			
			 
		});
		 
		
		  $(document).on( 'click', '#userMarkApproved', function() {
			 	$.alertable.confirm('Are you sure? A notification will be sent to the owner of this review.').then(function() {
			  		$.ajax({
				    		url: 'review-process.php',
				    		data: 'type=userMarkApproved&reviewID='+reviewID,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(results){
								loadReview();
							}
					});
				});
		});
		 
		  $(document).on( 'click', '#userMarkNotApproved', function() {
			 
			 	 	$.alertable.confirm('Are you sure? A notification will be sent to the owner of this review.').then(function() {
				 
			  		$.ajax({
				    		url: 'review-process.php',
				    		data: 'type=userMarkNotApproved&reviewID='+reviewID,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(results){
								loadReview();
							}
					});
			});
			 
		});
		 
		 //desktop image upload
		 $(document).on('click','#desktopUpload:not(.failed)', function() {
				
				var desktop_file_data = $('#desktopPreviewImage').prop('files')[0];  
				var thisButton = $(this);
			 	if ($("#desktopPreviewImage").val()==='') {
			$("#desktopPreviewImage").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
			 
				var type = "newReviewDesktopImage";
				var form_data = new FormData();                  
				form_data.append('type', type);
				form_data.append('desktopFile', desktop_file_data);
				form_data.append('reviewID',reviewID); 
			
				if (desktop_file_data !== undefined) {
					$.ajax({
					url: 'review-process.php', // point to server-side PHP script 
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(php_script_response){
						alert(php_script_response); // display response from the PHP script, if any
						location.reload();
					}
					});
					
					
				}
			
				else {
					
				}
		
				
			
	});
		 //mobile image upload
		  $(document).on('click','#mobileUpload:not(.failed)', function() {
				
				var mobile_file_data = $('#mobilePreviewImage').prop('files')[0];  
			  
			  	var thisButton = $(this);
			 	if ($("#mobilePreviewImage").val()==='') {
			$("#mobilePreviewImage").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
			  
			  
				var type = "newReviewMobileImage";
				var form_data = new FormData();                  
				form_data.append('type', type);
				form_data.append('mobileFile', mobile_file_data);
				form_data.append('reviewID',reviewID); 
				
				if (mobile_file_data !== undefined) {
					$.ajax({
					url: 'review-process.php', // point to server-side PHP script 
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(php_script_response){
						alert(php_script_response); // display response from the PHP script, if any
						location.reload();
					
					}
						
					});
					
					
					
				
				}
			
				else {
					
				}
		
			
			
	});
		 
		 //get comparisons 
		 $(document).on('click','#viewMoreDesktop', function() {
				
	
				
			 	$.ajax({
				    		url: 'comparisons.php',
				    		data: 'type=viewMoreDesktop&reviewID='+reviewID,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(results){
								
								$("#desktopCurrent").html(results.desktopCurrent);
								$("#desktopPrevious").html(results.desktopPrevious);
							}
					});
			
		});
		 $(document).on('click','#viewMoreMobile', function() {
				

			 	$.ajax({
				    		url: 'comparisons.php',
				    		data: 'type=viewMoreMobile&reviewID='+reviewID,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(results){
								
								$("#mobileCurrent").html(results.mobileCurrent);
								$("#mobilePrevious").html(results.mobilePrevious);
							}
					});
			
		});
		 $(document).on('click','.showImage', function() {
			 
			 $(this).next().slideToggle();
			 $(".showImage").next().not($(this).next()).hide();
		 });
		 
		 //reviewers
		 //getting usernames to add
		 $('#newReviewer').keyup(function(){
				  var valThis = $(this).val();
		
		
				if(valThis.charAt(0) === "@" && this.value.length > 1) {
					var newVal = valThis.substring(1, valThis.length);
					
					$("#showUsernames").fadeIn();
					
					var dataString = {'type':"getUsernames",typedUsername:newVal,reviewID:reviewID};	
					$.ajax({
						type: "POST",
						url: "/dashboard/team-projects/view/review/review-process.php",
						data: dataString,
						cache: false,
						success: function(results){
							
							if (results.foundUsernames !== null) {
								
								$("#showUsernames").html(results.foundUsernames);
							}
							else {
								
							}
						}
						});
				}
			  else {
				  	$("#showUsernames").html("");
			  }
		});
		 $(document).on('click','#newReviewerRow .userTags', function() {
				
				
				var username = $(this).text();
			 	var userID = $(this).attr("userid");
			 	
			 	if (!userID) {
					alert("User is required.");
					return false;
					}
				else{
			 	$.ajax({
				    		url: 'review-process.php',
				    		data: 'type=newReviewer&reviewID='+reviewID+'&newUserID='+userID,
				    		type: 'POST',
				    		dataType: 'text',
							success: function(result){
								if (result !== "") {
									alert(result);
								}
								else {
									$("#newReviewerRow").slideUp();
								$("#newReviewer").val("");
								$("#showUsernames").html("");
								loadReview();
								}
								
								
								
								
							}
					});
			}
			
		});
		 $(document).on('click','.deleteReviewer', function() {
				
			 
			var thisUserID = $(this).attr("userid");
			 $.alertable.confirm("Are you sure? This CANNOT be undone! This reviewer's comments will be deleted as well!").then(function() {
				 
				var dataString = {'type':"deleteReviewer",'reviewID':reviewID,'userID':thisUserID};
				 
			 	$.ajax({
												url: 'review-process.php',
												data: dataString,
												type: 'POST',
												dataType: 'text',
												success: function(result){

													loadReview();
													
													
												}
										});	
				 
			 
			 
			 });
		});
		 
		
		 //expanding mockup
		 $(document).on( 'click', '#printDesktopImage,#printMobileImage', function() {
			 var windowHeight = $(window).height();
			 
			 if ($(this).attr("id") === "printDesktopImage") {
				 $("#expandMockup .modal-content").css("width","1200px");
				 $("#expandedMockup").css("width","900px");
				 $("#expandMockup .col-sm-10").css("width","920px");
				 $("#expandMockup .col-sm-2").css("width","265px");
			}
			 else {
				 $("#expandMockup .modal-content").css("width","800px");
				 $("#expandedMockup").css("width","500px");
				 $("#expandMockup .col-sm-10").css("width","520px");
				 $("#expandMockup .col-sm-2").css("width","265px");
			 }
			 
			 
			var thisSrc = $(this).attr("src");
			 var thisImageID = $(this).attr("imageID");
			$("#expandedMockup").attr("imageID",thisImageID);
			 $("#expandedMockup").attr("src",thisSrc);
			 $("#mockupCanvas").attr("whichImage",$(this).attr("id"));
			 $('#expandMockup').modal('show');
			 
			 setTimeout(function(){
				  var mockHeight = $("#expandedMockup").height();
					$("#mockupCanvas").height(mockHeight);
				 var mockWidth = $("#expandedMockup").width();
					$("#mockupCanvas").width(mockWidth);
				}, 200);
			 
			  setTimeout(function(){
			loadMarkUps(thisImageID);
			 }, 300);
			 
			 
			
		});
		 
		 
		$('#mockupCanvas').on( 'click', function(e) {
			
			if (e.target == this) {
			
			
			var parentOffset = $(this).offset(); 
			var thisUserID = "<?php echo $userID?>";
			var initials = "<?php echo substr($FN, 0, 1).substr($LN, 0, 1)?>";
			
			var yPos;
			if ($(this).attr("whichimage") == "printDesktopImage") {
				yPos = ((e.pageY - parentOffset.top) /$('#mockupCanvas').height()) * 100;
			}	
			else {
				yPos = ((e.pageY - parentOffset.top) /$('#mockupCanvas').height()) * 100;
			}
			
			var xPos = ((e.pageX - parentOffset.left) /$('#mockupCanvas').width()) * 100;
				
			
			count++;
			mark = $("<span userid='"+thisUserID+"'>"+initials+"</span>").css({
					top: yPos+ "%",
					left: xPos+ "%",
					padding: "8px 0px 0px 0px !important"
			});
			
			$(mark).attr('id', "mark" + count);
			$(mark).addClass('mark');
			$(mark).css('display', 'none');
			$("div#marks").append(mark);
			$(mark).show('slow');
				
				
				if ($(this).attr("whichimage") == "printDesktopImage") {
				if (xPos > 74) {
					xPos = xPos-27;
					}
				else {
					xPos = xPos;
					}
				}
				else  {
				if (xPos > 64) {
					xPos = xPos-27;
					}
				else {
					xPos = xPos;
					}
					
				}
				
				
				
			$(mark).after('<div class="commentHere" mark="mark'+count+'" style="display:block;"><textarea placeholder="Enter your markup here..."></textarea><div class="smallSend cancel"><i class="fa fa-times" aria-hidden="true"></i></div><div class="smallSend newMarkup"><i class="fa fa-check" aria-hidden="true"></i></div>');
			
			$("div[mark='mark"+count+"']").css({
			top: yPos+ "%",
					left: xPos+ "%"
			});
				}

  });
		 
		 //save mockup comment 
		 $(document).on( 'click', '.commentHere .saveMarkup', function() {
			 
			 var markUp = $(this).parent().find("textarea").val();
			 var markUpID = $(this).parent().attr("markupid_controller");
			 var imageID = $('#expandedMockup').attr("imageID");
			 var whichImage = $('#mockupCanvas').attr("whichimage");
			 
			  var thisButton = $(this);
			 	if (markUp==='') {
			$(this).parent().find("textarea").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
				
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
			 
			 
			 var dataString = {'type':"updateMarkUp",'reviewID':reviewID,'markUp':markUp,'markUpID':markUpID,'whichImage':whichImage};
						
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									loadMarkUps(imageID);
								},
								error: function (jqXHR, status, error) {
										console.log("jqXHR: "+jqXHR);
										console.log("status: "+status);
										console.log("error: "+error);
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
			 
			 $(this).parent().fadeOut();
			 });
		 
		 $(document).on( 'click', '.commentHere .cancel', function() {
			 
			 $(this).parent().prev().remove();
			 $(this).parent().remove();
			 });
		 
		 $(document).on( 'click', '.mark', function() {
			 $(this).next().fadeToggle();
			 
			 });
		 
		 $(document).on('click','.displayMarkup', function() {
				
			 var markUpID = $(this).attr("markupid");
			$(".mark[markupid='"+markUpID+"']").trigger("click");
		});
		 
		  //add new mockup comment 
		 $(document).on( 'click', '.commentHere .newMarkup', function() {
			 
			 var markUp = $(this).parent().find("textarea").val();
			 var xPos = parseFloat($(this).parent().prev().css('top'))/$('#mockupCanvas').height() * 100;
			 var yPos = parseFloat($(this).parent().prev().css('left'))/$('#mockupCanvas').width() * 100;
			 var imageID = $('#expandedMockup').attr("imageID");
			 var whichImage = $('#mockupCanvas').attr("whichimage");
			 
			 var thisButton = $(this);
			 	if (markUp==='') {
			$(this).parent().find("textarea").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
				
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
			return false;
		}
			 
			 
			 var dataString = {'type':"addMarkUp",'reviewID':reviewID,'markUp':markUp,'imageID':imageID,'xPos':xPos,'yPos':yPos,'whichImage':whichImage};
						
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									loadMarkUps(imageID);
									loadReview();
								},
								error: function (jqXHR, status, error) {
										console.log("jqXHR: "+jqXHR);
										console.log("status: "+status);
										console.log("error: "+error);
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
			 
			 $(this).parent().fadeOut();
			 });
		 
		 $(document).on( 'click', '.commentHere .deleteMarkup', function(e) {
			 e.stopPropagation();
			 	
			 	if (confirm('Are you sure? This CANNOT be undone!')) {
			 var imageID = $('#expandedMockup').attr("imageID");
			 var markUpID = $(this).parent().prev().attr("markUpID");
			 	 var dataString = {'type':"deleteMarkUp",'markUpID':markUpID};
						
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									loadMarkUps(imageID);
									loadReview();
								},
								error: function (jqXHR, status, error) {
										console.log("jqXHR: "+jqXHR);
										console.log("status: "+status);
										console.log("error: "+error);
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
				}
			 });
  
		  //get editable mockups 
		 $(document).on( 'click', '.editMockupLoad', function() {
			 
			 var mockType = $(this).attr("mockType");
			 var dataString = {'type':"editMockupsLoad",'reviewID':reviewID,'mockType':mockType};
			
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'json',
								success: function(results){
									
									if (results.printActiveMockup !== null) {
											$("#printActiveMockup").html('<div class="mockupActions"><i class="fa fa-check" aria-hidden="true"></i><i class="fa fa-times" aria-hidden="true"></i></div><img imageid="'+results.printActiveMockupImageID+'" src='+results.printActiveMockup+'>');
										
										}
									else {
										
									}
									
									$("#printPreviousMockups").html(results.printPreviousMockups);
									$("#printPreviousMockups img[src='"+results.printActiveMockup+"']").parent().addClass("active");
								},
								error: function (jqXHR, status, error) {
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
			 
			 });
		 
		 //select mockup to edit
		 $(document).on( 'click', '.prevMockups', function() {
			 var thisImageID = $(this).find("img").attr("imageID");
			 var thisImageSrc = $(this).find("img").attr("src");
			
			 $(".prevMockups").not(this).removeClass("selected");
			 $(this).addClass("selected");
			 $("#printActiveMockup img").attr("imageID",thisImageID).attr("src",thisImageSrc);
			 $(".mockupActions .fa-check").fadeIn();
			 
		 });
		 
		  //delete mockup 
		 $(document).on( 'click', '.mockupActions .fa-times', function() {
			 if (confirm('Are you sure? This CANNOT be undone!')) {
			 
			 var thisImageID = $(this).parent().next().attr("imageID");
			 var mockType = $(".mySecondaryTabs li.active").html();
			 var dataString = {'type':"deleteMockup",'reviewID':reviewID,'imageID':thisImageID,'mockType':mockType};
				
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									
									 $('#editMockupsModal').modal('toggle');
									loadReview();
								},
								error: function (jqXHR, status, error) {
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
			 
			
			 }
			 
			 
			 });
		 
		 //save mockup 
		 $(document).on( 'click', '.mockupActions .fa-check', function() {
			 
			 var thisImageID = $(this).parent().next().attr("imageID");
			 var mockType = $(".mySecondaryTabs li.active").html();
			 var dataString = {'type':"updateMockup",'reviewID':reviewID,'imageID':thisImageID,'mockType':mockType};
				
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									 $('#editMockupsModal').modal('toggle');
									
									 loadReview();
								
									
								},
								error: function (jqXHR, status, error) {
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
			 
			
			 
			 });
		 
		 //notify pending/not approved users
		 $(document).on( 'click', '#sendReminder', function() {
			  
			 $.alertable.confirm('Are you sure? A notification will be sent to all members who have not responded yet.').then(function() {
		
	
			 
			 var dataString = {'type':"nudgeUsers",'reviewID':reviewID};
				
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									  alert("Members have been notified.");
									 loadReview();
								
									
								},
								error: function (jqXHR, status, error) {
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
			 
			   });
			 
			 });
		 
		 
		 //delete comments
		 $(document).on('click','.deleteReviewComment', function() {
				var commentID = $(this).parent().attr("reviewcommentid");
				
			 $.alertable.confirm("Are you sure? This CANNOT be undone!").then(function() {
				 
				
			 	$.ajax({
												url: 'review-process.php',
												data: 'type=deleteReviewComment&commentID='+commentID,
												type: 'POST',
												dataType: 'text',
												success: function(result){

													loadReview();
													
													
												}
										});	
				 
			 
			 
				});
			 
			
		});
	
		 
		 //mark as approved
		 $(document).on('click','#canMarkApproved div', function() {
				
				var dataString = {'type':"markApproved",'reviewID':reviewID};
			 
			 $.alertable.confirm("Are you sure? This will set ALL users as approved! This CANNOT be undone!").then(function() {
				 
				
			 	$.ajax({
												url: 'review-process.php',
												data: dataString,
												type: 'POST',
												dataType: 'text',
												success: function(result){

													loadReview();
													
													
												}
										});	
				 
			 
			 
				});
			 
			
		});
	 
	 	//send updated mock email
	 	$(document).on('click','#sendUpdateEmail', function() {
			
			var desktopCheck = $('#desktopCheck');
			var mobileCheck = $('#mobileCheck');
			
			var thisButton = $(this);

			var mockupType;
			//getting which mockup
			if (desktopCheck.prop('checked') == true && mobileCheck.prop('checked') == false) {
				mockupType = 'Desktop';
				}
			if (mobileCheck.prop('checked') == true && desktopCheck.prop('checked') == false) {
				mockupType = 'Mobile';
				
			}
			
			if (desktopCheck.prop('checked') == true && mobileCheck.prop('checked') == true) {
				mockupType = 'Desktop and Mobile';
			}
			
			if (mobileCheck.prop('checked') == false && desktopCheck.prop('checked') == false){
				$("#mobileCheck,#desktopCheck").addClass("required");
			var thisIcon = $(thisButton).html();
			$(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
			setTimeout(function(){
			  $(thisButton).html(thisIcon).removeClass("failed");
			}, 1200);
				return false;
			}
			
			
			
			if (confirm('Are you sure? An email will be sent to all members of the review.')) {
				var dataString = {'type':"sendUpdateEmail",'reviewID':reviewID,'mockupType':mockupType};
				
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									 alert("Members have been notified.");
									$("#sendEmailUpdateModal").modal('hide');
									 loadReview();
								
									
								},
								error: function (jqXHR, status, error) {
										alert("ERROR CODE: "+status+". Please refresh and try again.");
								}
								});
				
			}
				
			
		});
		 
		//approval btn
		 //send updated mock email
	 	$(document).on('click','.approvalBtn', function() {
			var type = $(this).attr("id");
			var thisBtn = $(this);
			var thisBtnText = $(this).html();
			
			var dataString = {'type':type,'reviewID':reviewID};
				
								$.ajax({
								url: 'review-process.php',
								data: dataString,
								type: 'POST',
								dataType: 'text',
								success: function(results){
									
									 loadReview();
								
									
								},
								error: function (jqXHR, status, error) {
										alert("ERROR CODE: "+status+". Please refresh and try again. If the problem persists, please contact Candice Garrett.");
								}
								});
		});
	 
	 });
	 
	 	
	 
    </script>
  </body>
</html>
	 
	 
	</script>
    <style>
		 
		body {    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);}
	
		.leftField {
			    float: left;
    width: 49.5%;
			margin-right:1%;
		}
		.rightField {
			    float: right;
    width: 49.5%;
			
		}
		
		#mentionUsersContainer {
			display:none;
		}
		</style>
      </head>

    <body>
    

<div class="container-fluid">

<center>
	<h1 style="color:#ffffff;margin-top:5%;margin-bottom:20px">
	<span class="printReviewType"></span> Review: 
	<span id="printReviewTitle"></span>
	<div style="font-size: 18px;margin-top: 10px;">Due: <span id="printDueDate"></span></div>
		
	<a href="" style="font-size: 24px;margin-top: 30px;color:#ffffff;clear: both;display: block;" class="printProjectTitle"></a>
		
	</h1>
</center>
	
  	 	
  	 	
  	 	<div class="whitebg" style="margin:0 auto;margin-bottom: 25px;">
   	 	<div class="row">	
  	 		<div class="col-sm-6">	
  	 			<div class="header">
					<div id="canSendEmailUpdate" class="pull-right"></div>
					<div id="canMarkApproved" class="pull-right"></div>
	 		 <h3>Content</h3>
					<p>Click the mockup below to view/add markups.</p>
	 		 	</div>	
				
   	 				<ul class="mySecondaryTabs" role="tablist2">
							<li class="active" id="desktopLink">Desktop</li>
						  <li id="mobileLink">Mobile</li>
							</ul>
							<hr>
					  	<div id="desktop" class="active">
					  		<div class="row">	
								<div class="col-sm-12">	
					  				<p><strong>Total Markups:</strong> <span id="printDesktopMarkupCount"></span></p>
					  			</div>
  	 							<div class="col-sm-12">	
					  				<div id="canUploadDesktop"></div>
									
					  			</div>
					  			
					  		</div>
					  	<br>
					  	<img id="printDesktopImage" style="width:100%">
					  	
						</div>
					
						<div id="mobile">
						<div class="row">	
							<div class="col-sm-12">	
					  				<p><strong>Total Markups:</strong> <span id="printMobileMarkupCount"></span></p>
					  			</div>
  	 							<div class="col-sm-12" id="canUploadMobile">	
					  				
					  			</div>
					  			
					  		</div>
							<div class="col-sm-12 text-center">	
							<img id="printMobileImage" style="width:500px;margin:0 auto;">
							</div>
						</div>
 	 		 </div>
	 		<div class="col-sm-6">	
	 		 <div class="header" style="margin-bottom: 15px;">
	 		 <h3>Reviewers </h3>
	 		 </div>
	 		 <div class="row">
	 		 		<div class="col-sm-12">
						
	 		 		<div class="row" id="newReviewerRow">
						
						<div class="col-sm-12">
							<div class="formLabels">Reviewers: (Use the @ symbol to find a user.)</div>
							<div id="showUsernames"></div></div>
	 		 		<div class="col-sm-12">
	 		 		<input type="text" id="newReviewer" placeholder="Enter the reviewer's @username here.">
	 		 		</div>
	 		 		
	 		 		
	 		 		</div>
	 		 			<div id="canEditReviewers"></div>
	 		 			<div id="printMembers"></div>
	 		 			
	 		 		</div>
	 		 </div>
				<hr>
	 		 <div class="row">
				 <div class="col-sm-12">
				 					
					 		<button class="genericbtn noExpand pull-right" id="showCommentBox" style="margin-bottom:20px;">Add Comment</button>
					 		<div id="userMarkAsButton" class="fa-pull-right"></div>
					</div>
	 		 	<div class="col-sm-12">
						
					
	 		 		<div class="commentSection">
						
	 		 		<div class="row">
	 		 			<div class="col-sm-12">
							<div id="mentionUsersContainer">
								<div class="formLabels">Mention:</div>
							<div id="mentionUsers"></div>
								<br>
	 		 				</div>
	 		 			</div>
	 		 			
	 		 		</div>
	 		 		<div class="row">
						
	 		 			<div class="col-sm-12" id="commentBox">
					 <textarea style="height:200px;" id="comment" placeholder="Your comment goes here..." name="comment"></textarea>
						
					 <br>
					 <button class="genericbtn noExpand pull-right" id="addComment">Send</button>
							<hr style="margin:50px 0px 0px">
					 	</div>
					 </div>
					 
					 <div id="printComments"></div>
					 </div>
				 </div>
	 		 </div>
	 		 
	 		</div> 
 	 		 			
  	 	</div>
   	 </div>
</div>    

  <!-- SEND EMAIL UPDATE -->      
<div class="modal fade" id="sendEmailUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 96%;margin: 0 auto;">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Send Mockup Update</h4>
      </div>
      <div class="modal-body">
        		<div class="row">
	 		 			<div class="col-sm-12">
	 		 				<div class="formLabels">Select Updated Mockup: </div>
							<input type="checkbox" value="Desktop" style="width: 30px;" id="desktopCheck"></input>
							<p style="display:inline-block">Desktop</p><br>
							<input type="checkbox" value="Mobile" style="width: 30px;" id="mobileCheck"></input>
							<p style="display:inline-block">Mobile</p>
	 		 				
		  					
	 		 			</div>
	 		 				
	 		 	</div>
      </div>
      <div class="modal-footer">
       
        <button type="button" id="sendUpdateEmail" class="genericbtn noExpand">Send</button>
         
      </div>
    </div>
  </div>
</div>		
		
 <!-- IMAGE COMPARISONS -->        
<div class="modal fade" id="imageComparison" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="    width: 100% !important;">
    <div class="modal-content" style="width: 96%;margin: 0 auto;">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Desktop Comparison</h4>
      </div>
      <div class="modal-body">
        		<div class="row">
	 		 			<div class="col-sm-6">
	 		 				<h3 class="text-center">Current Version</h3>
	 		 				<hr>
	 		 				<div id="desktopCurrent"></div>
	 		 			</div>
	 		 			<div class="col-sm-6">
	 		 					<h3 class="text-center">Previous Versions</h3>
								<center><em>Click a date below to view the mockup.</em></center>
	 		 					<hr>
	 		 					<div class="row">
	 		 					<div id="desktopPrevious"></div>
	 		 					</div>
	 		 			</div>	
	 		 	</div>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="genericbtn noExpand" data-dismiss="modal">Close</button>
         
      </div>
    </div>
  </div>
</div>
   
 <div class="modal fade" id="imageComparisonMobile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="    width: 100% !important;">
    <div class="modal-content" style="width: 96%;margin: 0 auto;">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Mobile Comparison</h4>
      </div>
      <div class="modal-body">
        		<div class="row">
	 		 			<div class="col-sm-6">
	 		 				<h3 class="text-center">Current Version</h3>
	 		 				<hr>
	 		 				<div id="mobileCurrent"></div>
	 		 			</div>
	 		 			<div class="col-sm-6">
	 		 					<h3 class="text-center">Previous Versions</h3>
	 		 					<hr>
	 		 					<div class="row">
	 		 					<div id="mobilePrevious"></div>
	 		 					</div>
	 		 			</div>	
	 		 	</div>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="genericbtn noExpand" data-dismiss="modal">Close</button>
         
      </div>
    </div>
  </div>
</div>
		
 <!-- EXPAND MOCKUP -->        
<div class="modal fade" id="expandMockup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 100% !important;">
    <div class="modal-content" style=";margin: 0 auto;">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">View Mockup</h4>
		 <p><em>Click on the image to add your markup.</em></p>
		  <button type="button" id="printMockup" class="genericbtn noExpand pull-right">Print</button>
		  <button type="button" id="downloadMockup" class="genericbtn noExpand pull-right" style="margin-right:10px">Download</button>
		  
      </div>
      <div class="modal-body">
        		<div class="row">
	 		 			<div class="col-sm-10" style="overflow:scroll">
							<div id="marks"></div>
	<ol id="coords"></ol>
							<div id="mockupCanvas" width="500px" height="150" style="border:1px solid #d3d3d3;"></div>
	 		 				<img src="" id="expandedMockup" style="width:500px">
							
	 		 			</div>
						<div class="col-sm-2" style="overflow:scroll">
							<h4>Mark Ups:</h4>
	 		 				<div id="printDisplayMarkUps"></div>
					</div>
	 		 	</div>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="genericbtn noExpand" data-dismiss="modal">Close</button>
         
      </div>
    </div>
  </div>
</div>
		
 <!-- EDIT MOCKUP -->        
<div class="modal fade" id="editMockupsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Edit Mockups</h4>
      </div>
      <div class="modal-body">
        		<div class="row">
	 		 			<div class="col-sm-12">
							<h4 class="text-center">Active Mockup:</h4>
							<div id="printActiveMockup"></div>
							<hr>
							<h4 class="text-center">Previous Mockups:</h4>
							<div id="printPreviousMockups"></div>
	 		 			</div>
	 		 				
	 		 	</div>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="genericbtn noExpand" data-dismiss="modal">Close</button>
         
      </div>
    </div>
  </div>
</div>
	<a href="" id="downloadMock" download ></a>
<?php echo $scripts ?>
<script type="text/javascript" src="/dashboard/js/pages/reviews.js"></script>
    </body>
</html>
