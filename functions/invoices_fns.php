<?php
if(preg_match("/invoices_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_invoices_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_invoices";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_invoices";
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
		$query  = "SELECT * FROM wbm_invoices WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_invoices WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
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
	<td width="70%"><a href="invoices_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="invoices_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="invoices_mgr.php?searchmode=none">
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
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Invoice No#</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Client Name</b></td>\n";
	echo "<td width=\"18%\" class=\"frmHeader\"><b>Invoice Date</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Invoice Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Proforma Inv No#</b></td>\n";
	echo "<td width=\"7%\" class=\"frmHeader\"><b>Actions</b></td>\n";
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
		$strInvoiceAmount = $row['invoiceAmount'];
		$strReceiptStatus = $row['receipt'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strID; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo formatMoney($strInvoiceAmount); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo $strInvoiceNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"printinvoice_mgr.php?invid=";
		echo $strID;
		echo "\" target=\"_blank\"><img alt=\"\" src=\"images/my_invoices.png\" width=\"32\" height=\"32\" border=\"0\"></a>\n";
		if($strReceiptStatus <> 1){
		echo " | <a href=\"invoices_mgr.php?mode=receipt&ID=";
		echo $strID;
		echo "\"><img alt=\"\" src=\"images/receipt.png\" width=\"32\" height=\"32\" border=\"0\"></a>\n";
		}
		
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

function GenReceipt($action, $strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//Get Invoice Details
	$sql3 = "SELECT * FROM wbm_invoices WHERE ID = $strID AND companyid=$strWbmCompanyid LIMIT 1";
	$result3 = mysql_query($sql3) or die(mysql_error());
	$row3 = mysql_fetch_array($result3);
	$strProinvoiceNum = $row3['invoiceNum'];
	$strInvClient = $row3['clientname'];
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strInvClient LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];
	$strIncomeamount = $row3['invoiceAmount'];
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="invoices_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="invoices_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="invoices_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	?>
	<table width="98%" align="center" border="0" bgcolor="#F3F5F8" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
	<td style="background-image: url(images/lefttop.jpg);" width="6" height="6"></td>
	<td style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	<td style="background-image: url(images/righttop.jpg);" width="6" height="6"></td>
	</tr>
	<tr>
	<td style="background: transparent url(images/left.jpg) repeat-y scroll 0% 0%;" height="6"></td>
	<td valign="middle" align="center">	
	<FORM name="formID" id="formID" action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Generate Receipt</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Client Name :*</td>
	<td><input name="client" type="text" id="client" value="<?php echo $strCompanyname;?>" disabled="disabled" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Invoice Amount (Ksh):*</td>
	<td><input name="amount" type="text" id="amount" value="<?php echo $strIncomeamount;?>" disabled="disabled" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">ETR Receipt Number :*</td>
	<td><input name="etrreceipt" type="text" id="etrreceipt" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br>
	<br></td>
	<td width="27%" align="left">&nbsp;</td>
	</tr>
	</table>
	</form>
	</td>
	<td style="background: transparent url(images/right.jpg) repeat-y scroll 0% 0%;" height="6"></td>
	</tr>
	<tr>
	<td style="background-image: url(images/leftbottom.jpg);" width="6" height="6"></td>
	<td style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	<td style="background-image: url(images/rightbottom.jpg);" width="6" height="6"></td>
	</tr>
	</tbody>
	</table>
<?
}

// Process Submitted Form
function ProcessReceipt($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = preg_replace('/\'/', "''", trim($formValues["theID"]));
	$strETRreceipt = preg_replace('/\'/', "''", trim($formValues["etrreceipt"]));
	//Get all records pertaining to this ID from Invoices
		$sql3 = "SELECT * FROM wbm_invoices WHERE ID = $strID AND companyid=$strWbmCompanyid LIMIT 1";
		$result3 = mysql_query($sql3) or die(mysql_error());
		$row3 = mysql_fetch_array($result3);
		$strInvoiceno = $row3['ID'];
		$strProinvoiceNum = $row3['invoiceNum'];
		$strInvClient = $row3['clientname'];
		$strIncomedate = $row3['invoiceDate'];
		$strSubTotal = $row3['subTotal'];
		$strSalesTax = $row3['salesTax'];
		$strIncomeamount = $row3['invoiceAmount'];
		$strCreated = $row3['created'];
		
	// Update database table with submitted content
	   mysql_query("INSERT INTO wbm_receipts (companyid, receiptNum, invoiceNum, proinvoiceNum, clientname, invoiceDate, subTotal, salesTax, invoiceAmount, created) VALUES ('$strWbmCompanyid', '$strETRreceipt', '$strInvoiceno', '$strProinvoiceNum', '$strInvClient', '$strIncomedate', '$strSubTotal', '$strSalesTax', '$strIncomeamount', '$strCreated')") or die (mysql_error());
	   
	 //Update Receipt status on Invoices table
    	mysql_query("UPDATE wbm_invoices SET receipt=1 WHERE ID = $strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());

	// Redirect to Main page
	header('Location: receipts_mgr.php?searchmode=none');
	exit();
}
?>