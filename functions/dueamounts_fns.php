<?php
if(preg_match("/dueamounts_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for UzuriWeb
 * Generate the form
**/

function LoadRecords() {
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions
	
	$strDatefrom = '2012-06-05';
	$strDateto = date('Y-m-d');

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// show first page by default
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_pro_invoices WHERE status=90 AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto' ORDER BY ID DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_pro_invoices WHERE status=90 AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto'";
		if($sortby)
			{
				$query = $query.$sortby." LIMIT $offset, ".WBM_ROWS_PERVIEW; 
			}	
			else{$query = $query." LIMIT $offset, ".WBM_ROWS_PERVIEW;}
		$res  = mysql_query($sq) or die('Error, query failed');
		$row  = mysql_fetch_array($res, MYSQL_ASSOC);
		$numrows = $row['numrows'];
	
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="emclients_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="emclients_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="domainname">Domain Name</option> 	
	<option value="registrar">Registrar</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="emclients_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=2 class=\"displayheader\"><span class=\"inputheader\">RECORDS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=2 class=\"displayheader\">";
	echo '<table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="16" class="frmLegendtxt"><img src="images/edit.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Edit</td>
	<td width="16" class="frmLegendtxt"><img src="images/delete.png" width="16" height="15" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Delete</td>
	<td width="64" class="frmLegendtxt">&nbsp;</td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Invoice No#</b></td>\n";
	echo "<td width=\"40%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Invoice Date</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\" align=\"right\"><b>Amount Due</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strInvoiceNum = $row['invoiceNum'];
		$strCompanyID = $row['clientname'];
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strCompanyID LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];

		$strInvoiceDate = date("F j, Y",strtotime($row['invoiceDate']));
		$strIAmount = $row['invoiceAmount'];
		$strAPaid = $row['amountPaid'];
		$strBAmount = $strIAmount - $strAPaid;
		//Format
		$strInvoiceAmount = number_format($strIAmount, 2,".",",");
		$strAmountPaid = number_format($strAPaid, 2,".",",");
		$strBalanceAmount = number_format($strBAmount, 2,".",",");

		echo "<tr>\n";
		echo '<td class="frmTabletxt"><a href="pro_invoices_mgr.php?mode=invoice&ID='.$strID.'" target="_blank" class="summarytxt">';
		echo $strInvoiceNum; 
		echo "</a></td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo $strBalanceAmount; 
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td class=\"frmTabletxt\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt\">&nbsp;</td>\n";
	echo "<td class=\"frmTabletxt\">&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td colspan=4><hr></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\" colspan=\"2\">TOTAL BALANCE (Ksh):</td>\n";
		$query1="SELECT SUM(invoiceAmount) AS totalamnt FROM wbm_pro_invoices WHERE status=90 AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto'";
		$result1 = mysql_query($query1) or die('Error, query failed');
		$row1 = mysql_fetch_array($result1);
		$strTotalamt=$row1['totalamnt'];
		//2
		$query2="SELECT SUM(amountPaid) AS paidamnt FROM wbm_pro_invoices WHERE status=90 AND invoiceDate BETWEEN '$strDatefrom' AND '$strDateto'";
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
	echo "<td colspan=4><hr></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=6>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo '<br>';
}
?>