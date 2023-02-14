<?php 
	$conPrefix = '';
    include 'includes/session.php'; 
    include 'includes/header.php'; 
?>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <?php include 'includes/navbar.php'; ?>
      <?php include 'includes/menubar.php'; ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Bank Account Information
          </h1>
          <ol class="breadcrumb">
            <li><a href="manage-view.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Banking Information</li>
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
    				<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Add Account</a>
                </div>
    			<div class="col-xs-6">
    				<div id='divMsg' class='alert alert-success alert-dismissible' style='margin: -4% -5% -4% 20%;display:none;'></div>
    			</div>
    			</div>
                <div class="box-body">
                  <table id="manageBankAccountTable" class="table table-bordered">
                    <thead>
                      <th>SN</th>
                      <th>Account No</th>
                      <th>Account Name</th>
                      <th>Bank Name</th>
                      <th>Branch Name</th>
                      <th>Swift Code</th>
                      <th>Balance</th>
                      <th>Status</th>
                      <th>Action</th>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>   

      </div>
       
      <?php include 'includes/footer.php'; ?>
      <?php include 'includes/manageBankAccount-modal.php'; ?>
    </div>
    <script src="notify.js"></script>
    <?php include 'includes/scripts.php'; ?>
    <script src="includes/js/manageBankAccount.js"></script> 
    </body>
</html>
