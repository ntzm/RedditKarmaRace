<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/plain" rel="author" href="../humans.txt">
    <title>Reddit Karma Race</title>
    <link rel="stylesheet" href="../css/vendor/foundation.min.css">
    <script src="../js/vendor/modernizr.js"></script>
  </head>
  <body>
    <div class="row">
<?php
/**
 * race/index.php
 *
 * Gets and displays the race information from the database
 *
 * @author Nat Zimmermann <nat@natzim.com>
 */

define("IN_APPLICATION", 1);

/**
 * Retrieves and returns a users karma
 * @param  string $user A reddit username
 * @param  string $type The karma type (link|comment)
 * @return string       The amount of karma
 */
function getKarma($user, $type) {
  $tempUserData = json_decode(
    file_get_contents("http://www.reddit.com/user/" . $user . "/about.json"),
    true
  );
  return $tempUserData["data"][$type . "_karma"];
}

if (isset($_GET["id"])) {
  $id = $_GET["id"];

  include "../php/dbconnect.php";

  $stmt = $db->prepare(
      "SELECT * FROM races WHERE id=?"
    );

  $stmt->execute(
    array(
      $id
    )
  );

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $userData = unserialize($result["userData"]);

  echo "<h1>" . $userData["user1"]["name"] . " vs " .
    $userData["user2"]["name"] . "</h1>" .
  "<p>First to increase their " . $result["type"] . " karma by " .
    $result["amount"] . "</p>";

  for ($i = 1; $i < 3; $i++) {
    $userData["user" . $i]["curkarma"] = getKarma(
      $userData["user" . $i]["name"],
      $result["type"]
    );
    $userData["user" . $i]["progress"] = floor(
      ($userData["user" . $i]["curkarma"] - $userData["user" . $i]["karma"]) /
      $result["amount"] * 100
    );

    $data = $userData["user" . $i];

    echo "<h3>" . $data["name"] . "</h3>" .
    "<div class='row'>" .
      "<div class='left'>" . $data["karma"] . "</div>" . 
      "<div class='right'>" . ($data["karma"] + $result["amount"]) . "</div>" . 
    "</div>" .
    "<div class='progress'>" .
      "<span class='meter' style='width:" . $data["progress"] . "%'></span>" .
    "</div>";
  }
} else {
  echo "Not allowed";
}
?>
    </div>

    <script src="../js/vendor/jquery.js"></script>
    <script src="../js/vendor/foundation.min.js"></script>
  </body>
</html>