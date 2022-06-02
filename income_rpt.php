<?php 
session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
//Include common files
include('includes/wbm_common.php');

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$uzuriwebpgname="myclients_mgr";
	$strPath = "";
	$strTitle = 'Income (Money In) Report';
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
		$today = date("F j, Y"); 
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Income (Money In) Report</title>
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
		$strDatefrom = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["datefrom"])));
		$strDateto = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["dateto"])));
		$strIncometype = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["incometype"])));
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
	<? echo $strPoname;?><? echo WEB_SITE_POBOX;?>&nbsp;<? echo WEB_SITE_POCODE;?><br>
	<? echo $strCountryName;?><br>
	<strong>TEL:</strong> <? echo WEB_SITE_TEL;?><br>
	<strong>WEB:</strong> <? echo WEB_SITE_URL;?><br>
	</td>
    <td>&nbsp;</td>
    <td align="right" valign="bottom" class="xtraformtxt">
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
	<strong>PRINTED: <? echo $today;?></strong> 
	</td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="titletx">Income Report</td>
  </tr>
</table>

<?
		$query="SELECT * FROM wbm_income WHERE companyid=$strWbmCompanyid";
		if(($strDatefrom)&&($strDateto))
			{
				$query = $query." AND receiveddate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}	
		if($strIncometype <> 0)
			{
				$query = $query." AND category=$strIncometype"; 
			}	
			
		$query = $query." ORDER BY ID ASC";

	echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td width=\"18%\" class=\"frmHeader\"><b>Received Date</b></td>\n";
	echo "<td width=\"27%\" class=\"frmHeader\"><b>Received From</b></td>\n";
	echo "<td width=\"12%\" class=\"frmHeader\"><b>Invoice</b></td>\n";
	echo "<td width=\"14%\" class=\"frmHeader\" align=\"right\"><b>Net Amount</b></td>\n";
	echo "<td width=\"14%\" class=\"frmHeader\" align=\"right\"><b>VAT (16%)</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\" align=\"right\"><b>Total Amount</b></td>\n";
	echo "</tr>\n";

	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strReceiveddate = date("F j, Y",strtotime($row['receiveddate']));	
		$strInvoiceNo = $strID;
		$strGAmount = $row['amount']; 
		$strGrossAmount = round($strGAmount, 2);
		$strVAmount = (16*$strGrossAmount)/116; 
		$strVatAmount = round($strVAmount, 2);
		$strNAmount = $strGrossAmount - $strVatAmount; 
		$strNetAmount = round($strNAmount, 2);
		$strReceivedfrom = $row['receivedfrom']; 	
		$strIncCatID = $row['category']; 
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt2\">\n";
		echo $strReceiveddate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt2\">\n";
		echo $strReceivedfrom; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt2\">\n";
		echo $strInvoiceNo; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt2\" align=\"right\">\n";
		echo $strNetAmount; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt2\" align=\"right\">\n";
		echo $strVatAmount; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt2\" align=\"right\">\n";
		echo $strGrossAmount; 
		echo "</td>\n";
		echo "</tr>\n";
	}
		echo "<tr>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td colspan=3><hr></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td class=\"titletx\" colspan=\"2\" align=\"right\">TOTAL (Ksh) :</td>\n";
				$query1="SELECT SUM(amount) AS totalamnt FROM wbm_income WHERE companyid=$strWbmCompanyid";
				if(($strDatefrom)&&($strDateto))
					{
						$query1 = $query1." AND receiveddate BETWEEN '$strDatefrom' AND '$strDateto'"; 
					}	
				if($strIncometype <> 0)
					{
						$query1 = $query1." AND category=$strIncometype"; 
					}	
					
			$query1 = $query1." ORDER BY ID ASC";
			$result1 = mysql_query($query1) or die('Error, query failed');
			$row1 = mysql_fetch_array($result1);
			$strTotalamt=$row1['totalamnt'];
			$strTotalamt = round($strTotalamt, 2);
			$strTVAmount = (16*$strTotalamt)/116; 
			$strTVatAmount = round($strTVAmount, 2);
			$strTNAmount = $strTotalamt - $strTVatAmount; 
			$strTNetAmount = round($strTNAmount, 2);
			 
			//$strTotalamt = formatMoney($strTotalamt);	
			echo "<td class=\"titletx\" align=\"right\">\n";
			echo formatMoney($strTNetAmount); 
			echo "</td>\n";
			echo "<td class=\"titletx\" align=\"right\">\n";
			echo formatMoney($strTVatAmount); 
			echo "</td>\n";
			echo "<td class=\"titletx\" align=\"right\">\n";
			echo formatMoney($strTotalamt); 
			echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td>&nbsp;</td>\n";
			echo "<td>&nbsp;</td>\n";
			echo "<td>&nbsp;</td>\n";
			echo "<td colspan=3><hr></td>\n";
			echo "</tr>\n";

	echo "</table>\n";
	echo '<br>';
 }

echo "</body>\n";
echo "</html>";
}
else
{
	header('Location: insufficientpermission.php');
}
?>