<?php include "conn.php";
$no = 1;
$SumNumOfCases = 0;
$SumAmountOfMoney = 0;
$output = '';
if (isset($_POST["input"])) {
  $search = mysqli_real_escape_string($conn, $_POST["input"]);
  $query = "SELECT * FROM gatheringpoint WHERE GatheringPoint LIKE '%$search%' OR fullname LIKE '%$search%' ORDER BY GatheringPoint ASC";
} else {
  $query = "SELECT * FROM gatheringpoint ORDER BY GatheringPoint ASC";
}
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="th">

<body>
  <!-- แสดงข้อมูล -->
  <div class="table-widget">
    <div class="table-responsive">
      <table class="table table-striped" id="point">
        <thead align="center">
          <tr>
            <th width="15%"></th>
            <th width="5%">ที่</th>
            <th width="10%">จุดรวมงาน</th>
            <th width="25%">ชื่อเต็ม</th>
          </tr>
        </thead>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <td align="center">
              <button class="btn btn-secondary EditDataBtn" data-id="<?php echo $row["id"]; ?>"><i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;
              <a href="delGP.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger" onclick="return confirm ('ต้องการลบข้อมูลใช่หรือไม่ !!');"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
            <td align="center"><?php echo $no++; ?></td>
            <td align="center"><?php echo $row["GatheringPoint"]; ?></td>
            <td align="center"><?php echo $row["fullname"]; ?></td>
            </tr>
          <?php
          }
        } else {
          ?>
          <tr>
            <td colspan="4" align="center">ไม่พบรายการ</td>
          </tr>
        <?php
        }
        ?>
      </table>
    </div>
  </div>
  <!-- แสดงข้อมูล -->


  <!-- ฟอร์มเพิ่มข้อมูล !-->
  <div class="modal fade" id="AddData" tabindex="-1" role="dialog" aria-labelledby="AddDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="AddDataLabel">เพิ่มข้อมูล</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="AddGP.php" method="post">
            <!-- ดึงจุดรวมงานมาแสดงใน Dropdown List -->
            <div class="mb-3">
              <label for="GatheringPoint" class="col-form-label">จุดรวมงาน&nbsp;</label>
              <input type='text' name="GatheringPoint" id="GatheringPoint" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="fullname" class="col-form-label">ชื่อเต็ม&nbsp;</label>
              <input type='text' name="fullname" id="fullname" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
          <button type="submit" class="btn btn-primary">ยืนยัน</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ฟอร์มเพิ่มข้อมูล !-->



  <!-- ฟอร์มแก้ไขข้อมูล !-->
  <div class="modal fade" id="EditDataModal" tabindex="-1" role="dialog" aria-labelledby="EditDataModalLabel" aria-hidden="true" role="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="EditDataModalLabel">แก้ไขข้อมูล</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="editGP.php" method="post">
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

</body>

</html>

<script>
  // เรียกใช้ edit modal
  $(document).ready(function() {
    $('.EditDataBtn').click(function() {

      var id = $(this).data('id');

      // AJAX request
      $.ajax({
        url: 'AjaxGP.php',
        type: 'post',
        data: {
          id: id
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
</script>