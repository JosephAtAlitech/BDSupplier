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


        	//Below Part for image upload
    	$imageFileType = '';
    	$path = '';
    	$target_dir = "../images/cheques/cheque_img/";
    	if(isset($_FILES["file"]["name"])) 
    	{
    		if($_FILES["file"]["name"]!='')
    		{
    			$check = getimagesize($_FILES["file"]["tmp_name"]);
    			if($check) 
    			{
    				//echo "File is an image - " . $check["mime"] . ".";
    				$uploadOk = 1;
    			}
    			else 
    			{
    				//echo "File is not an image.";
    				//echo "<script type='text/javascript'>alert('Sorry, File is not an image.');</script>";
    				$uploadOk = 1;
    			}
    			$target_file = $target_dir .$chequeNo.'_'.basename($_FILES["file"]["name"]);
    			//big size image//
    			$path_360 = '../images/cheques/cheque_img/'.str_replace(' ', '_',$chequeNo.'_'.$_FILES["file"]["name"]);
    			resize360(360,$path_360);
    			$path_100 = '../images/cheques/thumb/'.str_replace(' ', '_',$chequeNo.'_'.$_FILES["file"]["name"]);			
    			resize(100,$path_100);
    			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    			$path =str_replace(' ', '_',$chequeNo.'_'.$_FILES["file"]["name"]);
    		}else{
    		$target_file='';
    		}
    	}

        try{    
            $conn->begin_transaction();
            $sql = " INSERT INTO tbl_cheque ( cheque_receiving_date, tbl_party_id, party_type, voucher_type, tbl_bank_id, tbl_branch_id, cheque_type, pay_to, tbl_bank_account_id, cheque_no, cheque_date, amount, status, cheque_image, created_by, created_date, deleted )
             VALUES ( '$chequeReceivingDate', '$partyId', '$partyType', '$voucherType', '$bankId', '$branchId','$chequeType', '$payTo', '$depositeAccount', '$chequeNo', '$chequeDate' , '$amount', 'Pending','$path', '$loginID', '$toDay', 'No' ) ";
            
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
        $bankAccountId = $_POST['bankAccountId'];
        $amount = $_POST['amount'];
        $status = $_POST['chequeStatus'];
        
        if($status == "Completed"){
            $sql = "UPDATE tbl_cheque_placement SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id' ";
            if ($conn->query($sql)){
                $sql2 = "UPDATE tbl_cheque SET status='Pending' WHERE id = '$chequeId'";
                if ($conn->query($sql2)){ 
                    $sql3 = "UPDATE tbl_paymentvoucher SET deleted='Yes', deletedDate='$toDay', deletedBy='$loginID' WHERE chequeNo = '$chequeNo' ";
                    if ($conn->query($sql3)){ 
                        $sqlBank ="UPDATE tbl_bank_account_info SET current_balance = current_balance - $amount WHERE id = $bankAccountId";
                        
                        if($conn->query($sqlBank)){
                           echo json_encode('Success');
                        }else {
                           json_encode($conn->error);  
                        }
                    }else {
                        json_encode($conn->error);  
                    }
                }else {
                    json_encode($conn->error);
                }
            }
        }elseif($status == "Cancel"){
            $sql = "UPDATE tbl_cheque_placement SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id' ";
            if ($conn->query($sql)){
                $sql2 = "UPDATE tbl_cheque SET status='Pending' WHERE id = '$chequeId'";
                if ($conn->query($sql2)){ 
                    echo json_encode('Success');
                }
                else {
                    json_encode($conn->error);
                }
        }
    }else{
         
            $sql = "UPDATE tbl_cheque_placement SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id' ";
            if ($conn->query($sql))
            { 
                    echo json_encode(["Success"]);
            } else {
                    json_encode($conn->error);
            } 
        
        }
    }       //$chequeStatus = $_POST['chequeStatus'];
      
        
    
    elseif (isset($_GET['page1']) == "ShowChequeData") {
        $id = $_GET['id'];
    
        $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_bank.bank_name FROM `tbl_cheque`  
        JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
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
        $bankAccountId = $_POST['bankAccountId'];
        $bounceAndClearanceDate = $_POST['bounceAndClearanceDate'];
        $status = $_POST['chequeStatus'];
        $remarks = $_POST['remarks'];
       
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
            $sql = " INSERT INTO tbl_cheque_placement ( tbl_cheque_id, placement_date, cheque_status, remarks, clearance_date, created_by, created_date)
                    VALUES ( '$chequeId', '$placementDate', '$chequeStatus','$remarks', '$bounceAndClearanceDate', '$loginID', '$toDay') ";
            
			if ($conn->query($sql)) {
                if($status == "Cancel"){
                    $status = "Decline";
                }
                $sqlCheque  = "UPDATE tbl_cheque SET status='$status'  WHERE id='$chequeId'";
                if ($conn->query($sqlCheque)) {
                    if($chequeStatus === 'Completed'){
                            $sqlVoucherNo = "SELECT LPAD(IFNULL(max(voucherNo),0)+1, 6, 0) as voucherCode FROM tbl_paymentVoucher WHERE tbl_partyId='$partyName' AND customerType = 'Party'";
                            $query = $conn->query($sqlVoucherNo);
                            while ($prow = $query->fetch_assoc()) {
                                $voucherNo = $prow['voucherCode'];
                            }
                            if($voucherNo == ""){
                                $voucherNo = "000001";
                            }
                            $remarks="Cheque Entry for ". $partyType ."  And Voucher No:".$voucherNo." Amount: ".$amount;
                            $sqlVoucher = "INSERT INTO tbl_paymentVoucher(tbl_partyId, amount, entryBy, paymentMethod, chequeNo, paymentDate, chequeIssueDate, type, remarks, voucherNo,voucherType, customerType, chequeBank,entryDate) 
                                    VALUES ('$partyId','$amount','$loginID','Cheque','$chequeNo','$bounceAndClearanceDate','$chequeDate','paymentReceived','$remarks','$voucherNo','$chequeVoucherType', '$partyType', '$bankName','$toDay')";
                            
                            if($conn->query($sqlVoucher)){
                                $sqlBank ="UPDATE tbl_bank_account_info SET current_balance = current_balance + $amount WHERE id = $bankAccountId";
                                
                                if($conn->query($sqlBank)){
                                    $conn->commit();
                                    echo json_encode('Success');
                                }else{
                                    echo json_encode('Error: '.$conn->error);
                                }
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
     
        $sql = "SELECT tbl_cheque_placement.*, tbl_cheque.cheque_no, tbl_cheque.amount, tbl_cheque.tbl_bank_account_id FROM tbl_cheque_placement  
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
       // echo $_GET('type');
        /* Display Data from Cheque table */
if($_GET['type']== 'Bounced'){
    $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
    INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
    WHERE tbl_cheque.deleted='No' AND tbl_cheque.status='Bounce' order by tbl_cheque.id DESC";
}else if($_GET['type']== 'Unsettled'){

    $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
    INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
    WHERE tbl_cheque.deleted='No' AND tbl_cheque.status!='Pending' AND  tbl_cheque.status!='Completed' AND  tbl_cheque.status!='Decline'   order by tbl_cheque.id DESC";
}
else if($_GET['type']== 'Unplaced'){

    $effectiveDate = date('Y-m-d', strtotime("-5 months", strtotime($toDay)));
    $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
    INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
    WHERE tbl_cheque.deleted='No' AND tbl_cheque.status='Pending' AND tbl_cheque.cheque_date<='$effectiveDate'  order by tbl_cheque.id DESC";
}else if($_GET['type']== 'nextPlacement'){
    $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name  FROM `tbl_cheque`  
    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
    INNER JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
    WHERE tbl_cheque.deleted='No'  AND `cheque_date` BETWEEN NOW() AND (SELECT date FROM `calender_tbl` WHERE date > now() AND day_type='Onday' AND day_type='Offday' ORDER BY date ASC LIMIT 1)";
}
else{    
    $sql = "SELECT tbl_cheque.* , tbl_party.partyName, tbl_party.partyAddress,tbl_party.tblCountry,tbl_party.partyPhone, tbl_bank.bank_name FROM `tbl_cheque`  
    INNER JOIN tbl_bank on tbl_cheque.tbl_bank_id = tbl_bank.id
    INNER  JOIN tbl_party on tbl_cheque.tbl_party_id = tbl_party.id
    WHERE tbl_cheque.deleted='No' order by tbl_cheque.id DESC";
}
		
		$result = $conn->query($sql);
		$i = 1;
		$output = array('data' => array());

		while ($row = $result->fetch_array()) {
			$id = $row['id'];
            if($row['cheque_image'] == '' || $row['cheque_image'] == ' '){
                $chequeImage = "images/broken_image.png";
            }else{
                $chequeImage = "images/cheques/thumb/".$row['cheque_image'];
            }
            
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
            if ($row['status'] == 'Pending') {
				// Pending status
				$status = "<label class='label label-primary'>" . $row['status'] . "</label>";
			} elseif ($row['status'] == 'Bounce')  {
				// Bounce status
				$status = "<label class='label label-warning'>" . $row['status'] . "</label>";
			}
            elseif ($row['status'] == 'Completed')  {
				// Completed status
				$status = "<label class='label label-success'>" . $row['status'] . "</label>";
			}
            elseif ($row['status'] == 'Decline')  {
				// Completed status
				$status = "<label class='label label-danger'>" . $row['status'] . "</label>";
			}
           
            // retriving last placement and clearace date
            $sql2= "SELECT placement_date,clearance_date FROM `tbl_cheque_placement` WHERE tbl_cheque_id='$id' order by id DESC limit 1";
            $req= $conn->query($sql2);
            $rowData= $req->fetch_assoc();
            if($rowData['placement_date']){
                $placementDate ='<strong>Placement Date:</strong>'.$rowData['placement_date'].'<br><strong>Clearence Date:</strong>  '.$rowData['clearance_date'] ;
            }else{
                $placementDate='No placement yet';
            }
            $output['data'][] = array(
				$i++,
                $row['partyName'].'<br><strong>Address:</strong> '.$row['partyAddress'].','.$row['tblCountry'].'<br><strong>Contact:</strong>'.$row['tblCountry'],
				'<strong>Cheque No:</strong>'.$row['cheque_no'].'<br><strong>Receving Date:</strong>   '.$row['cheque_receiving_date'].'<br><strong>Amount:</strong>   '.$row['amount'],
                $row['bank_name']  ,
                'BD Suppliers <br><strong>Pay To: </strong>'.$row['pay_to'],
                $placementDate,
                '<img src="'.$chequeImage.'" style="width:70px; height:50px;"/>',
                $status,
				$button
			);
		} //End while 
		echo json_encode($output);
}

?>