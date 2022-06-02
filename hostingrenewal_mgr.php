<?php 
session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
//Include common files
include('includes/wbm_common.php');
//Set expiry to 30 days
$NewDate=date('Y-m-d', strtotime("+30 days"));
//echo $NewDate;









	/*//Get dates and generate renewal date
	$query  = "SELECT ID, year, month, day FROM wbm_hostingacc ORDER BY ID DESC";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
	$strID = $row['ID'];
	$strYear = $row['year'];
	$strMonth = $row['month'];
	$strDay = $row['day'];
	$strDate = $strYear."-".$strMonth."-".$strDay;
	
	//Update record
	mysql_query("UPDATE wbm_hostingacc SET renewal='$strDate' WHERE ID=$strID LIMIT 1") or die (mysql_error());
	}*/

?>