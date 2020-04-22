//sort filter search My Task
	 $(document).ready(function() {
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
		 
		 
$('#myTasks .goFilter').on('click', function() {
		var searchTerm = $("#myTasks .filterSearch").val();
		var status = $("#myTasks .Status").val();

		var owner = $("#myTasks .userID" ).val();
		var category = $("#myTasks .Category" ).val();
		var ownerText = $("#myTasks .userID" ).find(":selected").text();
		var categoryText = $("#myTasks .Category" ).find(":selected").text();
		var sortBy = $("#myTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
	
		$("#myTasks .printFilter").fadeIn();
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"myTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printMyTasks").html(results.printMyTasks);
					if (results.searchTerm !== "") {
						$("#myTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.status !== "All") {
						$("#myTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.owner !== "All") {
						$("#myTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.category !== "All") {
						$("#myTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
				}
			});
	});
	$('#myTasks .sortBy').on('change', function() {
		var searchTerm = $("#myTasks .filterSearch").val();
		var status = $("#myTasks .Status" ).val();
		var owner = $("#myTasks .userID" ).val();
		var category = $("#myTasks .Category" ).val();
		var sortBy = $("#myTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');

			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"myTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				dataType: 'json',
				success: function(results){

					$("#printMyTasks").html(results.printMyTasks);
				}
			});
		
	});
//sort filter for todo
	$('#myTasks .filterSearch').on('focusout', function() {
		if ($(this).val() === "") {
			$("#myTasks .clearSearchTerm").remove();
		}
	});
	$('#myTasks .Status').on('change', function() {
		if ($(this).val() === "All") {
			$("#myTasks .clearStatus").remove();
		}
	});
	$('#myTasks .userID').on('change', function() {
		if ($(this).val() === "All") {
			$("#myTasks .clearOwner").remove();
		}
	});
	$('#myTasks .Category').on('change', function() {
		if ($(this).val() === "All") {
			$("#myTasks .clearCategory").remove();
		}
	});
	
//END sort filter for todo		
	$(document).on('click','#myTasks .clearSearchTerm .fa', function() {
		$("#myTasks .filterSearch").val("");
		var searchTerm = null;
		var status = $("#myTasks .Status" ).val();
		var owner = $("#myTasks .userID" ).val();
		var category = $("#myTasks .Category" ).val();
		var ownerText = $("#myTasks .userID" ).find(":selected").text();
		var categoryText = $("#myTasks .Category" ).find(":selected").text();
		var sortBy = $("#myTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"myTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printMyTasks").html(results.printMyTasks);
					$("#myTasks .printSearchTermFilter").html('');
					
					
					if (status !== "All") {
						
						$("#myTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (owner !== "All") {
						$("#myTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (category !== "All") {
						$("#myTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
				}
			});
	});
	$(document).on('click','#myTasks .clearStatus .fa', function() {
		
		var searchTerm = $("#myTasks .filterSearch").val();
		$("#myTasks .Status").val("All");
		var status = "All";
		var owner = $("#myTasks .userID" ).val();
		var category = $("#myTasks .Category" ).val();
		var ownerText = $("#myTasks .userID" ).find(":selected").text();
		var categoryText = $("#myTasks .Category" ).find(":selected").text();
		var sortBy = $("#myTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		$(this).parent().parent().html('');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"myTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printMyTasks").html(results.printMyTasks);
					
					if (searchTerm !== "") {
						$("#myTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					$("#myTasks .printStatusFilter").html('');
					
					if (owner !== "All") {
						$("#myTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (category !== "All") {
						$("#myTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
				}
			});
	});
	$(document).on('click','#myTasks .clearOwner .fa', function() {
		
		var searchTerm = $("#myTasks .filterSearch").val();
		$("#myTasks .userID").val("All");
		var status = $("#myTasks .Status" ).val();
		var owner = "All";
		var category = $("#myTasks .Category" ).val();
		var ownerText = $("#myTasks .userID" ).find(":selected").text();
		var categoryText = $("#myTasks .Category" ).find(":selected").text();
		var sortBy = $("#myTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		$(this).parent().parent().html('');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"myTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printMyTasks").html(results.printMyTasks);
					
					if (searchTerm !== "") {
						$("#myTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (status !== "All") {
						$("#myTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					$("#myTasks .printOwnerFilter").html('');
					
					if (category !== "All") {
						$("#myTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
				}
			});
	});
	$(document).on('click','#myTasks .clearCategory .fa', function() {
		
		var searchTerm = $("#myTasks .filterSearch").val();
		$("#myTasks .Category").val("All");
		var status = $("#myTasks .Status" ).val();
		var owner = $("#myTasks .userID" ).val();
		var category = "All";
		var ownerText = $("#myTasks .userID" ).find(":selected").text();
		var categoryText = $("#myTasks .Category" ).find(":selected").text();
		var sortBy = $("#myTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		$(this).parent().parent().html('');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"myTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printMyTasks").html(results.printMyTasks);
					
					if (searchTerm !== "") {
						$("#myTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (status !== "All") {
						$("#myTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (owner !== "All") {
						$("#myTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					$("#myTasks .printCategoryFilter").html('');
					
				}
			});
	});
	$(document).on('click','#myTasks .clearFilters', function() {	
		var projectID = GetURLParameter('projectID');
	$("#myTasks .filterTags").fadeOut();
	
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"showMyTasks",'projectID':projectID},
				cache: false,
				success: function(results){
					$("#myTasks select:not(.sortBy)").val('All');
					$("#myTasks .filterSearch").val('');
					$("#printMyTasks").html(results.printMyTasks);
					
				}
			});
		
	});
	

$('#allTasks .goFilter').on('click', function() {
		var searchTerm = $("#allTasks .filterSearch").val();
		var status = $("#allTasks .Status").val();

		var owner = $("#allTasks .userID" ).val();
		var category = $("#allTasks .Category" ).val();
		var ownerText = $("#allTasks .userID" ).find(":selected").text();
		var categoryText = $("#allTasks .Category" ).find(":selected").text();
		var sortBy = $("#allTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		
		$("#allTasks .printFilter").fadeIn();
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"allTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printTasks").html(results.printTasks);
					if (results.searchTerm !== "") {
						$("#allTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.status !== "All") {
						$("#allTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.owner !== "All") {
						$("#allTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
					if (results.category !== "All") {
						$("#allTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					else {
						
					}
				}
			});
	});
	$('#allTasks .sortBy').on('change', function() {
		var searchTerm = $("#allTasks .filterSearch").val();
		var status = $("#allTasks .Status" ).val();
		var owner = $("#allTasks .userID" ).val();
		var category = $("#allTasks .Category" ).val();
		var sortBy = $("#allTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"allTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printTasks").html(results.printTasks);
				}
			});
		
	});
//sort filter for todo
	$('#allTasks .filterSearch').on('focusout', function() {
		if ($(this).val() === "") {
			$("#allTasks .clearSearchTerm").remove();
		}
	});
	$('#allTasks .Status').on('change', function() {
		if ($(this).val() === "All") {
			$("#allTasks .clearStatus").remove();
		}
	});
	$('#allTasks .userID').on('change', function() {
		if ($(this).val() === "All") {
			$("#allTasks .clearOwner").remove();
		}
	});
	$('#allTasks .Category').on('change', function() {
		if ($(this).val() === "All") {
			$("#allTasks .clearCategory").remove();
		}
	});
	
//END sort filter for todo		
	$(document).on('click','#allTasks .clearSearchTerm .fa', function() {
		$("#allTasks .filterSearch").val("");
		var searchTerm = null;
		var status = $("#allTasks .Status" ).val();
		var owner = $("#allTasks .userID" ).val();
		var category = $("#allTasks .Category" ).val();
		var ownerText = $("#allTasks .userID" ).find(":selected").text();
		var categoryText = $("#allTasks .Category" ).find(":selected").text();
		var sortBy = $("#allTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"allTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printTasks").html(results.printTasks);
					$("#allTasks .printSearchTermFilter").html('');
					
					
					if (status !== "All") {
						
						$("#allTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (owner !== "All") {
						$("#allTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (category !== "All") {
						$("#allTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
				}
			});
	});
	$(document).on('click','#allTasks .clearStatus .fa', function() {
		
		var searchTerm = $("#allTasks .filterSearch").val();
		$("#allTasks .Status").val("All");
		var status = "All";
		var owner = $("#allTasks .userID" ).val();
		var category = $("#allTasks .Category" ).val();
		var ownerText = $("#allTasks .userID" ).find(":selected").text();
		var categoryText = $("#allTasks .Category" ).find(":selected").text();
		var sortBy = $("#allTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		$(this).parent().parent().html('');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"allTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printTasks").html(results.printTasks);
					
					if (searchTerm !== "") {
						$("#allTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					$("#allTasks .printStatusFilter").html('');
					
					if (owner !== "All") {
						$("#allTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (category !== "All") {
						$("#allTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
				}
			});
	});
	$(document).on('click','#allTasks .clearOwner .fa', function() {
		
		var searchTerm = $("#allTasks .filterSearch").val();
		$("#allTasks .userID").val("All");
		var status = $("#allTasks .Status" ).val();
		var owner = "All";
		var category = $("#allTasks .Category" ).val();
		var ownerText = $("#allTasks .userID" ).find(":selected").text();
		var categoryText = $("#allTasks .Category" ).find(":selected").text();
		var sortBy = $("#allTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		$(this).parent().parent().html('');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"allTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printTasks").html(results.printTasks);
					
					if (searchTerm !== "") {
						$("#allTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (status !== "All") {
						$("#allTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					$("#allTasks .printOwnerFilter").html('');
					
					if (category !== "All") {
						$("#allTasks .printCategoryFilter").html('<div class="filterTags clearCategory">'+categoryText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
				}
			});
	});
	$(document).on('click','#allTasks .clearCategory .fa', function() {
		
		var searchTerm = $("#allTasks .filterSearch").val();
		$("#allTasks .Category").val("All");
		var status = $("#allTasks .Status" ).val();
		var owner = $("#allTasks .userID" ).val();
		var category = "All";
		var ownerText = $("#allTasks .userID" ).find(":selected").text();
		var categoryText = $("#allTasks .Category" ).find(":selected").text();
		var sortBy = $("#allTasks .sortBy" ).val();
		var projectID = GetURLParameter('projectID');
		$(this).parent().parent().html('');
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"allTasks",'searchTerm':searchTerm,'status':status,'owner':owner,'category':category,'sortBy':sortBy,'projectID':projectID},
				cache: false,
				success: function(results){
					
					$("#printTasks").html(results.printTasks);
					
					if (searchTerm !== "") {
						$("#allTasks .printSearchTermFilter").html('<div class="filterTags clearSearchTerm">'+results.searchTerm+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (status !== "All") {
						$("#allTasks .printStatusFilter").html('<div class="filterTags clearStatus">'+results.status+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					if (owner !== "All") {
						$("#allTasks .printOwnerFilter").html('<div class="filterTags clearOwner">'+ownerText+' <i class="fa fa-times-circle" aria-hidden="true" style="margin-left:15px;"></i></div>');
					}
					
					$("#allTasks .printCategoryFilter").html('');
					
				}
			});
	});
	$(document).on('click','#allTasks .clearFilters', function() {	
		var projectID = GetURLParameter('projectID');
	
		$("#allTasks .filterTags").html("").fadeOut();
			$.ajax({
				type: "POST",
				url: "/dashboard/team-projects/task-filter.php",
				data: {'type':"showAllTasks",'projectID':projectID},
				cache: false,
				success: function(results){
					$("#allTasks select:not(.sortBy)").val('All');
					$("#allTasks .filterSearch").val('');
					$("#printTasks").html(results.printTasks);
					
				}
			});
	});
	});