
<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
$loginID = $_SESSION['user'];

/*date_default_timezone_set('Asia/Dhaka');*/
$toDay = date('Y-m-d h:i:s', time());

date_default_timezone_set('Asia/Dhaka');


$action = isset($_POST['action']);

if($action == "getDailyReport"){
    $date = $_POST['date'];

   // $minusDaysFromDate =  date_create($date)->modify('-1 days')->format('Y-m-d');
   // $from_warehouse = Session::get("warehouse")[0]["id"];

    $openingData = "SELECT * FROM daily_reports 
                    WHERE deleted = 'No' AND date < '$date'
                    ORDER BY date DESC LIMIT 1";
    $result = $conn->query($openingData);
    
    if($result){
        $sql1 = "SELECT * FROM daily_reports
                 WHERE deleted = 'No' AND date < '$date'
                 ORDER BY date DESC LIMIT 1";
        $data = $conn->query($sql1);
        $lastDailyReport = $data->fetch_assoc();
    }else{
        $sql1 = "SELECT * FROM daily_reports
        WHERE deleted = 'No' AND date = $date
        ORDER BY date DESC LIMIT 1";
        $data = $conn->query($sql1);
        $lastDailyReport = $data->fetch_assoc();
       // $lastDailyReport = 0;
    }
        //-----today payment, expense , payment received-------//
      
        $sql3 ="SELECT 
                    tbl_paymentvoucher.*, tbl_sales.grandTotal, tbl_purchase.totalAmount 
                from 
                    tbl_paymentvoucher 
                left join 
                    tbl_purchase ON tbl_paymentvoucher.tbl_purchaseId= tbl_purchase.id 
                left join 
                    tbl_sales ON tbl_paymentvoucher.tbl_sales_id = tbl_sales.id 
                    where tbl_paymentvoucher.deleted = 'No' 
                    AND tbl_paymentvoucher.paymentDate ='$date'
                    AND tbl_paymentvoucher.status= 'Active' 
                    AND tbl_paymentvoucher.paymentMethod = 'Cash' 
                    AND tbl_paymentvoucher.type IN ('payment', 'paymentReceived')";

        $result3 = $conn->query($sql3);

        $purchasePaymentTotal = 0;
        $paymentVoucher = 0;
        $paymentReceivedVoucher = 0;
        $saleReceivedTotal = 0;
        $expanseAmount = 0;
		$i = 1;

		while ($todayReport = $result3->fetch_array()) {

            if ($todayReport['tbl_purchaseId'] > 0 && $todayReport['type'] == "payment") {
                $purchasePaymentTotal += $todayReport['amount'];
            }
            else if ($todayReport['tbl_expense_id'] > 0 && $todayReport['type'] == "payment") {
                $expanseAmount += $todayReport['amount'];
            }
            else if ( $todayReport['tbl_sales_id'] > 0 && $todayReport['type'] == "paymentReceived") {
                $saleReceivedTotal += $todayReport['amount'];
            }
            else if ( $todayReport['type'] == "payment") {
                $paymentVoucher += $todayReport['amount'];
            }
            else if ( $todayReport['type'] == "paymentReceived") {
                $paymentReceivedVoucher += $todayReport['amount'];
            }

        }

        $TotalPayment = $purchasePaymentTotal + $paymentVoucher;
        $todayBalance = ($saleReceivedTotal + $paymentReceivedVoucher) - ($TotalPayment + $expanseAmount);
        $todayReportArray = [$purchasePaymentTotal , $saleReceivedTotal , $expanseAmount ,  $todayBalance];
        
        $todayReportTable = '<tr><td>Purchase Payment(-)</td><td>'.number_format($TotalPayment, 2) .                               '</td></tr>
                             <tr><td>Expense(-)         </td><td>'.number_format($expanseAmount, 2) .                              '</td></tr>
                             <tr><td>Payment Received(+)</td><td>'.number_format($saleReceivedTotal + $paymentReceivedVoucher, 2) .'</td></tr>
                             <tr><td>Balance            </td><td>'.number_format($todayBalance, 2) .                               '</td></tr>';
        //-----End today payment, expense , payment received-------//
        
        $data = [$todayReportTable , $lastDailyReport ,  $todayReportArray];

        echo json_encode($data);

}

if(isset($_POST['saveReport'])){
   
        $date = $_POST['date'];
        $openingBalance = $_POST['openingBalance'];
        $totalAmount = $_POST['totalAmount'];
        $closingAmount = $_POST['closingAmount'];
    try {
        $conn->begin_transaction();
        $sql = "SELECT * from daily_reports where date='$date' AND deleted='No' order by id desc LIMIT 1";
        $result = $conn->query($sql);
        $dailyReport= $result->fetch_assoc();
        if($dailyReport){
            $dailyReportId = $dailyReport['id'];
            $isTodayDate = $dailyReport['date'];
            if ($isTodayDate = $date) {
                $sql2= "UPDATE daily_reports SET date='$date', previous_closing ='$openingBalance', today_closing='$totalAmount', opening_balance='$closingAmount', updated_at='$toDay' , updated_by='$loginID' WHERE id = '$dailyReportId' ";
                $result2 = $conn->query($sql2);
            }
        } else {
            $sql2= "INSERT INTO `daily_reports` (date, previous_closing, today_closing, opening_balance, status, deleted, created_at, created_by) 
            VALUES ('$date','$openingBalance','$totalAmount','$closingAmount','Active','No','$toDay','$loginID')";
            $result2 = $conn->query($sql2);
        }

        $conn->commit();
        echo json_encode('Success');
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'RollBack';
    }
}



?>