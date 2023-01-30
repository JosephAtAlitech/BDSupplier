
<?php
$conPrefix = '../';
include $conPrefix . 'includes/session.php';
$loginID = $_SESSION['user'];

/*date_default_timezone_set('Asia/Dhaka');*/
$toDay = date('Y-m-d h:i:s', time());

date_default_timezone_set('Asia/Dhaka');


if(isset($_POST['date'])){
    $date = $_POST['date'];

   // header ('Location: https://www.facebook.com/');
   // $minusDaysFromDate =  date_create($date)->modify('-1 days')->format('Y-m-d');
   // $from_warehouse = Session::get("warehouse")[0]["id"];

    $openingData = "SELECT * FROM daily_reports where deleted = 'No' AND 'date' < $date order By 'date' DESC limit 1";
    $result = $conn->query($openingData);
    
     if ($result != false){
       
        $sql1 = "SELECT * from daily_reports where deleted = 'No' AND 'date' < $date order By 'date' DESC LIMIT 1";
        $lastDailyReport = $conn->query($sql1);
    }else{
        $lastDailyReport = 0;
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
                    AND tbl_paymentvoucher.paymentDate = $date 
                    AND tbl_paymentvoucher.status= 'Active' 
                    AND tbl_paymentvoucher.paymentMethod = 'Cash' 
                    AND tbl_paymentvoucher.type IN ('payment', 'paymentReceived')";

        $result2 = $conn->query($sql3);

        $purchasePaymentTotal = 0;
        $paymentVoucher = 0;
        $paymentReceivedVoucher = 0;
        $saleReceivedTotal = 0;
        $expanseAmount = 0;

        while ($todayReport = $result2->fetch_array()) {

            if ($todayReport['tbl_purchaseId'] > 0 && $todayReport['type'] == "payment") {
                $purchasePaymentTotal += $todayReport['amount'];
            }
            else if ($todayReport['tbl_expense_id'] > 0 && $todayReport['type'] == "payment") {
                $expanseAmount += $todayReport['amount'];
            }
            else if ( $todayReport['tbl_sales_id'] > 0 && $todayReport['type'] == "paymentReceived") {
                $saleReceivedTotal += $todayReport['amount'];
            }
            else if ( todayReport['type'] == "payment") {
                $paymentVoucher += $todayReport['amount'];
            }
            else if ( $todayReport['type'] == "paymentReceived") {
                $paymentReceivedVoucher += $todayReport['amount'];
            }
        }

        $TotalPayment = $purchasePaymentTotal + $paymentVoucher;
        $todayBalance = ($saleReceivedTotal+$paymentReceivedVoucher) - ($TotalPayment + $expanseAmount);
        $todayReportArray = [$purchasePaymentTotal, $saleReceivedTotal, $expanseAmount,  $todayBalance];
        

        $todayReportTable = '<tr><td>Purchase Payment(-)</td><td>' . number_format($TotalPayment, 2) . '</td></tr>
        <tr><td>Expense(-)</td><td>' . number_format($expanseAmount, 2) . '</td></tr>
        <tr><td>Payment Received(+)</td><td>' . number_format($saleReceivedTotal+$paymentReceivedVoucher, 2) . '</td></tr>
        <tr><td>Balance </td><td>' . number_format($todayBalance, 2) . '</td></tr>';
        //-----End today payment, expense , payment received-------//
        

        $data = [$todayReportTable , $lastDailyReport ,  $todayReportArray];
        //var_dump($data);

        return json_encode($data);
//  return response()->json([$todayReportTable, $lastDailyReport, $todayReportArray]);
}
       

?>