<style>
  /* Chrome, Safari, Edge, Opera */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }
</style>

<?php include('conn.php');

$d = date('Y-m-d');

//แสดงวันที่แบบ ว ด ป
function changeDate($d)
{
  //ใช้ Function explode ในการแยกไฟล์ ออกเป็น  Array
  $get_date = explode("-", $d);
  //กำหนดชื่อเดือนใส่ตัวแปร $month
  $month = array("01" => "ม.ค.", "02" => "ก.พ.", "03" => "มี.ค.", "04" => "เม.ย.", "05" => "พ.ค.", "06" => "มิ.ย.", "07" => "ก.ค.", "08" => "ส.ค.", "09" => "ก.ย.", "10" => "ต.ค.", "11" => "พ.ย.", "12" => "ธ.ค.");
  //month
  $get_month = $get_date["1"];
  //year	
  $year = $get_date["0"] + 543;
  return $get_date["2"] . " " . $month[$get_month] . " " . $year;
}


//แสดงปี
$year = date("Y");
function changeYear($year)
{
  //year	
  $year_ = $year + 543;
  return $year_;
}

$no = 1;
$SumNumOfCases = 0;
$SumAmountOfMoney = 0;
$tbname = "summaryofdebtors";

// order by column names array
$orderby_arr = array(
  "GatheringPoint" => "จุดรวมงาน",
  "num" => "เลขที่คำสั่ง",
  "OrderDate" => "วันที่คำสั่ง",
  "StatusName" => "สถานะ",
  "Due" => "วันที่ครบกำหนด",
  "NumOfCases" => "จำนวน (ราย)",
  "AmountOfMoney" => "จำนวนเงิน (บาท)"
);

// sorting order ASC or DESC array
$sort_arr = array("asc", "desc");

// set default values
$orderby = isset($_GET['orderby']) ? $_GET['orderby'] : "CreateDate";
$sort = isset($_GET['sort']) ? $_GET['sort'] : "desc";

// get opposite $sort_order value for producing toggle links in the table heading
if ($sort == "desc") {
  $sort_order = "asc";
} else {
  $sort_order = "desc";
}


// if search request comes
if (isset($_POST["input"])) {
  $search = mysqli_real_escape_string($conn, $_POST["input"]);
  $query = "SELECT * FROM $tbname INNER JOIN status ON (summaryofdebtors.StatusID = status.StatusID)
WHERE GatheringPoint LIKE '%$search%' OR num LIKE '%$search%' OR OrderDate LIKE '%$search%' OR StatusName LIKE '%$search%' OR Due LIKE '%$search%' OR NumOfCases LIKE '%$search%' OR AmountOfMoney LIKE '%$search%'
ORDER BY $orderby $sort";
} else {
  $query = "SELECT * FROM $tbname INNER JOIN status ON (summaryofdebtors.StatusID = status.StatusID) ORDER BY $orderby $sort";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="th">

<body>
  <!-- แสดงข้อมูลทั้งหมด -->
  <div class="table-widget">
    <div class="table-responsive">
      <table class="table table-striped" id="myTable">
        <thead class="appoint" align="center">
          <tr class="header">
            <th></th>
            <th>ที่</th>

            <?php
            // dynamically produce table columns
            foreach ($orderby_arr as $key => $value) {
              echo "<th><a class='nav-link' href='?p=home&orderby=" . $key . "&sort=" . $sort_order . "'>" . $value . "</a></th>";
            }
            ?>
          </tr>
        </thead>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_array($result)) {
            $GatheringPoint = $row["GatheringPoint"];
            $OrderNumber = $row["OrderNumber"];
            $OrderDate = $row["OrderDate"];
            $StatusID = $row["StatusID"];
            $StatusName = $row["StatusName"];
            $times = $row["times"];
            $Due = $row["Due"];
            $NumOfCases = $row["NumOfCases"];
            $AmountOfMoney = $row["AmountOfMoney"];
        ?>

            <tr align="center" class="appoint">
              <td>
                <button class="btn btn-primary StatusBtn" data-id="<?php echo $row["OrderNumber"] ?>"><i class="fa fa-file-o" aria-hidden="true"></i></button>
                <button class="btn btn-secondary EditDataBtn" data-id="<?php echo $row["SummaryID"] ?>"><i class="fa fa-edit" aria-hidden="true"></i></button>
                <a href="del.php?OrderNumber=<?php echo $row["OrderNumber"] ?>" class="btn btn-danger del" id="del" onclick="return confirm ('ต้องการลบข้อมูลใช่หรือไม่ !!');"><i class="fa fa-trash" aria-hidden="true"></i></a>
              </td>
              <td><?php echo $no++ ?></td>
              <td><?php echo $GatheringPoint ?></td>
              <td><?php echo $OrderNumber ?></td>
              <td><?php echo changeDate($OrderDate) ?></td>
              <td><?php if ($StatusID == '2') {
                    echo  $StatusName . "ครั้งที่ " . $times;
                  } elseif ($StatusID == '4' && $times > 0) {
                    echo $StatusName . " ครั้งที่ " . $times;
                  } else {
                    echo $StatusName;
                  }
                  ?></td>
              <td><?php if ($StatusID == '1' || $StatusID == '2') {
                    echo changeDate($Due);
                  } else {
                    echo '-';
                  } ?></td>
              <td><?php echo $NumOfCases ?></td>
              <td align="right"><?php echo number_format($AmountOfMoney, 2) ?></td>
            </tr>

          <?php
            $SumNumOfCases += $NumOfCases;
            $SumAmountOfMoney += $AmountOfMoney;
          }
        } else {
          ?>

          <tr>
            <td colspan="10" align="center">ไม่พบรายการ</td>
          </tr>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="7" align="center"><b>Grand Total</b></td>
            <td align="center"><?php echo number_format($SumNumOfCases) ?></td>
            <td align="right"><?php echo number_format($SumAmountOfMoney, 2) ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <!-- แสดงข้อมูลทั้งหมด -->


  <!-- ฟอร์มเพิ่มข้อมูล !-->
  <div class="modal fade" id="AddData" tabindex="-1" role="dialog" aria-labelledby="AddDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="AddDataLabel">เพิ่มข้อมูล</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form class="form-inline" action="Add.php" method="post" enctype="multipart/form-data">
            <!-- ดึงจุดรวมงานมาแสดงใน Dropdown List -->
            <div class="form-group">
              <div class="mb-3">
                <label for="GatheringPoint" class="col-form-label">จุดรวมงาน</label>
                <select name="GatheringPoint" id="GatheringPoint" class="form-select" required>
                  <option>- เลือกจุดรวมงาน -</option>
                  <!--คิวรี่ข้อมูลเพื่อมาแสดงใน select/option!-->
                  <?php
                  $tbname = "gatheringpoint";
                  $sql = "SELECT * FROM $tbname ORDER BY GatheringPoint ASC";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_assoc($result)) : ?>
                    <option><?= $row['GatheringPoint'] . " " . $row['fullname']; ?></option>
                  <?php endwhile ?>
                </select>
              </div>
            </div>
            <div class="col-3">
              <label for="OrderNumber" class="col-form-label">เลขที่คำสั่ง</label>
            </div>
            <!-- เลขที่หนังสือ -->
            <div class="input-group">
              <label for="LbOrderNumber" class="col-form-label">น.3/ส.(ลม)&nbsp;</label>
              <div class="col-2">
                <input type="number" class="form-control" name="OrderNumber" id="OrderNumber" required>
              </div>
              <label for="LbOrderNumber" class="col-form-label">&nbsp;/<?php echo changeYear($year); ?></label>
            </div>
            <div class="row" id="data"></div>
            <!-- เลขที่หนังสือ -->
            <div class="mb-3">
              <label for="OrderDate" class="col-form-label">วันที่คำสั่ง</label>
              <input type='date' name="OrderDate" id="OrderDate" class="form-control" value="<?php echo $d ?>" required>
            </div>
            <div class="mb-3">
              <label for='Due' class="col-form-label">วันที่ครบกำหนด</label>
              <input type='date' name='Due' id='Due' class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
              <label for='NumOfCases' class="col-form-label">จำนวน (ราย)</label>
              <input type='number' name='NumOfCases' id='NumOfCases' class="form-control" min="0" required>
            </div>
            <div class="mb-3">
              <label for='AmountOfMoney' class="col-form-label">จำนวนเงิน (บาท)</label>
              <input type='float' name='AmountOfMoney' id='AmountOfMoney' class="form-control" min="0" required>
            </div>
            <div class='mb-3'>
              <label class='col-form-label'>แนบเอกสาร</label>
              <input type='file' name='file' id='file' class='form-control' accept='application/pdf' required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
          <button type="submit" name="AddData" class="btn btn-primary">ยืนยัน</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ฟอร์มเพิ่มข้อมูล !-->


  <!-- ฟอร์มแก้ไขสถานะ !-->
  <div class="modal fade" id="StatusModal" tabindex="-1" role="dialog" aria-labelledby="StatusModalLabel" aria-hidden="true" role="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title-status" id="StatusModalLabel">แก้ไขสถานะ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="AddStatus.php" method="post" enctype="multipart/form-data">
          <div class="modal-body-status">

            <!-- content -->

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-primary">ยืนยัน</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ฟอร์มแก้ไขสถานะ !-->



  <!-- ฟอร์มแก้ไขข้อมูล !-->
  <div class="modal fade" id="EditDataModal" tabindex="-1" role="dialog" aria-labelledby="EditDataModalLabel" aria-hidden="true" role="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="EditDataModalLabel">แก้ไขข้อมูล</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="edit.php" method="post">
          <div class="modal-body-edit">

            <!-- content -->

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-primary">ยืนยัน</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ฟอร์มแก้ไขข้อมูล !-->



  <script>
    // เรียกใช้ edit modal
    $(document).ready(function() {
      $('.EditDataBtn').click(function() {

        var SummaryID = $(this).data('id');

        // AJAX request
        $.ajax({
          url: 'EditData.php',
          type: 'post',
          data: {
            SummaryID: SummaryID
          },
          success: function(response) {
            // Add response in Modal body
            $('.modal-body-edit').html(response);

            // Display Modal
            $('#EditDataModal').modal('show');
          }
        });
      });
    });


    // เรียกใช้ status modal
    $(document).ready(function() {
      $('.StatusBtn').click(function() {

        var OrderNumber = $(this).data('id');

        // AJAX request
        $.ajax({
          url: 'StatusModal.php',
          type: 'post',
          data: {
            OrderNumber: OrderNumber
          },
          success: function(response) {
            // Add response in Modal body
            $('.modal-body-status').html(response);

            // Display Modal
            $('#StatusModal').modal('show');
          }
        });
      });
    });

    // เช็คค่าซ้ำ
    $("#OrderNumber").keyup(function() {
      let OrderNumber = $(this).val();
      let data = {
        'OrderNumber': OrderNumber
      };
      $.ajax({
        type: 'POST',
        url: "Add.php",
        data: data,
        success: function(data) {
          $("#data").html(data);
        }
      });
    });
  </script>

</body>

</html>