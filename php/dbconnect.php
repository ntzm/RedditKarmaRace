<?php
/**
 * php/dbconnect.php
 *
 * Sample dbconnect file
 */
defined("IN_APPLICATION") or die("Not allowed");

$db = new PDO("mysql:host=localhost;dbname=karmarace;charset=utf8", "root", "");
?>