<?php
if(preg_match("/reports_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for UzuriWeb
 * Generate the form
**/

function sales_leads(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Sales leads Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="salesleads_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :*</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :*</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Lead Source :*</td>
	<td>
	<select name="leadsource" id="leadsource" style="width: 400px;" class="validate[required]">
	<option value="0">All</option>
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
	<td>&nbsp;</td>
	</tr>		
	<tr>
	<td align="right" class="formscss">Lead Status :*</td>
	<td>
	<select name="leadstatus" id="leadstatus" style="width: 400px;" class="validate[required]">
	<option value="0">All</option>
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
	<td>&nbsp;</td>
	</tr>	
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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


function hosting_acc(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Hosting Accounts Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="hosting_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Hosting Package :*</td>
	<td>
	<select name="package" id="package" style="width: 400px;">
	<option value="0">All</option>
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
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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


function email_marketing(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Email Marketing Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="emailmarketing_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Hosting Package :*</td>
	<td>
	<select name="package" id="package" style="width: 400px;">
	<option value="0">All</option>
	<?PHP
	$sql = "SELECT ID, planname FROM wbm_emailmarketingplans WHERE companyid=$strWbmCompanyid ORDER BY planname ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strPlanID = $row['ID'];
	$strPlanName = $row['planname'];
	?>
	<option value="<?PHP echo $strPlanID; ?>"><?PHP echo $strPlanName;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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


function projects(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Projects Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="webprojects_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Project Manager :*</td>
	<td>
	<select name="prjmanager" id="prjmanager" class="validate[required]" style="width: 400px;">
	<option value="0">All</option>
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
	<td>&nbsp;</td>
	</tr>		
	<tr>
	<td align="right" class="formscss">Project Type :*</td>
	<td>
	<select name="prjtype" id="prjtype" style="width: 400px;" class="validate[required]">
	<option value="0">All</option>
	<?PHP
	$sql = "SELECT ID, servicename FROM wbm_webservices ORDER BY servicename ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCatID = $row['ID'];
	$strServicename = $row['servicename'];
	?>
	<option value="<?PHP echo $strCatID; ?>"><?PHP echo $strServicename;?></option>
	<?PHP
	}
	mysql_free_result($result);
	?>
	</select>			 
	</td>
	<td>&nbsp;</td>
	</tr>		
	<tr>
	<td align="right" class="formscss">Project Status :*</td>
	<td>
	<select name="prjstatus" id="prjstatus" style="width: 400px;" class="validate[required]">
	<option value="0">All</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=47 ORDER BY category ASC";
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
	<td>&nbsp;</td>
	</tr>	
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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


function income(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Income (Money In) Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="income_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Income Categories :*</td>
	<td>
	<select name="incometype" id="incometype" style="width: 400px;">
	<option value="0">All</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=84 ORDER BY category ASC";
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
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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


function expenses(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Expenses (Money Out) Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="expenses_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Expense Categories :*</td>
	<td>
	<select name="expensetype" id="expensetype" style="width: 400px;">
	<option value="0">All</option>
	<?PHP
	$sql = "SELECT ID, category FROM wbm_categories WHERE parent=61 ORDER BY category ASC";
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
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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


function invoices(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Invoices Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="invoices_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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
function cheques(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Issued Cheques Report</strong></td>
	<td width="30%" align="right">&nbsp;</td>
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
	<FORM name="formID" id="formID" action="cheques_rpt.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Report Options</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td align="right" class="formscss">Date From :</td>
	<td><input type='text' name='datefrom' id='datefrom' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Date To :</td>
	<td><input type='text' name='dateto' id='dateto' style="width: 370px;" class="date-pick"/></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="27%">&nbsp;</td>
	<td width="46%" align="left">
	<input type="image" src="images/generaterpt.png" border="0" alt="Submit Entry" title="Submit Entry" onclick="this.form.target='_blank';return true;">
	<br>
	<br>
	</td>
	<td>&nbsp;</td>
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
?>