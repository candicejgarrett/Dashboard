// JavaScript Document
$(document).ready(function () {

  //special css fixes
  if ($(window).width() < 728) {
    $(".col-sm-4").removeClass("col-xs-offset-4");
    $(".col-sm-2").css("padding-left", "15px");
  }

  setTimeout(function () {
    if (navigator.userAgent.indexOf("Firefox") > 0) {
      $('input[type=datetime-local],input[type=date]').datepicker({
        dateFormat: 'yy-mm-dd'
      });
    } else {}
  }, 1);

  //print function
  function printData() {
    var divToPrint = document.getElementById("expandedMockup");
    newWin = window.open("");
    newWin.document.write(divToPrint.outerHTML);
    setTimeout(function () {
      newWin.print();
      newWin.close();
    }, 1000);

  }


  $(document).on('click', '#printMockup', function () {
    printData();
  });

  $(document).on('click', '#downloadMockup', function (e) {
    e.preventDefault(); //stop the browser from following
    var visibleMock = $("#expandedMockup:visible").attr("src");
    $("#downloadMock").attr("href", visibleMock);
    $("#downloadMock")[0].click();
    console.log("clicks");
  });


  //adding/removing reviewers
  $(document).on('click', '#addReviewer', function () {
    $("#newReviewerRow").slideToggle();
    $("#deleteReviewer,#sendReminder").toggle();

  });
  $(document).on('click', '#deleteReviewer', function () {
    $(".deleteReviewer:not(:eq(0))").slideToggle();
    $("#addReviewer,#sendReminder").toggle();

  });

  //setting active tab for mocks
  $(document).on('click', '#desktopLink', function () {
    $("#desktop").addClass("active").fadeIn();
    $("#mobile").removeClass("active").hide();
    $(this).addClass("active");
    $("#mobileLink").removeClass("active");
  });
  $(document).on('click', '#mobileLink', function () {
    $("#desktop").removeClass("active").hide();
    $("#mobile").addClass("active").fadeIn();
    $(this).addClass("active");
    $("#desktopLink").removeClass("active");
  });


  $(document).on('click', '#newReviewerRow .userTags', function () {
    var thisTag = $(this).text();
    $("#newReviewer").val(thisTag);
  });

  //select mockup to edit
  $(document).on('click', '#showCommentBox', function () {

    $("#commentBox,#mentionUsersContainer").slideToggle();
  });


});
