<?php
if (isset($_POST['id']) && isset($_POST['GatheringPoint']) && isset($_POST['fullname'])) {
    include('conn.php');
    $id = $_POST['id'];
    $GatheringPoint = $_POST['GatheringPoint'];
    $fullname = $_POST['fullname'];

    $sql = " UPDATE gatheringpoint SET GatheringPoint = '$GatheringPoint', fullname = '$fullname' WHERE id = '$id' ";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    if ($result) {
        echo "<script type='text/javascript'>";
        echo "window.location = 'index.php?p=GatheringPoint'; ";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>";
        echo "alert('เกิดข้อผิดพลาด ลองใหม่อีกครั้ง');";
        echo "</script>";
    }
}
