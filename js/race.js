$(document).ready(function() {
  var url = window.location.href,
      id  = url.substr(url.indexOf("?") + 1);
  $.ajax({
    url:  "../php/getracedata.php",
    type: "post",
    data: {
      id: id
    },
    success: function(ret) {
      var raceData = JSON.parse(ret);

      $("h1").html(raceData.userData.user1.name + " vs " + raceData.userData.user2.name);
      $("#info").html("First to increase their " + raceData.type + " karma by " + raceData.amount);

      for (var i = 1; i < 3; i ++) {
        var user = raceData.userData["user" + i];
        $("#users").append(
          "<div class='user" + i + "'>" +
            "<h3>" + user.name + "</h3>" +
            "<div class='row'>" +
              "<div class='left'>" + user.karma + "</div>" +
              "<div class='right'>" + ((user.karma - 0) + (raceData.amount - 0)) + "</div>" +
            "</div>" +
            "<div class='progress'>" +
              "<span class='meter'></span>" +
            "</div>" +
          "</div>"
        );
        $.ajax({
          url: "http://www.reddit.com/user/" + user.name + "/about.json",
          type: "get",
          success: function(ret) {
            var progress = (ret.data[raceData.type + "_karma"] - user.karma) / raceData.amount * 100 + "%";
            $("#user" + i + " > .progress > .meter").css("width", progress);
          }
        });
      }
    }
  });
});