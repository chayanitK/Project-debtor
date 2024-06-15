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
isset($_POST['NewOrderNumber']) ? $NewOrderNumber = $_POST['NewOrderNumber'] : $NewOrderNumber = "";
$tbAdd = "history";
$double = " SELECT NewOrderNumber FROM $tbAdd WHERE ( NewOrderNumber = '{$NewOrderNumber}' ) ";
$q = mysqli_query($conn, $double);
$f = mysqli_fetch_assoc($q);

if (!empty($f['NewOrderNumber'])) {
    echo "<font color='red'>* ข้อมูลซ้ำ กรุณากรอกข้อมูลใหม่อีกครั้ง *</font>";
    // echo "window.history.back();";
} else {
    // เช็คค่าซ้ำ

    if (isset($_POST['Status']) && $_POST['Status'] == '2') {
        if (isset($_POST['GatheringPoint']) && isset($_POST['OrderNumber']) && isset($_POST['NewOrderNumber']) && isset($_POST['OrderDate']) && isset($_POST['Due'])) {
            require_once 'conn.php';
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
                    $OrderNumber = $_POST['OrderNumber'];
                    $NewOrderNumber = $_POST['NewOrderNumber'];
                    $OrderDate = $_POST['OrderDate'];
                    $Due = $_POST['Due'];
                    $StatusID = $_POST['Status'];

                    // เพิ่มประวัติ
                    $query = "SELECT * FROM $tbAdd WHERE OrderNumber = '$OrderNumber' ORDER BY CreateDate DESC LIMIT 0,1";
                    $result1 = mysqli_query($conn, $query);
                    $row = mysqli_fetch_array($result1);
                    $StatusCount = $row['times'] + 1;

                    $Add = "INSERT INTO $tbAdd (historyID, OrderNumber, NewOrderNumber, OrderDate, Due, StatusID, times, document, CreateDate)
    VALUES ('', '$OrderNumber', '$NewOrderNumber', '$OrderDate', '$Due', '$StatusID', " . $StatusCount++ . " , '$newname', NOW())";
                    $result = mysqli_query($conn, $Add);

                    // อัพเดทสถานะ
                    $tbUpdate = "summaryofdebtors";

                    $query = "SELECT * FROM $tbUpdate INNER JOIN gatheringpoint ON (summaryofdebtors.GatheringPoint = gatheringpoint.GatheringPoint) WHERE OrderNumber = '$OrderNumber'";
                    $result1 = mysqli_query($conn, $query);
                    $row = mysqli_fetch_array($result1);
                    $StatusCount = $row['times'] + 1;
                    $fullname = $row['fullname'];

                    $Update = "UPDATE $tbUpdate SET StatusID = '$StatusID', times = " . $StatusCount++ . " , Due = '$Due', CreateDate = NOW() WHERE OrderNumber = '$OrderNumber'";
                    $result2 = mysqli_query($conn, $Update);

                    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';


                    if ($result2) {
                        echo "<script type='text/javascript'>";
                        echo "window.location = 'index.php?p=home'";
                        echo "</script>";
                    } else {
                        echo "<script type='text/javascript'>";
                        echo "alert('เกิดข้อผิดพลาด ลองใหม่อีกครั้ง');";
                        echo "</script>";
                    }
                }
            }
        }
    }

    if (isset($_POST['Status']) && ($_POST['Status'] == '3') || ($_POST['Status'] == '4') || ($_POST['Status'] == '5')) {
        if (isset($_POST['GatheringPoint']) && isset($_POST['OrderNumber']) && isset($_POST['NewOrderNumber']) && isset($_POST['OrderDate'])) {
            require 'conn.php';
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

                    $OrderNumber = $_POST['OrderNumber'];
                    $StatusID = $_POST['Status'];
                    $NewOrderNumber = $_POST['NewOrderNumber'];
                    $OrderDate = $_POST['OrderDate'];

                    if ($_POST['Status'] == '5') {
                        // เพิ่มประวัติ
                        $tbAdd = "history";
                        $query = "SELECT * FROM $tbAdd WHERE OrderNumber = '$OrderNumber' ORDER BY CreateDate DESC LIMIT 0,1";
                        $result1 = mysqli_query($conn, $query);
                        $row = mysqli_fetch_array($result1);
                        $Status = $row['StatusID'];


                        // ถ้ามีสถานะเดิมเป็นขยายเวลา
                        if ($Status == '2') {
                            $Add = "INSERT INTO $tbAdd (historyID, OrderNumber, NewOrderNumber, OrderDate, StatusID, times, document, CreateDate)
    VALUES ('', '$OrderNumber', '$NewOrderNumber', '$OrderDate', '$StatusID', '1', '$newname', NOW())";
                            $result = mysqli_query($conn, $Add);

                            // อัพเดทสถานะ
                            $tbUpdate = "summaryofdebtors";

                            $query = "SELECT * FROM $tbUpdate INNER JOIN gatheringpoint ON (summaryofdebtors.GatheringPoint = gatheringpoint.GatheringPoint) WHERE OrderNumber = '$OrderNumber'";
                            $result1 = mysqli_query($conn, $query);
                            $row = mysqli_fetch_array($result1);
                            $fullname = $row['fullname'];
                            $times = $row['times'];

                            $Update = "UPDATE $tbUpdate SET StatusID = '$StatusID', times = '1', Due = '', CreateDate = NOW() WHERE OrderNumber = '$OrderNumber'";
                            $result1 = mysqli_query($conn, $Update);
                            // ถ้ามีสถานะเดิมเป็นขยายเวลา
                        } else {
                            $Add = "INSERT INTO $tbAdd (historyID, OrderNumber, NewOrderNumber, OrderDate, StatusID, times, document, CreateDate)
    VALUES ('', '$OrderNumber', '$NewOrderNumber', '$OrderDate', '$StatusID', " . $StatusCount++ . ", '$newname', NOW())";
                            $result = mysqli_query($conn, $Add);

                            // อัพเดทสถานะ
                            $tbUpdate = "summaryofdebtors";

                            $query = "SELECT * FROM $tbUpdate INNER JOIN gatheringpoint ON (summaryofdebtors.GatheringPoint = gatheringpoint.GatheringPoint) WHERE OrderNumber = '$OrderNumber'";
                            $result1 = mysqli_query($conn, $query);
                            $row = mysqli_fetch_array($result1);
                            $fullname = $row['fullname'];

                            $StatusCount = $row['times'] + 1;

                            $Update = "UPDATE $tbUpdate SET StatusID = '$StatusID', times = " . $StatusCount . ", Due = '', CreateDate = NOW() WHERE OrderNumber = '$OrderNumber'";
                            $result1 = mysqli_query($conn, $Update);
                        }
                    } else {
                        // เพิ่มประวัติ
                        $tbAdd = "history";
                        $query = "SELECT * FROM $tbAdd WHERE OrderNumber = '$OrderNumber' ORDER BY CreateDate DESC LIMIT 0,1";
                        $result1 = mysqli_query($conn, $query);
                        $row = mysqli_fetch_array($result1);


                        $Add = "INSERT INTO $tbAdd (historyID, OrderNumber, NewOrderNumber, OrderDate, StatusID, document, CreateDate)
    VALUES ('', '$OrderNumber', '$NewOrderNumber', '$OrderDate', '$StatusID', '$newname', NOW())";
                        $result = mysqli_query($conn, $Add);

                        // อัพเดทสถานะ
                        $tbUpdate = "summaryofdebtors";

                        $query = "SELECT * FROM $tbUpdate INNER JOIN gatheringpoint ON (summaryofdebtors.GatheringPoint = gatheringpoint.GatheringPoint) WHERE OrderNumber = '$OrderNumber'";
                        $result1 = mysqli_query($conn, $query);
                        $row = mysqli_fetch_array($result1);
                        $fullname = $row['fullname'];

                        $Update = "UPDATE $tbUpdate SET StatusID = '$StatusID', Due = '', CreateDate = NOW() WHERE OrderNumber = '$OrderNumber'";
                        $result1 = mysqli_query($conn, $Update);

                        mysqli_close($conn);

                        echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
                    }


                    if ($result1) {
                        echo "<script type='text/javascript'>";
                        echo "window.location = 'index.php?p=home'";
                        echo "</script>";
                    } else {
                        echo "<script type='text/javascript'>";
                        echo "alert('เกิดข้อผิดพลาด ลองใหม่อีกครั้ง');";
                        echo "</script>";
                    }
                }
            }
        }
    }
}
