<?php include('conn.php'); ?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!--นำเข้าไฟล์  Css -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />

  <title>สรุปลูกหนี้</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="header"><?php include('nav.php'); ?></div>
  <div>
    <?php
    if (isset($_GET['p']) && $_GET['p'] == 'home') {
    ?>
      <div class="row mb-3">
        <form action="" method="POST">
          <div style="float:left;"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddData">เพิ่มข้อมูล</button></div>
          &nbsp;<a href='exportData.php' id='download_link' class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
          <div style="float:right;"><input type="text" id="searchData" name="searchData" class="form-control" style="width:250px" placeholder="ค้นหา"></div>
        </form>
      </div>
    <?php
      include('data.php');
    } elseif (isset($_GET['p']) && $_GET['p'] == 'GatheringPoint') {
    ?>
      <div class="row mb-3">
        <form action="" method="POST">
          <div style="float:left;"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddData">เพิ่มข้อมูล</button></div>
          <div style="float:right;"><input type="text" id="searchPoint" name="searchPoint" class="form-control" style="width:250px" placeholder="ค้นหา"></div>
        </form>
      </div>
    <?php
      include('gp.php');
    } elseif (isset($_GET['p']) && $_GET['p'] == 'history') {
    ?>
      <div class="row mb-3">
        <form action="" method="POST">
          <div style="float:right;"><input type="text" id="searchHistory" name="searchHistory" class="form-control" style="width:250px" placeholder="ค้นหา"></div>
        </form>
      </div>
    <?php
      include('history.php');
    }
    ?>
  </div>

</body>

</html>

<script>
  // ค้นหาข้อมูล
  $(document).ready(function() {
    $('#searchData').keyup(function() {
      var input = $(this).val();
      // alert(input);
      if (input != '') {
        $.ajax({
          url: 'data.php',
          method: 'post',
          data: {
            input: input
          },
          success: function(data) {
            // Add response in Modal body
            $('#myTable').html(data);
            $('#myTable').css("display", "block");
          }
        });
      } else {

      }
    });
  });


  // ค้นหาข้อมูลจุดรวมงาน
  $(document).ready(function() {
    $('#searchPoint').keyup(function() {
      var input = $(this).val();
      // alert(input);
      if (input != '') {
        $.ajax({
          url: 'gp.php',
          method: 'post',
          data: {
            input: input
          },
          success: function(data) {
            // Add response in Modal body
            $('#point').html(data);
            $('#point').css("display", "block");
          }
        });
      } else {

      }
    });
  });


  // ค้นหาข้อมูลประวัติการติดตาม
  $(document).ready(function() {
    $('#searchHistory').keyup(function() {
      var input = $(this).val();
      // alert(input);
      if (input != '') {
        $.ajax({
          url: 'history.php',
          method: 'post',
          data: {
            input: input
          },
          success: function(data) {
            // Add response in Modal body
            $('#history').html(data);
            $('#history').css("display", "block");
          }
        });
      } else {

      }
    });
  });
</script>