// JavaScript Document

$(document).ready(function () {
  var lastColumn = parseInt($("#projectsTableContainer:visible table tr th").length - 1);
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
          [orderByColumn, "DESC"]
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

    $("#projectsTableContainer:visible table tbody").remove();
    if (entries !== null) {


      $("#projectsTableContainer:visible table thead").after('<tbody>' + entries.join("") + '</tbody>');

      reload(pageLength);
    } else {

      if (entryID.constructor.name == "Array") {
        $.each(entryID, function (index, value) {
          $("tr[userid='" + value + "'],tr[reviewid='" + entryID + "'],tr[groupid='" + entryID + "']").remove();
        });

      } else {
        $("tr[userid='" + entryID + "'],tr[reviewid='" + entryID + "'],tr[groupid='" + entryID + "']").remove();
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

        $("#projectsTableContainer:visible").fadeIn();

        var entries = result.printBack;

        if (!entries) {
          $("#projectsTableContainer:visible table thead").after('<tbody><tr><td colspan="7" class="text-center">No data to show.</td></tr></tbody>');
        } else {
          $("#projectsTableContainer:visible table thead").after('<tbody>' + entries.join("") + '</tbody>');


          $('#printBack').DataTable({
            'destroy': true,
            "order": [
              [orderByColumn, "desc"]
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
  $(document).on("click", ".projectsTable:visible thead tr th:last-child", function () {

    var thisTable = $(this).parent().parent().parent();
    $(thisTable).toggleClass("checkAllBoxes");

    if ($(thisTable).hasClass("checkAllBoxes")) {
      $(thisTable).find("input[type='checkbox']:visible").prop('checked', true);
    } else {
      $(thisTable).find("input[type='checkbox']").prop('checked', false);
    }

  });

  //close menu/remove
  $(document).on("click", "#closeMenu", function () {
    $("#projectsTableContainer:visible tbody tr").removeClass("selected");
    $(".selectedMenu,#moreInfo").remove();
  });

  //close more info/remove
  $(document).on("click", "#closeMoreInfo", function () {
    $("#projectsTableContainer:visible tbody tr").removeClass("selected");
    $(".selectedMenu,#moreInfo").remove();
  });

  //if any checkbox is checked show buttons
  $(document).on("click", ".projectsTable:visible thead tr th:last-child,.projectsTable:visible tbody tr td input[type='checkbox']", function () {
    var anyBoxesChecked;
    $(".checkedMenu").remove();

    $('.projectsTable:visible input[type="checkbox"]').each(function () {
      if ($(this).is(":checked")) {
        anyBoxesChecked = true;
      }
    });

    if (anyBoxesChecked == undefined) {
      $(".checkedMenu").remove();
    } else {

      if ($("#projectsTableContainer:visible").hasClass("activitiesSettingsTable")) {

        //appending menu		
        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="delete-btn2" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button></div>');

      } else if ($("#projectsTableContainer:visible").hasClass("projectsSettingsTable")) {
        //appending menu		
        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="delete-btn2" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button>&nbsp;<button type="button" id="archive-btn2" name="archive" class="archive pull-right"><i class="fa fa-archive" aria-hidden="true"></i></button>&nbsp;<button type="button" id="reactivate-btn2" name="reactivate" class="createNew noExpand pull-right"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');

      } else if ($("#projectsTableContainer:visible").hasClass("usersSettingsTable")) {
        //appending menu		
        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="delete-btn2" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button>&nbsp;<button type="button" id="archive-btn2" name="archive" class="archive pull-right"><i class="fa fa-archive" aria-hidden="true"></i></button>&nbsp;<button type="button" id="reactivate-btn2" name="reactivate" class="createNew noExpand pull-right"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');

      } else if ($("#projectsTableContainer:visible").hasClass("reviewsSettingsTable")) {


        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="delete-btn2" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button>&nbsp;<button type="button" id="archive-btn2" name="markNotApproved" class="archive pull-right"><i class="fa fa-times" aria-hidden="true"></i></button>&nbsp;<button type="button" id="reactivate-btn2" name="approve" class="createNew noExpand pull-right"><i class="fa fa-check" aria-hidden="true"></i></button>	</div>');
      } else if ($("#projectsTableContainer:visible").hasClass("groupSettingsTable")) {


        $("#printBack_filter").before('<div class="checkedMenu"><button type="button" id="delete-btn2" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button>&nbsp;</div>');
      }


    }

  });


});
