<?php
if(preg_match("/login_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}
// Generate Login Form
function GenloginForm($mesg, $action) {
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
		if (document.getElementById("Password").value == "") {
			alert("Please enter your PASSWORD.");
			document.getElementById("Password").focus();
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
	<td class="logout" align="right">Password:</td>
	<td><input name="Password" type="password" id="Password" style="width:200px;">
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td height="15">
	<input type="submit" name="Login" value="Login">
	<input type="reset" name="Reset" value="Reset">
    <input type="hidden" name="action" value="<?=$action?>" />
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><a href="passwordreminder.php" class="logout">:: Password Reminder</a></td>
	</tr>
	</table></TD>
	</TR>
	</TBODY>
	</TABLE>
	</FORM>
<?
}

// Validates & Authenticates User Prior to Login
function loginAuthenticateUser($formValues) {
	// Grab Values from array
	$Username = $formValues["Username"];
	$Password = $formValues["Password"];	

		//Start Authentication
		$sql1 = "SELECT password FROM wbm_users WHERE username='$Username' AND active=1 LIMIT 1";
		$result1 = mysql_query($sql1) or die (mysql_error());
		$num1 = mysql_num_rows($result1);
		if($num1 <> 0){
			$row1 = mysql_fetch_array($result1);
			$strUserpaswd = $row1['password'];
			//Validate password
			$successful=1;
			$t_hasher = new wbmhash(8, FALSE);
			$check = $t_hasher->CheckPassword($Password, $strUserpaswd);
			if ($check==$successful){
				//Succesful authentication
				$sql2 = "SELECT * FROM wbm_users WHERE username='$Username' LIMIT 1";
				$result2 = mysql_query($sql2) or die (mysql_error());
				$row2 = mysql_fetch_array($result2);
				$strUserID = $row2['ID'];
				$strUserName = $row2['fullname'];
				$strCompanyId = $row2['companyid'];
				//Assign variables to Sessions
				$_SESSION['uzuriweb_wbm_authenticate_id']=$strCompanyId;
				//$_SESSION['uzuriweb_wbm_authenticate_id']=$strUserID;	
				$_SESSION['uzuriweb_wbm_username']=$strUserName;	
				$_SESSION['uzuriweb_wbm_acclvl']="admin";
				$_SESSION['uzuriweb_wbm_userID']=$strUserID;
				
				mysql_free_result($result2);
				//Redirect to dashboard
				header("Location: dashboard.php");
				exit();
 			}
			else{
			// Display Error
			$newmesg = "Invalid Password. Try again ...";
			GenloginForm($newmesg, "login");
			}
		}
		else{
		// Display Error
		$newmesg = "No such user or Your account is not active.";
		GenloginForm($newmesg, "login");
		}
		
	mysql_free_result($result1);
}
?>