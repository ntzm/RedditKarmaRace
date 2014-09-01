$(document).ready(function() {
  $("#form-main").submit(function(e) {
    e.preventDefault();
  });
  $("#user-1-name, #user-2-name").blur(function() {
    var user = $(this).val(),
      uid = $(this).attr("id").replace(/[^0-9]/g,'');
    $.ajax({
      url: "http://www.reddit.com/user/" + user + "/about.json",
      type: "GET",
      dataType: "json",
      success: function(ret) {
        $("#user-" + uid + "-lkarma").html(ret.data.link_karma);
        $("#user-" + uid + "-ckarma").html(ret.data.comment_karma);
      },
      error: function(jqXHR) {
        if(jqXHR.status === 404) {
          console.log("User does not exist");
        }
      }
    });
  });
});