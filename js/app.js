// Variables

var usernames = ["", ""];

// Functions

var updatePanel = function(uid, val) {
  $("#user-" + uid + " > .panel").html(val);
}

var updateUserStats = function(el) {
  var user = el.val(),
    uid = el.parent().attr("id").slice(-1),
    aid = parseInt(uid, 10) - 1;

  if (usernames[aid] !== user) {
    updatePanel(uid, "Loading data...");
    $.ajax({
      url: "http://www.reddit.com/user/" + user + "/about.json",
      type: "GET",
      success: function(ret) {
        updatePanel(uid,
          "<p>Link karma: " + ret.data.link_karma + "</p>" +
          "<p>Comment karma: " + ret.data.comment_karma + "</p>"
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