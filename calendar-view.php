<?php 
$conPrefix='';
include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
	<?php 
		$first = date("Y-m-d", strtotime("first day of this month"));
		$last = date("Y-m-d", strtotime("last day of this month"));
		$firstDayOfYear = date('Y-m-d', strtotime('first day of january this year'));
		$lastDayOfYear = $yearEnd = date('Y-m-d', strtotime('last day of december this year'));
	?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Device Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Device Information</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
	   <link rel="stylesheet" href="../css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
					<div class="col-md-5">
						<div class="col-md-5">
							<input name="min" id="min" class="form-control icondate" placeholder="Select Start date" type="text" data-date-format="yyyy-mm-dd" />					
						</div>
						<label class="control-label col-md-1">-To-</label>
						<div class="col-md-5">
							<input name="max" id="max" class="form-control icondate" placeholder="Select End date" data-date-format="yyyy-mm-dd"/>
						</div>
					</div>
              <table id="example_fullview" class="table table-bordered">
                <thead>
                  <th>ID</th>
                  <th>Date</th>
                  <th>Day Name</th>
                  <th>Day Type</th>
                  <th>Cause</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM calender_tbl WHERE (date BETWEEN '$firstDayOfYear' AND '$lastDayOfYear')";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      echo "
                        <tr>
                          <td>".$row['id']."</td>
                          <td>".$row['date']."</td>
                          <td>".$row['day_name']."</td>
                          <td>".$row['day_type']."</td>
                          <td>".$row['offday_cause']."</td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit </button>
                          </td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/calendar_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $('.edit').click(function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'calender_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#calenderid').val(response.id);
      $('#edit_date').val(response.date);
      $('#edit_day_name').val(response.day_name);
	  $('#edit_day_type').val(response.day_type);
      $('#edit_offday_cause').val(response.offday_cause);
      
      $('#del_devid').val(response.id);
      $('#del_device_name').html(response.device_name);
    }
  });
}
</script>
<script type="text/javascript">

		$(document).ready(function() {
			$('#example_fullview').DataTable( {
			  responsive: true,
			  dom: 'Bfrtip',
				buttons: [
					'pageLength','copy', 'csv', 'pdf', 'print'
				]
			})
		
		})
		
		$(document).ready(function(){
				$.fn.dataTable.ext.search.push(
				function (settings, data, dataIndex) {
					var min = $('#min').datepicker("getDate");
					var max = $('#max').datepicker("getDate");
					var startDate = new Date(data[1]);
					if (min == null && max == null) { return true; }
					if (min == null && startDate <= max) { return true;}
					if(max == null && startDate >= min) {return true;}
					if (startDate <= max && startDate >= min) { return true; }
					return false;
				}
				);

       
            $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            var table = $('#example_fullview').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });
</script>
</body>
</html>
