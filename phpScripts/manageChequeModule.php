<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

/*date_default_timezone_set('Asia/Dhaka');*/
$toDay = date('Y-m-d h:i:s', time());
$loginID = $_SESSION['user'];

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

include('resize_image_product.php');

// Get Expensetype data for edit form
if(isset($_POST['saveCheque'])) {
 
        $chequeReceivingDate = $_POST['chequeReceivingDate'];
        $partyType = $_POST['partyType'];
        $voucherType = $_POST['voucherType'];
        $partyId = $_POST['partyId'];
        $bankId = $_POST['bankId'];
        $branchId = $_POST['branchId'];
        $chequeType = $_POST['chequeType'];
        $payTo = $_POST['payTo'];
        $depositeAccount = $_POST['depositeAccount'];
        $chequeNo = $_POST['chequeNo'];
        $chequeDate = $_POST['chequeDate'];
        $amount = $_POST['amount'];
    
        try{    
            $conn->begin_transaction();
            $sql = " INSERT INTO tbl_cheque ( cheque_receiving_date, tbl_party_id, party_type, voucher_type, tbl_bank_id, tbl_branch_id, cheque_type, pay_to, tbl_bank_account_id, cheque_no, cheque_date, amount, status, created_by, created_date, deleted )
             VALUES ( '$chequeReceivingDate', '$partyId', '$partyType', '$voucherType', '$bankId', '$branchId','$chequeType', '$payTo', '$depositeAccount', '$chequeNo', '$chequeDate' , '$amount', 'Pending', '$loginID', '$toDay', 'No' ) ";
            
			if ($conn->query($sql)) {
    		   
    			$conn->commit();
    			echo json_encode('Success');
            } else {
                echo json_encode($conn->error);
            }
    		
        }catch(Exception $e){
    		$conn->rollBack();
    		echo 'RollBack';
    	}
        $conn->close();
    
 
    }     // Delete Exp Cheque
    elseif (isset($_POST['action_delete'])== "deleteCheque") {
        $id = $_POST['Id'];
    	
        $sql = "UPDATE tbl_cheque SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id'";
        if ($conn->query($sql)) {
    		echo json_encode(['status'=> "Success"]);
        } else {
            json_encode($conn->error);
        }
        
        
    }
    elseif (isset($_POST['deletePlacement'])== "deletePlacement") {
        $id = $_POST['id'];
        $chequeId = $_POST['chequeId'];
        $chequeNo = $_POST['chequeNo'];
        if( $chequeId ===null && $chequeNo==null){
                $sql = "UPDATE tbl_cheque_placement SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id' ";
                if ($conn->query($sql)){
                        echo json_encode('Success');
                    }else {
                        json_encode($conn->error);
                } 
        
        }else{
            $sql = "UPDATE tbl_cheque_placement SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id' ";
            if ($conn->query($sql)){
                $sql = "UPDATE tbl_cheque SET status='Pending' WHERE id = '$chequeId'";
                if ($conn->query($sql)){ 
                    $sql = "UPDATE tbl_paymentvoucher SET deleted='Yes', deletedDate='$toDay', deletedBy='$loginID' WHERE chequeNo = '$chequeNo' ";
                    if ($conn->query($sql)){ 
                        echo json_encode('Success');
                    }
                    else {
                        json_encode($conn->error);  
                   }
            } 
            else {
                json_encode($conn->error);
         }
        }
 }
       }       //$chequeStatus = $_POST['chequeStatus'];
      
        
    
    elseif (isset($_GET['page1']) == "ShowChequeData") {
        $id = $_GET['id'];
    	
        $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_bank.bank_name,tbl_bank_branch.branch_name FROM `tbl_cheque`  
        JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
        JOIN tbl_bank_branch on tbl_cheque.tbl_branch_id = tbl_bank_branch.id
        JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
        WHERE tbl_cheque.id=' $id'";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
       
        if ($data ) {
    		echo json_encode($data);
        } else {
            json_encode($conn->error);;
        }
            
    } elseif (isset($_POST['loadParty'])== "loadParty") {
        $type = $_POST['partyType'];
       
        $sql = "SELECT id ,partyName FROM `tbl_party` WHERE status='Active' AND tblType='$type' ORDER BY `id`  DESC";
        $query = $conn->query($sql);
        //$datadd = mysqli_fetch_assoc($query);
       
        while ($prow = $query->fetch_array()) {
            $row[] =$prow;
        }
        echo json_encode($row);
    }
    elseif(isset($_POST['saveChequePlacement'])) {
        $chequeId = $_POST['chequeId'];
        $chequeNo = $_POST['chequeNo'];
        $partyId = $_POST['partyId'];
        $bankName = $_POST['bankName'];
        $partyType = $_POST['partyType'];
        $partyName = $_POST['partyName'];
        $amount = $_POST['amount'];
        $chequeDate = $_POST['chequeDate'];
        $placementDate = $_POST['placementDate'];
        $chequeStatus = $_POST['chequeStatus'];
        $bounceAndClearanceDate = $_POST['bounceAndClearanceDate'];
        $status = $_POST['chequeStatus'];
        if($chequeStatus === 'Clear'){
            $status = 'Completed';
        }
        if($partyType == 'WICustomer'){
            $partyType = "WalkinCustomer";
            $chequeVoucherType = "WalkinSale";
        }
        else if($partyType == 'Customers'){
            $partyType = "Party";
            $chequeVoucherType = "PartySale";
        }
        
        try{    
            $conn->begin_transaction();
            $sql = " INSERT INTO tbl_cheque_placement ( tbl_cheque_id, placement_date, cheque_status, clearance_date, created_by, created_date)
                    VALUES ( '$chequeId', '$placementDate', '$chequeStatus', '$bounceAndClearanceDate', '$loginID', '$toDay') ";
            
			if ($conn->query($sql)) {
                $sqlCheque  = "UPDATE tbl_cheque SET status='$status'  WHERE id='$chequeId'";
                if ($conn->query($sqlCheque)) {
                    if($chequeStatus === 'Clear'){
                            $sqlVoucherNo = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM tbl_paymentVoucher WHERE tbl_partyId='$partyName' AND customerType = 'Party'";
                            $query = $conn->query($sqlVoucherNo);
                            while ($prow = $query->fetch_assoc()) {
                                $voucherNo = $prow['voucherCode'];
                            }
                            if($voucherNo == ""){
                                $voucherNo = "000001";
                            }
                            $sqlVoucher = "INSERT INTO tbl_paymentVoucher(tbl_partyId, amount, entryBy, paymentMethod, chequeNo, paymentDate, chequeIssueDate, type, remarks, voucherNo,voucherType, customerType, chequeBank,entryDate) 
                                    VALUES ('$partyId','$amount','$loginID','Cheque','$chequeNo','$bounceAndClearanceDate','$chequeDate','paymentReceived','Voucher Entry for party Sale transaction','$voucherNo','$chequeVoucherType', '$partyType', '$bankName','$toDay')";
                            if($conn->query($sqlVoucher)){
                                $conn->commit();
                                echo json_encode('Success');
                            }else{
                                echo json_encode('Error: '.$conn->error);
                            }
                    }
                    else{
                        $conn->commit();
                        echo json_encode('Success');
                    }
                }
                else {
                    echo json_encode($conn->error);
                }
            } else {
                echo json_encode($conn->error);
            }
            $conn->close();
    		
        }catch(Exception $e){
    		$conn->rollBack();
    		echo 'RollBack';
    	}
           
    }  //Show Cheque Placement Data
    elseif(isset($_POST['chequePlacementData']) == "chequePlacementData"){
        $chequeId= $_POST['chequeId'];
     
        $sql = "SELECT tbl_cheque_placement.*, tbl_cheque.cheque_no FROM tbl_cheque_placement  
                join tbl_cheque on tbl_cheque_placement.tbl_cheque_id =tbl_cheque.id
                WHERE tbl_cheque_id = $chequeId AND tbl_cheque_placement.deleted ='No' ORDER BY created_date DESC";
		$results = $conn->query($sql);	
        $datas =[];
		while ($Arows = $results->fetch_array()) {
            $datas[]= $Arows;    
		}//End while
        
		echo json_encode($datas);
    }

else{
    /* Display Data from Cheque table */

		$sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_bank.bank_name,tbl_bank_branch.branch_name FROM `tbl_cheque`  
                JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
                JOIN tbl_bank_branch on tbl_cheque.tbl_branch_id = tbl_bank_branch.id
                JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
                WHERE tbl_cheque.deleted='No'";
		$result = $conn->query($sql);
		$i = 1;
		$output = array('data' => array());

		while ($row = $result->fetch_array()) {
			$id = $row['id'];
		 if($row['status'] == "Completed"){
            $button ='	<div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">';
            $button .= ' <li><a href="#" data-toggle="modal" onclick="openChequePlanement(' . $id . ') " ><i class="fa fa-mail-reply"></i>Cheque Placement</a></li>';
         }
         else{
            $button ='	<div class="btn-group">
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-gear tiny-icon"></i> <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">';
            $button .=  '<li><a href="#" onclick="deleteEntryCheque(' . $id . ')"><i class="fa fa-edit tiny-icon"></i>Delete</a></li>';
            $button .= ' <li><a href="#" data-toggle="modal" onclick="openChequePlanement(' . $id . ') " ><i class="fa fa-mail-reply"></i>Cheque Placement</a></li>';
         }
			          
                
            $button .= '</ul></div>';
            $output['data'][] = array(
				$i++,
				$row['cheque_no'].' Date: '.$row['cheque_receiving_date'],
                $row['bank_name'] .' Branch: '.$row['branch_name'] ,
                $row['partyName'],
                $row['pay_to'],
                $row['cheque_date'],
                $row['status'],
				$button,
			);
		} //End while 
		echo json_encode($output);
}

?>