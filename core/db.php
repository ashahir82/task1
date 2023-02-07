<?php
$servername = "localhost";
$username = "root";
$password = "";
$table = "task1";

$conn = mysqli_connect($servername, $username, $password, $table);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>