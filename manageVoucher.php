<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
	if(isset($_GET['voucherType'])){
		$getVoucherType=$_GET['voucherType'];
	}else{
		header("Location: user-home.php");
	}
?>
<body class="hold-transition skin-blue sidebar-mini">
    <link rel="stylesheet" href="dist/css/select2.min.css" />
    <div class="wrapper">
    
      <?php include 'includes/navbar.php'; ?>
      <?php include 'includes/menubar.php'; ?>
    
      <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
        <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                <?php
                    if($getVoucherType == 'paymentReceived'){
                        $pageHeader = 'Payment Received Voucher';
                    }else if ($getVoucherType == 'payment'){
                        $pageHeader = 'Payment Voucher';
                    }else if ($getVoucherType == 'payable'){
                        $pageHeader = 'Payable Voucher';
                    }else if ($getVoucherType == 'partyPayable'){
                        $pageHeader = 'Party Payable Voucher';
                    }else if($getVoucherType == "adjustment"){
                        $pageHeader = 'Return Voucher';
                    }
                    else if($getVoucherType == 'discount'){
                        $pageHeader = 'Discount Voucher';
                    }
                    echo $pageHeader;
                ?>
              </h1>
              <ol class="breadcrumb">
                <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                <?php
                    echo $pageHeader ;
                ?>        
                </li>
              </ol>
            </section>
            <!-- Main content -->
            <section class="content">
              
              <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
              <input type="hidden" name="voucherType" id="voucherType" value="<?php echo $getVoucherType;?>" />
              <?php
                if(isset($_GET['viewFrom']) && $_GET['viewFrom'] == "bulkPaymentReceived"){
                }else{
              ?>
              <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-header with-border">
                        <div class="col-xs-6"></div>
            			<div class="col-xs-6">
            				<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
            				<div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
            			</div>
                    </div>
                    <div class="box-body">
            			    <form class="form-horizontal" id="form_voucher" method="POST" action="#">
            				    <div class="form-group">
                				    <div class="col-sm-12 divVoucherTypePR" style="display:none;">
                				        <center>
                                            <input type="radio" id="PartySale" name="entryVoucherTypePR" value="PartySale">
                                            <label for="PartySale">Party Sale Customer</label>
                                            <!--input type="radio" id="WalkinSale" name="entryVoucherTypePR" value="WalkinSale">
                                            <label for="WalkinSale">Walk in Sale Customer</label-->
                                        </center>
                				    </div>
                				    <div class="col-sm-12 divVoucherTypePRD" style="display:none;">
                				        <center>
                                            <input type="radio" id="PartySaleD" name="entryVoucherTypePRD" value="PartySale">
                                            <label for="PartySale">Party Sale Customer</label>
                                            <!--input type="radio" id="WalkinSaleD" name="entryVoucherTypePRD" value="WalkinSale">
                                            <label for="WalkinSale">Walk in Sale Customer</label-->
                                        </center>
                				    </div>
                				    <div class="col-sm-12 divVoucherTypeP" style="display:none;">
                				        <center>
                                            <input type="radio" id="localPurchase" name="entryVoucherTypeP" value="Local Purchase">
                                            <label for="localPurchase">Local Purchase</label>
                                            <!--input type="radio" id="foreignPurchase" name="entryVoucherTypeP" value="Foreign Purchase">
                                            <label for="foreignPurchase">Foreign Purchase</label-->
                                        </center>
                				    </div>
                				    <div class="col-sm-12 divVoucherTypeADJ" style="display:none;">
                				        <center>
                                            <input type="radio" id="adjustment" name="entryVoucherTypeADJ" value="paymentReceived">
                                            <label for="male">Purchase Return</label>
                                            <input type="radio" id="paymentAdjustment" name="entryVoucherTypeADJ" value="payment">
                                            <label for="female">Party Sale Return</label>
                                            <!--input type="radio" id="wiadjustment" name="entryVoucherTypeADJ" value="wipayment">
                                            <label for="male">WI Sale Return</label>
                                            <input type="radio" id="adjustment" name="entryVoucherTypeADJ" value="partyPayable">
                                            <label for="male">Purchase Return Adjustment</label>
                                            <input type="radio" id="paymentAdjustment" name="entryVoucherTypeADJ" value="payable">
                                            <label for="female">Party Sale Return Adjustment</label-->
                                        </center>
                				    </div>
                					<div class="col-sm-3">
                					    <label for="partyName">Party Name</label>
                						<select class="form-control" id="partyName" name="partyName" style="width: 100%;"></select>
                					</div>  
                					<div class="col-sm-2">
                					    <label for="amount">Due</label>
                					    <input type="text" class="form-control" name="previousDue" id="previousDue" placeholder="Previous Due" Disabled />
                					</div>
                					<div class="col-sm-2">
                					    <label for="amount">Amount</label>
                					    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" autocomplete="off" />
                					</div>
                					<div class="col-sm-3">
                					    <label for="paymentMethod">Payment Method</label>
                						<select class="form-control" name="paymentMethod" id="paymentMethod" style="width: 100%;">
                							<?php
                							if($getVoucherType=='discount'){
                							    $sql = "SELECT id, methodName
                                                    FROM tbl_paymentMethod
                                                    WHERE status = 'Active'and methodName='CASH' AND deleted = 'No' 
                                                    ORDER BY `tbl_paymentMethod`.`sort_order` ASC";
                                                $query = $conn->query($sql);
                    							while ($prow = $query->fetch_assoc()) {
                    								echo "<option value='" . $prow['id'] . "'>" . $prow['methodName'] . "</option>";
                    							}
                							}else{
                							    $sql = "SELECT id, methodName
                                                    FROM tbl_paymentMethod
                                                    WHERE status = 'Active' AND deleted = 'No' 
                                                    ORDER BY `tbl_paymentMethod`.`sort_order` ASC";
                                                $query = $conn->query($sql);
                    							while ($prow = $query->fetch_assoc()) {
                    								echo "<option value='" . $prow['id'] . "'>" . $prow['methodName'] . "</option>";
                    							 }
                							}
                							?>
                						</select>
                					</div>
                					<div class="col-sm-2">
                					    <label for="date">Date</label>
                					    <input type="date" class="form-control" name="date" id="date" placeholder="Date" style="padding: inherit;" value="<?php echo date('Y-m-d');?>">
                					</div>			
            				    </div>
            					<div class="form-group">
                					<div class="col-sm-3 trCheque" style='display:none;'>
                					    <label for="chequeNumber">Cheque Number</label>
                					    <input type="text" class="form-control" name="chequeNumber" id="chequeNumber" placeholder="Cheque Number">
                				    </div>
                				    <div class="col-sm-3 trCheque" style='display:none;'>
                					    <label for="chequeBank">Cheque Bank</label>
                					    <!--input type="text" class="form-control" name="chequeBank" id="chequeBank" placeholder="Cheque Bank"-->
                					    	<select class="form-control" name="chequeBank" class="form-control" id="chequeBank" style="Width:100%;">
											<option value="" selected>~~ Cheque Bank  ~~</option>
												<option value="AB Bank Limited"> AB Bank Limited </option>
												<option value="Bangladesh Commerce Bank Limited"> Bangladesh Commerce Bank Limited </option>
												<option value="Bank Asia Limited"> Bank Asia Limited </option>
												<option value="BRAC Bank Limited"> BRAC Bank Limited </option>
												<option value="City Bank Limited"> City Bank Limited </option>
												<option value="Dhaka Bank Limited"> Dhaka Bank Limited </option>
												<option value="Dutch-Bangla Bank Limited"> Dutch-Bangla Bank Limited </option>
												<option value="Eastern Bank Limited"> Eastern Bank Limited </option>
												<option value="HSBC Bank Bangladesh"> HSBC Bank Bangladesh </option>
												<option value="IFIC Bank Limited"> IFIC Bank Limited </option>
												<option value="Jamuna Bank Limited"> Jamuna Bank Limited </option>
												<option value="Meghna Bank Limited"> Meghna Bank Limited </option>
												<option value="Mercantile Bank Limited"> Mercantile Bank Limited </option>
												<option value="Mutual Trust Bank Limited"> Mutual Trust Bank Limited </option>
												<option value="National Bank Limited"> National Bank Limited </option>
												<option value="NCC Bank Limited"> NCC Bank Limited </option>
												<option value="NRB Bank Limited"> NRB Bank Limited </option>
												<option value="NRB Commercial Bank Ltd"> NRB Commercial Bank Ltd </option>
												<option value="NRB Global Bank Ltd"> NRB Global Bank Ltd </option>
												<option value="One Bank Limited"> One Bank Limited </option>
												<option value="Premier Bank Limited"> Premier Bank Limited </option>
												<option value="Prime Bank Limited"> Prime Bank Limited </option>
												<option value="Pubali Bank Limited"> Pubali Bank Limited </option>
												<option value="Standard Bank Limited"> Standard Bank Limited </option>
												<option value="Shimanto Bank Ltd"> Shimanto Bank Ltd </option>
												<option value="Southeast Bank Limited"> Southeast Bank Limited </option>
												<option value="South Bangla Agriculture and Commerce Bank Limited"> South Bangla Agriculture and Commerce Bank Limited </option>
												<option value="Trust Bank Limited"> Trust Bank Limited </option>
												<option value="United Commercial Bank Ltd"> United Commercial Bank Ltd </option>
												<option value="Uttara Bank Limited"> Uttara Bank Limited </option>
												<option value="Bengal Commercial Bank Ltd"> Bengal Commercial Bank Ltd </option>
												<option value="Islami Bank Bangladesh Limited"> Islami Bank Bangladesh Limited </option>
												<option value="Al-Arafah Islami Bank Limited"> Al-Arafah Islami Bank Limited </option>
												<option value="EXIM Bank Limited"> EXIM Bank Limited </option>
												<option value="First Security Islami Bank Limited"> First Security Islami Bank Limited </option>
												<option value="Shahjalal Islami Bank Limited"> Shahjalal Islami Bank Limited </option>
												<option value="Social Islami Bank Limited"> Social Islami Bank Limited </option>
												<option value="Union Bank Limited"> Union Bank Limited  </option>
												<option value="Sonali Bank Limited"> Sonali Bank Limited </option>
												<option value="Janata Bank Limited"> Janata Bank Limited </option>
												<option value="Agrani Bank Limited"> Agrani Bank Limited </option>
												<option value="Rupali Bank Limited"> Rupali Bank Limited </option>
												<option value="BASIC Bank Limited"> BASIC Bank Limited </option>
										</select>
                				    </div>
                				    <div class="col-sm-3 trEft trCheque" style='display:none;'>
                					    <label for="date">Deposit Account</label>
                					    <select class="form-control" name="accountNo" id="accountNo">
                							<option value="" selected>~~ Account No ~~</option>
                							<?php
                							$sql = "SELECT id, accountNo, accountName, bankName, branchName 
                                                    FROM tbl_bank_account_info 
                                                    WHERE status = 'Active' AND deleted = 'No'
                                                    ORDER BY id DESC";
                                            $query = $conn->query($sql);
                							while ($prow = $query->fetch_assoc()) {
                								echo "<option value='" . $prow['id'] . "'>".$prow['bankName']." - ".$prow['accountName']."</option>";
                							}
                							?>
                						</select>
                					</div>			
            				    <!--<div class="col-sm-3 trCheque" style='display:none;'>
            					    <label for="chequeBranch">Cheque Branch</label>
            					    <input type="text" class="form-control" name="chequeBranch" id="chequeBranch" placeholder="Cheque Branch">
            				    </div>
            				    <div class="col-sm-3 trCheque" style='display:none;'>
            					    <label for="depositBank">Deposit Bank</label>
            					    <input type="text" class="form-control" name="depositBank" id="depositBank" placeholder="Deposit Bank">
            				    </div>
            				    <div class="col-sm-3 trCheque" style='display:none;'>
            					    <label for="depositBranch">Cheque Branch</label>
            					    <input type="text" class="form-control" name="depositBranch" id="depositBranch" placeholder="Deposit Branch">
            				    </div>
            				    <div class="col-sm-3 trCheque" style='display:none;'>
            					    <label for="depositDate">Deposit Date</label>
            					    <input type="date" class="form-control" name="depositDate" id="depositDate" placeholder="Deposit Date">
            				    </div>-->
                				    <div class="col-sm-3 trCheque" style='display:none;'>
                					    <label for="transitDate">Cheque  Date</label>
                					    <input type="date" class="form-control" name="transitDate" id="transitDate" placeholder="Cheque Date" style="padding: inherit;">
                				    </div>
            				</div>
            				<div class="form-group">
            					<div class="col-sm-3">
            					    <label for="remarks">Remarks</label>
            					    <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Remarks" autocomplete="off" >
            					</div>
            				<?php	
            				    if($getVoucherType == 'paymentReceived'){
            				?>
            					<div class="col-sm-3">
            					    <label for="remarks">Received Type</label>
            					    <select class="form-control" name="receivedType" class="form-control" id="receivedType" style="Width:100%;">
										<option value="Installation"> Installation</option>
										<option value="MMB"> MMB </option>
									</select>
            					</div>
            					<?php }else{
            					    
            					}?>
            				</div>				  
            				<div class="form-group">
            					<div class="col-sm-12">
            					<button type="submit" class="btn btn-primary btn-flat" name="btn_saveVoucher" id="btn_saveVoucher"><i class="fa fa-save"></i> Save Voucher </button>
            				    </div>
            				</div>
            			</form>
                  </div>
                </div>
              </div>
              </div>
              <?php 
                }
              ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="col-xs-6">
                                  <!--<a href="sale-return.php"  class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Sale Return </a>-->
                                </div>
                            	<div class="col-xs-6">
                            		<!--<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>
                            		<div id='divErrorMsg' class='alert alert-danger alert-dismissible' style='margin: -13% -5% -4% 20%;display:none;'></div>-->
                            		<select id="sortData" name="sortData" style='float:right;'>
                                        <option value="0,0">All</option>
                                        <?php
                                            $initialYear = 2020;
                                            $fromDate = date('Y-m-d', strtotime('-7 days'));
                    					    $toDate = date('Y-m-d');
                    				        echo '<option value="'.$fromDate.','.$toDate.'" Selected>7 Days</option>';
                                            $fromDate = date('Y-m-d', strtotime('-30 days'));
                                            $toDate = date('Y-m-d');
                                            echo '<option value="'.$fromDate.','.$toDate.'">30 Days</option>';
                                            $fromDate = date('Y-m-d', strtotime('-180 days'));
                                            $toDate = date('Y-m-d');
                                            echo '<option value="'.$fromDate.','.$toDate.'">180 Days</option>';
                                            for($i = date("Y"); $i >= $initialYear; $i--){
                                                $fromDate = $i.'-01-01';
                                                $toDate = $i.'-12-31';
                                                echo '<option value="'.$fromDate.','.$toDate.'">Year - '.$i.'</option>';
                                            }
                                        ?>
                					</select>
                            	</div>
                            </div>
                            <div class="box-body">
                                <input type="hidden" id="salesType" name="salesType" value="<?php echo $getType;?>"/>
                            	<table id="managePaymentVoucherTable" class="table table-bordered">
                                    <thead>
                                        <th>SN#</th>
                                        <th>Issue Date</th>
                                        <th>Party Name</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Information</th>
                                        <th>Remarks</th>
                                        <th style="width:8%;">Action</th>
                                    </thead>
                            	</table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>   
            
        </div>
       
      <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="dist/js/select2.min.js"></script>
    <script src="includes/js/manageVoucher.js"></script>	
</body>
</html>