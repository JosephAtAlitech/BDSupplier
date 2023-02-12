<?php 
    $conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
?>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<link rel="stylesheet" href="dist/css/select2.min.css" />
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Cheque Module</h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Cheque Information</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	  <link rel="stylesheet" href="css/buttons.dataTables.min.css"/>
      <div class="row">
         <div class="col-xs-12">
           <div class="box">
             <div class="box-header with-border d-flex"  style="display: flex;">
               <div class="d-flex mb-3 justify-content-end" style="justify-content: space-between; display: flex;">
                 <div class="me-auto">
                  <?php if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin'){		?>
                  <a href="#entryNewCheque" data-toggle="modal" class="btn btn-primary btn-sm btn-flat me-auto"><i class="fa fa-plus"></i> Entry Cheque</a>
                  <?php } ?>   
                  <!-- <a href="placedCheque-view.php" class="btn btn-success btn-sm btn-flat me-auto">Placemented Cheques   <i class="fa fa-arrow-right"></i></a> -->
                
                 </div>
                 <div class="ms-auto" style="justify-content:flex-end">
                    <?php  $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
                                   INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
                                   INNER  JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
                                   WHERE tbl_cheque.deleted='No' order by tbl_cheque.id DESC";
                                   $query = $conn->query($sql);
                                   $total =  mysqli_num_rows($query);
                    ?>
                  <a href="#/" onclick="loadChequeEntry('All')" class="btn btn-success btn-sm btn-flat ms-auto">All <sup><?php echo $total; ?></sup></a>

                  <?php  $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
                                  INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
                                  INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
                                  WHERE tbl_cheque.deleted='No' AND tbl_cheque.status='Bounce' order by tbl_cheque.id DESC";
                                   $query = $conn->query($sql);
                                   $total =  mysqli_num_rows($query);
                    ?>
                  <a href="#/" onclick="loadChequeEntry('Bounced')" class="btn btn-success btn-sm btn-flat ms-auto">Bounced <sup><?php echo $total; ?></sup></a>

                  <?php   $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
                                  INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
                                  INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
                                  WHERE tbl_cheque.deleted='No' AND tbl_cheque.status!='Pending' AND  tbl_cheque.status!='Completed'  order by tbl_cheque.id DESC";
                                   $query = $conn->query($sql);
                                   $total =  mysqli_num_rows($query);
                    ?>
                  <a href="#"  onclick="loadChequeEntry('Unsettled')" class="btn btn-success btn-sm btn-flat ms-auto">Unsettled <sup><?php echo $total; ?></sup> </a>

                            <?php  $effectiveDate = date('Y-m-d', strtotime("-5 months", strtotime($toDay)));
                                    $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
                                    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
                                    INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
                                    WHERE tbl_cheque.deleted='No' AND tbl_cheque.status='Pending' AND tbl_cheque.cheque_date<='$effectiveDate'  order by tbl_cheque.id DESC";
                                   $query = $conn->query($sql);
                                   $total =  mysqli_num_rows($query);
                    ?>
                  <a href="#"  onclick="loadChequeEntry('Unplaced')" class="btn btn-success btn-sm btn-flat ms-auto">Unplaced <sup><?php echo $total; ?></sup></a>

                        <?php  $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name  FROM `tbl_cheque`  
                                    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
                                    INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
                                    WHERE tbl_cheque.deleted='No'  AND `cheque_date` BETWEEN NOW() AND (SELECT date FROM `calender_tbl` WHERE date > now() AND day_type='Onday' AND day_type='Offday' ORDER BY date ASC LIMIT 1)";
                                   $query = $conn->query($sql);
                                   $total =  mysqli_num_rows($query);
                    ?>
                  <a href="#"  onclick="loadChequeEntry('nextPlacement')" class="btn btn-success btn-sm btn-flat ms-auto">Next Placement <sup><?php echo $total; ?></sup></a>
              
                 </div>
              </div>
			     <div class="col-xs-6">
				      <div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
		 	     </div>
         </div>
            <div class="box-body">
           
                <input type="hidden" id="type" name="type" value="<?php echo $type;?>" />
                <table id="manageEntryChequeTable" class="table table-bordered" style="width:100%;">
              
                <thead>
                   <th width="4%">SN</th>
                   <th>Party Info [Prayer]</th>
                   <th>Cheque Info</th>
                   <th>Bank Info</th>
                   <th>Party Info [Recever]</th>
                   <th>Placement Date Date</th>
                   <th>Image</th>
                   <th>Status</th>
                   <th width="4%">Action</th>
                </thead>
              </table>
              
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/chequeModule-modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>
<script src="includes/js/manageCheques.js"></script> 

<script>

    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });

    $(document).ready( function() {
        document.getElementById('placementDate').value = new Date().toDateInputValue();
    });​
</script>


</body>
</html>