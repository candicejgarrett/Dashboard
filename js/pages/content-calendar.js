// JavaScript Document
$(document).ready(function(){
	////// VARIABLES //////
	var categoryArray = [];
	
	////// FUNCTIONS //////
	function goFilter() {
			var searchTerm = $('#searchEventsTitle').val();
			//getting category exclusions
			//clearing current array
			categoryArray = [];
			$( ".filterContainer .minusOpacity" ).each(function() {
					var thisCategoryID = $(this).attr("categoryid");
 					categoryArray.push(thisCategoryID);
			});
			
			//if between dates are selected
			if ($('#specifyDateContainer').is(':visible')) {
				var startdate=$("#filterStartDate").val();
				var enddate=$("#filterEndDate").val();
			}
			else {
				var startdate="";
				var enddate="";
			
				}
			
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {'type':"search",'searchTerm':searchTerm,'categories':categoryArray,'startdate':startdate,'enddate':enddate},
				cache: false,
				success: function(results){
					$("#ccSearchResultsContainer").fadeIn();
					$("#ccSearchResults").html(results.ccSearchResults);
					$("#resultCount").html(results.numberOfEvents);
					
					if(!results.ccSearchResults) {
					   $("#ccSearchResults").html("<p>No events match your search term.</p>");
						$("#resultCount").html("0");
					   }
					
				}, 
				error: function(results) {
					console.log(results);
					alert("Error.");
				   
				}
			});
		}
		
		
	
	
	
	$(document).on('click','#datePicker', function() {
					var newDate = $(this).parent().prev().find("input").val();
		
					if (!newDate) {
							$(this).parent().prev().find("input").addClass("required");
							return false;
						}
					else {
						$(this).parent().prev().find("input").removeClass("required");
						$('#calendar').fullCalendar( 'gotoDate', newDate );
						}
					
			 		
			 });
	
	if ($(window).width() < 728) {
		$('#calendar').fullCalendar('changeView', 'agendaDay');
		}
		else {
			
		}
	
	//setting filter dates to this month
		var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var finalCurrentDate = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
	$("#filterStartDate").val(finalCurrentDate+"T09:30");
	$("#filterEndDate").val(finalCurrentDate+"T17:30");
	
	//setting default calendar time to NOW
	
	$("#saveEventModal-btn,#removeEvent,#editEventInfo,#addPreviewImage").hide();
	$("#editEventInfo").hide();
		
			
			$('#viewEvent').on('hidden.bs.modal', function () {
			  $("#editEventInfo").hide();
				$("#previewEventInfo").show();
				$(".copyLink").parent().remove();
			});
	
		//SHOWING uploading preview image
			$(document).on('click','.two', function() {
				$("#printCTAs").hide();
			});
			$(document).on('click','.one', function() {
				$("#printCTAs").show();
			});
			
			$(document).on('click','#addEventPreviewImage-btn', function() {
				$(this).next().toggle();
				
			});
	
		$(document).on('click','#desktopLink', function(){
			$("#desktop").addClass("active").fadeIn();
			$("#mobile").removeClass("active").hide();
			$(this).addClass("active");
			$("#mobileLink").removeClass("active");
		});
		$(document).on('click','#mobileLink', function(){
			$("#desktop").removeClass("active").hide();
			$("#mobile").addClass("active").fadeIn();
			$(this).addClass("active");
			$("#desktopLink").removeClass("active");
		});
		
		//filtering
		$(document).on('click','#hideCategories .eventCheckbox', function() {
		
			$(this).toggleClass("unchecked");
			var categoryID = $(this).attr("id");
			if ($(this).hasClass("unchecked")) {
				
				$( ".unchecked" ).each(function() {
			
					$('#calendar').fullCalendar('removeEvents', function(event) {
						return event.CategoryID == categoryID;
					});
					
				});
			}
			else {
					 //getFreshEvents();
				var dataString = {'type':"fetchOnly",'categoryID':categoryID};
					$.ajax({
						url: 'process.php',
						type: 'POST', // Send post data
						data: dataString,
						async: false,
						success: function(s){
							freshevents = s;
						}
					});

						setTimeout(function(){
						  $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
						}, 10);
			}
			
			var filterCount = $(".unchecked").length;
			
			if (filterCount !== 0) {
				if($("#clearFilter").length == 0) {
					$( "#hideCategories .eventFilter" ).last().after('<div class="eventFilter" style="font-weight:bold;color:#4801FF !important; border:1px solid #4801FF;display:block !important;max-width: 200px;text-align:Center;cursor:pointer;" id="clearFilter">Clear Filters</div>');
				}
				else {
					
				}
				
			}
			else {
				$( "#clearFilter" ).remove();
			}
			
		 });
		
		
	//searching
	$(document).on('click','#searchEventsTitle', function() {
			$("#ccSearchResultsContainer,.ccSearchResultsHeader,#ccSearchResults").fadeIn();
		});
		$(document).on('keyup','#searchEventsTitle', function() {
		

   		if( this.value.length > 0 ) {
			goFilter();
		}
		else {
					$("#ccSearchResultsContainer").slideUp();
					
		}
	
	});
	
	//closing search results
		$(document).on('click','#closeSearchResults', function() {
			$(".ccSearchResultsHeader,#ccSearchResults").slideUp();
			$("#ccSearchResultsContainer").fadeOut();
			$("#searchEventsTitle").val("");
			$("#ccSearchResults").html("");
			$("#specifyDateContainer").hide();
			$("#filterStartDate").val(finalCurrentDate+"T09:30");
			$("#filterEndDate").val(finalCurrentDate+"T17:30");
			$("#resultCount").html("0");
			$(".filterContainer").find(".minusOpacity").removeClass("minusOpacity");
		});
		
		//filtering searches
		$(document).on('click','.filterContainer .eventLabels', function() {
		
			
			$(this).toggleClass("minusOpacity");
			goFilter();
			
			
		 });
		//between dates
		$(document).on('click','#specifyDate', function() {
			$("#specifyDateContainer").fadeToggle();
			$("#specifyDateCancel").css("display","inline-block");
		});
		
		$(document).on('click','#specifyDateCancel', function() {
			$("#specifyDateContainer").fadeOut();
			$("#specifyDateCancel").fadeOut();
			setTimeout(function(){
			goFilter();
			}, 500);
		});
		
		$(document).on('focusout','#filterStartDate,#filterEndDate', function() {
		
			if (new Date($("#filterEndDate").val()) <= new Date($("#filterStartDate").val())) {
				alert("The filter's end date must be after the filter's start date.");
				return false;
			}
			
			goFilter();
		 });
	
			 //adding 
	$(document).on('click','.userTags', function() {
			var thisTag = $(this).text();
			var thisID = $(this).attr("userID");
			$("#giveAccessTo").val(thisTag).attr("userID",thisID);
	});
	
});
