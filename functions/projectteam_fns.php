<?php
if(preg_match("/projectteam_fns.php/i",$_SERVER['PHP_SELF'])) {
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
	$realmode=$_SESSION['uzuriweb_projectteam_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_team";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_team";
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
		$query  = "SELECT * FROM wbm_team WHERE companyid=$strWbmCompanyid ORDER BY fname ASC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_team WHERE companyid=$strWbmCompanyid ORDER BY fname ASC";
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
	<td width="70%"><a href="projectteam_mgr.php?mode=new">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="projectteam_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="email">Email Address</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="projectteam_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=3 class=\"displayheader\"><span class=\"inputheader\">PROJECT TEAM MEMBERS FOUND :</span> &nbsp;";
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
	<td width="150" class="inputheader"><a href="projectteam_rpt.php" target="_blank"><img src="images/printrecords.png" width="146" height="26" vspace="2" border="0" /></a></td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Team Role</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>First Name</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Last Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Email Address</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Mobile Number</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strTeam = $row['teamrole']; 
			$sql4 = "SELECT category FROM wbm_categories WHERE ID=$strTeam LIMIT 1";
			$result4 = mysql_query($sql4) or die(mysql_error());
			$row4 = mysql_fetch_array($result4);
			$strTeamrole = $row4['category'];
			
		$strFname = $row['fname']; 	
		$strLname = $row['lname']; 	
		$strEmail = $row['email'];
		$strCellphone = $row['cellphone']; 

		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strTeamrole; 
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
		echo $strCellphone; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"projectteam_mgr.php?mode=edit&ID=";
		echo $strID;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"projectteam_mgr.php?mode=delete&ID=";
		echo $strID;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Team Member?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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
	<td width="70%"><a href="projectteam_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="projectteam_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="email">Email Address</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="projectteam_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Team Role :*</td>
	<td>
	<select name="teamrole" id="teamrole" class="validate[required]" style="width: 400px;">
	    <?PHP
		$sql = "SELECT ID, category FROM wbm_categories WHERE parent=51 ORDER BY category ASC";
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
	<td align="right" class="formscss">First Name :*</td>
	<td><input name="fname" id="fname" type="text" style="width: 400px;" class="validate[required]"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Last Name :*</td>
	<td><input name="lname" id="lname" type="text" style="width: 400px;" class="validate[required]"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Email Address :*</td>
	<td><input name="email" id="email" type="text" style="width: 400px;" class="validate[required,custom[email]] text-input"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Gender :*</td>
	<td>
	<select name="gender" id="gender" class="validate[required]" style="width: 400px;">
	<option value="male">Male</option>
	<option value="female">Female</option>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Date Of Birth :*</td>
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
				for ($i=1950; $i<date('Y')+10; $i++)
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
	</select>	</td>
    <td>&nbsp;</td>
   </tr>
	<tr>
	<td align="right" class="formscss">Physical Address :</td>
	<td><textarea name="address" id="address" style="width:400px; height:100px;" class="buttontxt"></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Postal Address :</td>
	<td><textarea name="pobox" id="pobox" style="width:400px; height:100px;" class="buttontxt"></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number :</td>
	<td><input name="telno" id="telno" type="text" style="width: 400px;" class="validate[optional,custom[phone]] text-input"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number :</td>
	<td><input name="cellno" id="cellno" type="text" style="width: 400px;" class="validate[optional,custom[phone]] text-input"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Qualification :</td>
	<td><textarea name="qualification" id="qualification" style="width:400px; height:100px;" class="buttontxt"></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Experience :</td>
	<td><textarea name="experience" id="experience" style="width:400px; height:100px;" class="buttontxt"></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="28%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
    <input type="hidden" name="action" value="<?=$action?>" />
    <input type="hidden" name="theID" value="<?=$strID?>" />
	<br>
	<br></td>
	<td width="26%" align="left">&nbsp;</td>
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
	$result=@mysql_query("SELECT * FROM wbm_team WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strTeamrole = $row->teamrole; 	
		$strFname = $row->fname; 	
		$strLname = $row->lname; 	
		$strEmail = $row->email; 	
		$strGender = $row->gender; 	
		$strDob = $row->dob; 	
			//separate date, month and year
			$strTheyear = substr($strDob, 0, 4); 
			$strThemonth = substr($strDob, 5, 2);
			$strThedate = substr($strDob, 8, 2);
			
		$strCountry = $row->country; 	
		$strAddress = $row->address; 	
		$strPobox = $row->pobox; 	
		$strTelephone = $row->telephone; 	
		$strCellphone = $row->cellphone; 	
		$strQualification = $row->qualification; 	
		$strExperience = $row->experience;
		$strCreated = date("F j, Y",strtotime($row->created));
		$strModified = date("F j, Y",strtotime($row->modified));
	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="projectteam_mgr.php?searchmode=none">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="projectteam_mgr.php?search=y">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="fname">First Name</option> 	
	<option value="lname">Last Name</option> 	
	<option value="email">Email Address</option>
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="projectteam_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Date Created :*</td>
	<td><input name="creatdate" type="text" id="creatdate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strCreated;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Team Role :*</td>
	<td>
	<select name="teamrole" id="teamrole" class="validate[required]" style="width: 400px;">
	<?PHP
		$sql = "SELECT ID, category FROM wbm_categories WHERE parent=51 ORDER BY category ASC";
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
		$strCatID = $row['ID'];
		$strCategoryName = $row['category'];
		?>
		<option value="<?PHP echo $strCatID; ?>" <? if($strTeamrole==$strCatID){echo "selected";}?>><?PHP echo $strCategoryName;?></option>
		<?PHP
		}
		mysql_free_result($result);
		?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">First Name :*</td>
	<td><input name="fname" id="fname" type="text" style="width: 400px;" class="validate[required]" value="<?PHP echo $strFname;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Last Name :*</td>
	<td><input name="lname" id="lname" type="text" style="width: 400px;" class="validate[required]" value="<?PHP echo $strLname;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Email Address :*</td>
	<td><input name="email" id="email" type="text" style="width: 400px;" class="validate[required,custom[email]] text-input" value="<?PHP echo $strEmail;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Gender :*</td>
	<td><select name="gender" id="gender" class="validate[required]" style="width: 400px;">
	<option value="male" <?PHP if($strGender == "male") {echo "Selected";}?>>Male</option>
	<option value="female" <?PHP if($strGender == "female") {echo "Selected";}?>>Female</option>
	</select></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	  <td align="right" class="formscss">Date Of Birth :*</td>
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
				for ($i=1950; $i<date('Y')+10; $i++)
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
		<option value="<?PHP echo $strCountryID;?>" <?PHP if($strCountryID == $strCountry) {echo "Selected";}?>><?PHP echo $strCountryName;?></option>
		<?PHP
		}
		?>
	</select>	</td>
    <td>&nbsp;</td>
   </tr>
	<tr>
	<td align="right" class="formscss">Physical Address :</td>
	<td><textarea name="address" id="address" style="width:400px; height:100px;" class="buttontxt"><?PHP echo $strAddress;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Postal Address :</td>
	<td><textarea name="pobox" id="pobox" style="width:400px; height:100px;" class="buttontxt"><?PHP echo $strPobox;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number :</td>
	<td><input name="telno" id="telno" type="text" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?PHP echo $strTelephone;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number :</td>
	<td><input name="cellno" id="cellno" type="text" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?PHP echo $strCellphone;?>" /></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Qualification :</td>
	<td><textarea name="qualification" id="qualification" style="width:400px; height:100px;" class="buttontxt"><?PHP echo $strQualification;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Experience :</td>
	<td><textarea name="experience" id="experience" style="width:400px; height:100px;" class="buttontxt"><?PHP echo $strExperience;?></textarea></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date Modified :</td>
	<td><input name="moddate" type="text" id="moddate" disabled="disabled" style="width: 400px;" class="buttontxt" value="<?PHP echo $strModified;?>"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="28%">&nbsp;</td>
	<td width="46%" align="left">
	<input name="image" type="image" title="Submit Entry" src="images/updaterecord.png" alt="Submit Entry" border="0" />
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strID?>" />
	<br />
	<br />	</td>
	<td width="26%" align="left">&nbsp;</td>
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
	$strTeamrole = preg_replace('/\'/', "''", trim($formValues["teamrole"]));
	$strFname = preg_replace('/\'/', "''", trim($formValues["fname"]));
	$strLname = preg_replace('/\'/', "''", trim($formValues["lname"]));
	$strEmail = $formValues["email"];
	$strGender = preg_replace('/\'/', "''", trim($formValues["gender"]));
		//Create the Date Of Birth
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strDob = $strYear."-".$strMonth."-".$strTDate;

	$strCountry = preg_replace('/\'/', "''", trim($formValues["country"]));
	$strAddress = preg_replace('/\'/', "''", trim($formValues["address"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strTelephone = preg_replace('/\'/', "''", trim($formValues["telno"]));
	$strCellphone = preg_replace('/\'/', "''", trim($formValues["cellno"]));
	$strQualification = preg_replace('/\'/', "''", trim($formValues["qualification"]));
	$strExperience = preg_replace('/\'/', "''", trim($formValues["experience"]));
	$strCreated = date('Y-m-d');

	// Insert record into database table
	mysql_query("INSERT INTO wbm_team (companyid, teamrole, fname, lname, email, gender, dob, country, address, pobox, telephone, cellphone, qualification, experience, created, modified) VALUES ('$strWbmCompanyid', '$strTeamrole', '$strFname', '$strLname', '$strEmail', '$strGender', '$strDob', '$strCountry', '$strAddress', '$strPobox', '$strTelephone', '$strCellphone', '$strQualification', '$strExperience', '$strCreated', '$strCreated')") or die (mysql_error());
	// Redirect to Main page
	header('Location: projectteam_mgr.php');
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	// Grab Values from array
	$strID = $formValues["theID"];
	$strTeamrole = preg_replace('/\'/', "''", trim($formValues["teamrole"]));
	$strFname = preg_replace('/\'/', "''", trim($formValues["fname"]));
	$strLname = preg_replace('/\'/', "''", trim($formValues["lname"]));
	$strEmail = $formValues["email"];
	$strGender = preg_replace('/\'/', "''", trim($formValues["gender"]));
		//Create the Date Of Birth
		$strTDate = preg_replace('/\'/', "''", trim($formValues["thedate"]));
		$strMonth = preg_replace('/\'/', "''", trim($formValues["themonth"]));
		$strYear = preg_replace('/\'/', "''", trim($formValues["theyear"]));
		$strDob = $strYear."-".$strMonth."-".$strTDate;

	$strCountry = preg_replace('/\'/', "''", trim($formValues["country"]));
	$strAddress = preg_replace('/\'/', "''", trim($formValues["address"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strTelephone = preg_replace('/\'/', "''", trim($formValues["telno"]));
	$strCellphone = preg_replace('/\'/', "''", trim($formValues["cellno"]));
	$strQualification = preg_replace('/\'/', "''", trim($formValues["qualification"]));
	$strExperience = preg_replace('/\'/', "''", trim($formValues["experience"]));
	$strModified = date('Y-m-d');

	// Update database table with submitted content
	mysql_query("UPDATE wbm_team SET teamrole='$strTeamrole', fname='$strFname', lname='$strLname', email='$strEmail', gender='$strGender', dob='$strDob', country='$strCountry', address='$strAddress', pobox='$strPobox', telephone='$strTelephone', cellphone='$strCellphone', qualification='$strQualification', experience='$strExperience', modified='$strModified' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: projectteam_mgr.php');
	exit();
}

// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	//Check if associated with any projects
		$result1=@mysql_query("SELECT * FROM wbm_webprojects WHERE projectmanager=$strID LIMIT 1");
		if(@mysql_num_rows($result1) <> 0) {	
		$strResult1=1;
		}else{$strResult1=0;}	
		
	//No association, proceed with deletion
	if($strResult1==0)
	{
     	mysql_query("DELETE FROM wbm_team WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	}
	else
	{
	//This team member is associated with some projects. Delete them first before deleting the team member.
	}
	// Redirect to Main page
	header('Location: projectteam_mgr.php');
	exit();
}
?>