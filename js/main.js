//setting defaults

$(document).ready(function () {
  "use strict";

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

  //accordions
  $(document).on('click', '.accordionHeader:not(.active):not(.special)', function () {
    $(".accordionContent").slideDown();
    $(".accordionHeader").addClass("active");
  });

  $(document).on('click', '.accordionHeader.active:not(.special)', function () {
    $(".accordionContent").slideUp();
    $(".accordionHeader").removeClass("active");
  });


  //deleting comments
  $(document).on('click', '#printComments .outgoingCom .message', function () {
    $(this).next().slideToggle();
  });
  //ADDING TASK COMMENT
  $(document).on('click', '#addNewComment', function () {
    var comment = $("#newComment").val();
    var thisButton = $(this);
    if (comment === '') {
      $("#newComment").addClass("required");
      var thisIcon = $(thisButton).html();
      $(thisButton).html('<i class="fa fa-times" aria-hidden="true"></i>').addClass("failed");
      setTimeout(function () {
        $(thisButton).html(thisIcon).removeClass("failed");
      }, 1200);
      return false;
    }

    var currentButton = $(this);
    $(currentButton).addClass('waiting').html('<img src="/dashboard/images/Rolling.gif" style="width:100%;">');

    var taskID = $(this).attr("taskid");
    var dataString = {
      'type': "addComment",
      'comment': comment,
      'taskID': taskID
    };

    $.ajax({
      type: "POST",
      url: "/dashboard/team-projects/view/tasks/main.php",
      data: dataString,
      cache: false,
      success: function (result) {


        setTimeout(function () {
          $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');

        }, 500);

        setTimeout(function () {
          $(currentButton).html('<i class="fa fa-check" aria-hidden="true"></i>');
          $("#printComments").html(result.printComments);
          if (result.printComments) {
            $(".dot").show();
            setTimeout(function () {
              $('#printComments').scrollTop($('#printComments')[0].scrollHeight);
              $('#printComments').fadeIn();
            }, 100);
          }
          $("#canComment").html(result.canComment);
          $("#newComment").val("");
          $(currentButton).removeClass("waiting").html('<i class="fa fa-paper-plane" aria-hidden="true"></i>');
        }, 1000);


      }

    });


  });
  //DELETING TASK COMMENT
  $(document).on("click", ".comments td .removeNote", function () {
    // hover starts code here
    var commentID = $(this).attr("commentid");
    var taskID = $(this).attr("taskid");
    var dataString = {
      'type': "deleteComment",
      'commentID': commentID,
      'taskID': taskID
    };


    $.ajax({
      type: "POST",
      url: "/dashboard/team-projects/view/tasks/main.php",
      data: dataString,
      cache: false,
      success: function (result) {

        $("#printComments").html(result.printComments).fadeIn();
        if ($("#canComment").length > 0) {
          $("#canComment").html(result.canComment).fadeIn();
        }

      }
    });


  });


  //FORM MICROINTERACTIONS	

  //form validation errors

  //all regular non date inputs
  $(document).on('focusout', 'input.validate:not(input[type="datetime-local"])', function () {
    if (!$(this).val()) {
      var label = $(this).prev().text().replace(":*", "");
      $(this).addClass("required");
      $(this).attr("placeholder", label + " is required.");
    }
  });

  //select inputs
  $(document).on('focusout', 'select.validate', function () {
    if (!$(this).val()) {

      $(this).addClass("required");
    }
  });

  //date inputs
  $(document).on('focusout', 'input[type="datetime-local"].validate,input[type="datetime"].validate,input[type="date"].validate', function () {
    if (!$(this).val()) {
      $(this).addClass("required");

    }
  });
  //END form validation errors	

  //confirm delete button

  //small circle delete button
  //clicking not confirmed button
  $(document).on('click', '.deleteConfirm:not(.confirmed,.checkForConfirm)', function () {
    var input = $(this).prev().prev();
    if (input.is("input")) {
      $(input).css("width", "65%");
    }
    $(".switch").hide();
    $(this).prev().hide();
    $(this).html("").prepend('<span class="acceptConfirm">Confirm?</span>').append('<span class="cancelConfirm"><i class="fa fa-times" aria-hidden="true"></i></span>').addClass("checkForConfirm");
    $(".acceptConfirm,.cancelConfirm").delay(300).fadeIn();

  });

  //confirm clicked
  $(document).on('click', '.deleteConfirm .acceptConfirm', function () {
    var mainBtn = $(this).parent();
    $(mainBtn).addClass("confirmed");
    $(mainBtn).trigger('click');
    setTimeout(function () {
      $(".switch").fadeIn();
    }, 1500);
  });

  //cancel
  $(document).on('click', '.deleteConfirm .cancelConfirm', function () {
    var mainBtn = $(this).parent();
    var prevBtn = $(this).parent().prev().not(".save");

    var input = $(this).parent().prev().prev();
    if (input.is("input")) {
      $(input).css("width", "79%");
    }

    $(mainBtn).hide();
    $(mainBtn).html('<i class="fa fa-trash-o" aria-hidden="true"></i>').removeClass("checkForConfirm");
    $(".acceptConfirm,.cancelConfirm").remove();
    setTimeout(function () {
      $(prevBtn).fadeIn();
      $(mainBtn).fadeIn();
      $(".switch").delay(200).fadeIn();
    }, 200);
  });


  //END confirm delete button

  //END FORM MICROINTERACTIONS	

  //close all open modals
  $(document).on('click', '.closeOpenModals', function () {
    $('.modal').modal('hide');
  });


  //get all top nav data
  function getAllTopNavData() {
    var dataString = {
      'type': "getAll"
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/dependents/topNavData.php",
      data: dataString,
      cache: false,
      success: function (results) {


        //Quick Tasks
        if (results.todoCount > 0) {
          $(".todoCount").show();

        } else {
          $(".todoCount").hide();
        }

        $(".printTodoCount").html(results.todoCount);
        $(".printTotalCount").html(results.totalCount);
        $(".printTodoList").before("<ul id='editPriorityLevels'>" + results.printPriorityLevels + "</ul>");
        $("#filterPriorityLevels").html(results.printPriorityLevels);
        $("#printPriorityLevels").html(results.printPriorityLevels);
        $(".printTodoList").html(results.printTodoList);
        $("#newTodoItem").val("");
        //END Quick Tasks

        //Notifications
        $("#printAllNotifications").html(results.printAllNotifications);
        $("#printProjectNotifications").html(results.printProjectNotifications);
        $("#printTaskNotifications").html(results.printTaskNotifications);
        $("#printReviewNotifications").html(results.printReviewNotifications);
        $("#printCalendarNotifications").html(results.printCalendarNotifications);
        $("#printRequestNotifications").html(results.printRequestNotifications);
        //END Notifications

        //Favorites
        $(".printFavoriteCount").html(results.printFavoriteCount);
        $(".printFavoritesList").html(results.printFavoritesList);
        //END Favorites 

      },
      error: function (results) {


      }
    });
  }

  //todo list

  //getting all todo list information 
  function getTodoList() {
    var dataString = {
      'type': "getAll"
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/todolist/process.php",
      data: dataString,
      cache: false,
      success: function (results) {

        if (results.todoCount > 0) {
          $(".todoCount").show();

        } else {
          $(".todoCount").hide();
        }

        $(".printTodoCount").html(results.todoCount);
        $(".printTotalCount").html(results.totalCount);
        $(".printTodoList").before("<ul id='editPriorityLevels'>" + results.printPriorityLevels + "</ul>");
        $("#filterPriorityLevels").html(results.printPriorityLevels);
        $("#printPriorityLevels").html(results.printPriorityLevels);
        $(".printTodoList").html(results.printTodoList);
        $("#newTodoItem").val("");
      },
      error: function (results) {


      }
    });
  }

  //getting filtered todo list information 	
  function getFilteredTodoList(theID, filterType) {
    var dataString = {
      'type': "getFiltered",
      'filterType': filterType,
      'theID': theID,
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/todolist/process.php",
      data: dataString,
      cache: false,
      success: function (results) {
        $(".printTodoList").html(results.printTodoList);

      },
      error: function (results) {


      }
    });
  }


  getAllTopNavData();

  //date conversion


  //page loading 

  //disable close top av
  $('#showAddNewTodo').on('click', function (e) {
    $(".addNewTodoContainer").fadeToggle();
    e.stopPropagation();
  });

  $('#showTodoFiters').on('click', function (e) {
    $(".todoListFilterContainer").fadeToggle();
    e.stopPropagation();
  });

  $('.todoItem').on('click', function (e) {
    e.stopPropagation();
  });

  $(document).on('click', '.todoListFilterContainer', function (e) {
    e.stopPropagation();
  });

  $(document).on('click', '.todoCheck:not(.todoChecked)', function (e) {
    var todoID = $(this).parent().attr("todoID");
    var todoTitle = $(this).next();
    var dataString = {
      'type': "checkTodoItem",
      'todoID': todoID,
      'value': "yes"
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/todolist/process.php",
      data: dataString,
      cache: false,
      success: function (results) {
        $(todoTitle).addClass("strikeout");
        $(this).addClass("todoChecked");
        getTodoList();

      },
      error: function (results) {

        alert("failed");

      }
    });

    e.stopPropagation();
  });

  $(document).on('click', '.todoChecked', function (e) {
    var todoID = $(this).parent().attr("todoID");

    var dataString = {
      'type': "unCheckTodoItem",
      'todoID': todoID,
      'value': "no"
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/todolist/process.php",
      data: dataString,
      cache: false,
      success: function (results) {
        $(this).removeClass("todoChecked");
        getTodoList();

      },
      error: function (results) {

        alert("failed");

      }
    });

    e.stopPropagation();
  });

  $(document).on('click', '.todoTitle,.todoMenu', function (e) {
    e.stopPropagation();
  });

  $(document).on('click', '.todoTitle', function () {
    $(this).toggleClass("todoTitleFull");
  });

  $(document).on('click', '.editPriority', function (e) {
    $(this).parent().prepend($("#editPriorityLevels"));
    $("#editPriorityLevels").fadeToggle();
    e.stopPropagation();
  });

  $(document).on('click', '.editTodo', function (e) {
    var thisTextBox = $(this).parent().parent().prev();
    var currentVal = $(this).parent().parent().prev().text();
    var todoID = $(this).attr("todoID");

    //priority
    var thisPriority = $(this).parent().parent().prev().prev().prev();
    var currentPriority = thisPriority.attr("priorityID");
    //$('#editPriorityLevels .priorityIcon').removeClass("priorityIconBorder");


    //end priority

    //on open
    if ($(this).find("i").hasClass("fa-pencil")) {
      $("#editPriorityLevels .priorityIcon[priorityID='" + currentPriority + "']").addClass("priorityIconBorder");
      $('#editPriorityLevels .priorityIcon').not('#editPriorityLevels .priorityIcon[priorityID="' + currentPriority + '"]').removeClass("priorityIconBorder");
      thisPriority.addClass("editPriority");

    }
    //on save
    if ($(this).find("i").hasClass("fa-floppy-o")) {
      var selectedVal = $(this).find("i").parent().parent().parent().prev().find(".todoInput").val();

      //ajax call
      var priorityID = parseInt($("#editPriorityLevels .priorityIconBorder").attr("priorityid"));

      if (!selectedVal) {
        alert(selectedVal);
        $(thisTextBox).addClass("required");
        return false;
      }

      if (priorityID === undefined) {
        alert("Choose a priority level.");
        return false;
      }


      var dataString = {
        'type': "updateTodoItem",
        'todoID': todoID,
        'title': selectedVal,
        'priorityID': priorityID
      };
      $.ajax({
        type: "POST",
        url: "/dashboard/todolist/process.php",
        data: dataString,
        cache: false,
        success: function (results) {
          $("#editPriorityLevels").fadeOut();

          getTodoList();

        },
        error: function (results) {

          alert("failed");

        }
      });


    } else {


      $(this).find("i").parent().parent().parent().prev().html('<input type="text" class="todoInput" value="' + currentVal + '"></input>');
      //turning to save icon
      $(this).find("i").removeClass("fa-pencil").addClass("fa-floppy-o");

      $(this).parent().parent().prev().find(".todoInput").on('focusout', function () {

        $("#editPriorityLevels").fadeOut();
      });
    }


    e.stopPropagation();
  });

  $(document).on('click', '.deleteTodo', function (e) {
    var todoID = $(this).parent().parent().parent().attr("todoID");
    var todoTitle = $(this).parent().parent().parent().find(".todoTitle");
    var todoItem = $(this).parent().parent().parent();
    var dataString = {
      'type': "deleteTodoItem",
      'todoID': todoID
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/todolist/process.php",
      data: dataString,
      cache: false,
      success: function (results) {
        $(todoTitle).addClass("strikeout");
        $(todoItem).delay(500).fadeOut();
        setTimeout(function () {
          getTodoList();
        }, 2000);

      },
      error: function (results) {

        alert("failed");

      }
    });

    e.stopPropagation();
  });

  $(document).on('click', '.priorityIcon', function (e) {

    $(this).toggleClass("priorityIconBorder");
    $('.priorityIcon').not(this).removeClass("priorityIconBorder");
    e.stopPropagation();
  });

  $('#addNewTodoItem').on('click', function (e) {
    var value = $("#newTodoItem").val();
    var priorityID = $(".priorityIconBorder").attr("priorityID");

    if (!value) {
      $("#newTodoItem").addClass("required");
      return false;
    }

    if (priorityID === undefined) {
      alert("Choose a priority level.");
      return false;
    }

    var dataString = {
      'type': "addTodoItem",
      'title': value,
      'priorityID': priorityID
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/todolist/process.php",
      data: dataString,
      cache: false,
      success: function (results) {

        getTodoList();

      },
      error: function (results) {

        alert("failed");

      }
    });


    e.stopPropagation();
  });

  $(document).on('click', '#clearTodoList', function (e) {
    if (confirm('Are you sure? This CANNOT be undone!')) {
      var dataString = {
        'type': "deleteAll"
      };
      $.ajax({
        type: "POST",
        url: "/dashboard/todolist/process.php",
        data: dataString,
        cache: false,
        success: function (results) {
          setTimeout(function () {
            getTodoList();
          }, 100);

        },
        error: function (results) {

          alert("failed");

        }
      });

    }
    e.stopPropagation();
  });

  $(document).on('click', '#filterPriorityLevels .priorityIcon:not(.priorityIconBorder)', function (e) {

    var theID = $("#filterPriorityLevels .priorityIconBorder").attr("priorityID");
    var filterType = "priorityID";
    getFilteredTodoList(theID, filterType);

    e.stopPropagation();
  });

  $(document).on('click', '#filterPriorityLevels .priorityIconBorder', function (e) {

    getTodoList();

    e.stopPropagation();
  });

  $.expr[":"].contains = $.expr.createPseudo(function (arg) {
    return function (elem) {
      return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
  });
  //search function
  $(document).on('keyup', '#searchTodo', function () {
    var valThis = $(this).val();
    if (valThis === "") {
      $('.todoItem').fadeIn();
    }

    $('.todoItem .todoTitle:visible').each(function () {

      $('.todoItem .todoTitle:contains("' + valThis + '")').parent().fadeIn();
      $('.todoItem .todoTitle:not(:contains("' + valThis + '"))').parent().fadeOut();


    });
  });

  $(document).on('click', '#clearTodoFilter', function (e) {
    $("#searchTodo").val("");
    getTodoList();

    e.stopPropagation();
  });
  // end todo list

  //copy link functionality
  $(document).on('click', '.copyLink', function () {
    var thisLink = $(this);
    var copyText = document.getElementById("copyLinkInput");
    copyText.select();
    document.execCommand("copy");
    $(this).html('<i class="fa fa-check-circle-o" aria-hidden="true"></i> Link Copied!');
    setTimeout(function () {
      $(thisLink).html('<i class="fa fa-link" aria-hidden="true"></i> Share');
    }, 1500);
  });
  //closing alert bar
  $(document).on('click', '#alertContainer .greenAlert .fa-times,#alertContainer .redAlert .fa-times', function () {
    $(this).parent().fadeOut();
  });

  //search container
  //show search container 
  $(document).on('click', '#searchBar', function () {
    $("#searchBarInput input").fadeToggle();
    $("#searchResultsContainer").slideToggle();
  });

  $(document).on('click', '#closeTopSearchResults', function () {
    $("#searchResultsContainer").slideUp();
    $("#searchBarInput input").fadeOut();
  });


  //on third key up
  $(document).on('keyup', '#searchBarInput input', function () {


    var searchTerm = $(this).val();
    if (this.value.length > 0) {
      if ($(".searchLoading").length === 1) {
        return;
      } else {
        $("#searchResultsContainer .header").after('<div class="searchLoading"><img src="/dashboard/images/grey-spinner.gif"></div>');
      }


      $.ajax({
        type: "POST",
        url: "/dashboard/dependents/searchEverything.php",
        data: {
          'searchTerm': searchTerm
        },
        cache: false,
        success: function (results) {
          $('.searchHeader').fadeIn();
          $('.searchLoading').remove();
          if (results.printProjectResults === null) {
            $("#printProjectResults").html("<div class='noResults'>No projects matched your search.</div>");
          } else {
            $("#printProjectResults").html(results.printProjectResults);
          }

          if (results.printCalendarResults === null) {
            $("#printCalendarResults").html("<div class='noResults'>No events matched your search.</div>");
          } else {
            $("#printCalendarResults").html(results.printCalendarResults);
          }
          if (results.printKCResults === null) {
            $("#printKCResults").html("<div class='noResults'>No posts matched your search.</div>");
          } else {
            $("#printKCResults").html(results.printKCResults);
          }
          if (results.printUserResults === null) {
            $("#printUserResults").html("<div class='noResults'>No users matched your search.</div>");
          } else {
            $("#printUserResults").html(results.printUserResults);
          }


        }
      });

    } else {
      $('.searchHeader').fadeOut();
      $("#printProjectResults").html("");
      $("#printTaskResults").html("");
      $("#printNotebookResults").html("");
      $("#printNotebookPagesResults").html("");
      $("#printCalendarResults").html("");
      $("#printKCResults").html("");
      $("#printUserResults").html("");
    }

  });
  //end search container

  //adding dropdown to lhn
  $(document).on('click', '.lhnDropdown', function () {
    var controller = $(this).attr("controller");
    $("tr[controlledby='" + controller + "']").fadeToggle();
    $(this).find("td").toggleClass("hoverState");
  });


  //making header background stick for current page
  var pathname = window.location.pathname;

  $('.link a').each(function () {
    if ($(this).attr('href') === pathname) {
      $(this).parent().addClass('hoverState');

    } else if (pathname.indexOf("display.php") !== -1 && $(this).attr('href') === "/dashboard/team-projects/") {
      $(this).parent().addClass('hoverState');

    } else if (pathname.indexOf("todo") !== -1) {
      $("tr[controller='workflowDropdown']").find("td").addClass('hoverState');

    } else if (pathname.indexOf("requests") !== -1) {
      $("tr[controller='requestsDropdown']").find("td").addClass('hoverState');

    } else if (pathname.indexOf("knowledge") !== -1 && $(this).attr('href') === "/dashboard/knowledge-center/") {
      $(this).parent().addClass('hoverState');

    }


  });


  if ($(window).width() < 728) {
    $(".topNav-bar").prepend('<li class="topNav"><i class="fa fa-bars" aria-hidden="true" id="menuExpand"></i></li><li class="topNav" id="menuExtra"></li>');

    $("#printBackProjects.projectsTable tbody tr").each(function () {
      $(this).find("td:eq(0)").css("width", "50%");
      $(this).find("td:eq(1)").css("width", "50%");
      $(this).find("td:eq(2)").remove();
    });

    $(".modalHeader").prepend('<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>');
    $(".modalHeader").find("table").css("width", "95%");

    $("#projectTableMobileFix tbody tr").each(function () {
      $(this).find("td:eq(2)").remove();
    });
    $(document).on('click', '#createNewProject-btn', function () {
      $(".sorter").slideToggle();
    });
    $("#todoMobileFix,.removeOffset").removeClass("col-xs-offset-2");
    $("#hideActivityTab").parent().html('Notes');
    $("#showActivityTab").remove();
    $("#ActivityTab").parent().removeClass("removepadding_left");
    $("#ActivityTab").show();
    $("#taskSearch").prev().remove();
    $("#taskSearch").prev().remove();
  } else {

  }


  $('.descWrap').click(function () {
    $(this).toggleClass("descWrap", 500);
  });
  $(document).on('click', '.projectTitle', function () {
    $(this).toggleClass("expandedText", 300);

  });
  $("#menuExpand").on('click', function () {
    $(".navFix").slideToggle();
  });

  $(document).on('click', '.no_click', function () {
    return false;
  });

  var d = new Date();
  var month = d.getMonth() + 1;
  var day = d.getDate();
  var finalCurrentDate = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

  function getFormattedDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear().toString().slice(2);
    return day + '-' + month + '-' + year;
  }
  //detecting firefox - changing to date picker
  setTimeout(function () {
    if (navigator.userAgent.indexOf("Firefox") > 0 || /^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {

      $('input[type=datetime-local],input[type=date]').datetimepicker();
      $('input[type=datetime-local],input[type=date]').datetimepicker('setDate', (new Date()));
    } else {
      $('#addEventStartDate').val(finalCurrentDate + "T09:00");
      $('#addEventEndDate').val(finalCurrentDate + "T09:30");
      $("#projectDueDate,#taskDueDate,#reviewDueDate").val(finalCurrentDate + "T16:30");
      $("#alertDate").val(finalCurrentDate + "T17:00");
      $('.noDropdown').click(function (e) {
        e.stopPropagation(); // or return false
      });

    }
  }, 1);

});


/* Animations */
$(document).ready(function () {
  "use strict";

  $(".settingsIcon").each(function (i) {
    $(this).delay(100 * i).fadeIn(500);
  });


  $(".directoryLatestActivity").hide();
  $("#createEvent").hide();
  $("#createEventCheck").prop('checked');
  $(".viewMore").click(function () {
    $(this).parent().next().next().next(".directoryLatestActivity").slideToggle();

  });

  $('input').on('focus', function () {
    $(this).removeClass('required');
  });

  $(document).on('click', '#addMember', function () {
    $(".addMembersForm").slideDown();
  });

});

// GETTING NOTIFICATIONS
//clear notifications
$(document).ready(function () {
  "use strict";


  function getAllNotif() {
    var dataString = {
      'type': "getAll"
    };
    $.ajax({
      type: "POST",
      url: "/dashboard/notifications/process.php",
      data: dataString,
      cache: false,
      success: function (results) {

        $("#printAllNotifications").html(results.printAllNotifications);
        $("#printProjectNotifications").html(results.printProjectNotifications);
        $("#printTaskNotifications").html(results.printTaskNotifications);
        $("#printReviewNotifications").html(results.printReviewNotifications);
        $("#printCalendarNotifications").html(results.printCalendarNotifications);
        $("#printRequestNotifications").html(results.printRequestNotifications);

        if (results.printNotificationCount > 0 && results.printNotificationCount !== undefined) {
          $("#allNotif .dot").show();
          $(".notificationCount").show();
          $(".notificationCount").html(results.printNotificationCount);
          $(document).attr("title", "Dashboard (" + results.printNotificationCount + ")");
        } else {
          $(document).attr("title", "Dashboard");
          $("#allNotif .dot").hide();
          $(".notificationCount").hide();
        }

        if (results.printProjectNotificationCount > 0) {
          $("#projectNotif .dot").show();
        } else {
          $("#projectNotif .dot").hide();
        }
        if (results.printTaskNotificationCount > 0) {
          $("#taskNotif .dot").show();
        } else {
          $("#taskNotif .dot").hide();
        }
        if (results.printReviewNotificationCount > 0) {
          $("#reviewNotif .dot").show();
        } else {
          $("#reviewNotif .dot").hide();
        }
        if (results.printEventNotificationCount > 0) {
          $("#eventNotif .dot").show();
        } else {
          $("#eventNotif .dot").hide();
        }
        if (results.printRequestNotificationCount > 0) {
          $("#requestNotif .dot").show();
        } else {
          $("#requestNotif .dot").hide();
        }
        if (typeof results.startTime !== 'undefined' && parseInt(results.absentUser) > 7195 && parseInt(results.absentUser) < 10795) {
          clearInterval(getNotificationsInterval);
          window.location.href = "/dashboard/verify/";
        }

      },
      error: function (results) {


      }
    });
  }

  $("#notificationSort .formLabels").click(function (e) {
    $("#notificationSort .formLabels").removeClass("active");
    $(this).addClass("active");
    var controls = $(this).attr("controlsNotif");

    $(".printNotifications:visible").hide("slide", {
      direction: "right"
    }, 500);
    $.when($(".printNotifications:visible").hide("slide", {
      direction: "right"
    }, 300)).then(function () {
      $('#' + controls).show("slide", {
        direction: "right"
      }, 500);
    });


    e.stopPropagation();
  });

  $("#clearNotifications,#clearNotificationsMobile").click(function (e) {
    $(".notificationCount").hide();
    var whichNotif = $("#notificationSort .formLabels.active").attr("whichNotif");
    $.ajax({
      type: "POST",
      url: "/dashboard/notifications/process.php",
      data: {
        'type': "clearAll",
        'whichNotif': whichNotif
      },
      cache: false,
      success: function (result) {
        getAllNotif();
      }
    });
    $(document).attr("title", "Dashboard");
    e.stopPropagation();
  });
  $("#readNotifications,#readNotificationsMobile").click(function (e) {
    var whichNotif = $("#notificationSort .formLabels.active").attr("whichNotif");

    $.ajax({
      type: "POST",
      url: "/dashboard/notifications/process.php",
      data: {
        'type': "readAll",
        'whichNotif': whichNotif
      },
      cache: false,
      success: function (result) {
        getAllNotif();
      }
    });
    $(".notificationCount").hide();
    $(document).attr("title", "Dashboard");
    e.stopPropagation();
  });

  var getNotificationsInterval = setInterval(getAllNotif, 10000);


  //CHANGING NOTIFICATIONS TO SEEN //
  $(document).on('click', '.printNotifications div.unseen', function (e) {

    var notificationID = $(this).attr("id");
    var dataString = {
      'type': "seen",
      notificationID1: notificationID
    };

    // AJAX Code To Submit Form.
    $.ajax({
      type: "POST",
      url: "/dashboard/notifications/process.php",
      data: dataString,
      cache: false,
      success: function (result) {
        getAllNotif();
      }
    });
    e.stopPropagation();
  });

  //CHANGING ALERT TO SEEN
  $(document).on('click', '#alertContainer .fa-times', function () {
    var thisAlert = $(this).parent();
    var alertID = $(this).parent().attr("alertid");
    var dataString = {
      'type': "markAlert",
      alertID: alertID
    };

    // AJAX Code To Submit Form.
    $.ajax({
      type: "POST",
      url: "/dashboard/settings/send-alert/process.php",
      data: dataString,
      cache: false,
      success: function (result) {
        $(thisAlert).fadeOut();

      }
    });
  });

});
