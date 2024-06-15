<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" /> -->

<script>
  // เงื่อนไขเลือกสถานะ
  $('#Status').on('change', function() {
    // เมื่อมีการเลือก Listbox option = yes ให้ทำการ Show Element
    // ทีมี class name = ele_ref 
    // แต่ถ้าไม่ใช่ก็ให้ Hide Element นั้น
    if ($(this).val() === '3') {
      $('.ele_ref').hide();
    } else if ($(this).val() === '5') {
      $('.ele_ref').hide();
    } else {
      $('.ele_ref').show();
    }
  });

  // เช็คค่าซ้ำ
  $("#NewOrderNumber").keyup(function() {
    let NewOrderNumber = $(this).val();
    let data = {
      'NewOrderNumber': NewOrderNumber
    };
    $.ajax({
      type: 'POST',
      url: "AddStatus.php",
      data: data,
      success: function(data) {
        $("#data").html(data);
      }
    });
  });
</script>

<?php
include "conn.php";
$OrderNumber = 0;
if (isset($_POST['OrderNumber'])) {
  $OrderNumber = mysqli_real_escape_string($conn, $_POST['OrderNumber']);
}
$tbname = "summaryofdebtors";
$sql = "SELECT * FROM $tbname WHERE OrderNumber = '$OrderNumber' ";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($result)) {
  $OrderNumber = $row["OrderNumber"];
  $OrderDate = $row["OrderDate"];
  $status = $row["OrderDate"];
?>

  <div class='container'>
    <input type='hidden' name='OrderNumber' id='OrderNumber' class='form-control' value='<?php echo $row['OrderNumber'] ?>'>
    <input type='hidden' name='GatheringPoint' id='GatheringPoint' class='form-control' value='<?php echo $row['GatheringPoint'] ?>'>

    <div class='mb-3'>
      <p><label for='Status' class='col-form-label'>สถานะ</label>
        <select name='Status' id='Status' class='form-select' onchange='disableDrop(this);'>

          <?php
          $tbStatusCheck = "summaryofdebtors";
          if (($row['StatusID'] == '1') or ($row['StatusID'] == '2')) {
            $query = "SELECT * FROM $tbStatusCheck WHERE OrderNumber = '$OrderNumber' AND StatusID = '2'";
            $result1 = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result1);
            $StatusCount = $row['times'] + 1;

            $history = "SELECT * FROM history WHERE OrderNumber = '$OrderNumber' AND StatusID = '3'";
            $result2 = mysqli_query($conn, $history);
            $row = mysqli_fetch_array($result2);
            $StatusCount1 = $row['times'] + 1;

            $status = '';
            $status .= "<option>- เลือกสถานะ -</option>";
            $status .= "<option value='2'>ขยายเวลาครั้งที่ " . $StatusCount++ . "</option>";
            $status .= "<option value='3'>ผบน.รับเรื่อง อยู่ระหว่างตรวจสอบความถูกต้องครั้งที่ " . $StatusCount1++ . "</option>";
            $status .= "</select>";
            echo $status;
          } elseif ($row['StatusID'] == '3') {
            $query = "SELECT * FROM history WHERE OrderNumber = '$OrderNumber' AND StatusID = '5'";
            $result1 = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result1);
            $StatusCount = $row['times'] + 1;

            $status = '';
            $status .= "<option>- เลือกสถานะ -</option>";
            $status .= "<option value='4'>ผบน.ส่งเรื่องคืนจุดรวมงานครั้งที่ " . $StatusCount . "</option>";
            $status .= "<option value='5'>รอขออนุมัติจำหน่าย</option>";
            echo $status;
          } elseif ($row['StatusID'] == '4') {
            $status = '';
            $status .= "<option>- เลือกสถานะ -</option>";
            $status .= "<option value='3'>ผบน.รับเรื่อง อยู่ระหว่างตรวจสอบความถูกต้อง</option>";
            echo $status;
          }
          ?>
        </select>
    </div>

    <!-- เลขที่หนังสือ -->
    <div class='mb-3'>
      <label for='LBNewOrderNumber' class='col-form-label'>เลขที่หนังสือ</label>
      <input type='text' name='NewOrderNumber' id='NewOrderNumber' class='form-control' required>
    </div>
    <div class="row" id="data"></div>
    <!-- เลขที่หนังสือ -->

    <div class='mb-3'>
      <label for='OrderDate' class='col-form-label'>วันที่รับหนังสือ</label>
      <input type='date' name='OrderDate' id='OrderDate' class='form-control' value='<?php echo $OrderDate ?>' required>
    </div>

    <div class='ele_ref mb-3'>
      <label for='Due' class='col-form-label'>วันที่ครบกำหนด</label>
      <input type='date' name='Due' id='Due' class='form-control' min="<?php echo date('Y-m-d'); ?>">
    </div>

    <div class='mb-3'>
      <label class='col-form-label'>แนบเอกสาร</label>
      <input type='file' name='file' class='form-control' accept='application/pdf' required>
    </div>

  </div>

<?php } ?>