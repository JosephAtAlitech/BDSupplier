<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
$loginID = $_SESSION['user'];

/*date_default_timezone_set('Asia/Dhaka');*/
$toDay = date('Y-m-d h:i:s', time());

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

include('resize_image_product.php');

// Save Expense data
if(isset($_POST['saveExpense'])) {

    $expense_date = $_POST['expenseDate'];
    $expense_cause = $_POST['expenseCause'];
    $expense_typeId = $_POST['expenseType'];
    $expense_by = $_POST['expenseBy'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $voucherNo='00001';
   
    $sq = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM `tbl_paymentVoucher` WHERE deleted='No'";
	$result2 = $conn->query($sq);
  
   // $maxCode=max($row['voucherNo']);
   
    while ($prow = $result2->fetch_assoc()) {
        $voucherNo = $prow['voucherCode'];
      
    }
    if ($voucherNo == "") {
        $voucherNo = "000001";
    }
     //$maxCode =LPAD(IFNULL(max($row['voucherNo']),0)+1, 6, 0);
   
    //  $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
    //$maxCode='000099';
    $party_id = $expense_by;
    //$voucherNo = $maxCode;

    $payment_method = "Cash";
    $paymentDate = $toDay;
    // $expense_id = $expenseId;
    $type = "Payment";
    $voucherType = "Expense";
    $remarks = "[Voucher Entry for Expense Cause] " . $expense_cause;


    try{    
        $conn->begin_transaction();
        $sql = "INSERT INTO `expenses` (expense_date, expense_cause, expense_type_id, tbl_user_id, amount, created_by, created_date, status)
                    VALUES ('$expense_date','$expense_cause',$expense_typeId,'$expense_by',$amount,'$loginID','$toDay','Active')";
        $sql1=$conn->query($sql);
        $expenseId = $conn->insert_id;
        if ( $sql1== true) {
           
             $sql2 = "INSERT INTO `tbl_paymentVoucher` (tbl_partyId, voucherNo, amount, paymentMethod, paymentDate, tbl_expense_id, status, type, voucherType, remarks, entryBy, entryDate)
                    VALUES ('$party_id','$voucherNo','$amount','$payment_method','$paymentDate','$expenseId','Active','$type','$voucherType','$remarks','$loginID','$toDay')";

            if ($conn->query($sql2)) {
                    $conn->commit();
                    echo json_encode('Success');
                    } else {
                    echo json_encode($conn->error);
                    }
            }
            else {
                echo json_encode($conn->error);
        }
        
    }catch(Exception $e){
        $conn->rollBack();
        echo 'RollBack';
    }
    $conn->close();
}

// Delete Expense Type
elseif (isset($_POST['action']) == "action_delete") {
        $id = $_POST['id'];
    	
        $sql = "UPDATE expenses SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id'";
        if ($conn->query($sql)) {
            $query = $conn->query($sql);
    		echo json_encode('Success');
        } else {
            json_encode($conn->error);;
        }
        
        
    }
      
else{
    /* Display Data from Expense table */

		$sql = "SELECT expenses.id, tbl_users.fname,tbl_paymentVoucher.voucherNo, tbl_users.lname, expense_types.name, expenses.expense_date, expenses.expense_cause, expenses.amount, expenses.status  
                FROM `expenses` 
                join `expense_types` on expenses.expense_type_id = expense_types.id 
                join `tbl_paymentVoucher` on expenses.id = tbl_paymentVoucher.tbl_expense_id 
                join tbl_users on expenses.tbl_user_id = tbl_users.id  
                where expenses.deleted='No'";
		$result = $conn->query($sql);
		$i = 1;
      
		$output = array('data' => array());

		while ($row = $result->fetch_array() ) {
           
			$id = $row['id'];
			if ($row['status'] == 'Active') {
				// activate status
				$status = "<label class='label label-success'>" . $row['status'] . "</label>";
			} else {
				// deactivate status
				$status = "<label class='label label-danger'>" . $row['status'] . "</label>";
			}
	
			$button = '<a href="#"  onclick="deleteExpense(' . $id . ')"><button class="btn ml-3 btn-danger btn-sm btn-flat"><i class="fa fa-trash"></i> delete</button></a>';
			$output['data'][] = array(
				$i++,
				$row['expense_date'],
                $row['expense_cause'],
                $row['voucherNo'],
                $row['name'],
                $row['amount'],
                $row['fname']." " .$row['lname'],
				
				$button,
			);
		} //End while 
		echo json_encode($output);
}

?>