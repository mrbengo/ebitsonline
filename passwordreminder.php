<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();

//Include common files
include('includes/wbm_common.php');
require "includes/wbm_hash.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>UzuriWeb - Web Design Business Manager</title>
<link href="wbm-css/uzuriweb2.css" rel="stylesheet" type="text/css" media="all">
<link rel="icon" href="../imgs/rom.ico" type="image/x-icon">
</head>
<body>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="mainbox">
  <tr>
    <td height="100" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><table width="775" height="351"  border="0" align="center" cellpadding="0" cellspacing="0" background="images/mainaccessbg.png">
      <tr>
        <td width="495" height="64" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="233" valign="top">
		<table width="44%"  border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td width="2%" height="208">&nbsp;</td>
              <td width="96%" valign="top">
                <!--###### LOGIN FUNCTIONS ######-->
				<?
				// Authenticate User
				if ($_POST['action'] == "reminder") {
					remindpassword($HTTP_POST_VARS);												
				}
				else {
					// Generate Login Form
					$mesg = "Enter your email address below.";
					GenReminderForm($mesg, "reminder");
				}
				?>
                <!--###### END OF LOGIN FUNCTIONS ######-->
			  </td>
              <td width="2%">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="18">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="92">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?
ob_end_flush();
?>