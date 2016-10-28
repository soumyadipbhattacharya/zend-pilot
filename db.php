<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_nehgs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
   // header('Location: https://imis.imerit.net/index_maintanace.php');
    exit;
}
?>
 