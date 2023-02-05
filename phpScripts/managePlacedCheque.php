<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

date_default_timezone_set('Asia/Dhaka');

$toDay = date('Y-m-d h:i:s', time());
$loginID = $_SESSION['user'];

include('resize_image_product.php');


if(isset($_POST['action_delete'])== "deletePlacedCheque") {
    $id = $_POST['Id'];
    
    $sql = "UPDATE tbl_cheque_placement SET deleted='Yes', deleted_date='$toDay', deleted_by='$loginID' WHERE id = '$id'";
    if ($conn->query($sql)) {
        $query = $conn->query($sql);
        echo json_encode('Success');
    } else {
        json_encode($conn->error);
    }
    
    
} else{

    $sql = "SELECT * FROM `tbl_cheque_placement`  
    WHERE deleted='No'";

    $result = $conn->query($sql);
    $i = 1;
    $output = array('data' => array());

    while($row = $result->fetch_array()) {
  //  $button = '<a href="#"  onclick="deletePlacedCheque(' . $row['id'] . ')"><button class="btn ml-3 btn-danger btn-sm btn-space btn-flat"><i class="fa fa-trash"></i> delete</button></a>';
    $output['data'][] = array(
        $i++,
        $row['placement_date'],
        $row['clearance_date'],
        $row['cheque_status']
    );
}
echo json_encode($output);

}

?>