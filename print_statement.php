<?php 
session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_invoices_search'];
$_SESSION['uzuriweb_wbm_modes'];
$_SESSION['invoicecode'];
$_SESSION['uzuriweb_wbm_co_id'];
//Include common files
include('includes/wbm_common.php');

$HTTP_POST_VARS = $_POST;

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$uzuriwebpgname="printinvoice_mgr";
	$strPath = "";
	$strTitle = 'STATEMENT';
	
	//$strAction = $_SESSION['uzuriweb_wbm_modes'];

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
<?
//Generate report
$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
if (isset($_GET['PB'])) //if input from form
{
	$strCompany = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["company"])));
	$strDatefrom = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["datefrom"])));
	$strDateto = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["dateto"])));
	$_SESSION['uzuriweb_wbm_co_id'] = $strCompany;
	//Get company details
	$sql2 = "SELECT company, location, pobox, pocode, telephone01, telephone02, cellphone01, cellphone02, fax, website FROM wbm_clients WHERE ID = $strCompany";
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
?>
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
	<h1><? echo $strTitle;?></h1>
	<span class="inputheader">NUMBER #:</span> &nbsp;1<br>
	<strong>START:</strong> 
	<? 
	$strDatefrom1 = date("F j, Y",strtotime($strDatefrom));
	echo $strDatefrom1;
	?>
	<br>
	<strong>END:</strong> 
	<? 
	$strDateto1 = date("F j, Y",strtotime($strDateto));
	echo $strDateto1;
	?>
	<br>
	<br>
	<strong>PRINTED:</strong> <? echo $today;?> 
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
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Invoice No#</b></td>\n";
	echo "<td width=\"35%\" class=\"frmHeader\"><b>Invoice Date</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Total(Ksh)</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Paid(Ksh)</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Balance(ksh)</b></td>\n";
	echo "</tr>\n";
	$strCoCompany = $_SESSION['uzuriweb_wbm_co_id'];
	$query  = "SELECT * FROM wbm_pro_invoices WHERE clientname = $strCoCompany AND status !=92 ";
	if(($strDatefrom)&&($strDateto))
			{
				$query = $query." AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strInvoiceNum = $row['invoiceNum'];
		$strInvoiceDate = date("F j, Y",strtotime($row['invoiceDate']));
		$strIAmount = $row['invoiceAmount'];
		$strAPaid = $row['amountPaid'];
		$strBAmount = $strIAmount - $strAPaid;
		//Format
		$strInvoiceAmount = number_format($strIAmount, 2,".",",");
		$strAmountPaid = number_format($strAPaid, 2,".",",");
		$strBalanceAmount = number_format($strBAmount, 2,".",",");

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		echo $strInvoiceNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\">\n";
		echo $strInvoiceDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		echo $strInvoiceAmount; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		echo $strAmountPaid; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt3\" valign=\"top\" align=\"right\">\n";
		echo $strBalanceAmount; 
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
	echo "<td class=\"inputheader\" colspan=\"2\">TOTAL BALANCE (Ksh):</td>\n";
		$query1="SELECT SUM(invoiceAmount) AS totalamnt FROM wbm_pro_invoices WHERE clientname = $strCoCompany AND status !=92 ";
		if(($strDatefrom)&&($strDateto))
			{
				$query1 = $query1." AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}	
		$result1 = mysql_query($query1) or die('Error, query failed');
		$row1 = mysql_fetch_array($result1);
		$strTotalamt=$row1['totalamnt'];
		//2
		$query2="SELECT SUM(amountPaid) AS paidamnt FROM wbm_pro_invoices WHERE clientname = $strCoCompany AND status !=92 ";
		if(($strDatefrom)&&($strDateto))
			{
				$query2 = $query2." AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}	
		$result2 = mysql_query($query2) or die('Error, query failed');
		$row2 = mysql_fetch_array($result2);
		$strPaidamt=$row2['paidamnt'];
		//Total balalnce amount

		$strTotBAmount = $strTotalamt - $strPaidamt;
		$strTotBalanceAmount = number_format($strTotBAmount, 2,".",",");
		echo "<td class=\"frmTabletxt2\" align=\"right\"><strong>\n";
		echo $strTotBalanceAmount; 
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
}
echo "</body>\n";
echo "</html>";
}
else
{
	header('Location: insufficientpermission.php');
}
ob_end_flush();
?>