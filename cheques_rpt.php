<?php 
session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
//Include common files
include('includes/wbm_common.php');

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$uzuriwebpgname="cheques_rpt";
	$strPath = "";
	$strTitle = 'Issued Cheques Report';
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
<title>Issued Cheques Report</title>
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
    <td colspan="3" align="center" class="titletx">Issued Cheques Report</td>
  </tr>
</table>
<?
		
		$query="SELECT * FROM wbm_cheques WHERE companyid=$strWbmCompanyid";
		if(($strDatefrom)&&($strDateto))
			{
				$query = $query." AND chequedate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}	
			
		$query = $query." ORDER BY ID DESC";

	echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Cheque No#</b></td>\n";
	echo "<td width=\"35%\" class=\"frmHeader\"><b>Receipient</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Cheque Date</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Cheque Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strChequeNum = $row['chequenum'];
		$strChequeDate = date("F j, Y",strtotime($row['chequedate']));
		$strChequeAmount = $row['chequeamount'];
		$strChequeRec = $row['chequereceipient'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strChequeNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strChequeRec; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strChequeDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo formatMoney($strChequeAmount); 
		echo "</td>\n";
		echo "</tr>\n";
	}
		echo "<tr>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td colspan=2><hr></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td>&nbsp;</td>\n";
		echo "<td class=\"titletx\">TOTAL :</td>\n";
				$query1="SELECT SUM(chequeamount) AS totalamnt FROM wbm_cheques WHERE companyid=$strWbmCompanyid";
				if(($strDatefrom)&&($strDateto))
					{
						$query1 = $query1." AND chequedate BETWEEN '$strDatefrom' AND '$strDateto'"; 
					}	
					
			$query1 = $query1." ORDER BY ID ASC";
			$result1 = mysql_query($query1) or die('Error, query failed');
			$row1 = mysql_fetch_array($result1);
			$strTotalamt=$row1['totalamnt']; 
			$strTotalamt = formatMoney($strTotalamt);	
			echo "<td class=\"titletx\">\n";
			echo "Ksh ".$strTotalamt; 
			echo "</td>\n";
		
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td>&nbsp;</td>\n";
			echo "<td>&nbsp;</td>\n";
			echo "<td colspan=2><hr></td>\n";
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