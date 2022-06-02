<?php 
session_start();
$_SESSION['uzuriweb_wbm_logo_id'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Photo-upload form</title>
<style type="text/css" media="screen">
<!--
#message {
	color:#CC0000;
}
#result {
	padding-top:10px;
}
input {
	border-color:#666666;
	background-color:#ccc;
}
-->
</style>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.form.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$("#loading")
	.ajaxStart(function(){
		$(this).show();
	})
	.ajaxComplete(function(){
		$(this).hide();
	});
	var options = {
		beforeSubmit:  showRequest,
		success:       showResponse,
		url:       'upload4jquery.php',  // your upload script
		dataType:  'json'
	};
	$('#Form1').submit(function() {
		document.getElementById('message').innerHTML = '';
		$(this).ajaxSubmit(options);
		return false;
	});
}); 

function showRequest(formData, jqForm, options) {
	var fileToUploadValue = $('input[@name=fileToUpload]').fieldValue();
	if (!fileToUploadValue[0]) {
		document.getElementById('message').innerHTML = 'Please select a file.';
		return false;
	} 

	return true;
} 

function showResponse(data, statusText)  {
	if (statusText == 'success') {
		if (data.img != '') {
			document.getElementById('result').innerHTML = '<img src="/uzuriweb/upload/thumb/'+data.img+'" />';
			document.getElementById('logoimg').innerHTML = data.error;
			document.getElementById('message').innerHTML = data.error;
		} else {
			document.getElementById('message').innerHTML = data.error;
		}
	} else {
		document.getElementById('message').innerHTML = 'Unknown error!';
	}
} 

</script>
</head>
<body>
<div id="main">
  <h2>STEP 1:</h2>	   
	  <h3>Browse and Upload logo</h3>
	  <p>Max. filesize: <b>256 KB</b><br>
	  Max Width:<b>250px</b><br>
	  Max Height:<b>250px</b><br>
	  Allowed extensions are: <b>jpg, gif, png</b></p>
      <div id="formcont">
		<form id="Form1" name="Form1" method="post" action="">
		<input type="hidden" name="MAX_FILE_SIZE" value="262144" />
		<p>Select an image from your hard disk:</p>
		<div>
		<input type="file" name="fileToUpload" id="fileToUpload" size="20" />
		<input type="Submit" value="Submit" id="buttonForm" />
		</div>
		</form>
		<img id="loading" src="loading.gif" style="display:none;" />
		<p id="message">
		<p id="result">
		
  <h2>STEP 2:</h2>
	  <h3>Copy and paste the Logo Name above (in red) into the textbox below then Update</h3>
		<form id="Form2" name="Form2" method="post" action="save_logo.php">
		<input type="text" name="logoimg" id="logoimg" size="20" value="" />
		<input type="Submit" value="Update" id="Submit" />
		</form>
</div>  
</body>
</html>