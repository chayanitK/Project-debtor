<?php
include "conn.php";

$id = 0;
if(isset($_POST['id'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
}
$tbname = "gatheringpoint";
$sql = "SELECT * FROM $tbname WHERE id=".$id;
$result = mysqli_query($conn, $sql);

$response = "<div class='container'>";
while( $row = mysqli_fetch_array($result) ){
    $id = $row['id'];
    $GatheringPoint = $row['GatheringPoint'];
    $fullname = $row["fullname"];

    $response .= "<input type='hidden' name='id' id='id' class='form-control' value='".$id."'>";

    $response .= "<div class='mb-3'>";
    $response .= "<label for='GatheringPoint' class='col-form-label'>ชื่อเต็ม</label>";
    $response .= "<input type='text' name='GatheringPoint' id='GatheringPoint' class='form-control' value='".$GatheringPoint."'>";
    $response .= "</div>";

    $response .= "<div class='mb-3'>";
    $response .= "<label for='fullname' class='col-form-label'>ชื่อเต็ม</label>";
    $response .= "<input type='text' name='fullname' id='fullname' class='form-control' value='".$fullname."'>";
    $response .= "</div>";

    $response .= "</div>";

}

echo $response;

exit;