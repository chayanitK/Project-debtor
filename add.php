<?php
require 'conn.php';

//แสดงปี
$year = date("Y");
function changeYear($year)
{
  $year_ = $year + 543;
  return $year_;
}
//แสดงปี


//แสดงวันที่แบบ ว ด ป
function changeDate($date)
{
  //ใช้ Function explode ในการแยกไฟล์ ออกเป็น  Array
  $get_date = explode("-", $date);
  //กำหนดชื่อเดือนใส่ตัวแปร $month
  $month = array("01" => "ม.ค.", "02" => "ก.พ.", "03" => "มี.ค.", "04" => "เม.ย.", "05" => "พ.ค.", "06" => "มิ.ย.", "07" => "ก.ค.", "08" => "ส.ค.", "09" => "ก.ย.", "10" => "ต.ค.", "11" => "พ.ย.", "12" => "ธ.ค.");
  //month
  $get_month = $get_date["1"];
  //year	
  $year = $get_date["0"] + 543;
  return $get_date["2"] . " " . $month[$get_month] . " " . $year;
}
//แสดงวันที่แบบ ว ด ป


// เช็คค่าซ้ำ
isset($_POST['OrderNumber']) ? $OrderNumber = "น.3/ส.(ลม)" . $_POST['OrderNumber'] . "/" . changeYear($year) : $OrderNumber = "";
$tbname = "summaryofdebtors";
$double = " SELECT OrderNumber FROM $tbname WHERE ( OrderNumber = '{$OrderNumber}' ) ";
$q = mysqli_query($conn, $double);
$f = mysqli_fetch_assoc($q);

if (!empty($f['OrderNumber'])) {
  echo "<font color='red'>* ข้อมูลซ้ำ กรุณากรอกข้อมูลใหม่อีกครั้ง *</font>";
} else {
  // เช็คค่าซ้ำ

  if (isset($_POST['GatheringPoint']) && isset($_POST['OrderNumber']) && isset($_POST['OrderDate']) && isset($_POST['Due']) && isset($_POST['NumOfCases']) && isset($_POST['AmountOfMoney'])) {
    $numrand = (mt_rand());
    $file = (isset($_POST['file']) ? $_POST['file'] : '');
    $upload = $_FILES['file']['name'];
    //มีการอัพโหลดไฟล์
    if ($upload != '') {
      //ตัดขื่อเอาเฉพาะนามสกุล
      $typefile = strrchr($_FILES['file']['name'], ".");
      //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
      if ($typefile == '.pdf') {
        //โฟลเดอร์ที่เก็บไฟล์
        $path = "file/";
        //ตั้งชื่อไฟล์ใหม่เป็นสุ่มตัวเลข
        $newname = 'doc' . $numrand . $typefile;
        $path_copy = $path . $newname;
        //คัดลอกไฟล์ไปยังโฟลเดอร์
        move_uploaded_file($_FILES['file']['tmp_name'], $path_copy);

        $GatheringPoint = $_POST['GatheringPoint'];
        $OrderNumber = "น.3/ส.(ลม)" . $_POST['OrderNumber'] . "/" . changeYear($year);
        $num = changeYear($year) . $_POST['OrderNumber'];
        $OrderDate = $_POST['OrderDate'];
        $Due = $_POST['Due'];
        $NumOfCases = $_POST['NumOfCases'];
        $AmountOfMoney = $_POST['AmountOfMoney'];
        $date = Date('YYYY-MM-DD H:i:s');

        $tbname = "summaryofdebtors";

        $sql = "INSERT INTO $tbname (SummaryID, GatheringPoint, OrderNumber, num, OrderDate, StatusID, Due, NumOfCases, AmountOfMoney, CreateDate)
    VALUES ('', '$GatheringPoint', '$OrderNumber', '$num', '$OrderDate', '1', '$Due', '$NumOfCases', '$AmountOfMoney', NOW())";
        $result1 = mysqli_query($conn, $sql);

        $query = "SELECT * FROM $tbname INNER JOIN gatheringpoint ON (summaryofdebtors.GatheringPoint = gatheringpoint.GatheringPoint) WHERE OrderNumber = '$OrderNumber'";
        $result2 = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result2);
        $fullname = $row['fullname'];

        $sql2 = "INSERT INTO history (historyID, OrderNumber, NewOrderNumber, OrderDate, Due, StatusID, document, CreateDate)
    VALUES ('', '$OrderNumber', '$OrderNumber', '$OrderDate', '$Due', '1', '$newname', NOW())";
        $result3 = mysqli_query($conn, $sql2);
      }
    }

    if ($result3) {
      echo "<script type='text/javascript'>";
      echo "window.location = 'index.php?p=home'; ";
      echo "</script>";
    } else {
    }
  }
}