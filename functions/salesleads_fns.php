<?php
if(preg_match("/salesleads_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_salesleads_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_salesleads";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_salesleads";
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
		$query  = "SELECT * FROM wbm_salesleads WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_salesleads WHERE companyid=$strWbmCompanyid ORDER BY ID DESC";
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
	<td width="70%"><a href="salesleads_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="salesleads_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="leadtitle">Lead Title</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="salesleads_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=4 class=\"displayheader\"><span class=\"inputheader\">TOTAL SALES LEADS FOUND :</span> &nbsp;";
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
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>First Name</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Last Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Lead Title</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Lead Source</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Lead Status</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strCompany = $row['company'];
		$strFname = $row['fname'];
		$strLname = $row['lname'];
		$strLeadtitle = $row['leadtitle'];
		$strLeadsour = $row['leadsource'];
		//select package name
			$sql3 = "SELECT category FROM wbm_categories WHERE ID=$strLeadsour LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strLeadsource = $row3['category'];
		$strLeadstat = $row['leadstatus'];
		//select package name
			$sql4 = "SELECT category FROM wbm_categories WHERE ID=$strLeadstat LIMIT 1";
			$result4 = mysql_query($sql4) or die(mysql_error());
			$row4 = mysql_fetch_array($result4);
			$strLeadstatus = $row4['category'];

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompany; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strFname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLeadtitle; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLeadsource; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strLeadstatus; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"salesleads_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"salesleads_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Sales Lead?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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

function AddRecord($action) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="salesleads_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="salesleads_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="leadtitle">Lead Title</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="salesleads_mgr.php?searchmode=none">
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
	<td colspan="4" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Company Name  :*</td>
	<td><input name="company" type="text" id="company" style="width: 400px;" class="validate[required,length[0,100]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">First Name <em>(Contact Person)</em> :*</td>
	<td><input name="fname" type="text" id="fname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Last Name <em>(Contact Person)</em> :*</td>
	<td><input name="lname" type="text" id="lname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Email Address  :*</td>
	<td><input name="email" type="text" id="email" style="width: 400px;" class="validate[required,custom[email]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Physical Location  :</td>
	<td><textarea name="location" id="location" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Postal Address  :</td>
	<td><textarea name="pobox" id="pobox" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number :</td>
	<td><input name="telno" type="text" id="telno" style="width: 400px;" class="validate[optional,custom[phone]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number :</td>
	<td><input name="cellno" type="text" id="cellno" style="width: 400px;" class="validate[optional,custom[phone]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Website URL :*</td>
	<td><input name="website" type="text" id="website" style="width: 400px;" class="validate[optional,length[0,100]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Title :*</td>
	<td><input name="leadtitle" type="text" id="leadtitle" style="width: 400px;" class="validate[required]" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Description :</td>
	<td><textarea name="leaddesc" id="leaddesc" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Source :*</td>
	<td>
	<select name="leadsource" id="leadsource" style="width: 400px;" class="validate[required]">
	<option value="0">--Select One--</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=22 ORDER BY category ASC";
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
	</select>			 
	</td>
	</tr>		
	<tr>
	<td align="right" class="formscss">Lead Status :*</td>
	<td>
	<select name="leadstatus" id="leadstatus" style="width: 400px;" class="validate[required]">
	<option value="0">--Select One--</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=23 ORDER BY category ASC";
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
	</select>			 
	</td>
	</tr>		
	<tr>
	<td width="30%">&nbsp;</td>
	<td width="70%" align="left">
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
	$result=@mysql_query("SELECT * FROM wbm_salesleads WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strFname = $row->fname;
		$strLname = $row->lname;
		$strCompany = $row->company;
		$strEmail = $row->email;
		$strLocation = $row->location;
		$strPobox = $row->pobox;
		$strTelephone = $row->telephone;
		$strCellphone = $row->cellphone;
		$strWebsite = $row->website;
		$strLeadtitle = $row->leadtitle; 	
		$strLeaddesc = $row->leaddesc; 	
		$strLeadsource = $row->leadsource; 	
		$strLeadstatus = $row->leadstatus; 	
		$strLeaddate = date("F j, Y",strtotime($row->leaddate));
		$strAssignedto = $row->assignedto;
		$strCreated = date("F j, Y",strtotime($row->created));
		$strCreatedby = $row->createdby;
	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="salesleads_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="salesleads_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="leadtitle">Lead Title</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="salesleads_mgr.php?searchmode=none">
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
	<td colspan="4" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date Created :*</td>
	<td><input name="creatdate" type="text" id="creatdate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strCreated;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Created By :*</td>
	<td>
	<?
	$sqlUsr2="SELECT fullname FROM wbm_users WHERE ID=$strCreatedby";
	$qryUsr2=mysql_query($sqlUsr2) or die(mysql_error());
	$rowUsr2=mysql_fetch_assoc($qryUsr2);
	$strCreator = $rowUsr2['fullname'];
	?>
	<input name="createby" type="text" id="createby" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strCreator;?>"/></td>
	</tr>		  
	<tr>
	<td align="right" class="formscss">Company Name  :*</td>
	<td><input name="company" type="text" id="company" style="width: 400px;" class="validate[required,length[0,100]] text-input" value="<?php echo $strCompany;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">First Name <em>(Contact Person)</em> :*</td>
	<td><input name="fname" type="text" id="fname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input" value="<?php echo $strFname;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Last Name <em>(Contact Person)</em> :*</td>
	<td><input name="lname" type="text" id="lname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input" value="<?php echo $strLname;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Email Address  :*</td>
	<td><input name="email" type="text" id="email" style="width: 400px;" class="validate[required,custom[email]] text-input" value="<?php echo $strEmail;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Physical Location  :</td>
	<td><textarea name="location" id="location" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strLocation;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Postal Address  :</td>
	<td><textarea name="pobox" id="pobox" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strPobox;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number :</td>
	<td><input name="telno" type="text" id="telno" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strTelephone;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number :</td>
	<td><input name="cellno" type="text" id="cellno" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strCellphone;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Website URL :*</td>
	<td><input name="website" type="text" id="website" style="width: 400px;" class="validate[optional,length[0,100]] text-input" value="<?php echo $strWebsite;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Title :*</td>
	<td><input name="leadtitle" type="text" id="leadtitle" style="width: 400px;" class="validate[required]" value="<?php echo $strLeadtitle;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Description  :</td>
	<td><textarea name="leaddesc" id="leaddesc" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strLeaddesc;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Source :*</td>
	<td>
	<select name="leadsource" id="leadsource" style="width: 400px;" class="validate[required]">
	<option value="0">--Select One--</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=22 ORDER BY category ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strCategoryName = $row['category'];
	?>
	<option value="<?PHP echo $strCatID; ?>" <? if($strLeadsource==$strCatID){echo "selected";}?>><?PHP echo $strCategoryName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>			 
	</td>
	</tr>		
	<tr>
	<td align="right" class="formscss">Lead Status :*</td>
	<td>
	<select name="leadstatus" id="leadstatus" style="width: 400px;" class="validate[required]">
	<option value="0">--Select One--</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=23 ORDER BY category ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strCategoryName = $row['category'];
	?>
	<option value="<?PHP echo $strCatID; ?>" <? if($strLeadstatus==$strCatID){echo "selected";}?>><?PHP echo $strCategoryName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>			 
	</td>
	</tr>		
	<tr>
	<td width="30%">&nbsp;</td>
	<td width="70%" align="left">
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
	$strCompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strFname = preg_replace('/\'/', "''", trim($formValues["fname"]));
	$strLname = preg_replace('/\'/', "''", trim($formValues["lname"]));
	$strEmail = $formValues["email"];
	$strLocation = preg_replace('/\'/', "''", trim($formValues["location"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strTelno = preg_replace('/\'/', "''", trim($formValues["telno"]));
	$strCellno = preg_replace('/\'/', "''", trim($formValues["cellno"]));
	$strWebsite = preg_replace('/\'/', "''", trim($formValues["website"]));
	$strLeadtitle = preg_replace('/\'/', "''", trim($formValues["leadtitle"]));
    $strLeaddesc = preg_replace('/\'/', "''", trim($formValues["leaddesc"]));
    $strLeadsource = preg_replace('/\'/', "''", trim($formValues["leadsource"]));
    $strLeadstatus = preg_replace('/\'/', "''", trim($formValues["leadstatus"]));
	$strLeaddate = date('Y-m-d');
	$strAssignedto = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$strCreated = date('Y-m-d');
    $strCreatedBy = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Insert record into database table
	mysql_query("INSERT INTO wbm_salesleads (companyid, fname, lname, company, email, location, pobox, telephone, cellphone, website, leadtitle, leaddesc, leadsource, leadstatus, leaddate, assignedto, created, createdby) VALUES ('$strWbmCompanyid', '$strFname', '$strLname', '$strCompany', '$strEmail', '$strLocation', '$strPobox', '$strTelno', '$strCellno', '$strWebsite', '$strLeadtitle', '$strLeaddesc', '$strLeadsource', '$strLeadstatus', '$strLeaddate', '$strAssignedto', '$strCreated', '$strCreatedBy')") or die (mysql_error());
	// Redirect to Main page
	header('Location: salesleads_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = $formValues["theID"];
	$strCompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strFname = preg_replace('/\'/', "''", trim($formValues["fname"]));
	$strLname = preg_replace('/\'/', "''", trim($formValues["lname"]));
	$strEmail = $formValues["email"];
	$strLocation = preg_replace('/\'/', "''", trim($formValues["location"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strTelno = preg_replace('/\'/', "''", trim($formValues["telno"]));
	$strCellno = preg_replace('/\'/', "''", trim($formValues["cellno"]));
	$strWebsite = preg_replace('/\'/', "''", trim($formValues["website"]));
	$strLeadtitle = preg_replace('/\'/', "''", trim($formValues["leadtitle"]));
    $strLeaddesc = preg_replace('/\'/', "''", trim($formValues["leaddesc"]));
    $strLeadsource = preg_replace('/\'/', "''", trim($formValues["leadsource"]));
    $strLeadstatus = preg_replace('/\'/', "''", trim($formValues["leadstatus"]));
	// Update database table with submitted content
	mysql_query("UPDATE wbm_salesleads SET fname='$strFname', lname='$strLname', company='$strCompany', email='$strEmail', location='$strLocation', pobox='$strPobox', telephone='$strTelno', cellphone='$strCellno', website='$strWebsite', leadtitle='$strLeadtitle', leaddesc='$strLeaddesc', leadsource='$strLeadsource', leadstatus='$strLeadstatus' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: salesleads_mgr.php');
	exit();
}
// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	mysql_query("DELETE FROM wbm_salesleads WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: salesleads_mgr.php');
	exit();
}
?>