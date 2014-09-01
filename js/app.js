var updatePanel = function(uid, val) {
  $("#user-" + uid + " > .panel").html(val);
}

var updateUserStats = function(obj) {
  var user = obj.val(),
    // RegExp to extract the number from the id
    uid = obj.parent().attr("id").slice(-1);

  updatePanel(uid, "Loading data...");

  $.ajax({
    url: "http://www.reddit.com/user/" + user + "/about.json",
    type: "GET",
    success: function(ret) {
      updatePanel(uid,
        "<p>Link karma: " + ret.data.link_karma + "</p>" +
        "<p>Comment karma: " + ret.data.comment_karma + "</p>"
      );
    },
    error: function(jqXHR) {
      if(jqXHR.status === 404) {
        updatePanel(uid, "Whoops! User does not exist!");
      }
    }
  });
}

$("#form-main").submit(function(e) {
  e.preventDefault();
});

$("#user-1 > input, #user-2 > input")
  .blur(function() {
    updateUserStats($(this));
  })
  .keyup(function(e) {
    if (e.keyCode === 13) {
      updateUserStats($(this));
    }
  });

