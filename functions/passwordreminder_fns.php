<?php
if(preg_match("/passwordreminder_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}
// Generate password reminder Form
function GenReminderForm($mesg, $action) {
?>
	<SCRIPT language=javascript>
	function validateForm(theForm) {
		if (document.getElementById("Username").value == ""){
		    alert("Please Enter an Email Address!!");
		    document.getElementById("Username").focus();
		    return false;
		}
		if (document.getElementById("Username").value.indexOf("@",0) == -1) {
            alert("Enter a valid email address!!");
		    document.getElementById("Username").focus();
		    return false;
        }
		return true;
	}
	</SCRIPT>
    <FORM action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST" onSubmit="return validateForm(this)">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
	<TR>
	<TD width="95%"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td colspan="2" class="frmHeader"><div align="center"><br><?php echo $mesg; ?></div></td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td class="logout" align="right">Email :</td>
	<td><input name="Username" type="text" id="Username" style="width:200px;">
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td height="15">
	<input type="submit" name="pswd" value="Generate Password">
	<input type="hidden" name="action" value="<?=$action?>" />
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><a href="index.php" class="logout">:: Back to Login</a></td>
	</tr>
	</table></TD>
	</TR>
	</TBODY>
	</TABLE>
	</FORM>
<?
}

// Generate Login Form
function GenReminderConFirm($newmesg, $action) {
?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
	<TR>
	<TD width="68%">
	<table width="78%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td colspan="2" class="frmHeader">
	<div align="center"><br><?php echo $newmesg; ?></div>
	</td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="2" align="center"><a href="index.php" class="logout">:: Back to Login</a></td>
	</tr>
	</table></TD>
	</TR>
	</TBODY>
	</TABLE>
	</FORM>
<?
}
// Validates & Authenticates User Prior to Login
function remindpassword($formValues) {

	// Grab Values from array
	$strEmail = $formValues["Username"];
// Check if the user info validates the db
	$result=@mysql_query("SELECT username, fullname FROM wbm_users WHERE username='$strEmail'");
	if(@mysql_num_rows($result)>0)
	{
		$row = @mysql_fetch_object($result);
	    $strDatabaseEmail=$row->username;	
		$strFullname=$row->fullname;	
		// start with a blank password
			$password = "";
			// define possible characters
			$possible = "0123456789bcdfghjkmnpqrstvwxyz";
			// set up a counter
			$i = 0;
			// add random characters to $password until $length is reached
			while ($i < 8)
			 {
				// pick a random character from the possible ones
				$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
				// we don't want this character if it's already in the password
				if (!strstr($password, $char))
				{
				  $password .= $char;
				  $i++;
				}
			  }
			
			$t_hasher = new wbmhash(8, FALSE);
			$strNewpassword = $t_hasher->HashPassword($password);
			$sql2 = "UPDATE wbm_users SET password = '$strNewpassword' WHERE username='$strDatabaseEmail' LIMIT 1";
			$result2 = mysql_query($sql2) or die (mysql_error());
		
		//Autoresponder
		$to = $strDatabaseEmail;
		$frm = "support@uzuriweb.com";
		$subject = "Password reset on your UzuriWeb Account";
		$body="Hello $strFullname,\n\nYou requested to have your password reset on UzuriWeb.\n\nYour new password is : $password \n\nLogin here, https://wbm.uzuriweb.com/ with this new password and change it to one you can remember easily.\n\nWarm regards,\n\nUzuriweb Bot.";
		$headers = "From: $frm \r\n" . "X-Mailer: php";
		mail($to, $subject, $body, $headers);
		$newmesg = "Your password has been generated. Check your email for new password.";
		GenReminderConFirm($newmesg, "confirm");
	}
	else
	{
		$newmesg = "Sorry, no such user. Try again...";
		GenReminderForm($newmesg, "reminder");
	}	
}
?>