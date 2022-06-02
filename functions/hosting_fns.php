<?php
if(preg_match("/hosting_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_hosting_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_hosting";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_hosting";
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
		$query  = "SELECT * FROM wbm_hosting WHERE companyid=$strWbmCompanyid ORDER BY domainname ASC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_hosting WHERE companyid=$strWbmCompanyid ORDER BY domainname ASC";
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
	<td width="70%"><a href="hosting_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="hosting_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="domainname">Domain Name</option> 	
	<option value="registrar">Registrar</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="hosting_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=3 class=\"displayheader\"><span class=\"inputheader\">HOSTING ACCOUNTS FOUND :</span> &nbsp;";
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
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Domain Name</b></td>\n";
	echo "<td width=\"30%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Hosting Package</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Setup Date</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strTheCo = $row['company']; 	
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strTheCo AND companyid=$strWbmCompanyid LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];

		$strDomain = $row['domainname']; 
		$strPackageID = $row['package'];
		   //select package name
			$sql3 = "SELECT packagename FROM wbm_hostingplans WHERE ID=$strPackageID AND companyid=$strWbmCompanyid LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strPackage = $row3['packagename'];
		 
		$strSetupdate = date("F j, Y",strtotime($row['hostingdate'])); 	
		
		echo "<tr>\n";
		echo '<td class="frmTabletxt"><a href="http://www.'.$strDomain.'" target="_blank" class="summarytxt">'.$strDomain.'</a>';
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strPackage; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strSetupdate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"hosting_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"hosting_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Hosting Account?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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
	<td width="70%"><a href="hosting_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="hosting_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="domainname">Domain Name</option> 	
	<option value="registrar">Registrar</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="hosting_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Company Name :*</td>
	<td>
	<select name="company" id="company" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, company FROM wbm_clients WHERE companyid=$strWbmCompanyid AND archived=0 ORDER BY company ASC";
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
	<td align="right" class="formscss">Domain Name :*</td>
	<td><input name="domainname" type="text" id="domainname" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Hosting Package :*</td>
	<td>
	<select name="package" id="package" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, packagename FROM wbm_hostingplans WHERE companyid=$strWbmCompanyid ORDER BY packagename ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strPackageID = $row['ID'];
	$strPackageName = $row['packagename'];
	?>
	<option value="<?PHP echo $strPackageID; ?>"><?PHP echo $strPackageName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Setup Date :*</td>
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
	<td align="right" class="formscss">IP Address :*</td>
	<td><input name="ipaddress" type="text" id="ipaddress" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Username :*</td>
	<td><input name="username" type="text" id="username" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Password :*</td>
	<td><input name="password" type="text" id="password" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Contact Email :*</td>
	<td><input name="contactemail" type="text" id="contactemail" class="validate[required,custom[email]] text-input" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Other Info :</td>
	<td><textarea name="otherinfo" id="otherinfo" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="25%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br>
	<br></td>
	<td width="29%" align="left">&nbsp;</td>
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
	$result=@mysql_query("SELECT * FROM wbm_hosting WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strCompany = $row->company; 	
		$strDomainname = $row->domainname; 	
		$strPackage = $row->package; 	
		$strHostingdate = $row->hostingdate; 
			//separate date, month and year
			$strTheyear = substr($strHostingdate, 0, 4); 
			$strThemonth = substr($strHostingdate, 5, 2);
			$strThedate = substr($strHostingdate, 8, 2);

		$strIpaddress = $row->ipaddress; 	
		$strUsername = $row->username; 	
		$strPassword = $row->password;
		$strContactemail = $row->contactemail;
	    $strOtherinfo = $row->otherinfo;
	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="hosting_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="hosting_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="domainname">Domain Name</option> 	
	<option value="registrar">Registrar</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="hosting_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td colspan="3" align="center"><?PHP echo $strTitle ;?></td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Company Name :*</td>
	<td>
	<select name="company" id="company" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, company FROM wbm_clients WHERE companyid=$strWbmCompanyid AND archived=0 ORDER BY company ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCoID = $row['ID'];
	$strCoName = $row['company'];
	?>
	<option value="<?PHP echo $strCoID; ?>" <?php if($strCompany==$strCoID){echo "Selected";}?>><?PHP echo $strCoName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>
	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Domain Name :*</td>
	<td><input name="domainname" type="text" id="domainname" class="validate[required]" style="width: 400px;" value="<?php echo $strDomainname;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Hosting Package :*</td>
	<td>
	<select name="package" id="package" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT ID, packagename FROM wbm_hostingplans WHERE companyid=$strWbmCompanyid ORDER BY packagename ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strPackageID = $row['ID'];
	$strPackageName = $row['packagename'];
	?>
	<option value="<?PHP echo $strPackageID; ?>" <? if($strPackage==$strPackageID){echo "selected";}?>><?PHP echo $strPackageName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>
	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Setup Date :*</td>
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
	<td align="right" class="formscss">IP Address :*</td>
	<td><input name="ipaddress" type="text" id="ipaddress" class="validate[required]" style="width: 400px;" value="<?php echo $strIpaddress;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Username :*</td>
	<td><input name="username" type="text" id="username" class="validate[required]" style="width: 400px;" value="<?php echo $strUsername;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Password :*</td>
	<td><input name="password" type="text" id="password" class="validate[required]" style="width: 400px;" value="<?php echo $strPassword;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Contact Email :*</td>
	<td><input name="contactemail" type="text" id="contactemail" class="validate[required,custom[email]] text-input" style="width: 400px;" value="<?php echo $strContactemail;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Other Info :</td>
	<td><textarea name="otherinfo" id="otherinfo" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strOtherinfo;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="25%">&nbsp;</td>
	<td width="46%" align="left"><input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br />
	<br /></td>
	<td width="29%" align="left">&nbsp;</td>
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
	$strThecompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strDomain = preg_replace('/\'/', "''", trim($formValues["domainname"]));
	$strPackage = preg_replace('/\'/', "''", trim($formValues["package"]));
		//Create the Registration Date
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strHostingreg = $strYear."-".$strMonth."-".$strTDate;

	$strIpaddress = preg_replace('/\'/', "''", trim($formValues["ipaddress"]));
	$strUsername = preg_replace('/\'/', "''", trim($formValues["username"]));
	$strPassword = preg_replace('/\'/', "''", trim($formValues["password"]));
	$strContactemail = $formValues["contactemail"];
	$strOtherinfo = preg_replace('/\'/', "''", trim($formValues["otherinfo"]));
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_hosting (companyid, company, domainname, package, hostingdate, ipaddress, username, password, contactemail, otherinfo) VALUES ('$strWbmCompanyid', '$strThecompany', '$strDomain', '$strPackage', '$strHostingreg', '$strIpaddress', '$strUsername', '$strPassword', '$strContactemail', '$strOtherinfo')") or die (mysql_error());
	// Redirect to Main page
	header('Location: hosting_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = preg_replace('/\'/', "''", trim($formValues["theID"]));
	$strThecompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strDomain = preg_replace('/\'/', "''", trim($formValues["domainname"]));
	$strPackage = preg_replace('/\'/', "''", trim($formValues["package"]));
		//Create the Registration Date
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strHostingreg = $strYear."-".$strMonth."-".$strTDate;

	$strIpaddress = preg_replace('/\'/', "''", trim($formValues["ipaddress"]));
	$strUsername = preg_replace('/\'/', "''", trim($formValues["username"]));
	$strPassword = preg_replace('/\'/', "''", trim($formValues["password"]));
	$strContactemail = $formValues["contactemail"];
	$strOtherinfo = preg_replace('/\'/', "''", trim($formValues["otherinfo"]));
	
	// Update database table with submitted content
	mysql_query("UPDATE wbm_hosting SET company='$strThecompany', domainname='$strDomain', package='$strPackage', hostingdate='$strHostingreg', ipaddress='$strIpaddress', username='$strUsername', password='$strPassword', contactemail='$strContactemail', otherinfo='$strOtherinfo' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: hosting_mgr.php');
	exit();
}
// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	mysql_query("DELETE FROM wbm_hosting WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: hosting_mgr.php');
	exit();
}
?>