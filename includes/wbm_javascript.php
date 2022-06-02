<?php
if(preg_match("/wbm_javascript.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * CMS javascript section for UzuriWeb
**/

global $uzuriwebpgname, $strPath;
	?>
	<script language="JavaScript" type="text/JavaScript">
	<!--
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	//-->
	</script>
	<script language="JavaScript" type="text/JavaScript">
	<!--
	//Function to open pop up window
	function openWin(theURL,winName,features) {
		window.open(theURL,winName,features);
	}
	//-->
	</script>
	<script language="JavaScript" type="text/JavaScript">
	<!--
	//Function to open pop up window
	function openNewWindow(URL,w,h) {
		popupWin = window.open(URL,'open_window','scrollbars, resizable, dependent, width='+w+', height='+h+', left=0, top=0');
	}
	//-->
	</script>
	<?
	
//Configuration Form Validation
if ($uzuriwebpgname=="config"){
	?>
	<script language="JavaScript" type="text/JavaScript">
	function CheckForm() 
	{
	  //Check to see if User has entered the website name
	   if (document.formID.company_name.value == '')
		{
			alert("Please enter the Website Name!!");
			document.formID.company_name.focus();
			return false;
		}
	  //Check to see if User has entered the website path
	   if (document.formID.website_path.value == '')
		{
			alert("Please enter the Website Path!!");
			document.formID.website_path.focus();
			return false;
		}
	  //Check to see if User has entered an email address and whether it is in the correct format
		if (document.formID.site_email.value == '')
		{
			alert("Please Enter an Email Address!!");
			document.formID.site_email.focus();
			return false;
		}
		if (document.formID.site_email.value.length >0 && (document.formID.site_email.value.indexOf("@",0) == -1||document.formID.site_email.value.indexOf(".",0) == -1)) {
			alert("Enter a valid email address!!");
			document.formID.site_email.focus();
			return false;
        }
	  //Check to see if User has entered the img_upload_path
	   if (document.formID.img_upload_path.value == '')
		{
			alert("Please enter Image Upload Path!!");
			document.formID.img_upload_path.focus();
			return false;
		}
	  //Check to see if User has entered the Image Upload Types
	   if (document.formID.img_upload_types.value == '')
		{
			alert("Please enter the Image Upload Types!!");
			document.formID.img_upload_types.focus();
			return false;
		}
	  //Check to see if User has entered the maximum Image Upload Size
	   if (document.formID.img_upload_size.value == '')
		{
			alert("Please enter the maximum Image Upload Size!!");
			document.formID.img_upload_size.focus();
			return false;
		}
	  //Check to see if the maximum Image Upload Size is numeric
		if (isNaN(document.formID.img_upload_size.value)) 
		{
			alert("The maximum Image Upload Size has to be numeric!");
			document.formID.img_upload_size.value = '';
			document.formID.img_upload_size.focus();
			return false;
		}
	  //Check to see if User has entered the Image Upload Width
	   if (document.formID.upload_img_width.value == '')
		{
			alert("Please enter the Image Upload Width!!");
			document.formID.upload_img_width.focus();
			return false;
		}
	  //Check to see if the Image Upload Width is numeric
		if (isNaN(document.formID.upload_img_width.value)) 
		{
			alert("The Image Upload Width has to be numeric!");
			document.formID.upload_img_width.value = '';
			document.formID.upload_img_width.focus();
			return false;
		}
	  //Check to see if User has entered the Image Upload Height
	   if (document.formID.upload_img_height.value == '')
		{
			alert("Please enter the Image Upload Height!!");
			document.formID.upload_img_height.focus();
			return false;
		}
	  //Check to see if the Image Upload Height is numeric
		if (isNaN(document.formID.upload_img_height.value)) 
		{
			alert("The Image Upload Height has to be numeric!");
			document.formID.upload_img_height.value = '';
			document.formID.upload_img_height.focus();
			return false;
		}
	  //Check to see if User has entered the Number of record rows to view in the CMS
	   if (document.formID.wbm_rows_perview.value == '')
		{
			alert("Please enter the Number of record rows to view in the CMS!!");
			document.formID.wbm_rows_perview.focus();
			return false;
		}
	  //Check to see if the Number of record rows to view in the CMS is numeric
		if (isNaN(document.formID.wbm_rows_perview.value)) 
		{
			alert("The Number of record rows to view in the CMS has to be numeric!");
			document.formID.wbm_rows_perview.value = '';
			document.formID.wbm_rows_perview.focus();
			return false;
		}
	  //Check to see if User has entered the Number of record rows to view in the CMS
	   if (document.formID.web_rows_perview.value == '')
		{
			alert("Please enter the Number of record rows to view in the Website!!");
			document.formID.web_rows_perview.focus();
			return false;
		}
	 }
	</script>
	<?
}

//Password Form Validation
if ($uzuriwebpgname=="password_mgr"){
	?>
	<script language="JavaScript" type="text/JavaScript">
	function CheckForm() 
	{
	  //Check to see if User has entered the Page name
	   if (document.formID.pswd.value == 0)
		{
			alert("Please enter the New Password!!");
			document.formID.pswd.focus();
			return false;
		}
	  //Check to see if User has entered the Page Title
	   if (document.formID.pswd2.value == "")
		{
			alert("Please confirm New Password!!");
			document.formID.pswd2.focus();
			return false;
		}
		//Check for password length
		if ((document.formID.pswd.value.length <= 3) && (document.formID.pswd.value.length > 0)){
				alert("The Password must be at least 4 characters");
				document.formID.pswd.value = ""
				document.formID.pswd2.value = ""
				document.formID.pswd.focus();
				return false;
		}
        //Check both passwords are the same
        if ((document.formID.pswd.value) != (document.formID.pswd2.value)){
                alert("The passwords entered do not match");
                document.formID.pswd.value = ""
                document.formID.pswd2.value = ""
 				document.formID.pswd.focus();
				return false;
        }  
	 }
	</script>
	<?
}
//Company Form Validation
if ($uzuriwebpgname=="myclients_mgr"){
	?>
	<script language="JavaScript" type="text/JavaScript">
	function CheckForm() 
	{
	  //Check to see if User has entered the Page name
	   if (document.formID.company.value == "")
		{
			alert("Please enter the name of the company / client!!");
			document.formID.company.focus();
			return false;
		}
	  //Check to see if User has entered the Page Title
	   if (document.formID.fname.value == "")
		{
			alert("Please enter the Contact Person's First Name!!");
			document.formID.fname.focus();
			return false;
		}
	  //Check to see if User has entered the Page Title
	   if (document.formID.lname.value == "")
		{
			alert("Please enter the Contact Person's Last Name!!");
			document.formID.lname.focus();
			return false;
		}
	  //Check to see if User has entered an email address and whether it is in the correct format
		if (document.formID.email.value == '')
		{
			alert("Please Enter an Email Address!!");
			document.formID.email.focus();
			return false;
		}
		if (document.formID.email.value.length >0 && (document.formID.email.value.indexOf("@",0) == -1||document.formID.email.value.indexOf(".",0) == -1)) {
			alert("Enter a valid email address!!");
			document.formID.email.focus();
			return false;
        }
	 }
	</script>
	<?
}

else{
}
?>