<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';

/*date_default_timezone_set('Asia/Dhaka');*/
$toDay = date('Y-m-d h:i:s', time());
$loginID = $_SESSION['user'];

date_default_timezone_set('Asia/Dhaka');
//$toDay = (new DateTime($test))->format("Y-m-d H:i:s");

include('resize_image_product.php');


// Get ExpenseReport data 
if(isset($_GET['date'])){
    $date = $_GET['date'];

    $sql = "SELECT
        tbl_paymentvoucher.*,
        tbl_sales.grandTotal,
        tbl_sales.paidAmount,
        tbl_purchase.totalAmount,
        tbl_party.partyName,
        tbl_party.partyAddress
    FROM
        tbl_paymentvoucher
        left join tbl_purchase ON tbl_paymentvoucher.tbl_purchaseId = tbl_purchase.id
        left join tbl_sales ON tbl_paymentvoucher.tbl_sales_id = tbl_sales.id
        left join tbl_party ON tbl_paymentvoucher.tbl_partyId = tbl_party.id
    where tbl_paymentvoucher.deleted = 'No' 
    AND tbl_paymentvoucher.paymentDate ='$date'
    AND tbl_paymentvoucher.status= 'Active' 
    AND tbl_paymentvoucher.paymentMethod IN ('Cash', 'p')
    AND tbl_paymentvoucher.type IN ('payment', 'paymentReceived', 'partyPayable')";
    $result = $conn->query($sql);

    $i = 1;
    $info = '';
    $button = '';
    $data = '';
    $cashIn = 0;
    $cashOut = 0;
    $balance = 0;
    $amount =0;
    while ($row = $result->fetch_array()) {
        
                    if ($row['type'] == "payment") {
                        $cashOut +=  $row['amount'] ;
                        $balance -= $row['amount'] ;
                        $info .= '<tr>
                             <td class="text-center">' . $i++ . '</td>' .
                            '<td class="text-left">' . $row['remarks'] . '<br>' . $row['partyName']  . ' | ' .$row['partyAddress']  . '</td>' .
                            '<td class="text-center">'.  $row['voucherNo'] . '</td>' .
                            '<td class="text-center">' . $row['grandTotal']  . '</td>' .
                            '<td class="textRight">  -  </td>' .
                            '<td>' . $cashOut . '</td>' .
                            '<td class="textRight">' . number_format($balance) . '</td>' .
                            '</tr>';
                    } else if ($row['type'] == "paymentReceived") {
                        $cashIn +=  $row['amount'];
                        $balance += $row['amount'] ;
                        $info .= '<tr>
                             <td class="text-center">' . $i++ . '</td>' .
                            '<td class="text-left">' . $row['remarks']. '<br>' . $row['partyName'] . ' | ' . $row['partyAddress']  . '</td>' .
                            '<td class="text-center">' . $row['voucherNo'] . '</td>' .
                            '<td class="text-center">' . $row['grandTotal']  . '</td>' .
                            '<td>' . $cashIn . '</td>' .
                          
                            '<td class="textRight">  -  </td>' .
                            '<td class="textRight">' . number_format($balance) . '</td>' .
                            '</tr>';
                    } 
                   // else {
                   /*      $info .= '<tr>
                             <td class="text-center">' . $i++ . '</td>' .
                            '<td class="text-left">' .$row['type']  . '<br>' .$row['partyName'] . ' | ' . $row['partyAddress']  . '</td>' .
                            '<td class="text-center">' .$row['voucherNo']. '</td>' .
                            '<td class="text-center">' .  $row['grandTotal']  . '</td>' .
                            '<td class="textRight"></td>' .
                            '<td></td>' .
                            '<td class="textRight"></td>' .
                            '</tr>';
                    } */
    } //End while 
    $button .= '';
    echo $info;
}

?>