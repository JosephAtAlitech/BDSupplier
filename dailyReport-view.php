<?php $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
    $sessionId = time().uniqid();
	//include '../timezone.php'; 
	$today = date('Y-m-d');
	$year = date('Y');
    

    $sql = "SELECT * from daily_reports where deleted= 'No' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
   
    if ($result) {
        $row = $result->fetch_assoc();
        echo $date = $row['date'];

        $checkTransaction ="SELECT DISTINCT tbl_paymentvoucher.paymentDate 
                            from tbl_paymentvoucher 
                            where tbl_paymentvoucher.deleted = 'No' 
                            AND tbl_paymentvoucher.paymentDate >'$date'
                            AND tbl_paymentvoucher.status = 'Active' 
                            GROUP BY paymentDate";
        /* $checkTransaction = "SELECT DISTINCT tbl_paymentvoucher.paymentDate 
           from tbl_paymentvoucher
           join daily_reports on tbl_paymentvoucher.paymentDate != daily_reports.date
           where tbl_paymentvoucher.deleted = 'No'
           AND tbl_paymentvoucher.paymentDate > $date
           AND tbl_paymentvoucher.status = 'Active'"; */
        $result2 = $conn->query($checkTransaction);
        $dateArray = [];
        $i = 0;
        //var_dump($result2);
        while ($row2 = $result2->fetch_array()) {
            $dateArray[$i] = $row2['paymentDate']; // store previous not closing date(s)
            $i++;
        }
        // end check preious days report saved or not//

        $purchasePaymentATotal = 0;
        $saleReceivedTotal = 0;
        $expanseAmount = 0;
    } else {
       $dateArray = [];
    }

?>
<style> th,td{text-align: center;} </style>
<link rel="stylesheet" href="dist/css/select2.min.css" />
<body class="hold-transition skin-blue sidebar-mini">




<div class="wrapper">
	
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>Date Wise Cash Sales Reports View & Print </h1>
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
		
						<form  class="form-horizontal" method="POST">
							<div class="col-md-12">
							    <div class="col-md-2">
                                    <?php
                                foreach ($dateArray as $date){ ?>
                                                <input type="hidden" id="remainingDate" name="remainingDate" value=" <?php echo $date; ?>" disabled>
                                <?php } ?>
                                </div>
							  
    							<div class="col-md-3">
    								<label for="date" class="control-label">Select Date :</label>
                                    <input type="date" style="line-height: 10px;" class="form-control" id="date" value="<?php echo date('Y-m-d');?>" name="date" placeholder="  " >
    							</div>
							    <div class="col-md-1">
    								<button type="button" id="btndisplay" class="btn btn-default btn-flat pull-left" name="btndisplay" onclick="viewCalculation()" style="background-color: #3f3e93;color: #fff;margin-top: 48%;border-color: #3f3e93;"><i class="fa fa-search"></i> View Calculation </button>
    								
    							</div>
    					    </div>
						</form>
						<br><br>
						<!--input type="submit" id="btndisplay" value="show" onclick="showMyData();"-->
						<div id="myDiv"></div>
					<br><br>
                    <div id='divMsg' class='alert alert-success alert-dismissible successMessage' style="justify-content:center;"></div>
					<div class="form-group col-md-6">
                                            <label><strong>Daily Ledger Details:</strong></label>
                                            <table  border="1" style="width:100%;">
                                                <thead class="text-center">
                                                   <tr>
                                                        <th>Name</th>
                                                        <th>Amount</th>
                                                    </tr> 
                                                </thead>
                                                <tbody class="text-center" id="manageReportTable">
                                                    
                                                </tbody>
                                            </table>
                                         
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="hidden" id="totalDr" name="totalDr" value="">
                                            <input type="hidden" id="totalCr" name="totalCr" value="">
                                            <input type="hidden" id="totalExpense" name="totalExpense" value="">
                                        </div>
                                        <div class="form-group col-md-4"></div>
										<div class="row col-md-12">
										<div class="form-group col-md-4">
                                            <label>Opening Balance : </label>
                                            <input type="text" class="form-control" id="openingBalance"
                                                name="openingBalance" aria-describedby="emailHelp" value="" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Total Amount (today) : </label>
                                            <input type="text" class="form-control" id="totalAmount" name="totalAmount"
                                                aria-describedby="emailHelp" value="" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Closing Amount : </label>
                                            <input type="text" class="form-control" id="closingAmount"
                                                name="closingAmount" aria-describedby="emailHelp" value="" disabled>
                                        </div>
										</div>
                                        
                                    </div>
								</div>
								<div class="row">
                                        <div class="col-md-8">
                                            <label> </label>
                                            <a type="button"
                                                class="btn btn-success text-light my_button float-left mt-4 p-2"
                                                onclick="clearInput();"> Clear </a>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> </label>
                                            <button type="button" class="btn btn-success btn-lg btn-block "
                                                onclick="saveTodayReport()">Save Today Report</button>
                                        </div>
                                    </div>
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
<script src="includes/js/manageDailyReport.js"></script>
<script>
    $("#add_party").select2( {
    	placeholder: "Select Party Name",
    	allowClear: true
    	} );
    	
 </script>  	
</body>
</html>
