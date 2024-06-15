<?php
if (isset($_POST['GatheringPoint']) && isset($_POST['fullname'])) {
    include 'conn.php';
    $GatheringPoint = $_POST['GatheringPoint'];
    $fullname = $_POST['fullname'];

    $tbname = "gatheringpoint";

    $sql = "INSERT INTO $tbname (id, GatheringPoint, fullname) VALUES ('', '$GatheringPoint', '$fullname')";
    $result1 = mysqli_query($conn, $sql);
    mysqli_close($conn);

    if ($result1) {
        echo "<script type='text/javascript'>";
        echo "window.location = 'index.php?p=GatheringPoint'; ";
        echo "</script>";
    } else {
    }
}
