<?php
if(preg_match("/pro_invoices_fns.php/i",$_SERVER['PHP_SELF'])) {
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

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// show first page by default
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$realmode=$_SESSION['uzuriweb_pro_invoices_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_pro_invoices";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_pro_invoices";
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
		$query  = "SELECT * FROM wbm_pro_invoices WHERE companyid=$strWbmCompanyid ORDER BY invoiceNum DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_pro_invoices WHERE companyid=$strWbmCompanyid ORDER BY invoiceNum DESC";
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
	<td width="70%"><a href="pro_invoices_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="pro_invoices_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="pro_invoices_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=5 class=\"displayheader\"><span class=\"inputheader\">TOTAL INVOICES FOUND :</span> &nbsp;";
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
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Inv No#</b></td>\n";
	echo "<td width=\"22%\" class=\"frmHeader\"><b>Client Name</b></td>\n";
	echo "<td width=\"16%\" class=\"frmHeader\"><b>Inv Date</b></td>\n";
	echo "<td width=\"16%\" class=\"frmHeader\"><b>Due Date</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Inv Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Amount Paid(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"6%\" class=\"frmHeader\"><b>Inv Status</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
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
		$strInvoiceDue = date("F j, Y",strtotime($row['invoiceDue']));
		$strInvoiceAmount = $row['invoiceAmount'];
		$strAmountPaid = $row['amountPaid'];
		$strStatus = $row['status'];
			//Select Status
			$sql3 = "SELECT category FROM wbm_categories WHERE ID=$strStatus LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strInvStatus = $row3['category'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceDue; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo formatMoney($strInvoiceAmount); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo formatMoney($strAmountPaid); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvStatus; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"pro_invoices_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"pro_invoices_mgr.php?mode=invoice&ID=";
		echo $strID;
		echo " \"><img src=\"images/invoice.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
		echo " | <a href=\"pro_invoices_mgr.php?mode=delete&ID=";
		echo $strID;
		echo "&inv=";
		echo $strInvoiceNum;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Invoice?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
		echo "<tr>\n";
	    echo "<td colspan=8 class=\"scroll\">&nbsp;";
		echo "</tr>\n";
		echo "<tr>\n";
	echo "<td colspan=8 class=\"scroll\">Page:";
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

function ViewInvoice($strID) {
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="pro_invoices_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="pro_invoices_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="pro_invoices_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';

	//Select Invoice Details
	$sqlname = "SELECT * FROM wbm_pro_invoices WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1";
	$resultname = mysql_query($sqlname) or die(mysql_error());
	$rowname = mysql_fetch_array($resultname);
	$strTheInvoice = $rowname['invoiceNum']; 	
	$strCompanyID = $rowname['clientname']; 	
	//Select Page Name
		$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strCompanyID LIMIT 1";
		$result2 = mysql_query($sql2) or die(mysql_error());
		$row2 = mysql_fetch_array($result2);
		$strCompanyname = $row2['company'];
			
	$strInvoiceDate = date("F j, Y",strtotime($rowname['invoiceDate'])); 	
	$strInvoiceDue = date("F j, Y",strtotime($rowname['invoiceDue'])); 	
	$strDiscount = $rowname['discount']; 	
	$strSubTotal = $rowname['subTotal']; 	
	$strDiscountamnt = $rowname['discountamnt']; 	
	$strSalesTax = $rowname['salesTax']; 	
	$strInvoiceAmount = $rowname['invoiceAmount'];
	$strNewSubTotal = $strSubTotal+$strDiscountamnt;
	
	// show first page by default
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
	$query  = "SELECT * FROM wbm_invoicedetails WHERE invoiceNum = '$strTheInvoice' AND companyid=$strWbmCompanyid ORDER BY ID ASC LIMIT $offset, ".WBM_ROWS_PERVIEW;
	$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_invoicedetails WHERE invoiceNum = '$strTheInvoice' AND companyid=$strWbmCompanyid ORDER BY ID ASC LIMIT $offset, ".WBM_ROWS_PERVIEW;

	$res  = mysql_query($sq) or die('Error, query failed');
	$row  = mysql_fetch_array($res, MYSQL_ASSOC);
	$numrows = $row['numrows'];
	if ($numrows <> 0)
	{
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=4 class=\"displayheader\">&nbsp;</td>\n";
	echo "<td colspan=2 class=\"displayheader\"><a href=\"print_proformainvoice_mgr.php?invid=";
	echo $strID;
	echo "\" target=\"_blank\"><img alt=\"\" src=\"images/PrintInvoice.gif\" width=\"165\" height=\"36\" border=\"0\"></a></td>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=6 class=\"displayheader\"><span class=\"inputheader\">INVOICE NUMBER:</span> &nbsp;";
	echo $strTheInvoice;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=6 class=\"displayheader\"><span class=\"inputheader\">INVOICE DATE :</span> &nbsp;";
	echo $strInvoiceDate;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=6 class=\"displayheader\"><span class=\"inputheader\">DUE DATE :</span> &nbsp;";
	echo $strInvoiceDue;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=6 class=\"displayheader\"><span class=\"inputheader\">CLIENT NAME :</span> &nbsp;";
	echo $strCompanyname;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Item No#</b></td>\n";
	echo "<td width=\"45%\" class=\"frmHeader\"><b>Item Description</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Quantity</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Unit Price(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Total(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "</tr>\n";
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
		echo "<td class=\"frmTabletxt\">\n";
		echo $strItemName; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strItemDesc; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strQuantity; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strUnitPrice; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strTotalCost; 
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
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\">SUB TOTAL (".WBM_CURRENCY_SYMBOL."):</td>\n";
		echo "<td class=\"inputheader\">\n";
		echo $strNewSubTotal; 
		echo "</td>\n";
	echo "</tr>\n";
	
	if ($strDiscount <> 0)
	{
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\">\n";
	echo $strDiscount."% Discount:</td>\n";
		echo "<td class=\"inputheader\">\n";
		echo $strDiscountamnt; 
		echo "</td>\n";
	echo "</tr>\n";
	}
	
	if ($strSalesTax <> 0)
	{
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\">SALES TAX (".WBM_SALESTAX_NAME."):</td>\n";
		echo "<td class=\"inputheader\">\n";
		echo $strSalesTax; 
		echo "</td>\n";
	echo "</tr>\n";
	}
	
	echo "<tr>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td class=\"inputheader\">TOTAL AMOUNT (".WBM_CURRENCY_SYMBOL."):</td>\n";
		echo "<td class=\"inputheader\"><hr>\n";
		$strInvoiceAmount = number_format($strInvoiceAmount, 2,".",",");
		echo $strInvoiceAmount; 
		echo "<hr></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=7>&nbsp;</td>\n";
	echo "</tr>\n";

	echo "</table>\n";
	echo "</form>\n";
	echo '<br>';
	echo '<br>';
	echo '<br>';
	}
	else
	{
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=6 class=\"displayheader\"><span class=\"inputheader\">TOTAL RECORDS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Item Name</b></td>\n";
	echo "<td width=\"40%\" class=\"frmHeader\"><b>Item Description</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Quantity</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Unit Price</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Total</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class=\"frmTabletxt\" colspan=\"6\" align=\"center\">NO RECORDS FOUND.</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	}
}

function AddRecord($action) {
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="pro_invoices_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="pro_invoices_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="pro_invoices_mgr.php?searchmode=none">
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
	<td colspan="5" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Invoice Number :*</td>
	<td>
	<?PHP
	$sql = "SELECT invoicenum FROM wbm_settings WHERE companyid=$strWbmCompanyid LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$strInvoiceID = $row['invoicenum'];
	//Create Invoice Number
	$newIVcode = $strInvoiceID+1;
	mysql_free_result($result);
	?>
	<input name="invoicenum1" type="text" id="invoicenum1" style="width: 400px;" disabled="disabled" class="buttontxt" value="<? echo $newIVcode;?>" />
	<input name="invoicenum" type="hidden" id="invoicenum" style="width: 400px;" class="buttontxt" value="<? echo $newIVcode;?>" />	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Client Name :*</td>
	<td>
	<select name="company" id="company" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, company FROM wbm_clients WHERE companyid=$strWbmCompanyid ORDER BY company ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCoID = $row['ID'];
	$strCoName = $row['company'];
	?>
	<option value="<?PHP echo $strCoID; ?>"><?PHP echo $strCoName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Invoice Date :*</td>
	  <td><input type='text' name='invoicedate' id='invoicedate' style="width: 370px;" class="date-pick"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">% Discount :*</td>
	<td><input type='text' name='discount' id='discount' style="width: 400px;" class="validate[required,custom[onlyNumber]] text-input"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Calculate <? echo WBM_SALESTAX_NAME;?> :*</td>
	<td><input name="calctax" type="radio" value="1" checked />Yes&nbsp;<input name="calctax" type="radio" value="0"/>No&nbsp;<input name="calctax" type="radio" value="2"/>Inclusive</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="28%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
	<input type="hidden" name="action" value="<?=$action?>" />
	<br>
	<br></td>
	<td width="26%" align="left">&nbsp;</td>
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

function EditRecord($action, $strID) {
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Select Current Configuration details
	$result=@mysql_query("SELECT * FROM wbm_pro_invoices WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strInvoiceNum = $row->invoiceNum; 	
		$strCompanyID = $row->clientname; 	
		$strInvoiceDate = $row->invoiceDate; 
			//separate date, month and year
			$strTheyear = substr($strInvoiceDate, 0, 4); 
			$strThemonth = substr($strInvoiceDate, 5, 2);
			$strThedate = substr($strInvoiceDate, 8, 2);
	
		$strInvoiceDue = $row->invoiceDue;
			//separate date, month and year
			$strTheyear1 = substr($strInvoiceDue, 0, 4); 
			$strThemonth1 = substr($strInvoiceDue, 5, 2);
			$strThedate1 = substr($strInvoiceDue, 8, 2);

		$strDiscount = $row->discount; 	
		$strSubTotal = $row->subTotal; 	
		$strDiscountAmnt = $row->discountamnt;
		$strSalesTax = $row->salesTax; 	
		$strInvoiceAmount = $row->invoiceAmount; 	
		$strAmountPaid = $row->amountPaid; 	
		$strAmountDue = $row->amountDue; 	
		$strStatus = $row->status; 
		$strCreated = date("F j, Y",strtotime($row->created));
		$strCreatedby = $row->createdby;
		$strModified = date("F j, Y",strtotime($row->modified));
		$strModifiedby = $row->modifiedby;
	}
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="pro_invoices_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="pro_invoices_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="pro_invoices_mgr.php?searchmode=none">
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
	<td colspan="5" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date Created  :*</td>
	<td><input name="creatdate" type="text" id="creatdate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strCreated;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Invoice No# :*</td>
	<td><input name="invoicenum" type="text" id="invoicenum" style="width: 400px;" disabled="disabled" class="buttontxt" value="<? echo $strInvoiceNum;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Client Name :*</td>
	<td>
	<select name="company" id="company" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, company FROM wbm_clients WHERE companyid=$strWbmCompanyid ORDER BY company ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCoID = $row['ID'];
	$strCoName = $row['company'];
	?>
	<option value="<?PHP echo $strCoID; ?>" <?php if($strCompanyID==$strCoID){echo "selected";}?>><?PHP echo $strCoName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>
	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Invoice Date :*</td>
	  <td><table width="55%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><select name="thedate" disabled="disabled" >
				<?PHP 				
				for ($p=1; $p<=31; $p++)
				{
					echo '<option value="'.$p.'"';if($p == "$strThedate"){echo "Selected";}echo ' />'.$p.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="themonth" disabled="disabled" >
				<?PHP 				
				for ($j=1; $j<=12; $j++)
				{
					echo '<option value="'.$j.'"';if($j == "$strThemonth"){echo "Selected";}echo ' />'.$j.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="theyear" disabled="disabled" >
				<?PHP 				
				for ($i=2005; $i<date('Y')+1; $i++)
				{
					echo '<option value="'.$i.'"';if($i == "$strTheyear"){echo "Selected";}echo ' />'.$i.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
		    <td class="logotext">dd/mm/yyyy</td>
		  </tr>
		</table></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">% Discount :*</td>
	<td><input name="discount" type="text" id="discount" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strDiscount;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Sub Total (<? echo WBM_CURRENCY_SYMBOL;?>) :*</td>
	<td><input name="subtotal" type="text" id="subtotal" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strSubTotal;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Discount Amount (<? echo WBM_CURRENCY_SYMBOL;?>) :*</td>
	<td><input name="discountamnt" type="text" id="discountamnt" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strDiscountAmnt;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Sales Tax (<? echo WBM_SALESTAX_NAME;?>) :</td>
	<td><input name="salestax" type="text" id="salestax" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strSalesTax;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Invoice Amount (<? echo WBM_CURRENCY_SYMBOL;?>) :</td>
	<td><input name="invoiceamount" type="text" id="invoiceamount" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strInvoiceAmount;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Amount Paid (<? echo WBM_CURRENCY_SYMBOL;?>) :</td>
	<td><input name="amountpaid" type="text" id="amountpaid" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strAmountPaid;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Due Amount (<? echo WBM_CURRENCY_SYMBOL;?>) :</td>
	<td><input name="dueamount" type="text" id="dueamount" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?php echo $strAmountDue;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Invoice Status :*</td>
	<td>
	<select name="invstatus" id="invstatus" class="validate[required]" style="width: 400px;">
		<?PHP
		$sql = "SELECT * FROM wbm_categories WHERE parent=89 ORDER BY category ASC";
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
		$strStatusID = $row['ID'];
		$strStatusName = $row['category'];
		?>
		<option value="<?PHP echo $strStatusID;?>" <? if($strStatusID==$strStatus){echo "Selected";}?>><?PHP echo $strStatusName;?></option>
		<?PHP
		}
		?>
	</select>
	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date Modified :*</td>
	<td><input name="modifieddate" type="text" id="modifieddate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strModified;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="28%">&nbsp;</td>
	<td width="46%" align="left">
	<input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br />
	<br /></td>
	<td width="26%" align="left">&nbsp;</td>
	</tr>
	</tbody>
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
function ProcessAddForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strInvoicenum = preg_replace('/\'/', "''", trim($formValues["invoicenum"]));
	$strCompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strInvoiceDate = $formValues["invoicedate"];
	$strDuedate = $strInvoiceDate;
	$strDiscount = preg_replace('/\'/', "''", trim($formValues["discount"]));
	$strCalctax = preg_replace('/\'/', "''", trim($formValues["calctax"]));
	$_SESSION['calc_tax'] = $strCalctax;
	$strStatus = 90;
	$strCreated = date('Y-m-d');

	// Insert record into database table
	mysql_query("INSERT INTO wbm_pro_invoices (companyid, invoiceNum, clientname, invoiceDate, invoiceDue, discount, status, created, modified) VALUES ('$strWbmCompanyid', '$strInvoicenum', '$strCompany', '$strInvoiceDate', '$strDuedate', '$strDiscount', '$strStatus', '$strCreated', '$strCreated')") or die (mysql_error());
	// Update Invoice Number
	mysql_query("UPDATE wbm_settings SET invoicenum='$strInvoicenum' WHERE companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: invoicedetails_mgr.php?invoice='.$strInvoicenum);
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = $formValues["theID"];
	$strCompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strInvStatus = $formValues["invstatus"];
	mysql_query("UPDATE wbm_pro_invoices SET clientname='$strCompany', status=$strInvStatus WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: pro_invoices_mgr.php');
	exit();
}

// Delete Record
function DeleteRecord($strID, $strInvoiceID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//Check if invoice is attached to payments
	$result1=@mysql_query("SELECT * FROM wbm_income WHERE invoiceno=$strInvoiceID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result1) <> 0) {	
	$strResult1=1;
	}else{$strResult1=0;}
		
	if($strResult1==0){
		//Delete Invoice Details
		mysql_query("DELETE FROM wbm_invoicedetails WHERE invoiceNum=$strInvoiceID AND companyid=$strWbmCompanyid") or die(mysql_error());
		//Delete the invoice
		mysql_query("DELETE FROM wbm_pro_invoices WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	}
	else{
	//Some payments are associated with this invoice. Delete them first before deleting the invoice.
	}
	
	// Redirect to Main page
	header('Location: pro_invoices_mgr.php');
	exit();
}
?>