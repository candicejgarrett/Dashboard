
$(document).ready(function(){
	"use strict";
	
	function successConfirm() {
		$(".deleteConfirm.confirmed").addClass("successConfirm");
					
					setTimeout(function() {
						$(".deleteConfirm.confirmed").html("Success!");
						$(".deleteConfirm.confirmed").prepend('<span class="successfulConfirm"><i class="fa fa-check" aria-hidden="true"></i></span>');
					}, 200);
	}
	
	//get url parameter
	var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
	
	$("#createNewPost").hide();
		$('#createNewPost-btn').click(function(){
			$("#createNewPost").slideToggle();
		});
	
	$.expr[":"].contains = $.expr.createPseudo(function(arg) {
			return function( elem ) {
				return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});
	
	//add new
	$(document).on('click','#addNewPost-btn', function() {
		
					var postTitle = $("#postTitle").val();
				  	var postTagArray = [];

					$( "#printPostTags .KCTags.addedTagFixed" ).each(function() {
						var tag = $(this).text();
						postTagArray.push(tag);
					});
					
					var postBody = CKEDITOR.instances.postBody.getData();
				 	var postCategoryID = $(this).attr("categoryID");
					
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
						type: "POST",
						url: "/dashboard/knowledge-center/process.php",
						data: {'type':"newPost",'postTitle':postTitle,'postTags':postTagArray,'postBody':postBody,'postCategoryID':postCategoryID},
						cache: false,
						success: function(results){
							console.log(results);
							window.location.href = "/dashboard/knowledge-center/post/?ID="+results.postID;

						}, 
						error: function(results) {
							console.log(results);
							alert("Error.");

						}
					});
			
				
				

	});
	// save
	$(document).on('click','#savePost-btn', function() {
		
					var postTitle = $("#updateTitle").val();

				  	var postBody = CKEDITOR.instances.updateBody.getData();
				 	var postID = $(this).attr("postID");
					var postTags = $('#postTags').val().split(",");
					var postCategoryID = $(this).attr("categoryID");
					var postTagArray = [];
				
					if (postTitle === "" || postTitle === null) {
						return false;
						}
					else {
						$( "#printPostTags .KCTags.addedTagFixed" ).each(function() {
						var tag = $(this).text();
						postTagArray.push(tag);
					});
					
					
					$.ajax({
				    		url: '/dashboard/knowledge-center/process.php',
				    		data: {'type':"updatePost",'postTitle':postTitle,'postTags':postTagArray,'postBody':postBody,'postID':postID,'postCategoryID':postCategoryID},
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								console.log(response.message);
								window.location.href = "/dashboard/knowledge-center/post/?ID="+postID;
							}, 
							error: function(response) {
								console.log(response);
								alert("Error.");

							}
					});
				
					}
		
					
				

	});
	//delete
	$(document).on('click','.creatorInfo .remove.confirmed', function() {
					var categoryID = $(this).attr("categoryID");
					var postID = $(this).attr("postID");
					
					
						$.ajax({
				    		url: '../process.php',
				    		data: 'type=deletePost&postID='+postID+'&categoryID='+categoryID,
				    		type: 'POST',
				    		dataType: 'json',
							success: function(response){
								//confirmation animation
							successConfirm();
							//END confirmation animation
							   setTimeout(function(){
								window.location.replace("/dashboard/knowledge-center/category/?cat="+response.category);
								}, 1200);
								
							}
						});
					
			
				
				

	});
	
	//search
	 
	$('#postSearch').keyup(function(){
				  var valThis = $(this).val();
					if (valThis === ""){
						$('.blogPost').fadeIn();
					}
				 	
					$('.blogPost').each(function(){
					
						$(this).find('.headingSearch:contains("'+valThis+'")').parent().parent().parent().fadeIn();
						$('.blogPost').find('.headingSearch:not(:contains("'+valThis+'"))').parent().parent().parent().fadeOut();
						
					if (!$('.blogPost').length){
						 
					}	
						
				   });
			});
		   
	//on hover 

	
	$(document).on('mouseenter','#printPostTags .KCTags:not(.deletedTag,.deletedTagFixed)', function () {
    	$(this).addClass("deletedTag");
	});
	$(document).on('mouseleave','#printPostTags .KCTags.deletedTag:not(.deletedTagFixed)',  function(){
    $(this).removeClass("deletedTag");
    });
	
	$(document).on('mouseenter','#printPostTags .KCTags.deletedTagFixed:not(.addedTag)', function () {
    $(this).addClass("addedTag").removeClass("deletedTag");
	});
	$(document).on('mouseleave','#printPostTags .KCTags.addedTag:not(.addedTagFixed)',  function(){
    $(this).removeClass("addedTag").addClass("deletedTag");
    });
	
	$(document).on('click','#printPostTags .KCTags.deletedTag', function () {
    $(this).addClass("deletedTagFixed deletedTag").removeClass("addedTagFixed addedTag");
	});
	
	$(document).on('click','#printPostTags .KCTags.addedTag', function () {
    $(this).addClass("addedTagFixed").removeClass("deletedTagFixed deletedTag addedTag");
	});
	
	$(document).on('click','.showTags .KCTags', function() {
			  var tagVal = $(this).text();
			  $(this).parent().prev().val(tagVal);
			   $(this).hide();
			});
	
	//add new tag
	$(document).on('click','#editPostTags-btn', function() {
		$("#addTagsContainer").toggleClass("displayInline");
		
	});
	
	$('#postTags').keyup(function(){
		
		var valThis = $(this).val();				
		var postID = getUrlParameter('ID'); 
		var categoryID = $("#savePost-btn").attr("categoryID");

				if(this.value.length > 1) {
					
					var dataString = {'type':"getTags",searchTerm:valThis,postID:postID,categoryID:categoryID};	
					$.ajax({
						type: "POST",
						url: "/dashboard/knowledge-center/process.php",
						data: dataString,
						dataType: 'json',
						cache: false,
						success: function(results){
							
								$(".selectTags").html(results.foundTags);		
							
								if (!results.foundTags) {
									$(".selectTags").html("<span>Add New</span>");
									
								}
						}
						});
				}
			  else {
				  	$(".selectTags").html("");
			  }
		});
	
	$('#postTagsCreate').keyup(function(){
		
		var valThis = $(this).val();		 
		var categoryID = $("#addNewPost-btn").attr("categoryID");

				if(this.value.length > 1) {
					
					var dataString = {'type':"getTagsCreate",searchTerm:valThis,categoryID:categoryID};	
					$.ajax({
						type: "POST",
						url: "/dashboard/knowledge-center/process.php",
						data: dataString,
						dataType: 'json',
						cache: false,
						success: function(results){
							
								$(".selectTags").html(results.foundTags);		
							
								if (!results.foundTags) {
									$(".selectTags").html("<span>Add New</span>");
									
								}
						}
						});
				}
			  else {
				  	$(".selectTags").html("");
			  }
		});
	
	//add tag to list from clicking searched tag
	$(document).on('click','.selectTags .KCTags', function() {
		
		var thisTag = $(this).addClass("addedTagFixed").append('<div class="deleteTagIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="addTagIcon"><i class="fa fa-plus" aria-hidden="true"></i></div>')
		
		if ($("#editPostTags-btn").length === 1) {
			$(this).insertBefore("#editPostTags-btn");
			}
		else {
			$(this).appendTo("#printPostTags");
		}
		var input = $('#postTags').val(); 

			$('#postTags,#postTagsCreate').val("");
	});
	
	//add tag to list from clicking searched tag
	$(document).on('click','.selectTags span', function() {
		
		var postTags;
		
		if ($('#postTags').length === 0) {
			
			postTags = $('#postTagsCreate').val().replace(/,\s*$/, "").replace("'", "");
			}
		else {
			postTags = $('#postTags').val().replace(/,\s*$/, "");
		}
		
		postTags =  $.unique(postTags.split(','));
		
		var i;
		for (i=0;i<postTags.length;i++){
			if ($("#editPostTags-btn").length === 1) {
			$('<div class="KCTags addedTagFixed">'+$.trim(postTags[i])+'</div>').append('<div class="deleteTagIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="addTagIcon"><i class="fa fa-plus" aria-hidden="true"></i></div>').insertBefore("#editPostTags-btn");
			}
		else {
			$('<div class="KCTags addedTagFixed">'+$.trim(postTags[i])+'</div>').append('<div class="deleteTagIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="addTagIcon"><i class="fa fa-plus" aria-hidden="true"></i></div>').appendTo("#printPostTags");
		}
			
		}
		
			$(".selectTags").html("");
			$('#postTags,#postTagsCreate').val("");
	});
	
	
});



