<?php 
$conPrefix='';
include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
			<?php
				if(isset($_POST['add'])){
					
					date_default_timezone_set('UTC');
					$date = $_POST["employee"]."-01-01";
					$end_date = $_POST["employee"]."-12-31";
					while(strtotime($date) <= strtotime($end_date)){
						$datename = date('D',strtotime($date));
						
						//echo $date.'--------'.$datename.'<br />';
						
						$sql = "INSERT INTO calender_tbl (date, day_name, day_type, offday_cause) VALUES ('$date', '$datename', 'Onday', '')";
						if($conn->query($sql)){
								$_SESSION['success'] = 'Yearly date added successfully';
							}
							else{
								$_SESSION['error'] = $conn->error;
							}
						
						$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
						//echo "".$date." ---- ".$datename."<br />";
					}
				}
				?>
				<?php
					if(isset($_POST['offday'])){
						
						$off_year = $_POST["off_year"];
						$off_day = $_POST["off_day"];
						
						$sql="UPDATE `calender_tbl` set day_type='Offday' WHERE day_name='$off_day' and date like'%$off_year%'";
						if($conn->query($sql)){
								$_SESSION['success'] = 'OffDay added successfully';
							}
							else{
								$_SESSION['error'] = $conn->error;
							}
							
					}
				?>
				<?php
					if(isset($_POST['holiday'])){
						
						$holiday_date = $_POST["holidayDate"];
						$holiday_Cause = $_POST["holidayCause"];
						
						$sql="UPDATE `calender_tbl` set day_type='Holiday',offday_cause='$holiday_Cause' WHERE date='$holiday_date'";
						if($conn->query($sql)){
								$_SESSION['success'] = 'Holiday added successfully';
							}
							else{
								$_SESSION['error'] = $conn->error;
							}
							
					}
				?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Yearly Date & Holiday
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Manage</li>
        <li class="active">Holiday</li>
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
	  <style>
		* Response */
		.response{
			padding: 6px;
			display: none;
		}

		.not-exists{
			color: red;
		}

		.exists{
			color: green;
		}
		</style>
	 <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
				<br><br><br><br>
				<form class="form-horizontal" method="POST" action="view.php">				
					<div class="form-group">
						<label for="employee" class="col-sm-3 control-label">Yearly Calender Added</label>
						<div class="col-sm-6">
							<?php $years = range(2021, strftime("%Y", time())); ?>
								<select class="form-control"  id="offday" name="employee" required>
								  <option>Select Year</option>
								  <?php foreach($years as $year) : ?>
									<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
								  <?php endforeach; ?>
								</select>
						</div>
						<button type="submit" class="btn btn-primary btn-flat col-sm-1" name="add"><i class="fa fa-save"></i> Submit</button>
					</div>
				</form>
				<form class="form-horizontal" method="POST" action="view.php">
					<div class="form-group">
						<label for="offdays" class="col-sm-3 control-label">Yearly Offdays Added</label>

						<div class="col-sm-3">
						<?php $years = range(2021, strftime("%Y", time())); ?>
								<select class="form-control"  id="offday" name="off_year" required>
								  <option>Select Year</option>
								  <?php foreach($years as $year) : ?>
									<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
								  <?php endforeach; ?>
								</select>
						</div>
						<div class="col-sm-3">
								<select class="form-control"  id="offday" name="off_day" required>
									<option value="" selected>- Select One -</option>
									<option value="Fri">Friday</option>
									<option value="Sat">Saturday</option>
									<option value="Sun">Sunday</option>
									<option value="Mon">Monday</option>
									<option value="Tue">Tuesday</option>
									<option value="Wed">Wednesday</option>
									<option value="Thu">Thursday</option>
								</select>
						</div>
						<button type="submit" class="btn btn-primary btn-flat col-sm-1" name="offday"><i class="fa fa-save"></i> Submit</button>
					</div>
				</form>
				<form class="form-horizontal" method="POST" action="view.php">
					<div class="form-group">
						<label for="datepicker_add" class="col-sm-3 control-label">Holiday Added</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="datepicker_add" name="holidayDate" placeholder='Holiday Select' required>
							</div>
							<div class="col-sm-3">
								<textarea class="form-control"  id="holiday_Cause" name="holidayCause" placeholder="Holiday Cause"></textarea>
							</div>
						<button type="submit" class="btn btn-primary btn-flat col-sm-1" name="holiday"><i class="fa fa-save"></i> Submit</button>
					</div>
				</form>
				<br><br><br><br>
			</div>			
		 </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
// define variables
var nativePicker = document.querySelector('.nativeDatePicker');
var fallbackPicker = document.querySelector('.fallbackDatePicker');
var fallbackLabel = document.querySelector('.fallbackLabel');

var yearSelect = document.querySelector('#year');
var monthSelect = document.querySelector('#month');

// hide fallback initially
fallbackPicker.style.display = 'none';
fallbackLabel.style.display = 'none';

// test whether a new date input falls back to a text input or not
var test = document.createElement('input');
test.type = 'month';
// if it does, run the code inside the if() {} block
if(test.type === 'text') {
  // hide the native picker and show the fallback
  nativePicker.style.display = 'none';
  fallbackPicker.style.display = 'block';
  fallbackLabel.style.display = 'block';

  // populate the years dynamically
  // (the months are always the same, therefore hardcoded)
  populateYears();
}

function populateYears() {
  // get the current year as a number
  var date = new Date();
  var year = date.getFullYear();

  // Make this year, and the 100 years before it available in the year <select>
  for(var i = 0; i <= 100; i++) {
    var option = document.createElement('option');
    option.textContent = year-i;
    yearSelect.appendChild(option);
  }
}
</script>
</body>
</html>
