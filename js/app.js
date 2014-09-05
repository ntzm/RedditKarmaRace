// Variables

var usernames = ["", ""];

// Functions

var updatePanel = function(uid, val) {
  var $panel = $("#user-" + uid + " > .panel");
  if ($panel.hasClass("hide")) {
    $panel.slideDown("fast");
  }
  $panel.html(val);
}

var updateUserStats = function(el) {
  var user = el.val(),
      uid  = el.parent().parent().attr("id").slice(-1),
      aid  = parseInt(uid, 10) - 1;

  if (usernames[aid] !== user) {
    updatePanel(uid, "Loading data...");
    $.ajax({
      url:  "http://www.reddit.com/user/" + user + "/about.json",
      type: "get",
      success: function(ret) {
        updatePanel(uid,
          "<p><strong>Link karma:</strong> " + ret.data.link_karma + "</p>" +
          "<p><strong>Comment karma:</strong> " + ret.data.comment_karma + "</p>"
        );
        usernames[aid] = user;
      },
      error: function(jqXHR) {
        updatePanel(uid, "Cannot retrieve user information!");
      }
    });
  } 
}

var addError = function(el, message) {
  $("#" + el).addClass("error");
  $("#" + el + " > small")
    .addClass("error")
    .html(message);
}

var clearErrors = function() {
  $("#user-1, #user-2, #amount").removeClass("error");
  $("#user-1 > small, #user-2 > small, #amount > small")
    .removeClass("error")
    .html("");
}

// Events

$("#form-main").submit(function(e) {
  e.preventDefault();

  var user1  = $("#user-1 > label > input").val(),
      user2  = $("#user-2 > label > input").val(),
      amount = $("#amount > label > input").val(),
      valid  = true;

  var type = $("#ckarma").prop("checked") ? "comment" : "link";

  clearErrors();

  if (amount % 1 !== 0 || amount === "") {
    addError("amount", "Invalid number!");
    valid = false;
  }
  if (user1 === "") {
    addError("user-1", "Required!");
    valid = false;
  }
  if (user2 === "") {
    addError("user-2", "Required!");
    valid = false;
  }

  if (valid) {

    $(".button").prop("disabled", true);

    $.ajax({
      url: "submit/",
      type: "post",
      data: {
        user1:  user1,
        user2:  user2,
        amount: amount,
        type:   type
      },
      success: function(ret) {
        $(".button").prop("disabled", false);
        switch (ret) {
          case "user 1 404":
            addError("user-1", "User does not exist!");
            break;
          case "user 2 404":
            addError("user-2", "User does not exist!");
            break;
          case "amount non numeric":
            addError("amount", "Invalid number!");
            break;
          case "amount too high":
            addError("amount", "Too high!");
            break;
          case "amount too low":
            addError("amount", "Too low!");
            break;
          default:
          $(".button").prop("disabled", true);
            window.location.replace("race/?id=" + ret);
        }
      }
    });
  }
});

$("#user-1 > label > input, #user-2 > label > input")
  .blur(function() {
    updateUserStats($(this));
  })
  .keyup(function(e) {
    if (e.keyCode === 13) {
      updateUserStats($(this));
    }
  });