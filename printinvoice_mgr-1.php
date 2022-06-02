<?php 
session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_invoices_search'];
$_SESSION['uzuriweb_wbm_modes'];
$_SESSION['invoicecode'];
//Include common files
include('includes/wbm_common.php');

$HTTP_POST_VARS = $_POST;

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$uzuriwebpgname="printinvoice_mgr";
	$strPath = "";
	$strTitle = 'INVOICE';
	
	$strID = rtrim($_GET['invid']);
	$strAction = $_SESSION['uzuriweb_wbm_modes'];

	// Select Current Configuration details
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$result=@mysql_query("SELECT * FROM wbm_settings WHERE companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strCountry = $row->country;
			$sql = "SELECT country FROM wbm_countries WHERE ID = $strCountry LIMIT 1";
			$result = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($result);
			$strCountryName = $row['country'];

		if($strCountry==95){ $strPoname="P.O Box ";}
		$today = date("F j, Y, g:i a"); 
	}
	
	$sqlinve = "SELECT * FROM wbm_invoices WHERE ID=$strID LIMIT 1";
	$resultinve = mysql_query($sqlinve) or die(mysql_error());
	$rowinve = mysql_fetch_array($resultinve);
	$strThetitle = $rowinve['ID']; 	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $strThetitle;?></title>
<LINK href="wbm-css/uzuriweb2.css" type=text/css rel=stylesheet>
<script language="Javascript1.2">
<!--
function printpage() {
window.print();
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF" onLoad="printpage()">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><img src="../upload/thumb/<? echo WBM_COMPANY_LOGO;?>"/></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="xtraformtxt">
	<? echo WEB_SITE_NAME;?><br>
	<? echo WEB_SITE_LOCATION;?><br>
	<? echo $strPoname;?><? echo WEB_SITE_POBOX;?>&nbsp;<? echo WEB_SITE_POCODE;?>, <? echo $strCountryName;?><br>
	<strong>TEL:</strong> <? echo WEB_SITE_TEL;?><br>
	<strong>WEB:</strong> <? echo WEB_SITE_URL;?><br>

	</td>
    <td>&nbsp;</td>
    <td align="right" valign="bottom" class="xtraformtxt">
	<?
	//Select Invoice Details
	$sqlname = "SELECT * FROM wbm_invoices WHERE ID=$strID LIMIT 1";
	$resultname = mysql_query($sqlname) or die(mysql_error());
	$rowname = mysql_fetch_array($resultname);
	$strTheInvoice = $rowname['ID']; 	
	$strProInvoice = $rowname['invoiceNum']; 	
	$strClientname = $rowname['clientname']; 	
	//Select Company Details
	$sql2 = "SELECT company, location, pobox, pocode, telephone01, telephone02, cellphone01, cellphone02, fax, website FROM wbm_clients WHERE ID = $strClientname";
	$result2 = mysql_query($sql2) or die(mysql_error());
	$row2 = mysql_fetch_array($result2);
	$strCompanyname = $row2['company'];
	$strLocation = $row2['location'];
	$strPobox = $row2['pobox'];
	$strPocode = $row2['pocode'];
	$strTelephone01 = $row2['telephone01'];
	$strTelephone02 = $row2['telephone02'];
	$strCellphone01 = $row2['cellphone01'];
	$strCellphone02 = $row2['cellphone02'];
	$strFax = $row2['fax'];
	$strWebsite = $row2['website'];
	
	
	$strInvoiceDate = date("F j, Y",strtotime($rowname['invoiceDate'])); 	
	$strSubTotal = $rowname['subTotal']; 	
	$strSalesTax = $rowname['salesTax']; 	
	$strInvoiceAmount = $rowname['invoiceAmount'];
	?>
	<h1><? echo $strTitle;?></h1>
	<span class="inputheader">INVOICE #:</span> &nbsp;<? echo $strTheInvoice;?><br>
	<span class="inputheader">INVOICE DATE:</span> &nbsp;<? echo $strInvoiceDate;?><br>
	<span class="inputheader">PIN NO:</span> &nbsp;P051362399M<br>
</td>
  </tr>
  <tr>
    <td colspan="3" class="xtraformtxt">
	<h2>Customer</h2>
	<span class="inputheader">NAME:</span> &nbsp;<? echo $strCompanyname;?><br>
	<span class="inputheader">ADDRESS:</span> &nbsp;<? echo $strPobox;?><br>
	<span class="inputheader">TEL:</span> &nbsp;
	<? 
	if ($strTelephone01){
	echo $strTelephone01;
	echo ", &nbsp;";
	}
	if ($strTelephone02){
	echo $strTelephone02;
	echo ", &nbsp;";
	}
	if ($strCellphone01){echo $strCellphone01;
	echo ", &nbsp;";
	}
	if ($strCellphone02){echo $strCellphone02;
	}
	?></td>
  </tr>
</table>
<br>
	<?
	// show first page by default
	echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Item No#</b></td>\n";
	echo "<td width=\"45%\" class=\"frmHeader\"><b>Item Description</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Quantity</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Unit Price(Ksh)</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Total(ksh)</b></td>\n";
	echo "</tr>\n";
	$query  = "SELECT * FROM wbm_invoicedetails WHERE invoiceNum = '$strProInvoice' AND companyid=$strWbmCompanyid ORDER BY ID ASC ";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strInvoiceNum = $row['invoiceNum'];
		$strItemName = $row['itemName']; 	
		$strItemDesc = $row['itemDesc']; 	
		$strQuantity = $row['quantity']; 	
		$strUnitPrice = $row['unitPrice']; 	
		$strTotalCost = $row['totalCost']; 	

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		echo $strItemName; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\">\n";
		echo $strItemDesc; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		echo $strQuantity; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		$strUnitPrice = number_format($strUnitPrice, 2,".",",");
		echo $strUnitPrice;
		//echo formatMoney($strUnitPrice); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		$strTotalCost = number_format($strTotalCost, 2,".",",");
		echo $strTotalCost;
		//echo formatMoney($strTotalCost); 
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td class=\"frmTabletxt4\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt4\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt4\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt4\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt4\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td colspan=4><hr></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\" colspan=\"2\">SUB TOTAL:</td>\n";
		echo "<td class=\"frmTabletxt2\" align=\"right\">\n";
		$strSubTotal = number_format($strSubTotal, 2,".",",");
		echo $strSubTotal; 
		//echo formatMoney($strNewSubTotal); 
		echo "</td>\n";
	echo "</tr>\n";
	
	if ($strSalesTax <> 0)
	{
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\" colspan=\"2\">SALES TAX (".WBM_SALES_TAX."% ".WBM_SALESTAX_NAME."):</td>\n";
		echo "<td class=\"frmTabletxt2\" align=\"right\">\n";
		$strSalesTax = number_format($strSalesTax, 2,".",",");
		echo $strSalesTax;
		//echo formatMoney($strSalesTax); 
		echo "</td>\n";
	echo "</tr>\n";
	}
	
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\" colspan=\"2\">TOTAL AMOUNT:</td>\n";
		echo "<td class=\"frmTabletxt2\" align=\"right\"><strong>\n";
		$strInvoiceAmount = number_format($strInvoiceAmount, 2,".",",");
		echo $strInvoiceAmount; 
		//echo formatMoney($strInvoiceAmount); 
		echo "</strong></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td colspan=4><hr></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=7>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo '<br>';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="../upload/thumb/bengo-signature.png" width="158" height="90"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div class="InvoiceFootI">-----------------------</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="InvoiceFootH">For EBITS ONLINE LTD </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div class="InvoiceFootI">----------------------------------------------------------------------------------------------doing it your way</div></td>
  </tr>
</table>
<?
echo "</body>\n";
echo "</html>";
}
else
{
	header('Location: insufficientpermission.php');
}
ob_end_flush();
?>