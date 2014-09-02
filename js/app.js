// Variables

var usernames = ["", ""];

// Functions

var updatePanel = function(uid, val) {
  $("#user-" + uid + " > .panel").html(val);
}

var updateUserStats = function(el) {
  var user = el.val(),
      uid  = el.parent().attr("id").slice(-1),
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
        if(jqXHR.status === 404) {
          updatePanel(uid, "Whoops! User does not exist!");
        }
      }
    });
  } 
}

// Events

$("#form-main").submit(function(e) {
  e.preventDefault();

  var user1  = $("#user-1 > input").val(),
      user2  = $("#user-2 > input").val(),
      amount = $("#amount").val();

  if ($("#ckarma").prop("checked")) {
    var type = "comment";
  } else {
    var type = "link";
  }

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
      switch (ret) {
        case "user 1 404":
          $("#message").html("User 1 does not exist");
          break;
        case "user 2 404":
          $("#message").html("User 2 does not exist");
          break;
        case "amount non numeric":
          $("#message").html("The karma amount is not numeric");
          break;
        case "amount too high":
          $("#message").html("The karma amount is too high");
          break;
        default:
          $("#message").html("<a href='race?id=" + ret +"'>View race</a>");
      }
    }
  });
});

$("#user-1 > input, #user-2 > input")
  .blur(function() {
    updateUserStats($(this));
  })
  .keyup(function(e) {
    // If the enter key is pressed while the focus is on one of the inputs
    if (e.keyCode === 13) {
      updateUserStats($(this));
    }
  });