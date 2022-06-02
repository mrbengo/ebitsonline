<?php
if(preg_match("/income_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_income_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_income";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_income";
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
		$query  = "SELECT * FROM wbm_income WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_income WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
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
	<td width="70%"><a href="income_mgr.php?mode=invoice&step=stepone"><img src="images/addnewinvpay.png" width="219" height="39" border="0"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="income_mgr.php?mode=other"><img src="images/addnewincome.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="income_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="receivedfrom">Received From</option> 	
	<option value="amount">Amount</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="income_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=2 class=\"displayheader\"><span class=\"inputheader\">INCOME RECORDS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=3 class=\"displayheader\">";
	echo '<table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="16" class="frmLegendtxt"><img src="images/edit.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Edit</td>
	<td width="16" class="frmLegendtxt"><img src="images/delete.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Archive</td>
	<td width="64" class="frmLegendtxt">&nbsp;</td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Received Date</b></td>\n";
	echo "<td width=\"30%\" class=\"frmHeader\"><b>Received From</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Category</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\" align=\"right\"><b>Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strReceiveddate = date("F j, Y",strtotime($row['receiveddate']));	
		$strAmount = $row['amount']; 	
		$strReceivedfrom = $row['receivedfrom']; 	
		$strIncCatID = $row['category']; 
		   //select package name
			$sql3 = "SELECT category FROM wbm_categories WHERE ID=$strIncCatID LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strIncomeCat = $row3['category'];
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strReceiveddate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strReceivedfrom; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strIncomeCat; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo formatMoney($strAmount); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"income_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"income_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Income?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
		echo "<tr>\n";
	    echo "<td colspan=5 class=\"scroll\">&nbsp;";
		echo "</tr>\n";
		echo "<tr>\n";
	echo "<td colspan=5 class=\"scroll\">Page:";
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

function AddInvoiceRecord($action, $strSteps, $strTheClientID, $strInvID){ 
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="income_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="income_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="receivedfrom">Received From</option> 	
	<option value="amount">Amount</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="income_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<?PHP
	if($strSteps=="stepone")
	{
	?>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="4">Invoice Payment [Step One]</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Client Name :*</td>
	<td>
	<select name="clientname" class="buttontxt" id="clientname" onChange="MM_jumpMenu('parent',this,0)">
	<option value="0">--Select Client--</option>
	<?PHP
	$sql = "SELECT ID, company FROM wbm_clients WHERE companyid=$strWbmCompanyid AND archived=0 ORDER BY company ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strClientID = $row['ID'];
	$strClientName = $row['company'];
	?>
	<option value="income_mgr.php?mode=income&step=steptwo&clientid=<?PHP echo $strClientID;?>"><?PHP echo $strClientName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	</table>
	<? 
	}

	elseif($strSteps=="steptwo")
	{
	?>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="4">Invoice Payment [Step Two]</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td colspan="4">
	<?
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Inv No#</b></td>\n";
	echo "<td width=\"30%\" class=\"frmHeader\"><b>Client Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Inv Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Amount Paid(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$query  = "SELECT * FROM wbm_pro_invoices WHERE clientname=$strTheClientID AND companyid=$strWbmCompanyid AND status=90 ORDER BY invoiceNum DESC";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strInvoiceNum = $row['invoiceNum'];
		$strCompanyID = $row['clientname'];
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strCompanyID AND companyid=$strWbmCompanyid LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];
			//Save Company name in session
			$_SESSION['uzuriweb_wbm_companyname']=$strCompanyname;
		
		$strInvoiceAmount = $row['invoiceAmount'];
		$strAmountPaid = $row['amountPaid'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceNum; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strInvoiceAmount; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strAmountPaid; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"income_mgr.php?mode=invoice&step=stepthree&invid=";
		echo $strInvoiceNum;
		echo "\"><img src=\"images/paynow.jpg\" width=\"72\" height=\"23\" vspace=\"2\" border=\"0\"></a>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	?>
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	</table>
	<? 
	}
	elseif($strSteps=="stepthree")
	{
	$strCompanyname=$_SESSION['uzuriweb_wbm_companyname'];
	?>
	<FORM name="formID" id="formID" action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5">Invoice Payment [Step Three]</td>
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
	<td><input name="invoicenum" type="text" id="invoicenum" disabled="disabled" value="<?PHP echo $strInvID;?>"/>
	<input name="invoiceno" type="hidden" id="invoiceno" value="<?PHP echo $strInvID;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Received Date :*</td>
	  <td><table width="55%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><select name="thedate">
				<?PHP 				
				for ($p=1; $p<=31; $p++)
				{
					echo '<option value="'.$p.'"'; echo ' />'.$p.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="themonth">
				<?PHP 				
				for ($j=1; $j<=12; $j++)
				{
					echo '<option value="'.$j.'"'; echo ' />'.$j.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="theyear">
				<?PHP 				
				for ($i=2005; $i<date('Y')+1; $i++)
				{
					echo '<option value="'.$i.'"'; echo ' />'.$i.'</option>';echo "\n";
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
	<td align="right" class="formscss">Income Amount (<? echo WBM_CURRENCY_SYMBOL;?>):*</td>
	<td><input name="incomeamount" type="text" id="incomeamount" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Received From :*</td>
	<td><input name="receivedfrm" type="text" id="receivedfrm" disabled="disabled" style="width: 400px;" value="<? echo $strCompanyname;?>"/><input name="receivedfrom" type="hidden" id="receivedfrom" style="width: 400px;" value="<? echo $strCompanyname;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Memo :</td>
	<td><textarea name="memo" id="memo" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Category :*</td>
	<td>
	<select name="inccategory" id="inccategory" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=84 ORDER BY category ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strCategoryName = $row['category'];
	?>
	<option value="<?PHP echo $strCatID; ?>"><?PHP echo $strCategoryName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
    <input type="hidden" name="action" value="<?=$action?>" />
	<br>
	<br></td>
	<td width="27%" align="left">&nbsp;</td>
	</tr>
	</table>
	</form>
	<? 
	}
	?>
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

function AddOtherRecord($action) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="income_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="income_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="receivedfrom">Received From</option> 	
	<option value="amount">Amount</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="income_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	  <td align="right" class="formscss">Received Date :*</td>
	  <td><input name="invoiceno" type="hidden" id="invoiceno" value="0"/>
	  <table width="55%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><select name="thedate">
				<?PHP 				
				for ($p=1; $p<=31; $p++)
				{
					echo '<option value="'.$p.'"'; echo ' />'.$p.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="themonth">
				<?PHP 				
				for ($j=1; $j<=12; $j++)
				{
					echo '<option value="'.$j.'"'; echo ' />'.$j.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="theyear">
				<?PHP 				
				for ($i=2005; $i<date('Y')+1; $i++)
				{
					echo '<option value="'.$i.'"'; echo ' />'.$i.'</option>';echo "\n";
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
	<td align="right" class="formscss">Income Amount (<? echo WBM_CURRENCY_SYMBOL;?>):*</td>
	<td><input name="incomeamount" type="text" id="incomeamount" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Received From :*</td>
	<td><input name="receivedfrom" type="text" id="receivedfrom" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Memo :</td>
	<td><textarea name="memo" id="memo" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Category :*</td>
	<td>
	<select name="inccategory" id="inccategory" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=84 ORDER BY category ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strCategoryName = $row['category'];
	?>
	<option value="<?PHP echo $strCatID; ?>"><?PHP echo $strCategoryName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
    <input type="hidden" name="action" value="<?=$action?>" />
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

function EditRecord($action, $strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Select Current Configuration details
	$result=@mysql_query("SELECT * FROM wbm_income WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strInvoiceNo = $row->invoiceno;
		$strReceiveddate = $row->receiveddate; 	
			//separate date, month and year
			$strTheyear = substr($strReceiveddate, 0, 4); 
			$strThemonth = substr($strReceiveddate, 5, 2);
			$strThedate = substr($strReceiveddate, 8, 2);

		$strAmount = $row->amount; 	
		$strReceivedfrom = $row->receivedfrom; 	
		$strMemo = $row->memo; 	
		$strIncCategory = $row->category; 
	}
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="income_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="income_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="receivedfrom">Received From</option> 	
	<option value="amount">Amount</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="income_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<?
	if($strInvoiceNo <> 0){
	echo '<tr>
	<td align="right" class="formscss">Invoice Number :</td>
	<td>
	<input name="invoicenum" type="text" id="invoicenum" disabled="disabled" style="width: 400px;" value="'.$strInvoiceNo.'"/>
	<input name="invoiceno" type="hidden" id="invoiceno" style="width: 400px;" value="'.$strInvoiceNo.'"/></td>
	<td>&nbsp;</td>
	</tr>';
	}
	?>
	<tr>
	  <td align="right" class="formscss">Received Date :*</td>
	  <td><table width="55%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><select name="thedate">
				<?PHP 				
				for ($p=1; $p<=31; $p++)
				{
					echo '<option value="'.$p.'"';if($p == "$strThedate"){echo "Selected";}echo ' />'.$p.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="themonth">
				<?PHP 				
				for ($j=1; $j<=12; $j++)
				{
					echo '<option value="'.$j.'"';if($j == "$strThemonth"){echo "Selected";}echo ' />'.$j.'</option>';echo "\n";
				}
				?>
			  </select>
			</td>
			<td><select name="theyear">
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
	<td align="right" class="formscss">Income Amount (<? echo WBM_CURRENCY_SYMBOL;?>):*</td>
	<td><input name="incomeamount" type="text" id="incomeamount" style="width: 400px;" value="<?PHP echo $strAmount;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Received From :*</td>
	<td><input name="receivedfrom" type="text" id="receivedfrom" style="width: 400px;" value="<?PHP echo $strReceivedfrom;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Memo :</td>
	<td><textarea name="memo" id="memo" style="width: 400px; height:100px;" class="buttontxt" ><?PHP echo $strMemo;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Category :*</td>
	<td>
	<select name="inccategory" id="inccategory" style="width: 400px;">
	<option value="0">--Select Category--</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=84 ORDER BY category ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strCategoryName = $row['category'];
	?>
	<option value="<?PHP echo $strCatID; ?>" <? if($strIncCategory==$strCatID){echo "selected";}?>><?PHP echo $strCategoryName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="invoiceno" value="0" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br />
	<br />
	</td>
	<td width="27%" align="left">&nbsp;</td>
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
	$strInvoiceno = preg_replace('/\'/', "''", trim($formValues["invoiceno"]));
		//Create the Received Date
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strIncomedate = $strYear."-".$strMonth."-".$strTDate;

    $strIncomeamount = preg_replace('/\'/', "''", trim($formValues["incomeamount"]));
    $strReceivedfrom = preg_replace('/\'/', "''", trim($formValues["receivedfrom"]));
    $strThememo = preg_replace('/\'/', "''", trim($formValues["memo"]));
    $strInccategory = preg_replace('/\'/', "''", trim($formValues["inccategory"]));
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_income (companyid, invoiceno, receiveddate, amount, receivedfrom, memo, category) VALUES ('$strWbmCompanyid', '$strInvoiceno', '$strIncomedate', '$strIncomeamount', '$strReceivedfrom', '$strThememo', '$strInccategory')") or die (mysql_error());

	// Do invoice processing
	if($strInvoiceno <>0){
	   ///UPDATE PROFORMA INVOICE
		$sql3 = "SELECT ID, clientname, invoiceAmount, amountPaid, amountDue FROM wbm_pro_invoices WHERE invoiceNum = $strInvoiceno AND companyid=$strWbmCompanyid LIMIT 1";
		$result3 = mysql_query($sql3) or die(mysql_error());
		$row3 = mysql_fetch_array($result3);
		$strInvAmntID = $row3['ID'];
		$strInvClient = $row3['clientname'];
		$strInvAmnt = $row3['invoiceAmount'];
		$strInvAmntPaid = $row3['amountPaid'];
		$strInvAmntDue = $row3['amountDue'];
		//--Process
		$strTotalPaidAmnt = $strIncomeamount + $strInvAmntPaid;
		$strTotalDueAmnt = $strInvAmntDue - $strIncomeamount;
		if(($strTotalPaidAmnt==$strInvAmnt)&&($strTotalDueAmnt==0)){$strInvFullpaid=91;}
		else{$strInvFullpaid=90;}
		//--Save
    	mysql_query("UPDATE wbm_pro_invoices SET amountPaid='$strTotalPaidAmnt', amountDue='$strTotalDueAmnt', status='$strInvFullpaid' WHERE invoiceNum = $strInvoiceno AND ID=$strInvAmntID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
		//--end
	   
	   //UPDATE INVOICE
	   $strSTax = (16*$strIncomeamount)/116;
	   $strSalesTax = round($strSTax, 2);
	   $strSubTotal = $strIncomeamount-$strSalesTax;
	   $strCreated = date('Y-m-d');
	   mysql_query("INSERT INTO wbm_invoices (companyid, invoiceNum, clientname, invoiceDate, subTotal, salesTax, invoiceAmount, created) VALUES ('$strWbmCompanyid', '$strInvoiceno', '$strInvClient', '$strIncomedate', '$strSubTotal', '$strSalesTax', '$strIncomeamount', '$strCreated')") or die (mysql_error());
		//--end
	}
	$_SESSION['uzuriweb_wbm_companyname']="";
	
	// Redirect to Main page
	header('Location: income_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = $formValues["theID"];
	$strInvoiceno = $formValues["invoiceno"];
		//Create the Received Date
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strIncomedate = $strYear."-".$strMonth."-".$strTDate;

    $strIncomeamount = $formValues["incomeamount"];
    $strReceivedfrom = $formValues["receivedfrom"];
    $strThememo = $formValues["memo"];
    $strInccategory = $formValues["inccategory"];
	
	// Update database table with submitted content
    mysql_query("UPDATE wbm_income SET receiveddate='$strIncomedate' WHERE ID=$strID LIMIT 1") or die (mysql_error());
	
	
	// Redirect to Main page
	header('Location: income_mgr.php');
	exit();
}
// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//mysql_query("DELETE FROM wbm_income WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: income_mgr.php');
	exit();
}
?>