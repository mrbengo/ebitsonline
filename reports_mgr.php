<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_reports_search'];
$_SESSION['uzuriweb_wbm_modes'];

//Include common files
include('includes/wbm_common.php');
include('functions/reports_fns.php');

$HTTP_POST_VARS = $_POST;

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	
	$uzuriwebpgname="reports_mgr";
	$strPath = "";
	$strTitle = 'Reports Manager';
	
	$strReportType = rtrim($_GET['rpt']);
	if ($strReportType==""){
	$_SESSION['uzuriweb_wbm_modes']="sales_leads";
	}
	if ($strReportType=="sales_leads"){
	$_SESSION['uzuriweb_wbm_modes']="sales_leads";
	}
	elseif ($strReportType=="reg_domains"){
	$_SESSION['uzuriweb_wbm_modes']="reg_domains";
	}
	elseif ($strReportType=="hosting_acc"){
	$_SESSION['uzuriweb_wbm_modes']="hosting_acc";
	}
	elseif ($strReportType=="email_marketing"){
	$_SESSION['uzuriweb_wbm_modes']="email_marketing";
	}
	elseif ($strReportType=="projects"){
	$_SESSION['uzuriweb_wbm_modes']="projects";
	}
	elseif ($strReportType=="project_team"){
	$_SESSION['uzuriweb_wbm_modes']="project_team";
	}
	elseif ($strReportType=="income"){
	$_SESSION['uzuriweb_wbm_modes']="income";
	}
	elseif ($strReportType=="expenses"){
	$_SESSION['uzuriweb_wbm_modes']="expenses";
	}
	elseif ($strReportType=="invoices"){
	$_SESSION['uzuriweb_wbm_modes']="invoices";
	}
	elseif ($strReportType=="cheques"){
	$_SESSION['uzuriweb_wbm_modes']="cheques";
	}
	//End of Setting report action modes
	
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
              <td width="63%" valign="top" class="breadcrumbs"><strong>YOU ARE HERE:</strong> <a href="dashboard.php">Dashboard</a>&nbsp;->&nbsp;Reports</td>
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
        <?php include("sidebars/reports-management.php")?>
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
				if ($strAction=="sales_leads"){
				  sales_leads();
				}
				elseif ($strAction=="reg_domains"){
				  reg_domains();
				}
				elseif ($strAction=="hosting_acc"){
				  hosting_acc();
				}
				elseif ($strAction=="email_marketing"){
				  email_marketing();
				}
				elseif ($strAction=="projects"){
				  projects();
				}
				elseif ($strAction=="project_team"){
				  project_team();
				}
				elseif ($strAction=="income"){
				  income();
				}
				elseif ($strAction=="expenses"){
				  expenses();
				}
				elseif ($strAction=="invoices"){
				  invoices();
				}
				elseif ($strAction=="cheques"){
				  cheques();
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