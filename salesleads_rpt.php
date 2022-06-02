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
	$strTitle = 'Sales Leads Report';
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
<title>Active Clients Report</title>
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
    <td colspan="3" align="center" class="titletx">Sales Leads Report</td>
  </tr>
</table>

<?
	//Generate report
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	if (isset($_GET['PB'])) //if input from form
	{
		$strDatefrom = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["datefrom"])));
		$strDateto = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["dateto"])));
		$strLeadsource = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["leadsource"])));
		$strLeadstatus = preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["leadstatus"])));
		
		$query="SELECT * FROM wbm_salesleads WHERE companyid=$strWbmCompanyid";
		if(($strDatefrom)&&($strDateto))
			{
				$query = $query." AND leaddate BETWEEN '$strDatefrom' AND '$strDateto'"; 
			}	
		if($strLeadsource <> 0)
			{
				$query = $query." AND leadsource=$strLeadsource"; 
			}	
		if($strLeadstatus <> 0)
			{
				$query = $query." AND leadstatus=$strLeadstatus"; 
			}	
			
		$query = $query." ORDER BY ID DESC";
	echo "<table width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>First Name</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Last Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Lead Title</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Lead Source</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Lead Status</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strCompany = $row['company'];
		$strFname = $row['fname'];
		$strLname = $row['lname'];
		$strLeadtitle = $row['leadtitle'];
		$strLeadsour = $row['leadsource'];
		//select package name
			$sql3 = "SELECT category FROM wbm_categories WHERE ID=$strLeadsour LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strLeadsource = $row3['category'];
		$strLeadstat = $row['leadstatus'];
		//select package name
			$sql4 = "SELECT category FROM wbm_categories WHERE ID=$strLeadstat LIMIT 1";
			$result4 = mysql_query($sql4) or die(mysql_error());
			$row4 = mysql_fetch_array($result4);
			$strLeadstatus = $row4['category'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompany; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strFname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLeadtitle; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLeadsource; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLeadstatus; 
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