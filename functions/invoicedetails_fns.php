<?php
if(preg_match("/invoicedetails_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for BengoCMS
 * Generate the form
**/
function LoadRecords($action) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// show first page by default
	$strTheInvoice = $_SESSION['invoicecode'];
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="invoicedetails_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<td colspan="5" class="breadcrumbs">NOTE: Do not include Sales Tax amount within the Unit Cost. It will be calculated and added when you save/update the invoice. </td>
	</tr>
	  <tr>
		<td align="right" class="formscss">Invoice Number :*</td>
		<td width="14%">
		<input name="invoicenum1" type="text" id="invoicenum1" style="width: 120px;" disabled="disabled" class="buttontxt" value="<? echo $strTheInvoice;?>" />
		<input name="invoicenum" type="hidden" id="invoicenum" style="width: 400px;" class="buttontxt" value="<? echo $strTheInvoice;?>" /></td>
	    <td width="14%" rowspan="3" align="right" class="formscss" valign="top">Item Description :*</td>
	    <td width="34%" rowspan="3"><textarea name="itemdesc" id="itemdesc" style="width: 300px; height: 100px;" class="buttontxt"></textarea></td>
	    <td width="25%" rowspan="3">&nbsp;</td>
	  </tr>
	  <tr>
	    <td align="right" class="formscss">Quantity :*</td>
	    <td><input name="quantity" type="text" id="quantity" style="width: 120px;" class="validate[required,custom[onlyNumber]] text-input"/></td>
	    </tr>
	  <tr>
	    <td align="right" class="formscss">Unit Cost (<? echo WBM_CURRENCY_SYMBOL;?>) :*</td>
	    <td><input name="unitcost" type="text" id="unitcost" style="width: 120px;" class="validate[required,custom[onlyNumber]] text-input"/></td>
	    </tr>
	  
	<tr>
	<td width="13%">&nbsp;</td>
	<td align="left" colspan="4">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry" onClick="return CheckForm()">
    <input type="hidden" name="action" value="<?=$action?>" />
    <input type="hidden" name="theID" value="<?=$strID?>" />
	<br>
	<br>	</td>
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
	echo "<td colspan=7 class=\"displayheader\"><span class=\"inputheader\">TOTAL RECORDS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Item No#</b></td>\n";
	echo "<td width=\"35%\" class=\"frmHeader\"><b>Item Description</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Quantity</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Unit Price(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Total(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"5%\" class=\"frmHeader\"><b>Actions</b></td>\n";
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
		echo "<td class=\"frmTabletxt\"><a href=\"invoicedetails_mgr.php?mode=delete&ID=";
		echo $strID;
		echo "&invoice=";
		echo $strInvoiceNum;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Item?')\"><img src=\"images/delete.gif\" width=\"11\" height=\"11\" vspace=\"2\" border=\"0\"></a>\n";
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
	echo "<table width=\"30%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td  class=\"displayheader\" align=\"center\"><span class=\"inputheader\">UPDATE INVOICE</span></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class=\"frmTabletxt\" align=\"center\">\n";
	?>
	<FORM name="frmEntry" action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
    <input type="hidden" name="theInvoice" value="<?=$strTheInvoice?>" />
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
    <input type="hidden" name="action" value="invoice" />
	</form>
	<?
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo '<br>';
	echo '<br>';
	echo '<br>';
	}
	else
	{
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=7 class=\"displayheader\"><span class=\"inputheader\">TOTAL RECORDS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Item No#</b></td>\n";
	echo "<td width=\"40%\" class=\"frmHeader\"><b>Item Description</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Quantity</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Unit Price(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Total(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"5%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class=\"frmTabletxt\" colspan=\"7\" align=\"center\">NO RECORDS FOUND. ADD A NEW RECORD ABOVE</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo '<br>';
	echo '<br>';
	echo '<br>';
	}
}

// Process Submitted Form
function ProcessAddForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strTheInvoicenum = preg_replace('/\'/', "''", trim($formValues["invoicenum"]));
	$strItemdesc = preg_replace('/\'/', "''", trim($formValues["itemdesc"]));
	$strQuantity = preg_replace('/\'/', "''", trim($formValues["quantity"]));
	$strUnitprice = preg_replace('/\'/', "''", trim($formValues["unitcost"]));
	$strTotalcost = ($strQuantity*$strUnitprice);
	$strTransactionTime = date('Y-m-d');

		//Create Item Number
		$result=@mysql_query("SELECT itemName FROM wbm_invoicedetails WHERE invoiceNum=$strTheInvoicenum AND companyid=$strWbmCompanyid ORDER BY ID DESC LIMIT 1");
		if(@mysql_num_rows($result) <> 0) {		
			$row = @mysql_fetch_object($result);
			$strItemName = $row->itemName;
			$strNewItemName = $strItemName+1;
		}else{$strNewItemName = 1;}
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_invoicedetails (companyid, invoiceNum, itemName, itemDesc, quantity, unitPrice, totalCost, transactionTime) VALUES ('$strWbmCompanyid', '$strTheInvoicenum', '$strNewItemName', '$strItemdesc', '$strQuantity', '$strUnitprice', '$strTotalcost', '$strTransactionTime')") or die (mysql_error());
	// Redirect to Main page
	header('Location: invoicedetails_mgr.php?invoice='.$strTheInvoicenum);
	exit();
}

// Process Submitted Form
function ProcessInvoice($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Select all invoice details
	$strTheInvoiceID = preg_replace('/\'/', "''", trim($formValues["theInvoice"]));
 	$sql = "SELECT SUM(totalCost) AS thetotalcost FROM wbm_invoicedetails WHERE invoiceNum = '$strTheInvoiceID' AND companyid=$strWbmCompanyid";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
 	$strSubTotal = $row['thetotalcost'];
		//Get and calculate discount details
		$sql1 = "SELECT discount FROM wbm_pro_invoices WHERE invoiceNum = '$strTheInvoiceID' AND companyid=$strWbmCompanyid LIMIT 1";
		$result1 = mysql_query($sql1) or die(mysql_error());
		$row1 = mysql_fetch_array($result1);
		$strDiscID = $row1['discount'];
		mysql_free_result($result1);
		if($strDiscID <> 0){
			$strDiscountAmnt = (($strDiscID/100)*($strSubTotal));	
			$strSubTotal = $strSubTotal-$strDiscountAmnt;
		}
		else{
			$strDiscountAmnt = 0;	
			$strSubTotal = $strSubTotal-$strDiscountAmnt;
		}
		//end
	    
		//Get and calculate tax details
		$strCalctax = $_SESSION['calc_tax'];
		if($strCalctax == 1)
		{
 			$strSalestaxID = WBM_SALES_TAX;
			if($strSalestaxID <> 0){
				$strSalesTax = (($strSalestaxID/100)*($strSubTotal));	
				$strInvoiceAmount = (($strSubTotal)+($strSalesTax));	
			}
			else{
				$strSalesTax = 0;	
				$strInvoiceAmount = (($strSubTotal)+($strSalesTax));	
			}
		}
		elseif($strCalctax == 2)
		{
 			$strSalestaxID = WBM_SALES_TAX;
			if($strSalestaxID <> 0){
				$strInvoiceAmount = $strSubTotal;	
				$strNewSubTotal = ((100*$strInvoiceAmount)/($strSalestaxID+100));
				$strSalesTax = (($strInvoiceAmount) - ($strNewSubTotal));
				$strSubTotal = $strNewSubTotal;
			}
			else{
				$strSalesTax = 0;	
				$strInvoiceAmount = (($strSubTotal)+($strSalesTax));	
			}
		}
		else
		{
			$strSalesTax = 0;	
			$strInvoiceAmount = (($strSubTotal)+($strSalesTax));	
		}
		//end
	
	//Format decimal Places
	$strSubTotal = round($strSubTotal, 2);
	$strDiscountAmnt = round($strDiscountAmnt, 2);
	$strSalesTax =round($strSalesTax, 2);
	$strInvoiceAmount = number_format($strInvoiceAmount, 2,".","");
	
	// Update Invoice
	mysql_query("UPDATE wbm_pro_invoices SET subTotal='$strSubTotal', discountamnt='$strDiscountAmnt', salesTax='$strSalesTax', invoiceAmount='$strInvoiceAmount', amountDue='$strInvoiceAmount' WHERE invoiceNum = '$strTheInvoiceID' AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	//Unset Invoice
	$_SESSION['invoicecode']="";
	$_SESSION['calc_tax']="";
	// Redirect to Main page
	header('Location: pro_invoices_mgr.php');
	exit();
}


// Activate Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$strTheInvoice = $_SESSION['invoicecode'];
	mysql_query("DELETE FROM wbm_invoicedetails WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: invoicedetails_mgr.php?invoice='.$strTheInvoice);
	exit();
}
?>