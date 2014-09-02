<?php
/**
 * submit/index.php
 *
 * This file authenticates the input and adds it to the database, then returns
 * the id of the row in the database. This id is used in an URL to display the
 * results on a page.
 *
 * @author Nat Zimmermann <nat@natzim.com>
 */

/**
 * Retrieves user data from reddit's API
 * @param  string $username A reddit username
 * @return array            Whether the user exists and the user data
 */
function getUserData($username) {
  $url = "http://www.reddit.com/user/" . $username . "/about.json";
  return [
    "data"   => json_decode(@file_get_contents($url), true),
    "exists" => $http_response_header[0] === "HTTP/1.1 200 OK"
  ];
}

define("IN_APPLICATION", 1);

/**
 * As the dbconnect.php file is not included in the git repo, this warns the
 * developer that a dbconnect.php file must be created.
 */
if (file_exists("../dbconnect.php")) {

  include "../dbconnect.php";

  // TODO: Add isset() validation

  $amount = $_POST["amount"];
  $type   = $_POST["type"];

  // if ($type !== "link" || $type !== "comment") {
  //   $type = "link";
  // }
  
  // TODO: Find a better way of doing this

  $tempUserData = [
    getUserData($_POST["user1"]),
    getUserData($_POST["user2"])
  ];
  
  if (!$tempUserData[0]["exists"]) {
    echo "user 1 404";
  } elseif (!$tempUserData[0]["exists"]) {
    echo "user 2 404";
  } elseif (!is_numeric($amount)) {
    echo "amount non numeric";
  } elseif (strlen($amount) > 10) {
    echo "amount too high";
  } else {

    // TODO: Find a better way of doing this

    $userData = [
      $tempUserData[0]["data"]["data"]["name"] => [
        "karma" => $tempUserData[0]["data"]["data"][$type . "_karma"]
      ],
      $tempUserData[1]["data"]["data"]["name"] => [
        "karma" => $tempUserData[1]["data"]["data"][$type . "_karma"]
      ]
    ];

    $stmt = $db->prepare(
      "INSERT INTO races (
        userData,
        amount,
        type
      ) VALUES (?, ?, ?)"
    );

    $stmt->execute(
      array(
        serialize($userData),
        $amount,
        $type
      )
    );

    echo $db->lastInsertId();

  }
} else {
  echo "Whoops! You need to make your own dbconnect.php file!";
}
?>