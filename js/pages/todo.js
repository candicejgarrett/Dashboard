// JavaScript Document

$(document).ready(function () {

  var lastColumn = parseInt($("#projectsTableContainer table tr th").length - 1);
  var orderByColumn = parseInt($('#orderBy').prevAll().length);

  function reload(pageLength) {
    pageLength = parseInt(pageLength);
    if (!pageLength) {
      return false;
    }
    $('#printBack').hide();
    $('#printBack').DataTable().destroy();
    setTimeout(function () {

      $('#printBack').DataTable({
        'destroy': true,
        'pageLength': pageLength,
        "order": [
          [orderByColumn, "ASC"]
        ],
        "columnDefs": [{
          "orderable": false,
          "targets": lastColumn
        }]
      });
      $(".dataTables_length").appendTo("#printBack_wrapper");
      $("#orderBy").addClass("sorting_desc");
      $('#printBack').show();
    }, 10);


  }
  window.reloadSuccess = function (entries, entryID) {

    var pageLength = $("#printBack_length select").children("option:selected").val();

    if (entries !== null) {

      $("#projectsTableContainer table tbody").remove();
      $("#projectsTableContainer table thead").after('<tbody>' + entries.join("") + '</tbody>');

      reload(pageLength);
    } else {

      if (entryID.constructor.name == "Array") {
        $.each(entryID, function (index, value) {
          $("tr[taskid='" + value + "']").remove();
        });

      } else {
        $("tr[taskid='" + entryID + "'],tr[reviewid='" + entryID + "']").remove();
      }


      $(".dataTables_info").html("Showing 0 to 0 entries");
    }
  }

  function load() {

    var dataString = {
      'type': "load"
    };

    $.ajax({
      type: "POST",
      url: "process.php",
      data: dataString,
      cache: false,
      success: function (result) {

        $("#projectsTableContainer").fadeIn();

        var entries = result.printBack;

        if (!entries) {
          $("#projectsTableContainer table thead").after('<tbody><tr><td colspan="7" class="text-center">No data to show.</td></tr></tbody>');
        } else {
          $("#projectsTableContainer table thead").after('<tbody>' + entries.join("") + '</tbody>');


          $('#printBack').DataTable({
            'destroy': true,
            "order": [
              [orderByColumn, "asc"]
            ],
            "columnDefs": [{
              "orderable": false,
              "targets": lastColumn
            }]
          });

          $(".dataTables_length").appendTo("#printBack_wrapper");
        }

      },
      error: function (result) {
        alert("Error.");
      }
    });
  }
  load();

  //check all
  $(document).on("click", ".dataTable thead tr th:last-child", function () {

    var thisTable = $(this).parent().parent().parent();

    $(thisTable).toggleClass("checkAllBoxes");

    if ($(thisTable).hasClass("checkAllBoxes")) {
      $(thisTable).find("input[type='checkbox']:visible").prop('checked', true);
    } else {
      $(thisTable).find("input[type='checkbox']").prop('checked', false);
    }

  });

  //on click row, show menu
  $(document).on("click", ".dataTable tbody tr td:not(:last-child)", function (e) {

    // Remove any old one
    $(".ripple,.selectedMenu,#moreInfo").remove();
    //removing class selected
    $("#projectsTableContainer tbody tr").removeClass("selected");

    // Setup
    var posX = $(this).offset().left,
      posY = $(this).offset().top,
      buttonWidth = 50,
      buttonHeight = 50;

    var parentOffset = $("#projectsTableContainer").offset();
    var yPos = ((e.pageY - parentOffset.top - 17) / $('#projectsTableContainer').height()) * 100;
    var xPos = ((e.pageX - parentOffset.left - 23) / $('#projectsTableContainer').width()) * 100;

    // Add the element
    $(this).prepend("<span class='ripple'></span>");


    // Make it round!
    if (buttonWidth >= buttonHeight) {
      buttonHeight = buttonWidth;
    } else {
      buttonWidth = buttonHeight;
    }

    // Add the ripples CSS and start the animation
    $(".ripple").css({
      width: buttonWidth,
      height: buttonHeight,
      top: yPos + '%',
      left: xPos + '%'
    }).addClass("rippleEffect");

    //changing to selected row
    $(this).parent().addClass("selected");

    if ($("#projectsTableContainer").hasClass("openTasksTable")) {

      //appending menu		
      $("#projectsTableContainer").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewInfo">View Task <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="submitReview" class="hasSecondaryMenu">Submit for Review</li><li id="reviewSecondaryMenu" controller="submitReview" class="secondaryMenu"><textarea placeholder="Enter a message..."></textarea><button class="genericbtn">Save</button></li><li id="markComplete">Mark Complete</li></div>');

    } else if ($("#projectsTableContainer").hasClass("approvalsTable")) {
      //appending menu		
      $("#projectsTableContainer").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewInfo">View Task <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="approve">Approve</li><li id="kickback" class="hasSecondaryMenu">Kickback</li><li id="kickbackSecondaryMenu" controller="kickback" class="secondaryMenu"><textarea placeholder="Enter a message..."></textarea><button class="genericbtn">Save</button></li><li id="delete">Delete</li></div>');

    } else if ($("#projectsTableContainer").hasClass("reviewsTable")) {
      //appending menu		
      $("#projectsTableContainer").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewReview">View <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="approve">Approve</li></div>');

    }


    $(".selectedMenu").css({
      top: yPos + '%',
      left: xPos + '%'
    });

  });

  //close menu/remove
  $(document).on("click", "#closeMenu", function () {
    $("#projectsTableContainer tbody tr").removeClass("selected");
    $(".selectedMenu,#moreInfo").remove();
  });

  //close more info/remove
  $(document).on("click", "#closeMoreInfo", function () {
    $("#projectsTableContainer tbody tr").removeClass("selected");
    $(".selectedMenu,#moreInfo").remove();
  });

  //if any checkbox is checked show buttons
  $(document).on("click", ".dataTable thead tr th:last-child,.dataTable tbody tr td input[type='checkbox']", function () {
    var anyBoxesChecked;
    $(".checkedMenu").remove();

    $('.dataTable input[type="checkbox"]').each(function () {
      if ($(this).is(":checked")) {
        anyBoxesChecked = true;
      }
    });

    if (anyBoxesChecked == undefined) {
      $(".checkedMenu").remove();
    } else {

      if ($("#projectsTableContainer").hasClass("openTasksTable")) {

        //appending menu		
        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="markReviewButton" name="markReviewButton" class="archive pull-right" style="background:#DA6E00 !important"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>&nbsp;<button type="button" id="markCompleteButton" name="markCompleteButton" class="createNew noExpand pull-right" style="background:#07CD00"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');

      } else if ($("#projectsTableContainer").hasClass("approvalsTable")) {
        //appending menu		
        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="deleteButton" name="deleteButton" class="archive pull-right" style="background:#ff0000 !important"><i class="fa fa-trash" aria-hidden="true"></i></button>&nbsp;<button type="button" id="kickbackButton" name="kickbackButton" class="archive pull-right" style="background:#ff0000 !important"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>&nbsp;<button type="button" id="approveButton" name="approveButton" class="createNew noExpand pull-right" style="background:#07CD00"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');

      } else if ($("#projectsTableContainer").hasClass("reviewsTable")) {
        //appending menu		
        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="approveButton" name="approveButton" class="createNew noExpand pull-right" style="background:#07CD00"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');

      }


    }

  });


});
