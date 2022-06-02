<?php
if(preg_match("/hostingplans_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_hostingplans_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_hostingplans";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_hostingplans";
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
		$query  = "SELECT * FROM wbm_hostingplans WHERE companyid=$strWbmCompanyid ORDER BY packagename ASC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_hostingplans WHERE companyid=$strWbmCompanyid ORDER BY packagename ASC";
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
	<td width="70%"><a href="hostingplans_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="hostingplans_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="packagename ">Package Name</option> 	
	<option value="quota">Quota (MB)</option> 	
	<option value="bandwidth">Bandwidth (MB)</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="hostingplans_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=2 class=\"displayheader\"><span class=\"inputheader\">HOSTING PLANS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=3 class=\"displayheader\">";
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
	echo "<td width=\"40%\" class=\"frmHeader\"><b>Package Name</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Quota (MB)</b></td>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Bandwidth (MB)</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strPackage = $row['packagename']; 	
		$strQuota = $row['quota']; 	
		$strBandwidth = $row['bandwidth'];
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strPackage; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strQuota; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strBandwidth; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"hostingplans_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"hostingplans_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Hosting Plan?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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

function AddRecord($action) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="hostingplans_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="hostingplans_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="packagename ">Package Name</option> 	
	<option value="quota">Quota (MB)</option> 	
	<option value="bandwidth">Bandwidth (MB)</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="hostingplans_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td colspan="4" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Package Name :*</td>
	<td><input name="packagename" type="text" id="packagename" class="validate[required]" style="width: 400px;"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Quota (MB):*</td>
	<td><input name="quotamb" type="text" id="quotamb" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Bandwidth (MB) :*</td>
	<td><input name="bandwidth" type="text" id="bandwidth" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Other Info :</td>
	<td><textarea name="otherinfo" id="otherinfo" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="73%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
    <input type="hidden" name="action" value="<?=$action?>" />
    <input type="hidden" name="theID" value="<?=$strID?>" />
	<br>
	<br></td>
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
	$result=@mysql_query("SELECT * FROM wbm_hostingplans WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strPackagename = $row->packagename; 	
		$strQuota = $row->quota; 	
		$strBandwidth = $row->bandwidth; 	
	    $strOtherinfo = $row->otherinfo;
	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="hostingplans_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="hostingplans_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="packagename ">Package Name</option> 	
	<option value="quota">Quota (MB)</option> 	
	<option value="bandwidth">Bandwidth (MB)</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="hostingplans_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td colspan="4" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Package Name :*</td>
	<td><input name="packagename" type="text" id="packagename" class="validate[required]" style="width: 400px;" value="<?php echo $strPackagename;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Quota (MB):*</td>
	<td><input name="quotamb" type="text" id="quotamb" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;" value="<?php echo $strQuota;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Bandwidth (MB) :*</td>
	<td><input name="bandwidth" type="text" id="bandwidth" class="validate[required,custom[onlyNumber]] text-input" style="width: 400px;" value="<?php echo $strBandwidth;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Other Info :</td>
	<td><textarea name="otherinfo" id="otherinfo" style="width: 400px; height:100px;" class="buttontxt"><?php echo $strOtherinfo;?></textarea></td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="73%" align="left">
	<input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br />
	<br /></td>
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
	$strPackagename = preg_replace('/\'/', "''", trim($formValues["packagename"]));
	$strQuotamb = preg_replace('/\'/', "''", trim($formValues["quotamb"]));
	$strBandwidth = preg_replace('/\'/', "''", trim($formValues["bandwidth"]));
	$strOtherinfo = preg_replace('/\'/', "''", trim($formValues["otherinfo"]));
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_hostingplans (companyid, packagename, quota, bandwidth, otherinfo) VALUES ('$strWbmCompanyid', '$strPackagename', '$strQuotamb', '$strBandwidth', '$strOtherinfo')") or die (mysql_error());
	// Redirect to Main page
	header('Location: hostingplans_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = preg_replace('/\'/', "''", trim($formValues["theID"]));
	$strPackagename = preg_replace('/\'/', "''", trim($formValues["packagename"]));
	$strQuotamb = preg_replace('/\'/', "''", trim($formValues["quotamb"]));
	$strBandwidth = preg_replace('/\'/', "''", trim($formValues["bandwidth"]));
	$strOtherinfo = preg_replace('/\'/', "''", trim($formValues["otherinfo"]));
	
	// Update database table with submitted content
	mysql_query("UPDATE wbm_hostingplans SET packagename='$strPackagename', quota='$strQuotamb', bandwidth='$strBandwidth', otherinfo='$strOtherinfo' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: hostingplans_mgr.php');
	exit();
}

// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
		//Hosting Accounts
		$result1=@mysql_query("SELECT * FROM wbm_hosting WHERE package=$strID AND companyid=$strWbmCompanyid LIMIT 1");
		if(@mysql_num_rows($result1) <> 0) {	
		$strResult1=1;
		}else{$strResult1=0;}
		
	if($strResult1==0){
	mysql_query("DELETE FROM wbm_hostingplans WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	}
	else{
	//Some hosting accounts are associated with this package. Delete them first before deleting the package.
	}
	// Redirect to Main page
	header('Location: hostingplans_mgr.php');
	exit();
}
?>