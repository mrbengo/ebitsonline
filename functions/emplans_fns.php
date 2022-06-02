<?php
if(preg_match("/emplans_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_emplans_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_emailmarketingplans";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_emailmarketingplans";
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
		$query  = "SELECT * FROM wbm_emailmarketingplans WHERE companyid=$strWbmCompanyid ORDER BY planname ASC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_emailmarketingplans WHERE companyid=$strWbmCompanyid ORDER BY planname ASC";
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
	<td width="70%"><a href="emplans_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="emplans_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="planname ">Plan Name</option> 	
	<option value="subscribers">Subscribers</option> 	
	<option value="emaillimit">Email Limit</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="emplans_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=3 class=\"displayheader\"><span class=\"inputheader\">EMAIL MARKETING PLANS FOUND :</span> &nbsp;";
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
	echo "<td width=\"35%\" class=\"frmHeader\"><b>Plan Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Subscribers</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Email Limit</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Cost (".WBM_CURRENCY_SYMBOL.")</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strPlanname = $row['planname'];
		$strSubscribers = $row['subscribers']; 	
		$strEmaillimit = $row['emaillimit']; 	
		$strCost = $row['cost'];
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strPlanname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strSubscribers; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strEmaillimit; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCost; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"emplans_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"emplans_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Email Marketing Plan?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="emplans_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="emplans_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="planname ">Plan Name</option> 	
	<option value="subscribers">Subscribers</option> 	
	<option value="emaillimit">Email Limit</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="emplans_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Plan Name :*</td>
	<td><input name="planname" type="text" class="validate[required]" id="planname" style="width: 400px;"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Subscribers :*</td>
	<td><input name="subscribers" type="text" id="subscribers" class="validate[required]" style="width: 400px;"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Emails Limit :*</td>
	<td><input name="emaillimit" type="text" id="emaillimit" class="validate[required]" style="width: 400px;"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cost (<? echo WBM_CURRENCY_SYMBOL;?>) :*</td>
	<td><input name="cost" type="text" id="cost" style="width: 400px;" class="validate[required,custom[onlyNumber]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Other Details :</td>
	<td><textarea name="otherinfo" id="otherinfo" style="width: 400px; height:200px;" class="buttontxt" ></textarea></td>
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
	$result=@mysql_query("SELECT * FROM wbm_emailmarketingplans WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strPlanname = $row->planname; 	
		$strSubscribers = $row->subscribers; 	
		$strEmaillimit = $row->emaillimit; 	
		$strCost = $row->cost; 	
		$strOtherinfo = $row->otherdetails; 
	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="emplans_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="emplans_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="planname ">Plan Name</option> 	
	<option value="subscribers">Subscribers</option> 	
	<option value="emaillimit">Email Limit</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="emplans_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Plan Name :*</td>
	<td><input name="planname" type="text" id="planname" class="validate[required]" style="width: 400px;" value="<?php echo $strPlanname;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Subscribers :*</td>
	<td><input name="subscribers" type="text" id="subscribers" class="validate[required]" style="width: 400px;" value="<?php echo $strSubscribers;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Emails Limit :*</td>
	<td><input name="emaillimit" type="text" id="emaillimit" class="validate[required]" style="width: 400px;" value="<?php echo $strEmaillimit;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cost (<? echo WBM_CURRENCY_SYMBOL;?>) :*</td>
	<td><input name="cost" type="text" id="cost" style="width: 400px;" class="validate[required,custom[onlyNumber]] text-input" value="<?php echo $strCost;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Other Details :</td>
	<td><textarea name="otherinfo" id="otherinfo" style="width: 400px; height:200px;" class="buttontxt" ><?php echo $strOtherinfo;?></textarea></td>
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
	$strPlanname = $formValues["planname"];
	$strSubscribers = $formValues["subscribers"];
	$strEmaillimit = $formValues["emaillimit"];
	$strCost = $formValues["cost"];
	$strOtherinfo = $formValues["otherinfo"];
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_emailmarketingplans (companyid, planname, subscribers, emaillimit, cost, otherdetails) VALUES ('$strWbmCompanyid', '$strPlanname', '$strSubscribers', '$strEmaillimit', '$strCost', '$strOtherinfo')") or die (mysql_error());
	// Redirect to Main page
	header('Location: emplans_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = $formValues["theID"];
	$strPlanname = $formValues["planname"];
	$strSubscribers = $formValues["subscribers"];
	$strEmaillimit = $formValues["emaillimit"];
	$strCost = $formValues["cost"];
	$strOtherinfo = $formValues["otherinfo"];
	
	// Update database table with submitted content
	mysql_query("UPDATE wbm_emailmarketingplans SET planname='$strPlanname', subscribers='$strSubscribers', emaillimit='$strEmaillimit', cost='$strCost', otherdetails='$strOtherinfo' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: emplans_mgr.php');
	exit();
}
// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
		//Email Marketing Accounts
		$result1=@mysql_query("SELECT * FROM wbm_emailmarketing WHERE planid=$strID AND companyid=$strWbmCompanyid LIMIT 1");
		if(@mysql_num_rows($result1) <> 0) {	
		$strResult1=1;
		}else{$strResult1=0;}
		
	if($strResult1==0){
	mysql_query("DELETE FROM wbm_emailmarketingplans WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	}
	else{
	//Some email marketing accounts are associated with this package. Delete them first before deleting the package.
	}
	// Redirect to Main page
	header('Location: emplans_mgr.php');
	exit();
}
?>