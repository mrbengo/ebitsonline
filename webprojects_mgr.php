<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_webprojects_search'];
$_SESSION['uzuriweb_wbm_modes'];

//Include common files
include('includes/wbm_common.php');
include('functions/webprojects_fns.php');

$HTTP_POST_VARS = $_POST;

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	//Setup a no search mode on first access
	if(isset($_GET['searchmode'])){
	$theSearchMode = rtrim($_GET['searchmode']);
	$_SESSION['uzuriweb_webprojects_search'] = $theSearchMode;
	}else{
	$theSearchMode = $_SESSION['uzuriweb_webprojects_search'];
	}
	
	if(isset($_GET['prjtype'])){
	$strPrjtype = rtrim($_GET['prjtype']);
	$_SESSION['uzuriweb_webprojects_prjtype'] = $strPrjtype;
	}else{
	$strPrjtype = $_SESSION['uzuriweb_webprojects_prjtype'];
	}
		//Get project type name
		$resultprj = mysql_query("SELECT servicename FROM wbm_webservices WHERE ID=$strPrjtype") or die(mysql_error());
		$rowprj = mysql_fetch_array($resultprj);
		$strServName = $rowprj['servicename'];
	//End Setup a project type
	
	$uzuriwebpgname="webprojects_mgr";
	$strPath = "";
	$strTitle = 'Manage '.$strServName.' Projects';
	
	$strID = rtrim($_GET['ID']);
	$strMode = rtrim($_GET['mode']);
	if ($strMode==""){
	$_SESSION['uzuriweb_wbm_modes']="view";
	}
	if ($strMode=="new"){
	$_SESSION['uzuriweb_wbm_modes']="new";
	}
	elseif ($strMode=="edit"){
	$_SESSION['uzuriweb_wbm_modes']="edit";
	}
	elseif ($strMode=="delete"){
	$_SESSION['uzuriweb_wbm_modes']="delete";
	}
	elseif ($strMode=="activate"){
	$_SESSION['uzuriweb_wbm_modes']="activate";
	}
	//End of Setting action modes
	
	if ((isset($_GET['search']) && ($_GET['search'])=='y')){
	    $_SESSION['uzuriweb_webprojects_search']="Search";
		setcookie("searchvalue", preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["searchtxt"]))), time()+3600);
		setcookie("columnvalue", preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["columnname"]))), time()+3600);
		header('Location: webprojects_mgr.php');
	}
	
	include('includes/wbm_header.php');
	?>
	<link rel="stylesheet" title="Style CSS" href="scripts/cwcalendar.css" type="text/css" media="all" />
	<script type="text/javascript">
		var formatSplitter = "-";
		var monthFormat = "mm";
		var yearFormat = "yyyy";
		var formatType = yearFormat+formatSplitter+monthFormat+formatSplitter+"dd";
	</script>
	<script type="text/javascript" src="scripts/calendar.js"></script>

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
              <td width="63%" valign="top" class="breadcrumbs"><strong>YOU ARE HERE:</strong> <a href="dashboard.php">Dashboard</a>&nbsp;->&nbsp;Manage Web Projects</td>
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
        <?php include("sidebars/project-management.php")?>
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
				<?
				$strAction=$_SESSION['uzuriweb_wbm_modes'];
				if ($strAction=="view"){
				LoadRecords();
				}
				elseif ($strAction=="new"){
				AddRecord("new");
				}
				elseif ($strAction=="edit"){
				EditRecord("edit", $strID);
				}
				elseif ($strAction=="delete"){
				DeleteRecord($strID);				
				}
				elseif ($strAction=="activate"){
				ActivateRecord($strID);				
				}
				// Process forms and assign to relevant function
				if ($_POST['action'] == "new") {
					ProcessAddForm($HTTP_POST_VARS);												
				}
				elseif ($_POST['action'] == "edit") {
					ProcessEditForm($HTTP_POST_VARS);												
				}
				?>
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