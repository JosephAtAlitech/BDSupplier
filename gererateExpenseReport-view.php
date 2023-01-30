<?php $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $sessionId = time().uniqid();
	//include '../timezone.php'; 
	$today = date('Y-m-d');
	$year = date('Y');
	if(isset($_GET['year'])){
    $year = $_GET['year'];
	}
	$first_date = date('Y-m-d',strtotime('first day of this month'));
	$last_date = date('Y-m-d',strtotime('last day of this month'));
	$first_day_of_year=date('Y-m-d', strtotime('first day of january this year'));
?>
<style> th,td{text-align: center;} </style>
<link rel="stylesheet" href="dist/css/select2.min.css" />
<body class="hold-transition skin-blue sidebar-mini">
<script type='text/javascript'>
	function showMyData(){  
	//alert('Generate Reports For This Date: '+$('#salesType').val());
	var party = document.getElementById("startDate").value;
	if(party ==''){
      alert('Please select Start Date'); return false;
      }
      else{
	$.ajax({ 
			type: "POST",
			url :"dateWiseSalesPdfView.php",
			data:{
				 	cName:$('#cashSalesType').val(),
				 	startDate:$('#startDate').val(),
					endtDate:$('#endtDate').val()
			 },
			 beforeSend: function () {
                    $('#loading').show();
                },
			 success: function(data){
				// alert(data);
				$("#loader").load(" #loader");
				 $("#myDiv").html(data);
			 },
			 complete: function () {
                    $('#loading').hide();
                }
	});
	}
	}
function setInputDate(_id){
    var _dat = document.querySelector(_id);
    var hoy = new Date(),
        d = hoy.getDate(),
        m = hoy.getMonth()+1, 
        y = hoy.getFullYear(),
        data;

    if(d < 10){
        d = "0"+d;
    };
    if(m < 10){
        m = "0"+m;
    };

    data = y+"-"+m+"-"+d;
    console.log(data);
    _dat.value = data;
};

setInputDate("#startDate");

</script>

<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
	
	
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Date Wise Expense Reports View & Print </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Date Wise Expense Reports View & Print </li>
      </ol>
    </section>
    <!-- Main content -->
		<section class="content">
		<div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-body" style="height: auto;"> 
				<h4 style="color: gray;text-align: center;">Date Wise Expense Reports </h4>
					<h5 style="text-align: center;">**Date wise Expense summary view & Print As PDF**</h5>
						<form  class="form-horizontal" method="POST">
							<div class="col-md-12">
							    <div class="col-md-2"></div>
							  
    							<div class="col-md-3">
    								<label for="Date" class="control-label">Select Date :</label>
    								<input  id="Date" style="padding: inherit;" class="form-control datetimepicker" placeholder="Select date" name="Date" type="date" value="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd"  required/>					
    							</div>
							    <div class="col-md-1">
    								<button type="button" id="btndisplay" class="btn btn-default btn-flat pull-left" name="btndisplay" onclick="generateExpenseReport();" style="background-color: #3f3e93;color: #fff;margin-top: 48%;border-color: #3f3e93;"><i class="fa fa-search"></i> Generate Expense Report </button>
    							</div>
    					    </div>
						</form><br><br>
						<!--input type="submit" id="btndisplay" value="show" onclick="showMyData();"-->
						<div id="myDiv"></div>
                        <div class="form-group col-md-12">
                                <div>
                                   
                            </div>
                            </div>
                            <div>
                                    <a href="" target="_blank" class="btn btn-primary float-left p-2 mb-2" id="reportBtn"  onclick="expenseReport()" style="display:none;">Generate PDF Report </a>
                                </div>
                                <table id="ExpenseReportTable" class="table table-bordered" style="width:100%;">
                                    <thead>
                                        <th width="4%">SL</th>
                                        <th width="25%">Particulars</th>
                                        <th width="15%">Voucher</th>
                                        <th width="15%">Grand Total</th>
                                        <th width="15%">Cash In</th>
                                        <th width="15%">Cash Out</th>
                                        <th width="15%">Balance</th> 
                                    </thead>
                                    <tbody id="manageExpenseReportTable">
                                    </tbody>
                                </table>
					<br><br>
				</div>
            </div>
         </div>
        </div>
		</section> 
		
  </div>
    
  <?php 
  include 'includes/footer.php'; 
  ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/generateExpenseReport.js"></script>
<script>
    $("#add_party").select2( {
    	placeholder: "Select Party Name",
    	allowClear: true
    	} );
    	
 </script>  	
</body>
</html>
