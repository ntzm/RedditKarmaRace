<?php
if (file_exists("dbconnect.php")) {

  include "dbconnect.php";

  $user1  = $_POST["user1"];
  $user2  = $_POST["user2"];
  $amount = $_POST["amount"];
  $type   = $_POST["type"];

  // TODO: Check users exist
  
  if (!file_get_contents("http://www.reddit.com/user/" . $user1 . "/about.json")) {
    echo "u1404";
  } elseif (!file_get_contents("http://www.reddit.com/user/" . $user2 . "/about.json")) {
    echo "u2404";
  } elseif (!is_numeric($amount)) {
    echo "amnan";
  } elseif ($type !== "lkarma" || $type !== "ckarma") {
    echo "notype";
  } else {

    $stmt = $db->prepare(
      "INSERT INTO races (user1, user2, amount, type) VALUES (?, ?, ?, ?)"
    );

    $stmt->execute(
      array(
        $user1,
        $user2,
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