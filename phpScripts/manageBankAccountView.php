<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
if (isset($_POST['id'])) {
    $bankAccountId=$_POST['id'];
    $sql = "SELECT id, accountNo, accountName, bankName, branchName, swiftCode, status, address, opening_balance
            FROM tbl_bank_account_info
            WHERE deleted='No' AND id='$bankAccountId'";
    $result = $conn->query($sql);
    $output = array('data' => array());
    echo json_encode($result->fetch_array());
}
else{
    $sql = "SELECT id, accountNo, accountName, bankName, branchName, swiftCode, status, opening_balance, current_balance
            FROM tbl_bank_account_info
            WHERE deleted='No'
            order by id DESC";
    $result = $conn->query($sql);
    $i=1;
    while ($row = $result->fetch_array()) {
        $bankAccountId = $row['id'];
        // active 
        if ($row['status'] == 'Active') {
            // activate status
            $status = "<label class='label label-success'>" . $row['status'] . "</label>";
        } else {
            // deactivate status
            $status = "<label class='label label-danger'>" . $row['status'] . "</label>";
        }
        $button = '<a href="#" onclick="editBankAccount(' . $bankAccountId . ')"><button class="btn btn-warning btn-sm btn-flat"><i class="fa fa-edit"></i> Edit</button></a>';
        $output['data'][] = array(
            $i++,
            $row['accountNo'],
            $row['accountName'],
            $row['bankName'],
            $row['branchName'],
            $row['swiftCode'],
            "<strong>Opening Balence: </strong>".$row['opening_balance']."<br><strong>Current Balence: </strong>".$row['current_balance'],
            $status,
            $button
        );
    } // /while 
    echo json_encode($output);
}

$conn->close();
?>