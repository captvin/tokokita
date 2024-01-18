<?php
$servername = "db-mysql.csvdjkhcyyyp.ap-southeast-1.rds.amazonaws.com";
$username = "tokokita";
$password = "tokokita";
$dbname = "tokokita";

$mysql_conn = new mysqli($servername, $username, $password, $dbname);

if ($mysql_conn->connect_error) {
  die("Connection failed: " . $mysql_conn->connect_error);
}
