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
	$strTitle = 'Hosting Report';
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
<title>Hosting Report</title>
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
	<? echo $strPoname;?><? echo WEB_SITE_POBOX;?>&nbsp;<? echo WEB_SITE_POCODE;?><br>
	<? echo $strCountryName;?><br>
	<strong>TEL:</strong> <? echo WEB_SITE_TEL;?><br>
	<strong>WEB:</strong> <? echo WEB_SITE_URL;?><br>
	</td>
    <td>&nbsp;</td>
    <td align="right" valign="bottom" class="xtraformtxt"><strong>DATE: <? echo $today;?></strong> </td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="titletx">Hosting Accounts Report</td>
  </tr>
</table>

<?
	//Generate report
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	if (isset($_GET['PB'])) //if input from form
	{
		$strDatefrom = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["datefrom"])));
		$strDateto = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["dateto"])));
		$strPackage = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["package"])));
		
		$query="SELECT * FROM wbm_hosting WHERE companyid=$strWbmCompanyid";
		if(($strDatefrom)&&($strDateto))
			{
				$query = $query." AND hostingdate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}	
		if($strPackage <> 0)
			{
				$query = $query." AND package=$strPackage"; 
			}	
			
		$query = $query." ORDER BY domainname ASC";

	echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td width=\"35%\" class=\"frmHeader\"><b>Domain Name</b></td>\n";
	echo "<td width=\"30%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Hosting Package</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Setup Date</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strTheCo = $row['company']; 	
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strTheCo AND companyid=$strWbmCompanyid LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];

		$strDomain = $row['domainname']; 
		$strPackageID = $row['package'];
		   //select package name
			$sql3 = "SELECT packagename FROM wbm_hostingplans WHERE ID=$strPackageID AND companyid=$strWbmCompanyid LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strPackage = $row3['packagename'];
		 
		$strSetupdate = date("F j, Y",strtotime($row['hostingdate'])); 	
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strDomain; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strPackage; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strSetupdate; 
		echo "</td>\n";
		echo "</tr>\n";
	}
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