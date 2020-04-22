
//NOTEBOOK //
$(document).ready(function(){
	"use strict";
	
	function getAllNotebookData() {
			var dataString = {'type':"getAll"};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#printNotebooks").html(results.notebooks);	
					
					//END Favorites
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
		}
	
	function getAllPageData() {
			var notebookID = $(".noteBookContainer.active").attr("notebookID");
			var dataString = {'type':"getPages",'notebookID':notebookID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#printPages").html(results.pages);	
					
					//END Favorites
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
		}
	
	function getCurrentPage() {
			var notebookID = $(".notebookContainer.active").attr("notebookID");
			var pageID = $(".pageContainer.active").attr("pageID");
			
			
			var dataString = {'type':"viewPage",'pageID':pageID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					getAllNotebookData();
					getAllPageData();
					setTimeout(function(){
					$("#printNotebooks").find("[notebookID='" + notebookID + "']").addClass("active");
					$("#printPages").find("[pageID='" + pageID + "']").addClass("active");
					}, 100);
					
					$("#savePage, #cancelUpdatePage").hide();
					
					if (pageID !== undefined) {
						
						$("#editPage, #deletePage").fadeIn();
					
					$("#showPage").fadeIn();
					$("#editPage,#deletePage,#savePage,#cancelUpdatePage").attr("pageID",pageID);
					$("#showPage").find(".title").html(results.title);
					$("#showPage").find(".content").html(results.content);
					$("#showPage").find(".dateCreated span").html(results.dateCreated);
					$("#showPage").find(".lastUpdated span").html(results.lastUpdated);
						}
					
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
		
	}
	//getting color 
	function hexc(colorval) {
    var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    delete(parts[0]);
    for (var i = 1; i <= 3; ++i) {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    color = '#' + parts.join('');
}
	
	//clicking notebook
	$(document).on('click','.noteBookContainer', function() {
			
		if($('#saveNotebook:visible').length === 1)
		{
			return false;
		}
		
			$(".noNotebook").remove();
			$(this).addClass("active");
			$(".noteBookContainer").not(this).removeClass("active");
			$(this).parent().parent().parent().parent().removeClass("col-sm-2").addClass("col-sm-4");
			$(".myPages").fadeIn();
			$(".myNotebooks,.myPages").addClass("changeSidebarWidth");
			var notebookID = $(this).attr("notebookID");
			$(".notebookIcons").remove();
			
			//adding edit and delete
		
				$(this).before('<div class="notebookIcons"><div id="editNotebook"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div><div id="deleteNotebook"><i class="fa fa-trash-o" aria-hidden="true"></i></div><div id="saveNotebook"><i class="fa fa-check" aria-hidden="true"></i></div><div id="cancelNotebook"><i class="fa fa-times" aria-hidden="true"></i></div></div>');
			
			
		
			//getting pages
			var dataString = {'type':"getPages",'notebookID':notebookID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#printPages").html(results.pages);	
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
		
		
			
	});
	
	//clicking page
	$(document).on('click','.pageContainer', function() {
			
			//removing edit/delete button for notebook
			$(".notebookIcons").remove();
		
			$(this).addClass("active");
			$(".pageContainer").not(this).removeClass("active");
			var pageID = $(this).attr("pageID");	
			var notebookID = $(this).attr("pagenotebookid");
	
			var dataString = {'type':"viewPage",'pageID':pageID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#savePage, #cancelUpdatePage").hide();
					$("#editPage, #deletePage").fadeIn();
					
					$("#printNotebooks").find("[notebookID='" + notebookID + "']").addClass("active");
					
					$("#showPage").fadeIn();
					$("#editPage,#deletePage,#savePage,#cancelUpdatePage").attr("pageID",pageID);
					$("#showPage").find(".title").html(results.title);
					$("#showPage").find(".content").html(results.content);
					$("#showPage").find(".dateCreated span").html(results.dateCreated);
					$("#showPage").find(".lastUpdated span").html(results.lastUpdated);
					
					//highlighting search term if available
					
					if ($(".searchNotebooksInput").val().length > 0) {
							var term = $(".searchNotebooksInput").val();
							$("#showPage").highlight(term, false);
						
							setTimeout(function(){
							  $("#showPage").removeHighlight();
							}, 2000);
							
						}
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
			
	});
	
	//adding new notebook
	$(document).on('click','#addNewNotebook-btn', function() {
			$(this).addClass("active");
			$(".pageContainer").not(this).removeClass("active");
			
			var title = $("#notebookTitle").val();
			var color = $("#notebookColor").spectrum('get').toHexString();
			var dataString = {'type':"newNotebook",'title':title,'color':color};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					getAllNotebookData();
					setTimeout(function(){
						
					$("#printNotebooks").find("[notebookID='" + results.notebookID + "']").addClass("active");
						
						getAllPageData();
						$("#showPage").hide();
					}, 100);
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
			
	});
	
	//adding new page
	$(document).on('click','#addNewPage-btn', function() {

			var title = $("#pageTitle").val();
			var notebookID = $(".noteBookContainer.active").attr("notebookID");

			if (!notebookID) {
					alert("Please select a notebook first.");
				return false;
				}
		
		
			var dataString = {'type':"newPage",'title':title,'notebookID':notebookID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					
					getAllPageData();
					setTimeout(function(){
					$("#printPages").find("[pageID='" + results.pageID + "']").addClass("active");
						setTimeout(function(){
							getCurrentPage();
						}, 100);
					
					}, 500);
					
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
			
	});
	
	//editing notebook
	var color = '';
	$(document).on('click','#editNotebook', function() {
		
		$(".myNotebooks.changeSidebarWidth").addClass("expanded");
		$(".myPages.changeSidebarWidth").addClass("shortened");
		var tabBox = $(this).parent().next().find(".tabIcon").find(".tabBox");
		var textContainer = $(this).parent().next().find(".title h3");
		var text = textContainer.html();
		
		var x = $(tabBox).css('backgroundColor');
		hexc(x);
		
		$(this).parent().next().find(".title").html("<input type='text' value='"+text+"' id='editNotebookTitle'>");
		tabBox.html("<input type='text' value='"+color+"' id='editNotebookColor'>");
		
		$("#editNotebookColor").spectrum({
			color:color
    	});
		
		tabBox.addClass("tabBoxExpanded");

		
		$(this).parent().next().find(".title").addClass("shortenedTitle");
		$("#saveNotebook").css("display","inline-block");
		$("#cancelNotebook").css("display","inline-block");
		$(this).remove();
		$("#deleteNotebook").remove();
		
		
	});
	// cancel edit notebook
	$(document).on('click','#cancelNotebook', function() {
		$(".myNotebooks.changeSidebarWidth").removeClass("expanded");
		$(".myPages.changeSidebarWidth").removeClass("shortened");
		
		getAllNotebookData();
		getAllPageData();
		
	});
	
	//saving notebook
	$(document).on('click','#saveNotebook', function() {
		
		if( !$("#editNotebookTitle").val() ) {
			$("#editNotebookTitle").addClass("required");
			return false;
		}
		if( !$("#editNotebookColor").spectrum('get').toHexString()) {
			$("#editNotebookColor").addClass("required");
			return false;
		}
		
		var title = $("#editNotebookTitle").val();
		var color = $("#editNotebookColor").spectrum('get').toHexString();
		var notebookID = $(".noteBookContainer.active").attr("notebookID");
		
		var dataString = {'type':"saveNotebook",'title':title,'color':color,'notebookID':notebookID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					setTimeout(function(){
					$("#printNotebooks").find("[notebookID='" + results.notebookID + "']").addClass("active");	
							getCurrentPage();
					}, 100);
					
					$(".myNotebooks.changeSidebarWidth").removeClass("expanded");
		$(".myPages.changeSidebarWidth").removeClass("shortened");
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
		
		
	});
	
	//deleting notebook			
	$(document).on('click','#deleteNotebook', function() {
			if (confirm('Are you sure? This CANNOT be undone!')) {
			var notebookID = $(this).parent().next().attr("notebookid");
			
			var dataString = {'type':"deleteNotebook",'notebookID':notebookID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					getAllNotebookData();	
					getAllPageData();	
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
			}
	});
	
	//deleting page			
	$(document).on('click','#deletePage', function() {
			if (confirm('Are you sure? This CANNOT be undone!')) {
			var pageID = $(this).attr("pageid");
			
			var dataString = {'type':"deletePage",'pageID':pageID};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$(".pageContainer.active").remove();
					$("#showPage").fadeOut();
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
				});	
			}
	});
	
	//cancel edit page 
	$(document).on('click','#cancelUpdatePage', function() {
		getCurrentPage();
	
	});
	
	//searching notebook
	$(document).on('click','.searchNotebooksIcon', function() {
		$(".searchNotebooksInput").fadeToggle();
		$(this).parent().parent().parent().parent().removeClass("col-sm-2").addClass("col-sm-4");
			$(".myPages").fadeIn();
			$(".myNotebooks,.myPages").addClass("changeSidebarWidth");
		
		$(this).toggleClass("open");	
	});
	
	$(document).on('click','.searchNotebooksIcon.open', function() {
		
	
		if ($(".notebookContainer.active").length) {
			var currentNotebook = $(".notebookContainer.active").attr("notebookID");
			getAllNotebookData();
			setTimeout(function(){
			$("#printNotebooks").find("[notebookID='" + currentNotebook + "']").addClass("active");
			}, 200);
			
		}
		
		else if ($(".pageContainer.active").length) {
			var pageNotebook = $(".pageContainer.active").attr("pagenotebookid");
			getAllNotebookData();
			setTimeout(function(){
			$("#printNotebooks").find("[notebookID='" + pageNotebook + "']").addClass("active");
				
			}, 200);
			
		}
		
			$(this).removeClass("open");	
		$(".searchNotebooksInput").val("");
			
		
		
		
	});
	
	//on third key up
	$(document).on('keyup','.searchNotebooksInput', function() {
		

		var searchTerm = $(this).val();
		
   		if( this.value.length > 0 ) {
		
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {'type':"search",'searchTerm':searchTerm},
				cache: false,
				success: function(results){
					
					$("#printNotebooks").html(results.notebooks);
					$("#printPages").html(results.pages);
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
			});
			
		}
		else {
					getAllNotebookData();
					getAllPageData();
					
		}
	
	});
	
	//editing order of notebooks 
	$(document).on('click','.editNotebooks', function() {
		$(".notebookIcons").remove();
		$(".notebookOrderBy").fadeIn();
		$("#printNotebooks,#printPages").sortable({placeholder: "ui-state-highlight",helper:'clone'});
		$('.notebookContainer,.pageContainer').click(false);
		
		$(this).replaceWith('<div class="saveNotebooks">Done</div>');
		
			
	});
	
	$(document).on('click','.saveNotebooks', function() {
		$(".notebookOrderBy").fadeOut();
		$("#printNotebooks,#printPages").sortable("destroy");
		$('.notebookContainer,.pageContainer').unbind('click');
		
		$(this).replaceWith('<div class="editNotebooks">Edit</div>');
		
		var notebookID_array = [];
        $(".notebookContainer").each(function() {  
			notebookID_array.push($(this).attr('notebookid'));  
		});
		
		var pageID_array = [];
        $(".pageContainer").each(function() {  
			pageID_array.push($(this).attr('pageid'));  
		});
		
		$.ajax({
				type: "POST",
				url: "process.php",
				data: {'type':"saveOrder",'notebookID_array':" "+notebookID_array+"",'pageID_array':" "+pageID_array+""},
				cache: false,
				success: function(results){
					
					getAllNotebookData();
					getAllPageData();
					$("#showPage").hide();
					
				}, 
				error: function(results) {
					
					alert("Error.");
				   
				}
			});
		
			
	});
	
	$('#newNotebook').on('hidden.bs.modal', function () {
  		$("#notebookTitle").val("");
	});
	
	$('#newPage').on('hidden.bs.modal', function () {
  		$("#pageTitle").val("");
	});

	
});




