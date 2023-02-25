<?php ob_start(); //$conPrefix = '';
include 'includes/session.php';
$date = $_GET['date'];
//$purid = $_GET['purid'];
//$supId = $_GET['supId'];
    //$type = htmlspecialchars($_GET["page"]);
    //if($type != "")
    //{
    //$sessionId = time().uniqid();
$sql = "SELECT * FROM `shopSettings`"; 
$query = $conn->query($sql);
$row = $query->fetch_assoc();
	$address=$row['address'];
	$phone=$row['phone'];
	$mobile=$row['mobile'];
	$email=$row['email'];
	$website=$row['website'];
	$image=$row['image'];
	$imageWatermark=$row['imageWatermark'];
	$addType=$row['address_type'];

	require_once('tcpdf/tcpdf.php');

	$page_header = '<div>
    <table width="100%"><br><br><br><br><br><br>
        <tr>
            <td style="text-align:center;font-size: 25px;">'.strtoupper('Bangladesh Suppliers').'</td>
        </tr><tr>    
            <td style="padding-top:1px;text-align:center;"> '.strtoupper($address).' <br>Tel:'.$phone.' Mobile: '.$mobile.'<br>E-mail:'.$email.'</td>
        </tr>
    </table>
    </div>';
     $page_banner = $image;	
		// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        
        global $imageWatermark;
        global $page_header;
        global $page_banner;
        //$this->SetFont('helvetica', '', 7);
        //Page number
        $image_file = "images/companylogo/$page_banner";
        $this->Image($image_file, 90, 4,27, 20, 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
        $this->writeHTML($page_header);
        
        
        $image_file = 'images/companylogo/'.$imageWatermark;
        $this->Image($image_file,10, 60,189, '', 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer() {
        
        // Position at 15 mm from bottom
        $this->SetY(-12);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Powered By Alitech. Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
    }
}

		
    //$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('Duronto Shop Management System');  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('helvetica');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '', PDF_MARGIN_RIGHT);  
    $pdf->SetMargins('8', '47', '8');
    $pdf->setPrintHeader(TRUE);  
    $pdf->setPrintFooter(TRUE);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  
    $pdf->AddPage();
    $sql11 = "SELECT fname FROM `tbl_users` WHERE id='".$_SESSION['user']."'";
			$query = $conn->query($sql11);
			$row123 = $query->fetch_assoc();
			$fname11=$row123['fname'];
			
    $content = '<style>
				.supAddress {font-size: 8px;}
				.supAddressFont {font-size: 8px;}
				</style>'; 
	$sql = "SELECT tbl_purchase.id,tbl_purchase.tbl_supplierId,tbl_purchase.purchaseOrderNo,tbl_purchase.createdDate,tbl_purchase.purchaseDate,tbl_purchase.chalanNo,tbl_party.id,tbl_party.partyName,tbl_party.tblCountry,tbl_party.tblCity,tbl_party.partyAddress,
		tbl_party.contactPerson,tbl_party.partyPhone,tbl_party.partyAltPhone,tbl_party.creditLimit,tbl_party.tblType,tbl_users.fname
		FROM `tbl_purchase`
		LEFT JOIN tbl_party ON tbl_party.id=tbl_purchase.tbl_supplierId 
        LEFT JOIN tbl_users ON tbl_users.id=tbl_purchase.createdBy
        WHERE tbl_purchase.id='".$_SESSION['user']."' AND (tbl_party.tblType='Suppliers' || tbl_party.tblType='Both')";
		
		$query = $conn->query($sql);
		while($row = $query->fetch_assoc()){
			$partyName=$row['partyName'];
			$tblCountry=$row['tblCountry'];
			$tblCity=$row['tblCity'];
			$partyAddress=$row['partyAddress'];
			$contactPerson=$row['contactPerson'];
			$partyPhone=$row['partyPhone'];
			$partyAltPhone=$row['partyAltPhone'];
			$creditLimit=$row['creditLimit'];
			$purchaseOrderNo=$row['purchaseOrderNo'];
			$purchaseDate=$row['purchaseDate'];
			$createdDate12=$row['createdDate'];
			$createdDate = date('Y-m-d h:i:s A', strtotime($createdDate12));
			$chalanNo=$row['chalanNo'];
			$fname=$row['fname'];
			$tblType=$row['tblType'];
			
		}
    $content .= '<style>p{color:black;font-size: 8px;text-align:center;}
			.cities {background-color: gray;color: white;text-align: center;padding: 30px;}
			.citiestd {background-color: yellow;color: black;text-align: center;}
			.citiestd12 {background-color: gray;color: white;text-align: center; font-size: 9px;}
			.citiestd13 {background-color: orange;color: white;text-align: center;}
			.citiestd11 {text-align: center;font-size: 8px;}
			.citiestd14 {font-size: 7px;}
			.citiestd15 {text-align: center;font-size: 7px;}
			span{font-size: 9px;}
			h2{font-size: 18px;text-align:center;}
		</style>';
		//$image = 'images/companylogo/'.$image;    
        //$pdf->Image($image, 85, 3,40, 15);
		$content .='<br>
		<div class="cities"> Purchase Invoice : '.$purchaseOrderNo.'</div>
		
		<table border="1" cellspacing="0" cellpadding="3">
			<tr>
				<td width="73%" class="supAddress">Status :<font color="gray" class="supAddressFont">'.$tblType.'</font><br>Supplier Name :<font color="gray" class="supAddressFont">'.$partyName.'</font><br>Address :<font color="gray" class="supAddressFont"> '.$partyAddress.' - '.$tblCity.','.$tblCountry.'</font><br>Contact Person :<font color="gray" class="supAddressFont"> '.$contactPerson.'</font> / Phone :<font color="gray" class="supAddressFont"> '.$partyPhone.' , '.$partyAltPhone.'</font></td>
				<td width="27%" style="border: 1px solid gray;font-size: 8px;" >Memo No :<font color="gray">'.$chalanNo.'</font><br>Purchase Date :<font color="gray">'.$purchaseDate.'</font><br>Entry Date :<font color="gray">'.$createdDate.'</font><br>Entry By :<font color="gray">'.$fname.'</font><br>Printed By :<font color="gray">'.$fname11.'</font></td>
			</tr>
			<tr>
				<td width="100%">Del. Date :<font color="gray"></font> Adj. Date :<font color="gray"></font> Ret. Date :<font color="gray"></td>
			</tr>
		</table>
		Purchase product information :<br><br>
		<table border="1" cellspacing="0" cellpadding="3">
        <tr>
        <th class="citiestd11" width="5%">SL#</th>
        <th class="citiestd11" width="25%">Particulars</th>
        <th class="citiestd11" width="22%">Voucher</th>
        <th class="citiestd11" width="10%">Grand Total</th>
        <th class="citiestd11" width="10%">Cash In</th>
        <th class="citiestd11" width="9%">Cash Out</th>
        <th class="citiestd11" width="9%">Balance</th>
        </tr><tbody>';

    $sql = " SELECT
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
                AND tbl_paymentvoucher.paymentMethod = 'Cash' 
                AND tbl_paymentvoucher.type IN ('payment', 'paymentReceived', 'partyPayable')";
                //AND tbl_paymentvoucher.type IN ('payment', 'paymentReceived', 'partyPayable')";


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
        $content .= '<tr>
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
        $content .= '<tr>
                <td class="text-center">' . $i++ . '</td>' .
            '<td class="text-left">' . $row['remarks']. '<br>' . $row['partyName'] . ' | ' . $row['partyAddress']  . '</td>' .
            '<td class="text-center">' . $row['voucherNo'] . '</td>' .
            '<td class="text-center">' . $row['grandTotal']  . '</td>' .
            '<td>' . $cashIn . '</td>' .
            '<td class="textRight">  -  </td>' .
            '<td class="textRight">' . number_format($balance) . '</td>' .
            '</tr>';
    } 
/*     else {
        $content .= '<tr>
                    <td class="text-center">' . $i++ . '</td>' .
            '<td class="text-left">' .$row['remarks']  . '<br>' .$row['partyName'] . ' | ' . $row['partyAddress']  . '</td>' .
            '<td class="text-center">' .$row['voucherNo']. '</td>' .
            '<td class="text-center">' .  $row['grandTotal']  . '</td>' .
            '<td class="textRight"></td>' .
            '<td></td>' .
            '<td class="textRight"></td>' .
            '</tr>';
    } */


} //End while 
$button .= '';
$content .= '
	<tr><td></td><td></td><td></td><td></td><td></td><td class="citiestd11" >Total = </td><td class="citiestd11">'.number_format( $balance ,2).'</td></tr>
	</tbody>
    </table><br><br>
		';
			
		
		
	$pdf->writeHTML($content);  
    ob_end_clean();
	$pdf->Output('schedule.pdf', 'I');
    ob_end_flush();
?>