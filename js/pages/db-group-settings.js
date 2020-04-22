// JavaScript Document

$(document).ready(function () {

  function reload(pageLength) {
    var lastColumn = parseInt($(".dataTable:visible tr th").length - 1);
    var orderByColumn = parseInt($('.dataTable #orderBy').prevAll().length);


    pageLength = parseInt(pageLength);
    if (!pageLength) {
      return false;
    }
    //$('.dataTable:visible').hide();
    $('.dataTable:visible').DataTable().destroy();
    setTimeout(function () {

      $('.dataTable:visible').DataTable({
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
      $(".dataTable:visible.dataTables_length").appendTo(".dataTable:visible .dataTables_wrapper:visible");
      $(".dataTable:visible #orderBy").addClass("sorting_desc");
      $('.dataTable:visible').show();
    }, 10);


  }
  window.reloadSuccess = function (entries, entryID) {

    var pageLength = $(".dataTable:visible .dataTables_length:visible select").children("option:selected").val();

    $(".dataTable:visible tbody").remove();
    if (entries !== null) {


      $(".dataTable:visible thead").after('<tbody>' + entries.join("") + '</tbody>');

      reload(pageLength);
    } else {

      if (entryID.constructor.name == "Array") {
        $.each(entryID, function (index, value) {
          $("tr[eventid='" + value + "'],tr[reviewid='" + entryID + "'],tr[groupid='" + entryID + "']").remove();
        });

      } else {
        $("tr[userid='" + entryID + "'],tr[reviewid='" + entryID + "'],tr[groupid='" + entryID + "']").remove();
      }


      $(".dataTable:visible .dataTables_info").html("Showing 0 to 0 entries");
    }
    $(".dataTable tbody tr td:contains('Other')").parent().addClass("denied");
  }

  function loadAllData() {

    var dataString = {
      'type': "loadAllData"
    };

    $.ajax({
      type: "POST",
      url: "process.php",
      data: dataString,
      cache: false,
      success: function (result) {
        var lastColumn;
        var orderByColumn;
        if (result.groups) {

          lastColumn = parseInt($(".groupSettingsTable:visible table tr th").length - 1);
          orderByColumn = parseInt($('.groupSettingsTable #orderBy').prevAll().length);

          $(".groupSettingsTable:visible").fadeIn();
          var entries = result.groups;

          if (!entries) {
            $(".groupSettingsTable:visible table thead").after('<tbody><tr><td colspan="5" class="text-center">No data to show.</td></tr></tbody>');
          } else {
            $(".groupSettingsTable:visible table thead").after('<tbody>' + entries.join("") + '</tbody>');


            $('.groupSettingsTable .dataTable:visible').DataTable({
              'destroy': true,
              "order": [
                [orderByColumn, "desc"]
              ],
              "columnDefs": [{
                "orderable": false,
                "targets": lastColumn
              }]
            });

            $(".groupSettingsTable .dataTables_length").appendTo(".groupSettingsTable .dataTables_wrapper:visible");
          }
        }

        if (result.events) {

          lastColumn = parseInt($(".calendarEventsSettingsTable table tr th").length - 1);
          orderByColumn = parseInt($('.calendarEventsSettingsTable #orderBy').prevAll().length);

          $(".calendarEventsSettingsTable").fadeIn();
          var entries = result.events;

          if (!entries) {
            $(".calendarEventsSettingsTable table thead").after('<tbody><tr><td colspan="5" class="text-center">No data to show.</td></tr></tbody>');
          } else {
            $(".calendarEventsSettingsTable table thead").after('<tbody>' + entries.join("") + '</tbody>');


            $('.calendarEventsSettingsTable .dataTable').DataTable({
              'destroy': true,
              "order": [
                [orderByColumn, "desc"]
              ],
              "columnDefs": [{
                "orderable": false,
                "targets": lastColumn
              }]
            });

            $(".calendarEventsSettingsTable .dataTables_length").appendTo(".calendarEventsSettingsTable .dataTables_wrapper");
          }
        }

        if (result.KC) {

          lastColumn = parseInt($(".KCSettingsTable table tr th").length - 1);
          orderByColumn = parseInt($('.KCSettingsTable #orderBy').prevAll().length);

          $(".KCSettingsTable").fadeIn();
          var entries = result.KC;

          if (!entries) {
            $(".KCSettingsTable table thead").after('<tbody><tr><td colspan="5" class="text-center">No data to show.</td></tr></tbody>');
          } else {
            $(".KCSettingsTable table thead").after('<tbody>' + entries.join("") + '</tbody>');


            $('.KCSettingsTable .dataTable').DataTable({
              'destroy': true,
              "order": [
                [orderByColumn, "desc"]
              ],
              "columnDefs": [{
                "orderable": false,
                "targets": lastColumn
              }]
            });

            $(".KCSettingsTable .dataTables_length").appendTo(".KCSettingsTable .dataTables_wrapper");
          }
        }


      },
      error: function (result) {
        alert("Error.");
      }
    });

    $(".dataTable tbody tr td:contains('Other')").parent().addClass("denied");
  }

  function loadTaskTypeData(groupID) {


    $('.dataTable:visible').DataTable().destroy();

    $(".dataTable:visible tbody").remove();

    var dataString = {
      'type': "loadTaskTypeData",
      'groupID': groupID
    };

    $.ajax({
      type: "POST",
      url: "process.php",
      data: dataString,
      cache: false,
      success: function (result) {
        $("#teamID").val(groupID);

        var lastColumn;
        var orderByColumn;
        if (result.printBack) {
          $(".dataTable:visible tbody").remove();
          lastColumn = parseInt($(".taskTypeSettingsTable table tr th").length - 1);
          orderByColumn = parseInt($('.taskTypeSettingsTable #orderBy').prevAll().length);

          $(".taskTypeSettingsTable").fadeIn();
          var entries = result.printBack;

          if (!entries) {
            $(".taskTypeSettingsTable table thead").after('<tbody><tr><td colspan="3" class="text-center">No data to show.</td></tr></tbody>');
          } else {
            $(".taskTypeSettingsTable table thead").after('<tbody>' + entries.join("") + '</tbody>');


            $('.taskTypeSettingsTable .dataTable').DataTable({
              'destroy': true,
              "order": [
                [orderByColumn, "desc"]
              ],
              "columnDefs": [{
                "orderable": false,
                "targets": lastColumn
              }]
            });

            $(".taskTypeSettingsTable .dataTables_length").appendTo(".taskTypeSettingsTable .dataTables_wrapper");

          }
        }


      },
      error: function (result) {
        alert("Error.");
      }
    });
    setTimeout(function () {
      $(".dataTable tbody tr td:contains('Other')").parent().addClass("denied");
      $(".taskTypeSettingsTable table").addClass("dataTable");
    }, 20);
  }

  function loadProjectTypeData(groupID) {

    $('.dataTable:visible').DataTable().destroy();

    $(".dataTable:visible tbody").remove();

    var dataString = {
      'type': "loadProjectTypeData",
      'groupID': groupID
    };

    $.ajax({
      type: "POST",
      url: "process.php",
      data: dataString,
      cache: false,
      success: function (result) {
        $("#teamIDProjects").val(groupID);

        var lastColumn;
        var orderByColumn;
        if (result.printBack) {

          lastColumn = parseInt($(".projectTypeSettingsTable table tr th").length - 1);
          orderByColumn = parseInt($('.projectTypeSettingsTable #orderBy').prevAll().length);

          $(".projectTypeSettingsTable").fadeIn();
          var entries = result.printBack;

          if (!entries) {
            $(".projectTypeSettingsTable table thead").after('<tbody><tr><td colspan="3" class="text-center">No data to show.</td></tr></tbody>');
          } else {
            $(".projectTypeSettingsTable table thead").after('<tbody>' + entries.join("") + '</tbody>');


            $('.projectTypeSettingsTable .dataTable').DataTable({
              'destroy': true,
              "order": [
                [orderByColumn, "desc"]
              ],
              "columnDefs": [{
                "orderable": false,
                "targets": lastColumn
              }]
            });

            $(".projectTypeSettingsTable .dataTables_length").appendTo(".projectTypeSettingsTable .dataTables_wrapper");
          }
        }


      },
      error: function (result) {
        alert("Error.");
      }
    });

    setTimeout(function () {
      $(".dataTable tbody tr td:contains('Other')").parent().addClass("denied");
      $(".projectTypeSettingsTable table").addClass("dataTable");
    }, 20);
  }

  loadAllData();

  //on click row, show menu
  $(document).on("click", ".dataTable:visible tbody tr:not(:contains('Other')) td:not(:last-child)", function (e) {

    // Remove any old one
    $(".ripple,.selectedMenu,#moreInfo").remove();
    //removing class selected
    $(".table-responsive:visible tbody tr").removeClass("selected");

    // Setup
    var posX = $(this).offset().left,
      posY = $(this).offset().top,
      buttonWidth = 50,
      buttonHeight = 50;

    var parentOffset = $(".table-responsive:visible").offset();
    var yPos = ((e.pageY - parentOffset.top - 17) / $('.table-responsive:visible').height()) * 100;
    var xPos = ((e.pageX - parentOffset.left - 23) / $('.table-responsive:visible').width()) * 100;

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

    //appending menu		
    if ($('.groupSettingsTable').is(':visible')) {
      $(".table-responsive:visible").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewGroup">View <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="changeGroupName" class="hasSecondaryMenu">Change Name</li><li id="changeGroupNameSecondaryMenu" controller="changeGroupName" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="changeGroupColor" class="hasSecondaryMenu">Change Color</li><li id="changeGroupColorSecondaryMenu" controller="changeGroupColor" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="deleteGroup">Delete</li></ul></div>');
    } else if ($('.calendarEventsSettingsTable').is(':visible')) {
      $(".table-responsive:visible").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="changeEventName" class="hasSecondaryMenu">Change Name</li><li id="changeEventNameSecondaryMenu" controller="changeEventName" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="changeEventColor" class="hasSecondaryMenu">Change Color</li><li id="changeEventColorSecondaryMenu" controller="changeEventColor" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="deleteEvent">Delete</li></ul></div>');
    } else if ($('.taskTypeSettingsTable').is(':visible')) {
      $(".table-responsive:visible").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="changeTaskTypeName" class="hasSecondaryMenu">Change Name</li><li id="changeTaskTypeNameSecondaryMenu" controller="changeTaskTypeName" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="deleteTaskType">Delete</li></ul></div>');
    } else if ($('.KCSettingsTable').is(':visible')) {
      $(".table-responsive:visible").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="viewKC">View <i class="fa fa-external-link" aria-hidden="true"></i></li><li id="changeKCName" class="hasSecondaryMenu">Change Name</li><li id="changeKCSecondaryMenu" controller="changeKCName" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="deleteKC">Delete</li></ul></div>');
    } else if ($('.projectTypeSettingsTable').is(':visible')) {
      $(".table-responsive:visible").append('<div class="selectedMenu"><div class="heading"><h3>Actions</h3><div id="closeMenu"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div></div><ul><li id="changeProjectTypeName" class="hasSecondaryMenu">Change Name</li><li id="changeProjectTypeNameSecondaryMenu" controller="changeProjectTypeName" class="secondaryMenu"><input type="text"><button class="genericbtn">Save</button></li><li id="deleteProjectType">Delete</li></ul></div>');
    }


    $(".selectedMenu").css({
      top: yPos + '%',
      left: xPos + '%'
    });

  });

  //on selectedMenu items/opening secondary menu
  $(document).on("click", ".selectedMenu li", function () {

    if ($(this).hasClass("hasSecondaryMenu")) {

      var controllerID = $(this).attr("id");
      var thisMenu = $("li[controller='" + controllerID + "']");

      $("li[controller='" + controllerID + "']").toggle().toggleClass("activeMenu");

    }

    var selectedRow = $(".table-responsive:visible tbody tr.selected");

    var type = $(this).attr("id");
    if (type === "deleteGroup") {

      var groupID = $(".table-responsive:visible tbody tr.selected").attr("groupID");
      var groupName = $(".table-responsive:visible tbody tr.selected td:first").text();

      $.alertable.confirm('Are you sure? This cannot be undone!').then(function () {
        //closing menu
        $("#closeMenu").trigger("click");

        var dataString = {
          'type': type,
          'groupID': groupID
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            var entries = result.printBack;
            reloadSuccess(entries, groupID);
          },
          error: function (result) {
            alert("Error.");
          }
        });

      });
    } else if (type === "deleteEvent") {
      var eventID = $(".table-responsive:visible tbody tr.selected").attr("eventID");

      $.alertable.confirm('Are you sure? This cannot be undone!').then(function () {
        //closing menu
        $("#closeMenu").trigger("click");

        var dataString = {
          'type': type,
          'eventID': eventID
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            var entries = result.printBack;
            reloadSuccess(entries, eventID);
          },
          error: function (result) {
            alert("Error.");
          }
        });

      });
    } else if (type === "deleteTaskType") {
      var taskID = $(".table-responsive:visible tbody tr.selected").attr("taskID");
      var groupID = $("#teamID").find(":selected").val();

      $.alertable.confirm('Are you sure? This cannot be undone!').then(function () {
        //closing menu
        $("#closeMenu").trigger("click");

        var dataString = {
          'type': type,
          'id': taskID,
          'groupID': groupID
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {

            loadTaskTypeData(groupID);
            $(".createNewContainer input").val("");
          },
          error: function (result) {
            alert("Error.");
          }
        });

      });
    } else if (type === "deleteKC") {
      var ID = $(".table-responsive:visible tbody tr.selected").attr("KCID");

      $.alertable.confirm('Are you sure? This cannot be undone!').then(function () {
        //closing menu
        $("#closeMenu").trigger("click");

        var dataString = {
          'type': type,
          'ID': ID
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            var entries = result.printBack;

            reloadSuccess(entries, ID);
          },
          error: function (result) {
            alert("Error.");
          }
        });

      });
    } else if (type === "deleteProjectType") {
      var ID = $(".table-responsive:visible tbody tr.selected").attr("projectID");
      var groupID = $("#teamIDProjects").find(":selected").val();


      $.alertable.confirm('Are you sure? This cannot be undone!').then(function () {
        //closing menu
        $("#closeMenu").trigger("click");


        var dataString = {
          'type': type,
          'id': ID,
          'groupID': groupID
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {

            loadProjectTypeData(groupID);
            $(".createNewContainer input").val("");
          },
          error: function (result) {
            alert("Error.");
          }
        });

      });
    } else if (type.indexOf("Name") != -1) {
      var controllerID = $(this).attr("id");

      var currentVal = selectedRow.find("td:first").text();
      var thisMenusInput = $("li[controller='" + controllerID + "']").find("input");
      $(thisMenusInput).val(currentVal);

    } else if (type.indexOf("Color") != -1) {
      var controllerID = $(this).attr("id");

      var currentVal = selectedRow.find("td:nth-child(2)").text();
      var thisMenusInput = $("li[controller='" + controllerID + "']").find("input");
      $(thisMenusInput).val(currentVal);

    }


    //if view more is selected
    else if (type === "viewGroup") {
      var groupName = $(".table-responsive:visible tbody tr.selected td:first").text();
      window.open('/dashboard/users/teams/?team=' + groupName, '_blank');

    } else if (type === "viewKC") {
      var KCName = $(".table-responsive:visible tbody tr.selected td:first").text();
      window.open('/dashboard/knowledge-center/category/?cat=' + KCName, '_blank');

    } else {
      return false;
    }


  });

  //check all
  $(document).on("click", ".dataTable:visible thead tr th:last-child", function () {

    var thisTable = $(this).parent().parent().parent();
    $(thisTable).toggleClass("checkAllBoxes");

    if ($(thisTable).hasClass("checkAllBoxes")) {
      $(thisTable).find("input[type='checkbox']:visible").prop('checked', true);
    } else {
      $(thisTable).find("input[type='checkbox']").prop('checked', false);
    }

  });

  //create new slider
  $('.showCreateNew').click(function () {
    $(this).next().next().slideToggle();
  });

  //close menu/remove
  $(document).on("click", "#closeMenu", function () {
    $(".table-responsive:visible tbody tr").removeClass("selected");
    $(".selectedMenu,#moreInfo").remove();
  });

  //close more info/remove
  $(document).on("click", "#closeMoreInfo", function () {
    $(".table-responsive:visible tbody tr").removeClass("selected");
    $(".selectedMenu,#moreInfo").remove();
  });

  //if any checkbox is checked show buttons
  $(document).on("click", ".dataTable:visible thead tr th:last-child,.dataTable:visible tbody tr td input[type='checkbox']", function () {
    var anyBoxesChecked;
    $(".checkedMenu").remove();

    $('.dataTable:visible input[type="checkbox"]').each(function () {
      if ($(this).is(":checked")) {
        anyBoxesChecked = true;
      }
    });

    if (anyBoxesChecked == undefined) {
      $(".checkedMenu").remove();
    } else {

      if ($('.groupSettingsTable').is(':visible')) {

        //appending menu		
        $(".table-responsive:visible .dataTables_filter").before('<div class="checkedMenu"><button type="button" id="deleteBulkGroups" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button></div>');

      }

      if ($('.calendarEventsSettingsTable').is(':visible')) {

        //appending menu		
        $(".table-responsive:visible .dataTables_filter").before('<div class="checkedMenu"><button type="button" id="deleteBulkEvents" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button></div>');

      }

      if ($('.taskTypeSettingsTable').is(':visible')) {

        //appending menu		
        $(".table-responsive:visible .dataTables_filter").before('<div class="checkedMenu"><button type="button" id="deleteBulkTaskTypes" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button></div>');

      }

      if ($('.KCSettingsTable').is(':visible')) {

        //appending menu		
        $(".table-responsive:visible .dataTables_filter").before('<div class="checkedMenu"><button type="button" id="deleteBulkKCs" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button></div>');

      }

      if ($('.projectTypeSettingsTable').is(':visible')) {

        //appending menu		
        $(".table-responsive:visible .dataTables_filter").before('<div class="checkedMenu"><button type="button" id="deleteBulkProjectTypes" name="delete" class="remove pull-right"><i class="fa fa-trash" aria-hidden="true"></i></button></div>');

      }


    }

  });


  //clicking secondary button save
  $(document).on("click", ".secondaryMenu button", function () {
    var type = $(this).parent().attr("controller");
    var selectedRow = $(".table-responsive:visible tbody tr.selected");
    var thisMenusInput = $("li[controller='" + type + "']").find("input");
    var thisMenusSelect = $("li[controller='" + type + "']").find("select");
    var newVal = $(thisMenusInput).val();


    if ($('.groupSettingsTable').is(':visible')) {
      var groupID = $(".table-responsive:visible tbody tr.selected").attr("groupID");

      $.alertable.confirm('Are you sure?').then(function () {
        $("#closeMenu").trigger("click");

        var dataString = {
          'type': type,
          'groupID': groupID,
          'newVal': newVal
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            if (result.message) {
              alert(result.message);
            }
            var entries = result.printBack;
            reloadSuccess(entries);
          },
          error: function (result) {
            alert("Error.");
          }
        });
      })
    }

    if ($('.calendarEventsSettingsTable').is(':visible')) {
      var id = $(".table-responsive:visible tbody tr.selected").attr("eventID");


      $.alertable.confirm('Are you sure?').then(function () {
        $("#closeMenu").trigger("click");


        var dataString = {
          'type': type,
          'id': id,
          'newVal': newVal
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            if (result.message) {
              alert(result.message);
            }
            var entries = result.printBack;
            reloadSuccess(entries);
          },
          error: function (result) {
            alert("Error.");
          }
        });
      })
    }

    if ($('.taskTypeSettingsTable').is(':visible')) {
      var id = $(".table-responsive:visible tbody tr.selected").attr("taskID");
      var groupID = $("#teamID").find(":selected").val();

      $.alertable.confirm('Are you sure?').then(function () {
        $("#closeMenu").trigger("click");


        var dataString = {
          'type': type,
          'groupID': groupID,
          'id': id,
          'newVal': newVal
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            if (result.message) {
              alert(result.message);
            }
            loadTaskTypeData(groupID);
            $(".createNewContainer input").val("");
          },
          error: function (result) {
            alert("Error.");
          }
        });
      })
    }

    if ($('.KCSettingsTable').is(':visible')) {
      var id = $(".table-responsive:visible tbody tr.selected").attr("KCID");


      $.alertable.confirm('Are you sure?').then(function () {
        $("#closeMenu").trigger("click");


        var dataString = {
          'type': type,
          'id': id,
          'newVal': newVal
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            if (result.message) {
              alert(result.message);
            }
            var entries = result.printBack;
            reloadSuccess(entries);
          },
          error: function (result) {
            alert("Error.");
          }
        });
      })
    }

    if ($('.projectTypeSettingsTable').is(':visible')) {
      var id = $(".table-responsive:visible tbody tr.selected").attr("projectID");
      var groupID = $("#teamIDProjects").find(":selected").val();

      $.alertable.confirm('Are you sure?').then(function () {
        $("#closeMenu").trigger("click");


        var dataString = {
          'type': type,
          'groupID': groupID,
          'id': id,
          'newVal': newVal
        };

        $.ajax({
          type: "POST",
          url: "process.php",
          data: dataString,
          cache: false,
          success: function (result) {
            if (result.message) {
              alert(result.message);
            }
            loadProjectTypeData(groupID);
            $(".createNewContainer input").val("");
          },
          error: function (result) {
            alert("Error.");
          }
        });
      })
    }


  });


  //create new Group 
  $(document).on('click', '#addNewGroup-btn', function () {
    var groupName = $("#groupTitle").val();
    var groupColor = $("#groupColor").val();

    if (!groupColor || !groupName) {

      return false;
    }

    $.ajax({
      url: 'process.php',
      data: 'type=addGroup&groupName=' + groupName + '&groupColor=' + groupColor,
      type: 'POST',
      dataType: 'json',
      success: function (result) {

        if (result.message) {
          alert(result.message);
        }
        var entries = result.printBack;
        reloadSuccess(entries);

        $(".createNewContainer input").val("");
      }
    });


  });

  //create new Event 
  $(document).on('click', '#addNewEvent-btn', function () {
    var eventName = $("#eventTitle").val();
    var eventColor = $("#eventColor").val();

    if (!eventColor || !eventName) {

      return false;
    }

    $.ajax({
      url: 'process.php',
      data: 'type=addEvent&eventName=' + eventName + '&eventColor=' + eventColor,
      type: 'POST',
      dataType: 'json',
      success: function (result) {

        if (result.message) {
          alert(result.message);
        }
        var entries = result.printBack;
        reloadSuccess(entries);

        $(".createNewContainer input").val("");
      }
    });


  });

  //create new task type 
  $(document).on('click', '#addNewTaskType-btn', function () {
    var name = $("#taskTitle").val();
    var groupID = $("#teamID").find(":selected").val();

    if (!name || !groupID) {

      return false;
    }

    $.ajax({
      url: 'process.php',
      data: 'type=addTaskType&name=' + name + '&id=' + groupID,
      type: 'POST',
      dataType: 'json',
      success: function (result) {

        if (result.message) {
          alert(result.message);
        }

        loadTaskTypeData(groupID);
        $(".createNewContainer input").val("");
      }
    });


  });

  //create new KC 
  $(document).on('click', '#addNewKC-btn', function () {
    var title = $("#KCTitle").val();

    if (!title) {

      return false;
    }


    $.ajax({
      url: 'process.php',
      data: 'type=addKC&title=' + title,
      type: 'POST',
      dataType: 'json',
      success: function (result) {

        if (result.message) {
          alert(result.message);
        }
        var entries = result.printBack;
        reloadSuccess(entries);

        $(".createNewContainer input").val("");
      }
    });


  });

  //create new project type 
  $(document).on('click', '#addNewProjectType-btn', function () {
    var name = $("#projectTitle").val();
    var groupID = $("#teamIDProjects").find(":selected").val();

    if (!name || !groupID) {

      return false;
    }


    $.ajax({
      url: 'process.php',
      data: 'type=addProjectType&name=' + name + '&id=' + groupID,
      type: 'POST',
      dataType: 'json',
      success: function (result) {

        if (result.message) {
          alert(result.message);
        }

        loadProjectTypeData(groupID);
        $(".createNewContainer input").val("");
      }
    });


  });

  //bulk delete groups
  $(document).on('click', '#deleteBulkGroups', function () {

    var groupIDs = [];

    $.alertable.confirm('Are you sure you want to delete? THIS CANNOT BE UNDONE!').then(function () {
      $(".dataTable:visible tbody tr td input[type='checkbox']").each(function () {
        var groupID = $(this).attr("groupID");
        if ($(this).is(':checked')) {

          groupIDs.push(groupID);
        } else {
          groupIDs = $.grep(groupIDs, function (value) {
            return value != groupID;
          });
        }

      });


      $.ajax({
        url: 'process.php',
        data: 'type=deleteGroupsMultiple&groupIDs=' + groupIDs,
        type: 'POST',
        success: function (result) {
          $(".dataTable:visible tbody").remove();
          var entries = result.printBack;
          reloadSuccess(entries, groupIDs);
        },
        error: function (result) {
          alert("Error.");
        }
      });


    });

  });

  //bulk delete events
  $(document).on('click', '#deleteBulkEvents', function () {

    var IDs = [];

    $.alertable.confirm('Are you sure you want to delete? THIS CANNOT BE UNDONE!').then(function () {
      $(".dataTable:visible tbody tr td input[type='checkbox']").each(function () {
        var ID = $(this).attr("eventID");
        if ($(this).is(':checked')) {

          IDs.push(ID);
        } else {
          IDs = $.grep(IDs, function (value) {
            return value != ID;
          });
        }

      });


      $.ajax({
        url: 'process.php',
        data: 'type=deleteEventsMultiple&IDs=' + IDs,
        type: 'POST',
        success: function (result) {
          $(".dataTable:visible tbody").remove();
          var entries = result.printBack;
          reloadSuccess(entries, IDs);
        },
        error: function (result) {
          alert("Error.");
        }
      });


    });

  });

  //bulk delete task types
  $(document).on('click', '#deleteBulkTaskTypes', function () {

    var IDs = [];
    var groupID = $("#teamID").find(":selected").val();

    $.alertable.confirm('Are you sure you want to delete? THIS CANNOT BE UNDONE!').then(function () {
      $(".dataTable:visible tbody tr td input[type='checkbox']").each(function () {
        var ID = $(this).attr("taskID");
        if ($(this).is(':checked')) {

          IDs.push(ID);
        } else {
          IDs = $.grep(IDs, function (value) {
            return value != ID;
          });
        }

      });


      $.ajax({
        url: 'process.php',
        data: 'type=deleteTaskTypesMultiple&IDs=' + IDs,
        type: 'POST',
        success: function (result) {
          $(".dataTable:visible tbody").remove();
          loadTaskTypeData(groupID);
          $(".createNewContainer input").val("");
        },
        error: function (result) {
          alert("Error.");
        }
      });


    });

  });

  //bulk delete kc
  $(document).on('click', '#deleteBulkKCs', function () {

    var IDs = [];

    $.alertable.confirm('Are you sure you want to delete? THIS CANNOT BE UNDONE!').then(function () {
      $(".dataTable:visible tbody tr td input[type='checkbox']").each(function () {
        var ID = $(this).attr("KCID");
        if ($(this).is(':checked')) {

          IDs.push(ID);
        } else {
          IDs = $.grep(IDs, function (value) {
            return value != ID;
          });
        }

      });


      $.ajax({
        url: 'process.php',
        data: 'type=deleteKCsMultiple&IDs=' + IDs,
        type: 'POST',
        success: function (result) {
          $(".dataTable:visible tbody").remove();
          var entries = result.printBack;
          reloadSuccess(entries, IDs);
        },
        error: function (result) {
          alert("Error.");
        }
      });


    });

  });

  //bulk delete project types
  $(document).on('click', '#deleteBulkProjectTypes', function () {

    var IDs = [];
    var groupID = $("#teamIDProjects").find(":selected").val();

    $.alertable.confirm('Are you sure you want to delete? THIS CANNOT BE UNDONE!').then(function () {
      $(".dataTable:visible tbody tr td input[type='checkbox']").each(function () {
        var ID = $(this).attr("projectID");

        if ($(this).is(':checked')) {

          IDs.push(ID);
        } else {
          IDs = $.grep(IDs, function (value) {
            return value != ID;
          });
        }

      });

      $.ajax({
        url: 'process.php',
        data: 'type=deleteProjectTypesMultiple&groupID=' + groupID + '&IDs=' + IDs,
        type: 'POST',
        success: function (result) {
          $(".dataTable:visible tbody").remove();
          loadProjectTypeData(groupID);
          $(".createNewContainer input").val("");
        },
        error: function (result) {
          alert("Error.");
        }
      });


    });

  });


  //TASK TYPES
  $("#teamID").on('change', function () {

    $("#taskTypes hr:first, #taskTypes .showCreateNew").fadeIn();

    var groupID = $(this).val();


    loadTaskTypeData(groupID);
  });

  //PROJCECT TYPES
  $("#teamIDProjects").on('change', function () {

    $("#projectTypes hr:first, #projectTypes .showCreateNew").fadeIn();

    var groupID = $(this).val();


    loadProjectTypeData(groupID);
  });


});
