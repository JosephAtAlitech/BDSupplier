



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
      <h1>Cheque Placement List</h1>
      <ol class="breadcrumb">
        <li><a href="user-home.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Cheque Placement List</li>
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
                   
			  </div>
			  <div class="col-xs-6">
				<div id='divMsg' class='alert alert-success alert-dismissible successMessage'></div>
			</div>
            </div>
            <div class="box-body">
                <input type="hidden" id="type" name="type" value="<?php echo $type;?>" />
                <table id="managePlacedChequeTable" class="table table-bordered" style="width:100%;">
                <thead>
                   <th width="4%">SN</th>
                   <th>Placement Date</th>
                   <th>Clearance date</th>
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


 
</div>
<?php include 'includes/scripts.php'; ?>
<script src="dist/js/select2.min.js"></script>

<script src="includes/js/managePlacedCheque.js"></script> 
</body>
</html>