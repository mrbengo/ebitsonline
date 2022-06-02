<?php
if(preg_match("/webprojects_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for UzuriWeb
 * Generate the form
**/
function LoadRecords() {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$strNickname = $_SESSION['uzuriweb_webprojects_prjtype'];
	// show first page by default
	$pageNum = 1;
	// if $_GET['page'] defined, use it as page number
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$realmode=$_SESSION['uzuriweb_webprojects_search'];
	if ($realmode=="Search")
	{
		$strSearchName =$_COOKIE["searchvalue"];
		$strSearchColumn =$_COOKIE["columnvalue"];
		$offset = ($pageNum - 1) * WBM_ROWS_PERVIEW;
		$query  = "SELECT * FROM wbm_webprojects";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_webprojects";
		if($strSearchName)
			{
				$query = $query." WHERE companyid=$strWbmCompanyid AND projecttype=$strNickname AND ($strSearchColumn LIKE '%$strSearchName%'"; 
				$sq = $sq." WHERE companyid=$strWbmCompanyid AND projecttype=$strNickname AND ($strSearchColumn LIKE '%$strSearchName%'"; 
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
		$query  = "SELECT * FROM wbm_webprojects WHERE companyid=$strWbmCompanyid AND projecttype=$strNickname ORDER BY projectname ASC";
		$sq   = "SELECT COUNT(ID) AS numrows FROM wbm_webprojects WHERE companyid=$strWbmCompanyid AND projecttype=$strNickname ORDER BY projectname ASC";
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
	<td width="70%"><a href="webprojects_mgr.php?searchmode=Nosearch&mode=new&prjtype=';
	echo $strNickname;
	echo '">
	<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="webprojects_mgr.php?search=y&prjtype=';
	echo $strNickname;
	echo '">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="projectname">Project Name</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=';
	echo $strNickname;
	echo '"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
	</tr>
	</table>
	</form></td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	echo "<table width=\"98%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" class=\"inputboxs\">\n";
	echo "<tr>\n";
	echo "<td colspan=3 class=\"displayheader\"><span class=\"inputheader\">TOTAL PROJECTS FOUND :</span> &nbsp;";
	echo $numrows;
	echo "</td>\n";
	echo "<td colspan=3 class=\"displayheader\">";
	echo '<table width="200" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td width="16" class="frmLegendtxt"><img src="images/edit.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Edit</td>
	<td width="16" class="frmLegendtxt"><img src="images/calender.png" width="16" height="16" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Schedule</td>
	<td width="16" class="frmLegendtxt"><img src="images/delete.png" width="16" height="15" vspace="2" border="0" /></td>
	<td width="64" class="frmLegendtxt">Delete</td>
	<td width="64" class="frmLegendtxt">&nbsp;</td>
	</tr>
	</table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"25%\" class=\"frmHeader\"><b>Project Name</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Company Name</b></td>\n";
	echo "<td width=\"15%\" class=\"frmHeader\"><b>Project Type</b></td>\n";
	echo "<td width=\"20%\" class=\"frmHeader\"><b>Project Manager</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Status</b></td>\n";
	echo "<td width=\"10%\" class=\"frmHeader\"><b>Actions</b></td>\n";
	echo "</tr>\n";
	$result = mysql_query($query) or die('Error, query failed');
	while($row = mysql_fetch_array($result))
	{
		$strID = $row['ID'];
		$strProjectname = $row['projectname']; 
		$strTheCo = $row['clientname']; 	
			//Select Page Name
			$sql2 = "SELECT company FROM wbm_clients WHERE ID=$strTheCo AND companyid=$strWbmCompanyid LIMIT 1";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$strCompanyname = $row2['company'];

		$strProjecttype = $row['projecttype'];
		   //select package name
			$sql3 = "SELECT servicename FROM wbm_webservices WHERE ID=$strProjecttype LIMIT 1";
			$result3 = mysql_query($sql3) or die(mysql_error());
			$row3 = mysql_fetch_array($result3);
			$strServicename = $row3['servicename'];
	
		$strProjectmgr = $row['projectmanager'];
		   //select project manager
			$sql4 = "SELECT fname, lname FROM wbm_team WHERE ID=$strProjectmgr AND companyid=$strWbmCompanyid LIMIT 1";
			$result4 = mysql_query($sql4) or die(mysql_error());
			$row4 = mysql_fetch_array($result4);
			$strFName = $row4['fname'];
			$strLName = $row4['lname'];
			$strTeamName = $strFName." ".$strLName;
			
		$strProjectstatus = $row['projectstatus'];
		   //select package name
			$sql5 = "SELECT category FROM wbm_categories WHERE ID=$strProjectstatus LIMIT 1";
			$result5 = mysql_query($sql5) or die(mysql_error());
			$row5 = mysql_fetch_array($result5);
			$strPrjstatus = $row5['category'];
		
		echo "<tr>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strProjectname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strCompanyname; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strServicename; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strTeamName; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\">\n";
		echo $strPrjstatus; 
		echo "</td>\n";
		echo "<td class=\"frmTabletxt\"><a href=\"webprojects_mgr.php?searchmode=Nosearch&mode=edit&ID=";
		echo $strID."&prjtype=".$strNickname;
		echo "\"><img src=\"images/edit.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"projectschedule_mgr.php?mode=month&ID=";
		echo $strID;
		echo "\"><img src=\"images/calender.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a> | \n";
		echo "<a href=\"webprojects_mgr.php?searchmode=Nosearch&mode=delete&ID=";
		echo $strID."&prjtype=".$strNickname;
		echo " \"onClick=\"return confirm('Are you sure you want to delete this Web Project?')\"><img src=\"images/delete.png\" width=\"16\" height=\"16\" vspace=\"2\" border=\"0\"></a>\n";
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
	$strNickname = $_SESSION['uzuriweb_webprojects_prjtype'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=';
	echo $strNickname;
	echo '">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="webprojects_mgr.php?search=y&prjtype=';
	echo $strNickname;
	echo '">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="projectname">Project Name</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=';
	echo $strNickname;
	echo '"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Project Name :*</td>
	<td><input name="projectname" type="text" id="projectname" class="validate[required]" style="width: 400px;"/></td>
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
	  </select>
	</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Overview :</td>
	<td><textarea name="prjoverview" id="prjoverview" style="width: 400px; height:200px;"></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Deliverables :</td>
	<td><textarea name="prjdeliverables" id="prjdeliverables" style="width: 400px; height:200px;"></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Manager :*</td>
	<td>
	<select name="prjmanager" id="prjmanager" class="validate[required]" style="width: 400px;">
		<?PHP
		$sql = "SELECT * FROM wbm_team WHERE companyid=$strWbmCompanyid ORDER BY fname ASC";
		$result = mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
		$strTeamID = $row['ID'];
		$strFName = $row['fname'];
		$strLName = $row['lname'];
		$strTeamName = $strFName." ".$strLName;
		?>
		<option value="<?PHP echo $strTeamID;?>"><?PHP echo $strTeamName;?></option>
		<?PHP
		}
		?>
	</select>
	</td>
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
	$strNickname = $_SESSION['uzuriweb_webprojects_prjtype'];
	// Select Current Configuration details
	$result=@mysql_query("SELECT * FROM wbm_webprojects WHERE ID=$strID AND companyid=$strWbmCompanyid AND projecttype=$strNickname LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strID = $row->ID;
		$strProjectname = $row->projectname;
		$strClientname = $row->clientname;
		$strProjecttype = $row->projecttype;
		$strOverview = $row->overview;
		$strDeliverables = $row->deliverables;
		$strProjectmanager = $row->projectmanager;
		$strProjectstatus = $row->projectstatus;
	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=';
	echo $strNickname;
	echo '">
	<img src="images/backbutton.png" width="219" height="39" border="0"/></a></td>
	<td width="30%" align="right"><form name="form4" method="post" action="webprojects_mgr.php?search=y&prjtype=';
	echo $strNickname;
	echo '">
	<table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
	<tr>
	<td width="63%" class="inputtxt">
	<input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
	<td width="63%" class="inputtxt">
	<select name="columnname" class="searchtxt">
	<option value="projectname">Project Name</option> 	
	</select>					    
	</td>
	<td width="37%" colspan="2" class="inputtxt">
	<input type="submit" name="Submit" value="Search">
	<input type="hidden" name="PHM_Search" value="form4">						
	</td>
	<td width="37%" class="inputtxt"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=';
	echo $strNickname;
	echo '"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
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
	<td align="right" class="formscss">Project Name :*</td>
	<td><input name="projectname" type="text" id="projectname" class="validate[required]" style="width: 400px;" value="<?php echo $strProjectname;?>"/></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Company Name :*</td>
	<td>
	<?PHP
	$sql = "SELECT company FROM wbm_clients WHERE ID=$strClientname LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$strCoName = $row['company'];
	?>
	<input name="company" type="text" id="company" disabled="disabled" style="width: 400px;" value="<?php echo $strCoName;?>"/>    </td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Type :*</td>
	<td><?PHP
	$sql = "SELECT * FROM wbm_webservices WHERE ID=$strProjecttype ORDER BY servicename ASC";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$strServiceID = $row['ID'];
	$strServiceName = $row['servicename'];
	?>
	<input name="projecttype" type="text" id="projecttype" disabled="disabled" style="width: 400px;" value="<?php echo $strServiceName;?>"/>
	<?PHP
	mysql_free_result($result);
	?>
	</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Overview :</td>
	<td><textarea name="prjoverview" id="prjoverview" style="width: 400px; height:200px;" class="buttontxt"><?PHP echo $strOverview;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Deliverables :</td>
	<td><textarea name="prjdeliverables" id="prjdeliverables" style="width: 400px; height:200px;" class="buttontxt"><?PHP echo $strDeliverables;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Manager :*</td>
	<td>
	<select name="prjmanager" id="prjmanager" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT * FROM wbm_team WHERE companyid=$strWbmCompanyid ORDER BY fname ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strTeamID = $row['ID'];
	$strFName = $row['fname'];
	$strLName = $row['lname'];
	$strTeamName = $strFName." ".$strLName;
	?>
	<option value="<?PHP echo $strTeamID;?>" <? if ($strProjectmanager==$strTeamID){echo "Selected";}?>><?PHP echo $strTeamName;?></option>
	<?PHP
	}
	?>
	</select>
	</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Project Status :*</td>
	<td>
	<select name="prjstatus" id="prjstatus" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT * FROM wbm_categories WHERE parent=47 ORDER BY category ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strCategoryName = $row['category'];
	?>
	<option value="<?PHP echo $strCatID; ?>" <? if($strProjectstatus==$strCatID){echo "selected";}?>><?PHP echo $strCategoryName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>			 
	</td>
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
	$strNickname = $_SESSION['uzuriweb_webprojects_prjtype'];
	// Grab Values from array
	$strProjectname = $formValues["projectname"];
	$strThecompany = $formValues["company"];
	$strPrjoverview = $formValues["prjoverview"];
	$strPrjdeliverables = $formValues["prjdeliverables"];
	$strPrjmanager = $formValues["prjmanager"];
	$strPrjstatus = 48;
	
	// Insert record into database table
	mysql_query("INSERT INTO wbm_webprojects (companyid, projectname, clientname, projecttype, overview, deliverables, projectmanager, projectstatus) VALUES ('$strWbmCompanyid', '$strProjectname', '$strThecompany', '$strNickname', '$strPrjoverview', '$strPrjdeliverables', '$strPrjmanager', '$strPrjstatus')") or die (mysql_error());
	// Redirect to Main page
	header('Location: webprojects_mgr.php?searchmode=Nosearch&prjtype='.$strNickname);
	exit();
}

// Process Submitted Form
function ProcessEditForm($formValues) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$strNickname = $_SESSION['uzuriweb_webprojects_prjtype'];
	// Grab Values from array
	$strID = $formValues["theID"];
	$strProjectname = $formValues["projectname"];
	$strPrjoverview = $formValues["prjoverview"];
	$strPrjdeliverables = $formValues["prjdeliverables"];
	$strPrjmanager = $formValues["prjmanager"];
	$strPrjstatus = $formValues["prjstatus"];
	
	// Update database table with submitted content
	mysql_query("UPDATE wbm_webprojects SET projectname='$strProjectname', overview='$strPrjoverview', deliverables='$strPrjdeliverables', projectmanager='$strPrjmanager', projectstatus='$strPrjstatus' WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die (mysql_error());
	// Redirect to Main page
	header('Location: webprojects_mgr.php?searchmode=Nosearch&prjtype='.$strNickname);
	exit();
}

// Delete Record
function DeleteRecord($strID) {
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$strNickname = $_SESSION['uzuriweb_webprojects_prjtype'];
	//Delete all Project Todo lists
	mysql_query("DELETE FROM wbm_projectcal WHERE projectID=$strID") or die(mysql_error());
	//Delete Project
	mysql_query("DELETE FROM wbm_webprojects WHERE ID=$strID AND companyid=$strWbmCompanyid LIMIT 1") or die(mysql_error());
	// Redirect to Main page
	header('Location: webprojects_mgr.php?searchmode=Nosearch&prjtype='.$strNickname);
	exit();
}
?>