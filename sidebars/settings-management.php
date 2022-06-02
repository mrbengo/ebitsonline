<?php
if(preg_match("/project-management.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Sidebar section for UzuriWeb
**/
?>
	<table width="98%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="93%">
	<table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
	<td style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	</tr>
	<tr>
	<td height="6" align="center" valign="middle">
	<table width="200"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
	<td width="5%" rowspan="8"><img src="images/spacer.gif" width="10" height="10" /></td>
	<td width="90%"><table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td width="10%" valign="top"><img src="images/property.png" alt="" name="config" width="28" height="29" border="0" style="vertical-align: middle;" /></td>
	<td width="90%" class="sideheadings">Settings Manager</td>
	</tr>
	</table></td>
	<td width="5%" rowspan="41"><img src="images/spacer.gif" width="10" height="10"></td>
	</tr>
	<tr>
	<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="config_mgr.php" class="sidelinks"><b>M</b>y Account Configuration</a></td>
	</tr>
	<tr>
	<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="companylogo_mgr.php" class="sidelinks"><b>U</b>pdate Company Logo</a></td>
	</tr>
	<tr>
	<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="password_mgr.php" class="sidelinks"><b>U</b>pdate My Password</a></td>
	</tr>
	</table>				
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	</tr>
	</tbody>
	</table>    
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	</table>