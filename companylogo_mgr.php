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

//Check if user is logged in
$uzuriweb_wbm_user_cat=$_SESSION['uzuriweb_wbm_usercat_id'];
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$uzuriwebpgname="companylogo_mgr";
	$strPath = "";
	$strTitle = 'Update Company Logo';
	
	include('includes/wbm_header.php');
	?>
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
			document.getElementById('message').innerHTML = data.error;
		} else {
			document.getElementById('message').innerHTML = data.error;
		}
	} else {
		document.getElementById('message').innerHTML = 'Unknown error!';
	}
} 

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
				<td width="70%" class="breadcrumbs"><strong>NOTE: Complete Step 1 before you get to Step 2.</strong></td>
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
				$strCompanyLogo = $_POST['logoimg'];
				$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
				//Start Authentication
				$sql = "UPDATE wbm_settings SET company_logo='$strCompanyLogo' WHERE companyid=$strWbmCompanyid LIMIT 1";
				$result = mysql_query($sql) or die(mysql_error());
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="19%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="79%" align="left">Company Logo Updated Successfully</td>
					</tr>
					</table>
					<?		
				}
				else
				{
				?>
				<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
				<thead>
				<tr>
				<td colspan="2" align="left">&nbsp;</td>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td width="22%" align="right" class="formscss" valign="top">STEP 1 :</td>
				<td width="78%" class="logotext">
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
					<img id="loading" src="loading.gif" style="display:none; position: relative;" />
					<p id="message">
					<p id="result">
				</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				<td align="right" class="formscss" valign="top">STEP 2 :</td>
				<td>
				    <h3>Copy and paste the Logo Name above into the textbox below then Update</h3>
					<form name="formID" id="formID" method="post" action="companylogo_mgr.php?PB=Y">
					<input type="text" name="logoimg" id="logoimg" size="20" class="validate[required]"/>
					<input type="Submit" value="Update" id="Submit" />
					</form>	
				</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td align="left">&nbsp;</td>
				</tr>
				</table>
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