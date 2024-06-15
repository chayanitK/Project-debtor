<?php
include "conn.php";

$SummaryID = 0;
if (isset($_POST['SummaryID'])) {
  $SummaryID = mysqli_real_escape_string($conn, $_POST['SummaryID']);
}
$tbname = "summaryofdebtors";
$sql = "SELECT * FROM $tbname INNER JOIN status ON (summaryofdebtors.StatusID = status.StatusID)
INNER JOIN gatheringpoint ON (summaryofdebtors.GatheringPoint = gatheringpoint.GatheringPoint) where SummaryID=" . $SummaryID;
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($result)) {
  $id = $row['SummaryID'];
  $GatheringPoint = $row['GatheringPoint'];
  $Status = $row['StatusName'];
  $Due = $row['Due'];
  $NumOfCases = $row['NumOfCases'];
  $AmountOfMoney = $row['AmountOfMoney'];
  $OrderNumber = $row["OrderNumber"];
  $OrderDate = $row["OrderDate"];

  $StatusCount = $row['times'];
?>

  <div class='container'>
    <input type='hidden' name='SummaryID' id='SummaryID' class='form-control' value='<?php echo $id ?>'>

    <div class='mb-3'>
      <p><label for='GatheringPoint' class='col-form-label'>จุดรวมงาน</label>
        <select name='GatheringPoint' id='GatheringPoint' class='form-select'>
          <option><?php echo $row['GatheringPoint'] . " " . $row['fullname']; ?></option>
          <option disabled>- เลือกจุดรวมงาน -</option>
          <?php
          $tbname = "gatheringpoint";
          $sql = "SELECT * FROM $tbname ORDER BY GatheringPoint ASC";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) : ?>
            ?>
            <option><?= $row['GatheringPoint'] . " " . $row['fullname']; ?></option>
          <?php endwhile; ?>
        </select>
    </div>

    <!-- เลขที่หนังสือ -->
    <div class='mb-3'>
      <label for='OrderNumber' class='col-form-label'>เลขที่คำสั่ง</label>
      <input type='text' name='OrderNumber' id='OrderNumber' class='form-control' value='<?php echo $OrderNumber ?>'>
    </div>
    <div class="row" id="data"></div>
    <!-- เลขที่หนังสือ -->

    <div class='mb-3'>
      <label for='OrderDate' class='col-form-label'>วันที่คำสั่ง</label>
      <input type='date' name='OrderDate' id='OrderDate' class='form-control' value='<?php echo $OrderDate ?>'>
    </div>

    <?php
    $tbname = "summaryofdebtors";
    $sql = "SELECT * FROM $tbname INNER JOIN status ON (summaryofdebtors.StatusID = status.StatusID) where SummaryID=" . $SummaryID;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $Status = $row['StatusName'];
    $StatusCount = $row['times'];
    ?>

    <div class='mb-3'>
      <label for='StatusName' class='col-form-label'>สถานะ</label>
      <select name='Status' id='Status' class='form-select'>
        <option value='<?php echo $row['StatusID'] ?>'><?php if ($row['StatusID'] == '2' || $row['StatusID'] == '4') {
                                                          echo $Status . " " . $StatusCount;
                                                        } else {
                                                          echo $Status;
                                                        } ?></option>
        <option disabled>- เลือกสถานะ -</option>

        <?php
        $sql = "SELECT * FROM status";
        $result2 = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result2)) :
          $id = $row['StatusID'];
          $Status = $row['StatusName'];
        ?>
          <option value='<?php echo $row['StatusID'] ?>'><?php echo $Status; ?></option>
        <?php endwhile; ?>

        <!-- <option value='1'>พิจารณาให้แล้วเสร็จภายใน 30 วัน</option> -->


      </select>
    </div>


    <div class='mb-3'>
      <label for='Due' class='col-form-label'>วันที่ครบกำหนด</label>
      <input type='date' name='Due' id='Due' class='form-control' value='<?php echo $Due ?>'>
    </div>

    <div class='mb-3'>
      <label for='NumOfCases' class='col-form-label'>จำนวน (ราย)</label>
      <input type='number' name='NumOfCases' id='NumOfCases' class='form-control' value='<?php echo $NumOfCases ?>'>
    </div>

    <div class='mb-3'>
      <label for='AmountOfMoney' class='col-form-label'>จำนวนเงิน (บาท)</label>
      <input type='text' name='AmountOfMoney' id='AmountOfMoney' class='form-control' value='<?php echo $AmountOfMoney ?>'>
    </div>

  </div>
<?php } ?>

<script>
  // เช็คค่าซ้ำ
  $("#OrderNumber").keyup(function() {
    let OrderNumber = $(this).val();
    let data = {
      'OrderNumber': OrderNumber
    };
    $.ajax({
      type: 'POST',
      url: "edit.php",
      data: data,
      success: function(data) {
        $("#data").html(data);
      }
    });
  });
</script>