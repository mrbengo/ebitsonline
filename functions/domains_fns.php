<?php
if(preg_match("/domains_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_domains_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_domains";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_domains";
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
		$query  = "SELECT * FROM wbm_domains WHERE companyid=$strWbmCompanyid ORDER BY domainname ASC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_domains WHERE companyid=$strWbmCompanyid ORDER BY domainname ASC";
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
	<td width="70%"><a href="domains_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="domains_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="domains_mgr.php?searchmode=none">
	<img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=2 class=\"displayheader\"><span class=\"inputheader\">REGISTERED DOMAINS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=4 class=\"displayheader\">";
	echo '<table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="16" class="frmLegendtxt"><img src="images/edit.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Edit</td>
	<td width="16" class="frmLegendtxt"><img src="images/delete.png" width="16" height="15" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Delete</td>
	<td width="64" class="frmLegendtxt">&nbsp;</td>
	<td width="150" class="inputheader"><a href="domains_rpt.php" target="_blank"><img src="images/printrecords.png" width="146" height="26" vspace="2" border="0" /></a></td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Domain Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Registrar</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Registration Date</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Nameserver 1</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Nameserver 2</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strDomain = $row['domainname']; 
		$strRegistrar = $row['registrar'];
		$strRegDate = date("F j, Y",strtotime($row['domaindate'])); 	
        $strNsone = $row['nsone']; 	 
		$strNstwo = $row['nstwo'];	
			
		echo "<tr>\n";
		echo '<td class="frmTabletxt"><a href="http://www.'.$strDomain.'" target="_blank" class="summarytxt">'.$strDomain.'</a>';
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strRegistrar; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strRegDate; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strNsone; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strNstwo; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"domains_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"domains_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Domain?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="domains_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="domains_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="domains_mgr.php?searchmode=none">
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
	<td align="right" class="formscss">Registrar :*</td>
	<td><input name="registrar" type="text" id="registrar" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Domain Registration Date :*</td>
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
	<td align="right" class="formscss">Nameserver 1 :*</td>
	<td><input name="nsone" type="text" id="nsone" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Nameserver 2 :*</td>
	<td><input name="nstwo" type="text" id="nstwo" class="validate[required]" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Nameserver 3 :</td>
	<td><input name="nsthree" type="text" id="nsthree" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Nameserver 4 :</td>
	<td><input name="nsfour" type="text" id="nsfour" style="width: 400px;"/></td>
	<td>&nbsp;</td>
	</tr>	
	<tr>
	<td align="right" class="formscss">Registrant Contact Info :</td>
	<td><textarea name="registrant" id="registrant" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Administrative Contact Info :</td>
	<td><textarea name="administrative" id="administrative" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Technical Contact Info :</td>
	<td><textarea name="technicalinfo" id="technicalinfo" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Billing Contact Info :</td>
	<td><textarea name="billing" id="billing" style="width: 400px; height:100px;" class="buttontxt" ></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
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
	$result=@mysql_query("SELECT * FROM wbm_domains WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strCompany = $row->company; 	
		$strDomainname = $row->domainname; 	
		$strRegistrar = $row->registrar; 	
		$strDomaindate = $row->domaindate;
			//separate date, month and year
			$strTheyear = substr($strDomaindate, 0, 4); 
			$strThemonth = substr($strDomaindate, 5, 2);
			$strThedate = substr($strDomaindate, 8, 2);
		
		$strNsone = $row->nsone; 	
		$strNstwo = $row->nstwo; 	
		$strNsthree = $row->nsthree; 	
		$strNsfour = $row->nsfour; 	
		$strRegistrant = $row->registrantinfo; 
		$strAdministrative = $row->administrativeinfo; 
		$strTechnical = $row->technicalinfo; 
		$strBilling = $row->billinginfo; 
	}
	//View Records Button
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="domains_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="domains_mgr.php?search=y">
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
	<td width="37%" class="inputtxt"><a href="domains_mgr.php?searchmode=none">
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
	<td align="right" class="formscss">Registrar :*</td>
	<td><input name="registrar" type="text" id="registrar" class="validate[required]" style="width: 400px;" value="<?php echo $strRegistrar;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Domain Registration Date :*</td>
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
	<td align="right" class="formscss">Nameserver 1 :*</td>
	<td><input name="nsone" type="text" id="nsone" class="validate[required]" style="width: 400px;" value="<?php echo $strNsone;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Nameserver 2 :*</td>
	<td><input name="nstwo" type="text" id="nstwo" class="validate[required]" style="width: 400px;" value="<?php echo $strNstwo;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Nameserver 3 :*</td>
	<td><input name="nsthree" type="text" id="nsthree" style="width: 400px;" value="<?php echo $strNsthree;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Nameserver 4 :*</td>
	<td><input name="nsfour" type="text" id="nsfour" style="width: 400px;" value="<?php echo $strNsfour;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Registrant Contact Info :</td>
	<td><textarea name="registrant" id="registrant" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strRegistrant;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Administrative Contact Info :</td>
	<td><textarea name="administrative" id="administrative" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strAdministrative;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Technical Contact Info :</td>
	<td><textarea name="technicalinfo" id="technicalinfo" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strTechnical;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Billing Contact Info :</td>
	<td><textarea name="billing" id="billing" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strBilling;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left"><input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br />
	<br /></td>
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
	$strThecompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strDomain = preg_replace('/\'/', "''", trim($formValues["domainname"]));
	$strRegistrar = preg_replace('/\'/', "''", trim($formValues["registrar"]));
		//Create the Registration Date
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strDomainreg = $strYear."-".$strMonth."-".$strTDate;

	$strNsone = preg_replace('/\'/', "''", trim($formValues["nsone"]));
	$strNstwo = preg_replace('/\'/', "''", trim($formValues["nstwo"]));
	$strNsthree = preg_replace('/\'/', "''", trim($formValues["nsthree"]));
	$strNsfour = preg_replace('/\'/', "''", trim($formValues["nsfour"]));
	$strRegistrant = preg_replace('/\'/', "''", trim($formValues["registrant"]));
	$strAdministrative = preg_replace('/\'/', "''", trim($formValues["administrative"]));
	$strTechnical = preg_replace('/\'/', "''", trim($formValues["technicalinfo"]));
	$strBilling = preg_replace('/\'/', "''", trim($formValues["billing"]));
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_domains (companyid, company, domainname, registrar, domaindate, nsone, nstwo, nsthree, nsfour, registrantinfo, administrativeinfo, technicalinfo, billinginfo) VALUES ('$strWbmCompanyid', '$strThecompany', '$strDomain', '$strRegistrar', '$strDomainreg', '$strNsone', '$strNstwo', '$strNsthree', '$strNsfour', '$strRegistrant', '$strAdministrative', '$strTechnical', '$strBilling')") or die (mysql_error());
	// Redirect to Main page
	header('Location: domains_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = preg_replace('/\'/', "''", trim($formValues["theID"]));
	$strThecompany = preg_replace('/\'/', "''", trim($formValues["company"]));
	$strDomain = preg_replace('/\'/', "''", trim($formValues["domainname"]));
	$strRegistrar = preg_replace('/\'/', "''", trim($formValues["registrar"]));
		//Create the Registration Date
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strDomainreg = $strYear."-".$strMonth."-".$strTDate;

	$strNsone = preg_replace('/\'/', "''", trim($formValues["nsone"]));
	$strNstwo = preg_replace('/\'/', "''", trim($formValues["nstwo"]));
	$strNsthree = preg_replace('/\'/', "''", trim($formValues["nsthree"]));
	$strNsfour = preg_replace('/\'/', "''", trim($formValues["nsfour"]));
	$strRegistrant = preg_replace('/\'/', "''", trim($formValues["registrant"]));
	$strAdministrative = preg_replace('/\'/', "''", trim($formValues["administrative"]));
	$strTechnical = preg_replace('/\'/', "''", trim($formValues["technicalinfo"]));
	$strBilling = preg_replace('/\'/', "''", trim($formValues["billing"]));

	// Update database table with submitted content
	mysql_query("UPDATE wbm_domains SET company='$strThecompany', domainname='$strDomain', registrar='$strRegistrar', domaindate='$strDomainreg', nsone='$strNsone', nstwo='$strNstwo', nsthree='$strNsthree', nsfour='$strNsfour', registrantinfo='$strRegistrant', administrativeinfo='$strAdministrative', technicalinfo='$strTechnical', billinginfo='$strBilling' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: domains_mgr.php');
	exit();
}
// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	mysql_query("DELETE FROM wbm_domains WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: domains_mgr.php');
	exit();
}
?>