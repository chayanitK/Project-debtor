<?php include "conn.php";
if ($_GET["OrderNumber"] == '') {
}
$id = mysqli_real_escape_string($conn, $_GET['OrderNumber']);
$delsummary = "DELETE FROM summaryofdebtors WHERE OrderNumber='$id' ";
$result = mysqli_query($conn, $delsummary);

$delhistory = "DELETE FROM history WHERE OrderNumber='$id' ";
$result1 = mysqli_query($conn, $delhistory);

if ($result1) {
	echo "<script type='text/javascript'>";
	echo "window.location = 'index.php?p=home'; ";
	echo "</script>";
} else {
	echo "<script type='text/javascript'>";
	echo "alert('เกิดข้อผิดพลาด ลองใหม่อีกครั้ง');";
	echo "</script>";
}
