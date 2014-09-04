<?php
/**
 * php/getracedata.php
 *
 * Gets and echos the race information from the database
 *
 * @author Nat Zimmermann <nat@natzim.com>
 */

define("IN_APPLICATION", 1);

$id = $_POST["id"];

include "dbconnect.php";

$stmt = $db->prepare("SELECT * FROM races WHERE id=?");
$stmt->execute(array($id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$result["userData"] = unserialize($result["userData"]);

echo json_encode($result);
?>