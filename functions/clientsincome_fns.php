<?php
if(preg_match("/clientsincome_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_clientsincome_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_clients";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_clients";
		if($strSearchName)
			{
				$query = $query." WHERE companyid=$strWbmCompanyid AND archived=0 AND ($strSearchColumn LIKE '%$strSearchName%'"; 
				$sq = $sq." WHERE companyid=$strWbmCompanyid AND archived=0 AND ($strSearchColumn LIKE '%$strSearchName%'"; 
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
		$query  = "SELECT * FROM wbm_clients WHERE companyid=$strWbmCompanyid AND archived=0 ORDER BY company ASC";
		$sq = "SELECT COUNT(ID) AS numrows FROM wbm_clients WHERE companyid=$strWbmCompanyid AND archived=0 ORDER BY company ASC";
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
	<td width="70%"><a href="clientsincome_mgr.php?mode=new"><img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="clientsincome_mgr.php?search=y">
	<table width="75%" border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">Income 2015</option> 	
	<option value="lname">Income 2014</option> 	
	<option value="email">Income 2013</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4"></td>
	<td width="37%" class="inputtxt"><a href="clientsincome_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=3 class=\"displayheader\"><span class=\"inputheader\">ACTIVE CLIENTS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=3 class=\"displayheader\">";
	echo '<table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="16" class="frmLegendtxt"><img src="images/edit.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Edit</td>
	<td width="16" class="frmLegendtxt"><img src="images/archive.png" width="16" height="15" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Archive</td>
	<td width="64" class="frmLegendtxt">&nbsp;</td>
	<td width="150" class="inputheader"><a href="activeclients_rpt.php" target="_blank"><img src="images/printrecords.png" width="146" height="26" vspace="2" border="0" /></a></td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"30%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Income 2015</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Income 2014</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Income 2013</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strCompany = $row['company'];
		
		//Generate 2015 Income
		
		//select sum all paid invoice for 2015 for this client
		$query1  = "SELECT SUM(invoiceAmount) AS incAmnt FROM wbm_invoices WHERE clientname=$strID AND (invoiceDate BETWEEN '2015-01-01' AND '2015-12-31') ORDER BY ID DESC";
	    $result1 = mysql_query($query1) or die('Error, query failed');
		$row1 = mysql_fetch_array($result1);
		$strIncAmount = $row1['incAmnt'];
		if($strIncAmount == 0){$strIncAmount=0;}

		//Generate 2014 Income
		$query2  = "SELECT SUM(invoiceAmount) AS incAmnt2 FROM wbm_invoices WHERE clientname=$strID AND (invoiceDate BETWEEN '2014-01-01' AND '2014-12-31') ORDER BY ID DESC";
	    $result2 = mysql_query($query2) or die('Error, query failed');
		$row2 = mysql_fetch_array($result2);
		$strIncAmount2 = $row2['incAmnt2'];
		if($strIncAmount2 == 0){$strIncAmount2=0;}

		//Generate 2013 Income
		$query3  = "SELECT SUM(invoiceAmount) AS incAmnt3 FROM wbm_invoices WHERE clientname=$strID AND (invoiceDate BETWEEN '2013-01-01' AND '2013-12-31') ORDER BY ID DESC";
	    $result3 = mysql_query($query3) or die('Error, query failed');
		$row3 = mysql_fetch_array($result3);
		$strIncAmount3 = $row3['incAmnt3'];
		if($strIncAmount3 == 0){$strIncAmount3=0;}
	
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompany; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
        echo formatMoney($strIncAmount);
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
        echo formatMoney($strIncAmount2);
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
        echo formatMoney($strIncAmount3);
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"#";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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