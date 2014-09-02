<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

function getKarma($user, $type) {
  $thing = json_decode(file_get_contents("http://www.reddit.com/user/" . $user . "/about.json"), true);
  return $thing["data"][$type . "_karma"];
}

$id = $_GET["id"];

/**
 * As the dbconnect.php file is not included in the git repo, this warns the
 * developer that a dbconnect.php file must be created.
 */
if (file_exists("../dbconnect.php")) {
  include "../dbconnect.php";

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

  $userData["user1"]["curkarma"] = getKarma($userData["user1"]["name"], $result["type"]);
  $userData["user2"]["curkarma"] = getKarma($userData["user2"]["name"], $result["type"]);

  // Super messy code goes here

  echo "<h1>" . $userData["user1"]["name"] . " vs " . $userData["user2"]["name"] . "</h1>";

  echo "<p>First to increase their " . $result["type"] . " karma by " . $result["amount"] . "</p>";

  echo "<h3>" . $userData["user1"]["name"] . "</h3>";
  echo "<div class='row'><div class='left'>" . $userData["user1"]["karma"] . "</div><div class='right'>" . ($userData["user1"]["karma"] + $result["amount"]) . "</div></div>";
  echo "<div class='progress'><span class='meter' style='width:" . floor(($userData["user1"]["curkarma"] - $userData["user1"]["karma"]) / $result["amount"] * 100) . "%'></span></div>";

  echo "<h3>" . $userData["user2"]["name"] . "</h3>";
  echo "<div class='row'><div class='left'>" . $userData["user2"]["karma"] . "</div><div class='right'>" . ($userData["user2"]["karma"] + $result["amount"]) . "</div></div>";
  echo "<div class='progress'><span class='meter' style='width:" . floor(($userData["user2"]["curkarma"] - $userData["user2"]["karma"]) / $result["amount"] * 100) . "%'></span></div>";

} else {
  echo "Whoops! You need to make your own dbconnect.php file!";
}
?>
    </div>

    <script src="../js/vendor/jquery.js"></script>
    <script src="../js/vendor/foundation.min.js"></script>
  </body>
</html>