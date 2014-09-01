var updatePanel = function(uid, val) {
  $("#user-" + uid + " > .panel").html(val);
}

$(document).ready(function() {
  $("#form-main").submit(function(e) {
    e.preventDefault();
  });
  $("#user-1 > input, #user-2 > input").blur(function() {
    var user = $(this).val(),
      // RegExp to extract the number from the id
      uid = $(this).parent().attr("id").slice(-1);

    updatePanel(uid, "Loading...");

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
          updatePanel(uid, "User does not exist");
        }
      }
    });
  });
});