<?php
if(preg_match("/statements_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Page functions for UzuriWeb
 * Generate the form
**/

function statement_gen(){
	//Define all system wide permissions
	$allPermissionsArray = array();
	$allPermissionsArray = $_SESSION['uzuriweb_wbm_permission'];
	//end of defining all permissions

	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>Statement Generator</strong></td>
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
	<FORM name="formID" id="formID" action="print_statement.php?PB=Y" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="5" align="center">Statement Details</td>
	</tr>
	</thead>
	<tbody>
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