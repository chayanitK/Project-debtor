<?php
$servername = "localhost";
$user = "root";
$pass = "";
$dbname = "project";

$conn = new mysqli($servername,$user,$pass,$dbname);
mysqli_set_charset($conn, "utf8");
if(!$conn){
    die("Connect failed: ". mysqli_connect_error());
}
?>