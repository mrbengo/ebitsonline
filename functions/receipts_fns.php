<?php
if(preg_match("/receipts_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for UzuriWeb
 * Generate the form
**/
function LoadRecords() {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// show first page by default
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$realmode=$_SESSION['uzuriweb_receipts_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_receipts";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_receipts";
		if($strSearchName)
			{
				$query = $query." WHERE companyid=$strWbmCompanyid AND ($strSearchColumn LIKE '%$strSearchName%'"; 
				$sq = $sq." WHERE companyid=$strWbmCompanyid AND ($strSearchColumn LIKE '%$strSearchName%'"; 
			}
			$query = $query.") LIMIT $offset, ".WBM_ROWS_PERVIEW; 
			$sq = $sq.")"; 
		$res  = mysql_query($sq) or die('Error, query failed');
		$row  = mysql_fetch_array($res, MYSQL_ASSOC);
		$numrows = $row['numrows'];
	}
	elseif ($realmode=="Nosearch")
	{
		setcookie("searchvalue", $strSearchName, time()-3600);
		setcookie("columnvalue", $strSearchName, time()-3600);
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_receipts WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_receipts WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
		if($sortby)
			{
				$query = $query.$sortby." LIMIT $offset, ".WBM_ROWS_PERVIEW; 
			}	
			else{$query = $query." LIMIT $offset, ".WBM_ROWS_PERVIEW;}
		$res  = mysql_query($sq) or die('Error, query failed');
		$row  = mysql_fetch_array($res, MYSQL_ASSOC);
		$numrows = $row['numrows'];
	}
	
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="receipts_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="receipts_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="invoiceNum">Invoice Number</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="receipts_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=4 class=\"displayheader\"><span class=\"inputheader\">TOTAL INVOICES FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=3 class=\"displayheader\">";
	echo '<table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="16" class="frmLegendtxt"><img src="images/edit.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Edit</td>
	<td width="16" class="frmLegendtxt"><img src="images/invoice.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Details</td>
	<td width="16" class="frmLegendtxt"><img src="images/delete.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Delete</td>
	<td width="64" class="frmLegendtxt">&nbsp;</td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Receipt No#</b></td>\n";
	echo "<td width=\"28%\" class=\"frmHeader\"><b>Client Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Receipt Date</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"12%\" class=\"frmHeader\"><b>Invoice No#</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strETRNum = $row['receiptNum'];
		$strInvoiceNum = $row['invoiceNum'];
		$strCompanyID = $row['clientname'];
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strCompanyID LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];
		
		$strReceiptDate = date("F j, Y",strtotime($row['invoiceDate']));
		$strReceiptAmount = $row['invoiceAmount'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strETRNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strReceiptDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo formatMoney($strReceiptAmount); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo $strInvoiceNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"printreceipt_mgr.php?receiptid=";
		echo $strID;
		echo "\" target=\"_blank\"><img alt=\"\" src=\"images/PrintInvoice.gif\" width=\"165\" height=\"36\" border=\"0\"></a>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
		echo "<tr>\n";
	    echo "<td colspan=7 class=\"scroll\">&nbsp;";
		echo "</tr>\n";
		echo "<tr>\n";
	echo "<td colspan=7 class=\"scroll\">Page:";
	// how many pages we have when using paging?
	$maxPage = ceil($numrows/WBM_ROWS_PERVIEW);
	// print the link to access each page
	$self = $_SERVER['PHP_SELF'];
	$nav = '';
	for($page = 1; $page <= $maxPage; $page++)
	{
		if ($page == $pageNum)
		{
			$nav .= " $page |";   // no need to create a link to current page
		}
		else
		{
			$nav .= " <a href=\"$self?page=$page\" class=\"scroll\">$page</a> |";
		}		
	}
	// creating the links
	if ($pageNum > 1)
	{
		$page = $pageNum - 1;
		$prev = " <a href=\"$self?page=$page\" class=\"scroll\"> Prev </a> |";
	} 
	else
	{
		$prev  = '&nbsp;';
	}

	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self?page=$page\" class=\"scroll\"> Next </a> ";
	} 
	else
	{
		$next = '&nbsp;';
	}
	// print the navigation link
	echo $prev . $nav . $next;
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo '<br>';
}
?>