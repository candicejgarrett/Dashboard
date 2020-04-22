<?php 
include_once('../header.php');
require('../connect.php');


?>
  
  
   <html class="x-template-notebooks">
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
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script src="/dashboard/js/pages/notebooks.js"></script>
<script>
	$(document).ready(function() {
		
	function getAllNotebookData() {
	
			var dataString = {'type':"getAll"};
				$.ajax({
				type: "POST",
				url: "process.php",
				data: dataString,
				cache: false,
				success: function(results){
					$("#printNotebooks").html(results.notebooks);	
					
					
				}, 
				error: function(results) {
					console.log(results);
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
					console.log(results);
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
					}, 500);
					
					$("#editPage, #deletePage").fadeIn();
					$("#savePage, #cancelUpdatePage").hide();
					$("#showPage").fadeIn();
					$("#editPage,#deletePage").attr("pageID",pageID);
					$("#showPage").find(".title").html(results.title);
					$("#showPage").find(".content").html(results.content);
					$("#showPage").find(".dateCreated span").html(results.dateCreated);
					$("#showPage").find(".lastUpdated span").html(results.lastUpdated);
					
				}, 
				error: function(results) {
					console.log(results);
					alert("Error.");
				   
				}
				});	
		
	}	
	
	//editing page
	$(document).on('click','#editPage', function() {
		
		var text = $("#showPage").find(".title").html();
		
		$("#showPage").find(".title").html("<input type='text' value='"+text+"' id='editPageTitle'>");
		
		var content = $("#showPage").find(".content").html();
		$("#showPage").find(".content").html('<textarea id="pageContent"></textarea>');
		$("#pageContent").val(content);
		CKEDITOR.replace('pageContent');
		
		
		$("#savePage").css("display","inline-block");
		$("#cancelUpdatePage").css("display","inline-block");
		$(this).hide();
		$("#deletePage").hide();
		
		
	});	
		
	//saving page
	$(document).on('click','#savePage', function() {
		var title = $("#editPageTitle").val();
		var content = CKEDITOR.instances.pageContent.getData();
		
		var pageID = $(this).attr("pageID");
		if(!title){
		   $(this).addClass("required");
		   return false;
		   }
		
		var dataString = {'type':"savePage",'title':title,'content':content,'pageID':pageID};
			$.ajax({
				url: 'process.php',
				data: dataString,
				type: 'POST',
				dataType: 'json',
				success: function(results){
					$("#printPages").find("[pageID='" + results.pageID + "']").addClass("active");
					setTimeout(function(){
					getCurrentPage();
					}, 400);
					$("#savePage, #cancelUpdatePage").hide();
					$("#editPage, #deletePage").fadeIn();
					
				}, 
				error: function(results) {
					console.log(results);
					alert("Error.");
				   
				}
			});
		
	});	
		
	$("#notebookColor").spectrum({
    });
		
		
	//getting from url
	function GetURLParameter(sParam)
		{
			var sPageURL = window.location.search.substring(1);
			var sURLVariables = sPageURL.split('&');
			for (var i = 0; i < sURLVariables.length; i++)
			{
				var sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam)
				{
					return sParameterName[1];
				}
			}
		}

		var PARAMnotebookID = GetURLParameter('notebookID');
		var PARAMpageID = GetURLParameter('pageID');
		
		if (PARAMnotebookID && PARAMpageID) {
			getAllNotebookData();
			
			setTimeout(function(){
				var thisNotebook = $("#printNotebooks").find("[notebookID='" + PARAMnotebookID + "']");
				
				if (thisNotebook.length === 0) {
					$("#showPage").before("<div class='noNotebook'><span>404</span>This notebook does not exist or has been deleted.</div>");
				}
				else {
					$("#printNotebooks").find("[notebookID='" + PARAMnotebookID + "']").addClass("active");
					getAllPageData();
					$(".myNotebooksSidebarContainer").parent().removeClass("col-sm-2").addClass("col-sm-4");
					$(".myPages").fadeIn();
					$(".myNotebooks,.myPages").addClass("changeSidebarWidth");
					
					setTimeout(function(){
					var thisPage = $("#printPages").find("[pageID='" + PARAMpageID + "']");
					
					if (thisPage.length === 0) {
						$("#showPage").before("<div class='noNotebook'><span>404</span>This page does not exist or has been deleted.</div>");
						
					}
					else {
						
						
						setTimeout(function(){
			
						$("#printPages").find("[pageID='" + PARAMpageID + "']").addClass("active");
						}, 200);
						
						setTimeout(function(){
						getCurrentPage();
						}, 300);
					}
					}, 300);
					
				}
		
			
			}, 100);
			
			
		}
		else if (PARAMnotebookID && !PARAMpageID) {
			
			getAllNotebookData();
			setTimeout(function(){
				var thisNotebook = $("#printNotebooks").find("[notebookID='" + PARAMnotebookID + "']");
				
				if (thisNotebook.length === 0) {
					$("#showPage").before("<div class='noNotebook'><span>404</span>This notebook does not exist or has been deleted.</div>");
				}
				else {

					
				$("#printNotebooks").find("[notebookID='" + PARAMnotebookID + "']").addClass("active");
					getAllPageData();

					$(".myNotebooksSidebarContainer").parent().removeClass("col-sm-2").addClass("col-sm-4");
						$(".myPages").fadeIn();
						$(".myNotebooks,.myPages").addClass("changeSidebarWidth");


				}
			}, 100);
		}
		else {
			
			getAllNotebookData();
		}
		
	
		
				 
	});	
</script>
		
    </head>
    <body>
    <input type="hidden" id="projectUserID" value="<?php echo $userID ?>" name="<?php echo $userID ?>">
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
      		<div class="col-sm-12">
      			<div class="whitebg">
					<div class="row">
					<div class="col-sm-12">
					<div class="header">
						<h3>My Notebooks</h3>
						
					</div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-sm-12">
					<div class="fullNotebookContainer">	
					<div class="row">
						
						<div class="col-sm-2">	
							<div class="notebookHeader">
								<div class="row">
									<div class="col-sm-9 text-left">
										<div class="searchNotebooksIcon"><i class="fa fa-search" aria-hidden="true"></i></div>
										<input type="text" class="searchNotebooksInput" placeholder="Search term..."></div>
									<div class="col-sm-3">
										<div class="editNotebooks">Edit</div>
									</div>
								</div>
								
								
							</div>
							<div class="myNotebooksSidebarContainer">
								<div class="myNotebooks">
									<div class="addNewNotebook">
									<button id="addNewNotebook" data-toggle='modal' data-target='#newNotebook'>+ Notebook</button>
								</div>
									<div id="printNotebooks">
									</div>
									
									
									
								
								</div>
								<div class="myPages">
									<div class="addNewPage">
									<button id="addNewPage" data-toggle='modal' data-target='#newPage'>+ Page</button>
								</div>
										<div id="printPages">
									</div>
								</div>
							</div>
						</ul>
						</div>	
						<div class="col-sm-8">	
							<div id="showPage" pageid="">
								<div id="actions" class="pull-right" style="padding: 10px;text-align: right;">
									<div id="editPage" class="smallIcon grey"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
									<div id="deletePage" class="smallIcon red"><i class="fa fa-trash" aria-hidden="true"></i></div>
									<div id="savePage" class="smallIcon green"><i class="fa fa-check" aria-hidden="true"></i></div>
									<div id="cancelUpdatePage" class="smallIcon red"><i class="fa fa-times" aria-hidden="true"></i></div>
								</div>
								
								<div class="title">Page title</div>
								<div class="dateCreated">Created: <span>09/04/18</span></div>
								<div class="lastUpdated">Last Updated: <span>09/04/18</span></div>
								
								<div class="content"></div>
								
							</div>
						</ul>
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
<!-- NEW NOTEBOOK -->
 <div class="modal fade" id="newNotebook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Add a New Notebook</h4>
      </div>
      <div class="modal-body">
        		<div class="form-sm">
        		<div class="row">
        				<div class="col-sm-6">
       			<div class="formLabels">Title:</div>
      			<input type="text" id="notebookTitle" name="notebookTitle" placeholder="">
      			
      			</div>
      			<div class="col-sm-6">
      			<div class="formLabels">Color:</div>
     			<input type="text" id="notebookColor" name="notebookColor" placeholder="#000000"  maxlength="7">
     			</div>
				</div>
      			</div>
      </div>
      <div class="modal-footer">
       <button type="button" class="save noExpand" href="#" id="addNewNotebook-btn" data-dismiss="modal"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
         
      </div>
    </div>
  </div>
</div>
<!-- NEW PAGE -->	   
<div class="modal fade" id="newPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Add a New Page</h4>
      </div>
      <div class="modal-body">
        		<div class="form-sm">
        		<div class="row">
        				<div class="col-sm-12">
       			<div class="formLabels">Title:</div>
      			<input type="text" id="pageTitle" name="pageTitle" placeholder=""><br>
      			
      			</div>
      			
				</div>
      			</div>
      </div>
      <div class="modal-footer">
       <button type="button" class="save noExpand" href="#" id="addNewPage-btn" data-dismiss="modal"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
         
      </div>
    </div>
  </div>
</div>
<!-- EDIT NOTEBOOK -->
<div class="modal fade" id="editThisNotebook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Edit Notebook</h4>
      </div>
      <div class="modal-body">
        		<div class="form-sm">
        		<div class="row">
        				<div class="col-sm-6">
       			<div class="formLabels">Title:</div>
      			<input type="text" id="editNotebookTitle" name="editNotebookTitle" placeholder=""><br>
      			
      			</div>
      			<div class="col-sm-6">
      			<div class="formLabels">Color:</div>
     			<input type="color" id="editNotebookColor" name="editNotebookColor" placeholder="#000000"  maxlength="7">
     			</div>
				</div>
      			</div>
      </div>
      <div class="modal-footer">
       <button type="button" class="save noExpand" href="#" id="saveNotebook-btn" data-dismiss="modal"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
         
      </div>
    </div>
  </div>
</div>

 <input type="hidden" id="holdingNotificationCount"> 

    <script type="text/javascript" src="../js/main.js"></script>
    
    
    </body>
</html>