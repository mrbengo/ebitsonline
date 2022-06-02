<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_salesinvoices_search'];
$_SESSION['uzuriweb_wbm_modes'];

//Include common files
include('includes/wbm_common.php');
include('functions/salesinvoices_fns.php');

$HTTP_POST_VARS = $_POST;

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{

	//Setup a no search mode on first access
	$theSearchMode = rtrim($_GET['searchmode']);
	if ($theSearchMode=="none") {
	$_SESSION['uzuriweb_salesinvoices_search']="Nosearch";
	header('Location: salesinvoices_mgr.php');
	}
	
	$uzuriwebpgname="salesinvoices_mgr";
	$strPath = "";
	$strTitle = 'Manage Invoices';
	
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
	    $_SESSION['uzuriweb_salesinvoices_search']="Search";
		setcookie("searchvalue", preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["searchtxt"]))), time()+3600);
		setcookie("columnvalue", preg_replace('/\'/', "''", strip_tags(htmlspecialchars($_POST["columnname"]))), time()+3600);
		header('Location: salesinvoices_mgr.php');
	}
	
	include('includes/wbm_header.php');
	?>
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
              <td width="63%" valign="top" class="breadcrumbs"><strong>YOU ARE HERE:</strong> <a href="dashboard.php">Dashboard</a>&nbsp;->&nbsp;Manage Invoices</td>
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
        <?php include("sidebars/finance-management.php")?>
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
              </tr>
              <tr>
                <td width="53%" class="titletx">
				<form name="form4" method="post" action="salesinvoices_mgr.php?search=y">
                    <table width="75%"  border="0" align="left" cellpadding="2" cellspacing="2" class="inputboxs">
                      <tr>
                        <td width="63%" class="inputtxt"><input name="searchtxt" type="text" id="searchtxt" style="width: 150px;"></td>
                        <td width="63%" class="inputtxt">
						<select name="columnname" class="searchtxt">
						<option value="company">Company Name</option> 	
						<option value="fname">First Name</option> 	
						<option value="lname">Last Name</option> 	
						<option value="email">Email Address</option>
						<option value="cellphone">Mobile Number</option> 	
						<option value="website">Website</option>
                        </select>					    
						</td>
                        <td width="37%" colspan="2" class="inputtxt">
						<input type="submit" name="Submit" value="Search">
                        <input type="hidden" name="PHM_Search" value="form4">						
						</td>
                        <td width="37%" class="inputtxt"><a href="salesinvoices_mgr.php?searchmode=none"><img src="images/refresh.png" width="23" height="23" title="Refresh" border="0" /></a></td>
                      </tr>
                    </table>
                </form></td>
                <td width="47%"><table width="246"  border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="13" class="inputheader">LEGEND&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td width="13" class="frmLegendtxt"><img src="images/edit.png" width="12" height="13" vspace="2" border="0" /></td>
                    <td width="31" class="frmLegendtxt">Edit</td>
                    <td width="11" class="frmLegendtxt"><img src="images/delete.gif" width="11" height="11" vspace="2" border="0" /></td>
                    <td width="47" class="frmLegendtxt">Delete</td>
                    <td width="16" class="frmLegendtxt"><img src="images/activate.gif" width="16" height="15" vspace="2" border="0" /></td>
                    <td width="64" class="frmLegendtxt">Activate</td>
                    <td width="64" class="frmLegendtxt">&nbsp;</td>
                  </tr>
                </table></td>
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