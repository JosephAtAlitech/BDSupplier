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
if(isset($_GET['page'])){
    $page = $_GET['page'];
        if($page == 'editExpenseType'){
        $id = $_GET['id'];
        $sql_expanseType = "select * from expense_types where id=$id AND deleted='No'";
        $query_expanseType = $conn->query($sql_expanseType);
    	$row_expanseType = $query_expanseType->fetch_assoc();
        echo json_encode($row_expanseType);
     }
} elseif(isset($_POST['saveExpenseType'])) {

 
        

        $ExpenseTypeName = $_POST['name'];
		$created_date = date('Y-m-d H:i:s');
        
        try{    
            $conn->begin_transaction();
            $sql = "INSERT INTO expense_types (name, status, deleted, created_by, created_at ) "
                        . "VALUES ('$ExpenseTypeName','Active', 'No', '$loginID','$created_date' )";
            
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
    
     // Update Expense Type
    }elseif(isset($_POST['action']) == "updateExpenseType") {
        $id = $_POST['expenseTypeId'];
        $expenseTypeName = $_POST['expenseTypeName'];
        $expenseTypeStatus = $_POST['expenseTypeStatus'];
        $toDay= date('H:i:s');
        try{
            $conn->begin_transaction();
            $sql = "UPDATE expense_types set name='$expenseTypeName',status='$expenseTypeStatus',updated_by='$loginID',Updated_date='$toDay' where id='$id'";
            
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
    }
    elseif (isset($_POST['action_delete'])== "deleteExpenseType") {
        $id = $_POST['Id'];
    	
        $sql = "UPDATE expense_types SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id'";
        if ($conn->query($sql)) {
            $query = $conn->query($sql);
    		echo json_encode('Success');
        } else {
            json_encode($conn->error);;
        }
        
        
    }
else{
    /* Display Data from Expense type table */

		$sql = "select * from expense_types where deleted='No'";
		$result = $conn->query($sql);
		$i = 1;
		$output = array('data' => array());

		while ($row = $result->fetch_array()) {
			$id = $row['id'];
			if ($row['status'] == 'Active') {
				// activate status
				$status = "<label class='label label-success'>" . $row['status'] . "</label>";
			} else {
				// deactivate status
				$status = "<label class='label label-danger'>" . $row['status'] . "</label>";
			}
			$button = '<a href="#" onclick="editExpenseType(' . $row['id'] . ')"><button class="btn  btn-primary btn-sm btn-flat btn-space r-1" style="margin-right:4px;"><i class="fa fa-edit"></i> Edit</button></a>';
			$button .= '<a href="#"  onclick="deleteExpenseType(' . $row['id'] . ')"><button class="btn ml-3 btn-danger btn-sm btn-space btn-flat"><i class="fa fa-trash"></i> delete</button></a>';
			$output['data'][] = array(
				$i++,
				$row['name'],
				$status,
				$button,
			);
		} //End while 
		echo json_encode($output);

}

?>