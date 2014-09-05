<?php
/**
 * php/getracedata.php
 *
 * Gets and echos the race information from the database
 *
 * @author Nat Zimmermann <nat@natzim.com>
 */

define("IN_APPLICATION", 1);

isset($_POST["id"]) or die("Not allowed");

include "dbconnect.php";

$stmt = $db->prepare("SELECT * FROM races WHERE id=?");
$stmt->execute(array($_POST["id"]));
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result["userData"]) {
  $result["userData"] = unserialize($result["userData"]);
  
  echo json_encode($result);
} else {
  echo false;
}
?>