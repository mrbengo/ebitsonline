<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_wbm_modes'];
$_SESSION['uzuriweb_wbm_search'];
$_SESSION['uzuriweb_wbm_usercat_id'];

//Include common files
include('includes/wbm_common.php');
require "includes/wbm_hash.php";

$HTTP_POST_VARS = $_POST;

//Check if user is logged in
$uzuriweb_wbm_user_cat=$_SESSION['uzuriweb_wbm_usercat_id'];
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$uzuriwebpgname="password_mgr";
	$strPath = "";
	$strTitle = 'Change Password';
	
	include('includes/wbm_header.php');
	?>
		<!-- required plugins -->
		<script type="text/javascript" src="datejs/date.js"></script>
		<!--[if IE]><script type="text/javascript" src="datejs/jquery.bgiframe.js"></script><![endif]-->
		<!-- jquery.datePicker.js -->
		<script type="text/javascript" src="datejs/jquery.datePicker.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="datejs/datePicker.css">
		<!--[if IE]>
		<link href="datejs/ie.css" rel="stylesheet" type="text/css" />
		<![endif]-->

		<script type="text/javascript" charset="utf-8">
            $(function()
            {
				$('.date-pick').datePicker({startDate:'1996-01-01'});
            });
		</script>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
    <td width="1%" rowspan="3" valign="top"><img src="images/spacer.gif" width="50" height="10" /></td>
    <td width="98%" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" valign="top">
		<!--###### START TOPBAR ######-->
        <?php include("includes/wbm_topbar.php")?>
		<!--###### END TOPBAR ######-->		
		</td>
        </tr>
	  <tr>
	    <td colspan="2" valign="top">
	      <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="63%" valign="top" class="breadcrumbs"><strong>YOU ARE HERE:</strong> <a href="dashboard.php">Dashboard</a>&nbsp;->&nbsp;Settings Management</td>
              <td width="37%" align="right" class="breadcrumbs"><strong>COMPANY:</strong>  <?php echo WEB_SITE_NAME;?> &nbsp;&nbsp;<strong>LOGGED IN AS:</strong>  <?php echo $_SESSION['uzuriweb_wbm_username'];?> &nbsp;&nbsp;</td>
            </tr>
          </table>
		  </td>
        </tr>
	  <tr>
	    <td height="6" colspan="2" valign="top" bgcolor="#F3F5F8" style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;"><img alt="" src="images/spacer.gif" width="10" height="10"></td>
	    </tr>
	  <tr>
	    <td width="4%" valign="top">
		<!--###### START TOPBAR ######-->
        <?php include("sidebars/settings-management.php")?>
		<!--###### END TOPBAR ######-->		
		</td>
	    <td width="96%" valign="top" bgcolor="#F3F5F8">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
          </tr>
		<tr>
            <td width="5"><img alt="" src="images/spacer.gif" width="5" height="5"></td>
            <td valign="top" class="titletx"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="titletx"><?PHP echo $strTitle;?></td>
                <td class="titletx"></td>
              <tr>
                <td width="53%" class="titletx"></td>
                <td width="47%"></td>
              </tr>
            </table></td>
	    </tr>
          <tr>
            <td width="5"><img alt="" src="images/spacer.gif" width="5" height="5"></td>
            <td valign="top">
				<!--###### ACCESS FUNCTIONS ######-->
				<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
				<tr>
				<td width="70%" class="breadcrumbs"><strong>NOTE: Your new password will become active in your next login.</strong></td>
				<td width="30%" align="right">&nbsp;</td>
				</tr>
				</table>
				<hr width="98%" align="center">
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
				<?php
				if (isset($_GET['PB'])) 
				{
				$strOldPswd = $_POST['oldpswd'];
				$strPswd1 = $_POST['pswd'];
				//Start Authentication
				$uzuriUserId=$_SESSION['uzuriweb_wbm_authenticate_id'];
				$sql1 = "SELECT password FROM wbm_users WHERE ID='$uzuriUserId' LIMIT 1";
				$result1 = mysql_query($sql1) or die (mysql_error());
				$row1 = mysql_fetch_array($result1);
				$strUserpaswd = $row1['password'];
				//Validate password
				$successful=1;
				$t_hasher = new wbmhash(8, FALSE);
				$check = $t_hasher->CheckPassword($strOldPswd, $strUserpaswd);
			    if ($check==$successful){
					//Succesful authentication
					$t_hasher = new wbmhash(8, FALSE);
					$strNewpassword = $t_hasher->HashPassword($strPswd1);
					$sql2 = "UPDATE wbm_users SET password = '$strNewpassword' WHERE ID='$uzuriUserId' LIMIT 1";
					$result2 = mysql_query($sql2) or die (mysql_error());
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="19%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="79%" align="left">New Password updated. It will be active in your next login.</td>
					</tr>
					</table>
					<?		
					}
				    else
					{		
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="19%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="79%" align="left">Current Password does not match what is in the system. Try again!</td>
					</tr>
					</table>
					<?		
					}
				}
				else
				{
				?>
				<form name="formID" id="formID" method="post" action="password_mgr.php?PB=Y">
				<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
				<thead>
				<tr>
				<td colspan="2" align="left">&nbsp;</td>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="right" class="formscss">Old Password :*</td>
				<td><input name="oldpswd" type="password" id="oldpswd" style="width: 400px;" class="validate[required]"/></td>
				</tr>
				<tr>
				<td align="right" class="formscss">New Password :*</td>
				<td><input name="pswd" type="password" id="pswd"  style="width: 400px;" class="validate[required]"/></td>
				</tr>
				<tr>
				<td align="right" class="formscss">Repeat Password :*</td>
				<td><input name="pswd2" type="password" id="pswd2"  style="width: 400px;" class="validate[required]"/></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				<td width="22%">&nbsp;</td>
				<td width="78%" align="left">
				<input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
				<br>
				</td>
				</tr>
				</table>
				</form>					
				<?		
				}
				?>
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
				<!--###### END ACCESSING FUNCTIONS ######-->
		  </td>
		</tr>
		</table></td>
		</tr>
	  <tr>
	    <td valign="top"></td>
	    <td valign="top" style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	    </tr>
	  <tr>
	    <td valign="top" class="summaryheader" align="center">&nbsp;</td>
	    <td valign="top" class="summaryheader" align="center">&nbsp;</td>
	    </tr>
	  <tr>
	    <td valign="top" class="summaryheader" align="center">&nbsp;</td>
    <td valign="top" class="summaryheader" align="left">&copy; <? echo date("Y");?> Ebits Online Ltd.</td>
	    </tr>
		<tr>
		  <td>          
	    </tbody>
	  </table>		
	  </td>
	  <td width="1%" valign="top"><img src="images/spacer.gif" width="50" height="10" /></td>
	  </tr>
   </table>
</body>
</html>
<?PHP
}
else
{
	header('Location: insufficientpermission.php');
}
ob_end_flush();
?>	  