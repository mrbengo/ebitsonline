<?php
if(preg_match("/myclients_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_myclients_search'];
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
	<td width="70%"><a href="myclients_mgr.php?mode=new"><img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="myclients_mgr.php?search=y">
	<table width="75%" border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="email">Email Address</option>
	<option value="cellphone01">Mobile Number</option> 	
	<option value="website">Website</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4"></td>
	<td width="37%" class="inputtxt"><a href="myclients_mgr.php?searchmode=none">
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
	echo "<td width=\"15%\" class=\"frmHeader\"><b>First Name</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Last Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Email Address</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Mobile Number</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strCompany = $row['company'];
		$strFname = $row['fname'];
		$strLname = $row['lname'];
		$strEmail = $row['email'];
		$strTelno1 = $row['cellphone01']; 

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
		echo $strEmail; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strTelno1; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"myclients_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"myclients_mgr.php?mode=archive&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to archive this Company/Client?')\"><img src=\"images/archive.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="myclients_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="myclients_mgr.php?search=y">
	<table width="75%" border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="email">Email Address</option>
	<option value="cellphone01">Mobile Number</option> 	
	<option value="website">Website</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4"></td>
	<td width="37%" class="inputtxt"><a href="myclients_mgr.php?searchmode=none">
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
	<td align="right" class="formscss">Company Name :*</td>
	<td><input name="company" type="text" id="company" style="width: 400px;" class="validate[required,length[0,100]] text-input" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">First Name <em>(Contact Person)</em> :*</td>
	<td><input name="fname" type="text" id="fname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Last Name <em>(Contact Person)</em> :*</td>
	<td><input name="lname" type="text" id="lname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Email Address :*</td>
	<td><input name="email" type="text" id="email" style="width: 400px;" class="validate[required,custom[email]] text-input"/></td>
	</tr>
   <tr>
	<td align="right" class="formscss">Country :*</td>
	<td>
	<select name="country" id="country" class="validate[required]" style="width: 400px;">
		<?PHP
		$sql = "SELECT * FROM wbm_countries ORDER BY country ASC";
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
		$strCountryID = $row['ID'];
		$strCountryName = $row['country'];
		?>
		<option value="<?PHP echo $strCountryID;?>"><?PHP echo $strCountryName;?></option>
		<?PHP
		}
		?>
	</select></td>
  </tr>
	<tr>
	<td align="right" class="formscss">Physical Location :</td>
	<td><textarea name="location" id="location" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Postal Address :</td>
	<td><textarea name="pobox" id="pobox" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Zip/Postal Code :</td>
	<td><input name="pocode" type="text" id="pocode" style="width: 400px;" class="validate[optional,custom[onlyNumber]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number #1 :</td>
	<td><input name="telno1" type="text" id="telno1" style="width: 400px;" class="validate[optional,custom[phone]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number #2 :</td>
	<td><input name="telno2" type="text" id="telno2" style="width: 400px;" class="validate[optional,custom[phone]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number #1 :</td>
	<td><input name="cellno1" type="text" id="cellno1" style="width: 400px;" class="validate[optional,custom[phone]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number #2 :</td>
	<td><input name="cellno2" type="text" id="cellno2" style="width: 400px;" class="validate[optional,custom[phone]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Fax Number :</td>
	<td><input name="faxno" type="text" id="faxno" style="width: 400px;" class="validate[optional,length[0,100]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Website URL :</td>
	<td><input name="website" type="text" id="website" style="width: 400px;" class="validate[optional,length[0,100]] text-input"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Line of Business :</td>
	<td><textarea name="businessline" id="businessline" style="width: 400px; height:50px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Extra Information  :</td>
	<td><textarea name="extrainfo" id="extrainfo" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	</tr>
	<tr>
	<td width="28%">&nbsp;</td>
	<td width="72%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry" <? if($mobile_browser>0){ echo "onclick=\"return CheckForm()\"";} ?>>
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
	// Select Current Configuration details
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$result=@mysql_query("SELECT * FROM wbm_clients WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strCompany = $row->company;
		$strFname = $row->fname;
		$strLname = $row->lname;
		$strEmail = $row->email;
		$strCountry = $row->country; 	
		$strLocation = $row->location;
		$strPobox = $row->pobox;
		$strPocode = $row->pocode;
		$strTelephone01 = $row->telephone01;
		$strTelephone02 = $row->telephone02;
		$strCellphone01 = $row->cellphone01;
		$strCellphone02 = $row->cellphone02;
		$strFax = $row->fax;
		$strWebsite = $row->website;
		$strBusinessline = $row->lineofbusiness;
		$strExtrainfo = $row->othernotes;
		$strCreated = date("F j, Y",strtotime($row->created));
		$strCreatedby = $row->createdby;
		$strModified = date("F j, Y",strtotime($row->modified));
		$strModifiedby = $row->modifiedby;
	}
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="myclients_mgr.php?searchmode=none"><img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="myclients_mgr.php?search=y">
	<table width="75%" border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="company">Company Name</option> 	
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="email">Email Address</option>
	<option value="cellphone01">Mobile Number</option> 	
	<option value="website">Website</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4"></td>
	<td width="37%" class="inputtxt"><a href="myclients_mgr.php?searchmode=none">
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
	<td align="right" class="formscss">Date Created  :*</td>
	<td><input name="creatdate" type="text" id="creatdate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strCreated;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Created By  :*</td>
	<td>
	<?
	$sqlUsr2="SELECT fullname FROM wbm_users WHERE ID = $strCreatedby";
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
	<td align="right" class="formscss">Country: *</td>
	<td>
	<select name="country" id="country" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT * FROM wbm_countries ORDER BY country ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCountryID = $row['ID'];
	$strCountryName = $row['country'];
	?>
	<option value="<?PHP echo $strCountryID;?>" <?PHP if($strCountryID == $strCountry) {echo "Selected";}?>><?PHP echo $strCountryName;?></option>
	<?PHP
	}
	?>
	</select>
	</td>
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
	<td align="right" class="formscss">Zip/Postal Code :</td>
	<td><input name="pocode" type="text" id="pocode" style="width: 400px;" class="validate[optional,custom[onlyNumber]] text-input" value="<?php echo $strPocode;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number #1 :</td>
	<td><input name="telno1" type="text" id="telno1" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strTelephone01;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number #2 :</td>
	<td><input name="telno2" type="text" id="telno2" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strTelephone02;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number #1 :</td>
	<td><input name="cellno1" type="text" id="cellno1" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strCellphone01;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number #2 :</td>
	<td><input name="cellno2" type="text" id="cellno2" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strCellphone02;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Fax Number :</td>
	<td><input name="faxno" type="text" id="faxno" style="width: 400px;" class="validate[optional,length[0,100]] text-input" value="<?php echo $strFax;?>"  /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Website URL :</td>
	<td><input name="website" type="text" id="website" style="width: 400px;" class="validate[optional,length[0,100]] text-input" value="<?php echo $strWebsite;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Line of Business :</td>
	<td><textarea name="businessline" id="businessline" style="width: 400px; height:50px;" class="buttontxt" ><?php echo $strBusinessline;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Extra Information  :</td>
	<td><textarea name="extrainfo" id="extrainfo" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strExtrainfo;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date Modified  :*</td>
	<td><input name="moddate" type="text" id="moddate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strModified;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Modified By  :*</td>
	<td>
	<?
	$sqlUsr2="SELECT fullname FROM wbm_users WHERE ID = $strModifiedby";
	$qryUsr2=mysql_query($sqlUsr2) or die(mysql_error());
	$rowUsr2=mysql_fetch_assoc($qryUsr2);
	$strModifier = $rowUsr2['fullname'];
	?>
	<input name="modby" type="text" id="modby" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strModifier;?>"/></td>
	</tr>		
	<tr>
	<td width="28%">&nbsp;</td>
	<td width="72%" align="left">
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
	$strCountry = preg_replace('/\'/', "''", trim($formValues["country"])); 	
	$strLocation = preg_replace('/\'/', "''", trim($formValues["location"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strPocode = preg_replace('/\'/', "''", trim($formValues["pocode"]));
	$strTelno1 = preg_replace('/\'/', "''", trim($formValues["telno1"]));
	$strTelno2 = preg_replace('/\'/', "''", trim($formValues["telno2"]));
	$strCellno1 = preg_replace('/\'/', "''", trim($formValues["cellno1"]));
	$strCellno2 = preg_replace('/\'/', "''", trim($formValues["cellno2"]));
	$strFaxno = preg_replace('/\'/', "''", trim($formValues["faxno"]));
	$strWebsite = preg_replace('/\'/', "''", trim($formValues["website"]));
	$strBusinessline = preg_replace('/\'/', "''", trim($formValues["businessline"]));
	$strExtrainfo = preg_replace('/\'/', "''", trim($formValues["extrainfo"]));
	$strCreated = date('Y-m-d');
    $strCreatedBy = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Insert record into database table
	mysql_query("INSERT INTO wbm_clients (companyid, company, fname, lname, email, country, location, pobox, pocode, telephone01, telephone02, cellphone01, cellphone02, fax, website, lineofbusiness, othernotes, created, createdby, modified, modifiedby) VALUES ('$strWbmCompanyid', '$strCompany', '$strFname', '$strLname', '$strEmail', '$strCountry', '$strLocation', '$strPobox', '$strPocode', '$strTelno1', '$strTelno2', '$strCellno1', '$strCellno2', '$strFaxno', '$strWebsite', '$strBusinessline', '$strExtrainfo', '$strCreated','$strCreatedBy','$strCreated','$strCreatedBy')") or die (mysql_error());
	// Redirect to Main page
	header('Location: myclients_mgr.php');
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
	$strCountry = preg_replace('/\'/', "''", trim($formValues["country"])); 	
	$strLocation = preg_replace('/\'/', "''", trim($formValues["location"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strPocode = preg_replace('/\'/', "''", trim($formValues["pocode"]));
	$strTelno1 = preg_replace('/\'/', "''", trim($formValues["telno1"]));
	$strTelno2 = preg_replace('/\'/', "''", trim($formValues["telno2"]));
	$strCellno1 = preg_replace('/\'/', "''", trim($formValues["cellno1"]));
	$strCellno2 = preg_replace('/\'/', "''", trim($formValues["cellno2"]));
	$strFaxno = preg_replace('/\'/', "''", trim($formValues["faxno"]));
	$strWebsite = preg_replace('/\'/', "''", trim($formValues["website"]));
	$strBusinessline = preg_replace('/\'/', "''", trim($formValues["businessline"]));
	$strExtrainfo = preg_replace('/\'/', "''", trim($formValues["extrainfo"]));
	$strModified = date('Y-m-d');
    $strModifiedBy = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Update database table with submitted content
	mysql_query("UPDATE wbm_clients SET company='$strCompany', fname='$strFname', lname='$strLname', email='$strEmail', country='$strCountry', location='$strLocation', pobox='$strPobox', pocode='$strPocode', telephone01='$strTelno1', telephone02='$strTelno2', cellphone01='$strCellno1', cellphone02='$strCellno2', fax='$strFaxno', website='$strWebsite', lineofbusiness='$strBusinessline', othernotes='$strExtrainfo', modified='$strModified', modifiedby='$strModifiedBy' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: myclients_mgr.php');
	exit();
}

// Activate Record
function ArchiveRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	mysql_query("UPDATE wbm_clients SET archived=1 WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: myclients_mgr.php');
	exit();
}
?>