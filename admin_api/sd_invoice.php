<?php
include("config.php");
include("sd_send_sms.php");
//include('../api/inc/dompdf/autoload.inc.php');
require_once '../api/inc/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();


$returnArray= array();
if($_POST['func'] == "generate_invoice")
{
	$sql = "insert sd_invoice set			
			name			= '".addslashes(trim($_POST['merchant_name']))."',
			mobile			= '".trim($_POST['merchant_mobile'])."',
			location		= '".addslashes(trim($_POST['merchant_location']))."',
			amount			= '".addslashes(trim($_POST['amount']))."',
			bill_date		= '".addslashes(trim($_POST['bill_date']))."',
			plan_id			= '".$_POST['plan_id']."',
			description		= '".addslashes(trim($_POST['description']))."',
			created_date	= '".date('Y-m-d h:i:s')."'";
	
	mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	
	$invoice_id = mysqli_insert_id($link);

	$returnArray['invoice_id'] = $invoice_id;

	$sql = "update sd_billing set invoice_id='$invoice_id' where bill_id='".$_POST['bill_id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);	
	if($res)
	{
		$returnArray['success'] = true;
		$returnArray['msg'] = "Invoice has been successfully created.";

		$sql = "select plan_name from sd_billing_plan where plan_id='".$_POST['plan_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$row = mysqli_fetch_array($res);
		$planText = strtoupper(stripslashes($row['plan_name']));

		$sql = "select validity from sd_billing where bill_id='".$_POST['bill_id']."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$row = mysqli_fetch_array($res)	;	
		$validityText = date("jS M, Y", strtotime($row['validity']));


		$sql = "select created_date from sd_invoice where id='".$invoice_id ."'";
		$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
		$row = mysqli_fetch_array($res)	;	

		$invoiceText = "SD".date("Ymd", strtotime($row['created_date'])).$_POST['bill_id'].$invoice_id ;



		$sms = "Payment for Plan ". $planText." of ".stripslashes(trim($_POST['amount']))." is successfully received. Validity: ". $validityText. ". Invoice ID: ". $invoiceText;

		$returnArray['sms_return_data'] = $sms;
		sendSMS(trim($_POST['merchant_mobile']), $sms, "SDECRT");
	}
	else
	{
		$returnArray['success'] = false;
		$returnArray['msg'] = "Please try again later.";	
	}

	echo json_encode($returnArray);
}
elseif($_POST['func'] == "generate_pdf")
{
	$sql ="select * from sd_invoice where id='".$_POST['id']."'";
	$res = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row = mysqli_fetch_array($res);

	$sql = "select * from sd_billing where invoice_id='".$_POST['id']."'";
	$res_1 = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row_1 = mysqli_fetch_array($res_1);

	$plan_html ="";
	$sql = "select * from sd_billing_plan where is_active='1'";
	$res3 = mysqli_query($link, $sql) or die(mysqli_error($link));	

	while($row3 = mysqli_fetch_array($res3))
	{
		$check = "";
		if($row3['plan_id'] == $row['plan_id']) $check="checked";		
		$plan_html .= "<input type='checkbox' readonly ".$check."> ".$row3['plan_name'] ."<br/>";
	}


	$sql = "select shop_address1, shop_address2 from sd_vendors where vendor_id='".$row_1['vendor_id']."'";
	$res_4= mysqli_query($link, $sql) or die(mysqli_error($link).$sql);
	$row_4 = mysqli_fetch_array($res_4);
	//$returnArray['res'] = $row;

	$html ='<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td width="50%"> 
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
       				<tr>
       					<td align="right" colspan="2" style="padding-bottom: 20px; "> 
       					Invoice ID: SD'.date("Y",strtotime($row['created_date']))."/".$_POST['id'].
       					'</td>
       				</tr>
       				<tr>
       					<td rowspan="2" width="10%"><img src="../admin/assets/img/logo-invoice.png" width="50"></td>
       					<td style="color: #000; font-size: 22px;" align="right">Street Delight Service Pvt. Ltd.</td>
       				</tr>
       				<tr>
       					<td align="right"> Amar Hill, Saki Vihar Road, Powai, Mumbai - 40072<br>
                                         <font style="font-size:11px;">GSTIN - 27AAWCS7862H1ZG, PAN - AAWCS7862H<br>
                                         Service Tax Registration No. - AAWCS7862HSD001</font></td>
       				</tr>

       				<tr>
       					<td colspan="2" style="padding-top: 25px;">
       						<div style="float: left; width: 15%;">Name: </div>
       						<div style="float: right; width: 85%">'.stripslashes($row['name']).'</div>
       						<div style="clear: both;"></div>

       						<div style="float: left; width: 15%;">Mobile: </div>
       						<div style="float: left; width: 25%">'.stripslashes($row['mobile']).'</div>

       						<div style="float: left; width: 15%; text-align: right;">Location: </div>
       						<div style="float: left; width: 43%;"> &nbsp; &nbsp;'.nl2br(stripslashes($row_4['shop_address1'].' '. $row_4['shop_address2']." ".  $row['location'])).'</div>
       						<div style="clear: both;"></div>
       					</td>
       				</tr>
       				<tr>
       					<td colspan="2">
       						<div style="float: left; width: 15%;">GST No.: </div>
       						<div style="float: right; width: 85%">'.stripslashes($row_1['gst_no']).'</div>
       						<div style="clear: both;"></div>
       					</td>
       				</tr>
       				<tr>
       					<td style="font-size: 20px; font-weight: bold; padding-top: 20px;" colspan="2" align="center">Invoice</td>
       				</tr>
       				<tr>
       					<td colspan="2" align="center" style="padding-top: 10px;">
       						<table border="1" cellspacing="0" cellpadding="5" width="100%">
       							<tr>
       								<th> Date </th>
       								<th> Plan Name </th>
       								<th> Description </th>
       								<th> Amount </th>
       							</tr>
       							<tr>
       								<td>'.stripslashes($row['bill_date']).'</td>
       								<td>'.$plan_html.'</td>
       								<td>'.stripslashes($row['description']).'</td>
       								<td align="right">'.stripslashes($row['amount']).'</td>
       							</tr>
       							<tr>
       								<td colspan="3" align="right">Total</td>
       								<td align="right">'.stripslashes($row['amount']).'</td>
       							</tr>
       						</table>
       					</td>
       				</tr>
       			</table></td><td width="50%">&nbsp;</td></tr></table>';
//echo $html;


					//use Dompdf\Dompdf;

       				
       				$dompdf->loadHtml($html);

					// (Optional) Setup the paper size and orientation
					$dompdf->setPaper('A4', 'landscape');

					// Render the HTML as PDF
					$dompdf->render();

					$output = $dompdf->output();

					file_put_contents("../invoices/invoice_".$_POST['id'].".pdf", $output);
	/*$pdf=new PDF();
	$pdf->SetFont('Arial','',12);
	$pdf->AddPage();
	$pdf->WriteHTML($testHtml);
	$pdf->Output("../invoices/invoice_".$_POST['id'].".pdf", "F");
	//exit;

	/*$pdf=new PDF_HTML();
	$pdf->SetAuthor('Street Delight');
	$pdf->SetTitle('Invoice');
	$pdf->AddPage('P');
	$pdf->SetDisplayMode(real,'default');
	$pdf->SetFont('Arial');
	$pdf->WriteHTML($html);
	$pdf->Output("../invoices/invoice_".$_POST['id'].".pdf", "F");*/
	echo json_encode($returnArray);
}
?>