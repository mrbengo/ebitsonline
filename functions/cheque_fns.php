<?php
if(preg_match("/cheque_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for UzuriWeb
 * Generate the form
**/
function LoadRecords() {
	//Define all system wide permissions
	//$allPermissionsArray = array();
	//$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// show first page by default
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$realmode=$_SESSION['uzuriweb_cheque_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_cheques";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_cheques";
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
		$query  = "SELECT * FROM wbm_cheques WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_cheques WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
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
	<td width="70%"><a href="cheque_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="cheque_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="chequenum">Cheque Number</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="cheque_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=3 class=\"displayheader\"><span class=\"inputheader\">CHEQUES FOUND :</span> &nbsp;";
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
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Cheque Number</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Receipient</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Date Issued</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\" align=\"right\"><b>Amount(".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Purpose</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strCHnumber = $row['chequenum']; 	
		$strCHdate = date("F j, Y",strtotime($row['chequedate']));	
		$strCHamount = $row['chequeamount']; 	
		$strCHtype = $row['chequetype']; 	
		$strCHpurpose = $row['chequepurpose']; 	
		$strCHreceipient = $row['chequereceipient']; 	
		$strCHvat = $row['deductedvat']; 
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCHnumber; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCHreceipient; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCHdate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\" align=\"right\">\n";
		echo formatMoney($strCHamount); 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCHpurpose; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"cheque_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"cheque_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Cheque?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
		echo "</td>\n";
		echo "</tr>\n";
	}
		echo "<tr>\n";
	    echo "<td colspan=6 class=\"scroll\">&nbsp;";
		echo "</tr>\n";
		echo "<tr>\n";
	echo "<td colspan=6 class=\"scroll\">Page:";
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

function AddRecord($action) {
	//Define all system wide permissions
	//$allPermissionsArray = array();
	//$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="cheque_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="cheque_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="cheque_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	 <td align="right" class="formscss">Cheque Number :*</td>
	 <td><input name="chnumber" type="text" id="chnumber" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;"/></td>
	 <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Cheque Amount (<? echo WBM_CURRENCY_SYMBOL;?>):*</td>
	  <td><input name="chamount" type="text" id="chamount" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Date Issued :*</td>
	  <td><input type='text' name='chdate' id='chdate' style="width: 370px;" class="date-pick"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Issued To (e.g Company Name) :*</td>
	  <td><input name="chissuedto" type="text" id="chissuedto" class="validate[required]" style="width: 400px;"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Purpose :*</td>
	  <td><textarea name="chpurpose" id="chpurpose" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
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
	//Define all system wide permissions
	//$allPermissionsArray = array();
	//$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Select Current Configuration details
	$result=@mysql_query("SELECT * FROM wbm_cheques WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strCHnumber = $row->chequenum; 	
		$strCHdate = $row->chequedate;	
		$strCHamount = $row->chequeamount; 	
		$strCHtype = $row->chequetype; 	
		$strCHpurpose = $row->chequepurpose; 	
		$strCHreceipient = $row->chequereceipient; 	
		$strCHvat = $row->deductedvat; 		
	}
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="cheque_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="cheque_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="cheque_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	if($strCHnumber <> 0){
	echo '<tr>
	<td align="right" class="formscss">Cheque Number :*</td>
	<td>
	<input name="chnumbertxt" type="text" id="chnumbertxt" disabled="disabled" style="width: 400px;" value="'.$strCHnumber.'"/>
	<input name="chnumber" type="hidden" id="chnumber" style="width: 400px;" value="'.$strCHnumber.'"/></td>
	<td>&nbsp;</td>
	</tr>';
	}
	?>
	<tr>
	  <td align="right" class="formscss">Cheque Amount (<? echo WBM_CURRENCY_SYMBOL;?>):*</td>
	  <td><input name="chamount" type="text" id="chamount" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;" value="<?PHP echo $strCHamount;?>"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Date Issued :*</td>
	  <td><input type='text' name='chdate' id='chdate' style="width: 370px;" class="date-pick" value="<?PHP echo $strCHdate;?>"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Issued To (e.g Company Name) :*</td>
	  <td><input name="chissuedto" type="text" id="chissuedto" class="validate[required]" style="width: 400px;" value="<?PHP echo $strCHreceipient;?>"/></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Purpose :*</td>
	  <td><textarea name="chpurpose" id="chpurpose" style="width: 400px; height:100px;" class="buttontxt" ><?PHP echo $strCHpurpose;?></textarea></td>
	  <td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
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
    $strChequeNumber = preg_replace('/\'/', "''", trim($formValues["chnumber"]));
    $strChequeAmount = preg_replace('/\'/', "''", trim($formValues["chamount"]));
    $strChequeDate = preg_replace('/\'/', "''", trim($formValues["chdate"]));
    $strChequeIssuedto = preg_replace('/\'/', "''", trim($formValues["chissuedto"]));
    $strChequePurpose = preg_replace('/\'/', "''", trim($formValues["chpurpose"]));
    $strChequeType = 1;
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_cheques (companyid, chequenum, chequedate, chequeamount, chequetype, chequepurpose, chequereceipient) VALUES ('$strWbmCompanyid', '$strChequeNumber', '$strChequeDate', '$strChequeAmount', '$strChequeType', '$strChequePurpose', '$strChequeIssuedto')") or die (mysql_error());
	
	// Redirect to Main page
	header('Location: cheque_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = $formValues["theID"];
    $strChequeType = preg_replace('/\'/', "''", trim($formValues["chtype"]));
    $strChequeAmount = preg_replace('/\'/', "''", trim($formValues["chamount"]));
    $strChequeDate = preg_replace('/\'/', "''", trim($formValues["chdate"]));
    $strChequeIssuedto = preg_replace('/\'/', "''", trim($formValues["chissuedto"]));
    $strChequePurpose = preg_replace('/\'/', "''", trim($formValues["chpurpose"]));
	
	// Update database table with submitted content
    mysql_query("UPDATE wbm_cheques SET chequedate='$strChequeDate', chequeamount='$strChequeAmount', chequepurpose='$strChequePurpose', chequereceipient='$strChequeIssuedto' WHERE ID=$strID LIMIT 1") or die (mysql_error());
	
	// Redirect to Main page
	header('Location: cheque_mgr.php');
	exit();
}
// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//mysql_query("DELETE FROM wbm_cheques WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: cheque_mgr.php');
	exit();
}
?>