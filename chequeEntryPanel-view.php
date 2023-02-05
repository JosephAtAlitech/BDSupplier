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
            <div class="box-header with-border">
			   <div class="col-xs-6">
                    <?php
                        if(strtolower($_SESSION['userType']) == "admin coordinator" || strtolower($_SESSION['userType']) == "admin support" || strtolower($_SESSION['userType']) == "admin support plus" || strtolower($_SESSION['userType']) == 'super admin'){					
                    ?>
					<a href="#entryNewCheque" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Entry Cheque</a>
         
					<?php
                        }
					?>

            <!-- <a href="#eventEntry" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Event Entry</a> -->
            <!-- <a href="#expenseHead" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Expense Head</a>
            <a href="#expenseForm" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Expense Form</a> -->
            <a href="placedCheque-view.php" class="btn btn-success btn-sm btn-flat">Placemented Cheques   <i class="fa fa-arrow-right"></i></a>
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
                   <th>Cheque Info</th>
                   <th>Bank Info</th>
                   <th>Party Info [Payer]</th>
                   <th>Party Info [Recever]</th>
                   <th>Cheque Date</th>
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