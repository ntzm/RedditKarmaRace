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
 * Check user exists
 * @param  string  $user A username
 * @return boolean       Returns true if user exists
 */
function userExists($user) {
  $url = "http://www.reddit.com/user/" . $user . "/about.json";
  $headers = get_headers($url, 1);
  return $headers[0] === "HTTP/1.1 200 OK";
}

/**
 * As the dbconnect.php file is not included in the git repo, this warns the
 * developer that a dbconnect.php file must be created.
 */
if (file_exists("dbconnect.php")) {

  include "dbconnect.php";

  $user1  = $_POST["user1"];
  $user2  = $_POST["user2"];
  $amount = $_POST["amount"];
  $type   = $_POST["type"];

  if ($type !== 1 || $type !== 2) {
    $type = 1;
  }
  
  if (!userExists($user1)) {
    echo "user 1 404";
  } elseif (!userExists($user2)) {
    echo "user 2 404";
  } elseif (!is_numeric($amount)) {
    echo "amount non numeric";
  } elseif (strlen($amount) > 10) {
    echo "amount too high";
  } else {

    $stmt = $db->prepare(
      "INSERT INTO races (
        user1,
        user2,
        amount,
        type
      ) VALUES (?, ?, ?, ?)"
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