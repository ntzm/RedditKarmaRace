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

define("IN_APPLICATION", 1);

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

include "../php/dbconnect.php";

if (isset($_POST["user1"], $_POST["user2"], $_POST["amount"], $_POST["type"])) {

  $amount = $_POST["amount"];
  $type   = $_POST["type"];

   if ($type !== "link" && $type !== "comment") {
     $type = "link";
  }

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
  } elseif ($amount < 1) {
    echo "amount too low";
  } else {

    $userData = [
      "user1" => [
        "name"  => $tempUserData[0]["data"]["data"]["name"],
        "karma" => $tempUserData[0]["data"]["data"][$type . "_karma"]
      ],
      "user2" => [
        "name"  => $tempUserData[1]["data"]["data"]["name"],
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
  echo("Not allowed");
}
?>